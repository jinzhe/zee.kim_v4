<?php
require "./config.php";
require "./core/gum.php";
gum::init();
$db  = new db();
$uri = explode("/", $_SERVER["REQUEST_URI"]);
$id  = $uri[2];
$sql = "SELECT title FROM post WHERE status=1 AND id=$id";
$row = $db->row($sql);
?>
<!DOCTYPE html><html lang=zh-CN><head><meta charset=utf-8><meta name=viewport content="width=device-width"><meta name=apple-mobile-web-app-capable content=yes><meta name=apple-mobile-web-app-status-bar-style content=black><meta name=apple-mobile-web-app-title content=<?php echo $row["tile"] ?>><meta name=format-detection content="telephone=no"><meta name=theme-color content=#fff><meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1"><link rel=icon href=/static/favicon.ico><title><?php echo $row["title"] . "-"; ?>幸福彼岸</title><meta name=description content=""><meta name=keywords content=幸福,彼岸,Zee,golang,vuejs><link href=/static/app.dc89c1328e40b299fcb91d9c6e8b6b3c.css rel=stylesheet></head><body><div id=app></div><noscript>您的浏览器必须开启Javascript才能浏览本站</noscript><script type=text/javascript src=/static/manifest.f88f3853384c4a815cb2.js></script><script type=text/javascript src=/static/vendor.606d54484aa1cf209241.js></script><script type=text/javascript src=/static/app.a788a462eeb465c0e7b0.js></script></body></html>