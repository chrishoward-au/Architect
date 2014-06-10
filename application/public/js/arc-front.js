/*jshint multistr: true */
jQuery( document ).ready( function ()
{
    "use strict";
    // This stop error in CodeKit compilation
    /*global console:true */

    // Look in class_Architect get_section_opener() for swiper vars
    var arcSwipers = jQuery( '.swiper-container.swiper-container' );
    console.log( arcSwipers );
    //for each
    arcSwipers.each( function ()
    {
        var arcSwiperID = jQuery( this ).attr( 'data-swiperid' );
        var arcSwiperType = jQuery( this ).attr( 'data-navtype' );
        var arcSwiperTrans = jQuery( this ).attr( 'data-transtype' );
        var arcSwiperOpts = (jQuery( this ).attr( 'data-opts' ));
        console.log( arcSwiperID, arcSwiperType );
        if ( null !== arcSwiperID )
        {

            // Parse the option values
            // Nothing worked. Need a substitute character (3) for string quotes
            arcSwiperOpts = arcSwiperOpts.replace( /#/g, '"' );
            var arcSwiperOptsObj = JSON.parse( arcSwiperOpts );

            var arcSwiper = jQuery( '.swiper-container.swiper-container-' + arcSwiperID ).swiper(
                  {
                      // Nav item gets outta sync when loop on - even with activeLoopIndex
                      // TODO: Setup vertical mode. Will need an option for fixed height blueprint/panel
                      // TODO: Is there an option for nav items per view?
                      // TODO:Is there an option to stop sliding between slides - esp end to beginning.
                      mode:'horizontal',
                      loop: false,
                      calculateHeight: true,
                      cssWidthAndHeight: false,
                      grabCursor: true,
                      createPagination: false,
                      paginationClickable: true,
                      scrollContainer:false,
                      slidesPerView: 1,
                      useCSS3Transforms: true,
                      speed: arcSwiperOptsObj.tduration,
                      autoplay: arcSwiperOptsObj.tinterval,
                      autoplayDisableOnInteraction:false,
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
                                  console.log( arcSwiperTrans );
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
                                  console.log( arcSwiperTrans );

                                  for ( i = 0; i < swiper.slides.length; i++ )
                                  {
                                      slide = swiper.slides[i];
                                      progress = slide.progress;
                                      swiper.setTransform( slide, 'translate3d(0px,0,' + (-Math.abs( progress * 1500 )) + 'px)' );
                                  }
                                  break;
                              case "rotate":
                                  console.log( arcSwiperTrans );
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
                                  console.log( arcSwiperTrans );
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

            var arcTabs = jQuery( ".pzarc-navigator-" + arcSwiperID + " span" );
            arcTabs.on( 'touchstart mousedown', function ( e )
            {
                e.preventDefault();
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
                arcSwiper.swipePrev();
            } );
            jQuery( '.arrow-right' ).on( 'click', function ( e )
            {
                e.preventDefault();
                arcSwiper.swipeNext();
            } );

        } // End if has ID
    } );

} );
