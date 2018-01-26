<?php
define('ROOT', dirname($_SERVER['SCRIPT_FILENAME']));
require "./core/gum.php";
if (file::permissions(ROOT . "/") != 15) {
	die('温馨提示：“./” 无法写入');
}
if (file::permissions(ROOT . "/core/config.php") != 15) {
	die('温馨提示：“./core/config.php” 无法写入');
}
if ($_POST) {
	$db_host = trim($_POST['db_host']);
	$db_port = trim($_POST['db_port']);
	$db_user = trim($_POST['db_user']);
	$db_password = trim($_POST['db_password']);
	$db_name = trim($_POST['db_name']);
	$password = trim($_POST['password']);

	#数据库创建表
	$file = file_get_contents("./install.sql");
	if (!empty($file)) {
		$query = explode(";\n", $file);
		if (count($query) > 0) {
			$db = db::create("mysql", $db_name, $db_password, $db_user, $db_host, $db_port);
			foreach ($query as $sql) {
				if (!empty($sql)) {
					$db->exec($sql);
				}
			}
		}
		$content = "<?php\n";
		$content .= "define('DEBUG', false);\n";
		$content .= "define('TIMEZONE', 'PRC');\n";
		$content .= "define('KEY', 'GUM');\n\n";

		$content .= "// 数据库\n";
		$content .= "define('DB_TYPE', 'mysql');\n";
		$content .= "define('DB_HOST', '$db_host');\n";
		$content .= "define('DB_PORT', $db_port);\n";
		$content .= "define('DB_USER', '$db_user');\n";
		$content .= "define('DB_PASSWORD', '$db_password');\n";
		$content .= "define('DB_NAME', '$db_name');\n\n";

		$content .= "// 邮件发送\n";
		$content .= "define('SMTP_SERVER', 'smtp.exmail.qq.com');\n";
		$content .= "define('SMTP_PORT', 25);\n";
		$content .= "define('SMTP_USER', '');\n";
		$content .= "define('SMTP_PASSWORD', '');\n";
		$content .= "define('SMTP_MAIL', '');\n\n";

		$content .= "// 后台账号\n";
		$content .= "define('PASSWORD', '$password');\n";

		file_put_contents(ROOT . '/core/config.php', $content) or die("请检查文件 config.php 的权限是否为0777!");
		@unlink("install.php");
		@unlink("install.sql");
		die("安装成功");
	} else {
		die("请检查文件 ./install.sql 文件是否存在或者是否可读!");
	}

}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>安装</title>
<style>
*{
	box-sizing: border-box;
	outline:none;
}
html,body{
	height:100%;
	overflow: hidden;
}
form{
	height:100%;
	margin:auto;
    display: flex;
    justify-content: center; /*水平对齐*/
    align-items: center; /*垂直对齐*/
    flex-direction:column;
	width:300px;
	font-size:12px;
	text-align: center;
}
form>div{
	margin-bottom: 10px;
}

input[type='text']{

	padding:10px;
	display: block;
	width:300px;
	border-radius: 50px;
	border:1px solid #ccc;

}
button{
	width:100%;
	display: block;
	padding:8px 20px;
	border-radius: 50px;
	border:1px solid #000;
	background-color:#000;
	color:#fff;
}
</style>
<script>
function check(){
	if(confirm('确定要安装吗?')){
		return true;
	}
	return false;
}
</script>
</head>
<body>
<form onsubmit="return check()" method="post">
	<div>

		<input type="text" name="db_host" required value="localhost" placeholder="数据库主机" title="数据库主机">
	</div>
	<div>

		<input type="text" name="db_port" required value="3306" placeholder="数据库端口" title="数据库端口">
	</div>
	<div>

		<input type="text" name="db_user" required value="root" placeholder="数据库账号" title="数据库账号">
	</div>
	<div>

		<input type="text" name="db_password" required  value="123456" placeholder="数据库密码" title="数据库密码">
	</div>
	<div>

		<input type="text" name="db_name" required  value="" placeholder="数据库名称" title="数据库名称">
	</div>
	<div>

		<input type="text" id="password" required name="password"  value="" placeholder="登录密码" title="登录密码">
	</div>

		<button type="submit">开始安装</button>


</form>

</body>
</html>