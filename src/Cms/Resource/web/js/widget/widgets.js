function editWidget(link){
    var win = window.open(link, '_blank');
    win.focus();
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
