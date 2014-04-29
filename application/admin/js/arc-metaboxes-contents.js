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
    jQuery.each(contenttype,function(index,value){
      var typeid = '#'+value.text.toLowerCase().replace(/ /g,"-")+'-filters';
      var tabid = 'li.item-'+value.text.toLowerCase().replace(/ /g,"-")+'-filters';
      jQuery(typeid).toggle(value.selected);
      jQuery(tabid).toggle(value.selected);
      if (value.selected) {
        console.log(jQuery(tabid),tabid);
        jQuery(tabid).trigger('click');
      }
    });
  }
});

