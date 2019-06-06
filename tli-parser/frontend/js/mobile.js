//burger
$(document).ready(function() {
    var burger = $('.burger');
    var burger_active = $('.burger_active');
    var menu = $('.menu');


    burger.click(function () {
        burger.toggleClass('burger_active');
        menu.toggleClass('menu_active');
    });


burger_active.click(function() {
    menu.removeClass('menu_active');
});
});