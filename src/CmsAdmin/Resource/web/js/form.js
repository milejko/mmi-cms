/*jslint unparam: true */

/*global document, $, jQuery, id, request */

function urlencode(str) {
    "use strict";
    str = String(str);
    str = str.replace(',', '%2C');
    str = str.replace(' ', '+');
    str = str.replace('&', '%26');
    str = str.replace('~', '%7E');
    str = str.replace('?', '%3F');
    str = str.replace('=', '%3D');
    str = str.replace(';', '%3B');
    str = str.replace('\'', '%27');
    str = str.replace('"', '%22');
    return str;
}

var blurXhrs = [];

function fieldValidationOnBlur(element) {
    "use strict";
    var fieldValue = $(element).val(),
            fid = $(element).attr('id'),
            formId = fid.substr(0, fid.lastIndexOf('-')),
            form = $('form.' + fid.substr(0, fid.lastIndexOf('-'))),
            formClass = form.attr('data-class'),
            recordClass = form.attr('data-record-class'),
            recordId = form.attr('data-record-id'),
            name = fid.substr(fid.lastIndexOf('-') + 1),
            errorsId = formId + '-' + name + '-errors';
    if ('checkbox' === $(element).attr('type') && !$(element).is(':checked')) {
        fieldValue = '0';
    }
    var newXhr = $.post('/?module=cms&controller=form&action=validate', {
        field: name,
        class: formClass,
        recordClass: recordClass,
        recordId: recordId,
        value: urlencode(fieldValue)
    },
            function (result) {
                if (result) {
                    $('#' + errorsId).parent().addClass('error');
                    $(element).addClass('error');
                } else {
                    $('#' + errorsId).parent().removeClass('error');
                    $(element).removeClass('error');
                }
                $('#' + errorsId).html(result);
            });
    blurXhrs.push(newXhr);
}

function fieldValidation($element) {
    "use strict";
    let fid = $element.attr('id'),
        name = fid.substr(fid.lastIndexOf('-') + 1),
        url = window.location.href + (window.location.href.indexOf('?') === -1 ? '?' : '&') + 'validationField=' + name;
    let newXhr = $.post(url, $element.parents('form').serialize(),
        function (result) {
            if (result) {
                addValidationFeedback($element, result);
            } else {
                removeValidationFeedback($element);
            }
        });
    blurXhrs.push(newXhr);
}

function addValidationFeedback($element, result) {
    $element
        .addClass('error')
        .parent().addClass('error')
        .find('.form-control-feedback').html(result);
}

function removeValidationFeedback($element) {
    $element
        .removeClass('error')
        .parent().removeClass('error')
        .find('.form-control-feedback').empty();
}

$(document).ready(function () {
    "use strict";

    //podłączenie walidacji na blurze
    $('form:not(.cmsadmin-form-categoryform) .validate').on('blur', function () {
        fieldValidationOnBlur(this);
    });

    //podłączenie walidacji tylko na category formach i widgetach
    $('form.cmsadmin-form-categoryform .validate').on('blur', function () {
        fieldValidation($(this));
    }).on('focus', function () {
        removeValidationFeedback($(this));
    });

    //checkbox na change robi blur (walidacja)
    $('input[type="checkbox"].validate').on('change', function () {
        $(this).trigger('blur');
    });

    //on focus na submit
    $('input[type="submit"]').on('mousedown', function () {
        //abort ALL ajax request
        for (var x = 0; x < blurXhrs.length; x++) {
            blurXhrs[x].abort();
        }
        //$(this).trigger('click');
    });

    //pola do przeciwdziałania robotom bez JS
    $('div.antirobot > input').val('js-' + $('div.antirobot > input').val() + '-js');

    //licznik znaków
    $('.form-control.field.text,.form-control.field.textarea')
        .after('<small class="text-counter mt-1 float-right"></small>')
        .keyup(function () {
            $(this).siblings('.text-counter').text('liczba znaków: ' + $(this).val().length);
        })
        .focusout(function () {
            $(this).siblings('.text-counter').text('');
        });
});
