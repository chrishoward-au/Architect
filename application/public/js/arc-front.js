/*jshint multistr: true */
jQuery( document ).ready( function ()
{
    "use strict";
    /*
     var mySwiper = jQuery('.swiper-container.pzarc-blueprint-2173').swiper({
     //Your options here:
     mode:'horizontal',
     loop: true,
     keyboardControl:true,
     autoplay:0,
     slidesPerView:'auto'
     });
     */

    var arcSwiperID = jQuery('.swiper-container.swiper-container-featured-posts-2x4' ).attr('data-swiperid');
    var arcSwiperOpts = jQuery('.swiper-container.swiper-container-featured-posts-2x4' ).attr('data-swiperopts');
    // TODO: Get fader working
    var arcSwiperFade = ',"progress":true,\
                        "onProgressChange": "function(swiper){\
                            for (var i = 0; i < swiper.slides.length; i++){\
                                var slide = swiper.slides[i];\
                                var progress = slide.progress;\
                                var translate = progress*swiper.width;\
                                var opacity = 1 - Math.min(Math.abs(progress),1);\
                                slide.style.opacity = opacity;\
                                swiper.setTransform(slide,\'translate3d(\'+translate+\'px,0,0)\');\
                            }\
                        }",\
                        "onTouchStart":"function(swiper){\
                            for (var i = 0; i < swiper.slides.length; i++){\
                                swiper.setTransition(swiper.slides[i], 0);\
                            }\
                        }",\
                        "onSetWrapperTransition": "function(swiper, speed) {\
                            for (var i = 0; i < swiper.slides.length; i++){\
                                swiper.setTransition(swiper.slides[i], speed);\
                            }\
                        }"';
    arcSwiperOpts = arcSwiperOpts.replace(/:/g,'":');
    arcSwiperOpts = arcSwiperOpts.replace(/,/g,',"');
    arcSwiperOpts = arcSwiperOpts.replace(/'/g,'"');
//    arcSwiperOpts += arcSwiperFade;
    arcSwiperOpts = '{"'+arcSwiperOpts+'}';
    console.log(JSON.parse(arcSwiperOpts));
    var arcSwiper = jQuery('.swiper-container.swiper-container-'+arcSwiperID ).swiper(JSON.parse(arcSwiperOpts));
    jQuery('.arrow-left').on('click', function(e){
        e.preventDefault();
        arcSwiper.swipePrev();
    });
    jQuery('.arrow-right').on('click', function(e){
        e.preventDefault();
        arcSwiper.swipeNext();
    });

} );
