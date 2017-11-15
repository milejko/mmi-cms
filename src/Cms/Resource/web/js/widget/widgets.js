function editWidget(link){
    var win = PopupCenter(link, 'edit');
    win.focus();
    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
    eventer(messageEvent,function(e) {
        window.location.reload();
    },false);
}

function redirectToCMS(link, src, id) {
    console.log(id);
    sessionStorage.setItem('catActiveTab', '#tab-widget');
    sessionStorage.setItem('widgetScrollTarget', 'widget-item-'+id);
    location = link;
}

function PopupCenter(pageURL, title) {
    var w = (90 / 100) * screen.width;
    var h = (90 / 100) * screen.height;
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    return targetWin;
}