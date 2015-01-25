/**
 * Created by thomas on 16/01/15.
 */
$(document).ready(function () {

    var resize = function(){
        var height = $(window).height();
        if(height > 800)
            $('aside').height(height);
        else {
            $('aside').css({"height":"100%"});
            $('aside').css({"overflow" : "scroll"});
        }
    }
    resize();
    $(window).resize(function (){
        resize();
    });

    //console.log($('section').height());
    $('.wrapper_menu').on('click', function () {
        $('.menu-reduced').toggleClass('menu-extend');
        $('section').height($(document).width() - 260);
    });

    if(window.location.pathname == '/user/calendar'){
        $.get( "/user/getcalendar", function(data) {
        })
            .done(function(data) {
                $('#calendar-to-display').html(data);
            })
    }
    //$('#scroll').hide();

    $(window).scroll(function() {
        console.log($(this).scrollTop());
        if ($(this).scrollTop() > 200) $('#scroll').fadeIn();
        else $('#scroll').fadeOut();
    });

    $('#scroll a').click(function() {
        $('body,html').animate({
            scrollTop: 0
        }, 300);
        return false;
    });

});