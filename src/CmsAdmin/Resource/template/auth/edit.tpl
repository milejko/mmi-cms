{if $ldap}
    {headScript()->appendFile('/resource/cmsAdmin/js/auth.js')}
    {headLink()->appendStyleSheet('/resource/cmsAdmin/js/jquery-ui/jquery-ui.min.css')}
{/if}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#template.auth.edit.header.new#}{else}{#template.auth.edit.header.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$authForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
