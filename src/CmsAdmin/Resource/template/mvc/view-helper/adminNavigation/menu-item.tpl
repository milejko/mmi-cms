<li class="nav-item {if php_count($_menuItem.children) > 0}nav-dropdown{/if}">
    <a class="nav-link {$_menuItem.class} {if php_count($_menuItem.children) > 0}nav-dropdown-toggle{/if}" href="{$_menuItem.uri}"><i class="icon-list"></i> {$_menuItem.label}</a>
    {if php_count($_menuItem.children) > 0}
        <ul class="nav-dropdown-items">
            {$_menuItem.subMenu}
        </ul>
    {/if}
</li>