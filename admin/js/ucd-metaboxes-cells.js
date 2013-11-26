jQuery(document).ready(function() {
	"use strict";

	//pzucdUpdatePreview('');
	/**************************
   *
   *
	 * function pzucdUpdatePreview()
   *
   *
	 ***************************/
	function pzucdUpdatePreview(e) {

		/// add some resets - would save on all those resets being done. Plus add $var decs of each jQ

		//
    var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());

//    jQuery.each(cell_layout,function(index, value){
//      //console.log(index, value);
//    });
		//console.log(e);
		var target_id = '#' + e.target.id;

    //check for2which one is being passed. maybe even pass it

    // e is a field that has changed - eg nudgex
    // if e is null, then it's not one of the fields we care about.
    if (target_id != '#') {
      var target_name = jQuery(target_id).val();
      //console.log(target_id);
      if (jQuery(target_id).is(':checked')) {
        jQuery('.pzucd-draggable-' + target_name).show();
//        jQuery('#pzucd_layout-show span.' + target_name).text(' (' + jQuery('input#pzucd_layout-' + target_name + '-width').val() + '%)');
      } else {
        jQuery('.pzucd-draggable-' + target_name).hide();
 //       jQuery('#pzucd_layout-show span.' + target_name).text('');
      }
    }

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

    /**********************
		// update components width
    **********************/
		jQuery('.pzucd-content-area').css('width', jQuery('input#_pzucd_layout-sections-widths-cmb-field-0').val() + '%')

    /**********************
		// Update components height
    **********************/
		jQuery('.pzucd-content-area').css('height', jQuery('input#_pzucd_layout-cell-height-cmb-field-0').val() + 'px')

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
//------------------------
// End pzucdUpdatePreview
//=========================




//***********************
// Process field values
//***********************


	//jQuery('.pzucd_palette').draggable({scroll: true});

	// Hidden field containing the plugin url
	var plugin_url = jQuery('.field.Pizazz_Layout_Field .plugin_url').text();

  // Get the fields selector values
	var $preview_inputs = jQuery("select#_pzucd_layout-show-cmb-field-0 option");

  // Get the current order and widths from the hidden text field attached to preview layout box
  var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());


	$preview_inputs.each(function() {
		var altid = this.value;
		var $source_target = jQuery('.pzucd-draggable-' + altid);
		var $source_percent_text = jQuery('#_pzucd_layout-' + altid + '-width-cmb-field-0');
    //console.log($source_target);
		if (!this.selected) {
			$source_target.hide();
		} else {
//      //console.log($source_target);
			$source_target.show();
      //console.log(altid,cell_layout[altid]);
			$source_target.css("width", cell_layout[altid]+'%');
		}

	});

	var $thumb_align = jQuery('select[name="_pzucd_layout-excerpt-thumb[cmb-field-0]"]');
	$thumb_align.change(function() {
		if (jQuery(this).val() == 'left') {
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-align-right');
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-hide-excerpt-thumb');
			jQuery('.pzucd-draggable-excerpt img').addClass('pzucd-align-left');
		} else if (jQuery(this).val() == 'right') {
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-align-left');
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-hide-excerpt-thumb');
			jQuery('.pzucd-draggable-excerpt img').addClass('pzucd-align-right');
		} else {
			jQuery('.pzucd-draggable-excerpt img').addClass('pzucd-hide-excerpt-thumb');
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-align-left');
			jQuery('.pzucd-draggable-excerpt img').removeClass('pzucd-align-right');
		}
	});



	// Showhide preview zones if checked
	jQuery('#pzucd_layout-show input').change(function(e) {
		pzucdUpdatePreview(e);
	});

 	jQuery('select#_pzucd_layout-background-image-cmb-field-0').change(function(e) {
		pzucdUpdatePreview(e);
	});

	// Set position of zones
	jQuery('select#_pzucd_layout-sections-position-cmb-field-0').change(function(e) {
		pzucdUpdatePreview(e);
	});

	jQuery('input#_pzucd_layout-cell-height-type-cmb-field-0').change(function(e) {
		pzucdUpdatePreview(e);
	});

	jQuery('input#_pzucd_layout-sections-widths-cmb-field-0').change(function(e) {
		pzucdUpdatePreview(e);
	});

	jQuery('input#_pzucd_layout-nudge-section-x-cmb-field-0').change(function(e) {
		pzucdUpdatePreview(e);
	});

	jQuery('input#_pzucd_layout-nudge-section-y-cmb-field-0').change(function(e) {
		pzucdUpdatePreview(e);
	});

	jQuery(".pzucd-dropzone .pzucd-content-area").sortable({
		cursor: "move",
		opacity: 0.6,
		forceHelperSize: true,
		handle: "span",
		sort: function(event, ui) {
			// gets added unintentionally by droppable interacting with sortable
			// using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
			jQuery(this).removeClass("ui-state-default");
		},
		update: function(event, ui) {
      var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
			var sorted = jQuery(".pzucd-dropzone .pzucd-content-area").sortable("toArray", {
				attribute: "data-idcode"
			});
      var new_layout = reorder_parts(cell_layout,sorted);
      console.log(cell_layout,new_layout);
      jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val( JSON.stringify(reorder_parts(cell_layout,sorted) ));
      var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());
//      //console.log(sorted);
			jQuery('input[name="_pzucd_layout-field-order-cmb-field-0"]').val(sorted);
			jQuery('input[name="_pzucd_layout-field-order-cmb-field-0"]').change();
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


			element_html['content'] = '<span class="pzucd-draggable pzucd-draggable-content" title= "Full post content"  data-idcode=content style="font-size:13px;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" style="max-width:30%;float:left;padding:5px;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;&bull;&nbsp;Cras semper sem hendrerit</li><li>&nbsp;&bull;&nbsp;Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><p>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </p><p>Donec dictum leo at erat mattis sollicitudin. Nunc vulputate nisl suscipit enim adipiscing faucibus. Ut faucibus sem non sapien rutrum gravida. Maecenas pharetra mi et velit posuere ac elementum mi tincidunt. Nullam tristique tempus odio id rutrum. Nam ligula urna, semper eget elementum nec, euismod at tortor. Duis commodo, purus id posuere aliquam, orci felis facilisis odio, ac sagittis mi nisl at nibh. Sed non risus eu quam euismod faucibus.</p><p>Proin mattis convallis scelerisque. Curabitur auctor felis id sapien dictum vehicula. Aenean euismod porttitor dictum. Vestibulum nulla leo, volutpat quis tempus eu, accumsan eget ante.</p></span></span>';
			element_html['content'] = element_html['content'].replace(/PZUCD_PLUGIN_URL/, plugin_url);

			if ($thumb_align.val() == 'left') {
				element_html['excerpt'] = '<span class="pzucd-draggable pzucd-draggable-excerpt"  title= "Excerpt with featured image" data-idcode=excerpt style="font-size:13px;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" class="pzucd-align-left" style="max-width:20%;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>';
			} else if ($thumb_align.val() == 'right') {
				element_html['excerpt'] = '<span class="pzucd-draggable pzucd-draggable-excerpt"  title= "Excerpt with featured image" data-idcode=excerpt style="font-size:13px;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" class="pzucd-align-right" style="max-width:20%;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>';
			} else {
				element_html['excerpt'] = '<span class="pzucd-draggable pzucd-draggable-excerpt"  title= "Excerpt with featured image" data-idcode=excerpt style="font-size:13px;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" class="pzucd-hide-excerpt-thumb" style="max-width:20%;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>';

			}
			element_html['excerpt'] = element_html['excerpt'].replace(/PZUCD_PLUGIN_URL/, plugin_url);

			element_html['meta3'] = '<span class="pzucd-draggable pzucd-draggable-meta3 pzucd-draggable-meta"  title= "Meta info 3" data-idcode=meta3 style="font-size:11px;"><span>Comments: 27</span></span>';
      element_html['custom1'] = '<span class="pzucd-draggable pzucd-draggable-custom1 pzucd-draggable-meta"  title= "Custom field 1" data-idcode=custom1 style="font-size:11px;"><span>Custom content 1</span></span>';
      element_html['custom2'] = '<span class="pzucd-draggable pzucd-draggable-custom2 pzucd-draggable-meta"  title= "Custom field 2" data-idcode=custom2 style="font-size:11px;"><span>Custom content 2</span></span>';
      element_html['custom3'] = '<span class="pzucd-draggable pzucd-draggable-custom3 pzucd-draggable-meta"  title= "Custom field 3" data-idcode=custom3 style="font-size:11px;"><span>Custom content 3</span></span>';

      var cell_layout = jQuery.parseJSON(jQuery('input#_pzucd_layout-cell-preview-cmb-field-0').val());

			var sortzone = jQuery(".pzucd-dropzone .pzucd-content-area");
			sortzone.html('');
      var $preview_inputs = jQuery("select#_pzucd_layout-show-cmb-field-0 option");
      var input_states = [];
      $preview_inputs.each(function(){
        input_states[this.value]= this.selected;
      });

      jQuery.each(cell_layout,function(index, value){
        sortzone.html(sortzone.html() + element_html[index]);
        var $source_target = jQuery('.pzucd-draggable-' + index);
        var $source_percent_text = jQuery('span.percent-pzucd_layout-' + index + '-width');
        var $source_input = jQuery('input[name="_pzucd_layout-' + index + '-width[cmb-field-0]"]');

        if (input_states[index] == true ) {
          $source_target.show();
          $source_target.css("width", value+'%');
//          $source_input.val(value);
        } else {
          $source_target.hide();
//          $source_input.val('');
        }

        //console.log(index, value);
      });


      pzucdUpdatePreview(event);

		}
	});


	jQuery(".pzucd-draggable").resizable({
		handles: "e",
		containment: "parent",
		grid: [4, 1],
		minWidth: 10,
		maxWidth: 400,
		autoHide: true,
		resize: function(event, ui) {
			var zone = jQuery(this).data("idcode");
			var new_percent = Math.floor((jQuery(this).width() / 400) * 100);
			jQuery("#_pzucd_layout-" + zone + "-width[cmb-field-0]").val(new_percent);
			jQuery("#_pzucd_layout-" + zone + "-width[cmb-field-0]").change();

      // update the array
//			jQuery("#pzucd_layout-show span." + zone).text(' (' + new_percent + '%)');

		}
	});




//	jQuery('.pzucd-meta-tab-title').mousedown(function(){
//			jQuery('.pzucd-draggable-title').css("background" , "#caa");
//	});

	jQuery('.pzucd-meta-nav li').mousedown(function() {


		var altid = jQuery(this).text().toLowerCase();

	});

	jQuery('#pzucd-layouts-id input').mouseover(function(event) {

		var $source_input = jQuery(event.target);
		var altid = $source_input.attr("alt");
		var $source_target = jQuery('.pzucd-draggable-' + altid);
		var $source_percent_text = jQuery('span.percent-pzucd_layout-' + altid + '-width');

		//  	$source_target.css("background" , "#caa");

		$source_input.mousedown(function() {
			$source_input.change(function() {

				var $input = jQuery(this);

	//			$source_target.css("width", $input.val() + '%');
				if ($input.val() == 0) {
					$source_target.hide();
//					$source_percent_text.text('Not used');
				} else {
					$source_target.show();
	//				$source_percent_text.text($input.val() + '%');
				}

			});
		});

		$source_input.mouseleave(function() {
			//    $source_target.css("background" , "#ddd")
		});
	});



  function reorder_parts(o_from,a_to) {
  var new_from ={};

    jQuery.each(a_to,function(index, value){
      //console.log(index, value);
      new_from[value]= o_from[value];

    });
    //console.log(new_from);
    return new_from;
  }
});