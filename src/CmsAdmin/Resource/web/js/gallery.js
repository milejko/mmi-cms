$(document).ready(function () {
	$("a.lightbox").lightBox({
		imageLoading: request.baseUrl + "/default/file/images/lightbox-ico-loading.gif",
		imageBtnClose: request.baseUrl + "/default/file/images/lightbox-btn-close.gif",
		imageBtnPrev: request.baseUrl + "/default/file/images/lightbox-btn-prev.gif",
		imageBtnNext: request.baseUrl + "/default/file/images/lightbox-btn-next.gif",
		imageBlank: request.baseUrl + "/default/file/images/lightbox-blank.gif",
		containerResizeSpeed: 150,
		txtImage: "Obraz",
		txtOf: "z"
	});
});