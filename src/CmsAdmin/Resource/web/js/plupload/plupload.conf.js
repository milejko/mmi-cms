/*jslint unparam: true, nomen: true, devel: true*/
/*global $, jQuery, document, window, request */

//konfiguracja ogólna pluploada
var PLUPLOADCONF = PLUPLOADCONF || {};

PLUPLOADCONF.settings = {
	runtimes: 'html5',
	url: request.baseUrl + '/cmsAdmin/upload/plupload',
	file_data_name: 'file',
	chunk_size: '8mb',
	rename: true,
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
			formObjectId: up.getOption('form_object_id')
		});
	}
};

PLUPLOADCONF.settings.init = {
	FilesAdded: function (up, files) {
		//maksymalna ilość plików możliwa do przesłania
		var max = up.getOption('max_file_cnt');
		if (max > 0) {
			var removed = [], selectedCount = files.length;
			var extraCount = up.files.length - max;
			if (extraCount > 0) {
				removed = files.splice(selectedCount - extraCount, extraCount);
				up.trigger("Error", {
					code: 190,
					message: 'Maksymalna ilość plików do przesłania to ' + max + '. Nadliczbowe pliki zostały usunięte',
					file: removed
				});
				plupload.each(removed, function (file) {
					try {
						setTimeout(function(){ up.removeFile(file); }, 100);
					} catch(err) {
					}
				});
			}
		}
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
			$('#' + file.id + '.plupload_delete .ui-icon, #' + file.id + '.plupload_done .ui-icon').click(function(event) {
				event.stopPropagation();
				//jeśli nowy plik - można łatwo usunąć
				if (!file.cmsFileId) {
					$('#' + file.id).remove();
					up.removeFile(file);
				} else {
					$('#' + up.getOption('form_element_id') + '-confirm p span').text(' ' + file.name);
					$('#' + up.getOption('form_element_id') + '-confirm').dialog({
						resizable: false,
						width: 500,
						modal: true,
						closeText: 'Zamknij',
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
		});
		PLUPLOADCONF.sortable(up);
	},
	FileUploaded: function (up, file, info) {
		PLUPLOADCONF.parseResponse(up, file, info);
	},
	ChunkUploaded: function (up, file, info) {
		PLUPLOADCONF.parseResponse(up, file, info);
	},
	UploadComplete: function (up, files) {
		plupload.each(files, function (file) {
			$('#' + file.id + ' div.ui-icon-circle-check').removeClass('ui-icon-circle-check').addClass('ui-icon-circle-minus');
		});
		PLUPLOADCONF.sortable(up);
		PLUPLOADCONF.log(up, 'Przesyłanie plików zakończone...');
	},
	Error: function (up, err) {
		var str = "Wystąpił błąd: " + err.code + " - " + err.message;
		if (err.file !== undefined) {
			plupload.each(err.file, function (file) {
				str += ", plik: " + file.name;
			});
		}
		PLUPLOADCONF.log(up, str);
		up.refresh();
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
	if (enable) {
		$(selector).sortable('enable');
	}
};
