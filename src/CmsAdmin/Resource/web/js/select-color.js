/*jslint unparam: true, nomen: true, devel: true*/
/*global $, jQuery, document, window, request */

$(document).ready(function () {

    'use strict';

    $('div.select-color > div.color').click(function ()
    {
        $('#' + $(this).parent('div.select-color').attr('data-for-id')).val($(this).attr('data-color-class'));
        $(this).parent('div.select-color').children('div.color').removeClass('selected');
        $(this).addClass('selected');
    });

});