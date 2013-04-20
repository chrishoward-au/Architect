<?php

class pzucd_Data_Selection extends pzucdForm
{

	private $mb_fields;

	/**
	 * [__construct description]
	 */
	function __construct()
	{
		add_action( 'init', array( $this, 'create_data_selections_post_type' ) );
		// This overrides the one in the patent class

		if ( is_admin() )
		{

			//	add_action('admin_init', 'pzucd_preview_meta');
			add_action( 'add_meta_boxes', array( $this, 'data_selections_meta' ) );
			add_action( 'admin_head', array( $this, 'data_selection_admin_head' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'data_selection_admin_enqueue' ) );
			add_filter( 'manage_ucd-data_selections_posts_columns', array( $this, 'add_data_selection_columns' ) );
			add_action( 'manage_ucd-data_selections_posts_custom_column', array( $this, 'add_data_selection_column_content' ), 10, 2 );

			// check screen ucd-data_selections. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'ucd-data_selections' )
//			{
//			}
		}

		// Need to do this here so save knows about the data_selection. 
		$this->mb_fields = self::_populate_data_selection_options();
		add_action( 'save_post', array( $this, 'save_data' ) );
	}

	/**
	 * [data_selection_admin_enqueue description]
	 * @param  [type] $hook [description]
	 * @return [type]       [description]
	 */
	public function data_selection_admin_enqueue( $hook )
	{
		$screen = get_current_screen();
		if ( 'ucd-data_selections' == $screen->id )
		{


			wp_enqueue_style( 'pzucd-admin-data_selections-css', PZUCD_PLUGIN_URL . '/admin/css/ucd-admin-data_selections.css' );

			wp_enqueue_script( 'jquery-pzucd-metaboxes-data_selections', PZUCD_PLUGIN_URL . '/admin/js/ucd-metaboxes-data_selections.js', array( 'jquery' ) );
		}
	}

	/**
	 * [data_selection_admin_head description]
	 * @return [type] [description]
	 */
	public function data_selection_admin_head()
	{
		
	}

	/**
	 * [add_data_selection_columns description]
	 * @param [type] $columns [description]
	 */
	public function add_data_selection_columns( $columns )
	{
		unset( $columns[ 'thumbnail' ] );
		$pzucd_front	 = array_slice( $columns, 0, 2 );
		$pzucd_back		 = array_slice( $columns, 2 );
		$pzucd_insert	 =
						array
								(
								'pzucd_data_selection_short_name' => __( 'Criteria short name', 'pzsp' ),
		);

		return array_merge( $pzucd_front, $pzucd_insert, $pzucd_back );
	}

	/**
	 * [add_data_selection_column_content description]
	 * @param [type] $column  [description]
	 * @param [type] $post_id [description]
	 */
	public function add_data_selection_column_content( $column, $post_id )
	{
		switch ( $column )
		{
			case 'pzucd_short_name':
				echo get_post_meta( $post_id, 'pzucd_data_selection_short-name', true );
				break;
		}
	}

	/**
	 * [create_data_selections_post_type description]
	 * @return [type] [description]
	 */
	public function create_data_selections_post_type()
	{
		$labels = array(
				'name'							 => _x( 'Criterias', 'post type general name' ),
				'singular_name'			 => _x( 'Criteria', 'post type singular name' ),
				'add_new'						 => __( 'Add New Criteria' ),
				'add_new_item'			 => __( 'Add New Criteria' ),
				'edit_item'					 => __( 'Edit Criteria' ),
				'new_item'					 => __( 'New Criteria' ),
				'view_item'					 => __( 'View Criteria' ),
				'search_items'			 => __( 'Search Criterias' ),
				'not_found'					 => __( 'No Criteria found' ),
				'not_found_in_trash' => __( 'No Criterias found in Trash' ),
				'parent_item_colon'	 => '',
				'menu_name'					 => _x( 'Criteria', 'pzucd-data_selection-designer' ),
		);

		$args = array(
				'labels'							 => $labels,
				'description'					 => __( 'Ultimate Content Display Criteria are used to create reusable Criteria for use in your UCD blocks, widgets, shortcodes and WP template tags.' ),
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
				'register_meta_box_cb' => array( $this, 'data_selections_meta' )
		);

		register_post_type( 'ucd-data_selections', $args );
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
	 * [data_selections_meta description]
	 * @return [type] [description]
	 */
	public function data_selections_meta()
	{
		// global $this->meta_box_data_selection;
		// Fill any defaults if necessary
//		$this->data_selection_form = self::data_selection_defaults();
		add_meta_box(
						$this->mb_fields[ 'id' ], $this->mb_fields[ 'title' ], array( $this, 'draw_meta_box' ), $this->mb_fields[ 'page' ], $this->mb_fields[ 'context' ], $this->mb_fields[ 'priority' ], $this->mb_fields
		);
	}

// End data_selections_meta
	// cpt = custom post type
	/**
	 * [populate_data_selection_options description]
	 * @return [type] [description]
	 */
	private function _populate_data_selection_options()
	{
		//		global $this->meta_box_data_selection;

		$prefix					 = 'pzucd_';
		/*
		 *
		 * Setup data_selections extra fields
		 *
		 */
		$i							 = 0;
		$meta_box_data_selection = array(
				'id'					 => 'pzucd-data_selections-id',
				'title'				 => 'data_selection Designer',
				'page'				 => 'ucd-data_selections',
				'context'			 => 'normal',
				'priority'		 => 'high',
				'orientation'	 => 'horizontal',
				'tabs'				 => array(
						$i++ => array(
								'icon'	 => 'general-65grey.png',
								'label'	 => __( 'General', 'pzucd' ),
								'id'		 => $prefix . 'tab_data_selections_general',
								'type'	 => 'tab',
						),
						$i++ => array(
							'icon'	 => 'layout1-65grey.png',
							'label'	 => __( 'Structure', 'pzucd' ),
							'id'		 => $prefix . 'tab_data_selections_structure',
							'type'	 => 'tab',
						),
						$i++ => array(
							'icon'	 => 'transitions-65grey.png',
							'label'	 => __( 'Controls', 'pzucd' ),
							'id'		 => $prefix . 'tab_data_selections_controls',
							'type'	 => 'tab',
						),
				)
		);

		$i = 0;

		/*		 * **************
		 * RESPONSIVE
		 * *************** */
		$meta_box_data_selection[ 'tabs' ][ $i++ ][ 'fields' ] = array(
				array(
						'label'		 => __( 'General', 'pzucd' ),
						'id'			 => $prefix . 'data_selection-general',
						'type'		 => 'heading',
						'default'	 => '',
						'desc'		 => __( '', 'pzucd' )
				),
				array(
						'label'		 => __( 'data_selection set name', 'pzucd' ),
						'id'			 => $prefix . 'data_selection-set-name',
						'type'		 => 'text',
						'default'	 => '',
						'desc'		 => __( '.', 'pzucd' ),
						'help'		 => __( 'Create sets of data_selections with each data_selection in a set for different screen dimensions' )
				),
				array(
						'label'		 => __( 'data_selection short name ', 'pzucd' ),
						'id'			 => $prefix . 'data_selection-short-name',
						'type'		 => 'text',
						'default'	 => '',
						'desc'		 => __( '.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Display', 'pzucd' ),
						'id'			 => $prefix . 'data_selection-display',
						'type'		 => 'text',
						'default'	 => '',
						'desc'		 => __( 'Generic label to help you remember the primary device you expect this data_selection to show on.', 'pzucd' ),
				),
				array(
						'label'		 => __( 'Minimum display width (px)', 'pzucd' ),
						'id'			 => $prefix . 'data_selection-min-display-width',
						'type'		 => 'text',
						'default'	 => '0',
						'desc'		 => __( '.', 'pzucd' ),
				),
				array(
						'label'		 => __( 'Maximum display width (px)', 'pzucd' ),
						'id'			 => $prefix . 'data_selection-max-display-width',
						'type'		 => 'text',
						'default'	 => '320',
						'desc'		 => __( '.', 'pzucd' ),
				),
		);
		$meta_box_data_selection[ 'tabs' ][ $i++ ][ 'fields' ] = array(
			array(
				'label'		 => __( 'Structure', 'pzucd' ),
				'id'			 => $prefix . 'data_selection-structure',
				'type'		 => 'heading',
				'default'	 => '',
				'desc'		 => __( '', 'pzucd' )
			),
			array(
				'label'		 => __( 'Cells per view', 'pzucd' ),
				'id'			 => $prefix . 'data_selection-cells-per-view',
				'type'		 => 'spinner',
				'default'	 => '9',
				'suffix' => '',
				'desc'		 => __( '.', 'pzucd' ),
				'help'		 => __( '' )
			),
			array(
				'label'		 => __( 'Number of cells across', 'pzucd' ),
				'id'			 => $prefix . 'data_selection-cells-across',
				'type'		 => 'spinner',
				'default'	 => '3',
				'suffix' => '',
				'desc'		 => __( '.', 'pzucd' ),
				'help'		 => __( '' )
			),
			array(
				'label'		 => __( 'Gutter width (%)', 'pzucd' ),
				'id'			 => $prefix . 'layout-gutter-width',
				'type'		 => 'spinner',
				'alt'			 => 'gutter',
				'default'	 => '1',
				'min'			 => '1',
				'max'			 => '100',
				'step'		 => '1',
				'suffix' => '%',
				'desc'		 => __( 'Set the gutter width as a percentage of the cell width. The gutter is the gap between adjoining elements', 'pzucd' )
			),
		);
		$meta_box_data_selection[ 'tabs' ][ $i++ ][ 'fields' ] = array(
			array(
				'label'		 => __( 'Controls', 'pzucd' ),
				'id'			 => $prefix . 'data_selection-controls-heading',
				'type'		 => 'heading',
				'default'	 => '',
				'desc'		 => __( '', 'pzucd' )
			),
			array(
				'label'		 => __( 'Control positions', 'pzucd' ),
				'id'			 => $prefix . 'control-positions',
				'type'		 => 'multicheck',
				'default'	 => array( 'bottom' ),
				'options'	 => array(
					array( 'value'	 => 'top', 'text'	 => 'Top' ),
					array( 'value'	 => 'bottom', 'text'	 => 'Bottom' ),
					array( 'value'	 => 'left', 'text'	 => 'Left' ),
					array( 'value'	 => 'right', 'text'	 => 'Right' ),
				),
				'desc'		 => __( 'Choose where to display controls', 'pzucd' )
			),

			array(
				'label'		 => __( 'Top', 'pzucd' ),
				'id'			 => $prefix . 'data_selection-controls--heading-top',
				'type'		 => 'heading',
				'default'	 => '',
				'desc'		 => __( '', 'pzucd' )
			),
			array(
				'label'		 => __( 'Type', 'pzucd' ),
				'id'			 => $prefix . 'data_selection-top-control-type',
				'type'		 => 'text',
				'default'	 => '',
				'desc'		 => __( '.', 'pzucd' ),
				'help'		 => __( 'Top control type: Pagination, pager, navigation, thumbs, titles, ' )
			),
			array(
				'label'		 => __( 'Position', 'pzucd' ),
				'id'			 => $prefix . 'data_selection-top-control-position',
				'type'		 => 'text',
				'default'	 => '',
				'desc'		 => __( '.', 'pzucd' ),
				'help'		 => __( 'Top control position: Inside, Outside' )
			),



		);

		return $meta_box_data_selection;
	}


	/**
	 * 
	 * @return type
	 */
	/*
	function data_selection_defaults()
	{
		$pzucd_data_selection_defaults = array( );
		$this->populate_data_selection_options();
		foreach ( $this->meta_box_data_selection[ 'tabs' ] as $pzucd_meta_box )
		{
			foreach ( $pzucd_meta_box[ 'fields' ] as $pzucd_field )
			{
				if ( !isset( $pzucd_field[ 'id' ] ) )
				{
					$pzucd_data_selection_defaults[ $pzucd_field[ 'id' ] ] = (isset( $pzucd_field[ 'default' ] ) ? $pzucd_field[ 'default' ] : null);
				}
			}
		}
		return $pzucd_data_selection_defaults;
	}
*/
}

// EOC