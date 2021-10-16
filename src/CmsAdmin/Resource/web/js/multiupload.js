$(document).ready(function () {
    $(document).on('mouseenter', '.multiupload .thumb-small', function () {
        console.log($(this).attr('src'));
        let url = $(this).closest('.thumb').find('.thumb-big').attr('src');

        $('body').append('<img class="thumb-zoom" src="' + url + '">');
    });

    $(document).on('mouseleave', '.thumb-zoom, .thumb-small', function () {
        $('.thumb-zoom').remove();
    });
});
