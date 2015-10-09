/*jslint unparam: true */
/*global $, document, window, request */
var CMS = {};

CMS.grid = function () {
	"use strict";
	var initGridFilter,
			initGridOrder;

	initGridFilter = function () {

		var stoptyping;
		$('table.grid').on('keyup', "th > div.field > .field", function (event) {
			if (event.which === 27) {
				return;
			}
			var field = $(this);
			clearTimeout(stoptyping);
			stoptyping = setTimeout(function () {
				field.change();
			}, 500);
		});

		$('table.grid').on('change', 'th > div.field > .field', function () {
			var field = $(this).attr('name'),
					value = $(this).val(),
					gridId = $(this).parent('div').parent('th').parent('tr').parent('tbody').parent('table').attr('id');
			$.post(window.location, {filter: field, value: value}, function(data) {
				$('#' + gridId).html(data);
			});
		});

	};

	initGridOrder = function () {
		//sortowanie grida
		$('table.grid').on('click', 'th > a.order', function () {
			var field = $(this).attr('href'),
				gridId = $(this).parent('th').parent('tr').parent('tbody').parent('table').attr('id');
			$.post(window.location, {order: field}, function(data) {
				$('#' + gridId).html(data);
			});
			return false;
		});
	};

	initGridFilter();
	initGridOrder();
};

$(document).ready(function () {
	"use strict";
	CMS.grid();
});
