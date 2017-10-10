<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Twoje dane#}</strong>
                    </div>
                    {if $user}
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">{#ID#}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">{$user->id}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">{#Login#}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">{$user->username} (<a href="{@module=cmsAdmin&controller=index&action=logout@}">wyloguj</a>)</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">{#E-mail#}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">{$user->email}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                {$passwordHash = $user->password|length}
                                <label class="col-sm-6 col-form-label">{#Hasło#}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">{if $passwordHash > 0}{#zakodowane#} - {$user->password|length} {#znaków#}{else}LDAP{/if} (<a href="{@module=cmsAdmin&controller=index&action=password@}">zmiana hasła</a>)</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">{#Ilość błędnych logowań#}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">{$user->failLogCount}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">{$user->lastLog}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">[{$user->lastIp}]</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">{#Data i IP ostatniego błędnego logowania#}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">{$user->lastFailLog}<br />[{$user->lastFailIp}]</p>
                                </div>
                            </div>
                        </form>
                    </div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>