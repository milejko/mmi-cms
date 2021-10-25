$(window).on('load', function () {
    $('.upload-add-label').each(function () {
        let elementHeight = $(this).outerHeight();
        let container = $(this).closest('.multiupload');

        $(window).scroll(function () {
            if ($(window).scrollTop() >= container.offset().top - 65 && $(window).scrollTop() <= container.offset().top + container.outerHeight() - elementHeight + 120) {
                container.addClass('multiupload-fixed');
                if ($(window).scrollTop() >= container.offset().top + container.outerHeight() - elementHeight - 130) {
                    container.addClass('multiupload-fixed-bottom');
                } else {
                    container.removeClass('multiupload-fixed-bottom');
                }
            } else {
                container.removeClass('multiupload-fixed');
                container.removeClass('multiupload-fixed-bottom');
            }
        });
    });
});
