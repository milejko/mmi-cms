var CMS = CMS ? CMS : {};

CMS.default = function () {
	"use strict";
	var initConfirms,
			initIframes;

	initConfirms = function () {
		//linki potwierdzające
		$('body').on('click', 'a.confirm', function () {
			return window.confirm($(this).attr('title') + '?');
		});
	};

	initIframes = function () {
		$.each($('iframe'), function (key, frame) {
			if ($('#' + frame.id).length) {
				setTimeout(function () {
					if (!document.getElementById(frame.id).contentWindow.document.body) {
						return;
					}
					$('#' + frame.id).css('height', document.getElementById(frame.id).contentWindow.document.body.scrollHeight + 20 + 'px');
				}, 1000);
			}
		});
	};

	initConfirms();
	initIframes();
};

$(document).ready(function () {
	"use strict";
	CMS.default();
});
