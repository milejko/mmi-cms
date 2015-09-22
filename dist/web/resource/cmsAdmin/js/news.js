$(document).ready(function () {

	function toggleInternal() {
		if (!$('#cms-form-admin-news-internal').is(':checked')) {
			$('#cms-form-admin-news-uri-container').show();
			$('#cms-form-admin-news-text-container').hide();
			$('#cms-form-admin-news-lead-container').hide();
		} else {
			$('#cms-form-admin-news-uri-container').hide();
			$('#cms-form-admin-news-text-container').show();
			$('#cms-form-admin-news-lead-container').show();
		}
	}
	
	toggleInternal();

	$('#cms-form-admin-news-internal').change(function () {
		toggleInternal();
	});
	
});