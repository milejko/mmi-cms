<?php
require_once(dirname(__FILE__) . '/config.php');
if(!isAuth()) {
	die('Access denied');
}

$files = glob($uploadPath . 'thumbnail/*.{jpg,gif,jpeg,png}',GLOB_BRACE);
if(!empty($files)) {
	foreach($files as $image) : $base = urlencode(basename($image)); 
		$src = $imageURL . $base;
		$thumb = $imageURL . 'thumbnail/' . $base;
		include('image.php');
	endforeach;
}