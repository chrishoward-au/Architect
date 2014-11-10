jQuery( document ).ready( function ()
{
  "use strict";
  // This stop error in CodeKit compilation
  /*global console:true */
  var arcSlicks = jQuery( '.arc-slider-container.slider' );
  //for each

  function update_nav( i, arcNav )
  {

    var nav = jQuery( arcNav ).find( '.arc-slider-slide-nav-item' );
    jQuery( nav ).removeClass( 'active' );
    jQuery( nav[i] ).addClass( 'active' );

  }

  // Grab all the sliders on the page
  arcSlicks.each( function ()
  {

    var arcSlickID = jQuery( this ).attr( 'data-sliderid' );
    var arcSlickTrans = jQuery( this ).attr( 'data-transtype' ) === 'fade';
    var arcSlickOpts = (jQuery( this ).attr( 'data-opts' ));

//        console.log( arcSlickOpts );

    if ( null !== arcSlickID && null !== arcSlickOpts )
    {
      console.log( arcSlickID );
      // Parse the option values
      // Nothing worked. Need a substitute character (#) for string quotes
      arcSlickOpts = arcSlickOpts.replace( /#/g, '"' );
      var arcSlickOptsObj = JSON.parse( arcSlickOpts );

      var beforeChange = function ( slider, i, newIndex )
      {
        update_nav( newIndex, arcSlickNav );
        jQuery( '.pzarc-blueprint_' + arcSlickID + '.nav-navigator:hover .arrow-left' ).removeClass('hide');
        jQuery( '.pzarc-blueprint_' + arcSlickID + '.nav-navigator:hover .arrow-right' ).removeClass('hide');
        if ( 0 === newIndex )
        {
          jQuery( '.pzarc-blueprint_' + arcSlickID + '.nav-navigator:hover .arrow-left' ).addClass('hide');

        }

        if ( slider.$slides.length === newIndex + 1 )
        {
          jQuery( '.pzarc-blueprint_' + arcSlickID + '.nav-navigator:hover .arrow-right' ).addClass('hide');

        }
      };
      var afterChange = function ( slider, i )
      {
        var slideHeight = jQuery( slider.$slides[i] ).height();
        jQuery( slider.$slider ).height( slideHeight );
        //jQuery().show();
      };

      // TODO: Work out how to use infinite without messing up index!!
      var arcSlickNav = jQuery( '.pzarc-navigator-' + arcSlickID + '.thumbs' ).slick( {
            autoplay: false,
            centerMode: false,
            draggable: true,
            infinite: false,
            dots: false,
            slidesToShow: arcSlickOptsObj.tshow,
            slidesToScroll: arcSlickOptsObj.tskip,
            onBeforeChange: beforeChange,
            vertical:arcSlickOptsObj.tisvertical,
            arrows:false
          }
      );
      if ( arcSlickNav.length === 0 )
      {
        arcSlickNav = jQuery( '.pzarc-navigator-' + arcSlickID ).slick();
      }
      var arcSlick = jQuery( '.arc-slider-container.arc-slider-container-' + arcSlickID + ' .pzarc-section' ).slick(
          {
            slide: '.pzarc-panel',
            fade: arcSlickTrans,
            speed: arcSlickOptsObj.tduration,
            autoplay: (arcSlickOptsObj.tinterval > 0),
            autoplaySpeed: arcSlickOptsObj.tinterval,
            arrows: false,
            dots: false,
            onBeforeChange: beforeChange,
            onAfterChange: afterChange,
// TODO: replace these with vars
            infinite: arcSlickOptsObj.tinfinite,
            pauseOnHover: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            centerMode: false,
// TODO: Needs some tweaking - prob height and overflow
            vertical: false

          }
      );
      /** Use custom arrows */
        //pzarchitect use-hw-css pzarc-blueprint pzarc-blueprint_full-width nav-navigator icomoon
      jQuery( '.pzarc-blueprint_' + arcSlickID + ' .arrow-left' ).on( 'click', function ()
      {
        arcSlick.slickPrev();
      } );
      jQuery( '.pzarc-blueprint_' + arcSlickID + ' .arrow-right' ).on( 'click', function ()
      {
        arcSlick.slickNext();
      } );


      jQuery( arcSlickNav ).find( '.arc-slider-slide-nav-item' ).on( 'click', function ()
      {
        arcSlick.slickGoTo( (jQuery( this ).attr( 'data-index' ) - 1) );
      } );

      /** Use custom pager */
      jQuery( '.pzarc-blueprint_' + arcSlickID + ' .pager.skip-left' ).on( 'click', function ()
      {
        // dataindex is 1 to n, slick index is 0 to n-1
        var currentIndex = jQuery( arcSlickNav ).find( '.active' ).attr( 'data-index' ) - 1;
        var newIndex = Math.max( +currentIndex - arcSlickOptsObj.tskip, 0 );
        arcSlickNav.slickGoTo( newIndex );
        arcSlick.slickGoTo( newIndex );
      } );

      jQuery( '.pzarc-blueprint_' + arcSlickID + ' .pager.skip-right' ).on( 'click', function ()
      {
        // dataindex is 1 to n, slick index is 0 to n-1
        var currentIndex = jQuery( arcSlickNav ).find( '.active' ).attr( 'data-index' ) - 1;
        var maxIndex = jQuery( arcSlickNav ).find( '.arc-slider-slide-nav-item' );
        var newIndex = Math.min( +currentIndex + arcSlickOptsObj.tskip, (jQuery( maxIndex ).length - 1) );
        arcSlickNav.slickGoTo( newIndex );
        arcSlick.slickGoTo( newIndex );
      } );

//
//            /** Custom skipper */
//            jQuery( '.skip-left' ).on( 'click', function ( e )
//            {
//                e.preventDefault();
//                var goto_slide = Math.max( 0, arcSlickNav.activeIndex - arcSlickOptsObj.tskip );
//                arcSlickNav.swipeTo( goto_slide, 1000 , false);
//                arcSlick.swipeTo( goto_slide );
//
//            } );
//            jQuery( '.skip-right' ).on( 'click', function ( e )
//            {
//                e.preventDefault();
//                var goto_slide = Math.min( arcSlideCount-1, arcSlickNav.activeIndex + arcSlickOptsObj.tskip );
//                arcSlickNav.swipeTo( goto_slide, 1000 ,false);
//                arcSlick.swipeTo( goto_slide );
//            } );
//

    }
  } );
} );