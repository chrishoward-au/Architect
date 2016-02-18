/**
 * Created by chrishoward on 25/09/15.
 */

(function ( $ )
{
  localStorage.setItem( 'gotoBlueprint', '' );
  localStorage.setItem( 'gotoPanel', '0' );

  function getQueryParams( qs )
  {
    qs = qs.split( "#bp" );
    if ( qs[1] !== undefined )
    {
      qs = qs[1].split( '-' );
    }
    return qs;
  }

  var refs = getQueryParams( document.location.href );
  if ( refs.length === 2 )
  {
    if ( 'has class slick' )
    {
      var bp = jQuery( '#bp' + refs[0] + '-' + refs[1] ).parents( '.pzarc-blueprint' ).attr( 'id' );
      localStorage.setItem( "gotoBlueprint", bp );
      localStorage.setItem( "gotoPanel", refs[1] );
    }
  }

  /*
    Process more click
   */
  jQuery( '.pzarc-front .readmore' ).on( 'click', function ()
  {

    var thePanel = jQuery( this ).parents( '.pzarc-panel' );
    jQuery(thePanel ).addClass('open');
    var theFront = jQuery( thePanel ).find( '.pzarc-front' );
    var theBack = jQuery( thePanel ).find( '.pzarc-back' );
    jQuery( theFront ).slideToggle(function(){jQuery( theBack ).slideToggle();}).addClass( 'pzarc-hidden' );

    return false;
  } );
  jQuery( '.pzarc-close-back' ).on( 'click', function ()
  {
    var thePanel = jQuery( this ).parents( '.pzarc-panel' );
    var theFront = jQuery( thePanel ).find( '.pzarc-front' );
    var theBack = jQuery( thePanel ).find( '.pzarc-back' );
    jQuery( theBack ).slideToggle(function(){
      jQuery(thePanel ).removeClass('open');
      jQuery( theFront ).slideToggle().removeClass( 'pzarc-hidden' );
    });
    return false;
  } );
})( jQuery );