<div class="field select">
    <select name="{$_column->getFormColumnName()}" class="field select">
        {foreach $_column->getMultioptions() as $key => $value}
            <option value="{$key}"{if (string)$key === $_column->getFilterValue()} selected{/if}>{$value}</option>
        {/foreach}
    </select>
</div>
