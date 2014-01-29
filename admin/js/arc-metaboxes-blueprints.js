jQuery(document).ready(function () {
  "use strict";
  /*
   var $container = jQuery('#pzarc-sections-preview.pzarc-section-1');
   var $cell = jQuery('.pzarc-section-cell')
   // initialize Isotope
   $container.isotope({
   // options...
   resizable: false, // disable normal resizing
   // set columnWidth to a percentage of container width
   itemClass: 'pzarc-section-cell',
   masonry: { columnWidth: $container.width()/3, gutterWidth:20 }
   });

   // update columnWidth on window resize
   jQuery(window).smartresize(function(){
   $container.isotope({
   itemClass: 'pzarc-section-cell',
   // update columnWidth to a percentage of container width
   masonry: { columnWidth: $container.width()/3, gutterWidth:20 }
   });
   });
   */
//_pzarc_section-group-_pzarc_blueprint-cells-per-view-cmb-group-0-cmb-field-0
//_pzarc_section-group-_pzarc_blueprint-cells-per-view-cmb-group-1-cmb-field-0

  pzarc_refresh_blueprint_layout(0);
  pzarc_refresh_blueprint_layout(1);
  pzarc_refresh_blueprint_layout(2);
  pzarc_update_usage_info(jQuery("#_pzarc_blueprint-short-name-cmb-field-0").get(0));

  init();
  function init(){
  var fixorfluid =  jQuery('select#_pzarc_blueprint-navigation-cmb-field-0').find('option:selected').get(0).value;
  switch ( fixorfluid) {
    case 'none':
      jQuery('.item-blueprint-pagination').hide();
      jQuery('.item-blueprint-navigator').hide();
      break;
    case 'pagination':
      jQuery('.item-blueprint-pagination').show();
      jQuery('.item-blueprint-navigator').hide();
      break;
    case 'navigator':
      jQuery('.item-blueprint-pagination').hide();
      jQuery('.item-blueprint-navigator').show();
      break;
  }
  }




  function pzarc_refresh_blueprint_layout(i) {
    //console.log(i,jQuery('#_pzarc_' + i + '-blueprint-cells-per-view-cmb-field-0').get(0));
    pzarc_update_cell_count(i, jQuery('#_pzarc_' + i + '-blueprint-cells-per-view-cmb-field-0').get(0));
    pzarc_update_cell_margin(i, jQuery('#_pzarc_' + i + '-blueprint-cells-vert-margin-cmb-field-0').get(0));
    pzarc_update_cell_across(i, jQuery('#_pzarc_' + i + '-blueprint-cells-across-cmb-field-0').get(0));
    pzarc_update_min_width(i, jQuery('#_pzarc_' + i + '-blueprint-min-cell-width-cmb-field-0').get(0));
    pzarc_show_hide_section(i);
  }

//  jQuery('#pzarc-sections-preview-0').resize(function(){//console.log(this.width);pzarc_refresh_blueprint_layout(0)});
//  jQuery('#pzarc-sections-preview-1').resize(function(){//console.log(this.width);pzarc_refresh_blueprint_layout(1)});
//  jQuery('#pzarc-sections-preview-2').resize(function(){//console.log(this.width);pzarc_refresh_blueprint_layout(2)});


  for (var i = 0; i < 3; i++) {
    jQuery('#_pzarc_' + i + '-blueprint-cells-vert-margin-cmb-field-0').change(function () {
      pzarc_refresh_blueprint_layout(this.id.substr(7,1));
    });
    jQuery('#_pzarc_' + i + '-blueprint-cells-across-cmb-field-0').change(function () {
      pzarc_refresh_blueprint_layout(this.id.substr(7,1));
    });
    jQuery('#_pzarc_' + i + '-blueprint-cells-per-view-cmb-field-0').change(function () {
      pzarc_refresh_blueprint_layout(this.id.substr(7,1));
    });
    jQuery('#_pzarc_' + i + '-blueprint-min-cell-width-cmb-field-0').change(function () {
      pzarc_refresh_blueprint_layout(this.id.substr(7,1));
    });

    jQuery('#_pzarc_' + i + '-blueprint-section-enable-cmb-field-0').change(function () {
      var x = this.id.substr(7,1);
      pzarc_show_hide_section(x);
    });
  }
  jQuery('#_pzarc_blueprint-short-name-cmb-field-0').change(function () {
    pzarc_update_usage_info(this);
  });
  function pzarc_update_usage_info(t){
    //console.log(t.value);
    jQuery('span.pzarc-shortname').text(t.value);
  }


/* Switched to pixel based once, but not as fluid. Butwhat was it's advantage? Why did I switch? */
  function pzarc_update_cell_margin(i,t) {
 //   console.log(i);
    var cellsAcross = jQuery('#_pzarc_'+i+'-blueprint-cells-across-cmb-field-0').get(0).value;
    var containerWidth = jQuery('.pzarc-section-'+i).width();
    //  //console.log(containerWidth);
    jQuery('#pzarc-sections-preview-'+i+' .pzarc-section-cell').each(function (index, value) {
 //     //console.log(((index+1)%cellsAcross),cellsAcross);
//      if (((index+1)%cellsAcross) != 0) {
      jQuery(value).css({'marginRight': (t.value ) + '%'});
//      }
    });
  }

  function pzarc_update_cell_across(i,t) {
 //console.log(i);
    var containerWidth = jQuery('#pzarc-sections-preview-'+i).width();
//    //console.log(containerWidth);
    var cellRightMargin = jQuery('#_pzarc_'+i+'-blueprint-cells-vert-margin-cmb-field-0').val();
    var new_cell_width = (100/ t.value)- cellRightMargin;
    // Can't use width(), it breaks when padding is set.
    jQuery('#pzarc-sections-preview-'+i+' .pzarc-section-cell').css( {"width":new_cell_width + '%'});
  }

  function pzarc_update_cell_count(i,t) {
   // console.log(i);
    jQuery('.pzarc-section-'+i).empty();
    var plugin_url = jQuery('.field.Pizazz_Layout_Field .plugin_url').get(0).textContent;
    var show_count = (t.value==0?10: t.value);
    for (var j = 1; j <= show_count; j++) {
        jQuery('#pzarc-sections-preview-'+i).append('<div class="pzarc-section-cell-' + j + ' pzarc-section-cell" ></div>');
    }
  }

  function pzarc_update_min_width(i,t) {
   //console.log(i);
    jQuery('#pzarc-sections-preview-'+i+' .pzarc-section-cell').css({'minWidth': t.value + 'px'});
  }

  function pzarc_show_hide_section(x) {
    var y = parseInt(x)+1;
 //   console.log(x);
    if (x==0){
      jQuery('#pzarc-sections-preview-0').show();
      jQuery('#blueprint-section-1.postbox').show();
      jQuery('.item-blueprint-section-1').show();
      return;
    }
//    console.log(x,jQuery('#_pzarc_'+x+'-blueprint-section-enable-cmb-field-0').get(0).checked);

    jQuery('#pzarc-sections-preview-'+x).toggle(jQuery('#_pzarc_'+x+'-blueprint-section-enable-cmb-field-0').get(0).checked);
    jQuery('#blueprint-section-'+y+'.postbox').toggle(jQuery('#_pzarc_'+x+'-blueprint-section-enable-cmb-field-0').get(0).checked);
    jQuery('.item-blueprint-section-'+y).toggle(jQuery('#_pzarc_'+x+'-blueprint-section-enable-cmb-field-0').get(0).checked);

    // Need to make this a little clevered
    // needed this coz container was staying big
    jQuery('.item-blueprint-wireframe-preview').trigger('click');

  }
});

//id="_pzarc_blueprint-navigation-cmb-field-0"