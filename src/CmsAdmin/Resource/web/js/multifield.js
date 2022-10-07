let multifieldListItemTemplate = {};

$(document).ready(function () {
    multifieldInitLists(('.multifield'));
    $('.multifield .field-list').sortable({
        axis: "y",
        helper: function () {
            return '<li class="field-list-item border mb-3 p-3"></li>';
        },
        start: function(){
            jQuery(this).find('section .sidenoteformelementsidenotetinymce textarea').each(function(){
                tinyMCE.execCommand('mceRemoveEditor', false, jQuery(this).attr('id'));
            });
        },
        stop: function() {
            jQuery(this).find('section .sidenoteformelementsidenotetinymce textarea').each(function(){
                tinyMCE.execCommand('mceAddEditor', false, jQuery(this).attr('id') );
            });
        },
        handle: '.sortable-handler',
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


function multifieldInitLists(lists) {
    $(lists).each(function (index, list) {
        $(list).find('.ne-error-list').each(function () {
            if ($(this).children().length > 0) {
                multifieldShowMultifieldItem($(this).closest('.field-list-item'));
            }
        });

        let containerId = $(list).attr('id');
        multifieldInitContainer(containerId);
    });
}

function multifieldInitContainer(containerId) {
    multifieldInitActive(containerId);
    multifieldInitRemove(containerId);
    multifieldInitToggle(containerId);
    multifieldInitToggleAll(containerId);
    multifieldInitAdd(containerId);
    multifieldInitToggleAuto(containerId);
    multifieldInitTinyMce(containerId);
    multifieldInitLists('#' + containerId + ' .multifield');
}

function multifieldInitActive(containerId) {
    $('#' + containerId).children('.field-list').children('.field-list-item').each(function () {
        multifieldReadActive($(this));
    });

    $(document).off('click', '#' + containerId + ' > .field-list > li > .icons > .btn-active');
    $(document).on('click', '#' + containerId + ' > .field-list > li > .icons > .btn-active', function (e) {
        e.preventDefault();
        multifieldToggleActive($(this).closest('.field-list-item'));
    });
}

function multifieldInitRemove(containerId) {
    $(document).off('click', '#' + containerId + ' > .field-list > li > .icons > .btn-remove');
    $(document).on('click', '#' + containerId + ' > .field-list > li > .icons > .btn-remove', function (e) {
        e.preventDefault();
        if (confirm('Czy na pewno usunąć?')) {
            $(this).closest('.field-list-item').remove();
        }
    });
}

function multifieldInitToggle(containerId) {
    $(document).off('click', '#' + containerId + ' > .field-list > li > .icons > .btn-toggle');
    $(document).on('click', '#' + containerId + ' > .field-list > li > .icons > .btn-toggle', function (e) {
        e.preventDefault();
        multifieldToggleMultifieldItem($(this).closest('.field-list-item'));
    });
}

function multifieldInitToggleAll(containerId) {
    $(document).off('click', '#' + containerId + ' > .btn-toggle');
    $(document).on('click', '#' + containerId + ' > .btn-toggle', function (e) {
        e.preventDefault();
        multifieldToggleGeneralSwitch($(this));
    });
}

function multifieldInitAdd(containerId) {
    $(document).off('click', '#' + containerId + ' > .btn-add');
    $(document).on('click', '#' + containerId + ' > .btn-add', function (e) {
        e.preventDefault();
        let template = $(this).data('template');
        let list = $(this).closest('.multifield').find('.field-list').first();

        $(list).append(
            multifieldListItemTemplate[template]
                .replaceAll('**', $(list).children().length)
                .replaceAll('##', $(list).parents('.field-list-item').last().index())
        );
        let newItem = $(list).children('.field-list-item').last();
        newItem.find('.select2').select2();
        multifieldInitContainer(containerId);
        multifieldToggleActive(newItem);
    });
}

function multifieldInitToggleAuto(containerId) {
    $(document).off('focus', '#' + containerId + ' .form-group:nth-child(2) > input[type=text], #' + containerId + ' .form-group:nth-child(2) > select, #' + containerId + ' .form-group:nth-child(2) > .select2');
    $(document).on('focus', '#' + containerId + ' .form-group:nth-child(2) > input[type=text], #' + containerId + ' .form-group:nth-child(2) > select, #' + containerId + ' .form-group:nth-child(2) > .select2', function (e) {
        if (false === $(this).closest('.field-list-item').hasClass('active')) {
            multifieldToggleMultifieldItem($(this).closest('.field-list-item'));
        }
    });
}

function multifieldInitTinyMce(containerId) {
    if (typeof tinyMCE === "undefined") {
        return;
    }
    const editors = $('#' + containerId).find('.tinymce');
    editors.each(function () {
        var configJson = $(this).data('config');
        configJson.language = request.locale;
        tinyMCE.init(configJson);
    });
}

function multifieldToggleGeneralSwitchStyle(generalSwitch) {
    $(generalSwitch).toggleClass('active');
    $(generalSwitch).children('.fa').toggleClass('fa-angle-up fa-angle-down');
    if ($(generalSwitch).hasClass('active')) {
        $(generalSwitch).children('span').text('Zwiń wszystkie');
    } else {
        $(generalSwitch).children('span').text('Rozwiń wszystkie');
    }
}

function multifieldToggleGeneralSwitch(generalSwitch) {
    multifieldToggleGeneralSwitchStyle(generalSwitch);
    if ($(generalSwitch).hasClass('active')) {
        multifieldShowAllMultifieldItems($(generalSwitch).closest('.multifield').children('.field-list'));
    } else {
        multifieldHideAllMultifieldItems($(generalSwitch).closest('.multifield').children('.field-list'));
    }
}

function multifieldToggleMultifieldItem(listItem) {
    listItem.toggleClass('active');
    $(listItem).children('.icons').children('.btn-toggle').children('.fa').toggleClass('fa-angle-up fa-angle-down');

    if (listItem.hasClass('active')) {
        listItem.siblings().each(function (index, sibling) {
            multifieldHideMultifieldItem(sibling);
        });
    } else if (listItem.closest('.field-list').find('.active').length === 0) {
        multifieldToggleGeneralSwitchStyle(listItem.closest('.multifield').children('.btn-toggle'));
    }
}

function multifieldShowMultifieldItem(listItem) {
    listItem.addClass('active');
    listItem.children('.icons').children('.btn-toggle').children('.fa').removeClass('fa-angle-down');
    listItem.children('.icons').children('.btn-toggle').children('.fa').addClass('fa-angle-up');

    if (listItem.closest('.nested-multifield').length > 0) {
        multifieldShowMultifieldItem(listItem.closest('.nested-multifield').closest('.field-list-item'));
    }
}

function multifieldHideMultifieldItem(listItem) {
    $(listItem).removeClass('active');
    $(listItem).children('.icons').children('.btn-toggle').children('.fa').removeClass('fa-angle-up');
    $(listItem).children('.icons').children('.btn-toggle').children('.fa').addClass('fa-angle-down');
}

function multifieldShowAllMultifieldItems(list) {
    $(list).children('.field-list-item').each(function () {
        multifieldShowMultifieldItem($(this));
    });
}

function multifieldHideAllMultifieldItems(list) {
    $(list).children('.field-list-item').each(function () {
        multifieldHideMultifieldItem($(this));
    });
}

function multifieldReadActive(listItem) {
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

function multifieldToggleActive(listItem) {
    $(listItem).toggleClass('is-active');
    $(listItem).find('.btn-active').find('.fa').toggleClass('fa-eye fa-eye-slash');
    let checkbox = $(listItem).find('input[type=checkbox][name$="[isActive]"]');
    checkbox.prop('checked', !checkbox.prop('checked'));
}
