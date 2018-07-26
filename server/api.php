<?php
require "./config.php";
require "./core/gum.php";
gum::init([
	"headers" => [
		"Access-Control-Allow-Origin: *",
	],
]);

$path = gum::query("path");

$db = new db();

// 登录
if ($path == "/login/") {
	$password = gum::query("password");
	if ($password == PASSWORD) {
		$token = gum::uuid();
		$db->update("token", ["token" => $token]);
		gum::json(["code" => 200, "token" => $token]);
	} else {
		gum::json(["code" => 404]);
	}
}

// 帖子列表
if ($path == "/post/list/") {

	if (auth(true)) {
		$where = " 1=1";
	} else {
		$where = " status=1";
	}
	// 获取总数
	$count = $db->count("SELECT id FROM post WHERE $where");
	if ($count > 0) {
		$page = gum::query("page", 1);
		$pagesize = 100;
		$pagecount = ceil($count / $pagesize);
		$page = $page > $pagecount ? $pagecount : $page;
		$page = $page < 1 ? 1 : $page;
		// 获取ids
		$sql = "SELECT id FROM post WHERE $where ORDER BY id DESC  LIMIT " . ((intval($page) - 1) * $pagesize) . "," . $pagesize;
		$rows = $db->rows($sql);
		if (count($rows) > 0) {
			$ids = [];
			foreach ($rows as $key => $value) {
				$ids[] = $value["id"];
			}
			$sql = "SELECT id,title,view,time FROM post WHERE id IN (" . implode(",", $ids) . ") ORDER BY id DESC";
			$rows = $db->rows($sql);

		}
	} else {
		$rows = [];
	}
	gum::json([
		"code" => 200,
		"result" => [
			"count" => $count,
			"data" => $rows,
		],
	]);
}

// 帖子内容
if ($path == "/post/info/") {
	$id = gum::query("id");
	$post = $db->row("SELECT * FROM post WHERE id=:id",[":id"=>$id]);
	if (!$post) {
		gum::json(["code" => 404]);
	}
	$photos = $db->rows("SELECT id,path,description FROM post_photo WHERE post_id=:id ORDER BY sort ASC",[":id"=>$id]);
	$db->exec("UPDATE post SET view=view+1 WHERE id=" . $id);
	gum::json(["code" => 200, "result" => $post, "photos" => $photos]);
}

// 帖子图片上传
if ($path == "/post/upload/") {
	auth();
	if (isset($_FILES["file"]["name"])) {
		$result = file::upload([
			"upload" => $_FILES["file"],
			"target" => "./post/" . date("Ym") . "/",
		]);
		if ($result != '') {
			$action=$db->insert("post_photo", [
				"path" => $result["path"],
				"description" => "",
				"sort" => 0,
				"post_id" => 0,
			]);
			$id=$db->id();
			if($id==0){
				gum::json(["code" => 501,"msg"=>$action]);
			}else{
				gum::json([
					"code" => 200,
					"result" => [
						"id" => $id,
						"path" => $result["path"],
					],
				]);
			}

		} else {
			gum::json(["code" => 404]);
		}
	}else{
		gum::json(["code" => 500]);
	}
}

// 帖子保存
if ($path == "/post/save/") {
	auth();
	$id = gum::query("id");
	$image = gum::query("image");
	$title = gum::query("title");
	$content = gum::query("content");
	$status = gum::query("status");

	if ($title == "" || $content == "") {
		gum::json(["code" => 400]);
	}
	$action = false;
	$data = [
		"image" => $image,
		"title" => $title,
		"content" => $content,
		"status" => $status,
	];
	if ($id == "") {
		$data["time"] = time();
		$db->insert("post", $data);
		$id = $db->id();
	} else {
		$db->update("post", $data, "id=$id");
	}
	$photos = gum::query("photos");
	if ($photos != "") {
		$photos = json_decode($photos,true);
 		// print_r($photos);
		foreach ($photos as $key => $value) {
 
			$db->update("post_photo", [
				"sort" => $key,
				"description" => $value["description"],
				"post_id" => $id,
			], "id=" . $value["id"]);
		}
	}
	gum::json(["code" => 200]);
}

// 帖子删除
if ($path == "/post/delete/") {
	auth();
	$id = gum::query("id");
	if ($id == "") {
		gum::json(["code" => 400]);
	}
	// 删除物理文件
	$photos = $db->rows("SELECT path FROM post_photo WHERE post_id=" . $id);
	foreach ($photos as $row) {
		file::delete($row["path"]);
	}
	// 删除图片记录
	$db->delete("post_photo", "post_id=" . $id);
	// 删除帖子
	$success = $db->delete("post", "id=" . $id);
	if ($success) {
		gum::json(["code" => 200]);
	} else {
		gum::json(["code" => 404]);
	}
}

// 帖子图片删除
if ($path == "/post/delete-photo/") {
	auth();
	$id = gum::query("id");
	if ($id == "") {
		gum::json(["code" => 400]);
	}
	$photo = $db->row("SELECT path FROM post_photo WHERE post_id=:id",[":id"=>$id]);
	// 删除图片记录
	$success = $db->delete("post_photo", "id=" . $id);
	if ($success) {
		file::delete($photo["path"]);
		gum::json(["code" => 200]);
	} else {
		gum::json(["code" => 404]);
	}
}

// 音乐列表
if ($path == "/music/") {
	$rows = $db->rows("SELECT * FROM music WHERE status=1 ORDER BY id DESC");
	gum::json(["code" => 200, "result" => $rows]);
}

// 音乐保存
if ($path == "/music/save/") {
	auth();
	$id = gum::query("id");
	$name = gum::query("name");
	$url = gum::query("url");
	$status = gum::query("status", 1);

	if ($name == "" || $url == "") {
		gum::json(["code" => 400]);
	}

	if ($id == "") {
		$success = $db->insert("music", [
			"name" => $name,
			"url" => $url,
			"status" => $status,
		]);
		$id = $db->id();
	} else {
		$success = $db->update("music", [
			"name" => $name,
			"url" => $url,
			"status" => $status,
		], "id=$id");
	}

	if ($success) {
		gum::json(["code" => 200, "id" => $id]);
	} else {
		gum::json(["code" => 404]);
	}
}

// 删除音乐
if ($path == "/music/delete/") {
	auth();
	$id = gum::query("id");
	if ($id == "") {
		gum::json(["code" => 400]);
	}
	$success = $db->delete("music", "id=" . $id);
	if ($success) {
		gum::json(["code" => 200]);
	} else {
		gum::json(["code" => 404]);
	}
}

// 发送邮件
if ($path == "/sendmail/") {
	$name = gum::query("name");
	$email = gum::query("email");
	$content = gum::query("content");
	if ($name == "" || $email == "" || $content == "") {
		gum::json(["code" => 400]);
	}
	$html = $name . "<br>" . $email . "<br>" . $content;

	$success = gum::mail([
		"to" => SMTP_MAIL,
		"subject" => "收到" . $name . "一条留言信息",
		"body" => $html,
		"from" => SMTP_USER,
		"server" => SMTP_SERVER,
		"port" => SMTP_PORT,
		"user" => SMTP_USER,
		"password" => SMTP_PASSWORD,
	]);
	if ($success) {
		gum::json(["code" => 200]);
	} else {
		gum::json(["code" => 404]);
	}
}

function auth($isBool = false) {
	global $db;
	$token = $db->row("SELECT token FROM token")['token']; //读取数据库
	if (gum::query("token") != $token) {
		if ($isBool == false) {
			gum::json(["code" => 403]);
		}
		return false;
	} else {
		return true;
	}
}