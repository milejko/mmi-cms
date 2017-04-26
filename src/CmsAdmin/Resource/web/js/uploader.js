$(document).ready(function () {

    function bindSortable() {

        $("#manageImage").sortable({
            axis: 'x',
            update: function (event, ui) {
                $.post(
                        request.baseUrl + "/?module=cmsAdmin&controller=file&action=sort", $(this).sortable('serialize'),
                        function (result) {
                            if (result) {
                                alert(result);
                            }
                        });
            }
        });

        $('#manageOther').sortable({
            axis: 'x',
            update: function (event, ui) {
                $.post(
                        request.baseUrl + "/?module=cmsAdmin&controller=file&action=sort", $(this).sortable('serialize'),
                        function (result) {
                            if (result) {
                                alert(result);
                            }
                        });
            }
        });

    }

    $('body').on('click', 'a.edit-file', function () {
        var id = $(this).attr('id').split('-');
        $('li').removeClass('editActive');
        $('#item-file-' + id[2]).addClass('editActive');
        $.getJSON(
                request.baseUrl + '/?module=cmsAdmin&controller=file&action=edit&id=' + id[2] + '&hash=' + id[3],
                function (result) {
                    if (result.error != undefined) {
                        alert(result.error);
                        return false;
                    }
                    $('#fileUpload').hide();
                    $('#uploaderEdit').show();
                    $('#uploaderEditForm').attr('action', request.baseUrl + '/?module=cmsAdmin&controller=file&action=edit&id=' + id[2] + '&hash=' + id[3]);
                    $('#editTitle').val(result.title);
                    $('#editAuthor').val(result.author);
                    $('#editSource').val(result.source);
                    $('#editDescription').val(result.description);
                });
        return false;
    });

    $('body').on('click', 'a.remove-file', function () {
        if (!window.confirm($(this).attr('title') + '?')) {
            return false;
        }
        var id = $(this).attr('id').split('-');
        $.get(
                request.baseUrl + '/?module=cmsAdmin&controller=file&action=remove&id=' + id[1] + '&hash=' + id[2],
                function (result) {
                    if (!result) {
                        return location.reload();
                    }
                    alert(result);
                });
        return false;
    });

    $('body').on('click', 'input#editReset', function () {
        $('li').removeClass('editActive');
        $('#uploaderEdit').hide();
        $('#fileUpload').show();
        $('#uploaderEditForm').attr('action', '');
        return false;
    });

    $('body').on('click', 'input#editSubmit', function () {
        $.post($('#uploaderEditForm').attr('action'), $('#uploaderEditForm').serialize())
                .always(function () {
                    $('input#editReset').click();
                    return false;
                });
        return false;
    });

    $('body').on('click', 'input.sticky', function () {
        var id = $(this).attr('id').split('-');
        $.get(
                request.baseUrl + '/?module=cmsAdmin&controller=file&action=stick&id=' + id[2] + '&hash=' + id[3],
                function (result) {
                    if (result) {
                        alert(result);
                    }
                });
    });

    window.iframeId = undefined;

    function fileUpload() {
        form = document.getElementById('uploader');
        // Create the iframe...
        var iframe = document.createElement("iframe");
        iframe.setAttribute("id", "upload_iframe");
        iframe.setAttribute("name", "upload_iframe");
        iframe.setAttribute("width", "0");
        iframe.setAttribute("height", "0");
        iframe.setAttribute("border", "0");
        iframe.setAttribute("style", "width: 0; height: 0; border: none;");

        // Add to document...
        form.parentNode.appendChild(iframe);
        window.frames['upload_iframe'].name = "upload_iframe";

        iframeId = document.getElementById("upload_iframe");

        // Add event...
        var eventHandler = function () {

            if (iframeId.detachEvent)
                iframeId.detachEvent("onload", eventHandler);
            else
                iframeId.removeEventListener("load", eventHandler, false);

            var content = '';
            // Message from server...
            if (iframeId.contentDocument) {
                content = iframeId.contentDocument.body.innerHTML;
            } else if (iframeId.contentWindow) {
                content = iframeId.contentWindow.document.body.innerHTML;
            } else if (iframeId.document && iframeId.document.body) {
                content = iframeId.document.body.innerHTML;
            }
            if (content !== '') {
                document.getElementById('component').innerHTML = content;
                if (typeof window.uploaderChangeView === 'function') {
                    window.uploaderChangeView();
                }
            }
            bindSortable();

            if (iframeId && typeof iframeId === 'object' && iframeId.parentNode !== null) {
                // Del the iframe...
                setTimeout('(function () {iframeId.parentNode.removeChild(iframeId)})();', 500);
            }
        };

        if (iframeId.addEventListener)
            iframeId.addEventListener("load", eventHandler, true);
        if (iframeId.attachEvent)
            iframeId.attachEvent("onload", eventHandler);

        // Set properties of form...
        form.setAttribute("target", "upload_iframe");
        form.setAttribute("method", "post");
        form.setAttribute("enctype", "multipart/form-data");
        form.setAttribute("encoding", "multipart/form-data");

        // Submit the form...
        form.submit();
    }

    $('body').on('change', 'input#file', function () {
        fileUpload();
        $('input#file').val('');
    });

    bindSortable();

});