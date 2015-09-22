/*jslint unparam: true */
/*global $, document, window, request */
var CMS = {};

CMS.grid = function () {
	"use strict";
	var initGrid,
			getPages,
			initGridInputs,
			initSortable

	getPages = function (rowsCount, selected) {
		var i,
				html = '';
		for (i = 1; i <= rowsCount; i += 1) {
			if (selected === i) {
				selected = ' selected = ""';
			} else {
				selected = '';
			}
			html = html + '<option value="' + i + '"' + selected + '>' + i + '</option>';
		}
		return html;
	};

	initGridInputs = function () {

		$(".grid-field").change(function () {
			var id = $(this).attr('id'),
					splitedId = id.split('-'),
					formId = splitedId[0],
					type = splitedId[1],
					fieldType = splitedId[2],
					field = splitedId[3],
					identifier = splitedId[4],
					value = '',
					ctrl,
					url;

			if (fieldType === 'text' || fieldType === 'select') {
				value = $(this).val();
			} else if (fieldType === 'checkbox') {
				if ($(this).is(':checked')) {
					value = '1';
				} else {
					value = '0';
				}
			}
			ctrl = $('#' + formId + '__ctrl').val();
			url = request.baseUrl + "/?module=cms&controller=grid&action=" + type;
			$.post(url, {ctrl: ctrl, identifier: identifier, field: field, value: value});
			if (fieldType === 'text') {
				$(this).replaceWith('<a href="#" id="' + $(this).attr('id') +
						'" type="text" class="grid-field-trigger">' + $(this).val() + '</a>');
				$('#hid_' + $(this).attr('id')).remove();
			}
		});

		$(".grid-field").blur(function () {
			$(this).trigger('change');
		});

		$(".grid-field").keydown(function (event) {
			if (event.which === '13') {
				event.preventDefault();
				$(this).trigger('change');
			}
			if (event.which === '27') {
				event.preventDefault();
				$(this).val($(this).attr('name'));
				$(this).replaceWith('<a href="#" id="' + $(this).attr('id') +
						'" type="text" class="grid-field-trigger">' + $(this).val() + '</a>');
			}
		});
	};

	initGrid = function () {
		var stoptyping;
		$("body").on('keyup', ".grid-spot", function () {
			var spot = $(this);
			clearTimeout(stoptyping);
			stoptyping = setTimeout(function () {
				spot.change();
			}, 500);
		});
		$("body").on('change', ".grid-spot", function () {
			var id = $(this).attr('id'),
					splitedId = id.split('-'),
					formId = splitedId[0],
					type = splitedId[1],
					field = splitedId[2],
					value,
					ctrl,
					url;
			if (type === 'filter') {
				value = $(this).val();
			} else if (type === 'order') {
				$(this).next('i').remove();
				if ($(this).hasClass('asc')) {
					value = 'DESC';
					$(this).removeClass('asc').addClass('desc');
					$(this).parent('div').append(' <i class="icon-download"></i>');
				} else if ($(this).hasClass('desc')) {
					value = '';
					$(this).removeClass('desc').removeClass('asc');
				} else {
					value = 'ASC';
					$(this).removeClass('desc').addClass('asc');
					$(this).parent('div').append(' <i class="icon-upload"></i>');
				}
			}
			ctrl = $('#' + formId + '__ctrl').val();
			url = request.baseUrl + "/?module=cms&controller=grid&action=" + type;
			$.post(url, {ctrl: ctrl, field: field, value: value}, function (result) {
				var rowsCount,
						selected;
				$('#' + formId + '_body').html(result);
				if (type === 'filter' && field !== 'counter') {
					rowsCount = $('#' + formId + '__counter').val();
					selected = $('#' + formId + '_filter_counter').val();
					$('#' + formId + '-filter-counter').html(getPages(rowsCount, selected));
				}
			});
		});

		// Grid fields
		$('body').on('click', 'a.grid-field-trigger', function () {
			$(this).replaceWith('<input name="' + $(this).html() + '" id="' +
					$(this).attr('id') + '" type="text" class="grid-field" value="' +
					$(this).html() + '"/>');
			document.getElementById($(this).attr('id')).focus();
			initGridInputs();
		});

		// Grid interiors
		$('body').on('mouseenter', '.grid tr', function () {
			$(this).addClass('hover');
			$(this).removeClass('unhover');
		}).on('mouseleave', '.grid tr', function () {
			$(this).addClass('unhover');
			$(this).removeClass('hover');
		}).on('click', 'a.confirm', function () {
			return window.confirm($(this).attr('title') + '?');
		});

		// Nagłówek tabelki, nie jest zmieniany po sciągnięciu
		$("a.grid-spot").click(function () {
			$(this).trigger('change');
		});
		$("input.grid-spot").keydown(function (event) {
			if (event.which === 13) {
				event.preventDefault();
				$(this).trigger('change');
			}
		});
	};

	initSortable = function () {
		//dowiazanie sortable do tbody bez naglowka
		$('div.sortable tbody:not(:first)').sortable({
			items: 'tr:not(.last)',
			update: function (event, ui) {
				$.post(request.baseUrl + $('div.sortable').data().src, {
					value: $(this).sortable('toArray', {
						attribute: 'value'
					})
				},
				function (result) {
					if (result) {
						alert(result);
					}
				});
			}
		});
	};

	initGrid();
	initGridInputs();
	initSortable();
};

$(document).ready(function () {
	"use strict";
	CMS.grid();
});
