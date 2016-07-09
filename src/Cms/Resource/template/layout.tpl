<html>
	<head>
		<title>{navigation()->title()}</title>
		<meta name="description" content="{navigation()->description()}" />
	</head>
	<body>
		{navigation()->breadcrumbs()}
		{content()}ok
	</body>
</html>
