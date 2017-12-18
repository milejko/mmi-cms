$(document).ready(function () {
    $('div.auto-download > a').each(function () {
        var obj = $(this);
        $.get(request.baseUrl + '/?module=cms&controller=connector&action=importFile&name=' + obj.attr('data-name')).always(function () {
            obj.remove();
        });
    });
});