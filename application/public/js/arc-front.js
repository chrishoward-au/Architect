/*jshint multistr: true */
jQuery( document ).ready( function ()
{
    "use strict";


//    //bxSlider
//    jQuery( '.bxslider' ).bxSlider( {
//              mode: 'fade',
//              pagerType:'full',
//              autoControls:false,
//              auto:false,
//              pager:false,
//              pagerCustom:'.bxpager'
//          }
//    );


    // Swiper
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

    // Look in class_Architect build() for swiper vars
    var arcSwipers = jQuery( '.swiper-container.swiper-container' );
    console.log(arcSwipers);
    //for each
    arcSwipers.each( function ()
    {
        var arcSwiperID = jQuery( this ).attr( 'data-swiperid' );
        var arcSwiperType = jQuery( this ).attr( 'data-navtype' );
        var arcSwiperOpts = (jQuery( this ).attr( 'data-opts' ));
        console.log( arcSwiperID, arcSwiperType );
        if ( null !== arcSwiperID )
        {

            // TODO: Get fader working
//            var arcSwiperFade = ',"progress":true,\
//                        "onProgressChange": "function(swiper){\
//                            for (var i = 0; i < swiper.slides.length; i++){\
//                                var slide = swiper.slides[i];\
//                                var progress = slide.progress;\
//                                var translate = progress*swiper.width;\
//                                var opacity = 1 - Math.min(Math.abs(progress),1);\
//                                slide.style.opacity = opacity;\
//                                swiper.setTransform(slide,\'translate3d(\'+translate+\'px,0,0)\');\
//                            }\
//                        }",\
//                        "onTouchStart":"function(swiper){\
//                            for (var i = 0; i < swiper.slides.length; i++){\
//                                swiper.setTransition(swiper.slides[i], 0);\
//                            }\
//                        }",\
//                        "onSetWrapperTransition": "function(swiper, speed) {\
//                            for (var i = 0; i < swiper.slides.length; i++){\
//                                swiper.setTransition(swiper.slides[i], speed);\
//                            }\
//                        }"';
//



            // Parse the option values
            // Nothing worked. Need a substitute character (3) for string quotes
            arcSwiperOpts = arcSwiperOpts.replace( /#/g, '"' );
            var arcSwiperOptsObj = JSON.parse( arcSwiperOpts );

            var arcSwiper = jQuery( '.swiper-container.swiper-container-' + arcSwiperID ).swiper( {
                loop:false,
                calculateHeight:true,
                cssWidthAndHeight:false,
                mode:'horizontal',
                grabCursor: true,
                createPagination:false,
                paginationClickable: true,
                slidesPerView:'1',
                useCSS3Transforms:true,
                speed:arcSwiperOptsObj.tduration,
                autoplay:arcSwiperOptsObj.tinterval,
                roundLength:true
            } );

            var arcTabs = jQuery(  ".pzarc-navigator-"+arcSwiperID+" span" );
            arcTabs.on( 'touchstart mousedown', function ( e )
            {
                e.preventDefault();
                jQuery(  ".pzarc-navigator-"+arcSwiperID+" .active").removeClass( 'active' );
                jQuery( this ).addClass( 'active' );
                arcSwiper.swipeTo( jQuery( this ).index() );
            } );
            arcTabs.click( function ( e )
            {
                e.preventDefault();
            } );


            // Note: Rapid clicking doesn't queue the clicks. Some users may not like that
            jQuery( '.arrow-left' ).on( 'click', function ( e )
            {
                e.preventDefault();
                jQuery( ".pzarc-navigator-"+arcSwiperID+" .active" ).removeClass( 'active' );
                arcSwiper.swipePrev();
                jQuery(jQuery( ".pzarc-navigator-"+arcSwiperID+" span").get(arcSwiper.activeIndex)).addClass("active");
            } );
            jQuery( '.arrow-right' ).on( 'click', function ( e )
            {
                e.preventDefault();
                jQuery(  ".pzarc-navigator-"+arcSwiperID+" .active" ).removeClass( 'active' );
                arcSwiper.swipeNext();
                jQuery(jQuery( ".pzarc-navigator-"+arcSwiperID+" span" ).get(arcSwiper.activeIndex )).addClass("active");
            } );

        } // End if has ID
    } );

} );
