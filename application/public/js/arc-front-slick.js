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

        console.log( arcSlickOpts );

        if ( null !== arcSlickID && null !== arcSlickOpts )
        {
            // Parse the option values
            // Nothing worked. Need a substitute character (#) for string quotes
            arcSlickOpts = arcSlickOpts.replace( /#/g, '"' );
            var arcSlickOptsObj = JSON.parse( arcSlickOpts );

            var beforeChange = function ( slider, i, newIndex )
            {
                update_nav( newIndex, arcSlickNav );
            };



            // TODO: Work out how to use infinite without messing up index!!
            var arcSlickNav = jQuery( '.pzarc-navigator-' + arcSlickID + '.thumbs' ).slick( {
                      autoplay: false,
                      centerMode: false,
                      draggable: true,
                      infinite: false,
                      dots: false,
                      arrows: false,
                      slidesToShow: arcSlickOptsObj.tshow,
                      slidesToScroll: arcSlickOptsObj.tskip,
                      onBeforeChange: beforeChange
                  }
            );

            var arcSlick = jQuery( '.arc-slider-container.arc-slider-container-' + arcSlickID + ' .pzarc-section' ).slick(
                  {
                      slide: '.pzarc-panel',
                      fade: arcSlickTrans,
                      speed: arcSlickOptsObj.tduration,
                      autoplay: (arcSlickOptsObj.tinterval > 0),
                      autoPlaySpeed: arcSlickOptsObj.tinterval,
                      arrows: false,
                      dots: false,
                      onBeforeChange: beforeChange,
// TODO: replace these with vars
                      infinite: false,
                      pauseOnHover: true,
                      slidesToShow: 1,
                      slidesToScroll: 1,
                      centerMode: false,
// TODO: Needs some tweaking - prob height and overflow
                      vertical: false

                  }
            );
            /** Use custom arrows */
            jQuery( '.arrow-left' ).on( 'click', function ()
            {
                arcSlick.slickPrev();
            } );
            jQuery( '.arrow-right' ).on( 'click', function ()
            {
                arcSlick.slickNext();
            } );


            jQuery( arcSlickNav ).find( '.arc-slider-slide-nav-item' ).on( 'click', function ()
            {
                arcSlick.slickGoTo( (jQuery( this ).attr( 'data-index' ) - 1) );
            } );

            /** Use custom pager */
            jQuery( '.pager.skip-left' ).on( 'click', function ()
            {
                // dataindex is 1 to n, slick index is 0 to n-1
                var currentIndex = jQuery( arcSlickNav ).find( '.active' ).attr( 'data-index' )-1;
                var newIndex = Math.max(+currentIndex - arcSlickOptsObj.tskip,0);
                arcSlickNav.slickGoTo( newIndex );
                arcSlick.slickGoTo( newIndex );
            } );

            jQuery( '.pager.skip-right' ).on( 'click', function ()
            {
                // dataindex is 1 to n, slick index is 0 to n-1
                var currentIndex = jQuery( arcSlickNav ).find( '.active' ).attr( 'data-index' )-1;
                var maxIndex = jQuery( arcSlickNav ).find('.arc-slider-slide-nav-item');
                var newIndex = Math.min(+currentIndex + arcSlickOptsObj.tskip,(jQuery(maxIndex).length-1));
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