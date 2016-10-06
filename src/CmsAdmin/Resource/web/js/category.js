var CMS = CMS ? CMS : {};
var openedWindow = {closed: true};

CMS.category = function () {
	"use strict";
	var that = {},
			initSortableWidgets,
			initIframes,
			initNewWindowButtons,
			initWidgetButtons,
			reloadWidgets;

	initSortableWidgets = function () {
		$('#widget-list').sortable({
			update: function (event, ui) {
				$.post(request.baseUrl + "/?module=cmsAdmin&controller=categoryWidgetRelation&action=sort&id=" + $(this).attr('data-category-id'), $(this).sortable('serialize'),
						function (result) {
							if (result) {
								alert(result);
							}
						});
			}
		});
	};

	initIframes = function () {
		$.each($('iframe'), function (key, frame) {
			if ($('#' + frame.id).length) {
				setTimeout(function () {
					$('#' + frame.id).css('height', document.getElementById(frame.id).contentWindow.document.body.scrollHeight + 20 + 'px');
				}, 500);
			}
		});
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
			$.get($(this).attr('href'));
			if ($(this).children('i').attr('class') == 'icon-eye-close') {
				$(this).children('i').attr('class', 'icon-eye-open');
			} else {
				$(this).children('i').attr('class', 'icon-eye-close');
			}

			return false;
		});
	};

	reloadWidgets = function () {
		$.get(request.baseUrl + "/?module=cmsAdmin&controller=categoryWidgetRelation&action=preview&categoryId=" + $('#widget-list').attr('data-category-id'), function (data) {
			$('#widget-list').html(data);
		});
	};

	that.reloadWidgets = reloadWidgets;

	initSortableWidgets();
	initIframes();
	initNewWindowButtons();
	initWidgetButtons();
	return that;
};

$(document).ready(function () {
	"use strict";
	CMS.category();
});
