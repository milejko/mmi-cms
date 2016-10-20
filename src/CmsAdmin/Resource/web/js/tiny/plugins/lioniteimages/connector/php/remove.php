<?php
require_once (dirname(__FILE__) . '/config.php');
if(!isAuth()) {
	die('Access denied');
}

if(!empty($_GET) && isset($_GET['url']) && !empty($_GET['url'])) {
	$url = $_GET['url'];
	$filename = urldecode(str_replace($imageURL, '', $url));
	$file = $uploadPath  . $filename;
	if(is_file($file)) {
		unlink($file);
		$thumb = $uploadPath . 'thumbnail/' . $filename;
		unlink($thumb);
	}
}