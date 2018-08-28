var CMS = CMS ? CMS : {};

CMS.grid = function () {
    "use strict";

    var inputCursorPosition = 0,
        inputName,
        inputBuffer,
        filtering = false;

    var quickSwitch = function(data) {
        $('.grid-anchor').html(data.body);
        $('.paginator-anchor').html(data.paginator);
        initPaginator();
        initPicker();
    };

    var rememberCursor = function(object) {
        inputBuffer = object.val();
        inputCursorPosition = object[0].selectionStart;
        inputName = object.attr('name');
    };

    var initPicker = function () {
        $('.grid-picker').datetimepicker({format:'Y-m-d', allowBlank: true, scrollInput: false, scrollMonth: true, timepicker: false});
        $.datetimepicker.setLocale('pl');
    };

    var filter = function(field) {
        var filter = field.attr('name'),
            value = field.val();
        filtering = true;
        field.parent('div').parent('th').parent('tr').parent('thead').find('input.grid-picker').prop('disabled', true);
        $.ajax({
            url: window.location,
            type: 'POST',
            data: {filter: filter, value: value},
            success: function (data) {
                quickSwitch(data);
                if (!inputName) {
                    filtering = false;
                    return;
                }
                var element = $('input[name=\'' + inputName + '\']');
                element.focus();
                element.val(inputBuffer);
                try {
                    element[0].setSelectionRange(inputCursorPosition, inputCursorPosition);
                } catch (e) {}
                inputName = null;
                inputBuffer = null;
                filtering = false;
                return true;
            }
        });
    };

    var initPaginator = function () {
        if(!$('.paginator-anchor ul.pagination > li.page-item > a').length) {
            return;
        }
        var filter = $('ul.pagination').data('name'),
            value = $(this).data('page');
        $.ajax({
            url: window.location,
            type: 'POST',
            data: {filter: filter, value: value},
            success: function (data) {
                quickSwitch(data);
            }
        });
    };

    var initGridFilter = function () {
        $('table.grid-anchor').on('keyup', ".grid-filter", function (event) {
            rememberCursor($(this));
        });

        $('table.grid-anchor').on('change', ".grid-range > input", function () {
            var from = $(this).parent('div.input-group').children('input.from').val();
            var to = $(this).parent('div.input-group').children('input.to').val();
            $(this).parent('div.input-group').parent('div.form-group').children('input.hidden').val(from + ';' + to);
            if (!filtering) {
                filter($(this).parent('div.input-group').parent('div.form-group').children('input.hidden'));
            }
        });

        $('table.grid-anchor').on('change', ".grid-filter", function () {
            if (!filtering) {
                filter($(this));
            }
        });

        $('table.grid-anchor').on('click', ".grid-filter", function () {
            rememberCursor($(this));
        });

        $('table.grid-anchor').on('focus', "a.order", function () {
            inputName = null;
        });

    };

    var initGridOrder = function () {
        //sortowanie grida
        $('table.grid-anchor').on('click', 'th > div.form-group > a.order', function () {
            var field = $(this).attr('href'),
                method = $(this).attr('data-method');
            $.ajax({
                url: window.location,
                type: 'POST',
                data: {order: field, method: method},
                success: function (data) {
                    quickSwitch(data);
                }
            });
            return false;
        });
    };

    var initGridOperation = function () {
        //akcja na zmianie checkboxa
        $('table.grid-anchor').on('change', 'td > label.control-checkbox > input.checkbox', function () {
            var id = $(this).attr('id').split('-');
            $.ajax({
                url: window.location,
                type: 'POST',
                data: {id: id[1], name: id[0], value: $(this).val(), checked: $(this).is(':checked')}
            });
        });
    };

    initGridFilter();
    initGridOrder();
    initGridOperation();
    initPaginator();
    initPicker();
};

$(document).ready(function () {
    "use strict";
    CMS.grid();
});
