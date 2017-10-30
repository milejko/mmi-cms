{if $ldap}
    {headScript()->appendFile('/resource/cmsAdmin/js/auth.js')}
{/if}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#Dodawanie#}{else}{#Edycja#}{/if} {#użytkownika CMS#}</strong>
                    </div>
                    <div class="card-body">
                        {$authForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>