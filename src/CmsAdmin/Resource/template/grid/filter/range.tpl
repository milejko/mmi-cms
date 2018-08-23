{if $_column->isFieldInRecord()}
    {$_value = php_explode(';', $_column->getFilterValue())}
    <div class="input-group">
        <span class="input-group-addon">od</span>
        <input class="form-control" type="datetime"
               name="{$_column->getFormColumnName()}-from"
               data-name="{$_column->getFormColumnName()}"
               class="text"
               data-method="{$_column->getMethod()}"
               autocomplete="off"
               value="{$_value[0]}">
        <span class="input-group-addon">do</span>
        <input class="form-control"
               type="datetime"
               name="{$_column->getFormColumnName()}-to"
               data-name="{$_column->getFormColumnName()}"
               class="text"
               data-method="{$_column->getMethod()}"
               autocomplete="off"
               value="{php_isset($_value[1]) ? $_value[1] : ''}">
    </div>
    <input type="hidden" class="form-control" name="{$_column->getFormColumnName()}" data-method="{$_column->getOption('method')}" value="{$_column->getFilterValue()}">
{/if}
