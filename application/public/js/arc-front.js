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
        var arcSwiperVals = (jQuery( this ).attr( 'data-vals' ));
        var arcSwiperOpts = '';
        console.log( arcSwiperID, arcSwiperType );
        if ( null !== arcSwiperID )
        {
            switch (arcSwiperType)
            {
                case 'bulletsold':
                    arcSwiperOpts = jQuery( this ).attr( 'data-swiperopts' );
                    arcSwiperOpts = arcSwiperOpts.replace( /:/g, '":' );
                    arcSwiperOpts = arcSwiperOpts.replace( /,/g, ',"' );
                    arcSwiperOpts = arcSwiperOpts.replace( /'/g, '"' );
                    arcSwiperOpts = '"' + arcSwiperOpts;
                    break;
                case 'tabbed':
                    arcSwiperOpts = '"onSlideChangeStart": "function(){jQuery(\'.pzarc-navigator.tabbed .active\').removeClass(\'active\');jQuery(\'.pzarc-navigator.tabbed span\').eq(arcSwiper.activeIndex).addClass(\'active\')}"';
                    break;
                case 'numbers':
                    arcSwiperOpts = '"onSlideChangeStart": "function(){jQuery(\'.pzarc-navigator.numbers .active\').removeClass(\'active\');jQuery(\'.pzarc-navigator.numbers span\').eq(arcSwiper.activeIndex).addClass(\'active\')}"';
                    break;
                case 'bullets':
                    arcSwiperOpts = '"onSlideChangeStart": "function(){jQuery(\'.pzarc-navigator.bullets .active\').removeClass(\'active\');jQuery(\'.pzarc-navigator.bullets span\').eq(arcSwiper.activeIndex).addClass(\'active\')}"';
                    break;
            }

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
//   arcSwiperOpts += arcSwiperFade;

            // Add fixed options
            arcSwiperOpts += ',"roundLengths":true';

            arcSwiperOpts = '{' + arcSwiperOpts + '}';
//            console.log( arcSwiperOpts );
            console.log(  arcSwiperVals  );
            console.log( JSON.parse( arcSwiperVals ) );

            //TODO: Change this to full js using vars from data. Isn't that what we're essentially already doing? Yes, but this does it easier.
            //                      var arcSwiper = jQuery( '.swiper-container.swiper-container-' + arcSwiperID ).swiper( JSON.parse( arcSwiperOpts ) );
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
                speed:arcSwiperVals[0],
                autoplay:arcSwiperVals[1]
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


//            if ( arcSwiperType === 'tabbed' )
//            {
//                var arcTabs = jQuery( ".pzarc-navigator.tabbed span" );
//                arcTabs.on( 'touchstart mousedown', function ( e )
//                {
//                    e.preventDefault();
//                    jQuery( ".pzarc-navigator.tabbed .active" ).removeClass( 'active' );
//                    jQuery( this ).addClass( 'active' );
//                    arcSwiper.swipeTo( jQuery( this ).index() );
//                } );
//                arcTabs.click( function ( e )
//                {
//                    e.preventDefault();
//                } );
//            }

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
