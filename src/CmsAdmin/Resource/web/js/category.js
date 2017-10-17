var CMS = CMS || {};
var openedWindow = {closed: true};

CMS.category = function () {
    "use strict";
    var that = {},
            initSortableWidgets,
            initNewWindowButtons,
            initWidgetButtons,
            initPreviewReload,
            initCategoryChange,
            reloadWidgets,
            resizeIframe;

    initSortableWidgets = function () {
        if($('#widget-list').length > 0) {
            $('#widget-list').sortable({
                handle: '.handle-widget',
                update: function (event, ui) {
                    $.post(request.baseUrl + "/?module=cmsAdmin&controller=categoryWidgetRelation&action=sort&categoryId=" + $(this).attr('data-category-id'), $(this).sortable('serialize'),
                        function (result) {
                            if (result) {
                                alert(result);
                            }
                        });
                }
            });
        }
    };

    initNewWindowButtons = function () {
        $('#categoryContentContainer').on('click', 'a.new-window', function () {
            if (openedWindow.closed) {
                openedWindow = window.open($(this).attr('href'), '', "width=" + ($(window).width() - 200) + ",height=" + ($(window).height() - 200) + ",left=150,top=150,toolbar=no,scrollbars=yes,resizable=no");
                return false;
            }
            openedWindow.focus();
            return false;
        });
    };

    initWidgetButtons = function () {
        $('#widget-list').on('click', '.delete-widget', function () {
            if (!window.confirm($(this).attr('title') + '?')) {
                return false;
            }
            $.get($(this).attr('href'));
            $(this).parent('div').parent('li').remove();
            return false;
        });
        $('#widget-list').on('click', '.toggle-widget', function () {
            var state = parseInt($(this).data('state'));
            state++;

            if (state > 2) {
                state = 0;
            }

            $.get($(this).attr('href'), {'state': state});
            if (state === 1) {
                $(this).children('i').attr('class', 'icon-eye-open');
                $(this).attr('title', 'aktywny');
            } else if (state === 2) {
                $(this).children('i').attr('class', 'icon-eye-open red');
                $(this).attr('title', 'roboczy');
            } else {
                $(this).children('i').attr('class', 'icon-eye-close');
                $(this).attr('title', 'ukryty');
            }
            $(this).data('state', state);
            return false;
        });
    };

    initPreviewReload = function () {
        $('#categoryContentContainer').on('click', 'a.reload-preview', function () {
            var src = $('#preview-frame').attr('src');
            $('#preview-frame').attr('src', '');
            $('#preview-frame').attr('src', src);
        });
    };

    initCategoryChange = function () {
        $('#categoryContentContainer').on('change', '#cmsadmin-form-category-cmsCategoryTypeId', function () {
            $('#cmsadmin-form-category-submit1').click();
            return false;
        });
    };

    reloadWidgets = function () {
        $.get(request.baseUrl + "/?module=cmsAdmin&controller=categoryWidgetRelation&action=preview&categoryId=" + $('#widget-list-container').attr('data-category-id'), function (data) {
            $('#widget-list-container').html(data);
            initSortableWidgets();
            initWidgetButtons();
            if (window.MathJax !== undefined) {
                window.MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
            }
        });
    };

    resizeIframe = function () {
        //resize ramki tylko dla stron cms-owych
        if ($('input#cmsadmin-form-category-redirectUri').size() && $('input#cmsadmin-form-category-redirectUri').first().val().length === 0) {
            $('iframe#preview-frame').on('load', function () {
                $(this).height($(this).contents().find('body').height());
            });
        }
    };

    that.reloadWidgets = reloadWidgets;

    initSortableWidgets();
    initNewWindowButtons();
    initWidgetButtons();
    initPreviewReload();
    initCategoryChange();
    resizeIframe();
    return that;
};

$(document).ready(function () {
    "use strict";
    CMS.category();
});
