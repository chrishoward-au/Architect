jQuery( document ).ready( function ()
{
  "use strict";
  function nocontext(e) {
    if (jQuery(e.target).parent().hasClass('disable-save') || jQuery(e.target).hasClass('disable-save')) {
      return false;
    }
  }
  document.oncontextmenu = nocontext;
 // document.onclick = nocontext; // This is too generic!

  function add_disable(){
    jQuery( ".hentry.disable-save img" ).addClass( "disable-save" );
  }
  add_disable();
} );
