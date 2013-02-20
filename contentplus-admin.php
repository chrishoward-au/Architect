<?php

/* Create the layouts custom post type 
*/
add_action('init', 'contentplus_create_layouts_post_type');

if (is_admin()) {
	add_action('admin_init', 'contentplus_layouts_meta');
	add_action('admin_head', 'contentplus_admin_head');
	add_action('admin_enqueue_scripts','contentplus_admin_enqueue');
}

function contentplus_admin_enqueue() {
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-button');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-resizable');

	wp_enqueue_style('contentplus-block-css', CPLUS_PLUGIN_URL.'/css/contentplus-back.css');
	wp_enqueue_style('contentplus-jqueryui-css', CPLUS_PLUGIN_URL.'/css/smoothness/jquery-ui-1.10.1.custom.min.css');

	
}
function contentplus_admin_head() {
		wp_enqueue_script('jquery-cplus-metaboxes',CPLUS_PLUGIN_URL.'/js/cplus_metaboxes.js',array('jquery'));
}


	function contentplus_create_layouts_post_type() 
		{

		 $labels = array(
			'name' => _x('Cell Layouts', 'post type general name'),
			'singular_name' => _x('Cell Layout', 'post type singular name'),
			'add_new' => __('Add New Cell Layout'),
			'add_new_item' => __('Add Cell New Layout'),
			'edit_item' => __('Edit Cell Layout'),
			'new_item' => __('New Cell Layout'),
			'view_item' => __('View Cell Layout'),
			'search_items' => __('Search Cell Layouts'),
			'not_found' =>  __('No cell layouts found'),
			'not_found_in_trash' => __('No cell layouts found in Trash'), 
			'parent_item_colon' => '',
	    'menu_name' => _x( 'ContentPlus Layouts', 'contentplus-layouts' ),
		  );

		  $args = array(
			'labels' => $labels,
			'description' => __('ContentPlus cell layouts are used to create reusable cell layouts for use in your ContentPlus blocks'),
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => true, 
			'show_in_menu' => 'pizazzwp',
			'show_in_nav_menus' => false, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => false, 
			'hierarchical' => false,
			'menu_position' => 45,
			'supports' => array('title','revisions'),
			'exclude_from_search' => true,
			'register_meta_box_cb' => 'contentplus_layouts_meta'
		  ); 

		  register_post_type('contentplus-layouts',$args);
			
		}

		
		
		
	function contentplus_layouts_meta() {
		global $contentplus_cpt_layouts_meta_boxes;
				
		// Fill any defaults if necessary
		$contentplus_cpt_layouts_meta_boxes = contentplus_layout_defaults();

		contentplus_populate_layout_options();

			add_meta_box($contentplus_cpt_layouts_meta_boxes['id'], 
			$contentplus_cpt_layouts_meta_boxes['title'], 
			'contentplus_show_box', 
			$contentplus_cpt_layouts_meta_boxes['page'], 
			$contentplus_cpt_layouts_meta_boxes['context'], 
			$contentplus_cpt_layouts_meta_boxes['priority'],
				$contentplus_cpt_layouts_meta_boxes
		);

	} // End layouts_meta


	function contentplus_populate_layout_options() {
			global $contentplus_cpt_layouts_meta_boxes;

				$prefix = 'contentplus_';
				/*
				 *
				 * Setup Layouts extra fields
				 *
				 */
				$i = 0;
				$contentplus_cpt_layouts_meta_boxes = array(
						'id' => 'contentplus-layouts-id',
						'title' => 'Layouts',
						'page' => 'contentplus-layouts',
						'context' => 'normal',
						'priority' => 'high',
						'tabs' => array(
							$i++ => array(
								'icon' => '<img src="'.CPLUS_PLUGIN_URL.'/libs/images/icons/general-65grey.png" width="16px"/>',
								'label' => __('General','contentplus'),
								'id' => $prefix . 'tab_layouts_general',
								'type' => 'tab',
							),
							$i++ => array(
								'icon' => '<img src="'.CPLUS_PLUGIN_URL.'/libs/images/icons/layout2-65grey.png" width="16px"/>',
								'label' => __('Layout','contentplus'),
								'id' => $prefix . 'tab_layouts_layout',
								'type' => 'tab',
							),
							$i++ => array(
								'icon' => '<img src="'.CPLUS_PLUGIN_URL.'/libs/images/icons/phone-65grey.png" width="16px"/>',
								'label' => __('Responsive','contentplus'),
								'id' => $prefix . 'tab_layouts_responsive',
								'type' => 'tab',
							),
							$i++ => array(
								'icon' => '<img src="'.CPLUS_PLUGIN_URL.'/libs/images/icons/images-65grey.png" width="16px"/>',
								'label' => __('Images','contentplus'),
								'id' => $prefix . 'tab_layouts_images',
								'type' => 'tab',
							),
						)
				);

				$i = 0;
				$contentplus_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(
					array(
	            'label' => __('Short Name ','contentplus'),
	            'id' => $prefix . 'layout-short-name',
	            'type' => 'text',
							'default' => '',
	            'desc' => __('Something about the cell responsiveness.','contentplus')
	        ),
					array('label' => __('Formatting','pzsp'),
	            'desc' => __('','contentplus'),
	            'id' => $prefix . 'cell_formatting',
	            'type' => 'heading',
	            'default' => '',
							),
					array(
	            'label' => __('Title bullet','contentplus'),
	            'id' => $prefix . 'layout-title-bullet',
	            'type' => 'text',
							'default' => '',
	            'desc' => __('Prefix titles with bullets.','contentplus')
	        ),
					array(
	            'label' => __('Link title','contentplus'),
	            'id' => $prefix . 'layout-link-title',
	            'type' => 'checkbox',
							'default' => true,
	            'desc' => __('Link titles to the post.','contentplus')
	        ),
					array(
	            'label' => __('Read more message','contentplus'),
	            'id' => $prefix . 'layout-read-more',
	            'type' => 'text',
							'default' => '[Read more]',
	            'desc' => __('Text to display for read more.','contentplus')
	        ),
					array(
	            'label' => __('Excerpt length','contentplus'),
	            'id' => $prefix . 'layout-read-more',
	            'type' => 'text',
							'default' => '[Read more]',
	            'desc' => __('Text to display for read more.','contentplus')
	        ),
	      );
				$contentplus_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(

					array('label' => __('Element widths','pzsp'),
	            'desc' => __('','contentplus'),
	            'id' => $prefix . 'cell_zone_widths',
	            'type' => 'heading',
	            'default' => '',
							),
						array(
	            'label' => __('Title width','contentplus'),
	            'id' => $prefix . 'layout-title-width',
	            'type' => 'percent',
	            'alt' => 'title',
							'default' => '100',
	            'desc' => __('Set the title width as a percentage of the cell width.','contentplus')
	        ),
					array(
	            'label' => __('Excerpt width','contentplus'),
	            'id' => $prefix . 'layout-excerpt-width',
	            'type' => 'percent',
	            'alt' => 'excerpt',
							'default' => '100',
	            'desc' => __('Set the excerpt width as a percentage of the cell width.','contentplus')
	        ),
					array(
	            'label' => __('Excerpt with image width','contentplus'),
	            'id' => $prefix . 'layout-excerptthumb-width',
	            'type' => 'percent',
	            'alt' => 'excerptthumb',
							'default' => '100',
	            'desc' => __('Set the excerpt with image width as a percentage of the cell width.','contentplus')
	        ),
					array(
	            'label' => __('Content width','contentplus'),
	            'id' => $prefix . 'layout-content-width',
	            'type' => 'percent',
	            'alt' => 'content',
							'default' => '100',
	            'desc' => __('Set the content width as a percentage of the cell width..','contentplus')
	        ),
					array(
	            'label' => __('Image width','contentplus'),
	            'id' => $prefix . 'layout-image-width',
	            'type' => 'percent',
	            'alt' => 'image',
							'default' => '100',
	            'desc' => __('Set the image width as a percentage of the cell width..','contentplus')
	        ),
					array(
	            'label' => __('Meta 1 width','contentplus'),
	            'id' => $prefix . 'layout-meta1-width',
	            'type' => 'percent',
	            'alt' => 'meta1',
							'default' => '49',
	            'desc' => __('Set the meta 1 width as a percentage of the cell width.','contentplus')
	        ),
					array(
	            'label' => __('Meta 2 width','contentplus'),
	            'id' => $prefix . 'layout-meta2-width',
	            'type' => 'percent',
	            'alt' => 'meta2',
							'default' => '49',
	            'desc' => __('Set the meta 2 width as a percentage of the cell width.','contentplus')
	        ),
					array(
	            'label' => __('Meta 3 width','contentplus'),
	            'id' => $prefix . 'layout-meta3-width',
	            'type' => 'percent',
	            'alt' => 'meta3',
							'default' => '33',
	            'desc' => __('Set the meta 3 width as a percentage of the cell width.','contentplus')
	        ),
					array(
	            'label' => __('Gutter width','contentplus'),
	            'id' => $prefix . 'layout-gutter-width',
	            'type' => 'numeric',
	            'alt' => 'gutter',
							'default' => '1',
	            'desc' => __('Set the gutter width as a percentage of the cell width. The gutter is the gap between adjoining elements','contentplus')
	        ),
					array(
	            'label' => __('Cell example preview','contentplus'),
	            'id' => $prefix . 'layout-layout',
	            'type' => 'custom',
							'default' => '%title% %meta1% %meta2% %excerpt% %excerptthumb% %content% %image%',
							'code' => cplus_draw_cell_layout(),
	            'help' => __('Drag and drop to sort the order of your elements. Use the sliders below to resize their widths as a pecentage of the cell. Heights are fluid, so not indicative.','contentplus'),
	             'desc' => __('','contentplus')
	        ),
			);
				$contentplus_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(
					array(
	            'label' => __('Upper ','contentplus'),
	            'id' => $prefix . 'layout-responsive-upper',
	            'type' => 'text',
							'default' => '',
	            'desc' => __('Something about the cell responsiveness.','contentplus')
	        ),
					array(
	            'label' => __('Lower','contentplus'),
	            'id' => $prefix . 'layout-responsive-lower',
	            'type' => 'text',
							'default' => '',
	            'desc' => __('Something about the cell responsiveness.','contentplus')
	        ),
					array(
	            'label' => __('Devices','contentplus'),
	            'id' => $prefix . 'layout-responsive-devices',
	            'type' => 'text',
							'default' => '',
	            'desc' => __('Something about the cell responsiveness.','contentplus')
	        ),
					array(
	            'label' => __('Resolution','contentplus'),
	            'id' => $prefix . 'layout-responsive-resolution',
	            'type' => 'text',
							'default' => '',
	            'desc' => __('Something about the cell responsiveness.','contentplus')
	        ),
	       );

				$contentplus_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(
					array(
	            'label' => __('Width','contentplus'),
	            'id' => $prefix . 'layout-image-width',
	            'type' => 'numeric',
							'default' => '50',
	            'desc' => __('','contentplus')
	        ),
	       );


	}	
	// add_action('admin_head','contentplus_layouts_add_help_tab');
	// function contentplus_layouts_add_help_tab () {
	//     $screen = get_current_screen();
	// 		$prefix = 'contentplus_';
	// 		switch ($screen->id) {
	// 			case 'edit-contentplus-layouts':
	// 				$screen->add_help_tab( array(
	// 						'id'	=> $prefix.'view_help_about',
	// 						'title'	=> __('About ContentPlus Layouts'),
	// 						'content'	=> '<h3>About</h3><p>' . __( 'ContentPlus layouts are used in the ContentPlus Block' ) . '</p>'
	// 				) );
	// 				$screen->add_help_tab( array(
	//             'title' => __('Support','contentplus'),
	//             'id' => $prefix . 'view_help_support',
	//             'content' => '<h3>Support</h3><p>'.__('Headway users can get support for ContentPlus on the <a href="http://support.headwaythemes.com/" target=_blank>Headway forums</a>').'</p>'
	//         )	);

					
	// 				break;

	// 			default:
	// 				return;
	// 				break;
	// 		}
	// }
	// Make this only load once - probably loads all the time at the moment

	function contentplus_layout_defaults() {
		global $contentplus_cpt_layouts_meta_boxes;
		$contentplus_layout_defaults = array();
		contentplus_populate_layout_options();
//pzdebug($contentplus_cpt_layouts_meta_boxes);
		foreach ($contentplus_cpt_layouts_meta_boxes['tabs'] as $contentplus_meta_box) {
			foreach ($contentplus_meta_box['fields'] as $contentplus_field) {
					if (!isset($contentplus_field['id'])) {
						$contentplus_layout_defaults[$contentplus_field['id']] = (isset($contentplus_field['default'])?$contentplus_field['default']:null);
					}
				}
			}
		return $contentplus_layout_defaults;
	}	

// Callback function to show fields in meta box
/**
 * [contentplus_show_box description]
 * @param  [type] $postobj              [description]
 * @param  [type] $pizazz_callback_args [description]
 * @return [type]                       [description]
 */
function contentplus_show_box($postobj,$pizazz_callback_args) {
		global  $post, $post_ID;
		$contentplus_is_new = (get_post_status()=='auto-draft');
		$pizazz_meta_boxes = $pizazz_callback_args['args'];
		// Use nonce for verification
		echo '<input type="hidden" name="pizazz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		echo '<div id="pzwp_'.$pizazz_meta_boxes['id'].'" class="pizazz-meta-boxes" >';
		
		echo '<ul id="pizazz-meta-nav" class="pizazz-meta-nav">';
		foreach ($pizazz_meta_boxes['tabs'] as $pizazz_meta_box_tab) {
			if (isset($pizazz_meta_box_tab['icon'])) {
					$pzwp_showhide_labels ='hide';
					$pzwp_label_icon = $pizazz_meta_box_tab['icon'];
			} else {
					$pzwp_showhide_labels ='show';
					$pzwp_label_icon = null;
			}
			echo '<li class="pizazz-meta-tab-title"><a href="#pizazz-form-table-'.str_replace(' ', '-', $pizazz_meta_box_tab['label']).'">'.$pzwp_label_icon.'<span class="pzwp_'.$pzwp_showhide_labels.'_labels"><div class="contentplus-arrow-left"></div>'.$pizazz_meta_box_tab['label'].'</span></a></li>';
		}
		echo '</ul>
		<div class="pzwp_the_tables" style="min-height:'.(count($pizazz_meta_boxes['tabs'])*52+10).'px">';
		foreach ($pizazz_meta_boxes['tabs'] as $pizazz_meta_box_tab) {
				echo '<table id="pizazz-form-table-'.str_replace(' ', '-', $pizazz_meta_box_tab['label']).'" class="form-table pizazz-form-table" "style="width:60%!important;background:#eff!important;">';
				foreach ($pizazz_meta_box_tab['fields'] as $field) {
						// get current post meta data
						$pizazz_value = get_post_meta($post->ID, $field['id'], true);




/////
// WORK ON THIS!!
// $pizazz_value = ($force_default && $pizazz_value === '')?$field['default']:$pizazz_value;
/////








						//	if $pizazz_value is null it chucks a warning in in_array as it wants an array
						echo '<tr id="pizazz-form-table-row-'.str_replace(' ', '-', $pizazz_meta_box_tab['label']).'-field-'.$field['id'].'" class="row-'.$field['id'].'">';
						if ($field['type'] != 'heading') {
							echo '<th><label class="title-'.$field['id'].'" for="', $field['id'], '">', $field['label'], '</label></th>';
						} else {
							echo '<th class="pz-field-heading"><h4 class="pz-field-heading">'.$field['label'].'</h4></th>';
						}
						if ($field['type'] != 'infobox' && $field['type'] != 'heading') {
							echo '<td class="pz-help"><span class="pz-help-button">?<span class="pz-help-text">'.$field['desc'].'</span></span></td>';
						} else {
							echo '<td class="pz-help"></td>';
						}
						echo '<td class="cell-'.$field['id'].'">';
						// This is simply to stop PHP debugging notice about missing index 'help' when help isn't specified
						$field['help'] = ((!array_key_exists('help',$field))?null:$field['help']);
						switch ($field['type']) {
								case 'heading':
										echo '';
										break;
								case 'infobox':
										echo '<span class="pzwp-infobox">'.$field['desc'].'</span>';
										break;
								case 'readonly':
										echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" readonly="readonly" value="'.$post_ID.'" />';
										break;
								case 'text':
										echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', (!$contentplus_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default'], '" size="30" style="width:97%" />', '<br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'percent':
										echo '<input alt="'.$field['alt'].'"" type="range" min=0 max=100 name="', $field['id'], '" id="', $field['id'], '" value="', (!$contentplus_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default'], '"style="width:80%" /><span class="cplus-range-percent percent-'.$field['id'].'">', $pizazz_value,'%</span><br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'colorpicker':
										echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', (!$contentplus_is_new && $pizazz_value) ? $pizazz_value : $field['default'], '" size="30" style="width:100px" />', '<span class="pzwp_colour_swatch pzwp_colour_'.$field['id'].'">&nbsp;</span><br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'numeric':
										echo '<input type="numeric" name="', $field['id'], '" id="', $field['id'], '" value="',(!$contentplus_is_new && ($pizazz_value || $pizazz_value === '0' || $pizazz_value === '')) ? $pizazz_value : $field['default'], '" size="30" style="width:100px" />', '<br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
									case 'textarea':
										echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', (!$contentplus_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default'], '</textarea>', '<br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
									case 'textarea-small':
										echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="2" style="width:97%">', (!$contentplus_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default'], '</textarea>', '<br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
									case 'textarea-large':
										echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="16" style="width:97%">', (!$contentplus_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default'], '</textarea>', '<br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'select':
										$pizazz_value = ($contentplus_is_new) ? $field['default'] : $pizazz_value;
										echo '<select  name="', $field['id'], '" id="', $field['id'], '">';
										foreach ($field['options'] as $option) {
											$pizazz_value = (!$pizazz_value) ? $field['default'] : $pizazz_value;
											echo '<option'.(($pizazz_value == $option['value']) ? ' selected="selected"' : '').' value="'.$option['value'].'">'.$option['text'].'</option>';
										}
										echo '</select>';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'ddslick-select':
										$pizazz_value = ($contentplus_is_new) ? $field['default'] : $pizazz_value;
										echo '<select  name="', $field['id'], '" id="', $field['id'], '" class="pzwp_ddslick">';
										foreach ($field['options'] as $option) {
											$pizazz_value = (!$pizazz_value) ? $field['default'] : $pizazz_value;
											echo '<option'.(($pizazz_value == $option['value']) ? ' selected="selected"' : '').' value="'.$option['value'].'">'.$option['text'].'</option>';
										}
										echo '</select>';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'multiselect':
										$pizazz_value = ($contentplus_is_new) ? $field['default'] : $pizazz_value;
										if (!$field['options']) {
											echo '<div id="', $field['id'], '">';
											echo '<span class="pzwp-infobox">No options available</span>';
											echo '</div>';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
										}
										// echo '<div id="', $field['id'], '" style="overflow-x:auto;max-height:100px;background:white;border:1px #eee solid">';
										echo '<div id="', $field['id'], '" style="background:white;border:1px #eee solid">';
										foreach ($field['options'] as $option) {
											if (substr($option['text'],0,1)=='>') {
												echo '<strong>&nbsp;=== '.substr($option['text'],1).' ==========</strong><br/>';
											} else {
												$pizazz_array_value = (is_string($pizazz_value))?array($pizazz_value):$pizazz_value;
												$pzwp_in_array = ($pizazz_value) ? (in_array($option['value'],$pizazz_array_value) || $pizazz_value == $option['value']) : false;
												echo '&nbsp;<input type="checkbox" name="'.$field['id'].'[]" id="'.$field['id'].'" value="'.$option['value'].'"'.(($pzwp_in_array) ? ' checked="checked"' : '').' />&nbsp;'.$option['text'].'<br/>';
											}
										}
										echo '</div>';
										echo '<span class="howto">'.$field['help'].'</span>';
									break;
						
								case 'radio':
										foreach ($field['options'] as $option) {
												$pizazz_value = ($contentplus_is_new) ? $field['default'] : $pizazz_value;
												echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $pizazz_value == $option['value'] ? ' checked="checked"' : '', ' />&nbsp;', $option['text'], '&nbsp;&nbsp;&nbsp;';
										}
										echo '<span class="howto">'.$field['help'].'</span>';
									break;
								case 'checkbox':
										$pizazz_value = ($contentplus_is_new)?$field['default']:$pizazz_value;
										echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $pizazz_value ? ' checked="checked"' : '', ' />';
										echo '<span class="howto">'.$field['help'].'</span>';
									break;
								case 'dropzone':
									 echo '<div id="cplus-dropzone-'.$field['id'].'" class="cplus-dropzone">';
									 echo '</div>';
										echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', (!$contentplus_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default'], '" size="30" style="width:97%" />', '<br />';
										//echo '<input type="text" name="cplus-cell-template-'.$field['id'].'" id="cplus-cell-template-'.$field['id'].'" size=44><br/>';
									echo '<span class="howto">'.$field['help'].'</span>';
									break;
								case 'dragsource':
										$pizazz_value = ($contentplus_is_new) ? $field['default'] : $pizazz_value;
										if (!$field['options']) {
											echo '<div id="element-', $field['id'], '">';
											echo '<span class="pzwp-infobox">No options available</span>';
											echo '</div>';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
										}
										echo '<div id="', $field['id'], '">';
										foreach ($field['options'] as $option) {
												echo '<div id="element-'.$field['id'].'" alt="'.$option['value'].'" class="cplus-dragsource dragsource-'.$field['id'].'"><span>'.$option['text'].'</span></div>';
											}
										echo '</div>';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'custom':
									 echo '<div id="cplus-custom-'.$field['id'].'" class="cplus-custom">';
									 echo $field['code'];
										echo '<input type="hidden" name="', $field['id'], '" id="', $field['id'], '" value="', (!$contentplus_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default'], '" style="display:none;" />', '<br />';
									 echo '</div>';
									echo '<span class="howto">'.$field['help'].'</span>';
									break;
						}
						echo '</div><td>',
								'</tr>';
				}
		 
				echo '</table>';
		}
	echo '</div>';
	echo '</div>';
}


/**
 * Save data from custom meta boxes
 * @param  [type] $post_id [Custom post type Post ID]
 * @return [type]          [no return value]
 */
function contentplus_save_data($post_id) {
	//var_dump($post_id,$_POST['post_type']);
	// Will need to manually add each case as new types created.
	if (!isset($_POST['post_type'])) {return false;}
	switch ($_POST['post_type']) {
		case 'contentplus-layouts' :
			global $contentplus_cpt_layouts_meta_boxes;
			$pizazz_meta_boxes = $contentplus_cpt_layouts_meta_boxes;
			break;
		default:
			return false;
	}
//pzdebug($pizazz_meta_boxes);
		// verify nonce
		if (!wp_verify_nonce($_POST['pizazz_meta_box_nonce'], basename(__FILE__))) {
				return $post_id;
		}
 
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post_id;
		}
 
		// check permissions
		if ('page' == $_POST['post_type']) {
				if (!current_user_can('edit_page', $post_id)) {
						return $post_id;
				}
		} elseif (!current_user_can('edit_post', $post_id)) {
				return $post_id;
		}
		foreach ($pizazz_meta_boxes['tabs'] as $contentplus_meta_box) {
			foreach ($contentplus_meta_box['fields'] as $field) {
					$old = get_post_meta($post_id, $field['id'], true);
					$new = (isset($_POST[$field['id']]))?$_POST[$field['id']]:null;
					if ($new != $old) {
							update_post_meta($post_id, $field['id'], $new);
					} elseif ('' == $new && $old) {
							delete_post_meta($post_id, $field['id'], $old);
					}
			}
	}
}
add_action('save_post', 'contentplus_save_data');
//add_action('post_updated', 'pizazz_save_data');

function cplus_draw_cell_layout() {
	$return_html = '';

	$return_html = '
			<div class="cplus_cell cplus-dropzone">
				    <div class="cplus_cell_header">
				        <div class="cplus_cell_header_left">
									<ol>
								  </ol>
							  </div>
				        <div class="cplus_cell_header_right">
									<ol>
								  </ol>
				        </div>
				    </div>
				    <div class="cplus_clearfloat"></div>
				    <div class="cplus_cell_columns">
				        <div class= "cplus_cell_left_col">
									<ol>
								  </ol>
				        </div>
				        <div class="cplus_cell_right_col">
									<ol>
								  </ol>
				        </div>
				    </div>
				    <div class="cplus_clearfloat"></div>
				    <div class="cplus_cell_footer">
				        <div class="cplus_cell_footer_left">
									<ol>
								  </ol>
				        </div>
				        <div class="cplus_cell_footer_right">
									<ol>
								  </ol>
				        </div>
				    </div>
				    <div class="cplus_clearfloat"></div>
			</div>
			<div class="cplus-cells-inputs">
				<input type="text" name="cplus_cell_header_left" size=44><br/>	
				<input type="text" name="cplus_cell_header_right" size=44><br/>
				<input type="text" name="cplus_cell_column_left" size=44><br/>
				<input type="text" name="cplus_cell_column_centre" size=44><br/>
				<input type="text" name="cplus_cell_column_right" size=44><br/>
				<input type="text" name="cplus_cell_footer_left" size=44><br/>
				<input type="text" name="cplus_cell_footer_right" size=44><br/>
			</div>
	';


	$return_html = '
		<div id="cplus-dropzone-contentplus_layout-layout" class="cplus-dropzone ui-droppable ui-sortable">
		</div>
		<div class="plugin_url" style="display:none;">'.CPLUS_PLUGIN_URL.'</div>';
	


	return $return_html;

}


