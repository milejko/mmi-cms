var CMS = CMS ? CMS : {};
var selectionPosition = 0;
var filtering = false;

CMS.grid = function () {
    "use strict";

    var quickSwitch = function(data) {
        $('.grid-anchor').html(data.body);
        $('.paginator-anchor').html(data.paginator);
        initPaginator();
    };

    var initPicker = function () {
        if($('.gridDateTimePicker').length === 1){
            $('.gridDateTimePicker').datetimepicker({format:'Y-m-d H:i'});
        }
    };

    var filter = function(field, focus) {
        var filter = field.attr('name'),
            value = field.val(),
            fieldName = field.attr('name'),
            gridId = field.parent().parent().parent().parent().parent().parent().find('table').attr("id");
        filtering = true;
        $.ajax({
            url: window.location,
            type: 'POST',
            data: {filter: filter, value: value},
            beforeSend: function () {
                field.addClass('grid-loader');
            },
            success: function (data) {
                quickSwitch(data);
                if (!focus) {
                    filtering = false;
                    return;
                }
                var element = $('input[name=\'' + fieldName + '\']');
                element.focus();
                element[0].setSelectionRange(selectionPosition, selectionPosition);
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
                    beforeSend: function () {
                        $(this).addClass('grid-loader');
                    },
                    success: function (data) {
                        quickSwitch(data);
                    }
                });
            });
        }
    };

    var initGridFilter = function () {
        $('table.table-striped').on('keyup', "th > div.form-group > .form-control", function (event) {
            selectionPosition = $(this)[0].selectionStart;
            if (event.which === 13 && !filtering) {
                filter($(this), true);
            }
        });

        $('table.table-striped').on('change', "th > div.form-group > select.form-control", function () {
            filter($(this), false);
        });

        $('table.table-striped').on('blur', "th > div.form-group > input.form-control", function () {
            if (!filtering) {
                filter($(this), false);
            }
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
};

$(document).ready(function () {
    "use strict";
    CMS.grid();
});
