/**
 * Created by thomas on 16/01/15.
 */
$(document).ready(function(){
    $('aside').height($(document).height());

    console.log($('section').height());
    $('.wrapper_menu').on('click', function(){
        $('.menu-reduced').toggleClass('menu-extend');
        $('section').height($(document).width() - 260);
    });
});