jQuery(document).ready(function() {
	"use strict";

	//pzucdUpdatePreview('');
	/**************************
	 function pzucdUpdatePreview() 
	 ***************************/
	function pzucdUpdatePreview(e) {

		/// add some resets - would save on all those resets being done. Plus add $var decs of each jQ

		//	
		console.log(e);
		var target_id = '#' + e.target.id;
//check for2which one is being passed. maybe even pass it

		var target_name = jQuery(target_id).val();
		if (jQuery(target_id).is(':checked')) {
			jQuery('.pzucd-dropped-' + target_name).show();
			jQuery('#pzucd_layout-show span.' + target_name).text(' (' + jQuery('input#pzucd_layout-' + target_name + '-width').val() + '%)');
		} else {
			jQuery('.pzucd-dropped-' + target_name).hide();
			jQuery('#pzucd_layout-show span.' + target_name).text('');
		}


		// Update background
		switch (jQuery('select#pzucd_layout-background-image').find('option:selected').val()) {
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
				var sections_position = jQuery('select#pzucd_layout-sections-position').find('option:selected').val();
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
		// Update positions
		switch (jQuery('select#pzucd_layout-sections-position').find('option:selected').val()) {
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
		// update section width
		jQuery('.pzucd-content-area').css('width', jQuery('input#pzucd_layout-sections-widths').val() + '%')

		// Update cells height
		jQuery('.pzucd-content-area').css('height', jQuery('input#pzucd_layout-cell-height').val() + 'px')

		// Update zones nudge
		var nudgexy = [jQuery('input#pzucd_layout-nudge-section-x').val(),jQuery('input#pzucd_layout-nudge-section-y').val()];
		var sections_position = jQuery('select#pzucd_layout-sections-position').find('option:selected').val();
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
	var plugin_url = jQuery('#pzucd-custom-pzucd_layout-cell-preview .plugin_url').text();
	var $preview_inputs = jQuery("#pzucd-form-table-Layout input");


	$preview_inputs.each(function() {
		var altid = this.alt;
		var $source_target = jQuery('.pzucd-dropped-' + altid);
		var $source_percent_text = jQuery('span.percent-pzucd_layout-' + altid + '-width');
		if (jQuery(this).val() == 0) {
			$source_target.hide();
			$source_percent_text.text('Not used');
		} else {
			$source_target.show();
			$source_target.css("width", jQuery(this).val() + '%');
		}

	});

	var $thumb_align = jQuery('select[name="pzucd_layout-excerpt-thumb"]');
	$thumb_align.change(function() {
		if (jQuery(this).val() == 'left') {
			jQuery('.pzucd-dropped-excerpt img').removeClass('pzucd-align-right');
			jQuery('.pzucd-dropped-excerpt img').removeClass('pzucd-hide-excerpt-thumb');
			jQuery('.pzucd-dropped-excerpt img').addClass('pzucd-align-left');
		} else if (jQuery(this).val() == 'right') {
			jQuery('.pzucd-dropped-excerpt img').removeClass('pzucd-align-left');
			jQuery('.pzucd-dropped-excerpt img').removeClass('pzucd-hide-excerpt-thumb');
			jQuery('.pzucd-dropped-excerpt img').addClass('pzucd-align-right');
		} else {
			jQuery('.pzucd-dropped-excerpt img').addClass('pzucd-hide-excerpt-thumb');
			jQuery('.pzucd-dropped-excerpt img').removeClass('pzucd-align-left');
			jQuery('.pzucd-dropped-excerpt img').removeClass('pzucd-align-right');
		}
	});



	// Showhide preview zones if checked
	jQuery('#pzucd_layout-show input').change(function(e) {
		pzucdUpdatePreview(e);
	});

	jQuery('select#pzucd_layout-background-image').change(function(e) {
		pzucdUpdatePreview(e);
	});

	// Set position of zones
	jQuery('select#pzucd_layout-sections-position').change(function(e) {
		pzucdUpdatePreview(e);
	});

	jQuery('input#pzucd_layout-cell-height').change(function(e) {
		pzucdUpdatePreview(e);
	});

	jQuery('input#pzucd_layout-sections-widths').change(function(e) {
		pzucdUpdatePreview(e);
	});

	jQuery('input#pzucd_layout-nudge-section-x').change(function(e) {
		pzucdUpdatePreview(e);
	});

	jQuery('input#pzucd_layout-nudge-section-y').change(function(e) {
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
			var sorted = jQuery(".pzucd-dropzone .pzucd-content-area").sortable("toArray", {
				attribute: "data-idcode"
			});
			jQuery('input[name="pzucd_layout-field-order"]').val(sorted);
			jQuery('input[name="pzucd_layout-field-order"]').change();
		},
		create: function(event, ui) {
			var element_html = new Array();
			element_html['title'] = '<span class="pzucd-dropped pzucd-dropped-title" title= "Post title" data-idcode="%title%" style="display: inline-block;font-weight:bold;font-size:15px;"><span>This is the title</span></span>'

			element_html['meta1'] = '<span class="pzucd-dropped pzucd-dropped-meta1 pzucd-dropped-meta"  title= "Meta info 1" data-idcode="%meta1%" style="font-size:11px;"><span>Jan 1 2013</span></span>';

			element_html['meta2'] = '<span class="pzucd-dropped pzucd-dropped-meta2 pzucd-dropped-meta" title= "Meta info 2"  data-idcode="%meta2%" style="font-size:11px;"><span>Categories - News, Sport</span></span>';

			element_html['image'] = '<span class="pzucd-dropped pzucd-dropped-image"  title= "Featured image" data-idcode="%image%" style="max-height:100px;overflow:hidden;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" style="max-width:100%;"/></span></span>';
			element_html['image'] = element_html['image'].replace(/PZUCD_PLUGIN_URL/, plugin_url);


			element_html['content'] = '<span class="pzucd-dropped pzucd-dropped-content" title= "Full post content"  data-idcode="%content%" style="font-size:13px;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" style="max-width:30%;float:left;padding:5px;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;&bull;&nbsp;Cras semper sem hendrerit</li><li>&nbsp;&bull;&nbsp;Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><p>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </p><p>Donec dictum leo at erat mattis sollicitudin. Nunc vulputate nisl suscipit enim adipiscing faucibus. Ut faucibus sem non sapien rutrum gravida. Maecenas pharetra mi et velit posuere ac elementum mi tincidunt. Nullam tristique tempus odio id rutrum. Nam ligula urna, semper eget elementum nec, euismod at tortor. Duis commodo, purus id posuere aliquam, orci felis facilisis odio, ac sagittis mi nisl at nibh. Sed non risus eu quam euismod faucibus.</p><p>Proin mattis convallis scelerisque. Curabitur auctor felis id sapien dictum vehicula. Aenean euismod porttitor dictum. Vestibulum nulla leo, volutpat quis tempus eu, accumsan eget ante.</p></span></span>';
			element_html['content'] = element_html['content'].replace(/PZUCD_PLUGIN_URL/, plugin_url);

			if ($thumb_align.val() == 'left') {
				element_html['excerpt'] = '<span class="pzucd-dropped pzucd-dropped-excerpt"  title= "Excerpt with featured image" data-idcode="%excerpt%" style="font-size:13px;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" class="pzucd-align-left" style="max-width:20%;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>';
			} else if ($thumb_align.val() == 'right') {
				element_html['excerpt'] = '<span class="pzucd-dropped pzucd-dropped-excerpt"  title= "Excerpt with featured image" data-idcode="%excerpt%" style="font-size:13px;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" class="pzucd-align-right" style="max-width:20%;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>';
			} else {
				element_html['excerpt'] = '<span class="pzucd-dropped pzucd-dropped-excerpt"  title= "Excerpt with featured image" data-idcode="%excerpt%" style="font-size:13px;"><span><img src="PZUCD_PLUGIN_URL/assets/images/sample-image.jpg" class="pzucd-hide-excerpt-thumb" style="max-width:20%;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>';

			}
			element_html['excerpt'] = element_html['excerpt'].replace(/PZUCD_PLUGIN_URL/, plugin_url);

			element_html['meta3'] = '<span class="pzucd-dropped pzucd-dropped-meta3 pzucd-dropped-meta"  title= "Meta info 3" data-idcode="%meta3%" style="font-size:11px;"><span>Comments: 27</span></span>';

			var sorted = jQuery('input[name="pzucd_layout-field-order"]').val();
			var sort_order = sorted.split(',');
			var sortzone = jQuery(".pzucd-dropzone .pzucd-content-area");
			sortzone.html('');
			var i = 0;
			for (i = 0; i < sort_order.length; i++) {

				var element_id = sort_order[i].replace(/%/g, "");
				element_id = element_id.replace(/(^\s+|\s+$)/g, '');
				sortzone.html(sortzone.html() + element_html[element_id]);
				console.log('[' + element_id + ']');
				var $source_target = jQuery('.pzucd-dropped-' + element_id);
				var $source_percent_text = jQuery('span.percent-pzucd_layout-' + element_id + '-width');
				var $source_input = jQuery('input[name="pzucd_layout-' + element_id + '-width"]');
				var $source_shown = jQuery('#pzucd_layout-show input#pzucd_layout-show_' + element_id);
				console.log('.pzucd-dropped-' + element_id, 'input[name="pzucd_layout-' + element_id + '-width"]', '#pzucd_layout-show input#pzucd_layout-show_' + element_id);
				if ($source_shown.is(':checked')) {
					$source_target.show();
					$source_target.css("width", $source_input.val() + '%');
					$source_percent_text.text($source_input.val() + '%');
					jQuery("#pzucd_layout-show span." + element_id).text(' (' + $source_input.val() + '%)').css({'font-weight':'bold','color':'#7DB0C7'});
				} else {
					$source_target.hide();
					$source_percent_text.text('Not used');
				}
			}
			pzucdUpdatePreview(event);

		}
	});


	jQuery(".pzucd-dropped").resizable({
		handles: "e",
		containment: "parent",
		grid: [4, 1],
		minWidth: 10,
		maxWidth: 400,
		autoHide: true,
		resize: function(event, ui) {
			var zone = jQuery(this).data("idcode").replace(/%/g, "");
			var new_percent = Math.floor((jQuery(this).width() / 400) * 100);
			jQuery("#pzucd_layout-" + zone + "-width").val(new_percent);
			jQuery("#pzucd_layout-" + zone + "-width").change();
			jQuery("#pzucd_layout-show span." + zone).text(' (' + new_percent + '%)');

		}
	});




//	jQuery('.pzucd-meta-tab-title').mousedown(function(){
//			jQuery('.pzucd-dropped-title').css("background" , "#caa");
//	});

	jQuery('.pzucd-meta-nav li').mousedown(function() {


		var altid = jQuery(this).text().toLowerCase();

	});

	jQuery('#pzucd-layouts-id input').mouseover(function(event) {

		var $source_input = jQuery(event.target);
		var altid = $source_input.attr("alt");
		var $source_target = jQuery('.pzucd-dropped-' + altid);
		var $source_percent_text = jQuery('span.percent-pzucd_layout-' + altid + '-width');

		//  	$source_target.css("background" , "#caa");

		$source_input.mousedown(function() {
			$source_input.change(function() {

				var $input = jQuery(this);

				$source_target.css("width", $input.val() + '%');
				if ($input.val() == 0) {
					$source_target.hide();
					$source_percent_text.text('Not used');
				} else {
					$source_target.show();
					$source_percent_text.text($input.val() + '%');
				}

			});
		});

		$source_input.mouseleave(function() {
			//    $source_target.css("background" , "#ddd")
		});
	});



});