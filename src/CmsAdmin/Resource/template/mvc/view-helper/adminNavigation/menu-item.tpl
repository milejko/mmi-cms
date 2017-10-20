{$_hasChildren = false}
{foreach $_menuItem.children as $child}
    {if !$child.disabled}{$_hasChildren = true}{break}{/if}
{/foreach}
<li class="nav-item {if $_hasChildren}nav-dropdown{/if} {if $_menuItem.active}open{/if}">
    <a class="nav-link {$_menuItem.class} {if $_hasChildren}nav-dropdown-toggle{/if}" href="{$_menuItem.uri}"><i class="{$_menuItem.icon}"></i> {$_menuItem.label}</a>
    {if $_hasChildren}
        <ul class="nav-dropdown-items">
            {$_menuItem.subMenu}
        </ul>
    {/if}
</li>