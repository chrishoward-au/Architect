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
                      loop: false,
                      calculateHeight: true,
                      cssWidthAndHeight: false,
                      mode: 'horizontal',
                      grabCursor: true,
                      createPagination: false,
                      paginationClickable: true,
                      slidesPerView: '1',
                      useCSS3Transforms: true,
                      speed: arcSwiperOptsObj.tduration,
                      autoplay: arcSwiperOptsObj.tinterval,
                      roundLength: true,
                      onSlideChangeStart: function ( swiper, direction )
                      {
                          jQuery( ".pzarc-navigator-" + arcSwiperID + " .active" ).removeClass( 'active' );
                          jQuery( jQuery( ".pzarc-navigator-" + arcSwiperID + " span.swiper-pagination-switch" ).get( swiper.activeIndex ) ).addClass( "active" );
                      },
                      progress: true,
                      onProgressChange: function ( swiper )
                      {
                          switch (arcSwiperTrans)
                          {
                              case "fade":
                                  console.log( arcSwiperTrans );
                                  for ( var i = 0; i < swiper.slides.length; i++ )
                                  {
                                      var slide = swiper.slides[i];
                                      var progress = slide.progress;
                                      var translate = progress * swiper.width;
                                      var opacity = 1 - Math.min( Math.abs( progress ), 1 );
                                      slide.style.opacity = opacity;
                                      swiper.setTransform( slide, 'translate3d(' + translate + 'px,0,0)' );
                                  }
                                  break;
                              case "swipe":
                                  console.log( arcSwiperTrans );

                                  for ( var i = 0; i < swiper.slides.length; i++ )
                                  {
                                      var slide = swiper.slides[i];
                                      var progress = slide.progress;
                                      swiper.setTransform( slide, 'translate3d(0px,0,' + (-Math.abs( progress * 1500 )) + 'px)' );
                                  }
                                  break;
                              case "rotate":
                                  console.log( arcSwiperTrans );
                                  for ( var i = 0; i < swiper.slides.length; i++ )
                                  {
                                      var slide = swiper.slides[i];
                                      var progress = slide.progress;
                                      var rotate = -90 * progress;
                                      if ( rotate < -90 ) rotate = -90;
                                      if ( rotate > 90 ) rotate = 90;
                                      var translate = progress * swiper.width / 2;
                                      var opacity = 1 - Math.min( Math.abs( progress ), 1 );
                                      slide.style.opacity = opacity;
                                      swiper.setTransform( slide, 'rotateY(' + rotate + 'deg) translate3d(' + translate + 'px,0,0)' );
                                  }
                                  break;
                              case "flip":
                                  console.log( arcSwiperTrans );
                                  for ( var i = 0; i < swiper.slides.length; i++ )
                                  {
                                      var slide = swiper.slides[i];
                                      var progress = slide.progress;
                                      var rotate = -180 * progress;
                                      if ( rotate < -180 ) rotate = -180;
                                      if ( rotate > 180 ) rotate = 180;
                                      var translate = progress * swiper.width;
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
                                      swiper.setTransition(swiper.slides[i].querySelector('.flip-container'),swiper.params.speed);
                                      break;
                              }
                          }
                      }

                  } );

            var arcTabs = jQuery( ".pzarc-navigator-" + arcSwiperID + " span" );
            arcTabs.on( 'touchstart mousedown', function ( e )
            {
                e.preventDefault();
//                jQuery( ".pzarc-navigator-" + arcSwiperID + " .active" ).removeClass( 'active' );
//                jQuery( this ).addClass( 'active' );
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
//                jQuery( ".pzarc-navigator-" + arcSwiperID + " .active" ).removeClass( 'active' );
                arcSwiper.swipePrev();
//                jQuery( jQuery( ".pzarc-navigator-" + arcSwiperID + " span" ).get( arcSwiper.activeIndex ) ).addClass( "active" );
            } );
            jQuery( '.arrow-right' ).on( 'click', function ( e )
            {
                e.preventDefault();
//                jQuery( ".pzarc-navigator-" + arcSwiperID + " .active" ).removeClass( 'active' );
                arcSwiper.swipeNext();
//                jQuery( jQuery( ".pzarc-navigator-" + arcSwiperID + " span" ).get( arcSwiper.activeIndex ) ).addClass( "active" );
            } );

        } // End if has ID
    } );

} );
