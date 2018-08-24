{if $_column->isFieldInRecord()}
    {headLink()->appendStylesheet('/resource/cmsAdmin/css/datetimepicker.css')}
    {headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js')}
    {headScript()->appendFile('/resource/cmsAdmin/js/jquery/datetimepicker.js')}
    {$_value = php_explode(';', $_column->getFilterValue())}
    <div class="input-group">
        <span class="input-group-addon">od</span>
        <input class="form-control grid-picker from no-focus" type="datetime"
               name="{$_column->getFormColumnName()}-from"
               class="text"
               data-method="{$_column->getMethod()}"
               autocomplete="off"
               value="{$_value[0]}">
        <span class="input-group-addon">do</span>
        <input class="form-control grid-picker to no-focus" type="datetime"
               name="{$_column->getFormColumnName()}-to"
               class="text"
               data-method="{$_column->getMethod()}"
               autocomplete="off"
               value="{php_isset($_value[1]) ? $_value[1] : ''}">
    </div>
    <input type="hidden" class="form-control hidden" name="{$_column->getFormColumnName()}" data-method="{$_column->getOption('method')}" value="{$_column->getFilterValue()}">
{/if}
