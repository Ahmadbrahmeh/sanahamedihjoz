$(function(){
    'use strict';
    //Calculate Body Padding//
        $('body').css('paddingTop',$('.navbar').innerHeight());
    //Scroll To Element//
    $('.navbar li a').click(function(e){
        e.preventDefault();
        $('html,body').animate({
            scrollTop:$('#' + $(this).data('scroll')).offset().top+1
        },2000);
    });
     //Add Active Class//
     $('.navbar li a').click(function(){
       // $('.navbar a').removeClass('active');
       // $(this).addClass('active');//
        $(this).addClass('active').parent().siblings().find('a').removeClass('active');
    });
     //Sync navbar With Sections//
     $(window).scroll(function(){

        $('.block').each(function(){

            if($(window).scrollTop() > $(this).offset().top){

                console.log($(this).attr('id'));     

                var blockID = $(this).attr('id');

                $('.navbar a').removeClass('active');

                $('.navbar li a[data-scroll="' +blockID+ '"]').addClass('active');

            }
        });
     });
     //Scroll To Top Button//
     var scrolltotop=$(".scroll-top");
        $(window).scroll(function(){
            
            if($(window).scrollTop() >= 1000){
                console.log("Passed 1000px");
                scrolltotop.fadeIn();
            }else{
                scrolltotop.fadeOut();
            }
        });
        scrolltotop.click(function(e){
            e.preventDefault();
            $('html,body').animate({
                scrollTop:0
            },2000);
        });
    //  Fixed Menu //
        console.log("The Inner Of Fixed Menu Is" +" "+ $('.fixed-menu').innerWidth());
        $('.fixed-menu .fa-cog').click(function(){
            var fix = $(this).parent('.fixed-menu')
            fix.toggleClass('is-visible');
            if (fix.hasClass('is-visible')){
                $(fix).animate({
                    left:0
                });
                $('body').animate({
                    paddingLeft:'240px'
                });
            }else{
                $(fix).animate({
                    left:-$('.fixed-menu').innerWidth()
                });
                $('body').animate({
                    paddingLeft:'0'
                });
            }
        });

});