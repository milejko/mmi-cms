<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>{navigation()->title()}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		{headLink()->appendStyleSheet($baseUrl . '/resource/cmsAdmin/css/grid-bootstrap.css')}
		{headLink()->appendStyleSheet($baseUrl . '/resource/cmsAdmin/css/kickstart.css')}
		{headLink()->appendStyleSheet($baseUrl . '/resource/cmsAdmin/css/style.css')}
		{headLink()}
		{headScript()->prependFile($baseUrl . '/resource/cmsAdmin/js/jquery/jquery.js')}
		{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/kickstart.js')}
		{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/form.js')}
		{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/default.js')}
		{headScript()}
	</head>
	
	
	
	
	<body>