$(document).ready(function () {
    initLists(('.multifield'));
});

$(window).on('load', function () {
    $('.multifield .ne-error-list').each(function () {
        if ($(this).children().length > 0) {
            $('html, body').animate({
                scrollTop: $(this).offset().top - 200
            }, 1000);

            return false;
        }
    });
});

function initLists(lists) {
    $(lists).each(function (index, list) {
        $(list).find('.ne-error-list').each(function () {
            if ($(this).children().length > 0) {
                showMultifieldItem($(this).closest('.field-list-item'));
            }
        });

        let containerId = $(list).attr('id');

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
            toggleGeneralSwitch($(this));
        });

        $(document).on('focus', '#' + containerId + ' .form-group:first-child > input[type=text]', function (e) {
            if (false === $(this).closest('.field-list-item').hasClass('active')) {
                toggleMultifieldItem($(this).closest('.field-list-item'));
            }
        });
    });
}

function toggleGeneralSwitchStyle(generalSwitch) {
    $(generalSwitch).toggleClass('active');
    $(generalSwitch).children('.fa').toggleClass('fa-angle-up fa-angle-down');
    if ($(generalSwitch).hasClass('active')) {
        $(generalSwitch).children('span').text('Zwiń wszystkie');
    } else {
        $(generalSwitch).children('span').text('Rozwiń wszystkie');
    }
}

function toggleGeneralSwitch(generalSwitch) {
    toggleGeneralSwitchStyle(generalSwitch);
    if ($(generalSwitch).hasClass('active')) {
        showAllMultifieldItems($(generalSwitch).closest('.multifield').children('.field-list'));
    } else {
        hideAllMultifieldItems($(generalSwitch).closest('.multifield').children('.field-list'));
    }
}

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
    $(listItem).children('.btn-toggle').children('.fa').toggleClass('fa-angle-up fa-angle-down');

    if (listItem.hasClass('active')) {
        listItem.siblings().each(function (index, sibling) {
            hideMultifieldItem(sibling);
        });
    } else if (listItem.closest('.field-list').find('.active').length === 0) {
        toggleGeneralSwitchStyle(listItem.closest('.multifield').children('.btn-toggle'));
    }
}

function showMultifieldItem(listItem) {
    listItem.addClass('active');
    listItem.children('.btn-toggle').children('.fa').removeClass('fa-angle-down');
    listItem.children('.btn-toggle').children('.fa').addClass('fa-angle-up');

    if (listItem.closest('.nested-multifield').length > 0) {
        showMultifieldItem(listItem.closest('.nested-multifield').closest('.field-list-item'));
    }
}

function hideMultifieldItem(listItem) {
    $(listItem).removeClass('active');
    $(listItem).children('.btn-toggle').children('.fa').removeClass('fa-angle-up');
    $(listItem).children('.btn-toggle').children('.fa').addClass('fa-angle-down');
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
