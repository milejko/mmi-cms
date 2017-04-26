<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>{navigation()->title()}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        {headLink()->appendStyleSheet('/resource/cmsAdmin/css/grid-bootstrap.css')}
        {headLink()->appendStyleSheet('/resource/cmsAdmin/css/kickstart.css')}
        {headLink()->appendStyleSheet('/resource/cmsAdmin/css/style.css')}
        {headLink()}
        {headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js')}
        {headScript()->appendFile('/resource/cmsAdmin/js/kickstart.js')}
        {headScript()->appendFile('/resource/cmsAdmin/js/form.js')}
        {headScript()->appendFile('/resource/cmsAdmin/js/default.js')}
        {headScript()}
    </head>
    <body>