<?php
define('VERSION', 'v0.0.10 20180404');

define('ROOT', str_replace("core", "", dirname(__FILE__)));

require "check.php";
require "format.php";
require "file.php";
require "db.php";
require "model.php";

// 入口类
class gum {
	// 初始化
	public static function start() {
		$class = gum::query("class");
		if ($class == "") {
			gum::json(["code" => 500]);
		}
		$file = ROOT . "service/" . $class . ".php";
		if (file::has($file)) {
			require $file;
		}
		call_user_func($class . '::init');
	}

	// 初始化页面
	public static function init($options = array()) {
		// php 5.3以上版本
		if (version_compare(PHP_VERSION, '5.3.0', '<')) {
			die('PHP version must be higher then v5.3.0.');
		}
		// 容错
		error_reporting(DEBUG ? E_ALL : 0);

		// 设置时间区域
		date_default_timezone_set(TIMEZONE);

		// SESSION开启
		if (isset($options['session'])) {
			@session_start();
			if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
				@ini_set('session.lazy_write', 0);
				@ini_set('session.use_trans_sid', 1);
			}
		}

		header("X-Powered-By:Gum " . VERSION);

		if (isset($options['headers'])) {
			foreach ($options['headers'] as $item) {
				header($item);
			}
		} else {
			header("content-type: text/html;charset=utf-8");
		}

		// 过滤请求参数
		if (count($_REQUEST) > 0) {
			foreach ($_REQUEST as $key => $value) {
				$_REQUEST[$key] = htmlentities($value, ENT_QUOTES, "UTF-8");
			}
		}

		// 开启路由支持
		if (isset($options['bind'])) {
			$method = gum::query("method");
			method_exists($options['bind'], $method) && call_user_func([$options['bind'], $method]);
		}
	}

	/**
	 * 获取参数
	 * @param   string  $name    get或者post
	 * @return  string
	 */
	public static function query($name = "", $default = false) {
		if ($name == "") {
			return $_SERVER["QUERY_STRING"];
		}
		$_REQUEST = array_merge($_GET, $_POST);
		// var_dump($_REQUEST);
		$name = trim($name);
		$action = isset($_REQUEST[$name]) ? $_REQUEST[$name] : ($default == false ? "" : $default);
		return $action;
	}

	/**
	 * 模板解析
	 * @param   string  $name    get或者post
	 * @return  string
	 */
	public static function template($content, $data) {
		// {{var}}
		// {{if var} ... {{elseif var}} ... {{else}}... {{/if}}
		// {{for $items}} {{/for}}
		return $data;
	}

	/**
	 * 加密函数
	 * @param   string  $str    加密前的字符串
	 * @param   string  $key    密钥
	 * @return  string  加密后的字符串
	 */
	public static function encode($str, $key = KEY) {
		$tmp = '';
		$keylength = strlen($key);
		for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength) {
			$tmp .= substr($str, $i, $keylength) ^ $key;
		}
		return str_replace('=', '', base64_encode($tmp));
	}

	/**
	 * 解密函数
	 * @param   string  $str    加密后的字符串
	 * @param   string  $key    密钥
	 * @return  string  加密前的字符串
	 */
	public static function decode($str, $key = KEY) {
		$tmp = '';
		$keylength = strlen($key);
		$str = base64_decode($str);
		for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength) {
			$tmp .= substr($str, $i, $keylength) ^ $key;
		}
		return $tmp;
	}

	/**
	 * 截取字符串
	 *
	 * @param	string	$string		要截取的字符串
	 * @param	integer	$length		要截取的字数
	 * @param	boolean	$append		是否打印省略号移
	 * @return	string
	 */
	public static function cut($string, $length, $append = true) {
		$string = trim($string);
		$strlength = strlen($string);
		if ($length == 0 || $length >= $strlength) {
			return $string;
		} elseif ($length < 0) {
			$length = $strlength + $length;
			if ($length < 0) {
				$length = $strlength;
			}
		}
		if (function_exists('mb_substr')) {
			$newstr = mb_substr($string, 0, $length, "UTF-8");
		} elseif (function_exists('iconv_substr')) {
			$newstr = iconv_substr($string, 0, $length, "UTF-8");
		} else {
			for ($i = 0; $i < $length; $i++) {
				$tempstring = substr($string, 0, 1);
				if (ord($tempstring) > 127) {
					$i++;
					if ($i < $length) {
						$newstring[] = substr($string, 0, 3);
						$string = substr($string, 3);
					}
				} else {
					$newstring[] = substr($string, 0, 1);
					$string = substr($string, 1);
				}
			}
			$newstr = join($newstring);
		}
		if ($append && $string != $newstr) {
			$newstr .= '...';
		}
		return $newstr;
	}

	/**
	 * 创建SQL IN语法
	 *
	 * @param array $item_list		数组
	 * @param string $field_name	字段
	 * @return	string
	 */
	public static function sqlIn($item_list, $field_name = '') {
		if (empty($item_list)) {
			return $field_name . " IN ('') ";
		} else {
			if (!is_array($item_list)) {
				$item_list = explode(',', $item_list);
			}
			$item_list = array_unique($item_list);
			$item_list_tmp = '';
			foreach ($item_list AS $item) {
				if ($item !== '') {
					$item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
				}
			}
			if (empty($item_list_tmp)) {
				return $field_name . " IN ('') ";
			} else {
				return $field_name . ' IN (' . $item_list_tmp . ') ';
			}
		}
	}

	// 发送邮件
	public static function mail($options) {
		if (!isset($options['charset'])) {
			$options['charset'] = "utf-8";
		}
		if (!isset($options['auth'])) {
			$options['auth'] = 1;
		}
		$options['subject'] = '=?' . $options['charset'] . '?B?' . base64_encode($options['subject']) . '?=';
		$options['body'] = chunk_split(base64_encode(preg_replace("/(^|(\r\n))(\.)/", "\1.\3", $options['body'])));

		$headers = "";
		$headers .= "MIME-Version:1.0\r\n";
		$headers .= "Content-type:text/html\r\n";
		$headers .= "Content-Transfer-Encoding: base64\r\n";
		$headers .= "From: " . $options['from'] . "\r\n";
		$headers .= "Date: " . date("r") . "\r\n";
		list($msec, $sec) = explode(" ", microtime());
		$headers .= "Message-ID: <" . date("YmdHis", $sec) . "." . ($msec * 1000000) . "." . $options['from'] . ">\r\n";

		if (!$fp = fsockopen($options['server'], $options['port'], $errno, $errstr, 30)) {
			return "CONNECT - Unable to connect to the SMTP server";
		}

		stream_set_blocking($fp, true);

		$message = fgets($fp, 512);
		if (substr($message, 0, 3) != '220') {
			return "CONNECT - " . $message;
		}

		fputs($fp, ($options['auth'] ? 'EHLO' : 'HELO') . " befen\r\n");
		$message = fgets($fp, 512);
		if (substr($message, 0, 3) != 220 && substr($message, 0, 3) != 250) {
			return "HELO/EHLO - " . $message;
		}

		while (1) {
			if (substr($message, 3, 1) != '-' || empty($message)) {
				break;
			}
			$message = fgets($fp, 512);
		}

		if ($options['auth']) {
			fputs($fp, "AUTH LOGIN\r\n");
			$message = fgets($fp, 512);
			if (substr($message, 0, 3) != 334) {
				return $message;
			}

			fputs($fp, base64_encode($options['user']) . "\r\n");
			$message = fgets($fp, 512);
			if (substr($message, 0, 3) != 334) {
				return "AUTH LOGIN - " . $message;
			}

			fputs($fp, base64_encode($options['password']) . "\r\n");
			$message = fgets($fp, 512);
			if (substr($message, 0, 3) != 235) {
				return "AUTH LOGIN - " . $message;
			}
		}

		fputs($fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $options['from']) . ">\r\n");
		$message = fgets($fp, 512);
		if (substr($message, 0, 3) != 250) {
			fputs($fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $options['from']) . ">\r\n");
			$message = fgets($fp, 512);
			if (substr($message, 0, 3) != 250) {
				return "MAIL FROM - " . $message;
			}
		}
		foreach (explode(',', $options['to']) as $touser) {
			$touser = trim($touser);
			if ($touser) {
				fputs($fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser) . ">\r\n");
				$message = fgets($fp, 512);
				if (substr($message, 0, 3) != 250) {
					fputs($fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser) . ">\r\n");
					$message = fgets($fp, 512);
					return "RCPT TO - " . $message;
				}
			}
		}
		fputs($fp, "DATA\r\n");
		$message = fgets($fp, 512);
		if (substr($message, 0, 3) != 354) {
			return "DATA - " . $message;
		}
		fputs($fp, $headers);
		fputs($fp, "To: " . $options['to'] . "\r\n");
		fputs($fp, "Subject: " . $options['subject'] . "\r\n");
		fputs($fp, "\r\n\r\n");
		fputs($fp, $options['body'] . "\r\n.\r\n");
		$message = fgets($fp, 512);
		if (substr($message, 0, 3) != 250) {
			return "END - " . $message;
		}
		fputs($fp, "QUIT\r\n");
		return true;
	}

	/**
	 * 获取随机字符
	 *
	 * @param int $length		长度
	 * @return	string
	 */
	public static function randomCode($length = 6) {
		echo substr(str_shuffle("012345678901234567890123456789"), 0, $length);
	}

	/**
	 * 生成uuid
	 *
	 * @param string $prefix		前缀
	 * @return	string
	 */
	public static function uuid($prefix = "") {
		$str = md5(uniqid(mt_rand(), true));
		$uuid = substr($str, 0, 8) . '-';
		$uuid .= substr($str, 8, 4) . '-';
		$uuid .= substr($str, 12, 4) . '-';
		$uuid .= substr($str, 16, 4) . '-';
		$uuid .= substr($str, 20, 12);
		return $prefix . $uuid;
	}

	/**
	 * JSON输出
	 *
	 * @param array $data 数组
	 * @param defined $mode 常量
	 * @return	void
	 */
	public static function json($data, $mode = JSON_UNESCAPED_UNICODE) {
		header("content-type: application/json;charset=utf-8");
		echo json_encode($data, $mode);
		die();
	}

	/**
	 * 获取远程内容
	 *
	 * @param string $url 网址
	 * @return	string
	 */
	public static function fetch($url) {
		set_time_limit(0);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
		if (empty($result)) {
			$result = false;
		}
		return $result;
	}

	/**
	 * 获取ip物理地址
	 *
	 * @param string $ip IP地址
	 * @return	string
	 */
	public static function ipAddress($ip) {
		$data = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
		$data = self::fetch($data);
		if (empty($data)) {
			return '中国';
		}
		$json = json_decode($data);
		//print_r($json);exit;
		if ($json->code != 0) {
			return 'LAN';
		} else {
			$result = array();
			if ($json->data->country != "中国") {
				$result[] = $json->data->country;
			}
			$result[] = $json->data->region;
			$result[] = $json->data->city;
			$result[] = $json->data->area;
			$result[] = " " . $json->data->isp;
			$result = implode("", $result);
			// print_r($result);exit;
			return $result;
		}
	}

	/**
	 * 获取当前路径
	 *
	 * @param bool $hasDomain 是否需要域名
	 * @return	string
	 */
	public static function path($hasDomain = false) {
		$value = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
		if ($hasDomain) {
			$value = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $value;
		}
		return $value;
	}

	/**
	 * 获取当前运行位置
	 *
	 * @return	string
	 */
	public static function base() {
		$php_self = self::path();
		$self = explode('/', $php_self);
		$self_count = count($self);
		$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
		if ($self_count > 1) {
			$url .= str_replace('/' . $self[$self_count - 1], '', $php_self);
		}
		if (substr($url, -1) != '/') {
			$url .= '/';
		}
		return $url;
	}

	/**
	 * 获取ip
	 *
	 * @return	string
	 */
	public static function ip() {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		if (self::checkIP($ip)) {
			return $ip;
		} else {
			return '0.0.0.0';
		}
	}

	/**
	 * 检查ip地址格式
	 *
	 * @return	bool
	 */
	private static function checkIP($ip) {
		if (!filter_var($ip, FILTER_VALIDATE_IP)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 获取操作系统
	 *
	 * @return	string
	 */
	public static function os($useragent = '') {
		if (empty($useragent)) {
			$useragent = $_SERVER["HTTP_USER_AGENT"];
		}
		if (strpos($useragent, "Windows NT 5.0")) {
			$os = "Windows 2000";
		} elseif (strpos($useragent, "Windows NT 5.1")) {
			$os = "Windows XP";
		} elseif (strpos($useragent, "Windows NT 5.2")) {
			$os = "Windows 2003";
		} elseif (strpos($useragent, "Windows NT 6.0")) {
			$os = "Windows Vista";
		} elseif (strpos($useragent, "Windows NT 6.1")) {
			$os = "Windows 7";
		} elseif (strpos($useragent, "Windows NT 6.2")) {
			$os = "Windows 8";
		} elseif (strpos($useragent, "Windows NT 6.3")) {
			$os = "Windows 10";
		} elseif (strpos($useragent, "Windows NT")) {
			$os = "Windows NT";
		} elseif (strpos($useragent, "Windows CE")) {
			$os = "Windows CE";
		} elseif (strpos($useragent, "ME")) {
			$os = "Windows ME";
		} elseif (strpos($useragent, "Windows 9")) {
			$os = "Windows 98";
		} elseif (strpos($useragent, "unix")) {
			$os = "Unix";
		} elseif (strpos($useragent, "linux")) {
			$os = "Linux";
		} elseif (strpos($useragent, "SunOS")) {
			$os = "SunOS";
		} elseif (strpos($useragent, "OpenBSD")) {
			$os = "OpenBSD";
		} elseif (strpos($useragent, "FreeBSD")) {
			$os = "FreeBSD";
		} elseif (strpos($useragent, "AIX")) {
			$os = "AIX";
		} elseif (strpos($useragent, "iPhone")) {
			$os = "iPhone";
		} elseif (strpos($useragent, "iPad")) {
			$os = "iPad";
		} elseif (strpos($useragent, "Mac")) {
			$os = "Mac";
		} elseif (strpos($useragent, "Android")) {
			$os = "Android";
		} else {
			$os = "Other";
		}
		return $os;
	}

	/**
	 * 获取浏览器
	 * @param string $useragent 浏览器useragent字符串
	 * @return	string
	 */
	public static function bs($useragent = '') {
		if (empty($useragent)) {
			$useragent = $_SERVER["HTTP_USER_AGENT"];
		}
		if (strpos($useragent, "Opera")) {
			$browser = "Opera";
		} elseif (strpos($useragent, "Firefox")) {
			$browser = "Firefox";
		} elseif (strpos($useragent, "Chrome")) {
			$browser = "Chrome";
		} elseif (strpos($useragent, "MSIE 6")) {
			$browser = "IE6";
		} elseif (strpos($useragent, "MSIE 7")) {
			$browser = "IE7";
		} elseif (strpos($useragent, "MSIE 8")) {
			$browser = "IE8";
		} elseif (strpos($useragent, "MSIE 9")) {
			$browser = "IE9";
		} elseif (strpos($useragent, "MSIE 10")) {
			$browser = "IE10";
		} elseif (strpos($useragent, "MSIE 11")) {
			$browser = "IE11";
		} elseif (strpos($useragent, "Safari")) {
			$browser = "Safari";
		} else {
			$browser = "Other";
		}
		return $browser;
	}

	// 检测是否合法提交（在自己的服务器提交）
	public static function referer() {
		if (empty($_SERVER['HTTP_REFERER']) || (preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) != preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))) {
			self::notFound();
		}
	}

	// 判断是否为机器人、爬虫
	public static function isSpider($useragent = '') {
		static $kw_spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';
		static $kw_browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
		$useragent = empty($useragent) ? $_SERVER['HTTP_USER_AGENT'] : $useragent;
		if (!strpos($useragent, 'http://') && preg_match("/($kw_browsers)/i", $useragent)) {
			return false;
		} elseif (preg_match("/($kw_spiders)/i", $useragent)) {
			return true;
		} else {
			return false;
		}
	}

	// 判断是否为移动设备
	public static function isMobile() {
		if (preg_match("/iPhone|Android|phone|WAP|NetFront|JAVA|Opera\sMini|UCWEB|Windows\sCE|Symbian|Series|webOS|SonyEricsson|Sony|BlackBerry|IEMobile|dopod|Nokia|samsung|PalmSource|Xda|PIEPlus|MEIZU|MIDP|CLDC/i", $_SERVER['HTTP_USER_AGENT'])) {
			return true;
		} else {
			return false;
		}
	}

	// 301永久重定向
	public static function redirect($url = './') {
		header('HTTP/1.1 301 Moved Permanently');
		Header("Location:$url");
		exit();
	}

	// 404
	public static function notFound($content = 'HTTP/1.1 404 Not Found') {
		header('HTTP/1.1 404 Not Found');
		exit($content);
	}
}