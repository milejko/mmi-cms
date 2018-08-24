var CMS = CMS ? CMS : {};
var selectedPosition = 0,
    selectedInput = null,
    filtering = false;

CMS.grid = function () {
    "use strict";

    var quickSwitch = function(data) {
        $('.grid-anchor').html(data.body);
        $('.paginator-anchor').html(data.paginator);
        initPaginator();
        initPicker();
    };

    var initPicker = function () {
        $('.grid-picker').datetimepicker({format:'Y-m-d H:i'});
    };

    var filter = function(field) {
        var filter = field.attr('name'),
            value = field.val(),
            fieldName = field.attr('name');
        filtering = true;
        $.ajax({
            url: window.location,
            type: 'POST',
            data: {filter: filter, value: value},
            success: function (data) {
                quickSwitch(data);
                if (!selectedInput) {
                    filtering = false;
                    return;
                }
                var element = $('input[name=\'' + selectedInput + '\']');
                element.focus();
                try {
                    element[0].setSelectionRange(selectedPosition, selectedPosition);
                } catch (e) {}
                selectedInput = null;
                filtering = false;
                return true;
            }
        });
    };

    var initPaginator = function () {
        if($('.paginator-anchor ul.pagination > li.page-item > a').length) {
            $('.paginator-anchor ul.pagination > li.page-item > a').on('click', function (evt) {
                var filter = $('ul.pagination').data('name'),
                    value = $(this).data('page'),
                    gridId = $('table').attr("id");
                $.ajax({
                    url: window.location,
                    type: 'POST',
                    data: {filter: filter, value: value},
                    success: function (data) {
                        quickSwitch(data);
                    }
                });
            });
        }
    };

    var initGridFilter = function () {
        $('table.table-striped').on('keyup', ".grid-filter", function (event) {
            selectedPosition = $(this)[0].selectionStart;
            selectedInput = $(this).attr('name');
        });

        $('table.table-striped').on('keydown', ".grid-filter", function (event) {
            return event.which != 9;
        });

        $('table.table-striped').on('change', "th > div.form-group > .input-group > input", function () {
            if (!$(this).hasClass('no-focus')) {
                selectedPosition = $(this)[0].selectionStart;
                selectedInput = $(this).attr('name');
            }
            var from = $(this).parent('div.input-group').children('input.from').val();
            var to = $(this).parent('div.input-group').children('input.to').val();
            $(this).parent('div.input-group').parent('div.form-group').children('input.hidden').val(from + ';' + to);
            if (!filtering) {
                filter($(this).parent('div.input-group').parent('div.form-group').children('input.hidden'));
            }
        });

        $('table.table-striped').on('change', ".grid-filter", function () {
            if (!filtering) {
                filter($(this));
            }
        });

        $('table.table-striped').on('focus', ".grid-filter", function () {
            selectedInput = $(this).attr('name');
        });

    };

    var initGridOrder = function () {
        //sortowanie grida
        $('table.table-striped').on('click', 'th > div.form-group > a.order', function () {
            var field = $(this).attr('href'),
                gridId = $(this).parent().parent().parent().parent().parent().parent().find('table').attr("id"),
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
        $('table.table-striped').on('change', 'td > label.control-checkbox > input.checkbox', function () {
            var id = $(this).attr('id').split('-');
            $.ajax({
                url: window.location,
                type: 'POST',
                data: {id: id[1], name: id[0], value: $(this).val(), checked: $(this).is(':checked')},
                success: function() {

                }
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
