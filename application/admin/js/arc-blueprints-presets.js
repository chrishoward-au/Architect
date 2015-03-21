/**
 * Created by chrishoward on 16/03/15.
 */

var overlay='<div class="arc-presets-overlay"></div>';
jQuery( document ).ready( function ()
{
  "use strict";

  var arc_presets_selector = jQuery( ".arc-presets-selector" ).detach();
  jQuery('#wpwrap' ).append(arc_presets_selector);
  jQuery( 'a.arc-presets-link' ).on( 'click', function ()
  {
    jQuery('html' ).append(overlay);
    jQuery( 'a.arc-button-presets.close, .arc-presets-overlay' ).on( 'click', function ()
    {
      arc_close_presets();
    } );
    jQuery( ".arc-presets-selector" ).show();
    return false;
  } );


  jQuery( ".arc-presets-selector" ).draggable({ handle: "h2" });


  function arc_close_presets(){
    jQuery('.arc-presets-overlay' ).remove();
    jQuery( ".arc-presets-selector" ).hide();

  }

  jQuery('.arc-preset-item').on('change',function(){

    var post_name = jQuery(this ).find('input').val();
    jQuery('.arc-button-presets.styled' ).attr('href','admin.php?action=pzarc_new_from_preset&name='+post_name+'&type=styled' ).removeClass('disabled');
    jQuery('.arc-button-presets.unstyled' ).attr('href','admin.php?action=pzarc_new_from_preset&name='+post_name+'&type=unstyled').removeClass('disabled');
  })
  function update_presets_buttons() {

  }
} );