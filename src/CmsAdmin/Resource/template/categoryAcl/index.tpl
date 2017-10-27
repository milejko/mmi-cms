
{headScript()->appendFile('/resource/cmsAdmin/js/acl.js')}


<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Uprawnienia edycji kategorii#}</strong>
                    </div>
                    <div class="card-body">
                        {if $roles|count}
                            <div class="tabs btn-group" id="roles-list">
                                {foreach name="roles" $roles as $role}
                                    <a class="btn btn-outline-primary{if $request->roleId && $request->roleId == $role->id} active{/if}" href="{@module=cmsAdmin&controller=categoryAcl&action=index&roleId={$role->id}@}">{$role->name}</a>
                                {/foreach}
                            </div>
                            <div id="rules">
                                <div class="tab-content tab-aclForm">
                                    {$categoryAclForm}
                                </div>
                            {else}
                                <p>Brak zdefiniowanych ról.</p>
                            </div>
                        {/if}
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>
