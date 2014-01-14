/**
 * Created by chrishoward on 17/12/2013.
 */
jQuery(document).ready(function() {
  "use strict";

  /*
  Setup an array of WP metaboxes to turn into tabbed ones
  This will need an array of theslugs of the metaboxes,
  the title, and the panel element
  We could do massive manupulation, but it might be better to keepit siple, and use the slug element as the panel as well.
  We then jsut create an unordered list oit the titles. That coudl even allow us to then use jQueryUI to build the tabs.
   */
  // we should change this to work on any page by makign this defaults
  var tabscontainer = '#normal-sortables';
  //gotta automate this bit!
  var slugdivs_cells = ['#cell-designer','#styling','#settings'];
  var slugdivs_bpsections = ['#blueprint-section-1','#blueprint-section-2','#blueprint-section-3'];
  var title = 'h3 span';
  var panelsdiv = '.inside';
  var x;

  init();
  function init(){
    var container = '<div class="tabs-content"></div>';
    if (jQuery('#cells').length) {
      jQuery('#cells .inside').append(container);
      for (x in slugdivs_cells) {
       // console.log(slugdivs_cells[x]);
        jQuery(slugdivs_cells[x]).addClass('tabs-pane').appendTo('#cells .tabs-content').hide();
        jQuery(slugdivs_cells[x]+' .handlediv').remove();
        jQuery(slugdivs_cells[x]+' h3.hndle').remove();
      }
      jQuery(slugdivs_cells[0]).show().addClass('active');
    }
    if (jQuery('#blueprint-sections').length) {
      jQuery('#blueprint-sections .inside').append(container);
      for (x in slugdivs_bpsections) {
       // console.log(slugdivs_bpsections[x]);
        jQuery(slugdivs_bpsections[x]).addClass('tabs-pane').appendTo('#blueprint-sections .tabs-content').hide();
        jQuery(slugdivs_bpsections[x]+' .handlediv').remove();
        jQuery(slugdivs_bpsections[x]+' h3.hndle').remove();
      }
      jQuery(slugdivs_bpsections[0]).show().addClass('active');
    }
  }

  jQuery('#cells li').on('click',function(e) {
    var showdiv_cells = jQuery(e.target).attr('data-target');
    jQuery('#cells li').removeClass('active');
    jQuery(e.target).addClass('active');
    for (x in slugdivs_cells) {
      jQuery(slugdivs_cells[x]).removeClass('active').hide();
    }
    jQuery(showdiv_cells).addClass('active').slideDown('slow');
  });

  jQuery('#blueprint-sections li').on('click',function(e) {
    var showdiv_bps = jQuery(e.target).attr('data-target');
    jQuery('#blueprint-sections li').removeClass('active');
    jQuery(e.target).addClass('active');
    for (x in slugdivs_bpsections) {
      jQuery(slugdivs_bpsections[x]).removeClass('active').hide();
    }
    jQuery(showdiv_bps).addClass('active').show();
  });

}); // End
// Nothing past here!

