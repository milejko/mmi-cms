$(document).ready(function () {
    $("a.lightbox").lightBox({
        imageLoading: request.baseUrl + "/resource/cmsAdmin/images/lightbox-ico-loading.gif",
        imageBtnClose: request.baseUrl + "/resource/cmsAdmin/images/lightbox-btn-close.gif",
        imageBtnPrev: request.baseUrl + "/resource/cmsAdmin/images/lightbox-btn-prev.gif",
        imageBtnNext: request.baseUrl + "/resource/cmsAdmin/images/lightbox-btn-next.gif",
        imageBlank: request.baseUrl + "/resource/cmsAdmin/images/lightbox-blank.gif",
        containerResizeSpeed: 150,
        txtImage: "Obraz",
        txtOf: "z"
    });
});