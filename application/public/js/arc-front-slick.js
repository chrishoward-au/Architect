jQuery( document ).ready( function ()
{
    "use strict";
    // This stop error in CodeKit compilation
    /*global console:true */
    var arcSlicks = jQuery( '.swiper-container.slider' );
    //for each
    arcSlicks.each( function ()
    {

        var arcSlickID = jQuery( this ).attr( 'data-swiperid' );
        var arcSlickTrans = jQuery( this ).attr( 'data-transtype' ) === 'fade';
        var arcSlickOpts = (jQuery( this ).attr( 'data-opts' ));

        if ( null !== arcSlickID && null !== arcSlickOpts )
        {
            console.log( arcSlickID );

            var paging = function ( slider, i )
            {
                return '<button type="button" class="nav-bullets">' + (i + 1) + '</button>';
            };

//            var arcSlickNav = jQuery( '.pzarc-navigator-' + arcSlickID + ' .swiper-pagination-switch' );
            var arcSlick = jQuery( '.swiper-container.swiper-container-' + arcSlickID + ' .pzarc-section' ).slick(
                  {
                      slide: '.pzarc-panel',
// TODO: replace these with vars
                      slidesToShow: 1,
                      fade: arcSlickTrans,
                      autoplay: false,
                      autoPlaySpeed: 2000,
                      arrows: false,
                      easing: 'linear',
                      dots: false,
                      centerMode: false,
// TODO: Needs some tweaking - prob height and overflow
                      vertical: false,
                      customPaging: paging

                  }
            );
            /** Use custom arrows */
            jQuery( '.arrow-left' ).on( 'click', function (  )
            {
                arcSlick.slickPrev();
            } );
            jQuery( '.arrow-right' ).on( 'click', function (  )
            {
                arcSlick.slickNext();
            } );

            /** Use custom pager/dots */
            var arcSlickNav = jQuery( '.pzarc-navigator-featured-posts-2x4' ).slick( {arrows:true,autoplay:true } );
            console.log(arcSlickNav);
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