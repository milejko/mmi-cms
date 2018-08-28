{if $_column->isFieldInRecord()}
    {$_value = php_explode(';', $_column->getFilterValue())}
    <div class="input-group grid-range">
        <span class="input-group-addon">od</span>
        <input class="form-control grid-picker from"
               type="datetime"
               name="{$_column->getFormColumnName()}-from"
               class="text"
               data-method="{$_column->getMethod()}"
               autocomplete="off"
               value="{$_value[0]}">
        <span class="input-group-addon">do</span>
        <input class="form-control grid-picker to"
               type="datetime"
               name="{$_column->getFormColumnName()}-to"
               class="text"
               data-method="{$_column->getMethod()}"
               autocomplete="off"
               value="{php_isset($_value[1]) ? $_value[1] : ''}">
    </div>
    <input type="hidden" class="hidden" name="{$_column->getFormColumnName()}" data-method="{$_column->getOption('method')}" value="{$_column->getFilterValue()}">
{/if}