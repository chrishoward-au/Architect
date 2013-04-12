<?php

class pzucd_Cell_Layouts extends pzucdForm
{

	private $mb_fields;

	/**
	 * [__construct description]
	 */
	function __construct()
	{

		add_action( 'init', array( $this, 'create_layouts_post_type' ) );
		// This overrides the one in the parent class

		if ( is_admin() )
		{

			//	add_action('admin_init', 'pzucd_preview_meta');
			add_action( 'add_meta_boxes', array( $this, 'layouts_meta' ) );
			add_action( 'admin_head', array( $this, 'cell_layouts_admin_head' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'cell_layouts_admin_enqueue' ) );
			add_filter( 'manage_ucd-layouts_posts_columns', array( $this, 'add_cell_layout_columns' ) );
			add_action( 'manage_ucd-layouts_posts_custom_column', array( $this, 'add_cell_layout_column_content' ), 10, 2 );

			// check screen ucd-layouts. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'ucd-layouts' )
//			{
//			}
		}

		// Need to do this here so save knows about the layout. 
		$this->mb_fields = self::_populate_layout_options();
		add_action( 'save_post', array( $this, 'save_data' ) );
	}

	/**
	 * [cell_layouts_admin_enqueue description]
	 * @param  [type] $hook [description]
	 * @return [type]       [description]
	 */
	public function cell_layouts_admin_enqueue( $hook )
	{
		$screen = get_current_screen();
		if ( 'ucd-layouts' == $screen->id )
		{

			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'jquery-ui-button' );
			wp_enqueue_script( 'jquery-ui-draggable' );
			wp_enqueue_script( 'jquery-ui-droppable' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-resizable' );

			wp_enqueue_style( 'pzucd-block-css', PZUCD_PLUGIN_URL . '/admin/css/ucd-admin.css' );
			wp_enqueue_style( 'pzucd-jqueryui-css', PZUCD_PLUGIN_URL . '/external/jquery-ui-1.10.2.custom/css/pz_ultimate_content_display/jquery-ui-1.10.2.custom.min.css' );

			wp_enqueue_script( 'jquery-pzucd-metaboxes', PZUCD_PLUGIN_URL . '/admin/js/ucd-metaboxes.js', array( 'jquery' ) );
		}
	}

	/**
	 * [cell_layouts_admin_head description]
	 * @return [type] [description]
	 */
	public function cell_layouts_admin_head()
	{
		
	}

	/**
	 * [add_cell_layout_columns description]
	 * @param [type] $columns [description]
	 */
	public function add_cell_layout_columns( $columns )
	{
		unset( $columns[ 'thumbnail' ] );
		$pzucd_front	 = array_slice( $columns, 0, 2 );
		$pzucd_back		 = array_slice( $columns, 2 );
		$pzucd_insert	 =
						array
								(
								'pzucd_set_name'	 => __( 'Set name', 'pzsp' ),
								'pzucd_short_name' => __( 'Layout short name', 'pzsp' ),
		);

		return array_merge( $pzucd_front, $pzucd_insert, $pzucd_back );
	}

	/**
	 * [add_cell_layout_column_content description]
	 * @param [type] $column  [description]
	 * @param [type] $post_id [description]
	 */
	public function add_cell_layout_column_content( $column, $post_id )
	{
		switch ( $column )
		{
			case 'pzucd_short_name':
				echo get_post_meta( $post_id, 'pzucd_short-name', true );
				break;
			case 'pzucd_set_name':
				echo get_post_meta( $post_id, 'pzucd_set-name', true );
				break;
		}
	}

	/**
	 * [create_layouts_post_type description]
	 * @return [type] [description]
	 */
	public function create_layouts_post_type()
	{
		$labels = array(
				'name'							 => _x( 'Cell Layouts', 'post type general name' ),
				'singular_name'			 => _x( 'Cell Layout', 'post type singular name' ),
				'add_new'						 => __( 'Add New Cell Layout' ),
				'add_new_item'			 => __( 'Add Cell New Layout' ),
				'edit_item'					 => __( 'Edit Cell Layout' ),
				'new_item'					 => __( 'New Cell Layout' ),
				'view_item'					 => __( 'View Cell Layout' ),
				'search_items'			 => __( 'Search Cell Layouts' ),
				'not_found'					 => __( 'No cell layouts found' ),
				'not_found_in_trash' => __( 'No cell layouts found in Trash' ),
				'parent_item_colon'	 => '',
				'menu_name'					 => _x( 'UCD Cell Layouts', 'pzucd-cell-designer' ),
		);

		$args = array(
				'labels'							 => $labels,
				'description'					 => __( 'Ultimate Content Display cell layouts are used to create reusable cell layouts for use in your UCD blocks, widgets, shortcodes and template tags' ),
				'public'							 => false,
				'publicly_queryable'	 => false,
				'show_ui'							 => true,
				'show_in_menu'				 => 'pzucd',
				'show_in_nav_menus'		 => false,
				'query_var'						 => true,
				'rewrite'							 => true,
				'capability_type'			 => 'post',
				'has_archive'					 => false,
				'hierarchical'				 => false,
				'menu_position'				 => 45,
				'supports'						 => array( 'title', 'revisions' ),
				'exclude_from_search'	 => true,
				'register_meta_box_cb' => array( $this, 'layouts_meta' )
		);

		register_post_type( 'ucd-layouts', $args );
	}

	public function save_data( $post_id )
	{
//		var_dump( 'SAVE', count( $this->mb_fields ) );
		parent::save_data( $post_id, $this->mb_fields );
	}

	public function draw_meta_box( $postobj, $callback_args )
	{
//		var_dump( 'DRAW', count( $this->mb_fields ) );
		parent::show_meta_box( $postobj, $callback_args );

		//	$this->mb_fields = $callback_args;
	}

	/**
	 * [layouts_meta description]
	 * @return [type] [description]
	 */
	public function layouts_meta()
	{
		// global $this->meta_box_layout;
		// Fill any defaults if necessary
//		$this->cell_layout_form = self::layout_defaults();
		add_meta_box(
						$this->mb_fields[ 'id' ], $this->mb_fields[ 'title' ], array( $this, 'draw_meta_box' ), $this->mb_fields[ 'page' ], $this->mb_fields[ 'context' ], $this->mb_fields[ 'priority' ], $this->mb_fields
		);
	}

// End layouts_meta
	// cpt = custom post type
	/**
	 * [populate_layout_options description]
	 * @return [type] [description]
	 */
	private function _populate_layout_options()
	{
		//		global $this->meta_box_layout;

		$prefix					 = 'pzucd_';
		/*
		 *
		 * Setup Layouts extra fields
		 *
		 */
		$i							 = 0;
		$meta_box_layout = array(
				'id'					 => 'pzucd-layouts-id',
				'title'				 => 'Cell Designer',
				'page'				 => 'ucd-layouts',
				'context'			 => 'normal',
				'priority'		 => 'high',
				'orientation'	 => 'horizontal',
				'tabs'				 => array(
						$i++ => array(
								'icon'	 => 'phone-65grey.png',
								'label'	 => __( 'Responsive', 'pzucd' ),
								'id'		 => $prefix . 'tab_layouts_responsive',
								'type'	 => 'tab',
						),
						$i++ => array(
								'icon'	 => 'layout2-65grey.png',
								'label'	 => __( 'Layout', 'pzucd' ),
								'id'		 => $prefix . 'tab_layouts_layout',
								'type'	 => 'tab',
						),
						$i++ => array(
								'icon'	 => 'text2-65grey.png',
								'label'	 => __( 'Title', 'pzucd' ),
								'id'		 => $prefix . 'tab_layouts_title',
								'type'	 => 'tab',
						),
						$i++ => array(
								'icon'	 => 'text1-65grey.png',
								'label'	 => __( 'Content', 'pzucd' ),
								'id'		 => $prefix . 'tab_layouts_content',
								'type'	 => 'tab',
						),
						$i++ => array(
								'icon'	 => 'layout1-65grey.png',
								'label'	 => __( 'Excerpt', 'pzucd' ),
								'id'		 => $prefix . 'tab_layouts_excerpt',
								'type'	 => 'tab',
						),
						$i++ => array(
								'icon'	 => 'help2-65grey.png',
								'label'	 => __( 'Meta', 'pzucd' ),
								'id'		 => $prefix . 'tab_layouts_meta',
								'type'	 => 'tab',
						),
						$i++ => array(
								'icon'	 => 'images-65grey.png',
								'label'	 => __( 'Image', 'pzucd' ),
								'id'		 => $prefix . 'tab_layouts_images',
								'type'	 => 'tab',
						),
						$i++ => array(
								'icon'	 => 'braces-65grey.png',
								'label'	 => __( 'Format', 'pzucd' ),
								'id'		 => $prefix . 'tab_layouts_format',
								'type'	 => 'tab',
						),
				)
		);

		$i = 0;

		/*		 * **************
		 * RESPONSIVE
		 * *************** */
		$meta_box_layout[ 'tabs' ][ $i++ ][ 'fields' ] = array(
				array(
						'label'		 => __( 'Intelligence', 'pzucd' ),
						'id'			 => $prefix . 'layout-layout-responsive',
						'type'		 => 'heading',
						'default'	 => '',
						'desc'		 => __( '', 'pzucd' )
				),
				array(
						'label'			 => __( 'Set name', 'pzucd' ),
						'id'				 => $prefix . 'layout-set-name',
						'type'			 => 'text',
						'default'		 => '',
						'desc'			 => __( 'A set name for this layout. This enables you to create sets of layouts for different parent dimensions. That is,when the dimensions of the parent change, the layout will change accordingly. Tracditional responsive design is based on the width of your device\'s screen,; however this fails if you place the object in a narrow column on a large screen,', 'pzucd' ),
						'help'			 => __( 'Create sets of layouts with each layout in a set for different parent dimensions' ),
						'validation' => 'data-validation-engine="validate[required]"'
				),
				array(
						'label'			 => __( 'Layout short name ', 'pzucd' ),
						'id'				 => $prefix . 'layout-short-name',
						'type'			 => 'text',
						'default'		 => '',
						'desc'			 => __( 'Something about the cell responsiveness.', 'pzucd' ),
						'validation' => 'data-validation-engine="validate[required]"'
				),
				array(
						'label'			 => __( 'Minimum parent width (px)', 'pzucd' ),
						'id'				 => $prefix . 'layout-min-display-width',
						'type'			 => 'text',
						'default'		 => '0',
						'desc'			 => __( 'Something about the cell responsiveness.', 'pzucd' ),
						'validation' => 'data-validation-engine="validate[required]"'
				),
				array(
						'label'			 => __( 'Maximum parent width (px)', 'pzucd' ),
						'id'				 => $prefix . 'layout-max-display-width',
						'type'			 => 'text',
						'default'		 => '320',
						'desc'			 => __( 'Something about the cell responsiveness.', 'pzucd' ),
						'validation' => 'data-validation-engine="validate[required]"'
				),
		);

		/*		 * **************
		 * LAYOUT
		 * ************** */
		$meta_box_layout[ 'tabs' ][ $i++ ][ 'fields' ] = array(
				array(
						'label'		 => __( 'Layout', 'pzucd' ),
						'id'			 => $prefix . 'layout-layout-head',
						'type'		 => 'heading',
						'default'	 => '',
						'desc'		 => __( '', 'pzucd' )
				),
				array(
						'label'		 => __( 'Components to show', 'pzucd' ),
						'id'			 => $prefix . 'layout-show',
						'type'		 => 'multicheck',
						'default'	 => array( 'title', 'excerpt', 'meta1', 'image' ),
						'options'	 => array(
								array( 'value'	 => 'title', 'text'	 => 'Title <span class = "title"></span> ' ),
								array( 'value'	 => 'excerpt', 'text'	 => 'Excerpt <span class = "excerpt"></span> ' ),
								array( 'value'	 => 'content', 'text'	 => 'Content <span class = "content"></span> ' ),
								array( 'value'	 => 'image', 'text'	 => 'Image <span class = "image"></span> ' ),
								array( 'value'	 => 'meta1', 'text'	 => 'Meta1 <span class = "meta1"></span> ' ),
								array( 'value'	 => 'meta2', 'text'	 => 'Meta2 <span class = "meta2"></span> ' ),
								array( 'value'	 => 'meta3', 'text'	 => 'Meta3 <span class = "meta3"></span>' ),
						),
						'desc'		 => __( 'Select which base components to include in this cell layout.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Components area position', 'pzucd' ),
						'id'			 => $prefix . 'layout-sections-position',
						'type'		 => 'select',
						'default'	 => 'top',
						'options'	 => array(
								array( 'value'	 => 'top', 'text'	 => 'Top of cell' ),
								array( 'value'	 => 'bottom', 'text'	 => 'Bottom of cell' ),
								array( 'value'	 => 'left', 'text'	 => 'Left of cell' ),
								array( 'value'	 => 'right', 'text'	 => 'Right of cell' ),
						),
						'desc'		 => __( 'Position for all the components as a group', 'pzucd' )
				),
				array(
						'label'		 => __( 'Components area width (%)', 'pzucd' ),
						'id'			 => $prefix . 'layout-sections-widths',
						'type'		 => 'percent',
						'default'	 => '100',
						'alt'			 => 'zones',
						'min'			 => '1',
						'max'			 => '100',
						'step'		 => '1',
						'desc'		 => __( 'Set the overall width for the components area. Necessary for left or right positioning of sections', 'pzucd' ),
						'help'		 => __( 'Note:The sum of the width and the left/right nudge should equal 100', 'pzucd' )
				),
				array(
						'label'		 => __( 'Nudge components area x, y (%)', 'pzucd' ),
						'id'			 => $prefix . 'layout-nudge-sections',
						'type'		 => 'text',
						'default'	 => '0, 0',
						'desc'		 => __( 'Enter x, y co-ordinates to move the components area. e.g. 75, 75. Note: These measurements are percenatge of the cell.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Feature Image/Video', 'pzucd' ),
						'id'			 => $prefix . 'layout-background-image',
						'type'		 => 'select',
						'default'	 => 'none',
						'options'	 => array(
								array( 'value'	 => 'none', 'text'	 => 'No image' ),
								array( 'value'	 => 'fill', 'text'	 => 'Fill the cell' ),
								array( 'value'	 => 'align', 'text'	 => 'Align with components area' ),
						),
						'desc'		 => __( 'Select how to display the featured image or video as the background.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Cell Height', 'pzucd' ),
						'id'			 => $prefix . 'layout-cell-height-type',
						'type'		 => 'select',
						'default'	 => 'fluid',
						'options'	 => array(
								array( 'value'	 => 'fluid', 'text'	 => 'Fluid' ),
								array( 'value'	 => 'fixed', 'text'	 => 'Fixed' ),
						),
						'desc'		 => __( 'Choose whether to set the height of the cells (fixed), or allow them to adjust to the content height (fluid).', 'pzucd' )
				),
				array(
						'label'		 => __( 'Cell layout preview', 'pzucd' ),
						'id'			 => $prefix . 'layout-cell-preview',
						'type'		 => 'custom',
						'code'		 => self::draw_cell_layout(),
						'default'	 => '',
						'desc'		 => __( '', 'pzucd' )
				),
				array(
						'label'		 => __( 'Cell field order', 'pzucd' ),
						'id'			 => $prefix . 'layout-field-order',
						'type'		 => 'hidden',
						'default'	 => '%title%, %meta1%, %meta2%, %excerpt%, %content%, %image%, %meta3%',
						'desc'		 => __( '', 'pzucd' )
				),
		);

		/*		 * **************
		 * TITLES
		 * ************** */
		$meta_box_layout[ 'tabs' ][ $i++ ][ 'fields' ] = array(
				array(
						'label'		 => __( 'Titles', 'pzucd' ),
						'id'			 => $prefix . 'layout-title-head',
						'type'		 => 'heading',
						'default'	 => '',
						'desc'		 => __( '', 'pzucd' )
				),
				array(
						'label'		 => __( 'Title area width', 'pzucd' ),
						'id'			 => $prefix . 'layout-title-width',
						'type'		 => 'readonly',
						'class'		 => 'pzucd-zone-percent',
						'alt'			 => 'title',
						'default'	 => '100',
						'desc'		 => __( 'Set the title width as a percentage of the cell width.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Title bullet', 'pzucd' ),
						'id'			 => $prefix . 'layout-title-bullet',
						'type'		 => 'text',
						'default'	 => '',
						'desc'		 => __( 'Prefix titles with bullets.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Link title', 'pzucd' ),
						'id'			 => $prefix . 'layout-link-title',
						'type'		 => 'checkbox',
						'default'	 => true,
						'desc'		 => __( 'Link titles to the post.', 'pzucd' )
				),
		);

		/*		 * **************
		 * CONTENT
		 * ************** */
		$meta_box_layout[ 'tabs' ][ $i++ ][ 'fields' ] = array(
				array(
						'label'		 => __( 'Content', 'pzucd' ),
						'id'			 => $prefix . 'layout-content-head',
						'type'		 => 'heading',
						'default'	 => '',
						'desc'		 => __( '', 'pzucd' )
				),
				array(
						'label'		 => __( 'Content area width', 'pzucd' ),
						'id'			 => $prefix . 'layout-content-width',
						'type'		 => 'readonly',
						'class'		 => 'pzucd-zone-percent',
						'alt'			 => 'content',
						'default'	 => '100',
						'desc'		 => __( 'Set the content width as a percentage of the cell width.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Content CSS selectors', 'pzucd' ),
						'id'			 => $prefix . 'layout-content-css-selectors',
						'type'		 => 'infobox',
						'default'	 => '',
						'desc'		 => __( 'CSS selectors used in the content area:<ul>
		<li>.pzucd-entry-content</li>
		<li>.pzucd-entry-content a</li>
		<li>.pzucd-entry-content img</li>
		</ul>', 'pzucd' )
				),
		);

		/*		 * **************
		 * EXCERPTS
		 * ************** */
		$meta_box_layout[ 'tabs' ][ $i++ ][ 'fields' ] = array(
				array(
						'label'		 => __( 'Excerpts', 'pzucd' ),
						'id'			 => $prefix . 'layout-excerpts-head',
						'type'		 => 'heading',
						'default'	 => '',
						'desc'		 => __( '', 'pzucd' )
				),
				array(
						'label'		 => __( 'Excerpt area width', 'pzucd' ),
						'id'			 => $prefix . 'layout-excerpt-width',
						'type'		 => 'readonly',
						'class'		 => 'pzucd-zone-percent',
						'alt'			 => 'excerpt',
						'default'	 => '100',
						'desc'		 => __( 'Set the excerpt width as a percentage of the cell width.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Excerpt image', 'pzucd' ),
						'id'			 => $prefix . 'layout-excerpt-thumb',
						'type'		 => 'select',
						'default'	 => 'none',
						'options'	 => array(
								array( 'value'	 => 'none', 'text'	 => 'No image' ),
								array( 'value'	 => 'left', 'text'	 => 'Image left' ),
								array( 'value'	 => 'right', 'text'	 => 'Image right' ),
						),
						'desc'		 => __( 'Set the alignment of the image when it is in the excerpt. This will use the image settings', 'pzucd' )
				),
				array(
						'label'		 => __( 'Read more message', 'pzucd' ),
						'id'			 => $prefix . 'layout-read-more',
						'type'		 => 'text',
						'default'	 => '[ Read more ]',
						'desc'		 => __( 'Text to display for read more.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Excerpt length', 'pzucd' ),
						'id'			 => $prefix . 'layout-excerpt-length',
						'type'		 => 'colorpicker',
						'default'	 => '#336699',
						'desc'		 => __( 'Text to display for read more.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Excerpt length type', 'pzucd' ),
						'id'			 => $prefix . 'layout-excerpt-length-type',
						'type'		 => 'select',
						'default'	 => 'characters',
						'options'	 => array(
								array( 'value'	 => 'characters', 'text'	 => 'Characters' ),
								array( 'value'	 => 'words', 'text'	 => 'Words' ),
						),
						'desc'		 => __( 'Text to display for read more.', 'pzucd' )
				),
		);
		/*		 * **************
		 * META
		 * ************** */
		$meta_box_layout[ 'tabs' ][ $i++ ][ 'fields' ] = array(
				array(
						'label'		 => __( 'Meta', 'pzucd' ),
						'id'			 => $prefix . 'layout-meta-head',
						'type'		 => 'heading',
						'default'	 => '',
						'desc'		 => __( '', 'pzucd' )
				),
				array(
						'label'		 => __( 'Meta 1 area width', 'pzucd' ),
						'id'			 => $prefix . 'layout-meta1-width',
						'type'		 => 'readonly',
						'class'		 => 'pzucd-zone-percent',
						'alt'			 => 'meta1',
						'default'	 => '100',
						'desc'		 => __( 'Set the meta 1 width as a percentage of the cell width.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Meta 2 area width', 'pzucd' ),
						'id'			 => $prefix . 'layout-meta2-width',
						'type'		 => 'readonly',
						'class'		 => 'pzucd-zone-percent',
						'alt'			 => 'meta2',
						'default'	 => '100',
						'desc'		 => __( 'Set the meta 2 width as a percentage of the cell width.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Meta 3 area width', 'pzucd' ),
						'id'			 => $prefix . 'layout-meta3-width',
						'type'		 => 'readonly',
						'class'		 => 'pzucd-zone-percent',
						'alt'			 => 'meta3',
						'default'	 => '100',
						'desc'		 => __( 'Set the meta 3 width as a percentage of the cell width.', 'pzucd' )
				),
		);

		/*		 * **************
		 * IMAGE
		 * ************** */
		$meta_box_layout[ 'tabs' ][ $i++ ][ 'fields' ] = array(
				array(
						'label'		 => __( 'Image', 'pzucd' ),
						'id'			 => $prefix . 'layout-image-head',
						'type'		 => 'heading',
						'default'	 => '',
						'desc'		 => __( '', 'pzucd' )
				),
				array(
						'label'		 => __( 'Image area width', 'pzucd' ),
						'id'			 => $prefix . 'layout-image-width',
						'type'		 => 'readonly',
						'class'		 => 'pzucd-zone-percent',
						'alt'			 => 'image',
						'default'	 => '100',
						'desc'		 => __( 'Set the image width as a percentage of the cell width.', 'pzucd' )
				),
		);

		/*		 * **************
		 * FORMAT
		 * ************** */

		$meta_box_layout[ 'tabs' ][ $i++ ][ 'fields' ] = array(
				array(
						'label'		 => __( 'Gutter width (%)', 'pzucd' ),
						'id'			 => $prefix . 'layout-gutter-width',
						'type'		 => 'numeric',
						'alt'			 => 'gutter',
						'default'	 => '1',
						'min'			 => '1',
						'max'			 => '100',
						'step'		 => '1',
						'desc'		 => __( 'Set the gutter width as a percentage of the cell width. The gutter is the gap between adjoining elements', 'pzucd' )
				),
				array(
						'label'		 => __( 'CSS instructions', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-instructions',
						'type'		 => 'infobox',
						'default'	 => '',
						'desc'		 => __( 'Enter CSS declarations, such as: background:#123; color:#abc; font-size:1.6em; padding:1%;', 'pzucd' ) . '<br/>' . __( 'As much as possible, use fluid units (%,em) if you want to ensure maximum responsiveness.', 'pzucd' ) . '<br/>' .
						__( 'The base font size is 10px. So, for example, to get a font size of 14px, use 1.4em. Even better is using relative ems i.e. rem.' )
				),
				array(
						'label'		 => __( 'Cells', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-cells',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: .pzucd_cells',
						'desc'		 => __( 'Format the cells', 'pzucd' )
				),
				array(
						'label'		 => __( 'Components group', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-components-group',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: .pzucd_components',
						'desc'		 => __( 'Format the components group', 'pzucd' )
				),
				array(
						'label'		 => __( 'Entry title', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-entry-title',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: .pzucd_entry_title and .pzucd_entry_title a',
						'desc'		 => __( 'Format the entry title', 'pzucd' )
				),
				array(
						'label'		 => __( 'Entry title hover', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-entry-title-hover',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: .pzucd_entry_title a:hover',
						'desc'		 => __( 'Format the entry title link hover', 'pzucd' )
				),
				array(
						'label'		 => __( 'Entry meta', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-entry-meta',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: .pzucd_entry_meta',
						'desc'		 => __( 'Format the entry meta', 'pzucd' )
				),
				array(
						'label'		 => __( 'Entry meta links', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-entry-meta-link',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: .pzucd_entry_meta a',
						'desc'		 => __( 'Format the entry meta link', 'pzucd' )
				),
				array(
						'label'		 => __( 'Entry meta link hover', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-entry-meta-link-hover',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: .pzucd_entry_meta a:hover',
						'desc'		 => __( 'Format the entry meta link hover', 'pzucd' )
				),
				array(
						'label'		 => __( 'Entry content', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-entry-content',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: .pzucd_entry_content',
						'desc'		 => __( 'Format the entry content', 'pzucd' )
				),
				array(
						'label'		 => __( 'Entry content links', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-entry-content-links',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: .pzucd_entry_content a',
						'desc'		 => __( 'Format the entry content', 'pzucd' )
				),
				array(
						'label'		 => __( 'Entry content link hover', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-entry-content-link-hover',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: .pzucd_entry_content a:hover',
						'desc'		 => __( 'Format the entry content link hover', 'pzucd' )
				),
				array(
						'label'		 => __( 'Entry featured image', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-entry-fimage',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: .pzucd_entry_featured_image',
						'desc'		 => __( 'Format the entry featured image', 'pzucd' )
				),
				array(
						'label'		 => __( 'Read more', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-entry-readmore',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: a.pzucd_readmore',
						'desc'		 => __( 'Format the content "Read more" link', 'pzucd' )
				),
				array(
						'label'		 => __( 'Read more hover', 'pzucd' ),
						'id'			 => $prefix . 'layout-format-entry-readmore-hover',
						'type'		 => 'textarea',
						'rows'		 => 1,
						'default'	 => '',
						'help'		 => 'Declarations only for class: a.pzucd_readmore:hover',
						'desc'		 => __( 'Format the content "Read more" link hover', 'pzucd' )
				),
		); // End of settings array

		return $meta_box_layout;
	}

	/**
	 * [draw_cell_layout description]
	 * @return [type] [description]
	 */
	function draw_cell_layout()
	{
		$return_html = '';

		// Put in a hidden field with the plugin url for use in js
		$return_html = '
			<div id="pzucd-custom-pzucd_layout-layout" class="pzucd-custom">
				<div id="pzucd-dropzone-pzucd_layout-layout" class="pzucd-dropzone">
					<div class="pzgp-cell-image-behind"></div>
					<div class="pzucd-zone-control ui-sortable">
					
					<span class="pzucd-dropped pzucd-dropped-title" title="Post title" data-idcode="%title%" style="display: inline-block; font-weight: bold; font-size: 15px; background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;">This is the title</span>
					
					<span class="pzucd-dropped pzucd-dropped-meta1 pzucd-dropped-meta" title="Meta info 1" data-idcode="%meta1%" style="font-size: 11px; background-color: rgb(204, 170, 170); background-position: initial initial; background-repeat: initial initial;">Jan 1 2013</span>

					<span class="pzucd-dropped pzucd-dropped-excerpt" title="Excerpt with featured image" data-idcode="%excerpt%" style="font-size: 13px;"><img src="' . PZUCD_PLUGIN_URL . '/images/sample-image.jpg" style="max-width:20%;float:right;padding:2px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span>

					<span class="pzucd-dropped pzucd-dropped-content" title="Full post content" data-idcode="%content%" style="font-size: 13px; display: none;"><img src="' . PZUCD_PLUGIN_URL . '/images/sample-image.jpg" style="max-width:30%;float:left;padding:5px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;•&nbsp;Cras semper sem hendrerit</li><li>&nbsp;•&nbsp;Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><p>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </p><p>Donec dictum leo at erat mattis sollicitudin. Nunc vulputate nisl suscipit enim adipiscing faucibus. Ut faucibus sem non sapien rutrum gravida. Maecenas pharetra mi et velit posuere ac elementum mi tincidunt. Nullam tristique tempus odio id rutrum. Nam ligula urna, semper eget elementum nec, euismod at tortor. Duis commodo, purus id posuere aliquam, orci felis facilisis odio, ac sagittis mi nisl at nibh. Sed non risus eu quam euismod faucibus.</p><p>Proin mattis convallis scelerisque. Curabitur auctor felis id sapien dictum vehicula. Aenean euismod porttitor dictum. Vestibulum nulla leo, volutpat quis tempus eu, accumsan eget ante.</p></span>

					<span class="pzucd-dropped pzucd-dropped-image" title="Featured image" data-idcode="%image%" style="max-height: 100px; overflow: hidden;"><img src="' . PZUCD_PLUGIN_URL . '/images/sample-image.jpg" style="max-width:100%;"></span>


					<span class="pzucd-dropped pzucd-dropped-meta2 pzucd-dropped-meta" title="Meta info 2" data-idcode="%meta2%" style="font-size: 11px; background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;">Categories - News, Sport</span>

					<span class="pzucd-dropped pzucd-dropped-meta3 pzucd-dropped-meta" title="Meta info 3" data-idcode="%meta3%" style="font-size: 11px; background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;">Comments: 27</span>
				</div>
			</div>


					<span class="howto">Drag and drop to sort the order of your elements.<br>Heights are fluid in cells, so not indicative of how it will look on the page.<br>
		            	<strong style="color:#d00">This is an example only and thus only a <span style="border-bottom: 3px double;">general guide</span> to how the cells will look.</strong>
		      </span>
		      </div>
					<div class="plugin_url" style="display:none;">' . PZUCD_PLUGIN_URL . '</div>
		      ';

		return $return_html;
	}

	/**
	 * 
	 * @return type
	 */
	function layout_defaults()
	{
		$pzucd_layout_defaults = array( );
		$this->populate_layout_options();
		foreach ( $this->meta_box_layout[ 'tabs' ] as $pzucd_meta_box )
		{
			foreach ( $pzucd_meta_box[ 'fields' ] as $pzucd_field )
			{
				if ( !isset( $pzucd_field[ 'id' ] ) )
				{
					$pzucd_layout_defaults[ $pzucd_field[ 'id' ] ] = (isset( $pzucd_field[ 'default' ] ) ? $pzucd_field[ 'default' ] : null);
				}
			}
		}
		return $pzucd_layout_defaults;
	}

}

// EOC