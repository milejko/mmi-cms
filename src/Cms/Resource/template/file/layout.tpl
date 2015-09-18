<!DOCTYPE html>
<html>
	<head>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/jquery/ui.js')}
		{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/uploader.js')}
		{if $ajaxParams['js']}
			{headScript()->appendFile($baseUrl . $ajaxParams['js'])}
		{/if}
		{headLink()->prependStylesheet($baseUrl . '/resource/cmsAdmin/css/uploader.css')}
		{headLink()}
		{headScript()}
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" />
	</head>	
	<body>
		<div id="component">
			{content()}
		</div>
	</body>
</html>