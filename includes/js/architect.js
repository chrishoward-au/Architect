/**
 * Created by chrishoward on 8/01/2014.
 */

    // Add platform info to html header
var b = document.documentElement;
b.setAttribute('data-useragent',  navigator.userAgent);
b.setAttribute('data-platform', navigator.platform );
b.className += ((!!('ontouchstart' in window) || !!('onmsgesturechange' in window))?' touch':'');

// jQuery
jQuery(document).ready(function () {
  "use strict";
  pzarc_align_bg_images('.pzarc-bg-image img.fill.crop');
  jQuery(window).on('resize',function(){
    pzarc_align_bg_images('.pzarc-bg-image img.fill.crop');
  });

  function pzarc_align_bg_images(imageToAlign){
    jQuery(imageToAlign).each(function(index){
      var panelWidth = jQuery(this).parent().outerWidth();
      var imageWidth = jQuery(this).width();
      if (panelWidth>imageWidth) {
        var centreLeft =  (panelWidth-imageWidth)/2;
        jQuery(this).css('margin-left',centreLeft);
      } else if (imageWidth>panelWidth) {
        var centreLeft =  -(imageWidth-panelWidth)/2;
        jQuery(this).css('margin-left',centreLeft);
      }
    });

  }

}); // End
// Nothing past here!
