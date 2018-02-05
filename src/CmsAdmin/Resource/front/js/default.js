var CMS = CMS ? CMS : {};

CMS.default = function () {
    "use strict";
    var initConfirms,
        initFormDoubleSendLock,
        duringSend = false;

    initConfirms = function () {
        //linki potwierdzające
        $('body').on('click', 'a.confirm', function () {
            return window.confirm($(this).attr('title') + '?');
        });
    };

    initFormDoubleSendLock = function () {
        $('form').submit(function () {
            if (duringSend) {
                event.preventDefault();
            }
            duringSend = true;
            $('input[type=submit]').attr('disabled', true);
            $('button[type=submit]').attr('disabled', true);
        });
    };
    initFormDoubleSendLock();
    initConfirms();
};

$(document).ready(function () {
    "use strict";
    CMS.default();
});
