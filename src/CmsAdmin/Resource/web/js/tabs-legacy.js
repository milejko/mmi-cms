/*---------------------------------
    Tabs
    -----------------------------------*/
// tab setup
$('.tab-content-legacy').addClass('clearfix').not(':first').hide();
$('ul.tabs').each(function () {
    var current = $(this).find('li.current');
    if (current.length < 1) {
        $(this).find('li:first').addClass('current');
    }
    current = $(this).find('li.current a').attr('href');
    $(current).show();
});

// tab click
$(document).on('click', 'ul.tabs-legacy a[href^="#"]', function (e) {
    e.preventDefault();
    var tabs = $(this).parents('ul.tabs-legacy').find('li');
    var tab_next = $(this).attr('href');
    var tab_current = tabs.filter('.current').find('a').attr('href');
    $(tab_current).hide();
    tabs.removeClass('current');
    $(this).parent().addClass('current');
    $(tab_next).show();
    history.pushState(null, null, window.location.search + $(this).attr('href'));
    return false;
});

// tab hashtag identification and auto-focus
var wantedTag = window.location.hash;
if (wantedTag != "")
{
    // This code can and does fail, hard, killing the entire app.
    // Esp. when used with the jQuery.Address project.
    try {
        var allTabs = $("ul.tabs-legacy a[href^=" + wantedTag + "]").parents('ul.tabs-legacy').find('li');
        var defaultTab = allTabs.filter('.current').find('a').attr('href');
        $(defaultTab).hide();
        allTabs.removeClass('current');
        $("ul.tabs-legacy a[href^=" + wantedTag + "]").parent().addClass('current');
        $("#" + wantedTag.replace('#', '')).show();
    } catch (e) {
        // I have no idea what to do here, so I'm leaving this for the maintainer.
    }
}