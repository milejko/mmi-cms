$(document).ready(function () {
    $("a.order").each(function () {
        let page = $(this).data("page");
        let column = $(this).data("column");
        let currentMethod = $(this).data("method");
        let nextMethod = '';
        if (!currentMethod) {
            nextMethod = 'asc';
        } else if (currentMethod == 'asc') {
            nextMethod = 'desc';
        }
        $(this)
            .attr("href", "?p=" + page + "&order[" + column + "]=" + nextMethod)
            .append("<i class=\"fa fa-sort" + (currentMethod ? '-' + currentMethod : '') + "\" style=\"color: #20a8d8\"></i>");
    });
    $(".paginator .page a").each(function () {
        let page = $(this).data("page") || 1;
        $(this).attr("href", window.location.href.replace(/p=[0-9]+/g, 'p=' + page));
    });
});
