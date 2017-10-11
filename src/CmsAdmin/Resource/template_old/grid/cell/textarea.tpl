{if $_value == $_truncated}
    <pre>{$_value}</pre>
{else}
    <pre title="{$_value}">
        {$_truncated}
    </pre>
{/if}