<select name="{$_column->getFormColumnName()}" class="form-control grid-filter" {if $_column->getOption('chosen')}data-chosen="true"{/if}>
    {foreach $_column->getMultioptions() as $key => $value}
        <option value="{$key}"{if (string)$key === $_column->getFilterValue()} selected{/if}>{_($value)}</option>
    {/foreach}
</select>
