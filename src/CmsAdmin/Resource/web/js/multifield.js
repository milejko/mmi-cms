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

        $(document).off('click', '#' + containerId + ' > .btn-toggle');
        $(document).on('click', '#' + containerId + ' > .btn-toggle', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $(this).children('.fa').toggleClass('fa-angle-up fa-angle-down');
            if ($(this).hasClass('active')) {
                $(this).children('span').text('Zwiń wszystkie');
                showAllMultifieldItems($(this).closest('.multifield').children('.field-list'));
            } else {
                $(this).children('span').text('Rozwiń wszystkie');
                hideAllMultifieldItems($(this).closest('.multifield').children('.field-list'));
            }
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

        if (listItem.hasClass('active')) {
            listItem.siblings().each(function (index, sibling) {
                hideMultifieldItem(sibling);
            });
        }
    }

    function showMultifieldItem(listItem) {
        listItem.addClass('active');
        listItem.children('.btn-toggle').children('.fa').removeClass('fa-angle-down');
        listItem.children('.btn-toggle').children('.fa').addClass('fa-angle-up');
    }

    function hideMultifieldItem(listItem) {
        listItem.removeClass('active');
        listItem.children('.btn-toggle').children('.fa').removeClass('fa-angle-up');
        listItem.children('.btn-toggle').children('.fa').addClass('fa-angle-down');
    }

    function showAllMultifieldItems(list) {
        $(list).children('.field-list-item').each(function () {
            showMultifieldItem($(this));
        });
    }

    function hideAllMultifieldItems(list) {
        $(list).children('.field-list-item').each(function () {
            hideMultifieldItem($(this));
        });
    }
});
