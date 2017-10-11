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
                 value="">
        do<input type="datetime"
                 name="{$_column->getFormColumnName()}"
                 id="{$_column->getFormColumnName()}-to"
                 class="field text to datePickerField"
                 data-method="{$_column->getMethod()}"
                 autocomplete="off"
                 value="">
    </div>
    <script>
        $(document).ready(function () {
            $('#" . $this->getId() . "').datetimepicker({
                allowBlank: true, scrollInput: false, scrollMonth:false, step: 15, minDate: $dateMin, maxDate: $dateMax,
                datepicker: $datepicker, timepicker: $timepicker, format: '" . $this->getFormat() . "', validateOnBlur: true,
                onShow: function(currentTime, input) {
                    if ('" . $minFieldId . "' != '' && jQuery('#" . $minFieldId . "').val()) {
                        this.setOptions({
                            minDate: jQuery('#" . $minFieldId . "').val()
                        });
                        input.attr('data-min-date', jQuery('#" . $minFieldId . "').val());
                    }
                    if ('" . $maxFieldId . "' != '' && jQuery('#" . $maxFieldId . "').val()) {
                        this.setOptions({
                            maxDate: jQuery('#" . $maxFieldId . "').val()
                        });
                        input.attr('data-max-date', jQuery('#" . $maxFieldId . "').val());
                    }
                },
                onClose: function(currentTime, input) {
                    var inputDate = new Date(input.val()),
                        maxDate = new Date(input.attr('data-max-date')),
                        minDate = new Date(input.attr('data-min-date'));
                    if (input.attr('data-min-date') != '' && inputDate < minDate) {
                        input.val('');
                    }
                    if (input.attr('data-max-date') != '' && inputDate > maxDate) {
                        input.val('');
                    }
                }
            });
            $.datetimepicker.setLocale('pl');
        });
    </script>
{/if}
