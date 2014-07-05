jQuery( document ).ready( function ()
{
    "use strict";
    // This stop error in CodeKit compilation
    /*global console:true */
    var arcSlicks = jQuery( '.swiper-container.slider' );
    //for each

    function update_nav_after( i, arcNav )
    {

        var nav = jQuery( arcNav ).find( '.swiper-pagination-switch' );
        jQuery( nav ).removeClass( 'active' );
        jQuery( nav[i] ).addClass( 'active' );

    }

    // Grab all the sliders on the page
    arcSlicks.each( function ()
    {

        var arcSlickID = jQuery( this ).attr( 'data-swiperid' );
        var arcSlickTrans = jQuery( this ).attr( 'data-transtype' ) === 'fade';
        var arcSlickOpts = (jQuery( this ).attr( 'data-opts' ));

        console.log( arcSlickOpts );

        if ( null !== arcSlickID && null !== arcSlickOpts )
        {
            // Parse the option values
            // Nothing worked. Need a substitute character (#) for string quotes
            arcSlickOpts = arcSlickOpts.replace( /#/g, '"' );
            var arcSlickOptsObj = JSON.parse( arcSlickOpts );

            var afterChange = function ( slider, i )
            {
                update_nav_after( i, arcSlickNav );
            };


            var arcSlick = jQuery( '.swiper-container.swiper-container-' + arcSlickID + ' .pzarc-section' ).slick(
                  {
                      slide: '.pzarc-panel',
// TODO: replace these with vars
                      slidesToShow: 1,
                      fade: arcSlickTrans,
                      speed: arcSlickOptsObj.tduration,
                      autoplay: (arcSlickOptsObj.tinterval>0),
                      autoPlaySpeed: arcSlickOptsObj.tinterval,
                      arrows: false,
                      easing: 'linear',
                      dots: false,
                      centerMode: false,
// TODO: Needs some tweaking - prob height and overflow
                      vertical: false,
                      onAfterChange: afterChange

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

//            /** Use custom pager/dots */
//            var arcSlickNav = jQuery( '.pzarc-navigator-featured-posts-2x4.thumbs' ).slick( {
//                arrows:true,
//                autoplay:false,
//                  slidesToShow:10}
//            );
            var arcSlickNav = jQuery( '.pzarc-navigator-' + arcSlickID );

            // Custom Nav we'll use for everything except thumbs
            jQuery( arcSlickNav ).find( '.swiper-pagination-switch' ).on( 'click', function ()
            {
                arcSlick.slickGoTo( (jQuery( this ).attr( 'data-index' ) - 1) );
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