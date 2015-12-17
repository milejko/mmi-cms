/*jslint unparam: true, nomen: true, devel: true*/
/*global $, jQuery, document, window, request */

//konfiguracja ogólna pluploada
var PLUPLOADCONF = PLUPLOADCONF || {};
var plupload = plupload || {};

PLUPLOADCONF.settings = {
	runtimes: 'html5',
	url: request.baseUrl + '/cmsAdmin/upload/plupload',
	file_data_name: 'file',
	chunk_size: '8mb',
	rename: false,
	sortable: false,
	dragdrop: true,
	multi_selection: true,
	multiple_queues: true,
	multipart: true,
	max_retries: 3,
	flash_swf_url: request.baseUrl + '/resource/cmsAdmin/js/plupload/Moxie.swf',
	silverlight_xap_url: request.baseUrl + '/resource/cmsAdmin/js/plupload/Moxie.xap',
	max_file_size: 0,
	max_file_cnt: 0,
	filters: {
		mime_types: []
	},
	views: {
		list: true,
		thumbs: true,
		active: 'thumbs'
	},
	log_element: '',
	form_element_id: '',
	form_object: 'library',
	form_object_id: null
};

//zdarzenia
PLUPLOADCONF.settings.preinit = {
	Init: function (up, info) {
		PLUPLOADCONF.log(up, 'Uploader zasobów gotowy do przesyłania plików w trybie ' + info.runtime);
		$.post(request.baseUrl + '/cmsAdmin/upload/current', {object: up.getOption('form_object'), objectId: up.getOption('form_object_id')}, 'json')
		.done(function (data) {
			if (data.result === 'OK') {
				var i, cf;
				$.each(data.files, function(i, cf) {
					var file = new plupload.File({
						name: cf.original,
						size: parseInt(cf.size),
						origSize: parseInt(cf.size),
						type: cf.mimeType,
						loaded: 0,
						percent: 0,
						status: plupload.QUEUED
					});
					file.cmsFileId = cf.id;
					file.getSource = function () {
						return false;
					};
					up.addFile(file);
				});
				plupload.each(up.files, function (file) {
					if (file.cmsFileId) {
						file.status = plupload.DONE;
						file.percent = 100;
						file.loaded = file.size;
						up.trigger("UploadProgress", {file: file});
					}
				});
				up.refresh();
			} else {
				up.trigger("Error", {code: 177, message: 'Pobranie aktualnych plików nie powiodło się'});
			}
		})
		.fail(function () {
			up.trigger("Error", {code: 177, message: 'Pobranie aktualnych plików nie powiodło się'});
		});
	},
	UploadFile: function (up, file) {
		up.setOption('multipart_params', {
			fileId: file.id,
			fileSize: file.origSize,
			formObject: up.getOption('form_object'),
			formObjectId: up.getOption('form_object_id'),
			filters: up.getOption('filters')
		});
	}
};

PLUPLOADCONF.settings.init = {
	FilesAdded: function (up, files) {
		plupload.each(files, function (file) {
			if (!file.cmsFileId) {
				PLUPLOADCONF.log(up, 'Dodano do kolejki plik: ' + file.name);
			} else if(file.type.indexOf('image') >= 0) { //plik odtworzony z serwera - pobranie minaiturki
				$.post(request.baseUrl + '/cmsAdmin/upload/thumbnail', {cmsFileId: file.cmsFileId}, 'json')
				.done(function (data) {
					if (data.result === 'OK' && data.url) {
						$('li#' + file.id + ' div.plupload_file_dummy').html('<img src="' + data.url + '" alt="" />');
					}
				})
				.fail(function () {});
			}
		});
	},
	FilesRemoved: function (up, files) {
		plupload.each(files, function (file) {
			if (!file.cmsFileId) {
				PLUPLOADCONF.log(up, 'Usunięto z kolejki plik: ' + file.name);
			} else {
				PLUPLOADCONF.log(up, 'Usunięto trwale plik: ' + file.name);
			}
		});
	},
	QueueChanged: function(up) {
		plupload.each(up.files, function (file) {
			$('#' + file.id + '.plupload_delete .ui-icon, #' + file.id + '.plupload_done .ui-icon').unbind('click');
			$('#' + file.id + '.plupload_delete .plupload_file_action .ui-icon, #' + file.id + '.plupload_done .plupload_file_action .ui-icon').click(function(event) {
				event.stopPropagation();
				//jeśli nowy plik - można łatwo usunąć
				if (!file.cmsFileId) {
					$('#' + file.id).remove();
					up.removeFile(file);
				} else {
					var confirm = '#' + up.getOption('form_element_id') + '-confirm';
					$(confirm + ' p span.confirm-info').text('Czy na pewno trwale usunąć plik ');
					$(confirm + ' p span.confirm-file').text(file.name);
					$(confirm + ' p span.confirm-info-2').text('?');
					$(confirm).dialog({
						resizable: false,
						width: 500,
						modal: true,
						closeText: 'Zamknij',
						title: 'Usunąć plik?',
						buttons: {
							'Usuń': function () {
								$.post(request.baseUrl + '/cmsAdmin/upload/delete', {cmsFileId: file.cmsFileId, object: up.getOption('form_object'), objectId: up.getOption('form_object_id')}, 'json')
								.done(function (data) {
									if (data.result === 'OK') {
										$('#' + file.id).remove();
										up.removeFile(file);
									} else {
										up.trigger("Error", {code: 178, message: 'Usunięcie pliku nie powiodło się'});
									}
								})
								.fail(function () {
									up.trigger("Error", {code: 178, message: 'Usunięcie pliku nie powiodło się'});
								});
								$(this).dialog('close');
							},
							'Anuluj': function () {
								$(this).dialog('close');
							}
						}
					});
				}
			});
			if (file.cmsFileId && $('#' + file.id + ' div.plupload_file_name span span.ui-icon-pencil').size() === 0) {
				$('#' + file.id + ' div.plupload_file_name span').prepend('<span class="ui-icon ui-icon-pencil"></span>');
			}
		});
		PLUPLOADCONF.sortable(up);
	},
	FileUploaded: function (up, file, info) {
		var result = PLUPLOADCONF.parseResponse(up, file, info);
		if (result === true) {
			if (file.cmsFileId && $('#' + file.id + ' div.plupload_file_name span span.ui-icon-pencil').size() === 0) {
				$('#' + file.id + ' div.plupload_file_name span').prepend('<span class="ui-icon ui-icon-pencil"></span>');
			}
		}
		$('#' + file.id + ' div.ui-icon-circle-check').removeClass('ui-icon-circle-check').addClass('ui-icon-circle-minus');
	},
	ChunkUploaded: function (up, file, info) {
		PLUPLOADCONF.parseResponse(up, file, info);
	},
	UploadComplete: function (up) {
		PLUPLOADCONF.sortable(up);
		PLUPLOADCONF.log(up, 'Przesyłanie plików zakończone...');
	},
	Error: function (up, err) {
		var str = "Wystąpił błąd: " + err.code + " - " + err.message;
		if (err.file !== undefined) {
			if ($.isArray(err.file)) {
				var i, file;
				$.each(err.file, function(i, file) {
					if (i) {
						str += ", ";
					} else {
						str += " Pliki: ";
					}
					str += file.name;
				});	
			} else {
				str += ", plik: " + err.file.name;
			}
		}
		PLUPLOADCONF.log(up, str);
		up.refresh();
	}
};

PLUPLOADCONF.settings.ready = function (event, args) {
	var list = 'ul#' + args.up.getOption('form_element_id') + '_filelist';
	$(list).on('click', 'li div.plupload_file_name span.ui-icon-pencil', function (e) {
		e.stopPropagation();
		var id = $(this).parents('li.plupload_file').attr('id');
		var file = args.up.getFile(id);
		if (!file || !file.cmsFileId) {
			//alert, że nie można edytować
			var confirm = '#' + args.up.getOption('form_element_id') + '-confirm';
			$(confirm + ' p span.confirm-info').text('Plik ');
			$(confirm + ' p span.confirm-file').text((file) ? file.name : '');
			$(confirm + ' p span.confirm-info-2').text(' nie został jeszcze prawidłowo przesłany na serwer. Nie można teraz edytować jego opisu!');
			$(confirm).dialog({
				resizable: false,
				width: 500,
				modal: true,
				closeText: 'Zamknij',
				title: 'Nie można edytować opisu pliku!',
				buttons: {
					'Ok': function () {
						$(this).dialog('close');
					}
				}
			});
		} else {
			//okienko edycji - pobieramy dane rekordu z bazy
			$.post(request.baseUrl + '/cmsAdmin/upload/details', {cmsFileId: file.cmsFileId}, 'json')
			.done(function (data) {
				if (data.result === 'OK' && data.record) {
					//przygotowujemy zawartość okienka edycji i pokazujemy go
				} else {
					args.up.trigger("Error", {code: 185, message: 'Pobranie opisu pliku nie powiodło się! Spróbuj ponownie'});
				}
			})
			.fail(function () {
				args.up.trigger("Error", {code: 185, message: 'Pobranie opisu pliku nie powiodło się! Spróbuj ponownie'});
			});
		}
		return false;
	});
};

PLUPLOADCONF.settings.selected = function (event, args) {
	//maksymalna ilość plików możliwa do przesłania
	var max = args.up.getOption('max_file_cnt');
	if (max > 0) {
		var removed = [], selectedCount = args.files.length;
		var extraCount = args.up.files.length - max;
		if (extraCount > 0) {
			removed = args.files.splice(selectedCount - extraCount, extraCount);
			args.up.trigger("Error", {
				code: 190,
				message: 'Maksymalna ilość plików do przesłania to ' + max + '. Nadliczbowe pliki zostały usunięte!',
				file: removed
			});
			plupload.each(removed, function (file) {
				var selector = '#' + args.up.getOption('form_element_id') + ' li#' + file.id;
				$(selector).remove();
				args.up.removeFile(file);
			});
		} else if (extraCount === 0) {
			$(this).plupload("disable");
		}
	}
};

PLUPLOADCONF.settings.removed = function (event, args) {
	//maksymalna ilość plików możliwa do przesłania
	var max = args.up.getOption('max_file_cnt');
	if (max > 0) {;
		if (max - args.up.files.length > 0) {
			$(this).plupload("enable");
		}
	}
};

PLUPLOADCONF.settings.complete = function (event, args) {
	//maksymalna ilość plików możliwa do przesłania
	var max = args.up.getOption('max_file_cnt');
	if (max > 0) {;
		if (max - args.up.files.length === 0) {
			$(this).plupload("disable");
		}
	}
};

PLUPLOADCONF.log = function(up, str) {
	var logElem = up.getOption('log_element');
	if (typeof logElem !== 'string' || !logElem.length) {
		return;
	}
	var log = $('#' + logElem);
	log.append(str + "\n");
	log.scrollTop(log[0].scrollHeight);
};

PLUPLOADCONF.parseResponse = function(up, file, info) {
	var response;
	try {
		response = $.parseJSON(info.response);
	} catch (err) {
		response = undefined;
	}
	if (typeof response !== 'undefined' && response.result === 'OK') {
		if (response.cmsFileId) {
			file.cmsFileId = response.cmsFileId;
		}
		return true;
	}
	var code;
	var message;
	if (typeof response !== 'undefined' && typeof response.error !== 'undefined') {
		code = response.error.code;
		message = response.error.message;
	} else {
		code = 111;
		message = "Wystąpił nieznany błąd";
	}
	up.trigger("Error", {
		code: code,
		message: message,
		file: file
	});
	return false;
};

PLUPLOADCONF.sortable = function(up) {
	var selector = '#' + up.getOption('form_element_id') + '_filelist';
	$(selector).sortable({
		items: '> li.plupload_file',
		cursor: 'move',
		disabled: true,
		stop: function(e, ui) {
			var i, files = [];
			$.each($(this).sortable('toArray'), function(i, id) {
				var file = up.getFile(id);
				if (file.cmsFileId) {
					files[files.length] = file.cmsFileId;
				}
			});
			$.post(request.baseUrl + '/cmsAdmin/upload/sort', {order: files}, 'json')
			.done(function (data) {
				if (data.result !== 'OK') {
					up.trigger("Error", {code: 180, message: 'Zapis kolejności plików nie powiódł się'});
				}
			})
			.fail(function () {
				up.trigger("Error", {code: 180, message: 'Zapis kolejności plików nie powiódł się'});
			});
		}
	});
	var enable = true;
	plupload.each(up.files, function (file) {
		if (!file.cmsFileId) {
			enable = false;
		}
	});
	if (enable && up.files.length > 1) {
		$(selector).sortable('enable');
	}
};
