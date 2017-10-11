{if $_column->isFieldInRecord()}
        <input type="text"  class="form-control" data-method="{$_column->getOption('method')}" value="{$_column->getFilterValue()}">
{/if}