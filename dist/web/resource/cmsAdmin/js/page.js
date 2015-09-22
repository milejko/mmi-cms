var CMSADMIN = CMSADMIN || {};

CMSADMIN.composer = function () {
	"use strict";

	var that = {},
			init,
			bind,
			unbind,
			toggle,
			save,
			configurator,
			takenSpaceInSection,
			composerRoot,
			toolkitRoot,
			compilationRoot,
			configuratorRoot,
			saveEndpoint;

	init = function () {
		composerRoot = $('.cms-page-composer');
		toolkitRoot = $('.cms-page-composer-toolkit');
		compilationRoot = $('.cms-page-composer-compilation');
		configuratorRoot = $('.cms-page-composer-configurator');
		saveEndpoint = request.baseUrl + '/?module=cmsAdmin&controller=page&action=update';

		toolkitRoot.find('.template').draggable({
			revert: true,
			snap: false
		});

		toolkitRoot.find('input:radio[name="wrapper"]').change(function () {
			toolkitRoot.find('.template.drag-section').attr('options', $(this).val());
		});

		toolkitRoot.find('input:radio[name="align"]').change(function () {
			var position = $(this).val();
			if (toolkitRoot.find('input:checkbox[name="stretch"]').is(':checked')) {
				position += ' stretch';
			}
			toolkitRoot.find('.template.drag-placeholder').attr('options', position);
		});

		toolkitRoot.find('.preview').click(function () {
			toggle();
		});

		toolkitRoot.find('.save').click(function () {
			save();
		});

		bind();

		return that;
	};
	that.init = init;

	// przypinanie sortowania, zmiany rozmiaru, upuszczania do elementow composera
	bind = function () {

		// wysrodkowuje widok klockow
		composerRoot.parent().css({'width': '952px', 'margin': '0 auto'});

		// chowa pokazuje widgety
		composerRoot.find('.composer-widget').show();

		// sortowanie placeholderow w poziomie
		composerRoot.find('.section').sortable({
			items: '> .placeholder',
			opacity: 0.5,
			axis: 'x',
			tolerance: 'pointer',
			placeholder: 'holder',
			start: function (event, ui) {
				$('.holder').addClass(ui.item[0].className);
			}
		});

		// sortowanie sekcji w pionie
		composerRoot.find('.placeholder').addBack().sortable({
			items: '> section',
			opacity: 0.5,
			axis: 'y',
			tolerance: 'pointer',
			placeholder: 'holder section'
		});

		// zmiana rozmiaru placeholderow
		composerRoot.find('.placeholder').resizable({
			handles: 'e',
			create: function (event, ui) {
				$(this).resizable('option', 'grid', $(this).parent().width() / 12);
			},
			resize: function (event, ui) {
				//obliczanie bieżącej szerokości
				var currentWidth = Math.round($(this).width() / ($(this).parent().width() / 12));

				//czyszczenie css po obliczeniach na podstawie rozmiaru
				$(this).parent().find('.placeholder').removeAttr('data-current');
				$(this).parent().find('.placeholder').removeAttr('style');
				$(this).attr('data-pool-excluded', '1');

				//przestawienie szerokości jeśli > 1 i nie przepełnia puli
				if (currentWidth > 0 && (currentWidth + takenSpaceInSection($(this).parent())) < 13) {
					$(this).removeAttr('class');
					$(this).addClass('placeholder ui-resizable span-' + currentWidth + '-of-12');
				}
				$(this).removeAttr('data-pool-excluded');
			}
		});

		// placeholdery do drugiego poziomu sa upuszczalne dla widgetow i sekcji
		composerRoot.find('> .section > .placeholder, > .section > .placeholder > .section > .placeholder').addBack().droppable({
			accept: '.template.drag-section, .template.drag-widget',
			greedy: true,
			tolerance: 'pointer',
			drop: function (event, ui) {
				//jeśli upuszczamy widget w placeholder i w placeholderze brak sekcji
				if ($(this).hasClass('placeholder') && ui.draggable.hasClass('drag-widget') && $(this).find('> .section').size() === 0 && $(this).find('> .composer-widget').size() === 0) {
					$(this).append('<div class="composer-widget" data-widget="' + ui.draggable.attr('data-widget') + '">' + ui.draggable.attr('data-widget') + '</section>');
					configurator($(this));
				}
				if (ui.draggable.hasClass('drag-section') && $(this).find('> .composer-widget').size() === 0 && ($(this).parent().parent().hasClass('compose') || $(this).hasClass('compose'))) {
					$(this).append('<section class="section ' + ui.draggable.attr('options') + '"></section>');
				}
				unbind();
				bind();
			}
		});

		// upuszczalna sekcja dla placeholdera i ustawienie rozmiaru upuszczanego placeholdera
		composerRoot.find('.section').droppable({
			accept: '.template.drag-placeholder',
			greedy: true,
			tolerance: 'pointer',
			drop: function (event, ui) {
				var freeSpace = 12 - takenSpaceInSection($(this));
				if (freeSpace <= 0) {
					return false;
				}
				$(this).append('<div class="placeholder span-' + freeSpace + '-of-12 ' + ui.draggable.attr('options') + '"></div>');
				unbind();
				bind();
			}
		});

		// usuwanie widgetu, placeholdera lub sekcji po dwukrotnych kliknieciu
		composerRoot.find('.section, .placeholder, .composer-widget').on('dblclick', function () {
			if ($(this).hasClass('composer-widget')) {
				$(this).parent().empty();
			} else {
				$(this).remove();
			}
			return false;
		});

		// dodanie klasy composera
		composerRoot.addClass('compose');

	};
	that.bind = bind;

	// odpinanie zdarzen composera, tzn. podglad ulozonej strony
	unbind = function () {
		if (!composerRoot.hasClass('compose')) {
			return;
		}
		composerRoot.parent().removeAttr('style');
		composerRoot.find('.composer-widget').hide();
		composerRoot.find('.placeholder, .section').addBack().sortable().sortable('destroy');
		composerRoot.find('.placeholder').resizable().resizable('destroy');
		composerRoot.find('.placeholder, .section').addBack().droppable().droppable('destroy');
		composerRoot.find('.section, .placeholder, .composer-widget').off('dblclick');
		composerRoot.removeClass('compose');
	};
	that.unbind = unbind;

	// wlaczanie/wylaczanie podgladu
	toggle = function () {
		if (!composerRoot.hasClass('compose')) {
			return bind();
		}
		return unbind();
	};
	that.toggle = toggle;

	// przesyłanie ajax'em struktury layoutu do bazy
	save = function () {
		unbind();
		compilationRoot.html(composerRoot.html());
		compilationRoot.find('.placeholder').each(function () {
			if ($(this).find('.composer-widget').attr('data-widget') !== undefined) {
				$(this).html('{widget(' + $(this).find('.composer-widget').attr('data-widget') + ')}');
			}
		});
		$.ajax({
			type: 'POST',
			url: saveEndpoint,
			data: {id: request.id, data: compilationRoot.html()}
		}).done(function () {
			bind();
		});
	};

	configurator = function (placeholder) {
		configuratorRoot.dialog({
			autoOpen: true,
			height: 500,
			width: 500,
			modal: true,
			resizable: false
//			close: function () {
//				placeholder.empty();
//			}
		});
	};

	// ile miejsca zajete w sekcji
	takenSpaceInSection = function (element) {
		var pool = 0;
		element.find('> .placeholder').each(function () {
			if (!($(this).attr('data-pool-excluded') === '1')) {
				pool = pool + parseInt($(this).attr('class').match(/span\-([1-9]|10|11|12)\-of\-12/)[1]);
			}
		});
		return pool;
	};

	return that;
};

$(document).ready(function () {

	CMSADMIN.composer = CMSADMIN.composer().init();

});