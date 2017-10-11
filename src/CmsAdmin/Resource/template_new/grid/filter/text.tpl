{if $_column->isFieldInRecord()}
    <input type="text" class="form-control" name="{$_column->getFormColumnName()}" data-method="{$_column->getOption('method')}" value="{$_column->getFilterValue()}">
{/if}
