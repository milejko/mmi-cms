<!DOCTYPE html>
<html>
	<head>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		{headScript()->appendFile($baseUrl . '/resource/cms/js/jquery/ui.js')}
		{headScript()->appendFile($baseUrl . '/default/file/js/uploader.js')}
		{if $ajaxParams['js']}
			{headScript()->appendFile($baseUrl . $ajaxParams['js'])}
		{/if}
		{headLink()->prependStylesheet($baseUrl . '/default/file/css/uploader.css')}
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