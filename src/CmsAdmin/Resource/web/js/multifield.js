let listItemTemplate = {};

$(document).ready(function () {
    initLists(('.multifield'));
    $('.multifield .field-list').sortable({
        axis: "y",
        helper: function () {
            return '<li class="field-list-item border mb-3 p-3"></li>';
        },
        handle: '.sortable-handler'
    });
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
        initContainer(containerId);
    });
}

function initContainer(containerId) {
    initActive(containerId);
    initRemove(containerId);
    initToggle(containerId);
    initToggleAll(containerId);
    initAdd(containerId);
    initToggleAuto(containerId);
    initLists('#' + containerId + ' .multifield');
}

function initActive(containerId) {
    $('#' + containerId).children('.field-list').children('.field-list-item').each(function () {
        readActive($(this));
    });

    $(document).off('click', '#' + containerId + ' > .field-list > li > .icons > .btn-active');
    $(document).on('click', '#' + containerId + ' > .field-list > li > .icons > .btn-active', function (e) {
        e.preventDefault();
        toggleActive($(this).closest('.field-list-item'));
    });
}

function initRemove(containerId) {
    $(document).off('click', '#' + containerId + ' > .field-list > li > .icons > .btn-remove');
    $(document).on('click', '#' + containerId + ' > .field-list > li > .icons > .btn-remove', function (e) {
        e.preventDefault();
        if(confirm('Czy na pewno usunąć?')){
            $(this).closest('.field-list-item').remove();
        }
    });
}

function initToggle(containerId) {
    $(document).off('click', '#' + containerId + ' > .field-list > li > .icons > .btn-toggle');
    $(document).on('click', '#' + containerId + ' > .field-list > li > .icons > .btn-toggle', function (e) {
        e.preventDefault();
        toggleMultifieldItem($(this).closest('.field-list-item'));
    });
}

function initToggleAll(containerId) {
    $(document).off('click', '#' + containerId + ' > .btn-toggle');
    $(document).on('click', '#' + containerId + ' > .btn-toggle', function (e) {
        e.preventDefault();
        toggleGeneralSwitch($(this));
    });
}

function initAdd(containerId) {
    $(document).off('click', '#' + containerId + ' > .btn-add');
    $(document).on('click', '#' + containerId + ' > .btn-add', function (e) {
        e.preventDefault();
        let template = $(this).data('template');
        let list = $(this).closest('.multifield').find('.field-list').first();

        $(list).append(
            listItemTemplate[template]
                .replaceAll('**', $(list).children().length)
                .replaceAll('##', $(list).parents('.field-list-item').last().index())
        );
        $(list).children('.field-list-item').last().find('.select2').select2();
        initContainer(containerId);
    });
}

function initToggleAuto(containerId) {
    $(document).off('focus', '#' + containerId + ' .form-group:nth-child(2) > input[type=text]');
    $(document).on('focus', '#' + containerId + ' .form-group:nth-child(2) > input[type=text]', function (e) {
        if (false === $(this).closest('.field-list-item').hasClass('active')) {
            toggleMultifieldItem($(this).closest('.field-list-item'));
        }
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

function readActive(listItem) {
    let checkbox = $(listItem).find('input[type=checkbox][name$="[isActive]"]');
    let isActive = checkbox.prop('checked');
    let button = $(listItem).find('.btn-active').find('.fa');

    if (isActive) {
        $(listItem).addClass('is-active');
        button.removeClass('fa-eye-slash');
        button.addClass('fa-eye');
        return;
    }

    $(listItem).removeClass('is-active');
    button.removeClass('fa-eye');
    button.addClass('fa-eye-slash');
}

function toggleActive(listItem) {
    $(listItem).toggleClass('is-active');
    $(listItem).find('.btn-active').find('.fa').toggleClass('fa-eye fa-eye-slash');
    let checkbox = $(listItem).find('input[type=checkbox][name$="[isActive]"]');
    checkbox.prop('checked', !checkbox.prop('checked'));
}
