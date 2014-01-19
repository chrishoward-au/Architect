/**
 * Created by chrishoward on 4/01/2014.
 */
jQuery(document).ready(function () {
  "use strict";

init();
  function init() {
    pzarc_show_hide_types();
  }
jQuery('#_pzarc_contents-content-source-cmb-field-0').on('change',function() {pzarc_show_hide_types()});

  function pzarc_show_hide_types() {
    var contenttype = jQuery('#_pzarc_contents-content-source-cmb-field-0').get(0);
    //console.log(contenttype);
    jQuery.each(contenttype,function(index,value){
      var typeid = '#'+value.text.toLowerCase().replace(/ /g,"-")+'-filters';
      //console.log(typeid,value.selected);
      jQuery(typeid).toggle(value.selected);
    });
//    jQuery('#pzarc-sections-preview-' + x).toggle();
  }
});