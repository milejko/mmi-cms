{if $_value == $_truncated}
    {$_value}
{else}
    <span title="{$value}">
        {$truncated}
    </span>
{/if}
