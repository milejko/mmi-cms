$(document).ready(function () {
    if ($('#widget-list-container').length > 0) {
        window.opener.CMS.category().reloadWidgets();
    } else {
        window.opener.postMessage('updateWidgets', window.opener.location);
    }
    window.close();
});