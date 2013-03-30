<?php

/* 
* Create the layouts custom post type 
*/
add_action('init', 'pzucd_create_layouts_post_type');

if (is_admin()) {
//	add_action('admin_init', 'pzucd_preview_meta');
	add_action('add_meta_boxes', 'pzucd_layouts_meta');
	add_action('admin_head', 'pzucd_admin_head');
	add_action('admin_enqueue_scripts','pzucd_admin_enqueue');
	add_filter('manage_ucd-layouts_posts_columns','pzucd_add_columns');
	add_action('manage_ucd-layouts_posts_custom_column','pzucd_add_column_content',10,2);
}

function pzucd_admin_enqueue($hook) {
	$screen = get_current_screen();
    if ( 'ucd-layouts' == $screen->id ) {

		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-button');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-droppable');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-resizable');

		wp_enqueue_style('pzucd-block-css', PZUCD_PLUGIN_URL.'/css/ucd-back.css');
		wp_enqueue_style('pzucd-jqueryui-css', PZUCD_PLUGIN_URL.'/css/smoothness/jquery-ui-1.10.1.custom.min.css');

		wp_enqueue_script('jquery-pzucd-metaboxes',PZUCD_PLUGIN_URL.'/js/ucd_metaboxes.js',array('jquery'));

    }
	
}
function pzucd_admin_head() {
}


function pzucd_add_columns($columns) {
	unset($columns['thumbnail']);
	$pzucd_front = array_slice($columns, 0,2);
	$pzucd_back = array_slice($columns, 2);
	$pzucd_insert = 
			array(
				'pzucd_set_name' => __('Set name','pzsp'),
				'pzucd_short_name' => __('Layout short name','pzsp'),
			);
  
	return array_merge($pzucd_front,$pzucd_insert,$pzucd_back);
}

function pzucd_add_column_content($column, $post_id) {
	switch ($column) {
		case 'pzucd_short_name':
			echo get_post_meta($post_id,'pzucd_short-name',true);
			break;
		case 'pzucd_set_name':
			echo get_post_meta($post_id,'pzucd_set-name',true);
			break;
	}
}



	function pzucd_create_layouts_post_type() 
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
	    'menu_name' => _x( 'UCD Cell Layouts', 'ucd-layouts' ),
		  );

		  $args = array(
			'labels' => $labels,
			'description' => __('Ultimate Content Display cell layouts are used to create reusable cell layouts for use in your UCD blocks, widgets, shortcodes and template tags'),
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
			'register_meta_box_cb' => 'pzucd_layouts_meta'
		  ); 

		  register_post_type('ucd-layouts',$args);
			
		}

			function pzucd_preview_meta() {
// this isn't used at the moment coz moved preview back into other meta box
		$pzucd_preview = '
		<div id="pzucd-custom-pzucd_layout-layout" class="pzucd-custom">
			<div id="pzucd-dropzone-pzucd_layout-layout" class="pzucd-dropzone ui-droppable ui-sortable">
				
				</div>

				<div class="plugin_url" style="display:none;">http://localhost/wp-mba.dev/wp-content/plugins/headway-ultimatecontentdisplay</div>

				<span class="howto">Drag and drop to sort the order of your elements.<br>Heights are fluid in cells, so not indicative of how it will look on the page.<br>
	            	<strong>This is an example only and thus only a general guide to how the cells will look.</strong>
	      </span>
	      </div>		';

		// Fill any defaults if necessary
			$pzucd_preview_meta_box = array(
						'id' => 'pzucd-preview-id',
						'title' => 'Cell Preview',
						'page' => 'ucd-layouts',
						'context' => 'side',
						'priority' => 'high',
						'content' => $pzucd_preview
				);

			add_meta_box(
				$pzucd_preview_meta_box['id'], 
				$pzucd_preview_meta_box['title'], 
				'pzucd_show_preview_box', 
				$pzucd_preview_meta_box['page'], 
				$pzucd_preview_meta_box['context'], 
				$pzucd_preview_meta_box['priority'],
				$pzucd_preview_meta_box
		);

	} // End preview_meta

function pzucd_show_preview_box($postobj,$pizazz_callback_args) {
		global  $post, $post_ID;
		$pzucd_is_new = (get_post_status()=='auto-draft');
		$pizazz_meta_boxes = $pizazz_callback_args['args'];
		echo $pizazz_meta_boxes['content'];
}

	function pzucd_layouts_meta() {
		global $pzucd_cpt_layouts_meta_boxes;
				
		// Fill any defaults if necessary
		$pzucd_cpt_layouts_meta_boxes = pzucd_layout_defaults();
//pzdebug($pzucd_cpt_layouts_meta_boxes);

		pzucd_populate_layout_options();

			add_meta_box($pzucd_cpt_layouts_meta_boxes['id'], 
			$pzucd_cpt_layouts_meta_boxes['title'], 
			'pzucd_show_box', 
			$pzucd_cpt_layouts_meta_boxes['page'], 
			$pzucd_cpt_layouts_meta_boxes['context'], 
			$pzucd_cpt_layouts_meta_boxes['priority'],
				$pzucd_cpt_layouts_meta_boxes
		);

	} // End layouts_meta
		
		// cpt = custom post type
	function pzucd_populate_layout_options() {
			global $pzucd_cpt_layouts_meta_boxes;

				$prefix = 'pzucd_';
				/*
				 *
				 * Setup Layouts extra fields
				 *
				 */
				$i = 0;
				$pzucd_cpt_layouts_meta_boxes = array(
						'id' => 'pzucd-layouts-id',
						'title' => 'Cell Designer',
						'page' => 'ucd-layouts',
						'context' => 'normal',
						'priority' => 'high',
						'tabs' => array(
							$i++ => array(
								'icon' => '<img src="'.esc_url(PZUCD_PLUGIN_URL.'/libs/images/icons/phone-65grey.png').'" width="16px"/>',
								'label' => __('Responsive','pzucd'),
								'id' => $prefix . 'tab_layouts_responsive',
								'type' => 'tab',
							),
							$i++ => array(
								'icon' => '<img src="'.esc_url(PZUCD_PLUGIN_URL.'/libs/images/icons/layout2-65grey.png').'" width="16px"/>',
								'label' => __('Layout','pzucd'),
								'id' => $prefix . 'tab_layouts_layout',
								'type' => 'tab',
							),
							$i++ => array(
								'icon' => '<img src="'.esc_url(PZUCD_PLUGIN_URL.'/libs/images/icons/text2-65grey.png').'" width="16px"/>',
								'label' => __('Title','pzucd'),
								'id' => $prefix . 'tab_layouts_title',
								'type' => 'tab',
							),
							$i++ => array(
								'icon' => '<img src="'.esc_url(PZUCD_PLUGIN_URL.'/libs/images/icons/text1-65grey.png').'" width="16px"/>',
								'label' => __('Content','pzucd'),
								'id' => $prefix . 'tab_layouts_content',
								'type' => 'tab',
							),
							$i++ => array(
								'icon' => '<img src="'.esc_url(PZUCD_PLUGIN_URL.'/libs/images/icons/layout1-65grey.png').'" width="16px"/>',
								'label' => __('Excerpt','pzucd'),
								'id' => $prefix . 'tab_layouts_excerpt',
								'type' => 'tab',
							),
							$i++ => array(
								'icon' => '<img src="'.esc_url(PZUCD_PLUGIN_URL.'/libs/images/icons/help2-65grey.png').'" width="16px"/>',
								'label' => __('Meta','pzucd'),
								'id' => $prefix . 'tab_layouts_meta',
								'type' => 'tab',
							),
							$i++ => array(
								'icon' => '<img src="'.esc_url(PZUCD_PLUGIN_URL.'/libs/images/icons/images-65grey.png').'" width="16px"/>',
								'label' => __('Image','pzucd'),
								'id' => $prefix . 'tab_layouts_images',
								'type' => 'tab',
							),
							$i++ => array(
								'icon' => '<img src="'.esc_url(PZUCD_PLUGIN_URL.'/libs/images/icons/braces-65grey.png').'" width="16px"/>',
								'label' => __('Format','pzucd'),
								'id' => $prefix . 'tab_layouts_format',
								'type' => 'tab',
							),
							
						)
				);

		$i = 0;

		/****************
		* RESPONSIVE
		*****************/
		$pzucd_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(
			array(
	            'label' => __('Responsive','pzucd'),
	            'id' => $prefix . 'layout-layout-responsive',
	            'type' => 'heading',
				'default' => '',
	            'desc' => __('','pzucd')
	        ),
			array(
	            'label' => __('Set name','pzucd'),
	            'id' => $prefix . 'layout-set-name',
	            'type' => 'text',
				'default' => '',
	            'desc' => __('Something about the cell responsiveness.','pzucd'),
	            'help' => __('Create sets of layouts with each layout in a set for different screen dimensions')
	        ),
			array(
	            'label' => __('Layout short name ','pzucd'),
	            'id' => $prefix . 'layout-short-name',
	            'type' => 'text',
				'default' => '',
	            'desc' => __('Something about the cell responsiveness.','pzucd')
	        ),
			array(
	            'label' => __('Display','pzucd'),
	            'id' => $prefix . 'layout-display',
	            'type' => 'text',
				'default' => '',
	            'desc' => __('Generic label to help you remember the primary device you expect this layout to show on.','pzucd'),
	        ),
			array(
	            'label' => __('Minimum display width (px)','pzucd'),
	            'id' => $prefix . 'layout-min-display-width',
	            'type' => 'text',
				'default' => '0',
	            'desc' => __('Something about the cell responsiveness.','pzucd'),
	        ),
			array(
	            'label' => __('Maximum display width (px)','pzucd'),
	            'id' => $prefix . 'layout-max-display-width',
	            'type' => 'text',
				'default' => '320',
	            'desc' => __('Something about the cell responsiveness.','pzucd'),
	        ),
			array(
	            'label' => __('Devices','pzucd'),
	            'id' => $prefix . 'layout-responsive-devices',
	            'type' => 'text',
							'default' => '',
	            'desc' => __('Something about the cell responsiveness.','pzucd')
	        ),
					array(
	            'label' => __('Resolution','pzucd'),
	            'id' => $prefix . 'layout-responsive-resolution',
	            'type' => 'text',
							'default' => '',
	            'desc' => __('Something about the cell responsiveness.','pzucd')
	        ),
		);

		/****************
		* LAYOUT
		****************/
		$pzucd_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(
			array(
	            'label' => __('Layout','pzucd'),
	            'id' => $prefix . 'layout-layout-head',
	            'type' => 'heading',
							'default' => '',
	            'desc' => __('','pzucd')
	        ),
			array(
	            'label' => __('Components to show','pzucd'),
	            'id' => $prefix . 'layout-show',
	            'type' => 'multicheck',
				'default' => array('title','excerpt','meta1'),
				'options' => array(
					array('value'=>'title','text'=>'Title <span class="title"></span>  '),
					array('value'=>'excerpt','text'=>'Excerpt <span class="excerpt"></span>  '),
					array('value'=>'content','text'=>'Content <span class="content"></span>  '),
					array('value'=>'image','text'=>'Image <span class="image"></span>  '),
					array('value'=>'meta1','text'=>'Meta1 <span class="meta1"></span>  '),
					array('value'=>'meta2','text'=>'Meta2 <span class="meta2"></span>  '),
					array('value'=>'meta3','text'=>'Meta3 <span class="meta3"></span>'),
				),
	            'desc' => __('Select which base components to include in this cell layout.','pzucd')
	        ),
			array(
	            'label' => __('Components area position','pzucd'),
	            'id' => $prefix . 'layout-sections-position',
	            'type' => 'select',
				'default' => 'top',
				'options' => array(
					array('value' => 'top', 'text'=>'Top of cell'),
					array('value' => 'bottom', 'text'=>'Bottom of cell'),
					array('value' => 'left', 'text'=>'Left of cell'),
					array('value' => 'right', 'text'=>'Right of cell'),
					),
	            'desc' => __('Position for all the components as a group','pzucd')
	        ),
			array(
	            'label' => __('Components area width (%)','pzucd'),
	            'id' => $prefix . 'layout-sections-widths',
	            'type' => 'text',
				'default' => '100',
				'alt' => 'zones',
				'min' => '1',
				'max' => '100',
				'step' => '1',
	            'desc' => __('Set the overall width for the components area. Necessary for left or right positioning of sections','pzucd'),
	            'help' => __('Note:The sum of the width and the left/right nudge should equal 100','pzucd')
	        ),
			array(
	            'label' => __('Nudge components area x,y (%)','pzucd'),
	            'id' => $prefix . 'layout-nudge-sections',
	            'type' => 'text',
				'default' => '0,0',
	            'desc' => __('Enter x,y co-ordinates to move the components area. e.g. 75,75. Note: These measurements are percenatge of the cell.','pzucd')
	        ),
			array(
	            'label' => __('Background Image','pzucd'),
	            'id' => $prefix . 'layout-background-image',
	            'type' => 'select',
				'default' => 'none',
				'options' =>  array(
					array('value' => 'none', 'text'=>'No image'),
					array('value' => 'fill', 'text'=>'Fill the cell'),
					array('value' => 'align', 'text'=>'Align with components area'),
				),
	            'desc' => __('Select how to display the featured image in the background.','pzucd')
	        ),

			array(
	            'label' => __('Cell layout preview','pzucd'),
	            'id' => $prefix . 'layout-cell-preview',
	            'type' => 'custom',
	            'code' => pzucd_draw_cell_layout(),
				'default' => '',
	            'desc' => __('','pzucd')
          ),

			array(
	            'label' => __('Cell field order','pzucd'),
	            'id' => $prefix . 'layout-field-order',
	            'type' => 'hidden',
				'default' => '%title%,%meta1%,%meta2%,%excerpt%,%content%,%image%,%meta3%',
            	'desc' => __('','pzucd')
	        ),
		);
	
		/****************
		* TITLES
		****************/
				$pzucd_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(
				array(
	            'label' => __('Titles','pzucd'),
	            'id' => $prefix . 'layout-title-head',
	            'type' => 'heading',
				'default' => '',
	            'desc' => __('','pzucd')
	        ),
					array(
	            'label' => __('Title area width','pzucd'),
	            'id' => $prefix . 'layout-title-width',
	            'type' => 'readonly',
	            'class' => 'pzucd-zone-percent',
	            'alt' => 'title',
				'default' => '100',
	            'desc' => __('Set the title width as a percentage of the cell width.','pzucd')
	        ),
			array(
	            'label' => __('Title bullet','pzucd'),
	            'id' => $prefix . 'layout-title-bullet',
	            'type' => 'text',
				'default' => '',
	            'desc' => __('Prefix titles with bullets.','pzucd')
	        ),
					array(
	            'label' => __('Link title','pzucd'),
	            'id' => $prefix . 'layout-link-title',
	            'type' => 'checkbox',
					'default' => true,
	            'desc' => __('Link titles to the post.','pzucd')
	        ),
	       );

		/****************
		* CONTENT
		****************/
				$pzucd_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(
				array(
	            'label' => __('Content','pzucd'),
	            'id' => $prefix . 'layout-content-head',
	            'type' => 'heading',
							'default' => '',
	            'desc' => __('','pzucd')
	        ),
						array(
	            'label' => __('Content area width','pzucd'),
	            'id' => $prefix . 'layout-content-width',
	            'type' => 'readonly',
	            'class' => 'pzucd-zone-percent',
	            'alt' => 'content',
							'default' => '100',
	            'desc' => __('Set the content width as a percentage of the cell width.','pzucd')
	        ),
						array(
	            'label' => __('Content CSS selectors','pzucd'),
	            'id' => $prefix . 'layout-content-css-selectors',
	            'type' => 'infobox',
							'default' => '',
	            'desc' => __('CSS selectors used in the content area:<ul>
	            	<li>.pzucd-entry-content</li>
	            	<li>.pzucd-entry-content a</li>
	            	<li>.pzucd-entry-content img</li>
	            	</ul>','pzucd')
	        ),

		       );

		/****************
		* EXCERPTS
		****************/
				$pzucd_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(
				array(
	            'label' => __('Excerpts','pzucd'),
	            'id' => $prefix . 'layout-excerpts-head',
	            'type' => 'heading',
							'default' => '',
	            'desc' => __('','pzucd')
	        ),
						array(
	            'label' => __('Excerpt area width','pzucd'),
	            'id' => $prefix . 'layout-excerpt-width',
	            'type' => 'readonly',
	            'class' => 'pzucd-zone-percent',
	            'alt' => 'excerpt',
							'default' => '100',
	            'desc' => __('Set the excerpt width as a percentage of the cell width.','pzucd')
	        ),
					array(
	            'label' => __('Excerpt image','pzucd'),
	            'id' => $prefix . 'layout-excerpt-thumb',
	            'type' => 'select',
							'default' => 'none',
							'options' => array(
								array('value' => 'none', 'text'=>'No image'),
								array('value' => 'left', 'text'=>'Image left'),
								array('value' => 'right', 'text'=>'Image right'),
								),
	            'desc' => __('Set the alignment of the image when it is in the excerpt. This will use the image settings','pzucd')
	        ),
				array(
	            'label' => __('Read more message','pzucd'),
	            'id' => $prefix . 'layout-read-more',
	            'type' => 'text',
							'default' => '[Read more]',
	            'desc' => __('Text to display for read more.','pzucd')
	        ),
					array(
	            'label' => __('Excerpt length','pzucd'),
	            'id' => $prefix . 'layout-excerpt-length',
	            'type' => 'text',
							'default' => '100',
	            'desc' => __('Text to display for read more.','pzucd')
	        ),
					array(
	            'label' => __('Excerpt length type','pzucd'),
	            'id' => $prefix . 'layout-excerpt-length-type',
	            'type' => 'select',
							'default' => 'characters',
							'options' => array(
								array('value' => 'characters', 'text'=>'Characters'),
								array('value' => 'words', 'text'=>'Words'),
 							),

	            'desc' => __('Text to display for read more.','pzucd')
	        ),
	       );
		/****************
		* META
		****************/
				$pzucd_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(
				array(
	            'label' => __('Meta','pzucd'),
	            'id' => $prefix . 'layout-meta-head',
	            'type' => 'heading',
							'default' => '',
	            'desc' => __('','pzucd')
	        ),
						array(
	            'label' => __('Meta 1 area width','pzucd'),
	            'id' => $prefix . 'layout-meta1-width',
	            'type' => 'readonly',
	            'class' => 'pzucd-zone-percent',
	            'alt' => 'meta1',
							'default' => '100',
	            'desc' => __('Set the meta 1 width as a percentage of the cell width.','pzucd')
	        ),
					array(
	            'label' => __('Meta 2 area width','pzucd'),
	            'id' => $prefix . 'layout-meta2-width',
	            'type' => 'readonly',
	            'class' => 'pzucd-zone-percent',
	            'alt' => 'meta2',
							'default' => '100',
	            'desc' => __('Set the meta 2 width as a percentage of the cell width.','pzucd')
	        ),
					array(
	            'label' => __('Meta 3 area width','pzucd'),
	            'id' => $prefix . 'layout-meta3-width',
	            'type' => 'readonly',
	            'class' => 'pzucd-zone-percent',
	            'alt' => 'meta3',
							'default' => '100',
	            'desc' => __('Set the meta 3 width as a percentage of the cell width.','pzucd')
	        ),
		       );

		/****************
		* IMAGE
		****************/
				$pzucd_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(
				array(
	            'label' => __('Image','pzucd'),
	            'id' => $prefix . 'layout-image-head',
	            'type' => 'heading',
							'default' => '',
	            'desc' => __('','pzucd')
	        ),
					array(
	            'label' => __('Image area width','pzucd'),
	            'id' => $prefix . 'layout-image-width',
	            'type' => 'readonly',
	            'class' => 'pzucd-zone-percent',
	            'alt' => 'image',
							'default' => '100',
	            'desc' => __('Set the image width as a percentage of the cell width.','pzucd')
	        ),
       );

		/****************
		* FORMAT
		****************/

			$pzucd_cpt_layouts_meta_boxes['tabs'][$i++]['fields'] = array(
			array(
	            'label' => __('Gutter width (%)','pzucd'),
	            'id' => $prefix . 'layout-gutter-width',
	            'type' => 'numeric',
	            'alt' => 'gutter',
							'default' => '1',
							'min' => '1',
							'max' => '100',
							'step' => '1',
	            'desc' => __('Set the gutter width as a percentage of the cell width. The gutter is the gap between adjoining elements','pzucd')
	        ),
			array(
				'label' => __('CSS instructions','pzucd'),
	            'id' => $prefix . 'layout-format-instructions',
	            'type' => 'infobox',
				'default' => '',
	            'desc' => __('Enter CSS declarations, such as: background:#123; color:#abc; font-size:1.6em; padding:1%;','pzucd').'<br/>'.__('As much as possible, use fluid units (%,em) if you want to ensure maximum responsiveness.','pzucd').'<br/>'.
	            __('The base font size is 10px. So, for example, to get a font size of 14px, use 1.4em. Even better is using relative ems i.e. rem.')
				),
			array(
	            'label' => __('Cells','pzucd'),
	            'id' => $prefix . 'layout-format-cells',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: .pzucd_cells',
	            'desc' => __('Format the cells','pzucd')
	        ),
			array(
	            'label' => __('Components group','pzucd'),
	            'id' => $prefix . 'layout-format-components-group',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: .pzucd_components',
	            'desc' => __('Format the components group','pzucd')
	        ),
			array(
	            'label' => __('Entry title','pzucd'),
	            'id' => $prefix . 'layout-format-entry-title',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: .pzucd_entry_title and .pzucd_entry_title a',
	            'desc' => __('Format the entry title','pzucd')
	        ),
			array(
	            'label' => __('Entry title hover','pzucd'),
	            'id' => $prefix . 'layout-format-entry-title-hover',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: .pzucd_entry_title a:hover',
	            'desc' => __('Format the entry title link hover','pzucd')
	        ),
			array(
	            'label' => __('Entry meta','pzucd'),
	            'id' => $prefix . 'layout-format-entry-meta',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: .pzucd_entry_meta',
	            'desc' => __('Format the entry meta','pzucd')
	        ),
			array(
	            'label' => __('Entry meta links','pzucd'),
	            'id' => $prefix . 'layout-format-entry-meta-link',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: .pzucd_entry_meta a',
	            'desc' => __('Format the entry meta link','pzucd')
	        ),
			array(
	            'label' => __('Entry meta link hover','pzucd'),
	            'id' => $prefix . 'layout-format-entry-meta-link-hover',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: .pzucd_entry_meta a:hover',
	            'desc' => __('Format the entry meta link hover','pzucd')
	        ),
			array(
	            'label' => __('Entry content','pzucd'),
	            'id' => $prefix . 'layout-format-entry-content',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: .pzucd_entry_content',
	            'desc' => __('Format the entry content','pzucd')
	        ),
			array(
	            'label' => __('Entry content links','pzucd'),
	            'id' => $prefix . 'layout-format-entry-content-links',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: .pzucd_entry_content a',
	            'desc' => __('Format the entry content','pzucd')
	        ),
			array(
	            'label' => __('Entry content link hover','pzucd'),
	            'id' => $prefix . 'layout-format-entry-content-link-hover',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: .pzucd_entry_content a:hover',
	            'desc' => __('Format the entry content link hover','pzucd')
	        ),
			array(
	            'label' => __('Entry featured image','pzucd'),
	            'id' => $prefix . 'layout-format-entry-fimage',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: .pzucd_entry_featured_image',
	            'desc' => __('Format the entry featured image','pzucd')
	        ),
			array(
	            'label' => __('Read more','pzucd'),
	            'id' => $prefix . 'layout-format-entry-readmore',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: a.pzucd_readmore',
	            'desc' => __('Format the content "Read more" link','pzucd')
	        ),
			array(
	            'label' => __('Read more hover','pzucd'),
	            'id' => $prefix . 'layout-format-entry-readmore-hover',
	            'type' => 'textarea-oneline',
				'default' => '',
				'help' => 'Declarations only for class: a.pzucd_readmore:hover',
	            'desc' => __('Format the content "Read more" link hover','pzucd')
	        ),

	      ); // End of settings array




	}	
	// add_action('admin_head','pzucd_layouts_add_help_tab');
	// function pzucd_layouts_add_help_tab () {
	//     $screen = get_current_screen();
	// 		$prefix = 'pzucd_';
	// 		switch ($screen->id) {
	// 			case 'edit-ucd-layouts':
	// 				$screen->add_help_tab( array(
	// 						'id'	=> $prefix.'view_help_about',
	// 						'title'	=> __('About ContentPlus Layouts'),
	// 						'content'	=> '<h3>About</h3><p>' . __( 'ContentPlus layouts are used in the ContentPlus Block' ) . '</p>'
	// 				) );
	// 				$screen->add_help_tab( array(
	//             'title' => __('Support','pzucd'),
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

	function pzucd_layout_defaults() {
		global $pzucd_cpt_layouts_meta_boxes;
//pzdebug($pzucd_cpt_layouts_meta_boxes);
		$pzucd_layout_defaults = array();
		pzucd_populate_layout_options();
		foreach ($pzucd_cpt_layouts_meta_boxes['tabs'] as $pzucd_meta_box) {
//		pzdebug($pzucd_meta_box);
			foreach ($pzucd_meta_box['fields'] as $pzucd_field) {
					if (!isset($pzucd_field['id'])) {
						$pzucd_layout_defaults[$pzucd_field['id']] = (isset($pzucd_field['default'])?$pzucd_field['default']:null);
					}
				}
			}
//pzdebug($pzucd_layout_defaults);			
		return $pzucd_layout_defaults;
	}	

// Callback function to show fields in meta box
/**
 * [pzucd_show_box description]
 * @param  [type] $postobj              [description]
 * @param  [type] $pizazz_callback_args [description]
 * @return [type]                       [description]
 */
function pzucd_show_box($postobj,$pizazz_callback_args) {
		global  $post, $post_ID;
		$pzucd_is_new = (get_post_status()=='auto-draft');
		$pizazz_meta_boxes = $pizazz_callback_args['args'];
		// Use nonce for verification
		echo '<input type="hidden" name="pizazz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		echo '<div id="pzucd_'.esc_attr($pizazz_meta_boxes['id']).'" class="pizazz-meta-boxes" >';
		
		echo '<ul id="pizazz-meta-nav" class="pizazz-meta-nav">';
		foreach ($pizazz_meta_boxes['tabs'] as $pizazz_meta_box_tab) {
					$pzucd_label_icon = $pizazz_meta_box_tab['icon'];
					$pzucd_showhide_labels ='show';
			echo '<li class="pizazz-meta-tab-title"><a href="#pizazz-form-table-'.str_replace(' ', '-', esc_attr($pizazz_meta_box_tab['label'])).'">'.$pzucd_label_icon.'<span class="pzucd_'.esc_attr($pzucd_showhide_labels).'_labels"><div class="contentplus-arrow-left"></div>'.esc_attr($pizazz_meta_box_tab['label']).'</span></a></li>';
		}
		echo '</ul>
		<div class="pzucd_the_tables" style="min-height:'.(count((int) $pizazz_meta_boxes['tabs'])*52+10).'px">';
		foreach ($pizazz_meta_boxes['tabs'] as $pizazz_meta_box_tab) {
				echo '<table id="pizazz-form-table-'.str_replace(' ', '-', esc_attr($pizazz_meta_box_tab['label'])).'" class="form-table pizazz-form-table" "style="width:60%!important;background:#eff!important;">';
				foreach ($pizazz_meta_box_tab['fields'] as $field) {
						// get current post meta data
					$pzucd_class = (!empty($field['class']))?' class="'.$field['class'].'" ':null;
						$pizazz_value = get_post_meta($post->ID, $field['id'], true);




/////
// WORK ON THIS!!
// $pizazz_value = ($force_default && $pizazz_value === '')?$field['default']:$pizazz_value;
/////








						//	if $pizazz_value is null it chucks a warning in in_array as it wants an array
						echo '<tr id="pizazz-form-table-row-'.str_replace(' ', '-', sanitize_html_class($pizazz_meta_box_tab['label'])).'-field-'.sanitize_html_class($field['id']).'" class="row-'.sanitize_html_class($field['id']).' '.'pzucd_field_'.$field['type'].'">';

						if ($field['type'] == 'hidden') {
							echo '<th></th>';
						} elseif ($field['type'] != 'heading') {
							echo '<th><label class="title-'.sanitize_html_class($field['id']).'" for="', sanitize_html_class($field['id']), '">', esc_attr($field['label']), '</label></th>';
						} else {
							echo '<th class="pz-field-heading"><h4 class="pz-field-heading">'.esc_html($field['label']).'</h4></th>';
						}

						if ($field['type'] != 'infobox' && $field['type'] != 'heading' && $field['type'] != 'hidden') {
							echo '<td class="pz-help"><span class="pz-help-button">?<span class="pz-help-text">'.esc_html($field['desc']).'</span></span></td>';
						} else {
							echo '<td class="pz-help"></td>';
						}

						echo '<td class="cell-'.sanitize_html_class($field['id']).'">';
						// This is simply to stop PHP debugging notice about missing index 'help' when help isn't specified
						$field['help'] = ((!array_key_exists('help',$field))?null:$field['help']);
						switch ($field['type']) {
								case 'heading':
										echo '';
										break;
								case 'infobox':
										echo '<span class="pzucd-infobox">',$field['desc'],'</span>';
										break;
								case 'readonly':
										echo '<input type="text" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" readonly="readonly" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '"  '.esc_attr($pzucd_class).'/>';
										break;
								case 'text':
										echo '<input type="text" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '" size="30" style="width:97%" '.esc_attr($pzucd_class).'/>', '<br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'hidden':
										echo '<input type="hidden" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '" style="height:0" />';
										break;
								case 'percent':
										echo '<input alt="'.$field['alt'].'"" type="range" min=0 max=100 name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '"style="width:80%" /><span class="pzucd-range-percent percent-'.sanitize_html_class($field['id']).'">', $pizazz_value,'%</span><br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'colorpicker':
										echo '<input type="text" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && $pizazz_value) ? $pizazz_value : $field['default']), '" size="30" style="width:100px" />', '<span class="pzucd_colour_swatch pzucd_colour_'.sanitize_html_class($field['id']).'">&nbsp;</span><br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'numeric':
										echo '<input type="number" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="',esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '0' || $pizazz_value === '')) ? $pizazz_value : $field['default']), '" size="30" min="'.$field['min'].'" max="'.$field['max'].'" step="'.$field['step'].'" style="width:100px" />', '<br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
									case 'textarea':
										echo '<textarea name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" cols="60" rows="4" style="width:97%">', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default']), '</textarea>', '<br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
									case 'textarea-oneline':
										echo '<textarea name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" cols="60" rows="1" style="width:97%">', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default']), '</textarea>', '<br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
									case 'textarea-small':
										echo '<textarea name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" cols="60" rows="2" style="width:97%">', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default']), '</textarea>', '<br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
									case 'textarea-large':
										echo '<textarea name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" cols="60" rows="16" style="width:97%">', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default']), '</textarea>', '<br />';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'select':
										$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
										echo '<select  name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '">';
										foreach ($field['options'] as $option) {
											$pizazz_value = (!$pizazz_value) ? $field['default'] : $pizazz_value;
											echo '<option'.(($pizazz_value == $option['value']) ? ' selected="selected"' : '').' value="'.esc_attr($option['value']).'">'.$option['text'].'</option>';
										}
										echo '</select>';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'ddslick-select':
										$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
										echo '<select  name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" class="pzucd_ddslick">';
										foreach ($field['options'] as $option) {
											$pizazz_value = (!$pizazz_value) ? $field['default'] : $pizazz_value;
											echo '<option'.(($pizazz_value == $option['value']) ? ' selected="selected"' : '').' value="'.esc_attr($option['value']).'">'.$option['text'].'</option>';
										}
										echo '</select>';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'multicheck':
										$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
										if (!$field['options']) {
											echo '<div id="', sanitize_html_class($field['id']), '">';
											echo '<span class="pzucd-infobox">No options available</span>';
											echo '</div>';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
										}
										// echo '<div id="', sanitize_html_class($field['id']), '" style="overflow-x:auto;max-height:100px;background:white;border:1px #eee solid">';
										echo '<div id="', sanitize_html_class($field['id']), '" >';
										foreach ($field['options'] as $option) {
												$pizazz_array_value = (is_string($pizazz_value))?array($pizazz_value):$pizazz_value;
												$pzucd_in_array = ($pizazz_value) ? (in_array($option['value'],$pizazz_array_value) || $pizazz_value == $option['value']) : false;
												echo '<input type="checkbox" name="'.sanitize_html_class($field['id']).'[]" id="'.sanitize_html_class($field['id']).'_'.$option['value'].'" value="'.esc_attr($option['value']).'"'.(($pzucd_in_array) ? ' checked="checked"' : '').' />&nbsp;'.$option['text'];
										}
										echo '</div>';
										echo '<span class="howto">'.$field['help'].'</span>';
									break;
								case 'multiselect':
										$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
										if (!$field['options']) {
											echo '<div id="', sanitize_html_class($field['id']), '">';
											echo '<span class="pzucd-infobox">No options available</span>';
											echo '</div>';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
										}
										// echo '<div id="', sanitize_html_class($field['id']), '" style="overflow-x:auto;max-height:100px;background:white;border:1px #eee solid">';
										echo '<div id="', sanitize_html_class($field['id']), '" style="background:white;border:1px #eee solid">';
										foreach ($field['options'] as $option) {
											if (substr($option['text'],0,1)=='>') {
												echo '<strong>&nbsp;=== '.substr($option['text'],1).' ==========</strong><br/>';
											} else {
												$pizazz_array_value = (is_string($pizazz_value))?array($pizazz_value):$pizazz_value;
												$pzucd_in_array = ($pizazz_value) ? (in_array($option['value'],$pizazz_array_value) || $pizazz_value == $option['value']) : false;
												echo '&nbsp;<input type="checkbox" name="'.sanitize_html_class($field['id']).'[]" id="'.sanitize_html_class($field['id']).'" value="'.esc_attr($option['value']).'"'.(($pzucd_in_array) ? ' checked="checked"' : '').' />&nbsp;'.$option['text'].'<br/>';
											}
										}
										echo '</div>';
										echo '<span class="howto">'.$field['help'].'</span>';
									break;
						
								case 'radio':
										foreach ($field['options'] as $option) {
												$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
												echo '<input type="radio" name="', sanitize_html_class($field['id']), '" value="', esc_attr($option['value'], '"', $pizazz_value == $option['value'] ? ' checked="checked"' : ''), ' />&nbsp;', $option['text'], '&nbsp;&nbsp;&nbsp;';
										}
										echo '<span class="howto">'.$field['help'].'</span>';
									break;
								case 'checkbox':
										$pizazz_value = ($pzucd_is_new)?$field['default']:$pizazz_value;
										echo '<input type="checkbox" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '"', $pizazz_value ? ' checked="checked"' : '', ' />';
										echo '<span class="howto">'.$field['help'].'</span>';
									break;
								case 'dropzone':
									 echo '<div id="pzucd-dropzone-'.sanitize_html_class($field['id']).'" class="pzucd-dropzone">';
									 echo '</div>';
										echo '<input type="text" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '" size="30" style="width:97%" />', '<br />';
										//echo '<input type="text" name="pzucd-cell-template-'.sanitize_html_class($field['id']).'" id="pzucd-cell-template-'.sanitize_html_class($field['id']).'" size=44><br/>';
									echo '<span class="howto">'.$field['help'].'</span>';
									break;
								case 'dragsource':
										$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
										if (!$field['options']) {
											echo '<div id="element-', sanitize_html_class($field['id']), '">';
											echo '<span class="pzucd-infobox">No options available</span>';
											echo '</div>';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
										}
										echo '<div id="', sanitize_html_class($field['id']), '">';
										foreach ($field['options'] as $option) {
												echo '<div id="element-'.sanitize_html_class($field['id']).'" alt="'.esc_attr($option['value']).'" class="pzucd-dragsource dragsource-'.sanitize_html_class($field['id']).'"><span>'.$option['text'].'</span></div>';
											}
										echo '</div>';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
								case 'custom':
									 echo '<div id="pzucd-custom-'.sanitize_html_class($field['id']).'" class="pzucd-custom">';
									 echo $field['code'];
										echo '<input type="hidden" readonly name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '" style="display:none;height:0;/>', '<br />';
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
function pzucd_save_data($post_id) {
	//var_dump($post_id,$_POST['post_type']);
	// Will need to manually add each case as new types created.
	if (!isset($_POST['post_type'])) {return false;}
	switch ($_POST['post_type']) {
		case 'ucd-layouts' :
			global $pzucd_cpt_layouts_meta_boxes;
pzdebug($pzucd_cpt_layouts_meta_boxes);
			$pizazz_meta_boxes = $pzucd_cpt_layouts_meta_boxes;
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
		foreach ($pizazz_meta_boxes['tabs'] as $pzucd_meta_box) {
			foreach ($pzucd_meta_box['fields'] as $field) {
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
add_action('save_post', 'pzucd_save_data');
//add_action('post_updated', 'pizazz_save_data');

function pzucd_draw_cell_layout() {
	$return_html = '';

		// Put in a hidden field with the plugin url for use in js
	$return_html = '
		<div id="pzucd-custom-pzucd_layout-layout" class="pzucd-custom">
			<div id="pzucd-dropzone-pzucd_layout-layout" class="pzucd-dropzone">
				<div class="pzgp-cell-image-behind"></div>
				<div class="pzucd-zone-control ui-sortable">
				
				<span class="pzucd-dropped pzucd-dropped-title" title="Post title" data-idcode="%title%" style="display: inline-block; font-weight: bold; font-size: 15px; background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;">This is the title</span>
				
				<span class="pzucd-dropped pzucd-dropped-meta1 pzucd-dropped-meta" title="Meta info 1" data-idcode="%meta1%" style="font-size: 11px; background-color: rgb(204, 170, 170); background-position: initial initial; background-repeat: initial initial;">Jan 1 2013</span>

				<span class="pzucd-dropped pzucd-dropped-excerpt" title="Excerpt with featured image" data-idcode="%excerpt%" style="font-size: 13px;"><img src="http://localhost/wp-mba.dev/wp-content/plugins/headway-ultimatecontentdisplay/images/sample-image.jpg" style="max-width:20%;float:right;padding:2px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span>

				<span class="pzucd-dropped pzucd-dropped-content" title="Full post content" data-idcode="%content%" style="font-size: 13px; display: none;"><img src="http://localhost/wp-mba.dev/wp-content/plugins/headway-ultimatecontentdisplay/images/sample-image.jpg" style="max-width:30%;float:left;padding:5px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;•&nbsp;Cras semper sem hendrerit</li><li>&nbsp;•&nbsp;Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><p>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </p><p>Donec dictum leo at erat mattis sollicitudin. Nunc vulputate nisl suscipit enim adipiscing faucibus. Ut faucibus sem non sapien rutrum gravida. Maecenas pharetra mi et velit posuere ac elementum mi tincidunt. Nullam tristique tempus odio id rutrum. Nam ligula urna, semper eget elementum nec, euismod at tortor. Duis commodo, purus id posuere aliquam, orci felis facilisis odio, ac sagittis mi nisl at nibh. Sed non risus eu quam euismod faucibus.</p><p>Proin mattis convallis scelerisque. Curabitur auctor felis id sapien dictum vehicula. Aenean euismod porttitor dictum. Vestibulum nulla leo, volutpat quis tempus eu, accumsan eget ante.</p></span>

				<span class="pzucd-dropped pzucd-dropped-image" title="Featured image" data-idcode="%image%" style="max-height: 100px; overflow: hidden;"><img src="http://localhost/wp-mba.dev/wp-content/plugins/headway-ultimatecontentdisplay/images/sample-image.jpg" style="max-width:100%;"></span>


				<span class="pzucd-dropped pzucd-dropped-meta2 pzucd-dropped-meta" title="Meta info 2" data-idcode="%meta2%" style="font-size: 11px; background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;">Categories - News, Sport</span>

				<span class="pzucd-dropped pzucd-dropped-meta3 pzucd-dropped-meta" title="Meta info 3" data-idcode="%meta3%" style="font-size: 11px; background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;">Comments: 27</span>
			</div>
		</div>


				<span class="howto">Drag and drop to sort the order of your elements.<br>Heights are fluid in cells, so not indicative of how it will look on the page.<br>
	            	<strong style="color:#d00">This is an example only and thus only a <span style="border-bottom: 3px double;">general guide</span> to how the cells will look.</strong>
	      </span>
	      </div>
				<div class="plugin_url" style="display:none;">'.PZUCD_PLUGIN_URL.'</div>
	      ';		
	return $return_html;

}

/*
Plugin Name: Demo Admin Page
Plugin URI: http://en.bainternet.info
Description: My Admin Page Class usage demo
Version: 1.2.6
Author: Bainternet, Ohad Raz
Author URI: http://en.bainternet.info
*/



	require_once PZUCD_PLUGIN_PATH.'/admin-page-class/admin-page-class.php';

  /**
   * configure your admin page
   */
  $config = array(
    'menu'           => 'settings',             //sub page to settings page
    'page_title'     => __('Demo Admin Page','apc'),       //The name of this page
    'capability'     => 'edit_themes',         // The capability needed to view the page
    'option_group'   => 'demo_options',       //the name of the option to create in the database
    'id'             => 'admin_page',            // meta box id, unique per page
    'fields'         => array(),            // list of fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );

  /**
   * Initiate your admin page
   */
  $options_panel = new BF_Admin_Page_Class($config);
  $options_panel->OpenTabs_container('');

  /**
   * define your admin page tabs listing
   */
  $options_panel->TabsListing(array(
    'links' => array(
    'options_1' =>  __('Simple Options','apc'),
    'options_2' =>  __('Fancy Options','apc'),
    'options_3' => __('Editor Options','apc'),
    'options_4' => __('WordPress Options','apc'),
    'options_5' =>  __('Advanced Options','apc'),
    'options_6' =>  __('Field Validation','apc'),
    'options_7' =>  __('Import Export','apc'),
    )
  ));

  /**
   * Open admin page first tab
   */
  $options_panel->OpenTab('options_1');

  /**
   * Add fields to your admin page first tab
   *
   * Simple options:
   * input text, checbox, select, radio
   * textarea
   */
  //title
  $options_panel->Title(__("Simple Options","apc"));
  //An optionl descrption paragraph
  $options_panel->addParagraph(__("This is a simple paragraph","apc"));
  //text field
  $options_panel->addText('text_field_id', array('name'=> __('My Text ','apc'), 'std'=> 'text', 'desc' => __('Simple text field description','apc')));
  //textarea field
  $options_panel->addTextarea('textarea_field_id',array('name'=> __('My Textarea ','apc'), 'std'=> 'textarea', 'desc' => __('Simple textarea field description','apc')));
  //checkbox field
  $options_panel->addCheckbox('checkbox_field_id',array('name'=> __('My Checkbox ','apc'), 'std' => true, 'desc' => __('Simple checkbox field description','apc')));
  //select field
  $options_panel->addSelect('select_field_id',array('selectkey1'=>'Select Value1','selectkey2'=>'Select Value2'),array('name'=> __('My select ','apc'), 'std'=> array('selectkey2'), 'desc' => __('Simple select field description','apc')));
  //radio field
  $options_panel->addRadio('radio_field_id',array('radiokey1'=>'Radio Value1','radiokey2'=>'Radio Value2'),array('name'=> __('My Radio Filed','apc'), 'std'=> array('radiokey2'), 'desc' => __('Simple radio field description','apc')));
  /**
   * Close first tab
   */
  $options_panel->CloseTab();


  /**
   * Open admin page Second tab
   */
  $options_panel->OpenTab('options_2');
  /**
   * Add fields to your admin page 2nd tab
   *
   * Fancy options:
   *  typography field
   *  image uploader
   *  Pluploader
   *  date picker
   *  time picker
   *  color picker
   */
  //title
  $options_panel->Title(__('Fancy Options','apc'));
  //Typography field
  $options_panel->addTypo('typography_field_id',array('name' => __("My Typography","apc"),'std' => array('size' => '14px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('Typography field description','apc')));
  //Image field
  $options_panel->addImage('image_field_id',array('name'=> __('My Image ','apc'),'preview_height' => '120px', 'preview_width' => '440px', 'desc' => __('Simple image field description','apc')));
  //PLupload field
  $options_panel->addPlupload('plupload_field_ID',array('name' => __('PlUpload Field','apc'), 'multiple' => true, 'desc' => __('Simple multiple image field description','apc')));
  //date field
  $options_panel->addDate('date_field_id',array('name'=> __('My Date ','apc'), 'desc' => __('Simple date picker field description','apc')));
  //Time field
  $options_panel->addTime('time_field_id',array('name'=> __('My Time ','apc'), 'desc' => __('Simple time picker field description','apc')));
  //Color field
  $options_panel->addColor('color_field_id',array('name'=> __('My Color ','apc'), 'desc' => __('Simple color picker field description','apc')));

  /**
   * Close second tab
   */
  $options_panel->CloseTab();



  /**
   * Open admin page 3rd tab
   */
  $options_panel->OpenTab('options_3');
  /**
   * Add fields to your admin page 3rd tab
   *
   * Editor options:
   *   WYSIWYG (tinyMCE editor)
   *  Syntax code editor (css,html,js,php)
   */
  //title
  $options_panel->Title(__("Editor Options","apc"));
  //wysiwyg field
  $options_panel->addWysiwyg('wysiwyg_field_id',array('name'=> __('My wysiwyg Editor ','apc'), 'desc' => __('wysiwyg field description','apc')));
  //code editor field
  $options_panel->addCode('code_field_id',array('name'=> __('Code Editor ','apc'),'syntax' => 'php', 'desc' => __('code editor field description','apc')));
  /**
   * Close 3rd tab
   */
  $options_panel->CloseTab();


  /**
   * Open admin page 4th tab
   */
  $options_panel->OpenTab('options_4');

  /**
   * Add fields to your admin page 4th tab
   *
   * WordPress Options:
   *   Taxonomies dropdown
   *  posts dropdown
   *  Taxonomies checkboxes list
   *  posts checkboxes list
   *
   */
  //title
  $options_panel->Title(__("WordPress Options","apc"));
  //taxonomy select field
  $options_panel->addTaxonomy('taxonomy_field_id',array('taxonomy' => 'category'),array('name'=> __('My Taxonomy Select','apc'),'class' => 'no-fancy','desc' => __('This field has a <pre>.no-fancy</pre> class which disables the fancy select2 functions','apc') ));
  //posts select field
  $options_panel->addPosts('posts_field_id',array('post_type' => 'post'),array('name'=> __('My Posts Select','apc'), 'desc' => __('posts select field description','apc')));
  //Roles select field
  $options_panel->addRoles('roles_field_id',array(),array('name'=> __('My Roles Select','apc'), 'desc' => __('roles select field description','apc')));
  //taxonomy checkbox field
  $options_panel->addTaxonomy('taxonomy2_field_id',array('taxonomy' => 'category','type' => 'checkbox_list'),array('name'=> __('My Taxonomy Checkboxes','apc'), 'desc' => __('taxonomy checkboxes field description','apc')));
  //posts checkbox field
  $options_panel->addPosts('posts2_field_id',array('post_type' => 'post','type' => 'checkbox_list'),array('name'=> __('My Posts Checkboxes','apc'), 'class' => 'no-toggle','desc' => __('This field has a <pre>.no-toggle</pre> class which disables the fancy Iphone like toggle','apc')));
  //Roles checkbox field
  $options_panel->addRoles('roles2_field_id',array('type' => 'checkbox_list' ),array('name'=> __('My Roles Checkboxes','apc'), 'desc' => __('roles checboxes field description','apc')));


  /**
   * Close 4th tab
   */
  $options_panel->CloseTab();
  /**
   * Open admin page 5th tab
   */
  $options_panel->OpenTab('options_5');
  //title
  $options_panel->Title(__("Advanced Options","apc"));

  //sortable field
   $options_panel->addSortable('sortable_field_id',array('1' => 'One','2'=> 'Two', '3' => 'three', '4'=> 'four'),array('name' => __('My Sortable Field','apc'), 'desc' => __('Sortable field description','apc')));

  /*
   * To Create a reapeater Block first create an array of fields
   * use the same functions as above but add true as a last param
   */
  $repeater_fields[] = $options_panel->addText('re_text_field_id',array('name'=> __('My Text ','apc')),true);
  $repeater_fields[] = $options_panel->addTextarea('re_textarea_field_id',array('name'=> __('My Textarea ','apc')),true);
  $repeater_fields[] = $options_panel->addImage('image_field_id',array('name'=> __('My Image ','apc')),true);
  $repeater_fields[] = $options_panel->addCheckbox('checkbox_field_id',array('name'=> __('My Checkbox  ','apc')),true);

  /*
   * Then just add the fields to the repeater block
   */
  //repeater block
  $options_panel->addRepeaterBlock('re_',array('sortable' => true, 'inline' => true, 'name' => __('This is a Repeater Block','apc'),'fields' => $repeater_fields, 'desc' => __('Repeater field description','apc')));

  /**
   * To Create a Conditional Block first create an array of fields (just like a repeater block
   * use the same functions as above but add true as a last param
   */
  $Conditinal_fields[] = $options_panel->addText('con_text_field_id',array('name'=> __('My Text ','apc')),true);
  $Conditinal_fields[] = $options_panel->addTextarea('con_textarea_field_id',array('name'=> __('My Textarea ','apc')),true);
  $Conditinal_fields[] = $options_panel->addImage('con_image_field_id',array('name'=> __('My Image ','apc')),true);
  $Conditinal_fields[] = $options_panel->addCheckbox('con_checkbox_field_id',array('name'=> __('My Checkbox  ','apc')),true);

  /**
   * Then just add the fields to the repeater block
   */
  //conditinal block
  $options_panel->addCondition('conditinal_fields',
      array(
        'name'   => __('Enable conditinal fields? ','apc'),
        'desc'   => __('<small>Turn ON if you want to enable the <strong>conditinal fields</strong>.</small>','apc'),
        'fields' => $Conditinal_fields,
        'std'    => false
      ));
  /**
   * Close 5th tab
   */
  $options_panel->CloseTab();


  /**
   * Open admin page 6th tab
   * field validation
   * `email`            => validate email
   * `alphanumeric`     => validate alphanumeric
   * `url`              => validate url
   * `length`           => check for string length
   * `maxlength`        => check for max string length
   * `minlength`        => check for min string length
   * `maxvalue`         => check for max numeric value
   * `minvalue`         => check for min numeric value
   * `numeric`          => check for numeric value
   */
  $options_panel->OpenTab('options_6');
  //email validation
  $options_panel->addText('email_text_field_id',
    array(
      'name'     => __('My Email validation ','apc'),
      'std'      => 'test@domain.com',
      'desc'     => __("Field with email validation","apc"),
      'validate' => array(
          'email' => array('param' => '','message' => __("must be a valid email address","apc"))
      )
    )
  );

  //alphanumeric validation
  $options_panel->addText('an_text_field_id',
    array(
      'name'     => __('My alpha numeric validation ','apc'),
      'std'      => 'abcd1234',
      'desc'     => __("Field with alpa numeric validation, try entring something like #$","apc"),
      'validate' => array(
          'alphanumeric' => array('param' => '','message' => __("must be a valid alpha numeric chars only","apc"))
      )
    )
  );


  // string length exceeds maximum length validation
  $options_panel->addText('max_text_field_id',
    array(
      'name'     => __('My Max length of string validation ','apc'),
      'std'      => 'abcdefghi',
      'desc'     => __("Field with max string lenght validation,So try entering a longer string","apc"),
      'validate' => array(
          'maxlength' => array('param' => 10,'message' => __("must be not exceed 10 chars long","apc"))
      )
    )
  );

  // string length exceeds maximum length validation
  $options_panel->addText('min_text_field_id',
    array(
      'name'     => __('My Min length of string validation ','apc'),
      'std'      => 'abcdefghi',
      'desc'     => __("Field with min string lenght validation, So try entering a shorter string","apc"),
      'validate' => array(
          'minlength' => array('param' => 8,'message' => __("must be a minimum length of 8 chars long","apc"))
      )
    )
  );



  // check for exactly length of string validation
  $options_panel->addText('exact_text_field_id',
    array(
      'name'     => __('My exactly length of string validation ','apc'),
      'std'      => 'abcdefghij',
      'desc'     => __("Field with exact string lenght validation, So try entering a shorter or longer string","apc"),
      'validate' => array(
          'length' => array('param' => 10,'message' => __("must be exactly 10 chars long","apc"))
      )
    )
  );

  //is_numeric
  $options_panel->addText('n_text_field_id',
    array(
      'name'     => __('My numeric validation ','apc'),
      'std'      => 1,
      'desc'     => __("Field with numeric value validation","apc"),
      'validate' => array(
          'numeric' => array('param' => '','message' => __("must be numeric value","apc"))
      )
    )
  );

  //min numeric value
  $options_panel->addText('nmin_text_field_id',
    array(
      'name'     => __('My Min numeric validation ','apc'),
      'std'      => 9,
      'desc'     => __("Field with min numeric value validation","apc"),
      'validate' => array(
          'minvalue' => array('param' => 8,'message' => __("must be numeric with a min value of 8","apc"))
      )
    )
  );

  //max numeric value
  $options_panel->addText('nmax_text_field_id',
    array(
      'name'     => __('My Max numeric validation ','apc'),
      'std'      => 9,
      'desc'     => __("Field with max numeric value validation","apc"),
      'validate' => array(
          'maxvalue' => array('param' => 10,'message' => __("must be numeric with a Max value of 10","apc"))
      )
    )
  );

  //is_url validation
  $options_panel->addText('url_text_field_id',
    array(
      'name'     => __('My URL validation ','apc'),
      'std'      => 'http://en.bainternet.info',
      'desc'     => __("Field with url value validation","apc"),
      'validate' => array(
          'url' => array('param' => '','message' => __("must be a valid URL","apc"))
      )
    )
  );

  /**
   * Close 6th tab
   */
  $options_panel->CloseTab();

  /**
   * Open admin page 7th tab
   */
  $options_panel->OpenTab('options_7');

  //title
  $options_panel->Title(__("Import Export","apc"));

  /**
   * add import export functionallty
   */
  $options_panel->addImportExport();

  /**
   * Close 7th tab
   */
  $options_panel->CloseTab();
  $options_panel->CloseTab();

  //Now Just for the fun I'll add Help tabs
  $options_panel->HelpTab(array(
    'id'      =>'tab_id',
    'title'   => __('My help tab title','apc'),
    'content' =>'<p>'.__('This is my Help Tab content','apc').'</p>'
  ));
  $options_panel->HelpTab(array(
    'id'       => 'tab_id2',
    'title'    => __('My 2nd help tab title','apc'),
    'callback' => 'help_tab_callback_demo'
  ));

  //help tab callback function
  function help_tab_callback_demo(){
    echo '<p>'.__('This is my 2nd Help Tab content from a callback function','apc').'</p>';
  }


