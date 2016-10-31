<!DOCTYPE html>
<html lang="pl">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<title>{navigation()->title()}</title>
		{headMeta(['name' => 'author', 'content' => 'Nowa Era Sp. z o.o.'])}
		{headMeta(['name' => 'description', 'content' => navigation()->description()])}
		{headMeta(['name' => 'keywords', 'content' => navigation()->keywords()])}
		{headMeta(['name' => 'Resource-type', 'content' => 'Document'])}
		{headMeta()->openGraph('og:type', 'website')}
		{headMeta()->openGraph('og:locale', 'pl_PL')}
		{*headMeta()->openGraph('og:url', url([], false, true, null))*}
		{headMeta()->openGraph('og:title', navigation()->title())}
		{headMeta()->openGraph('og:description', navigation()->description())}
		{headMeta()}
		<link rel="icon" href="{$baseUrl}/resource/common/img/favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="{$baseUrl}/resource/common/img/favicon.ico" type="image/x-icon" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,800,600,700&subset=latin,latin-ext" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Ubuntu&subset=latin,latin-ext" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Bad%20Script" rel="stylesheet" type="text/css" />
		{headLink()->appendStylesheet($baseUrl . '/resource/css/main.css')}
		{headLink()}
		{headScript()->appendFile($baseUrl . '/resource/js/all.js')}		
		{headScript()}
	</head>

	<body id="{$bodyId}">
		{'common/partial/topMenu'}
        {'common/partial/navigation'}
        {'common/partial/banner'}

        {messenger()}

        {content()}

        {'common/partial/footer'}
	</body>

</html>