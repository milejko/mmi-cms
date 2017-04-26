<!DOCTYPE html>
<html>
    <head>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        {headScript()->appendFile('/resource/cmsAdmin/js/jquery-ui/jquery-ui.min.js')}
        {headScript()->appendFile('/resource/cmsAdmin/js/uploader.js')}
        {if $ajaxParams['js']}
            {headScript()->appendFile($ajaxParams['js'])}
        {/if}
        {headLink()->prependStylesheet('/resource/cmsAdmin/css/uploader.css')}
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