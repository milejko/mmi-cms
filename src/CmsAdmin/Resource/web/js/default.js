var CMS = CMS ? CMS : {};

CMS.default = function () {
    "use strict";
    var initConfirms,
        initFormDoubleSendLock,
        unlockSubmits,
        duringSend = false;

    initConfirms = function () {
        //linki potwierdzające
        $('body').on('click', 'a.confirm', function () {
            return window.confirm(($(this).data('message') ? $(this).data('message') : $(this).attr('title')) + '?');
        });
    };

    initFormDoubleSendLock = function () {
        $('form').submit(function () {
            if (duringSend) {
                event.preventDefault();
            }
            duringSend = true;
            $('input[type=submit]').addClass('disabled');
            $('button[type=submit]').addClass('disabled');
            setTimeout(unlockSubmits, 1000);
        });
    };

    unlockSubmits = function () {
        duringSend = false;
        $('input[type=submit]').removeClass('disabled');
        $('button[type=submit]').removeClass('disabled');
    };

    unlockSubmits();
    initFormDoubleSendLock();
    initConfirms();
};

$(document).ready(function () {
    "use strict";
    CMS.default();
});
