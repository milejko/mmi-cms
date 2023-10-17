$(document).ready(function () {
    $('table.table-sort tbody').sortable({
        items: "> tr",
        handle: '.sort-row',
        axis: 'y',
        update: function (event, ui) {
            var orderDirection = 'desc';
            if ($('table.table-sort a[href$="[order]"]').attr('data-method') === 'orderAsc') {
                orderDirection = 'asc';
            }
            $.post($('table.table-sort').attr('data-sort-url'), {
                    order: orderDirection,
                    value: $(this).sortable('toArray', {attribute: "data-id"})
                },
                function (result) {
                    if (result) {
                        alert(result);
                    }
                }
            );
        }
    });
    // $('table.table-sort tbody').disableSelection();
});
