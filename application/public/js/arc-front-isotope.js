/**
 * Created by chrishoward on 2/02/15.
 */
//          $isotope      = 'data-isotope-options=\'{ "layoutMode": "masonry","itemSelector": ".pzarc-panel","masonry":{"columnWidth":".grid-sizer","gutter":".gutter-sizer"}}\'';



jQuery( document ).ready( function ()
{
  "use strict";
  var $container = jQuery( '.js-isotope' ).each(function()
  {
    var $c = this;
    jQuery($c).imagesLoaded( function ()
    {
// init
      var arcIsotopeID = jQuery( $c ).attr( 'data-uid' );
      console.log( arcIsotopeID );
      $container.isotope( {
        // options
        layoutMode: 'masonry',
        itemSelector: '.' + arcIsotopeID + ' .pzarc-panel',
        masonry: {
          columnWidth: '.grid-sizer',
          gutter: '.gutter-sizer'
        }
      } );
    } );
  });
} );

