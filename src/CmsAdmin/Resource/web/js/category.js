var CMS = CMS ? CMS : {};

CMS.category = function () {
	"use strict";
	var initSortableWidgets,
			initIframes,
			initNewWindowButtons;

	initSortableWidgets = function () {
		$('#widget-list').sortable({
			update: function (event, ui) {
				$.post(request.baseUrl + "/?module=cmsAdmin&controller=category&action=sort", $(this).sortable('serialize'),
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
			window.open($(this).attr('href'), '', "width=" + ($(window).width()-200) + ",height=" + ($(window).height()-200) + ",left=150,top=150,toolbar=no,scrollbars=yes,resizable=no");
			return false;
		});
	};

	initSortableWidgets();
	initIframes();
	initNewWindowButtons();
};

$(document).ready(function () {
	"use strict";
	CMS.category();
});
