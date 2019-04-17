<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.index.index.header#}</strong>
                    </div>
                    {if $user}
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">{#template.index.index.id#}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">{$user->id}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">{#template.index.index.login#}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">{$user->username} (<a href="{@module=cmsAdmin&controller=index&action=logout@}">{#template.index.index.logout#}</a>)</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">{#template.index.index.email#}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">{$user->email}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">{#template.index.index.failLogin#}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">{$user->failLogCount}</p>
                                    <p class="form-control-static">{$user->lastFailLog}<br />[{$user->lastFailIp}]</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">{#template.index.index.lastLogin#}</label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">{$user->lastLog}<br />[{$user->lastIp}]</p>
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