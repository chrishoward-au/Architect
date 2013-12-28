jQuery(document).ready(function() {
	"use strict";

// Would like to get meta configs sortable
// /  jQuery('ul.select2-choices').sortable({cursor:'move'});

//  jQuery("#_pzucd_cell-settings-meta1-config-cmb-field-0").select2("container").find("ul.select2-choices").sortable({
//    containment: 'parent',
//    cursor: "move",
//    opacity: 0.6,
//    forceHelperSize: true,
//    placeholder: "ui-state-highlight",
//    start: function() { jQuery("#_pzucd_cell-settings-meta1-config-cmb-field-0").select2("onSortStart"); },
//    update: function() { jQuery("#_pzucd_cell-settings-meta1-config-cmb-field-0").select2("onSortEnd"); }
//  });

//  init();
  function init() {
//    console.log('you are here');
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
//    pzucd_update_component_location(cell_layout);
//    pzucd_update_components_container_width(cell_layout);
//    pzucd_update_components_height(cell_layout);
//    pzucd_update_components_nudge(cell_layout);
//    pzucd_update_components_toshow(cell_layout);
//    pzucd_update_component_visibility(cell_layout);
//    pzucd_update_background(cell_layout);
//    pzucd_update_status(cell_layout);
  }

  //***********************
  // Process field values
  //***********************

	//jQuery('.pzucd_palette').draggable({scroll: true});

	// Hidden field containing the plugin url
	var plugin_url = jQuery('.field.Pizazz_Layout_Field .plugin_url').text();

  // Get the fields selector values
//	var $preview_inputs = jQuery("select#_pzucd_layout-show-cmb-field-0 option");

  // Get the current order and widths from the hidden text field attached to preview layout box
  var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());


  pzucd_update_status(cell_layout);

  jQuery('select#_pzucd_layout-excerpt-thumb-cmb-field-0').change(function() {
		if (jQuery(this).val() == 'left') {
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-align-right');
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-align-none');
			jQuery('.pzucd-draggable-excerpt img').addClass('pzucd-align-left');
		} else if (jQuery(this).val() == 'right') {
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-align-left');
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-align-none');
			jQuery('.pzucd-draggable-excerpt img').addClass('pzucd-align-right');
		} else {
			jQuery('.pzucd-draggable-excerpt img').addClass('pzucd-align-none');
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-align-left');
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-align-right');
		}
	});


  ////Wha??
  //	// Showhide preview zones if checked
  //	jQuery('#pzucd_layout-show input').change(function(e) {
  //		pzucdUpdatePreview(e,'cshow');
  //	});

 	jQuery('select#_pzucd_layout-background-image-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
		pzucd_update_background(cell_layout);
	});

  jQuery('select#_pzucd_layout-show-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
    pzucd_update_components_toshow(cell_layout,e)
  });

	// Set position of zones

  jQuery('select#_pzucd_layout-sections-position-cmb-field-0').change(function(e) {
    console.log(e);
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
    pzucd_update_component_location(cell_layout);
  });

  // This does bugger all at the moment!
	jQuery('input#_pzucd_layout-cell-height-type-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
// this is COMPONENTS height, CELLS!
//		pzucd_update_components_height(cell_layout);
	});

	jQuery('input#_pzucd_layout-sections-widths-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
		pzucd_update_components_container_width(cell_layout);
	});

	jQuery('input#_pzucd_layout-nudge-section-x-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
		pzucd_update_components_nudge(cell_layout);
	});

	jQuery('input#_pzucd_layout-nudge-section-y-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
		pzucd_update_components_nudge(cell_layout);
	});

  /***************************
   *
   * sort
   *
   */
	jQuery(".pzucd-dropzone .pzucd-content-area").sortable({
		cursor: "move",
		opacity: 0.6,
		forceHelperSize: true,
		sort: function(event, ui) {
			// gets added unintentionally by droppable interacting with sortable
			// using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
			jQuery(this).removeClass("ui-state-default");
		},
		update: function(event, ui) {
      var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
//			var sorted = jQuery(".pzucd-dropzone .pzucd-content-area").sortable("toArray", {
//				attribute: "data-idcode"
//			});
//      var new_layout = reorder_parts(cell_layout,sorted);
// //     console.log(cell_layout,new_layout);
//      jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val( JSON.stringify(reorder_parts(cell_layout,sorted) ));
//      var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
////      //console.log(sorted);
//			jQuery('input[name="_pzucd_layout-field-order-cmb-field-0"]').val(sorted);
//			jQuery('input[name="_pzucd_layout-field-order-cmb-field-0"]').change();
      cell_layout =  pzucd_resort_components(cell_layout);
      pzucd_update_status(cell_layout);
    },
    /*************
    Do on create
    *************/
		create: function(event, ui) {
			var element_html = new Array();
			element_html['title'] = '<span class="pzucd-draggable pzucd-draggable-title" title= "Post title" data-idcode=title style="display: inline-block;font-weight:bold;font-size:15px;"><span>This is the title</span></span>'

			element_html['meta1'] = '<span class="pzucd-draggable pzucd-draggable-meta1 pzucd-draggable-meta"  title= "Meta info 1" data-idcode=meta1 style="font-size:11px;"><span>Jan 1 2013</span></span>';

			element_html['meta2'] = '<span class="pzucd-draggable pzucd-draggable-meta2 pzucd-draggable-meta" title= "Meta info 2"  data-idcode=meta2 style="font-size:11px;"><span>Categories - News, Sport</span></span>';

			element_html['image'] = '<span class="pzucd-draggable pzucd-draggable-image"  title= "Featured image" data-idcode=image style="max-height:100px;overflow:hidden;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" style="max-width:100%;"/></span></span>';
			element_html['image'] = element_html['image'].replace(/PZUCD_PLUGIN_URL/, plugin_url);


			element_html['content'] = '<span class="pzucd-draggable pzucd-draggable-content" title= "Full post content"  data-idcode=content style="font-size:13px;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" style="max-width:30%;float:left;padding:5px;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;&bull;&nbsp;Cras semper sem hendrerit</li><li>&nbsp;&bull;&nbsp;Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><p>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </p></span></span>';
			element_html['content'] = element_html['content'].replace(/PZUCD_PLUGIN_URL/, plugin_url);

			element_html['excerpt'] = '<span class="pzucd-draggable pzucd-draggable-excerpt"  title= "Excerpt with featured image" data-idcode=excerpt style="font-size:13px;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" class="pzucd-align-'+jQuery("select#_pzucd_layout-excerpt-thumb-cmb-field-0").val()+'" style="max-width:20%;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>';

			element_html['excerpt'] = element_html['excerpt'].replace(/PZUCD_PLUGIN_URL/, plugin_url);

			element_html['meta3'] = '<span class="pzucd-draggable pzucd-draggable-meta3 pzucd-draggable-meta"  title= "Meta info 3" data-idcode=meta3 style="font-size:11px;"><span>Comments: 27</span></span>';
      element_html['custom1'] = '<span class="pzucd-draggable pzucd-draggable-custom1 pzucd-draggable-meta"  title= "Custom field 1" data-idcode=custom1 style="font-size:11px;"><span>Custom content 1</span></span>';
      element_html['custom2'] = '<span class="pzucd-draggable pzucd-draggable-custom2 pzucd-draggable-meta"  title= "Custom field 2" data-idcode=custom2 style="font-size:11px;"><span>Custom content 2</span></span>';
      element_html['custom3'] = '<span class="pzucd-draggable pzucd-draggable-custom3 pzucd-draggable-meta"  title= "Custom field 3" data-idcode=custom3 style="font-size:11px;"><span>Custom content 3</span></span>';

      var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());

      jQuery(".pzucd-content-area.sortable").html('');
      jQuery.each(cell_layout,function(index,value){
        //console.log(index,value);
        jQuery(".pzucd-content-area.sortable").append(element_html[index]);
      });
      pzucd_update_component_location(cell_layout);
      pzucd_update_components_container_width(cell_layout);
      pzucd_update_components_height(cell_layout);
      pzucd_update_components_nudge(cell_layout);
      pzucd_update_components_toshow(cell_layout);
      pzucd_update_component_visibility(cell_layout);
      pzucd_update_background(cell_layout);
      pzucd_update_status(cell_layout);

		}
	});


  /*
  * Process resizing
  *
   */
	jQuery(".pzucd-draggable").resizable({
		handles: "e",
		containment: "parent",
		grid: [4, 1],
		minWidth: 10,
		maxWidth: 400,
		autoHide: true,
		resize: function(event, ui) {
      var zone = jQuery(this).data("idcode");
			var new_width = Math.floor((jQuery(this).width() / 400) * 100);
      var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
      cell_layout = pzucd_update_component_width(cell_layout,zone,new_width);
      pzucd_update_status(cell_layout);
		}
	});



  /*
  * Resort the components
  */
  function pzucd_resort_components(cell_layout) {
    var new_order ={};
    var sorted = jQuery(".pzucd-dropzone .pzucd-content-area").sortable("toArray", {
      attribute: "data-idcode"
    });

    jQuery.each(sorted,function(index, value){
      new_order[value]= cell_layout[value];
    });
    return new_order;
  }



  /*
  * Update status message and field data
  */
  function pzucd_update_status(cell_layout) {
    var showing = "";
    jQuery.each(cell_layout,function(index, value){
      if (value.show)  {
        showing = showing + " <strong>"+index+":</strong> "+value.width+"% &nbsp;&nbsp;&nbsp;";
      }
    });
    jQuery("p.pzucd-states").html(showing);
    jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val( JSON.stringify(cell_layout ));
  }

  /*
  * Update visibility
   */
  function pzucd_update_component_visibility(cell_layout) {
    var components_state = jQuery("select#_pzucd_layout-show-cmb-field-0 option");
    console.log(components_state);
    jQuery.each(components_state,function(index,value){
      console.log(value.value,cell_layout[value.value]);
      cell_layout[value.value].show = value.selected;
      if (value.selected) {
        jQuery('.pzucd-draggable-' + value.value).show();
        jQuery('.pzucd-draggable-' + value.value).css('width',cell_layout[value.value].width+'%');

      } else {
//        console.log('.pzucd-draggable-' + value.value);
        jQuery('.pzucd-draggable-' + value.value).hide();
      }
    });
    pzucd_update_status(cell_layout);
    return cell_layout;
  }

  /*
  * Update component widths
  * Although this is a seemingly superfluous function, it does provide consistency and makes future changes easier.
   */
  function pzucd_update_component_width(cell_layout,zone,new_width) {
    cell_layout[zone].width = new_width;
    return cell_layout;
  }

//pzucdUpdatePreview('');
  /**************************
   *
   *
   * function pzucdUpdatePreview()
   *
   *
   ***************************/
//  function pzucdUpdatePreview(e,whathit) {

    /// add some resets - would save on all those resets being done. Plus add $var decs of each jQ

    //
//    var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
//    jQuery.each(cell_layout,function(index, value){
//      //console.log(index, value);
//    });
    //console.log(e);
//    var target_id = '#' + e.target.id;
//
//    //check for2which one is being passed. maybe even pass it
//
//    // e is a field that has changed - eg nudgex
//    // if e is null, then it's not one of the fields we care about.
//    if (target_id != '#') {
//      var target_name = jQuery(target_id).val();
//      console.log(target_id,target_name,e);
//      if (jQuery(target_id).is(':checked')) {
//        jQuery('.pzucd-draggable-' + target_name).show();
////        jQuery('#pzucd_layout-show span.' + target_name).text(' (' + jQuery('input#pzucd_layout-' + target_name + '-width').val() + '%)');
//      } else {
//        jQuery('.pzucd-draggable-' + target_name).hide();
//        //       jQuery('#pzucd_layout-show span.' + target_name).text('');
//      }
//    }



      function pzucd_update_background(cell_layout) {

        var plugin_url = jQuery('.field.Pizazz_Layout_Field .plugin_url').text();

        /*********************
         // Update background
         *********************/
        switch (jQuery('select#_pzucd_layout-background-image-cmb-field-0').find('option:selected').val()) {
          case 'fill':
            jQuery('.pzucd-dropzone .pzgp-cell-image-behind').html('<img src="' + plugin_url + '/assets/images/sample-image.jpg"/>');
            jQuery('.pzucd-dropzone .pzgp-cell-image-behind').css({
              'left': '0',
              top: '0',
              right: '',
              bottom: ''
            });
            jQuery('.pzucd-dropzone .pzgp-cell-image-behind img').css({
              'height': '300px',
              'width': ''
            });
            break;
          case 'align':
            jQuery('.pzucd-dropzone .pzgp-cell-image-behind').html('<img src="' + plugin_url + '/assets/images/sample-image.jpg"/>');
            var zonesWidth = jQuery('.pzucd-content-area').width();
            var zonesHeight = jQuery('.pzucd-content-area').height();
            var imageWidth = 400 - zonesWidth;
            var imageHeight = 300 - zonesHeight;
            var sections_position = jQuery('select#_pzucd_layout-sections-position-cmb-field-0').find('option:selected').val();
            switch (sections_position) {
              case 'left':
                jQuery('.pzucd-dropzone .pzgp-cell-image-behind img').css({
                  'height': '300px',
                  'width': '',
                  'left': ''
                });
                jQuery('.pzucd-dropzone .pzgp-cell-image-behind').css({
                  'left': zonesWidth + 'px',
                  'right': '',
                  'top': '',
                  'bottom': ''
                });
                break;
              case 'right':
                jQuery('.pzucd-dropzone .pzgp-cell-image-behind img').css({
                  'height': '300px',
                  'width': '',
                  'left': zonesWidth + 'px'
                });
                jQuery('.pzucd-dropzone .pzgp-cell-image-behind').css({
                  'right': zonesWidth + 'px',
                  'left': '',
                  'top': '',
                  'bottom': ''
                });
                break;
              case 'top':
                jQuery('.pzucd-dropzone .pzgp-cell-image-behind img').css({
                  'width': '400px',
                  'height': '',
                  'left': ''
                });
                jQuery('.pzucd-dropzone .pzgp-cell-image-behind').css({
                  'top': zonesHeight + 'px',
                  'right': '',
                  'left': '',
                  'bottom': ''
                });
                break;
              case 'bottom':
                jQuery('.pzucd-dropzone .pzgp-cell-image-behind img').css({
                  'width': '400px',
                  'height': '',
                  'left': ''
                });
                jQuery('.pzucd-dropzone .pzgp-cell-image-behind').css({
                  'bottom': zonesHeight + 'px',
                  'right': '',
                  'top': '0',
                  'left': ''
                });
                break;
            }
            break;
          case 'none':
            jQuery('.pzucd-dropzone .pzgp-cell-image-behind').html('');
            break;
        }

    }

    function pzucd_update_component_location(cell_layout) {
        /******************
         // Update component location
         ******************/
        switch (jQuery('select#_pzucd_layout-sections-position-cmb-field-0').find('option:selected').val()) {
          case 'bottom':
            jQuery('.pzucd-content-area').css({
              bottom: '0',
              top: '',
              left: '',
              right: ''
            });
            break;
          case 'top':
            jQuery('.pzucd-content-area').css({
              bottom: '',
              top: '0',
              left: '',
              right: ''
            });
            break;
          case 'left':
            jQuery('.pzucd-content-area').css({
              bottom: '0',
              top: '0',
              left: '0',
              right: ''
            });
            break;
          case 'right':
            jQuery('.pzucd-content-area').css({
              bottom: '0',
              top: '0',
              left: '',
              right: '0'
            });
            break;
        }
    //  pzucd_update_components_nudge(cell_layout);
    }

    function pzucd_update_components_container_width(cell_layout){
        /**********************
         // update components width
         **********************/
        jQuery('.pzucd-content-area').css('width', jQuery('input#_pzucd_layout-sections-widths-cmb-field-0').val() + '%')
//      break;
    }
  function pzucd_update_components_height(cell_layout) {
        /**********************
         // Update components height
         **********************/
// this is COMPONENTS height, CELLS!
//        jQuery('.pzucd-content-area').css('height', jQuery('input#_pzucd_layout-cell-height-cmb-field-0').val() + 'px')
//      break;
  }
  function pzucd_update_components_nudge(cell_layout) {
        /*********************
         // Update components nudge
         *********************/
        var nudgexy = [jQuery('input#_pzucd_layout-nudge-section-x-cmb-field-0').val(),jQuery('input#_pzucd_layout-nudge-section-y-cmb-field-0').val()];
        var sections_position = jQuery('select#_pzucd_layout-sections-position-cmb-field-0').find('option:selected').val();
        if (sections_position == 'left' || sections_position == 'top') {
          jQuery('.pzucd-content-area').css('marginLeft', nudgexy[0] + '%');
          jQuery('.pzucd-content-area').css('marginTop', nudgexy[1] + '%');
        } else if (sections_position == 'right' || sections_position == 'bottom') {
          jQuery('.pzucd-content-area').css('marginRight', nudgexy[0] + '%');
          jQuery('.pzucd-content-area').css('marginBottom', nudgexy[1] + '%');
        }
  }

  function pzucd_update_components_toshow(cell_layout) {
      //    break;
        pzucd_update_component_visibility(cell_layout);
    }






}); // End
// Nothing past here!

