/*price range*/

$('#sl2').slider();

var RGBChange = function () {
    $('#RGB').css('background', 'rgb(' + r.getValue() + ',' + g.getValue() + ',' + b.getValue() + ')')
};

/*scroll to top*/

$(document).ready(function () {
    $(function () {
        $.scrollUp({
            scrollName: 'scrollUp', // Element ID
            scrollDistance: 300, // Distance from top/bottom before showing element (px)
            scrollFrom: 'top', // 'top' or 'bottom'
            scrollSpeed: 300, // Speed back to top (ms)
            easingType: 'linear', // Scroll to top easing (see http://easings.net/)
            animation: 'fade', // Fade, slide, none
            animationSpeed: 200, // Animation in speed (ms)
            scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
            //scrollTarget: false, // Set a custom target element for scrolling to the top
            scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
            scrollTitle: false, // Set a custom <a> title if required.
            scrollImg: false, // Set true to use image
            activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
            zIndex: 2147483647 // Z-Index for the overlay
        });
    });

    $('#myCarousel').carousel({
        interval: 10000
    })

    $('.carousel .item').each(function () {
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));

        if (next.next().length > 0) {
            next.next().children(':first-child').clone().appendTo($(this));
        }
        else {
            $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
        }
    });

    $('.img-hint').mouseover(function() {
        
        let params = $(this).data("params");

        console.log(params);


    });

    var my_window;

    $('.img-hint').mouseover(function() {
        let params = $(this).data("params");
        my_window = window.open("about:blank", "Москва", "scrollbars=no, width=400, height=200");
        let my_html = '<!DOCTYPE html>\
        <html lang="en">\
        <head>\
            <meta charset="UTF-8">\
            <style>\
                body{background-color: #F0F0E9;font-family: "Roboto", sans-serif;}\
            </style>\
            <title>Описание</title>\
        </head>\
        <body>';
        my_html += "<h1>"+params['name']+"</h1>";
        my_html += "<p><b>Описание: </b>"+(params['description'].length ? params['description'] : "Без описания")+"<p>";
        my_html += "<p>"+(parseInt(params['availability']) ? "Есть в наличии" : "Нет в наличии")+"<p>";

        my_html += "</body></html>"

        my_window.document.write(my_html);
    });

    $('.img-hint').mouseout(function() {
        my_window.close();
    });

});
