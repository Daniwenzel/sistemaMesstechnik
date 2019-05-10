$(document).ready(function() {
    $('.card-button').hover(function () {
        $(this).css('cursor', 'pointer');
        $(this).css('opacity', 0.4);

    }, function () {
        $(this).css('cursor', 'default');
        $(this).css('opacity', 1);
    });
});

