/*jshint multistr: true */
jQuery( document ).ready( function ()
{
    "use strict";
    // This stop error in CodeKit compilation
    /*global console:true */

    // Look in class_Architect get_section_opener() for swiper vars
    var arcSwipers = jQuery( '.swiper-container.slider' );
    //for each
    arcSwipers.each( function ()
    {
        var arcSwiperID = jQuery( this ).attr( 'data-swiperid' );
        // TODO: Why aren't we using this?
//        var arcSwiperType = jQuery( this ).attr( 'data-navtype' );
        var arcSwiperTrans = jQuery( this ).attr( 'data-transtype' );
        var arcSwiperOpts = (jQuery( this ).attr( 'data-opts' ));
        //console.log( arcSwiperID, arcSwiperType );
        var arcSlideCount = jQuery( this ).find( '.pzarc-panel' ).length;
        if ( null !== arcSwiperID && null !== arcSwiperOpts )
        {

            // Parse the option values
            // Nothing worked. Need a substitute character (3) for string quotes
            arcSwiperOpts = arcSwiperOpts.replace( /#/g, '"' );
            var arcSwiperOptsObj = JSON.parse( arcSwiperOpts );

            // NAVIGATOR
            //TODO: Work out how to stop nav being grabberable when not thumbs
            var arcSwiperNav = jQuery( '.pzarc-blueprint_' + arcSwiperID + ' .swiper-nav.swiper-container' ).swiper( {
                slidesPerView: 'auto',
                createPagination: false,
                paginationClickable: false,
                simulateTouch:false, //This is the one that stops mouse dragging
                keyboardControl: false,
                freeMode: true,
                freeModeFluid: true,
                calculateHeight: false,
                useCSS3Transforms: false,
                grabCursor: false,
                onSlideChangeStart: function ( swiper )
                {
                    jQuery( ".swiper-nav.pzarc-navigator-" + arcSwiperID + " .active" ).removeClass( 'active' );
                    jQuery( jQuery( ".swiper-nav.pzarc-navigator-" + arcSwiperID + " span.swiper-pagination-switch" ).get( swiper.activeIndex ) ).addClass( "active" );
                },

                onSlideClick: function ( nav )
                {
                    arcSwiper.swipeTo( nav.clickedSlideIndex );
                }

            } );
            jQuery( '.skip-left' ).on( 'click', function ( e )
            {
                e.preventDefault();
                var goto_slide = Math.max( 0, arcSwiperNav.activeIndex - arcSwiperOptsObj.tskip );
                arcSwiperNav.swipeTo( goto_slide, 1000 , false);
                arcSwiper.swipeTo( goto_slide );

            } );
            jQuery( '.skip-right' ).on( 'click', function ( e )
            {
                e.preventDefault();
                var goto_slide = Math.min( arcSlideCount-1, arcSwiperNav.activeIndex + arcSwiperOptsObj.tskip );
                arcSwiperNav.swipeTo( goto_slide, 1000 ,false);
                arcSwiper.swipeTo( goto_slide );
            } );


            // PANELS
            var arcSwiper = jQuery( '.swiper-container.swiper-container-' + arcSwiperID ).swiper(
                  {
                      //  Nav item gets outta sync when loop on - even with activeLoopIndex
                      // TODO: Setup vertical mode. Will need an option for fixed height blueprint/panel
                      // TODO: Is there an option for nav items per view?
                      // TODO:Is there an option to stop sliding between slides - esp end to beginning.
                      mode: 'horizontal',
                      loop: false,
                      calculateHeight: true,
                      cssWidthAndHeight: false,
                      grabCursor: true,
                      createPagination: false,
                      paginationClickable: true,
                      scrollContainer: false,
                      keyboardControl: true,
                      slidesPerView: 1,
                      useCSS3Transforms: false,
                      speed: arcSwiperOptsObj.tduration,
                      autoplay: arcSwiperOptsObj.tinterval,
                      autoplayDisableOnInteraction: false,
                      roundLength: true,
                      onSlideChangeStart: function ( swiper )
                      {
                          jQuery( ".pzarc-navigator-" + arcSwiperID + " .active" ).removeClass( 'active' );
                          jQuery( jQuery( ".pzarc-navigator-" + arcSwiperID + " span.swiper-pagination-switch" ).get( swiper.activeIndex ) ).addClass( "active" );
                      },
                      progress: true,
                      onProgressChange: function ( swiper )
                      {
                          var i, slide, progress, translate, rotate, opacity;
                          switch (arcSwiperTrans)
                          {
                              case "fade":
//                                  console.log( arcSwiperTrans );
                                  for ( i = 0; i < swiper.slides.length; i++ )
                                  {
                                      slide = swiper.slides[i];
                                      progress = slide.progress;
                                      translate = progress * swiper.width;
                                      opacity = 1 - Math.min( Math.abs( progress ), 1 );
                                      slide.style.opacity = opacity;
                                      swiper.setTransform( slide, 'translate3d(' + translate + 'px,0,0)' );
                                  }
                                  break;
                              case "swipe":
                                  //                                console.log( arcSwiperTrans );

                                  for ( i = 0; i < swiper.slides.length; i++ )
                                  {
                                      slide = swiper.slides[i];
                                      progress = slide.progress;
                                      swiper.setTransform( slide, 'translate3d(0px,0,' + (-Math.abs( progress * 1500 )) + 'px)' );
                                  }
                                  break;
                              case "rotate":
                                  //                              console.log( arcSwiperTrans );
                                  for ( i = 0; i < swiper.slides.length; i++ )
                                  {
                                      slide = swiper.slides[i];
                                      progress = slide.progress;
                                      rotate = -90 * progress;
                                      if ( rotate < -90 )
                                      {rotate = -90;}
                                      if ( rotate > 90 )
                                      {rotate = 90;}
                                      translate = progress * swiper.width / 2;
                                      opacity = 1 - Math.min( Math.abs( progress ), 1 );
                                      slide.style.opacity = opacity;
                                      swiper.setTransform( slide, 'rotateY(' + rotate + 'deg) translate3d(' + translate + 'px,0,0)' );
                                  }
                                  break;
                              case "flip":
                                  //                            console.log( arcSwiperTrans );
                                  for ( i = 0; i < swiper.slides.length; i++ )
                                  {
                                      slide = swiper.slides[i];
                                      progress = slide.progress;
                                      rotate = -180 * progress;
                                      if ( rotate < -180 )
                                      {rotate = -180;}
                                      if ( rotate > 180 )
                                      {rotate = 180;}
                                      translate = progress * swiper.width;
                                      swiper.setTransform( slide, 'translate3d(' + translate + 'px,0,' + -Math.abs( progress ) * 500 + 'px)' );
                                      swiper.setTransform( slide.querySelector( '.flip-container' ), 'rotateY(' + rotate + 'deg)' );
                                  }
                                  break;
                          }
                      },
                      onTouchStart: function ( swiper )
                      {
                          for ( var i = 0; i < swiper.slides.length; i++ )
                          {
                              swiper.setTransition( swiper.slides[i], 0 );
                              switch (arcSwiperTrans)
                              {
                                  case "flip":
                                      swiper.setTransition( swiper.slides[i].querySelector( '.flip-container' ), 0 );
                                      break;
                              }
                          }
                      },
                      onSetWrapperTransition: function ( swiper )
                      {
                          for ( var i = 0; i < swiper.slides.length; i++ )
                          {
                              swiper.setTransition( swiper.slides[i], swiper.params.speed );
                              switch (arcSwiperTrans)
                              {
                                  case "flip":
                                      swiper.setTransition( swiper.slides[i].querySelector( '.flip-container' ), swiper.params.speed );
                                      break;
                              }
                          }
                      }

                  } );

            // This sets up the tab method of clicking
            var arcTabs = jQuery( ".pzarc-navigator-" + arcSwiperID + " span" );

            arcTabs.on( 'touchstart', function ( e )
            {
                if ( jQuery( this ).hasClass( 'active' ) )
                {
                    e.preventDefault();
                    return false;
                }
                else
                {
                    e.preventDefault();
                    arcSwiper.swipeTo( jQuery( this ).index() );
                    return true;
                }

            } );
            arcTabs.click( function ( e )
            {
                if ( jQuery( this ).hasClass( 'active' ) )
                {
                    e.preventDefault();
                    return false;
                }
                else
                {
                    e.preventDefault();
                    return true;
                }
            } );


            // Note: Rapid clicking doesn't queue the clicks. Some users may not like that
            jQuery( '.arrow-left' ).on( 'click', function ( e )
            {
                e.preventDefault();
                arcSwiper.swipePrev();
            } );
            jQuery( '.arrow-right' ).on( 'click', function ( e )
            {
                e.preventDefault();
                arcSwiper.swipeNext();
            } );
            //    console.log( arcSwiper );


            //  console.log( arcSwiperNav );
        } // End if has ID
    } );


} );
