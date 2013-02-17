<?php

/* Create the layouts custom post type 
*/
add_action('init', 'contentplus_create_layouts_post_type');

if (is_admin()) {
	add_action('admin_init', 'contentplus_layouts_meta');
	add_action('admin_head', 'contentplus_admin_head');
}

add_action('admin_enqueue_scripts','contentplus_admin_enqueue');
function contentplus_admin_enqueue() {
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-button');

	
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
				$contentplus_cpt_layouts_meta_boxes = array(
						'id' => 'contentplus-layouts-id',
						'title' => 'Layouts',
						'page' => 'contentplus-layouts',
						'context' => 'normal',
						'priority' => 'high',
						'tabs' => array(
							0 => array(
								'icon' => '<img src="'.CPLUS_PLUGIN_URL.'/libs/images/icons/general-65grey.png" width="16px"/>',
								'label' => __('General','contentplus'),
								'id' => $prefix . 'tab_layouts_general',
								'type' => 'tab',
							),
							1 => array(
								'icon' => '<img src="'.CPLUS_PLUGIN_URL.'/libs/images/icons/phone-65grey.png" width="16px"/>',
								'label' => __('Responsive','contentplus'),
								'id' => $prefix . 'tab_layouts_responsive',
								'type' => 'tab',
							),
							2 => array(
								'icon' => '<img src="'.CPLUS_PLUGIN_URL.'/libs/images/icons/layout2-65grey.png" width="16px"/>',
								'label' => __('Header','contentplus'),
								'id' => $prefix . 'tab_layouts_header',
								'type' => 'tab',
							),
							3 => array(
								'icon' => '<img src="'.CPLUS_PLUGIN_URL.'/libs/images/icons/layout1-65grey.png" width="16px"/>',
								'label' => __('Columns','contentplus'),
								'id' => $prefix . 'tab_layouts_columns',
								'type' => 'tab',
							),
							4 => array(
								'icon' => '<img src="'.CPLUS_PLUGIN_URL.'/libs/images/icons/general-65grey.png" width="16px"/>',
								'label' => __('Footer','contentplus'),
								'id' => $prefix . 'tab_layouts_footer',
								'type' => 'tab',
							)
						)
				);
				$contentplus_cpt_layouts_meta_boxes['tabs'][0]['fields'] = array(
					array(
	            'label' => __('Short Name ','contentplus'),
	            'id' => $prefix . 'layout-short-name',
	            'type' => 'text',
							'default' => '',
	            'desc' => __('Something about the cell responsiveness.','contentplus')
	        ),
	      );
				$contentplus_cpt_layouts_meta_boxes['tabs'][1]['fields'] = array(
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
				$contentplus_cpt_layouts_meta_boxes['tabs'][2]['fields'] = array(
					array(
	            'label' => __('Header left','contentplus'),
	            'id' => $prefix . 'layout-header-left',
	            'type' => 'multiselect',
	            'options' => array(
	                array('value' => 'title', 'text'=> __('Title','contentplus')),
	                array('value' => 'content', 'text'=> __('Content','contentplus')),
	                array('value' => 'excerpt', 'text'=> __('Excerpt','contentplus')),
	                array('value' => 'image', 'text'=> __('Image','contentplus')),
	                array('value' => 'meta1', 'text'=> __('Meta 1','contentplus')),
	                array('value' => 'meta2', 'text'=> __('Meta 2','contentplus')),
	                array('value' => 'meta3', 'text'=> __('Meta 3','contentplus')),
	            ),
							'default' => null,
	            'desc' => __('Choose what to show in this part of the cell.','contentplus')
	        ),
					array(
	            'label' => __('Header right','contentplus'),
	            'id' => $prefix . 'layout-header-right',
	            'type' => 'multiselect',
	            'options' => array(
	                array('value' => 'title', 'text'=> __('Title','contentplus')),
	                array('value' => 'content', 'text'=> __('Content','contentplus')),
	                array('value' => 'excerpt', 'text'=> __('Excerpt','contentplus')),
	                array('value' => 'image', 'text'=> __('Image','contentplus')),
	                array('value' => 'meta1', 'text'=> __('Meta 1','contentplus')),
	                array('value' => 'meta2', 'text'=> __('Meta 2','contentplus')),
	                array('value' => 'meta3', 'text'=> __('Meta 3','contentplus')),
	            ),
							'default' => null,
	            'desc' => __('Choose what to show in this part of the cell.','contentplus')
	        ),
					array(
	            'label' => __('Header left width (%)','contentplus'),
	            'id' => $prefix . 'layout-header-left-width',
	            'type' => 'numeric',
							'default' => '50',
	            'desc' => __('Set the header left width as a percentage of the cell width. The two header widths must total no more than 100%.','contentplus')
	        ),
					array(
	            'label' => __('Header right width (%)','contentplus'),
	            'id' => $prefix . 'layout-header-right-width',
	            'type' => 'numeric',
							'default' => '50',
	            'desc' => __('Set the header right width as a percentage of the cell width. The two header widths must total no more than 100%.','contentplus')
	        ),
				);
				$contentplus_cpt_layouts_meta_boxes['tabs'][3]['fields'] = array(
					array(
	            'label' => __('Column left','contentplus'),
	            'id' => $prefix . 'layout-column-left',
	            'type' => 'multiselect',
	            'options' => array(
	                array('value' => 'title', 'text'=> __('Title','contentplus')),
	                array('value' => 'content', 'text'=> __('Content','contentplus')),
	                array('value' => 'excerpt', 'text'=> __('Excerpt','contentplus')),
	                array('value' => 'image', 'text'=> __('Image','contentplus')),
	                array('value' => 'meta1', 'text'=> __('Meta 1','contentplus')),
	                array('value' => 'meta2', 'text'=> __('Meta 2','contentplus')),
	                array('value' => 'meta3', 'text'=> __('Meta 3','contentplus')),
	            ),
							'default' => null,
	            'desc' => __('Choose what to show in this part of the cell.','contentplus')
	        ),
					array(
	            'label' => __('Column centre','contentplus'),
	            'id' => $prefix . 'layout-column-centre',
	            'type' => 'multiselect',
	            'options' => array(
	                array('value' => 'title', 'text'=> __('Title','contentplus')),
	                array('value' => 'content', 'text'=> __('Content','contentplus')),
	                array('value' => 'excerpt', 'text'=> __('Excerpt','contentplus')),
	                array('value' => 'image', 'text'=> __('Image','contentplus')),
	                array('value' => 'meta1', 'text'=> __('Meta 1','contentplus')),
	                array('value' => 'meta2', 'text'=> __('Meta 2','contentplus')),
	                array('value' => 'meta3', 'text'=> __('Meta 3','contentplus')),
	            ),
							'default' => null,
	            'desc' => __('Choose what to show in this part of the cell.','contentplus')
	        ),
					array(
	            'label' => __('Column right','contentplus'),
	            'id' => $prefix . 'layout-column-right',
	            'type' => 'multiselect',
	            'options' => array(
	                array('value' => 'title', 'text'=> __('Title','contentplus')),
	                array('value' => 'content', 'text'=> __('Content','contentplus')),
	                array('value' => 'excerpt', 'text'=> __('Excerpt','contentplus')),
	                array('value' => 'image', 'text'=> __('Image','contentplus')),
	                array('value' => 'meta1', 'text'=> __('Meta 1','contentplus')),
	                array('value' => 'meta2', 'text'=> __('Meta 2','contentplus')),
	                array('value' => 'meta3', 'text'=> __('Meta 3','contentplus')),
	            ),
							'default' => null,
	            'desc' => __('Choose what to show in this part of the cell.','contentplus')
	        ),
					array(
	            'label' => __('Column left width (%)','contentplus'),
	            'id' => $prefix . 'layout-column-left-width',
	            'type' => 'numeric',
							'default' => '33.33',
	            'desc' => __('Set the column left width as a percentage of the cell width. The three column widths must total no more than 100%.','contentplus')
	        ),
					array(
	            'label' => __('Column centre width (%)','contentplus'),
	            'id' => $prefix . 'layout-column-centre-width',
	            'type' => 'numeric',
							'default' => '33.33',
	            'desc' => __('Set the column centre width as a percentage of the cell width. The three column widths must total no more than 100%.','contentplus')
	        ),
					array(
	            'label' => __('Column right width (%)','contentplus'),
	            'id' => $prefix . 'layout-column-right-width',
	            'type' => 'numeric',
							'default' => '33.33',
	            'desc' => __('Set the column right width as a percentage of the cell width. The three column widths must total no more than 100%.','contentplus')
	        ),
				);
				$contentplus_cpt_layouts_meta_boxes['tabs'][4]['fields'] = array(
					array(
	            'label' => __('Footer left','contentplus'),
	            'id' => $prefix . 'layout-footer-left',
	            'type' => 'multiselect',
	            'options' => array(
	                array('value' => 'title', 'text'=> __('Title','contentplus')),
	                array('value' => 'content', 'text'=> __('Content','contentplus')),
	                array('value' => 'excerpt', 'text'=> __('Excerpt','contentplus')),
	                array('value' => 'image', 'text'=> __('Image','contentplus')),
	                array('value' => 'meta1', 'text'=> __('Meta 1','contentplus')),
	                array('value' => 'meta2', 'text'=> __('Meta 2','contentplus')),
	                array('value' => 'meta3', 'text'=> __('Meta 3','contentplus')),
	            ),
							'default' => null,
	            'desc' => __('Choose what to show in this part of the cell.','contentplus')
	        ),
					array(
	            'label' => __('Footer right','contentplus'),
	            'id' => $prefix . 'layout-footer-right',
	            'type' => 'multiselect',
	            'options' => array(
	                array('value' => 'title', 'text'=> __('Title','contentplus')),
	                array('value' => 'content', 'text'=> __('Content','contentplus')),
	                array('value' => 'excerpt', 'text'=> __('Excerpt','contentplus')),
	                array('value' => 'image', 'text'=> __('Image','contentplus')),
	                array('value' => 'meta1', 'text'=> __('Meta 1','contentplus')),
	                array('value' => 'meta2', 'text'=> __('Meta 2','contentplus')),
	                array('value' => 'meta3', 'text'=> __('Meta 3','contentplus')),
	            ),
							'default' => null,
	            'desc' => __('Choose what to show in this part of the cell.','contentplus')
	        ),
					array(
	            'label' => __('Footer left width (%)','contentplus'),
	            'id' => $prefix . 'layout-footer-left-width',
	            'type' => 'numeric',
							'default' => '50',
	            'desc' => __('Set the footer left width as a percentage of the cell width. The two footer widths must total no more than 100%.','contentplus')
	        ),
					array(
	            'label' => __('Footer right width (%)','contentplus'),
	            'id' => $prefix . 'layout-footer-right-width',
	            'type' => 'numeric',
							'default' => '50',
	            'desc' => __('Set the footer right width as a percentage of the cell width. The two footer widths must total no more than 100%.','contentplus')
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
