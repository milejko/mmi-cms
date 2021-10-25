$(window).on('load', function () {
    $('.upload-add-label').each(function () {
        let elementHeight = $(this).outerHeight();
        let container = $(this).closest('.multiupload');

        $(window).scroll(function () {
            let containerHeight = container.outerHeight();
            let containerTop = container.offset().top;
            let windowScroll = $(window).scrollTop();

            if (windowScroll >= containerTop - 65 && windowScroll <= containerTop + containerHeight - elementHeight + 120) {
                container.addClass('multiupload-fixed');
                if (windowScroll >= containerTop + containerHeight - elementHeight - 130) {
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
