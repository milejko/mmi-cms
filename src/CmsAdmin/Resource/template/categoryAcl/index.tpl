<div class="content-box">
    <div class="content-box-header">
        <h3>{#Uprawnienia edycji kategorii#}</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content clearfix">
        {if $roles|count}
            <ul class="tabs" id="roles-list">
                {foreach name="roles" $roles as $role}
                    <li {if $request->roleId && $request->roleId == $role->id}class="current"{/if}>
                        <a href="{@module=cmsAdmin&controller=categoryAcl&action=index&roleId={$role->id}@}">{$role->name}</a>
                    </li>
                {/foreach}
            </ul>
            <div class="tab-content">
                {$categoryAclForm}
            </div>
        {else}
            <p>Brak zdefiniowanych ról.</p>
        {/if}
    </div>
</div>