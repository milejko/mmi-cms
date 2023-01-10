var CMS = CMS || {};
var openedWindow = {closed: true};
var followScroll = false;

var $elems = $("html, body");
var delta = 0;

$(document).on("mousemove", function (e) {
    if (followScroll) {
        var h = $(window).height();
        var y = e.clientY - h / 2;
        delta = y * 0.1;
    } else {
        delta = 0;
    }
});

CMS.category = function () {
    "use strict";
    var that = {},
        initSectionFolding,
        initScrollPositioning,
        initSortableWidgets,
        initWidgetButtons,
        initErrorHandler,
        initCategoryChange;

    initErrorHandler = function () {
        $('.ne-error-list').each(function () {
            if (($(this).html()).length > 20) {
                $(this).parents('div.tab-pane').each(function () {
                    window.scroll(0, $(this).position().top);
                    $('a[aria-controls="' + $(this).attr('id') + '"]').click();
                });
            }
        });
    };

    initSectionFolding = function () {
        $('a.show-all').click(function () {
            $('ul.wlist li').removeClass('folded');
        });

        $('a.hide-all').click(function () {
            $('ul.wlist li').addClass('folded');
        });

        $('.toogleWidget').click(function () {
            var el = $(this).parents('li');
            if (el.hasClass('folded')) {
                el.removeClass('folded')
            } else {
                el.addClass('folded')
            }

        });
    }

    initScrollPositioning = function () {
        var key = 'scrollPosition-' + window.location.href;
        if (sessionStorage.getItem(key)) {
            window.scroll(0, sessionStorage.getItem(key));
        }
        $(window).on('click', function () {
            sessionStorage.setItem(key, window.scrollY);
        });
    };

    initSortableWidgets = function () {
        if ($('.widget-list').length > 0) {
            $('.widget-list').sortable({
                start: function () {
                    followScroll = true;
                },
                stop: function () {
                    followScroll = false;
                },
                handle: '.handle-widget',
                update: function (event, ui) {
                    $.post("/?module=cmsAdmin&controller=categoryWidgetRelation&action=sort&categoryId=" + $(this).attr('data-category-id'), $(this).sortable('serialize'),
                        function (result) {
                            if (result) {
                                alert(result);
                            }
                        });
                }
            });
        }
    };

    initWidgetButtons = function () {
        $('.widget-list').on('click', '.toggle-widget', function () {
            var state = parseInt($(this).data('state'));
            state++;
            if (state > 1) {
                state = 0;
            }
            $.get($(this).attr('href'), {'state': state});
            if (state === 1) {
                $(this).children('i').attr('class', 'fa fa-2 fa-eye pull-right');
                $(this).attr('title', 'aktywny');
            } else {
                $(this).children('i').attr('class', 'fa fa-2 fa-eye-slash pull-right');
                $(this).attr('title', 'ukryty');
            }
            $(this).data('state', state);
            return false;
        });
    };

    initCategoryChange = function () {
        $('form.cmsadmin-form-categoryform').on('change', '#cmsadmin-form-categoryform-template', function () {
            $('#cmsadmin-form-categoryform-submit-top').val('type');
            $('#cmsadmin-form-categoryform-submit-top').click();
            return false;
        });
    };

    var dataTabRestore = function () {
        var itemName = 'categoryActiveTab-' + $('ul.nav-tabs').attr('data-id');
        $('ul.nav-tabs > li').on('click', 'a', function (evt) {
            sessionStorage.setItem(itemName, $(this).attr("href"));
        });
        var currentTab = sessionStorage.getItem(itemName);
        $('div.tab-content .tab-pane').each(function () {
            if ($(this).find('.form-control-feedback li').length > 0) {
                currentTab = $(this).attr('id');
                return false;
            }
        });
        if (currentTab) {
            $('ul.nav-tabs > li > a[href$="' + currentTab + '"]').click();
            return;
        }
        $('ul.nav-tabs > li > a[href$="#default"]').click();
    };
    dataTabRestore();
    initSortableWidgets();
    initWidgetButtons();
    initCategoryChange();
    initSectionFolding();
    initScrollPositioning();
    initErrorHandler();
    return that;
};

$(document).ready(function () {
    "use strict";
    CMS.category();
});

$(document).ready(function () {
    const $redirectTypeTrigger = $('#cmsadmin-form-categoryform-redirectType-container .radio');
    const $redirectCategoryIdElement = $('#cmsadmin-form-categoryform-redirectCategoryId-container');
    const $redirectUriElement = $('#cmsadmin-form-categoryform-redirectUri-container');
    const $redirectUriInput = $redirectUriElement.find('input');
    $redirectTypeTrigger.change(function () {
        if (!$(this).is(':checked')) {
            return;
        }
        if ('internal' === $(this).val()) {
            $redirectUriElement.hide();
            $redirectCategoryIdElement.show();
        } else {
            $redirectUriElement.show();
            $redirectCategoryIdElement.hide();
            if (/internal:\/\//.test($redirectUriInput.val())) {
                $redirectUriInput.val('');
            }
        }
    }).change();
});

$(document).ready(function () {
    $('.toggle-widgets').click(function (e) {
        e.preventDefault();
        let $availableWidgets = $(this).siblings('.available-widgets');
        if ($availableWidgets.hasClass('short')) {
            $availableWidgets.removeClass('short');
            $(this).text('Zwiń');
        } else {
            $availableWidgets.addClass('short');
            $(this).text('Wszystkie widgety');
        }
    }).click();
});
