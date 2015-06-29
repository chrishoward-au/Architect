/**
 * Created by chrishoward on 16/03/15.
 */

jQuery( document ).ready( function ()
{
  "use strict";


  jQuery('.arc-preset-item').on('change',function(){

    var post_name = jQuery(this ).find('input').val();
    jQuery('.arc-button-presets.styled' ).attr('href','admin.php?action=pzarc_new_from_preset&name='+post_name+'&type=styled' ).removeClass('disabled');
    jQuery('.arc-button-presets.unstyled' ).attr('href','admin.php?action=pzarc_new_from_preset&name='+post_name+'&type=unstyled').removeClass('disabled');
  });

  var architect_presets = jQuery.cookie('architect_presets');
  if (typeof architect_presets === "undefined" || architect_presets === "open") {
    jQuery('.arc-presets-selector .heading' ).addClass('open').removeClass('closed' );
    jQuery('.arc-presets-selector' ).addClass('open').removeClass('closed' );
  } else {
    jQuery('.arc-presets-selector .heading' ).removeClass('open').addClass('closed');
    jQuery('.arc-presets-selector' ).removeClass('open' ).addClass('closed');

  }

jQuery('.arc-presets-selector .heading' ).on('click',function(){
  if (jQuery(this ).hasClass('open')) {
    jQuery(this ).removeClass('open').addClass('closed');
    jQuery('.arc-presets-selector' ).removeClass('open' ).addClass('closed');
    jQuery.cookie('architect_presets','closed',9999);
  } else {
    jQuery(this ).addClass('open').removeClass('closed' );
    jQuery('.arc-presets-selector' ).addClass('open').removeClass('closed' );
    jQuery.cookie('architect_presets','open',9999);
  }
});

} );