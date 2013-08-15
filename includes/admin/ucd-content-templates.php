<?php

class pzucd_Content_templates extends pzucdForm
{

	private $mb_fields;

	/**
	 * [__construct description]
	 */
	function __construct()
	{
		add_action( 'init', array( $this, 'create_templates_post_type' ) );
		// This overrides the one in the patent class

		if ( is_admin() )
		{

			//	add_action('admin_init', 'pzucd_preview_meta');
			add_action( 'add_meta_boxes', array( $this, 'templates_meta' ) );
			add_action( 'admin_head', array( $this, 'content_templates_admin_head' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'content_templates_admin_enqueue' ) );
			add_filter( 'manage_ucd-templates_posts_columns', array( $this, 'add_template_columns' ) );
			add_action( 'manage_ucd-templates_posts_custom_column', array( $this, 'add_template_column_content' ), 10, 2 );

			// check screen ucd-templates. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'ucd-templates' )
//			{
//			}
		}

		// Need to do this here so save knows about the template. 
		$this->mb_fields = self::_populate_template_options();
		add_action( 'save_post', array( $this, 'save_data' ) );
	}

	/**
	 * [content_templates_admin_enqueue description]
	 * @param  [type] $hook [description]
	 * @return [type]       [description]
	 */
	public function content_templates_admin_enqueue( $hook )
	{
		$screen = get_current_screen();
		if ( 'ucd-templates' == $screen->id )
		{


			wp_enqueue_style( 'pzucd-admin-templates-css', PZUCD_PLUGIN_URL . '/includes/admin/css/ucd-admin-templates.css' );

			wp_enqueue_script( 'jquery-pzucd-metaboxes-templates', PZUCD_PLUGIN_URL . '/includes/admin/js/ucd-metaboxes-templates.js', array( 'jquery' ) );
		}
	}

	/**
	 * [content_templates_admin_head description]
	 * @return [type] [description]
	 */
	public function content_templates_admin_head()
	{
		
	}

	/**
	 * [add_template_columns description]
	 * @param [type] $columns [description]
	 */
	public function add_template_columns( $columns )
	{
		unset( $columns[ 'thumbnail' ] );
		$pzucd_front	 = array_slice( $columns, 0, 2 );
		$pzucd_back		 = array_slice( $columns, 2 );
		$pzucd_insert	 =
						array
								(
								'pzucd_template_short_name' => __( 'Template short name', 'pzsp' ),
		);

		return array_merge( $pzucd_front, $pzucd_insert, $pzucd_back );
	}

	/**
	 * [add_template_column_content description]
	 * @param [type] $column  [description]
	 * @param [type] $post_id [description]
	 */
	public function add_template_column_content( $column, $post_id )
	{
		switch ( $column )
		{
			case 'pzucd_short_name':
				echo get_post_meta( $post_id, 'pzucd_template_short-name', true );
				break;
		}
	}

	/**
	 * [create_templates_post_type description]
	 * @return [type] [description]
	 */
	public function create_templates_post_type()
	{
		$labels = array(
				'name'							 => _x( 'Templates', 'post type general name' ),
				'singular_name'			 => _x( 'Template', 'post type singular name' ),
				'add_new'						 => __( 'Add New template' ),
				'add_new_item'			 => __( 'Add New template' ),
				'edit_item'					 => __( 'Edit template' ),
				'new_item'					 => __( 'New template' ),
				'view_item'					 => __( 'View template' ),
				'search_items'			 => __( 'Search templates' ),
				'not_found'					 => __( 'No templates found' ),
				'not_found_in_trash' => __( 'No templates found in Trash' ),
				'parent_item_colon'	 => '',
				'menu_name'					 => _x( 'Templates', 'pzucd-template-designer' ),
		);

		$args = array(
				'labels'							 => $labels,
				'description'					 => __( 'Ultimate Content Display templates are used to create reusable templates for use in your UCD blocks, widgets, shortcodes and WP template tags.' ),
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
				'menu_position'				 => 30,
				'supports'						 => array( 'title', 'revisions' ),
				'exclude_from_search'	 => true,
				'register_meta_box_cb' => array( $this, 'templates_meta' )
		);

		register_post_type( 'ucd-templates', $args );
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
	 * [templates_meta description]
	 * @return [type] [description]
	 */
	public function templates_meta()
	{
		// global $this->meta_box_template;
		// Fill any defaults if necessary
//		$this->template_form = self::template_defaults();
		add_meta_box(
						$this->mb_fields[ 'id' ], $this->mb_fields[ 'title' ], array( $this, 'draw_meta_box' ), $this->mb_fields[ 'page' ], $this->mb_fields[ 'context' ], $this->mb_fields[ 'priority' ], $this->mb_fields
		);
	}

// End templates_meta
	// cpt = custom post type
	/**
	 * [populate_template_options description]
	 * @return [type] [description]
	 */
	private function _populate_template_options()
	{
		//		global $this->meta_box_template;

		$prefix					 = 'pzucd_';
		/*
		 *
		 * Setup templates extra fields
		 *
		 */
		$i							 = 0;
		$meta_box_template = array(
				'id'					 => 'pzucd-templates-id',
				'title'				 => 'Template Designer',
				'page'				 => 'ucd-templates',
				'context'			 => 'normal',
				'priority'		 => 'high',
				'orientation'	 => 'horizontal',
				'tabs'				 => array(
						$i++ => array(
								'icon'	 => 'entypo-window',
								'label'	 => __( 'General', 'pzucd' ),
								'id'		 => $prefix . 'tab_templates_general',
								'type'	 => 'tab',
						),
						$i++ => array(
							'icon'	 => 'fontawesome-reorder',
							'label'	 => __( 'Structure', 'pzucd' ),
							'id'		 => $prefix . 'tab_templates_structure',
							'type'	 => 'tab',
						),
						$i++ => array(
							'icon'	 => 'fontawesome-forward',
							'label'	 => __( 'Navigation', 'pzucd' ),
							'id'		 => $prefix . 'tab_templates_controls',
							'type'	 => 'tab',
						),
						$i++ => array(
							'icon'	 => 'fontawesome-forward',
							'label'	 => __( 'Pager', 'pzucd' ),
							'id'		 => $prefix . 'tab_templates_controls',
							'type'	 => 'tab',
						),
				)
		);

		$i = 0;

		/*		 * **************
		 * RESPONSIVE
		 * *************** */
		$meta_box_template[ 'tabs' ][ $i++ ][ 'fields' ] = array(
				array(
						'label'		 => __( 'General', 'pzucd' ),
						'id'			 => $prefix . 'template-general',
						'type'		 => 'heading',
						'default'	 => '',
						'desc'		 => __( '', 'pzucd' )
				),
				array(
						'label'		 => __( 'Template set name', 'pzucd' ),
						'id'			 => $prefix . 'template-set-name',
						'type'		 => 'text',
						'default'	 => '',
						'desc'		 => __( '.', 'pzucd' ),
						'help'		 => __( 'Create sets of templates with each template in a set for different screen dimensions' )
				),
				array(
						'label'		 => __( 'Template short name ', 'pzucd' ),
						'id'			 => $prefix . 'template-short-name',
						'type'		 => 'text',
						'default'	 => '',
						'desc'		 => __( '.', 'pzucd' )
				),
				array(
						'label'		 => __( 'Display', 'pzucd' ),
						'id'			 => $prefix . 'template-display',
						'type'		 => 'text',
						'default'	 => '',
						'desc'		 => __( 'Generic label to help you remember the primary device you expect this template to show on.', 'pzucd' ),
				),
				array(
						'label'		 => __( 'Minimum display width (px)', 'pzucd' ),
						'id'			 => $prefix . 'template-min-display-width',
						'type'		 => 'text',
						'default'	 => '0',
						'desc'		 => __( '.', 'pzucd' ),
				),
				array(
						'label'		 => __( 'Maximum display width (px)', 'pzucd' ),
						'id'			 => $prefix . 'template-max-display-width',
						'type'		 => 'text',
						'default'	 => '320',
						'desc'		 => __( '.', 'pzucd' ),
				),
		);
		$meta_box_template[ 'tabs' ][ $i++ ][ 'fields' ] = array(
			array(
				'label'		 => __( 'Structure', 'pzucd' ),
				'id'			 => $prefix . 'template-structure',
				'type'		 => 'heading',
				'default'	 => '',
				'desc'		 => __( '', 'pzucd' )
			),
			array(
				'label'		 => __( 'Cells per view', 'pzucd' ),
				'id'			 => $prefix . 'template-cells-per-view',
				'type'		 => 'spinner',
				'default'	 => '9',
				'suffix' => '',
				'desc'		 => __( '.', 'pzucd' ),
				'help'		 => __( '' )
			),
			array(
				'label'		 => __( 'Number of cells across', 'pzucd' ),
				'id'			 => $prefix . 'template-cells-across',
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

		/// Navigation
		$meta_box_template[ 'tabs' ][ $i++ ][ 'fields' ] = array(
			array(
				'label'		 => __( 'Navigation', 'pzucd' ),
				'id'			 => $prefix . 'template-controls-heading',
				'type'		 => 'heading',
				'default'	 => '',
				'desc'		 => __( 'Navigation controls single cells. Used, for example, in sliders and tabs.', 'pzucd' )
			),
			array(
				'label'		 => __( 'Navigation positions', 'pzucd' ),
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
				'id'			 => $prefix . 'template-controls--heading-top',
				'type'		 => 'heading',
				'default'	 => '',
				'desc'		 => __( '', 'pzucd' )
			),
			array(
				'label'		 => __( 'Type', 'pzucd' ),
				'id'			 => $prefix . 'template-top-control-type',
				'type'		 => 'text',
				'default'	 => '',
				'desc'		 => __( '.', 'pzucd' ),
				'help'		 => __( 'Top control type: navigation, thumbs, titles, bullets' )
			),
			array(
				'label'		 => __( 'Position', 'pzucd' ),
				'id'			 => $prefix . 'template-top-control-position',
				'type'		 => 'text',
				'default'	 => '',
				'desc'		 => __( '.', 'pzucd' ),
				'help'		 => __( 'Top control position: Inside, Outside' )
			),
		);
			$meta_box_template[ 'tabs' ][ $i++ ][ 'fields' ] = array(
				array(
					'label'		 => __( 'Pager', 'pzucd' ),
					'id'			 => $prefix . 'template-pager-heading',
					'type'		 => 'heading',
					'default'	 => '',
					'desc'		 => __( 'Pagers control navigation between pages of content. Sometimes these may be a single cell, and sometimes a grid of cells.', 'pzucd' )
				),
				array(
					'label'		 => __( 'Pager positions', 'pzucd' ),
					'id'			 => $prefix . 'pager-positions',
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
					'id'			 => $prefix . 'template-pager--heading-top',
					'type'		 => 'heading',
					'default'	 => '',
					'desc'		 => __( '', 'pzucd' )
				),
				array(
					'label'		 => __( 'Type', 'pzucd' ),
					'id'			 => $prefix . 'template-top-pager-type',
					'type'		 => 'text',
					'default'	 => '',
					'desc'		 => __( '.', 'pzucd' ),
					'help'		 => __( 'Top control type: Pagination, pager' )
				),
				array(
					'label'		 => __( 'Position', 'pzucd' ),
					'id'			 => $prefix . 'template-top-pager-position',
					'type'		 => 'text',
					'default'	 => '',
					'desc'		 => __( '.', 'pzucd' ),
					'help'		 => __( 'Top control position: Inside, Outside' )
				),



		);

		return $meta_box_template;
	}


	/**
	 * 
	 * @return type
	 */
	/*
	function template_defaults()
	{
		$pzucd_template_defaults = array( );
		$this->populate_template_options();
		foreach ( $this->meta_box_template[ 'tabs' ] as $pzucd_meta_box )
		{
			foreach ( $pzucd_meta_box[ 'fields' ] as $pzucd_field )
			{
				if ( !isset( $pzucd_field[ 'id' ] ) )
				{
					$pzucd_template_defaults[ $pzucd_field[ 'id' ] ] = (isset( $pzucd_field[ 'default' ] ) ? $pzucd_field[ 'default' ] : null);
				}
			}
		}
		return $pzucd_template_defaults;
	}
*/
}

// EOC