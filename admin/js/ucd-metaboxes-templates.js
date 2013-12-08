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
  pzucd_update_usage_info(jQuery("#_pzucd_template-short-name-cmb-field-0").get(0));

  function pzucd_refresh_template_layout(i) {
    pzucd_update_cell_count(i, jQuery('#_pzucd_' + i + '-template-cells-per-view-cmb-field-0').get(0));
    pzucd_update_cell_margin(i, jQuery('#_pzucd_' + i + '-template-cells-vert-margin-cmb-field-0').get(0));
    pzucd_update_cell_across(i, jQuery('#_pzucd_' + i + '-template-cells-across-cmb-field-0').get(0));
    pzucd_update_min_width(i, jQuery('#_pzucd_' + i + '-template-min-cell-width-cmb-field-0').get(0));
    pzucd_show_hide_section(i);
  }

//  jQuery('#pzucd-sections-preview-0').resize(function(){console.log(this.width);pzucd_refresh_template_layout(0)});
//  jQuery('#pzucd-sections-preview-1').resize(function(){console.log(this.width);pzucd_refresh_template_layout(1)});
//  jQuery('#pzucd-sections-preview-2').resize(function(){console.log(this.width);pzucd_refresh_template_layout(2)});


  for (var i = 0; i < 3; i++) {
    jQuery('#_pzucd_' + i + '-template-cells-vert-margin-cmb-field-0').change(function () {
      pzucd_refresh_template_layout(this.id.substr(7,1));
    });
    jQuery('#_pzucd_' + i + '-template-cells-across-cmb-field-0').change(function () {
      pzucd_refresh_template_layout(this.id.substr(7,1));
    });
    jQuery('#_pzucd_' + i + '-template-cells-per-view-cmb-field-0').change(function () {
      pzucd_refresh_template_layout(this.id.substr(7,1));
    });
    jQuery('#_pzucd_' + i + '-template-min-cell-width-cmb-field-0').change(function () {
      pzucd_refresh_template_layout(this.id.substr(7,1));
    });
    jQuery('#_pzucd_' + i + '-template-section-enable-cmb-field-0').change(function () {
      var x = this.id.substr(7,1);
      pzucd_show_hide_section(x);
    });
  }
  jQuery('#_pzucd_template-short-name-cmb-field-0').change(function () {
    pzucd_update_usage_info(this);
  });
  function pzucd_update_usage_info(t){
    console.log(t.value);
    jQuery('span.pzucd-shortname').text(t.value);
  }


/* Switched to pixel based once, but not as fluid. Butwhat was it's advantage? Why did I switch? */
  function pzucd_update_cell_margin(i,t) {
//    console.log(i,t,t.value);
    var cellsAcross = jQuery('#_pzucd_'+i+'-template-cells-across-cmb-field-0').get(0).value;
    var containerWidth = jQuery('.pzucd-section-'+i).width();
    //  console.log(containerWidth);
    jQuery('#pzucd-sections-preview-'+i+' .pzucd-section-cell').each(function (index, value) {
 //     console.log(((index+1)%cellsAcross),cellsAcross);
//      if (((index+1)%cellsAcross) != 0) {
      jQuery(value).css({'marginRight': (t.value ) + '%'});
//      }
    });
  }

  function pzucd_update_cell_across(i,t) {
 //   console.log(i,t,t.value);
    var containerWidth = jQuery('#pzucd-sections-preview-'+i).width();
//    console.log(containerWidth);
    var cellRightMargin = jQuery('#_pzucd_'+i+'-template-cells-vert-margin-cmb-field-0').val();
    var new_cell_width = (100/ t.value)- cellRightMargin;
    // Can't use width(), it breaks when padding is set.
    jQuery('#pzucd-sections-preview-'+i+' .pzucd-section-cell').css( {"width":new_cell_width + '%'});
  }

  function pzucd_update_cell_count(i,t) {
    console.log(i,t,t.value);
    jQuery('.pzucd-section-'+i).empty();
    var plugin_url = jQuery('.field.Pizazz_Layout_Field .plugin_url').get(0).textContent;
    for (var j = 1; j <= t.value; j++) {
      // Need to setup a field to enable random heights in preview

//      if (true) {
//          var h = parseInt(Math.random()*200)+70;
//          jQuery('#pzucd-sections-preview-'+i).append('<div class="pzucd-section-cell-' + j + ' pzucd-section-cell" style="height:'+h+'px;"></div>');
//      } else {
        jQuery('#pzucd-sections-preview-'+i).append('<div class="pzucd-section-cell-' + j + ' pzucd-section-cell" ></div>');
//      }
//      var w = parseInt(Math.random()*50)+10;
//      jQuery('#pzucd-sections-preview-'+i+' .pzucd-section-cell-' + j).lorem({ type: 'words',amount:w,ptags:true});
//      jQuery('#pzucd-sections-preview-'+i+' .pzucd-section-cell-' + j).prepend('<h3 class="pzucd-template-cell-preview-title">Title</h3><img src="'+plugin_url+'/assets/images/sample-image.jpg" width="64"/>');
//      var w = parseInt(Math.random()*5)+1;
//      jQuery('#pzucd-sections-preview-'+i+' .pzucd-section-cell-' + j+' h3').lorem({ type: 'words',amount:w,ptags:false});
    }
  }

  function pzucd_update_min_width(i,t) {
//   console.log(t.value);
    jQuery('#pzucd-sections-preview-'+i+' .pzucd-section-cell').css({'minWidth': t.value + 'px'});
  }

  function pzucd_show_hide_section(x) {
    var y = parseInt(x)+1;
    jQuery('#pzucd-sections-preview-'+x).toggle(jQuery('#_pzucd_'+x+'-template-section-enable-cmb-field-0').get(0).checked);
    jQuery('#template-section-'+y+'.postbox').toggle(jQuery('#_pzucd_'+x+'-template-section-enable-cmb-field-0').get(0).checked);
  }
});