{if $_column->isFieldInRecord()}
    <input type="text" class="form-control grid-filter" name="{$_column->getFormColumnName()}" data-method="{$_column->getOption('method')}" value="{$_column->getFilterValue()}">
{/if}
