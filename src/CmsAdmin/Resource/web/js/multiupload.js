$(document).ready(function () {
    $(document).on('mouseenter', '.multiupload .thumb-small', function () {
        let url = $(this).data('image');

        $('.thumb-zoom').remove();
        $('body').append('<img class="thumb-zoom " src="' + url + '">');
        $('.thumb-zoom').removeClass('hidden');
        setTimeout(function(){
            $('.thumb-zoom').removeClass('hidden').addClass('active');
        }, 100);
    });

    $(document).on('mouseleave', '.thumb-small', function () {
        let zoom = $('.thumb-zoom')
        zoom.removeClass('active');
        setTimeout(function(){
            zoom.remove();
        }, 200);
    });
});
