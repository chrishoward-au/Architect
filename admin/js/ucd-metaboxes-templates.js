jQuery(document).ready(function () {
  "use strict";
  /*
   var $container = jQuery('#pzucd-sections-preview.pzucd-section-1');
   var $cell = jQuery('.pzucd-section-cell')
   // initialize Isotope
   $container.isotope({
   // options...
   resizable: false, // disable normal resizing
   // set columnWidth to a percentage of container width
   itemClass: 'pzucd-section-cell',
   masonry: { columnWidth: $container.width()/3, gutterWidth:20 }
   });

   // update columnWidth on window resize
   jQuery(window).smartresize(function(){
   $container.isotope({
   itemClass: 'pzucd-section-cell',
   // update columnWidth to a percentage of container width
   masonry: { columnWidth: $container.width()/3, gutterWidth:20 }
   });
   });
   */
//_pzucd_section-group-_pzucd_template-cells-per-view-cmb-group-0-cmb-field-0
//_pzucd_section-group-_pzucd_template-cells-per-view-cmb-group-1-cmb-field-0

  pzucd_refresh_template_layout(0);
  pzucd_refresh_template_layout(1);
  pzucd_refresh_template_layout(2);
  function pzucd_refresh_template_layout(i) {
    pzucd_update_cell_count(i, jQuery('#_pzucd_' + i + '-template-cells-per-view-cmb-field-0').get(0));
    pzucd_update_cell_margin(i, jQuery('#_pzucd_' + i + '-template-cells-vert-margin-cmb-field-0').get(0));
    pzucd_update_cell_across(i, jQuery('#_pzucd_' + i + '-template-cells-across-cmb-field-0').get(0));
    pzucd_update_min_width(i, jQuery('#_pzucd_' + i + '-template-min-cell-width-cmb-field-0').get(0));

  }


  for (var i = 0; i < 3; i++) {
    jQuery('#_pzucd_' + i + '-template-cells-vert-margin-cmb-field-0').change(function () {
      pzucd_refresh_template_layout(i);
    });
    jQuery('#_pzucd_' + i + '-template-cells-across-cmb-field-0').change(function () {
      pzucd_refresh_template_layout(i);
    });
    jQuery('#_pzucd_' + i + '-template-cells-per-view-cmb-field-0').change(function () {
      pzucd_refresh_template_layout(i);
    });
    jQuery('#_pzucd_' + i + '-template-min-cell-width-cmb-field-0').change(function () {
      pzucd_refresh_template_layout(i);
    });
  }


  function pzucd_update_cell_margin(i,t) {
    console.log(i,t,t.value);
    var cellsAcross = jQuery('#_pzucd_'+i+'-template-cells-across-cmb-field-0').get(0).value;
    var containerWidth = jQuery('#pzucd-sections-preview.pzucd-section-'+i).width();
    //  console.log(containerWidth);
    jQuery('.pzucd-section-cell').each(function (index, value) {
//      console.log(((index+1)%cellsAcross),cellsAcross);
//      if (((index+1)%cellsAcross) != 0) {
      jQuery(value).css({'marginRight': (containerWidth * t.value / 100) + 'px'});
//      }
    });
  }

  function pzucd_update_cell_across(i,t) {
    console.log(i,t,t.value);
    var containerWidth = jQuery('#pzucd-sections-preview.pzucd-section-'+i).width();
//    console.log(containerWidth);
    var cellRightMargin = jQuery('#_pzucd_'+i+'-template-cells-vert-margin-cmb-field-0').val() * containerWidth;
    var new_cell_width = ((containerWidth - ((cellRightMargin * (t.value) / 100))) / t.value);
    jQuery('.pzucd-section-cell').width(new_cell_width + 'px');
  }

  function pzucd_update_cell_count(i,t) {
    console.log(i,t,t.value);
    jQuery('#pzucd-sections-preview.pzucd-section-'+i).empty();
    for (var j = 1; j <= t.value; j++) {
      jQuery('#pzucd-sections-preview.pzucd-section-'+i).append('<div class="pzucd-section-cell-' + j + ' pzucd-section-cell">Cell ' + j + '</div>');
    }
  }

  function pzucd_update_min_width(i,t) {
    console.log(i,t,t.value);
    jQuery('.pzucd-sections.pzucd-section-'+i+' .pzucd-section-cell').css({'minWidth': t.value + 'px'});
  }
});