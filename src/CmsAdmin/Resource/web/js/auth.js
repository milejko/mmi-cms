/*jslint unparam: true, nomen: true, devel: true*/
/*global $, jQuery, document, window, request */

$(document).ready(function () {

    'use strict';

    $('#cmsadmin-form-auth-username').autocomplete({
        source: request.baseUrl + '/cmsAdmin/auth/autocomplete',
        type: "json",
        minLength: 3,
        select: function (event, ui) {
            $('#cmsadmin-form-auth-name').val(ui.item.name);
            $('#cmsadmin-form-auth-email').val(ui.item.email);
        }
    });

});