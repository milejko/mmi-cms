{if $_column->isFieldInRecord()}
    <div class="field text">
        <input type="text" name="{$_column->getFormColumnName()}" class="field text" data-method="{$_column->getOption('method')}" value="{$_column->getFilterValue()}">
    </div>
{/if}
