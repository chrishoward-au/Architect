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
  // JS doesn't seem to support a[x] = ['v1','v2','v3']
  var slugdivs_cells = ['#panel-designer','#styling','#settings'];
  var slugdivs_bpsections = ['#blueprint-section-1','#blueprint-section-2','#blueprint-section-3','#blueprint-pagination','#blueprint-navigator','#blueprint-wireframe-preview'];
  var slugdivs_bpswitcher = ['#blueprint-settings','#contents-selection-settings'];
  var slugdivs_content =  ['#default-filter', '#posts-filters','#pages-filters','#galleries-filters','#slides-filters','#custom-post-types-filters'];
  var title = 'h3 span';
  var panelsdiv = '.inside';
  var x;

  init();
  function init(){
    var container = '<div class="tabs-content"></div>';
    if (jQuery('#panels').length) {
      jQuery('#panels .inside').append(container);
      for (x in slugdivs_cells) {
       // console.log(slugdivs_cells[x]);
        jQuery(slugdivs_cells[x]).addClass('tabs-pane').appendTo('#panels .tabs-content').hide();
        jQuery(slugdivs_cells[x]+' .handlediv').remove();
        jQuery(slugdivs_cells[x]+' h3.hndle').remove();
      }
      jQuery(slugdivs_cells[0]).show().addClass('active');
    }

    if (jQuery('#blueprint-layout').length) {
      jQuery('#blueprint-layout .inside').append(container);
      for (x in slugdivs_bpsections) {
       // console.log(slugdivs_bpsections[x]);
        jQuery(slugdivs_bpsections[x]).addClass('tabs-pane').appendTo('#blueprint-layout .tabs-content').hide();
        jQuery(slugdivs_bpsections[x]+' .handlediv').remove();
        jQuery(slugdivs_bpsections[x]+' h3.hndle').remove();
      }
      jQuery(slugdivs_bpsections[0]).show().addClass('active');
    }

    if (jQuery('#show-settings-for').length) {
      jQuery('#show-settings-for .inside').append(container);
      for (x in slugdivs_bpswitcher) {
        // console.log(slugdivs_bpsections[x]);
        jQuery(slugdivs_bpswitcher[x]).addClass('tabs-pane').appendTo('#show-settings-for .tabs-content').hide();
        jQuery(slugdivs_bpswitcher[x]+' .handlediv').remove();
        jQuery(slugdivs_bpswitcher[x]+' h3.hndle').remove();
      }
      jQuery(slugdivs_bpswitcher[0]).show().addClass('active');
    }

    if (jQuery('#content').length) {
      jQuery('#content .inside').append(container);
      for (x in slugdivs_content) {
        // console.log(slugdivs_bpsections[x]);
        jQuery(slugdivs_content[x]).addClass('tabs-pane').appendTo('#content .tabs-content').hide();
        jQuery(slugdivs_content[x]+' .handlediv').remove();
        jQuery(slugdivs_content[x]+' h3.hndle').remove();
      }
      jQuery(slugdivs_content[0]).show().addClass('active');
    }

  }

  // On click on panels tabs
  jQuery('#panels li').on('click',function(e) {
    var showdiv_cells = jQuery(e.target).attr('data-target');
    jQuery('#panels li').removeClass('active');
    jQuery(e.target).addClass('active');
    for (x in slugdivs_cells) {
      jQuery(slugdivs_cells[x]).removeClass('active').hide();
    }
    jQuery(showdiv_cells).addClass('active').slideDown('slow');
  });

  // On click on blueprint sections tabs
  jQuery('#blueprint-layout li').on('click',function(e) {
    var showdiv_bps = jQuery(e.target).attr('data-target');
    jQuery('#blueprint-layout li').removeClass('active');
    jQuery(e.target).addClass('active');
    console.log(e);
    for (x in slugdivs_bpsections) {
      jQuery(slugdivs_bpsections[x]).removeClass('active').hide();
    }
    jQuery(showdiv_bps).addClass('active').show();
  });

  // On click on switcher stabs
  jQuery('#show-settings-for li').on('click',function(e) {
    var showdiv_bpswitcher = jQuery(e.target).attr('data-target');
    jQuery('#show-settings-for li').removeClass('active');
    jQuery(e.target).addClass('active');
    console.log(e);
    for (x in slugdivs_bpswitcher) {
      jQuery(slugdivs_bpswitcher[x]).removeClass('active').hide();
    }
    jQuery(showdiv_bpswitcher).addClass('active').show();
  });

  jQuery('#content li').on('click',function(e) {
    var showdiv_content = jQuery(e.target).attr('data-target');
    jQuery('#content li').removeClass('active');
    jQuery(e.target).addClass('active');
    console.log(e);
    for (x in slugdivs_content) {
      jQuery(slugdivs_content[x]).removeClass('active').hide();
    }
    jQuery(showdiv_content).addClass('active').show();
  });

}); // End
// Nothing past here!

