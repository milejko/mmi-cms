<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>{navigation()->title()}</title>
        {headMeta(['name' => 'description', 'content' => navigation()->description()])}
    </head>
    <body>
        {messenger()}
        {content()}
    </body>
</html>