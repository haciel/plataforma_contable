$(window).on('load',function() {
    var window_height = $(window).height();
    $(".wrapper").css('min-height', window_height - $('.content-footer').outerHeight());
});

$('.scroll-efect').click(function () {
    var href=$(this).data('href');
    $('html, body').animate({
        scrollTop: $(href).offset().top
    }, 1000);
});