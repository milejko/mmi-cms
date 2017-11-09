{if $_value == $_truncated}
    {$_value}
{else}
    <span title="{$_value}">
        {$_truncated}
    </span>
{/if}
