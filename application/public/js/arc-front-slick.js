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

            var arcSlickNav = jQuery( '.pzarc-navigator-' + arcSlickID + ' .swiper-pagination-switch' );
            console.log( arcSlickNav );
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
            jQuery( '.arrow-left' ).on( 'click', function (  )
            {
                arcSlick.slickPrev();
            } );
            jQuery( '.arrow-right' ).on( 'click', function (  )
            {
                arcSlick.slickNext();
            } );


        }
    } );
} );