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

function deleteWidget(link, btn){
    $('<div class="cd-popup is-visible" role="alert"><div class="cd-popup-container"><p>Czy chcesz usunąć ten widget?</p><ul class="cd-buttons"><li><a id="clickYes" href="#">Tak</a></li><li><a id="clickNo" href="#">Nie</a></li></ul> <a id="clickClose" href="#" class="cd-popup-close img-replace">zamknij</a></div></div>').appendTo(document.body);
    $('#clickYes').on('click', function(){
        $.get(link, function(){
            if($(btn).parent().parent().hasClass('cms-widget')){
                $(btn).parent().parent().remove();
                $('.cd-popup').remove();
            }
        });
    });
    $('#clickNo').on('click', function(){
        $('.cd-popup').remove();
    });
    $('#clickClose').on('click', function(){
        $('.cd-popup').remove();
    });

}

function PopupCenter(pageURL, title) {
    var w = (90 / 100) * screen.width;
    var h = (90 / 100) * screen.height;
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    return targetWin;
}