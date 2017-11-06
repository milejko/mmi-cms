/*jslint unparam: true, nomen: true, devel: true*/
/*global $, jQuery, document, window, request */

//konfiguracja ogólna pluploada
var PLUPLOADCONF = PLUPLOADCONF || {};
var plupload = plupload || {};
var tinymce = tinymce || {};

PLUPLOADCONF.settings = {
    runtimes: 'html5',
    url: request.baseUrl + '/cmsAdmin/upload/plupload',
    file_data_name: 'file',
    chunk_size: '2mb',
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
    autostart: true,
    buttons: {
        browse: true,
        start: false,
        stop: false
    },
    log_element: '',
    form_element_id: '',
    form_object: 'library',
    form_object_id: null,
    file_types: '',
    after_upload: {},
    after_delete: {},
    after_edit: {},
    replace_file: null,
    edit_dialog: null,
    refresh_current: false
};

//zdarzenia
PLUPLOADCONF.settings.preinit = {
    Init: function (up, info) {
        PLUPLOADCONF.log(up, 'Uploader zasobów gotowy do przesyłania plików w trybie ' + info.runtime);
        //pobranie i odtworzenie aktualnej listy z serwera
        PLUPLOADCONF.getCurrent(up);
    },
    UploadFile: function (up, file) {
        up.setOption('multipart_params', {
            fileId: file.id,
            fileSize: file.origSize,
            formObject: up.getOption('form_object'),
            formObjectId: up.getOption('form_object_id'),
            cmsFileId: ((file.cmsFileId) ? file.cmsFileId : 0),
            filters: up.getOption('filters'),
            afterUpload: up.getOption('after_upload')
        });
    }
};

PLUPLOADCONF.settings.init = {
    FilesAdded: function (up, files) {
        plupload.each(files, function (file) {
            if (!file.cmsFileId) {
                PLUPLOADCONF.log(up, 'Dodano do kolejki plik: ' + file.name);
                up.setOption('refresh_current', true);
                //jeśli plik ma zastąpić inny
                var replaceFile = up.getOption('replace_file');
                if (replaceFile !== null) {
                    file.cmsFileId = replaceFile.cmsFileId;
                    up.setOption('replace_file', null);
                    up.removeFile(replaceFile);
                    //czy jest otwarte okienko edycji pliku
                    var editDialog = up.getOption('edit_dialog');
                    if (editDialog !== null) {
                        editDialog.dialog('close');
                    }
                    PLUPLOADCONF.editable(up, file);
                }
            } else if (file.type.indexOf('image') >= 0) { //plik odtworzony z serwera - pobranie minaiturki
                $.post(request.baseUrl + '/cmsAdmin/upload/thumbnail', {cmsFileId: file.cmsFileId}, 'json')
                        .done(function (data) {
                            if (data.result === 'OK' && data.url) {
                                $('div#' + up.getOption('form_element_id') + ' li#' + file.id + ' div.plupload_file_dummy').html('<img src="' + data.url + '" alt="" />');
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
    QueueChanged: function (up) {
        plupload.each(up.files, function (file) {
            $('div#' + up.getOption('form_element_id') + ' li#' + file.id + '.plupload_delete .ui-icon, div#' + up.getOption('form_element_id') + ' li#' + file.id + '.plupload_done .ui-icon').unbind('click');
            $('div#' + up.getOption('form_element_id') + ' li#' + file.id + '.plupload_delete .plupload_file_action .ui-icon, div#' + up.getOption('form_element_id') + ' li#' + file.id + '.plupload_done .plupload_file_action .ui-icon').click(function (event) {
                event.stopPropagation();
                //jeśli nowy plik - można łatwo usunąć
                if (!file.cmsFileId) {
                    $('div#' + up.getOption('form_element_id') + ' li#' + file.id).remove();
                    up.removeFile(file);
                } else {
                    var confirm = 'div#' + up.getOption('form_element_id') + '-confirm';
                    $(confirm + ' p span.dialog-info').text('Czy na pewno trwale usunąć plik ');
                    $(confirm + ' p span.dialog-file').text(file.name);
                    $(confirm + ' p span.dialog-info-2').text('?');
                    $(confirm).dialog({
                        resizable: false,
                        width: 600,
                        modal: true,
                        closeText: 'Zamknij',
                        title: 'Usunąć plik?',
                        buttons: {
                            'Usuń': function () {
                                $.post(request.baseUrl + '/cmsAdmin/upload/delete', {cmsFileId: file.cmsFileId, object: up.getOption('form_object'), objectId: up.getOption('form_object_id'), afterDelete: up.getOption('after_delete')}, 'json')
                                        .done(function (data) {
                                            if (data.result === 'OK') {
                                                $('div#' + up.getOption('form_element_id') + ' li#' + file.id).remove();
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
            PLUPLOADCONF.editable(up, file);
        });
        PLUPLOADCONF.sortable(up);
    },
    FileUploaded: function (up, file, info) {
        var result = PLUPLOADCONF.parseResponse(up, file, info);
        if (result === true) {
            PLUPLOADCONF.editable(up, file);
        }
        $('div#' + up.getOption('form_element_id') + ' li#' + file.id + ' div.ui-icon-circle-check').removeClass('ui-icon-circle-check').addClass('ui-icon-circle-minus');
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
                $.each(err.file, function (i, file) {
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
    args.up.setOption('that', $(this));
    //kliknięcie w górną belkę
    $('div#' + args.up.getOption('form_element_id')).on('click', 'div.plupload_logo,div.plupload_header_title', function () {
        $('div#' + args.up.getOption('form_element_id') + ' div.moxie-shim-html5 input[type=file]').trigger('click');
    });
    //lista plików
    var list = 'ul#' + args.up.getOption('form_element_id') + '_filelist';
    $(list).on('click', 'li div.plupload_file_name span.ui-icon-pencil', function (e) {
        e.stopPropagation();
        var id = $(this).parents('li.plupload_file').first().attr('id');
        var file = args.up.getFile(id);
        if (!file || !file.cmsFileId) {
            //alert, że nie można edytować
            var confirm = 'div#' + args.up.getOption('form_element_id') + '-confirm';
            $(confirm + ' p span.dialog-info').text('Plik ');
            $(confirm + ' p span.dialog-file').text((file) ? file.name : '');
            $(confirm + ' p span.dialog-info-2').text(' nie został jeszcze prawidłowo przesłany na serwer. Nie można teraz edytować jego opisu!');
            $(confirm).dialog({
                resizable: false,
                width: 600,
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
            var editIcon = $(this);
            editIcon.removeClass('ui-icon-pencil').addClass('ui-icon-gear');
            //okienko edycji - pobieramy dane rekordu z bazy
            $.post(request.baseUrl + '/cmsAdmin/upload/details', {cmsFileId: file.cmsFileId}, 'json')
                    .done(function (data) {
                        if (data.result === 'OK' && data.record) {

                            //refresh background select
                            if (args.up.getOption('preview')) {
                                $.post(request.baseUrl + '/?module=commonAdmin&controller=widget&action=multimediaBackgroundJson', {idrecord: args.up.getOption('form_object_id')}, 'json')
                                        .done(function (dane) {
                                            var selBackground = $('#commonadmin-form-multimediaeditform-uploadMultimedia-background');
                                            selBackground.empty();
                                            $('<option>').val('').text('---').appendTo(selBackground);
                                            $.each(dane.background, function (key, val) {
                                                if (val.data) {
                                                    $('<option>', {
                                                        'data-image-url': val.data.data_image_url
                                                    }).val(key).text(val.value).appendTo(selBackground);
                                                }
                                            });
                                            selBackground.val(data.data['background']).change();
                                        });
                            }

                            //przygotowujemy zawartość okienka edycji i pokazujemy go
                            var edit = 'div#' + args.up.getOption('form_element_id') + '-edit';
                            $(edit + ' > fieldset > .imprint').each(function () {
                                var fieldName = $(this).attr('name');
                                if ($(this).attr('type') === 'checkbox') {
                                    $(this).prop('checked', (parseInt(data.data[fieldName])) > 0 ? 'checked' : '');
                                } else {
                                    $(this).val(data.data[fieldName]).change();
                                }
                            });
                            $(edit + ' input[name="original"]').val(data.record.original);
                            $(edit + ' input[name="active"]').prop('checked', (parseInt(data.record.active) > 0) ? 'checked' : '');
                            $(edit + ' input[name="sticky"]').prop('checked', (parseInt(data.record.sticky) > 0) ? 'checked' : '');
                            $(edit + ' .dialog-error').hide().find('p').text('');
                            //inicjalizacja tinyMce
                            PLUPLOADCONF.initTinyMce(args.up);
                            var editDialog = $(edit).dialog({
                                resizable: false,
                                width: 700,
                                modal: true,
                                closeText: 'Zamknij',
                                title: 'Edycja opisu pliku: ' + file.name,
                                dialogClass: 'ui-state-default',
                                buttons: {
                                    'Zapisz': function () {
                                        //trigger odświeżający dane
                                        tinymce.triggerSave();
                                        $.post(request.baseUrl + '/cmsAdmin/upload/describe', {cmsFileId: file.cmsFileId, form: $(edit + ' input,' + edit + ' textarea,' + edit + ' select').serializeArray(), afterEdit: args.up.getOption('after_edit')}, 'json')
                                                .done(function (data) {
                                                    if (data.result === 'OK') {
                                                        //pobranie i odtworzenie aktualnej listy z serwera
                                                        args.up.setOption('refresh_current', true);
                                                        PLUPLOADCONF.getCurrent(args.up);
                                                        args.up.refresh();
                                                        editDialog.dialog('close');
                                                    } else {
                                                        $(edit + ' .dialog-error p').text('Nie udało się zapisać zmian! Spróbuj ponownie!').parent().show();
                                                    }
                                                })
                                                .fail(function () {
                                                    $(edit + ' .dialog-error p').text('Nie udało się zapisać zmian! Spróbuj ponownie!').parent().show();
                                                });
                                    },
                                    'Zastąp plik': function () {
                                        args.up.setOption('replace_file', file);
                                        args.up.getOption('that').plupload("enable");
                                        setTimeout(function () {
                                            $('div#' + args.up.getOption('form_element_id') + ' div.moxie-shim-html5 input[type=file]').trigger('click');
                                        }, 500);
                                    },
                                    'Pobierz plik': function () {
                                        window.open(request.baseUrl + '/cmsAdmin/upload/download?id=' + file.cmsFileId + '&object=' + args.up.getOption('form_object') + '&objectId=' + args.up.getOption('form_object_id'), '_blank');
                                    },
                                    'Anuluj': function () {
                                        $(this).dialog('close');
                                    }
                                },
                                open: function (event, ui) {
                                    args.up.setOption('edit_dialog', $(this));
                                },
                                close: function (event, ui) {
                                    args.up.setOption('replace_file', null);
                                    args.up.setOption('edit_dialog', null);
                                    //maksymalna ilość plików możliwa do przesłania
                                    var max = args.up.getOption('max_file_cnt');
                                    if (max > 0) {
                                        if (max - args.up.files.length <= 0) {
                                            args.up.getOption('that').plupload("disable");
                                        }
                                    }
                                }
                            });
                        } else {
                            args.up.trigger("Error", {code: 185, message: 'Pobranie opisu pliku nie powiodło się! Spróbuj ponownie'});
                        }
                    })
                    .fail(function () {
                        args.up.trigger("Error", {code: 185, message: 'Pobranie opisu pliku nie powiodło się! Spróbuj ponownie'});
                    })
                    .always(function () {
                        editIcon.removeClass('ui-icon-gear').addClass('ui-icon-pencil');
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
        //jeśli jakiś plik ma zastąpić inny, chwilowo dopuszczamy o jeden za dużo na liście
        var replaceFile = args.up.getOption('replace_file');
        if (replaceFile !== null) {
            extraCount--;
        }
        if (extraCount > 0) {
            removed = args.files.splice(selectedCount - extraCount, extraCount);
            args.up.trigger("Error", {
                code: 190,
                message: 'Maksymalna ilość plików do przesłania to ' + max + '. Nadliczbowe pliki zostały usunięte!',
                file: removed
            });
            plupload.each(removed, function (file) {
                var selector = 'div#' + args.up.getOption('form_element_id') + ' li#' + file.id;
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
    if (max > 0) {
        if (max - args.up.files.length > 0) {
            $(this).plupload("enable");
        }
    }
};

PLUPLOADCONF.settings.complete = function (event, args) {
    //maksymalna ilość plików możliwa do przesłania
    var max = args.up.getOption('max_file_cnt');
    if (max > 0) {
        if (max - args.up.files.length === 0) {
            $(this).plupload("disable");
        }
    }
    //pobranie i odświeżenie aktualnej listy z serwera
    PLUPLOADCONF.getCurrent(args.up, $(this));
};

PLUPLOADCONF.log = function (up, str) {
    var logElem = up.getOption('log_element');
    if (typeof logElem !== 'string' || !logElem.length) {
        return;
    }
    var log = $('#' + logElem);
    log.append(str + "\n");
    log.scrollTop(log[0].scrollHeight);
};

PLUPLOADCONF.parseResponse = function (up, file, info) {
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
    var code, message;
    if (typeof response !== 'undefined' && typeof response.error !== 'undefined') {
        code = response.error.code;
        message = response.error.message;
    } else {
        code = 111;
        message = "Wystąpił nieznany błąd";
    }
    up.trigger("Error", {code: code, message: message, file: file});
    return false;
};

PLUPLOADCONF.getCurrent = function (up, pluploadObject) {
    if (pluploadObject) {
        if (up.getOption('refresh_current') === true) {
            up.setOption('refresh_current', false);
            $('ul#' + up.getOption('form_element_id') + '_filelist > li').remove();
            pluploadObject.plupload("clearQueue");
        } else {
            return;
        }
    }
    $.post(request.baseUrl + '/cmsAdmin/upload/current', {object: up.getOption('form_object'), objectId: up.getOption('form_object_id'), fileTypes: up.getOption('file_types')}, 'json')
            .done(function (data) {
                if (data.result === 'OK') {
                    var i, cf;
                    $.each(data.files, function (i, cf) {
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
};

PLUPLOADCONF.sortable = function (up) {
    var selector = 'ul#' + up.getOption('form_element_id') + '_filelist';
    $(selector).sortable({
        items: '> li.plupload_file',
        cursor: 'move',
        disabled: true,
        stop: function (e, ui) {
            var i, files = [];
            $.each($(this).sortable('toArray'), function (i, id) {
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

PLUPLOADCONF.editable = function (up, file) {
    if (file.cmsFileId && $('div#' + up.getOption('form_element_id') + ' li#' + file.id + ' div.plupload_file_name span span.ui-icon-pencil').length === 0) {
        $('div#' + up.getOption('form_element_id') + ' li#' + file.id + ' div.plupload_file_name span').prepend('<span class="ui-icon ui-icon-pencil"></span>');
    }
};

PLUPLOADCONF.initTinyMce = function (up) {
    var selector = 'div#' + up.getOption('form_element_id') + '-edit textarea.plupload-edit-tinymce';
    tinymce.init({
        selector: selector,
        language: 'pl'
    });
};
