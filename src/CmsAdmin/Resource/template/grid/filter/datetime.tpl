{headLink()->appendStylesheet('/resource/cmsAdmin/css/datetimepicker.css')}
{headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js')}
{headScript()->appendFile('/resource/cmsAdmin/js/jquery/datetimepicker.js')}
{if $_column->isFieldInRecord()}
    <div class="field text date-time">
        od<input type="datetime"
                 name="{$_column->getFormColumnName()}-from"
                 data-name="{$_column->getFormColumnName()}"
                 class="text from datePickerFieldFrom"
                 data-method="{$_column->getMethod()}"
                 autocomplete="off"
                 value="{$_column->getFilterValue('from')}">
        do<input type="datetime"
                 name="{$_column->getFormColumnName()}-to"
                 data-name="{$_column->getFormColumnName()}"
                 class="text to datePickerFieldTo"
                 data-method="{$_column->getMethod()}"
                 autocomplete="off"
                 value="{$_column->getFilterValue('to')}">
    </div>
{/if}
