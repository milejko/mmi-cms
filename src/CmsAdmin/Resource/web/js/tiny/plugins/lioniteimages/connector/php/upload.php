<?php
require_once(dirname(__FILE__) . '/config.php');
if(isAuth()) {
		
	require('UploadHandler.php');
		
	$upload_handler = new UploadHandler(array(
		'upload_url' => $imageURL,
		'upload_dir' => $uploadPath,
		'image_versions' => array(

			'thumbnail' => array(
				'crop' => true,
				'max_width' => 100,
				'max_height' => 100
			)
		)
	),false);
	$response = $upload_handler -> post(false);
	foreach($response['files'] as $file) {
		ob_start();
		$src = $file -> url;
		$thumb = $file -> thumbnailUrl;
		include('image.php');
		$file -> html = ob_get_clean();
	}

	echo json_encode($response);
}

function cleanupFilename($filename) {
	//Clean up filename
	$filename = str_replace(array('_' ,'!','@','$','%','^','&','*','(',')',"'",'`',';','<','>','"',",",'/'),'',$filename);
	$filename = strtolower(str_replace(' ','-',$filename));
	$filename = str_replace(array('--','---','----'),'-',$filename);
	$filename = urlencode($filename);
	//Add a small random suffix to prevent overwrite
	$suffix = substr(md5(mt_rand(0, 100000000)),0,8);
	$ext = substr($filename,strrpos($filename,'.') + 1);
	$filename = str_replace('.' . $ext,'-' . $suffix . '.' . $ext,$filename);
	return $filename;
}
function createthumb($file,$target,$x,$y) {
	$image = getimagesize($file);
	if(!$image) {
		return false;
	}
	switch ($image['mime'])
	{
		case 'image/gif':
			$createFunction	= 'imagecreatefromgif';
			$outputFunction		= 'imagegif';
		break;
		
		case 'image/x-png':
		case 'image/png':
			$createFunction	= 'imagecreatefrompng';
			$outputFunction		= 'imagepng';
		break;
		
		default:
			$createFunction	= 'imagecreatefromjpeg';
			$outputFunction	 	= 'imagejpeg';
		break;
	}

	$imageRsc =  $createFunction($file);
	$prevX = imageSX($imageRsc);
	$prevY = imageSY($imageRsc);
	if ($prevX > $prevY) {
		$width = $x;
		$height = $prevY * ($y/$prevX);
	} else if ($prevX < $prevY)	{
		$width=$prevX*($x/$prevY);
		$height=$y;
	}else if ($prevX == $prevY)	{
		$width=$x;
		$height=$y;
	}
	$imgRsc=imagecreatetruecolor($width,$height);
	imagecopyresampled($imgRsc,$imageRsc,0,0,0,0,$width,$height,$prevX,$prevY);
	 
	$outputFunction($imgRsc,$target);
	imagedestroy($imgRsc); 
	imagedestroy($imageRsc); 
}