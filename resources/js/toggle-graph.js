$(document).ready(function() {

    $(".card-button-toggle").click(function (event) {
        var target = $(event.target);

        if (target.is('h4')) {
            target.siblings('canvas').fadeToggle("fast", "linear");
            target.children('span').toggleClass('mdi-arrow-down-bold').toggleClass('mdi-arrow-right-thick');
        }
        else if (target.is('span')) {
            target.parent().siblings('canvas').fadeToggle("fast", "linear");
            target.toggleClass('mdi-arrow-down-bold').toggleClass('mdi-arrow-right-thick');
        }
        else if (target.is('img')) {
            target.siblings('canvas').fadeToggle("fast", "linear");
            target.siblings('h4').children('span').toggleClass('mdi-arrow-down-bold').toggleClass('mdi-arrow-right-thick');
        }
        else {
            target.children('canvas').fadeToggle("fast", "linear");
            target.children('h4').children('span').toggleClass('mdi-arrow-down-bold').toggleClass('mdi-arrow-right-thick');
        }
    });
});