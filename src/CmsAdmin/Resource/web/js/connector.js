$(document).ready(function () {
    $('div.auto-download > a').each(function () {
        var obj = $(this);
        $.get('/?module=cms&controller=connector&action=importFile&name=' + obj.attr('data-name') + '&url=' + obj.parent().attr('data-url')).always(function () {
            obj.remove();
        });
    });
});