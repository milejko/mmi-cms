$(document).ready(function () {
    let lists = $('.multifield');

    lists.each(function (index, list) {
        let containerId = $(list).attr('id');

        $(list).find('.ne-error-list').each(function () {
            if ($(this).children().length > 0) {
                showMultifieldItem($(this).closest('.field-list-item'));
            }
        });

        $(document).off('click', '#' + containerId + ' > .field-list > li > .btn-remove');
        $(document).on('click', '#' + containerId + ' > .field-list > li > .btn-remove', function (e) {
            e.preventDefault();
            $(this).parent().remove();
            reindexMultifield(list);
        });

        $(document).off('click', '#' + containerId + ' > .field-list > li > .btn-toggle');
        $(document).on('click', '#' + containerId + ' > .field-list > li > .btn-toggle', function (e) {
            e.preventDefault();
            toggleMultifieldItem($(this).closest('.field-list-item'));
        });
    });

    function reindexMultifield(list) {
        $(list).children().each(function (i) {
            let elementsWithId = $('[id]', this);
            elementsWithId.each(function () {
                $(this).attr('id', $(this).attr('id').replace(/-\d+-/ig, '-' + i + '-'));
            });

            let elementsWithFor = $('[for]', this);
            elementsWithFor.each(function () {
                $(this).attr('for', $(this).attr('for').replace(/-\d+-/ig, '-' + i + '-'));
            });

            let elementsWithName = $('[name]', this);
            elementsWithName.each(function () {
                $(this).attr('name', $(this).attr('name').replace(/\[\d+\]/ig, '[' + i + ']'));
            });
        });
    }

    function toggleMultifieldItem(listItem) {
        listItem.toggleClass('active');
        listItem.children('.btn-toggle').children('.fa').toggleClass('fa-angle-up fa-angle-down');

        listItem.siblings().removeClass('active');
        listItem.siblings().children('.btn-toggle').children('.fa').removeClass('fa-angle-up');
        listItem.siblings().children('.btn-toggle').children('.fa').addClass('fa-angle-down');
    }

    function showMultifieldItem(listItem){
        listItem.addClass('active');
        listItem.children('.btn-toggle').children('.fa').addClass('fa-angle-down');
        listItem.children('.btn-toggle').children('.fa').removeClass('fa-angle-up');
    }
});
