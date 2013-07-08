<?php

class pzucd_Criteria extends pzucdForm {

	private $mb_fields;

	/**
	 * [__construct description]
	 */
	function __construct() {
		add_action('init', array($this, 'create_criterias_post_type'));
		// This overrides the one in the patent class

		if (is_admin()) {

			//	add_action('admin_init', 'pzucd_preview_meta');
			add_action('add_meta_boxes', array($this, 'criterias_meta'));
			add_action('admin_head', array($this, 'criteria_admin_head'));
			add_action('admin_enqueue_scripts', array($this, 'criteria_admin_enqueue'));
			add_filter('manage_ucd-criterias_posts_columns', array($this, 'add_criteria_columns'));
			add_action('manage_ucd-criterias_posts_custom_column', array($this, 'add_criteria_column_content'), 10, 2);

			// check screen ucd-criterias. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'ucd-criterias' )
//			{
//			}
		}

		// Need to do this here so save knows about the criteria. 
		$this->mb_fields = self::_populate_criteria_options();
		add_action('save_post', array($this, 'save_data'));
	}

	/**
	 * [criteria_admin_enqueue description]
	 * @param  [type] $hook [description]
	 * @return [type]       [description]
	 */
	public function criteria_admin_enqueue($hook) {
		$screen = get_current_screen();
		if ('ucd-criterias' == $screen->id) {


			wp_enqueue_style('pzucd-admin-criterias-css', PZUCD_PLUGIN_URL . '/code/admin/css/ucd-admin-criterias.css');

			wp_enqueue_script('jquery-pzucd-metaboxes-criterias', PZUCD_PLUGIN_URL . '/code/admin/js/ucd-metaboxes-criterias.js', array('jquery'));
		}
	}

	/**
	 * [criteria_admin_head description]
	 * @return [type] [description]
	 */
	public function criteria_admin_head() {
		
	}

	/**
	 * [add_criteria_columns description]
	 * @param [type] $columns [description]
	 */
	public function add_criteria_columns($columns) {
		unset($columns['thumbnail']);
		$pzucd_front = array_slice($columns, 0, 2);
		$pzucd_back = array_slice($columns, 2);
		$pzucd_insert =
						array
								(
								'pzucd_criteria_short_name' => __('Criteria short name', 'pzsp'),
		);

		return array_merge($pzucd_front, $pzucd_insert, $pzucd_back);
	}

	/**
	 * [add_criteria_column_content description]
	 * @param [type] $column  [description]
	 * @param [type] $post_id [description]
	 */
	public function add_criteria_column_content($column, $post_id) {
		switch ($column) {
			case 'pzucd_short_name':
				echo get_post_meta($post_id, 'pzucd_criteria_short-name', true);
				break;
		}
	}

	/**
	 * [create_criterias_post_type description]
	 * @return [type] [description]
	 */
	public function create_criterias_post_type() {
		$labels = array(
				'name' => _x('Content selections', 'post type general name'),
				'singular_name' => _x('Content selection', 'post type singular name'),
				'add_new' => __('Add New Content selection'),
				'add_new_item' => __('Add New Content selection'),
				'edit_item' => __('Edit Content selection'),
				'new_item' => __('New Content selection'),
				'view_item' => __('View Content selection'),
				'search_items' => __('Search Content selections'),
				'not_found' => __('No Content selection found'),
				'not_found_in_trash' => __('No Content selections found in Trash'),
				'parent_item_colon' => '',
				'menu_name' => _x('Content', 'pzucd-criteria-designer'),
		);

		$args = array(
				'labels' => $labels,
				'description' => __('Ultimate Content Display Content selection are used to create reusable Criteria for use in your UCD blocks, widgets, shortcodes and WP template tags.'),
				'public' => false,
				'publicly_queryable' => false,
				'show_ui' => true,
				'show_in_menu' => 'pzucd',
				'show_in_nav_menus' => false,
				'query_var' => true,
				'rewrite' => true,
				'capability_type' => 'post',
				'has_archive' => false,
				'hierarchical' => false,
				'menu_position' => 20,
				'supports' => array('title', 'revisions'),
				'exclude_from_search' => true,
				'register_meta_box_cb' => array($this, 'criterias_meta')
		);

		register_post_type('ucd-criterias', $args);
	}

	public function save_data($post_id) {
//		var_dump( 'SAVE', count( $this->mb_fields ) );
		parent::save_data($post_id, $this->mb_fields);
	}

	public function draw_meta_box($postobj, $callback_args) {
//		var_dump( 'DRAW', count( $this->mb_fields ) );
		parent::show_meta_box($postobj, $callback_args);

		//	$this->mb_fields = $callback_args;
	}

	/**
	 * [criterias_meta description]
	 * @return [type] [description]
	 */
	public function criterias_meta() {
		// global $this->meta_box_criteria;
		// Fill any defaults if necessary
//		$this->criteria_form = self::criteria_defaults();
		add_meta_box(
						$this->mb_fields['id'], $this->mb_fields['title'], array($this, 'draw_meta_box'), $this->mb_fields['page'], $this->mb_fields['context'], $this->mb_fields['priority'], $this->mb_fields
		);
	}

// End criterias_meta
	// cpt = custom post type
	/**
	 * [populate_criteria_options description]
	 * @return [type] [description]
	 */
	private function _populate_criteria_options() {
		//		global $this->meta_box_criteria;

		$prefix = 'pzucd_';
		/*
		 *
		 * Setup criterias extra fields
		 *
		 */
		$i = 0;
		$meta_box_criteria = array(
				'id' => 'pzucd-criterias-id',
				'title' => 'Criteria Designer',
				'page' => 'ucd-criterias',
				'context' => 'normal',
				'priority' => 'high',
				'orientation' => 'horizontal',
				'tabs' => array(
						$i++ => array(
								'icon' => 'entypo-window',
								'label' => __('General', 'pzucd'),
								'id' => $prefix . 'tab_criterias_general',
								'type' => 'tab',
						),
						$i++ => array(
								'icon' => 'fontawesome-filter',
								'label' => __('Filters', 'pzucd'),
								'id' => $prefix . 'tab_criterias_filters',
								'type' => 'tab',
						),
				)
		);

		$i = 0;

		$meta_box_criteria['tabs'][$i++]['fields'] = array(
				array(
						'label' => __('General', 'pzucd'),
						'id' => $prefix . 'criteria-general',
						'type' => 'heading',
						'default' => '',
						'desc' => __('', 'pzucd')
				),
				array(
						'label' => __('Criteria name', 'pzucd'),
						'id' => $prefix . 'criteria-name',
						'type' => 'text',
						'default' => '',
						'desc' => __('.', 'pzucd'),
						'help' => __('Create re-usable sets of criteria')
				),
				array(
						'label' => __('Content source', 'pzucd'),
						'id' => $prefix . 'criteria-content-source',
						'type' => 'select',
						'default' => 'posts',
						'options' => array(
								array('value' => 'posts', 'text' => 'Posts'),
								array('value' => 'pages', 'text' => 'Pages'),
								array('value' => 'gallery', 'text' => 'Gallery'),
								array('value' => 'widgets', 'text' => 'Widgets'),
								array('value' => 'blocks', 'text' => 'Blocks'),
								array('value' => 'custom-code', 'text' => 'Custom code'),
								array('value' => 'shortcode', 'text' => 'Shortcodes')
						),
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Content type(s)', 'pzucd'),
						'id' => $prefix . 'criteria-content-types',
						'type' => 'text',
						'default' => 'All',
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Specific IDs', 'pzucd'),
						'id' => $prefix . 'criteria-specific-ids',
						'type' => 'text',
						'default' => 'All',
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Order by', 'pzucd'),
						'id' => $prefix . 'criteria-orderby',
						'type' => 'select',
						'default' => 'date',
						'options' => array(
								array('value' => 'date', 'text' => 'Date'),
								array('value' => 'title', 'text' => 'Title'),
						),
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Order direction', 'pzucd'),
						'id' => $prefix . 'criteria-orderdir',
						'type' => 'select',
						'default' => 'DESC',
						'options' => array(
								array('value' => 'ASC', 'text' => 'Ascending'),
								array('value' => 'DESC', 'text' => 'Descending'),
						),
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Sticky posts first', 'pzucd'),
						'id' => $prefix . 'criteria-sticky',
						'type' => 'checkbox',
						'default' => '',
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Skip N posts', 'pzucd'),
						'id' => $prefix . 'criteria-skip',
						'type' => 'text',
						'default' => 'All',
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Include sub-categories on archives', 'pzucd'),
						'id' => $prefix . 'criteria-sub-cat-archives',
						'type' => 'checkbox',
						'default' => false,
						'desc' => __('.', 'pzucd'),
				),
		);
		$meta_box_criteria['tabs'][$i++]['fields'] = array(
				array(
						'label' => __('Filters', 'pzucd'),
						'id' => $prefix . 'criteria-filters-heading',
						'type' => 'heading',
						'default' => '',
						'desc' => __('', 'pzucd')
				),
				array(
						'label' => __('Taxonomies', 'pzucd'),
						'id' => $prefix . 'criteria-filters-taxonomies-heading',
						'type' => 'heading',
						'default' => '',
						'desc' => __('', 'pzucd')
				),
				array(
						'label' => __('Include categories', 'pzucd'),
						'id' => $prefix . 'criteria-inc-cats',
						'type' => 'text',
						'default' => 'All',
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Must be in ALL categories', 'pzucd'),
						'id' => $prefix . 'criteria-all-cats',
						'type' => 'checkbox',
						'default' => false,
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Exclude categories', 'pzucd'),
						'id' => $prefix . 'criteria-exc-cats',
						'type' => 'text',
						'default' => 'All',
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Tags', 'pzucd'),
						'id' => $prefix . 'criteria-inc-tags',
						'type' => 'text',
						'default' => 'All',
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Other taxonomies', 'pzucd'),
						'id' => $prefix . 'criteria-other-tax',
						'type' => 'text',
						'default' => 'All',
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Taxonomies operator', 'pzucd'),
						'id' => $prefix . 'criteria-tax-op',
						'type' => 'text',
						'default' => 'All',
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Others', 'pzucd'),
						'id' => $prefix . 'criteria-filters-others-heading',
						'type' => 'heading',
						'default' => '',
						'desc' => __('', 'pzucd')
				),
				array(
						'label' => __('Days to show', 'pzucd'),
						'id' => $prefix . 'criteria-days',
						'type' => 'text',
						'default' => 'All',
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Days to show until', 'pzucd'),
						'id' => $prefix . 'criteria-days-until',
						'type' => 'text',
						'default' => '',
						'desc' => __('.', 'pzucd'),
				),
				array(
						'label' => __('Authors', 'pzucd'),
						'id' => $prefix . 'criteria-authors',
						'type' => 'text',
						'default' => 'All',
						'desc' => __('.', 'pzucd'),
				),
		);

		return $meta_box_criteria;
	}

	/**
	 * 
	 * @return type
	 */
	/*
	  function criteria_defaults()
	  {
	  $pzucd_criteria_defaults = array( );
	  $this->populate_criteria_options();
	  foreach ( $this->meta_box_criteria[ 'tabs' ] as $pzucd_meta_box )
	  {
	  foreach ( $pzucd_meta_box[ 'fields' ] as $pzucd_field )
	  {
	  if ( !isset( $pzucd_field[ 'id' ] ) )
	  {
	  $pzucd_criteria_defaults[ $pzucd_field[ 'id' ] ] = (isset( $pzucd_field[ 'default' ] ) ? $pzucd_field[ 'default' ] : null);
	  }
	  }
	  }
	  return $pzucd_criteria_defaults;
	  }
	 */
}

// EOC