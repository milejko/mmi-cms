{headLink()->appendStylesheet('/resource/cmsAdmin/css/datetimepicker.css')}
{headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js')}
{headScript()->appendFile('/resource/cmsAdmin/js/jquery-ui/jquery-ui.js')}
{headScript()->appendFile('/resource/cmsAdmin/js/jquery/datetimepicker.js')}
{if $_column->isFieldInRecord()}
<div class="input-group">
    <span class="input-group-addon">od</span>
    <input class="form-control dtFrom" type="datetime"
           name="{$_column->getFormColumnName()}-from"
           data-name="{$_column->getFormColumnName()}"
           class="text from datePickerFieldFrom"
           data-method="{$_column->getMethod()}"
           autocomplete="off"
           value="{$_column->getFilterValue('from')}">
    <span class="input-group-addon">do</span>
    <input class="form-control dtTo"
           type="datetime"
           name="{$_column->getFormColumnName()}-to"
           data-name="{$_column->getFormColumnName()}"
           class="text to datePickerFieldTo"
           data-method="{$_column->getMethod()}"
           autocomplete="off"
           value="{$_column->getFilterValue('to')}">
</div>
{/if}
