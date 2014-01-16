jQuery(document).ready(function() {
	"use strict";

// Would like to get meta configs sortable
// /  jQuery('ul.select2-choices').sortable({cursor:'move'});

//  jQuery("#_pzarc_cell-settings-meta1-config-cmb-field-0").select2("container").find("ul.select2-choices").sortable({
//    containment: 'parent',
//    cursor: "move",
//    opacity: 0.6,
//    forceHelperSize: true,
//    placeholder: "ui-state-highlight",
//    start: function() { jQuery("#_pzarc_cell-settings-meta1-config-cmb-field-0").select2("onSortStart"); },
//    update: function() { jQuery("#_pzarc_cell-settings-meta1-config-cmb-field-0").select2("onSortEnd"); }
//  });

//  init();
  function init() {
//    console.log('you are here');
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());

//    pzarc_update_component_location(cell_layout);
//    pzarc_update_components_container_width(cell_layout);
//    pzarc_update_components_height(cell_layout);
//    pzarc_update_components_nudge(cell_layout);
//    pzarc_update_components_toshow(cell_layout);
//    pzarc_update_component_visibility(cell_layout);
//    pzarc_update_background(cell_layout);
//    pzarc_update_status(cell_layout);
  }

  //***********************
  // Process field values
  //***********************

	//jQuery('.pzarc_palette').draggable({scroll: true});

	// Hidden field containing the plugin url
	var plugin_url = jQuery('.field.Pizazz_Layout_Field .plugin_url').text();

  // Get the fields selector values
//	var $preview_inputs = jQuery("select#_pzarc_layout-show-cmb-field-0 option");

  // Get the current order and widths from the hidden text field attached to preview layout box
  var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());


  pzarc_update_status(cell_layout);

  jQuery('select#_pzarc_layout-excerpt-thumb-cmb-field-0').change(function() {
		if (jQuery(this).val() == 'left') {
      jQuery('.pzarc-draggable-excerpt img.pzarc-align').removeClass('right');
      jQuery('.pzarc-draggable-excerpt img.pzarc-align').removeClass('none');
      jQuery('.pzarc-draggable-excerpt img.pzarc-align').addClass('left');

      jQuery('.pzarc-draggable-content img.pzarc-align').removeClass('right');
      jQuery('.pzarc-draggable-content img.pzarc-align').removeClass('none');
      jQuery('.pzarc-draggable-content img.pzarc-align').addClass('left');
		} else if (jQuery(this).val() == 'right') {
      jQuery('.pzarc-draggable-excerpt img.pzarc-align').removeClass('left');
      jQuery('.pzarc-draggable-excerpt img.pzarc-align').removeClass('none');
      jQuery('.pzarc-draggable-excerpt img.pzarc-align').addClass('right');

      jQuery('.pzarc-draggable-content img.pzarc-align').removeClass('left');
      jQuery('.pzarc-draggable-content img.pzarc-align').removeClass('none');
      jQuery('.pzarc-draggable-content img.pzarc-align').addClass('right');
		} else {
      jQuery('.pzarc-draggable-excerpt img.pzarc-align').addClass('none');
      jQuery('.pzarc-draggable-excerpt img.pzarc-align').removeClass('left');
      jQuery('.pzarc-draggable-excerpt img.pzarc-align').removeClass('right');

      jQuery('.pzarc-draggable-content img.pzarc-align').addClass('none');
      jQuery('.pzarc-draggable-content img.pzarc-align').removeClass('left');
      jQuery('.pzarc-draggable-content img.pzarc-align').removeClass('right');
		}
	});

  jQuery('li.item-cell-designer').on('click',function(index,value){jQuery('#cell-designer-settings').show();});
  jQuery('li.item-styling').on('click',function(index,value){jQuery('#cell-designer-settings').hide();});
  jQuery('li.item-settings').on('click',function(index,value){jQuery('#cell-designer-settings').hide();});

  ////Wha??
  //	// Showhide preview zones if checked
  //	jQuery('#pzarc_layout-show input').change(function(e) {
  //		pzarcUpdatePreview(e,'cshow');
  //	});

 	jQuery('select#_pzarc_layout-background-image-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());
		pzarc_update_background(cell_layout);
	});

  jQuery('select#_pzarc_layout-show-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());
    pzarc_update_components_toshow(cell_layout,e)
  });

	// Set position of zones

  jQuery('select#_pzarc_layout-sections-position-cmb-field-0').change(function(e) {
    console.log(e);
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());
    pzarc_update_component_location(cell_layout);
  });

  // This does bugger all at the moment!
	jQuery('input#_pzarc_layout-cell-height-type-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());
// this is COMPONENTS height, CELLS!
//		pzarc_update_components_height(cell_layout);
	});

	jQuery('input#_pzarc_layout-sections-widths-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());
		pzarc_update_components_container_width(cell_layout);
	});

	jQuery('input#_pzarc_layout-sections-nudge-x-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());
		pzarc_update_components_nudge(cell_layout);
	});

	jQuery('input#_pzarc_layout-sections-nudge-y-cmb-field-0').change(function(e) {
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());
		pzarc_update_components_nudge(cell_layout);
	});

  /***************************
   *
   * sort
   *
   */
	jQuery(".pzarc-dropzone .pzarc-content-area").sortable({
		cursor: "move",
		opacity: 0.6,
		forceHelperSize: true,
		sort: function(event, ui) {
			// gets added unintentionally by droppable interacting with sortable
			// using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
			jQuery(this).removeClass("ui-state-default");
		},
		update: function(event, ui) {
      var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());
//			var sorted = jQuery(".pzarc-dropzone .pzarc-content-area").sortable("toArray", {
//				attribute: "data-idcode"
//			});
//      var new_layout = reorder_parts(cell_layout,sorted);
// //     console.log(cell_layout,new_layout);
//      jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val( JSON.stringify(reorder_parts(cell_layout,sorted) ));
//      var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());
////      //console.log(sorted);
//			jQuery('input[name="_pzarc_layout-field-order-cmb-field-0"]').val(sorted);
//			jQuery('input[name="_pzarc_layout-field-order-cmb-field-0"]').change();
      cell_layout =  pzarc_resort_components(cell_layout);
      pzarc_update_status(cell_layout);
    },
    /*************
    Do on create
    *************/
		create: function(event, ui) {
			var element_html = new Array();
			element_html['title'] = '<span class="pzarc-draggable pzarc-draggable-title" title= "Post title" data-idcode=title style="display: inline-block;font-weight:bold;font-size:15px;"><span>This is the title</span></span>'

			element_html['meta1'] = '<span class="pzarc-draggable pzarc-draggable-meta1 pzarc-draggable-meta"  title= "Meta info 1" data-idcode=meta1 style="font-size:11px;"><span>Jan 1 2013</span></span>';

			element_html['meta2'] = '<span class="pzarc-draggable pzarc-draggable-meta2 pzarc-draggable-meta" title= "Meta info 2"  data-idcode=meta2 style="font-size:11px;"><span>Categories - News, Sport</span></span>';

			element_html['image'] = '<span class="pzarc-draggable pzarc-draggable-image"  title= "Featured image" data-idcode=image style="max-height:100px;overflow:hidden;"><span><img src="PZARC_PLUGIN_URL/assets/images/sample-image.jpg" style="max-width:100%;"/></span></span>';
			element_html['image'] = element_html['image'].replace(/PZARC_PLUGIN_URL/g, plugin_url);
      element_html['caption'] = '<span class="pzarc-draggable pzarc-draggable-caption pzarc-draggable-caption" title="Image caption" data-idcode=caption ><span>Featured image caption</span></span>';

			element_html['content'] = '<span class="pzarc-draggable pzarc-draggable-content" title= "Full post content"  data-idcode=content style="font-size:13px;"><span><img src="PZARC_PLUGIN_URL/assets/images/sample-image.jpg" class="pzarc-align '+jQuery("select#_pzarc_layout-excerpt-thumb-cmb-field-0").val()+'" style="max-width:20%;"/><img src="PZARC_PLUGIN_URL/assets/images/fireworks.jpg" style="max-width:30%;float:left;padding:5px;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;&bull;&nbsp;Cras semper sem hendrerit</li><li>&nbsp;&bull;&nbsp;Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><p>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </p></span></span>';
			element_html['content'] = element_html['content'].replace(/PZARC_PLUGIN_URL/g, plugin_url);

			element_html['excerpt'] = '<span class="pzarc-draggable pzarc-draggable-excerpt"  title= "Excerpt with featured image" data-idcode=excerpt style="font-size:13px;"><span><img src="PZARC_PLUGIN_URL/assets/images/sample-image.jpg" class="pzarc-align '+jQuery("select#_pzarc_layout-excerpt-thumb-cmb-field-0").val()+'" style="max-width:20%;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>';

			element_html['excerpt'] = element_html['excerpt'].replace(/PZARC_PLUGIN_URL/g, plugin_url);

			element_html['meta3'] = '<span class="pzarc-draggable pzarc-draggable-meta3 pzarc-draggable-meta"  title= "Meta info 3" data-idcode=meta3 style="font-size:11px;"><span>Comments: 27</span></span>';
      element_html['custom1'] = '<span class="pzarc-draggable pzarc-draggable-custom1 pzarc-draggable-meta"  title= "Custom field 1" data-idcode=custom1 style="font-size:11px;"><span>Custom content 1</span></span>';
      element_html['custom2'] = '<span class="pzarc-draggable pzarc-draggable-custom2 pzarc-draggable-meta"  title= "Custom field 2" data-idcode=custom2 style="font-size:11px;"><span>Custom content 2</span></span>';
      element_html['custom3'] = '<span class="pzarc-draggable pzarc-draggable-custom3 pzarc-draggable-meta"  title= "Custom field 3" data-idcode=custom3 style="font-size:11px;"><span>Custom content 3</span></span>';

      var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());

      jQuery(".pzarc-content-area.sortable").html('');
      jQuery.each(cell_layout,function(index,value){
        //console.log(index,value);
        jQuery(".pzarc-content-area.sortable").append(element_html[index]);
      });
      pzarc_update_component_location(cell_layout);
      pzarc_update_components_container_width(cell_layout);
      pzarc_update_components_height(cell_layout);
      pzarc_update_components_nudge(cell_layout);
      pzarc_update_components_toshow(cell_layout);
      pzarc_update_component_visibility(cell_layout);
      pzarc_update_background(cell_layout);
      pzarc_update_status(cell_layout);

		}
	});


  /*
  * Process resizing
  *
   */
	jQuery(".pzarc-draggable").resizable({
		handles: "e",
		containment: "parent",
		grid: [4, 1],
		minWidth: 10,
		maxWidth: 400,
		autoHide: true,
		resize: function(event, ui) {
      var zone = jQuery(this).data("idcode");
			var new_width = Math.floor((jQuery(this).width() / 400) * 100);
      var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());
      cell_layout = pzarc_update_component_width(cell_layout,zone,new_width);
      pzarc_update_status(cell_layout);
		}
	});



  /*
  * Resort the components
  */
  function pzarc_resort_components(cell_layout) {
    var new_order ={};
    var sorted = jQuery(".pzarc-dropzone .pzarc-content-area").sortable("toArray", {
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
  function pzarc_update_status(cell_layout) {
    var showing = "";
    jQuery.each(cell_layout,function(index, value){
      if (value.show)  {
        showing = showing + " <strong>"+index+":</strong> "+value.width+"% &nbsp;&nbsp;&nbsp;";
      }
    });
    jQuery("p.pzarc-states").html(showing);
    jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val( JSON.stringify(cell_layout ));
  }

  /*
  * Update visibility
   */
  function pzarc_update_component_visibility(cell_layout) {
    var components_state = jQuery("select#_pzarc_layout-show-cmb-field-0 option");
  //  console.log(components_state,cell_layout);
    jQuery.each(components_state,function(index,value){
  //    console.log(value.value,cell_layout[value.value]);
      cell_layout[value.value].show = value.selected;
      if (value.selected) {
        jQuery('.pzarc-draggable-' + value.value).show();
        jQuery('.pzarc-draggable-' + value.value).css('width',cell_layout[value.value].width+'%');

      } else {
//        console.log('.pzarc-draggable-' + value.value);
        jQuery('.pzarc-draggable-' + value.value).hide();
      }
    });
    pzarc_update_status(cell_layout);
    return cell_layout;
  }

  /*
  * Update component widths
  * Although this is a seemingly superfluous function, it does provide consistency and makes future changes easier.
   */
  function pzarc_update_component_width(cell_layout,zone,new_width) {
    cell_layout[zone].width = new_width;
    return cell_layout;
  }

//pzarcUpdatePreview('');
  /**************************
   *
   *
   * function pzarcUpdatePreview()
   *
   *
   ***************************/
//  function pzarcUpdatePreview(e,whathit) {

    /// add some resets - would save on all those resets being done. Plus add $var decs of each jQ

    //
//    var cell_layout = jQuery.parseJSON(jQuery('input#_pzarc_layout-cell-preview-cmb-field-0').val());
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
//        jQuery('.pzarc-draggable-' + target_name).show();
////        jQuery('#pzarc_layout-show span.' + target_name).text(' (' + jQuery('input#pzarc_layout-' + target_name + '-width').val() + '%)');
//      } else {
//        jQuery('.pzarc-draggable-' + target_name).hide();
//        //       jQuery('#pzarc_layout-show span.' + target_name).text('');
//      }
//    }



      function pzarc_update_background(cell_layout) {

        var plugin_url = jQuery('.field.Pizazz_Layout_Field .plugin_url').text();

        /*********************
         // Update background
         *********************/
        switch (jQuery('select#_pzarc_layout-background-image-cmb-field-0').find('option:selected').val()) {
          case 'fill':
            jQuery('.pzarc-dropzone .pzgp-cell-image-behind').html('<img src="' + plugin_url + '/assets/images/sample-image.jpg"/>');
            jQuery('.pzarc-dropzone .pzgp-cell-image-behind').css({
              'left': '0',
              top: '0',
              right: '',
              bottom: ''
            });
            jQuery('.pzarc-dropzone .pzgp-cell-image-behind img').css({
              'height': '300px',
              'width': ''
            });
            break;
          case 'align':
            jQuery('.pzarc-dropzone .pzgp-cell-image-behind').html('<img src="' + plugin_url + '/assets/images/sample-image.jpg"/>');
            var zonesWidth = jQuery('.pzarc-content-area').width();
            var zonesHeight = jQuery('.pzarc-content-area').height();
            var imageWidth = 400 - zonesWidth;
            var imageHeight = 300 - zonesHeight;
            var sections_position = jQuery('select#_pzarc_layout-sections-position-cmb-field-0').find('option:selected').val();
            switch (sections_position) {
              case 'left':
                jQuery('.pzarc-dropzone .pzgp-cell-image-behind img').css({
                  'height': '300px',
                  'width': '',
                  'left': ''
                });
                jQuery('.pzarc-dropzone .pzgp-cell-image-behind').css({
                  'left': zonesWidth + 'px',
                  'right': '',
                  'top': '',
                  'bottom': ''
                });
                break;
              case 'right':
                jQuery('.pzarc-dropzone .pzgp-cell-image-behind img').css({
                  'height': '300px',
                  'width': '',
                  'left': zonesWidth + 'px'
                });
                jQuery('.pzarc-dropzone .pzgp-cell-image-behind').css({
                  'right': zonesWidth + 'px',
                  'left': '',
                  'top': '',
                  'bottom': ''
                });
                break;
              case 'top':
                jQuery('.pzarc-dropzone .pzgp-cell-image-behind img').css({
                  'width': '400px',
                  'height': '',
                  'left': ''
                });
                jQuery('.pzarc-dropzone .pzgp-cell-image-behind').css({
                  'top': zonesHeight + 'px',
                  'right': '',
                  'left': '',
                  'bottom': ''
                });
                break;
              case 'bottom':
                jQuery('.pzarc-dropzone .pzgp-cell-image-behind img').css({
                  'width': '400px',
                  'height': '',
                  'left': ''
                });
                jQuery('.pzarc-dropzone .pzgp-cell-image-behind').css({
                  'bottom': zonesHeight + 'px',
                  'right': '',
                  'top': '0',
                  'left': ''
                });
                break;
            }
            break;
          case 'none':
            jQuery('.pzarc-dropzone .pzgp-cell-image-behind').html('');
            break;
        }

    }

    function pzarc_update_component_location(cell_layout) {
        /******************
         // Update component location
         ******************/
        switch (jQuery('select#_pzarc_layout-sections-position-cmb-field-0').find('option:selected').val()) {
          case 'bottom':
            jQuery('.pzarc-content-area').css({
              bottom: '0',
              top: '',
              left: '',
              right: ''
            });
            break;
          case 'top':
            jQuery('.pzarc-content-area').css({
              bottom: '',
              top: '0',
              left: '',
              right: ''
            });
            break;
          case 'left':
            jQuery('.pzarc-content-area').css({
              bottom: '0',
              top: '0',
              left: '0',
              right: ''
            });
            break;
          case 'right':
            jQuery('.pzarc-content-area').css({
              bottom: '0',
              top: '0',
              left: '',
              right: '0'
            });
            break;
        }
    //  pzarc_update_components_nudge(cell_layout);
    }

    function pzarc_update_components_container_width(cell_layout){
        /**********************
         // update components width
         **********************/
        jQuery('.pzarc-content-area').css('width', jQuery('input#_pzarc_layout-sections-widths-cmb-field-0').val() + '%')
//      break;
    }
  function pzarc_update_components_height(cell_layout) {
        /**********************
         // Update components height
         **********************/
// this is COMPONENTS height, CELLS!
//        jQuery('.pzarc-content-area').css('height', jQuery('input#_pzarc_layout-cell-height-cmb-field-0').val() + 'px')
//      break;
  }
  function pzarc_update_components_nudge(cell_layout) {
        /*********************
         // Update components nudge
         *********************/
        var nudgexy = [jQuery('input#_pzarc_layout-sections-nudge-x-cmb-field-0').val(),jQuery('input#_pzarc_layout-sections-nudge-y-cmb-field-0').val()];
        var sections_position = jQuery('select#_pzarc_layout-sections-position-cmb-field-0').find('option:selected').val();
        if (sections_position == 'left') {
          jQuery('.pzarc-content-area').css('marginLeft', nudgexy[0] + '%');
          jQuery('.pzarc-content-area').css('marginTop', nudgexy[1] + '%');
        }else if (sections_position == 'top') {
          jQuery('.pzarc-content-area').css('marginLeft', nudgexy[0] + '%');
          jQuery('.pzarc-content-area').css('marginTop', nudgexy[1] + '%');
        } else if (sections_position == 'right') {
          jQuery('.pzarc-content-area').css('marginRight', nudgexy[0] + '%');
          jQuery('.pzarc-content-area').css('marginTop', nudgexy[1] + '%');
        } else if (sections_position == 'bottom') {
          jQuery('.pzarc-content-area').css('marginLeft', nudgexy[0] + '%');
          jQuery('.pzarc-content-area').css('marginBottom', nudgexy[1] + '%');
        }
  }

  function pzarc_update_components_toshow(cell_layout) {
      //    break;
        pzarc_update_component_visibility(cell_layout);
    }






}); // End
// Nothing past here!

