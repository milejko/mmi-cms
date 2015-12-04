/*jslint unparam: true, nomen: true, devel: true*/
/*global $, jQuery, document, window, request */

//konfiguracja ogólna pluploada
var PLUPLOADCONF = PLUPLOADCONF || {};

PLUPLOADCONF.settings = {
	runtimes: 'html5',
	url: request.baseUrl + '/cmsAdmin/upload/plupload',
	chunk_size: '8mb',
	file_data_name: 'file',
	rename: true,
	sortable: true,
	dragdrop: true,
	multi_selection: true,
	multiple_queues: true,
	multipart: true,
	max_retries: 3,
	filters: {
		prevent_duplicates: true
	},
	views: {
		list: true,
		thumbs: true,
		active: 'thumbs'
	},
	flash_swf_url: request.baseUrl + '/resource/cmsAdmin/js/plupload/Moxie.swf',
	silverlight_xap_url: request.baseUrl + '/resource/cmsAdmin/js/plupload/Moxie.xap',
	log_element: '',
	form_object: 'library',
	form_object_id: null
};

//zdarzenia
PLUPLOADCONF.settings.preinit = {
	Init: function (up, info) {
		PLUPLOADCONF.log(up, 'Uploader zasobów gotowy do przesyłania plików w trybie ' + info.runtime);
	},
	UploadFile: function (up, file) {
		up.setOption('multipart_params', {
			fileId: file.id,
			formObject: up.getOption('form_object'),
			formObjectId: up.getOption('form_object_id')
		});
	}
};

PLUPLOADCONF.settings.init = {
	FilesAdded: function (up, files) {
		plupload.each(files, function (file) {
			PLUPLOADCONF.log(up, 'Dodano do kolejki plik: ' + file.name);
		});
	},
	FilesRemoved: function (up, files) {
		plupload.each(files, function (file) {
			PLUPLOADCONF.log(up, 'Usunięto z kolejki plik: ' + file.name);
		});
	},
	FileUploaded: function (up, file, info) {
		PLUPLOADCONF.parseResponse(up, file, info);
	},
	ChunkUploaded: function (up, file, info) {
		PLUPLOADCONF.parseResponse(up, file, info);
	},
	UploadComplete: function (up, files) {
		PLUPLOADCONF.log(up, 'Przesyłanie plików zakończone...');
	},
	Error: function (up, err) {
		var str = "Wystąpił błąd: " + err.code + " - " + err.message + (err.file ? ", plik: " + err.file.name : "");
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
