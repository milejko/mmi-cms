function editWidget(link){
    var win = window.open(link, '_blank');
    win.focus();
}

function deleteWidget(link, btn){
    $.get(link, function(){
        if($(btn).parent().parent().hasClass('cms-widget')){
            $(btn).parent().parent().remove();
        }
    });
}