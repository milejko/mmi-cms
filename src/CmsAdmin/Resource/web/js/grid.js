/*jslint unparam: true */
/*global $, document, window, request */
var CMS = {};

CMS.grid = function () {
	"use strict";
	var initGridFilter,
		initGridOrder,
		initGridOperation;

	initGridFilter = function () {

		var stoptyping;
		$('table.grid').on('keyup', "th > div.field > .field", function (event) {
			if (event.which === 27) {
				return;
			}
			var field = $(this);
			var fieldName = $(this).attr('name');
			clearTimeout(stoptyping);
			stoptyping = setTimeout(function () {
				field.change();
			}, 500);
		});

		$('table.grid').on('change', 'th > div.field > .field', function () {
			var field = $(this).attr('name'),
				value = $(this).val(),
				method =  $(this).attr('data-method'),
				fieldName = $(this).attr('name'),
				gridId = $(this).parent('div').parent('th').parent('tr').parent('tbody').parent('table').attr('id');
			$.post(window.location, {method: method, filter: field, value: value}, function (data) {
				$('#' + gridId).html(data);
				$('input[name=\'' + fieldName + '\']').focus().val($('input[name=\'' + fieldName + '\']').val());
			});
		});

	};

	initGridOrder = function () {
		//sortowanie grida
		$('table.grid').on('click', 'th > a.order', function () {
			var field = $(this).attr('href'),
				gridId = $(this).parent('th').parent('tr').parent('tbody').parent('table').attr('id'),
				method = $(this).attr('data-method');
			$.post(window.location, {order: field, method: method}, function (data) {
				$('#' + gridId).html(data);
			});
			return false;
		});
	};

	initGridOperation = function () {
		//zapytanie o kasowanie
		$('table.grid').on('click', 'td > a.confirm', function () {
			return window.confirm($(this).attr('title') + '?');
		});
		//zapytanie o kasowanie
		$('table.grid').on('change', 'td > div.checkbox > input.checkbox', function () {
			var id = $(this).attr('id').split('-');
			$.post(window.location, {id: id[1], name: id[0], value: $(this).val(), checked: $(this).is(':checked')}, function (data) {
				return;
			});
		});
	};

	initGridFilter();
	initGridOrder();
	initGridOperation();
};

$(document).ready(function () {
	"use strict";
	CMS.grid();
});
