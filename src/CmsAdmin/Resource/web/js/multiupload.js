$(document).ready(function () {
    multiuploadInitLists(('.multiupload'));
});

function multiuploadInitLists(lists) {
    $(lists).each(function (index, list) {
        let containerId = $(list).attr('id');
        multiuploadInitContainer(containerId);
    });
}

function multiuploadInitContainer(containerId) {
    multiuploadInitThumbs(containerId);
    multiuploadInitAdd(containerId);
    multiuploadInitScroll(containerId);
    multiuploadInitToggleAuto(containerId)
}

function multiuploadInitScroll(containerId) {
    let uploader = $('#' + containerId + ' .upload-add-label');
    let uploaderHeight = uploader.outerHeight();
    let list = uploader.closest('.multiupload');

    $(window).scroll(function () {
        let windowScroll = $(window).scrollTop();
        let listTop = list.offset().top;
        let listHeight = list.outerHeight();

        if (listHeight > 2 * uploaderHeight) {
            if (windowScroll >= listTop - 65 && windowScroll <= listTop + listHeight - uploaderHeight + 120) {
                list.addClass('multiupload-fixed');
                if (windowScroll >= listTop + listHeight - uploaderHeight - 130) {
                    list.addClass('multiupload-fixed-bottom');
                } else {
                    list.removeClass('multiupload-fixed-bottom');
                }
            } else {
                list.removeClass('multiupload-fixed');
                list.removeClass('multiupload-fixed-bottom');
            }
        }
    });
}

function multiuploadInitThumbs(containerId) {
    $('#' + containerId + ' > .field-list > li').each(function () {
        multiuploadLoadThumb($(this).find('input[type=hidden]'));
    });
}

function multiuploadLoadThumb(sourceInput) {
    if ($(sourceInput).parent().find('.thumb').length < 1) {
        let uploader = sourceInput.closest('.multiupload').find('.upload-add-label input[type=file]');
        $.ajax({
            url: uploader.data('thumb-url'),
            type: 'POST',
            data: {
                'object': uploader.data('object'),
                'objectId': uploader.data('object-id'),
                'cmsFileName': sourceInput.attr('value')
            }
        }).done(function (response) {
            let thumb = $(sourceInput).closest('.field-list-item').find('.thumb img');
            if ('undefined' !== typeof response.thumb) {
                $(thumb).attr('src', response.thumb);
            }
            if ('undefined' !== typeof response.class) {
                $(thumb).attr('src', uploader.data('icons-url') + response.class + '.svg');
                $(thumb).addClass('file-icon');
            }
        });
    }
}

function multiuploadUpdateProgress(containerId, progress) {
    let container = $('#' + containerId)
    let progressBar = container.find('.multiupload-progress-bar .progress');
    progressBar.css('width', progress + '%');

    if (progress === 100) {
        let icon = container.find('.upload-add-label .icon');
        icon.addClass('pulse');
        setTimeout(function () {
            icon.removeClass('pulse');
        }, 300);
    }
}

function multiuploadInitAdd(containerId) {
    $(document).off('change', '#' + containerId + ' .upload-add');
    $(document).on('change', '#' + containerId + ' .upload-add', function (e) {
        e.preventDefault();

        let template = $(this).data('template');
        let list = $(this).closest('.multifield').find('.field-list').first();
        let uploadBar = $(this).closest('.multiupload').find('.multiupload-progress-bar');
        let uploadUrl = $(this).data('upload-url');
        let object = $(this).data('object');
        let objectId = $(this).data('object-id');
        let fileId = $(this).data('file-id');

        multiuploadUpdateProgress(containerId, 0);
        setTimeout(function () {
            uploadBar.addClass('active');
        }, 100);

        const chunkSize = 1024 * 512;
        let files = $(this).prop('files');
        let filesCompleted = 0;
        let filesTotal = files.length;

        $.each(files, function (index, file) {
            let reader = new FileReader();
            let total = file.size;
            let parts = Math.ceil(file.size / chunkSize);
            let partsLoaded = 0;
            let loaded = 0;
            let blob = file.slice(0, chunkSize);
            let cmsFileName = '';

            reader.readAsArrayBuffer(blob);
            reader.onload = function (e) {
                let chunk = blob;
                let formData = new FormData();

                formData.append('name', file.name);
                formData.append('chunk', partsLoaded);
                formData.append('chunks', parts);
                formData.append('fileId', fileId);
                formData.append('fileSize', total);
                formData.append('formObject', object);
                formData.append('formObjectId', objectId);
                formData.append('cmsFileName', '');
                formData.append('filters[max_file_size]', 0);
                formData.append('filters[prevent_duplicates]', false);
                formData.append('filters[prevent_empty]', true);
                formData.append('file', chunk);

                $.ajax({
                    url: uploadUrl,
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: formData
                }).done(function (response) {
                    cmsFileName = response.cmsFileName;
                    loaded += chunkSize;
                    partsLoaded += 1;

                    if (filesTotal === 1) {
                        multiuploadUpdateProgress(containerId, loaded / total * 100);
                    }

                    if (loaded <= total) {
                        blob = file.slice(loaded, loaded + chunkSize);
                        reader.readAsArrayBuffer(blob);
                    } else {
                        filesCompleted += 1;
                        multiuploadUpdateProgress(containerId, filesCompleted / filesTotal * 100);

                        $(list).append(
                            multifieldListItemTemplate[template]
                                .replaceAll('**', $(list).children().length)
                                .replaceAll('##', $(list).parents('.field-list-item').last().index())
                                .replaceAll('{{cmsFileName}}', cmsFileName)
                        );
                        let newItem = $(list).children('.field-list-item').last();
                        newItem.find('.select2').select2();
                        multifieldInitContainer(containerId);
                        multiuploadInitContainer(containerId);
                        multifieldToggleActive(newItem);

                        if (filesCompleted === filesTotal) {
                            setTimeout(function () {
                                uploadBar.removeClass('active');
                            }, 200);
                        }
                    }
                });
            };
        });
    });
}

function multiuploadInitToggleAuto(containerId) {
    $(document).off('focus', '#' + containerId + ' .form-group:nth-child(3) > input[type=text]');
    $(document).on('focus', '#' + containerId + ' .form-group:nth-child(3) > input[type=text]', function (e) {
        if (false === $(this).closest('.field-list-item').hasClass('active')) {
            multifieldToggleMultifieldItem($(this).closest('.field-list-item'));
        }
    });
}
