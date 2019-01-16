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
        $('div.grid').on('click', ".page-link", function (event) {
            var filter = $(this).parent('li').parent('ul').data('name'),
                value = $(this).data('page');
            $.ajax({
                url: window.location,
                type: 'POST',
                data: {filter: filter, value: value},
                success: function (data) {
                    quickSwitch(data);
                }
            });
        });
    };

    var initGridFilter = function () {
        $('div.grid').on('keyup', ".grid-filter", function (event) {
            rememberCursor($(this));
        });

        $('div.grid').on('change', ".grid-range > input", function () {
            var from = $(this).parent('div.input-group').children('input.from').val();
            var to = $(this).parent('div.input-group').children('input.to').val();
            $(this).parent('div.input-group').parent('div.form-group').children('input.hidden').val(from + ';' + to);
            if (!filtering) {
                filter($(this).parent('div.input-group').parent('div.form-group').children('input.hidden'));
            }
        });

        $('div.grid').on('change', ".grid-filter", function () {
            if (!filtering) {
                filter($(this));
            }
        });

        $('div.grid').on('click', ".grid-filter", function () {
            rememberCursor($(this));
        });

        $('div.grid').on('focus', "a.order", function () {
            inputName = null;
        });

    };

    var initGridOrder = function () {
        //sortowanie grida
        $('div.grid').on('click', 'th > div.form-group > a.order', function () {
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
        $('div.grid').on('change', 'td > label.control-checkbox > input.checkbox', function () {
            var id = $(this).attr('id').split('-');
            $.ajax({
                url: window.location,
                type: 'POST',
                data: {id: id[1], name: id[0], value: $(this).val(), checked: $(this).is(':checked')}
            });
        });
    };

    var initGridSortable = function () {
        if ($('table.table-sort').length > 0 && $('table.table-sort').attr('data-sort-url')) {
            $('table.table-sort tbody').sortable({
                items: "> tr",
                handle: '.sort-row',
                update: function (event, ui) {
                    $.post(request.baseUrl + "/?" + $('table.table-sort').attr('data-sort-url'), {value:$(this).sortable('toArray')},
                        function (result) {
                            if (result) {
                                alert(result);
                            }
                        });
                }
            });
            $('table.table-sort tbody').disableSelection();
        }
    };

    initGridFilter();
    initGridOrder();
    initGridSortable();
    initGridOperation();
    initPaginator();
    initPicker();
};

$(document).ready(function () {
    "use strict";
    CMS.grid();
});
