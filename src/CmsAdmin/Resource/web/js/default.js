var CMS = CMS ? CMS : {};

CMS.default = function () {
	"use strict";
	var initConfirms,
			initIframes,
			initAutosave;

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
					$('#' + frame.id).css('height', document.getElementById(frame.id).contentWindow.document.body.scrollHeight + 20 + 'px');
				}, 500);
			}
		});
	};
	
	initAutosave = function () {
		
	};

	initConfirms();
	initIframes();
};

$(document).ready(function () {
	"use strict";
	CMS.default();
});
