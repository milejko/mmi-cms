{headLink()->appendStylesheet('/resource/cmsAdmin/css/datetimepicker.css')}
{headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js')}
{headScript()->appendFile('/resource/cmsAdmin/js/jquery/datetimepicker.js')}
{if $_column->isFieldInRecord()}
    <div class="field text date-time">
        od<input type="datetime"
                 name="{$_column->getFormColumnName()}"
                 id="{$_column->getFormColumnName()}-from"
                 class="field text from datePickerField"
                 data-method="{$_column->getMethod()}"
                 autocomplete="off"
                 value="{$_column->getFilterValue('from')}">
        do<input type="datetime"
                 name="{$_column->getFormColumnName()}"
                 id="{$_column->getFormColumnName()}-to"
                 class="field text to datePickerField"
                 data-method="{$_column->getMethod()}"
                 autocomplete="off"
                 value="{$_column->getFilterValue('to')}">
    </div>
    <script>
        $(document).ready(function () {
            $(".datePickerField").datetimepicker({
                allowBlank: true,
                scrollInput: false,
                scrollMonth: false,
                step: 15,
                minDate: false,
                maxDate: false,
                datepicker: true,
                timepicker: true,
                format: 'Y-m-d H:i',
                validateOnBlur: true
            });
            $.datetimepicker.setLocale('pl');
        });
    </script>
{/if}
