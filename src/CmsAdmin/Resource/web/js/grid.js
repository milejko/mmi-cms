/*jslint unparam: true */
/*global $, document, window, request */

jQuery.fn.putCursorAtEnd = function () {
    return this.each(function () {
        $(this).focus();
        if (this.setSelectionRange) {
            var len = $(this).val().length * 2;
            //this.setSelectionRange(len, len);
        } else {
            $(this).val($(this).val());
        }
    });
};

var CMS = CMS ? CMS : {};

CMS.grid = function () {
    "use strict";
    var stoptyping;
    var doFilter = true;

    var quickSwitch = function(data){
        $('.grid-anchor').html(data.body);
        $('.paginator-anchor').html(data.paginator);
        initDt();
    };

    var initDt = function () {
        if($('.dtFrom').length === 1){
            $('.dtFrom').datetimepicker({format:'Y-m-d H:i'});
        }
        if($('.dtTo').length === 1){
            $('.dtTo').datetimepicker({format:'Y-m-d H:i'});
        }
    };

    var filter = function(field) {
        var filter = field.attr('name'),
            value = field.val(),
            fieldName = field.attr('name'),
            gridId = field.parent().parent().parent().parent().parent().parent().find('table').attr("id");
        $.ajax({
            url: window.location,
            type: 'POST',
            data: {filter: filter, value: value},
            beforeSend: function () {
                field.addClass('grid-loader');
            },
            success: function (data) {
                $('input[name=\'' + fieldName + '\']').putCursorAtEnd();
                quickSwitch(data);
                console.log('d');
            }
        });
    };

    var initGridFilter = function () {
        $('table.table-striped').on('keyup', "th > div.form-group > .form-control", function (event) {
            if (event.which === 27) {
                return;
            }
            var field = $(this);
            clearTimeout(stoptyping);
            stoptyping = setTimeout(function () {
                if (field.val().length === 0 && !doFilter) {
                    doFilter = true;
                    return;
                }
                doFilter = true;
                filter(field);
            }, 500);
        });

        $('table.table-striped').on('change', "th > div.form-group > select.form-control", function () {
            filter($(this));
        });

        if($('ul.pagination > li.page-item > a').length) {
            $('ul.pagination > li.page-item > a').on('click', function (evt) {
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

        $('table.table-striped').on('input', "th > div.form-group > input.form-control", function () {
            if ($(this).val().length === 0) {
                if (doFilter === true) {
                    filter($(this));
                    doFilter = false;
                }
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
        $('table.table-striped').on('change', 'td > div.control-checkbox > input.checkbox', function () {
            var id = $(this).attr('id').split('-');
            $.ajax({
                url: window.location,
                type: 'POST',
                data: {id: id[1], name: id[0], value: $(this).val(), checked: $(this).is(':checked')},
                success: function() {
                    initDt();
                }
            });
        });
    };

    initGridFilter();
    initGridOrder();
    initGridOperation();
    initDt();
};

$(document).ready(function () {
    "use strict";
    CMS.grid();
});
