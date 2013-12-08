<?php

class pzucd_Criteria extends pzucdForm {

	private $mb_fields;

	/**
	 * [__construct description]
	 */
	function __construct() {
		add_action('init', array($this, 'create_criterias_post_type'));
		// This overrides the one in the parent class

		if (is_admin()) {
			//	add_action('admin_init', 'pzucd_preview_meta');
	//		add_action('add_meta_boxes', array($this, 'criterias_meta'));
			add_action('admin_head', array($this, 'criteria_admin_head'));
			add_action('admin_enqueue_scripts', array($this, 'criteria_admin_enqueue'));
      add_action('views_edit-ucd-layouts', array($this,'pzucd_cells_description'));
//			add_filter('manage_ucd-criterias_posts_columns', array($this, 'add_criteria_columns'));
//			add_action('manage_ucd-criterias_posts_custom_column', array($this, 'add_criteria_column_content'), 10, 2);

			// check screen ucd-criterias. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'ucd-criterias' )
//			{
//			}
		}

	}

	/**
	 * [criteria_admin_enqueue description]
	 * @param  [type] $hook [description]
	 * @return [type]       [description]
	 */
	public function criteria_admin_enqueue($hook) {
		$screen = get_current_screen();
		if ('ucd-criterias' == $screen->id) {


			wp_enqueue_style('pzucd-admin-criterias-css', PZUCD_PLUGIN_URL . 'admin/css/ucd-admin-criterias.css');

			wp_enqueue_script('jquery-pzucd-metaboxes-criterias', PZUCD_PLUGIN_URL . 'admin/js/ucd-metaboxes-criterias.js', array('jquery'));
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
				'menu_name' => _x('<span class="dashicons-icon icon-criterias"></span>Contents', 'pzucd-criteria-designer'),
		);

		$args = array(
				'labels' => $labels,
				'description' => __('Ultimate Content Display Content selection are used to create reusable criteria for use in your UCD blocks, widgets, shortcodes and WP template tags.'),
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
		);

		register_post_type('ucd-criterias', $args);
	}

  function pzucd_cells_description($post)
  {

    echo '<div class="after-title-help postbox">
      <div class="inside">
        <p class="howto">'. __('Cell layouts are...', 'pzsp').'
      </div>
      <!-- .inside -->
    </div><!-- .postbox -->';
  }




} // EOC


add_filter('cmb_meta_boxes', 'pzucd_criterias_metabox');
function pzucd_criterias_metabox($meta_boxes = array()){

  $prefix = '_pzucd_';

  $fields = array(
    array(
      'name' => __('General', 'pzucd'),
      'id' => $prefix . 'criteria-general',
      'type' => 'title',
    ),
    array(
      'name' => __('Criteria name', 'pzucd'),
      'id' => $prefix . 'criteria-name',
      'type' => 'text',
      'default' => '',
      'help' => __('Create re-usable sets of criteria')
    ),
    array(
      'name' => __('Content source', 'pzucd'),
      'id' => $prefix . 'criteria-content-source',
      'type' => 'pzselect',
      'default' => 'posts',
      'cols'=>6,
      'options' => array(
        'posts' => 'Posts',
        'pages' => 'Pages',
        'images' => 'Specific Images',
        'wpgallery' => 'WP Gallery from post',
        'galleryplus' => 'GalleryPlus',
        'nggallery' => 'NextGen',
        'widgets' => 'Widgets',
        'custom-code' => 'Custom code',
        'rss' => 'RSS Feed',
        'cpt' => 'Custom Post Type - need to be added'
      ),
    ),
    // So what's thediffbetween contentsource and content types?
//    array(
//      'name' => __('Content type(s)', 'pzucd'),
//      'id' => $prefix . 'criteria-content-types',
//      'type' => 'text',
//      'default' => 'All',
//      'desc' => __('.', 'pzucd'),
//    ),
    array(
      'name' => __('Specific IDs', 'pzucd'),
      'id' => $prefix . 'criteria-specific-ids',
      'type' => 'text',
      'default' => 'All',
      'cols'=>6,
    ),
    array(
      'name' => __('Specific Images', 'pzucd'),
      'id' => $prefix . 'criteria-specific-images',
      'type' => 'image',
      'size'=> 'height=100&width=100&crop=1',
      'repeatable' => true,
      'cols'=>12,
      'desc'=>__('Can be overriden by shortcode like [pzucd_gallery ids="1,2,3,4,5"]','pzucd')
    ),
    array(
      'name' => __('Order by', 'pzucd'),
      'id' => $prefix . 'criteria-orderby',
      'type' => 'pzselect',
      'default' => 'date',
      'cols'=>6,
      'options' => array(
        'date' => 'Date',
        'title' => 'Title',
      ),
    ),
    array(
      'name' => __('Order direction', 'pzucd'),
      'id' => $prefix . 'criteria-orderdir',
      'type' => 'pzselect',
      'default' => 'DESC',
      'cols'=>6,
      'options' => array(
        'ASC' => 'Ascending',
        'DESC' => 'Descending',
      ),
    ),
    array(
      'name' => __('Sticky posts first', 'pzucd'),
      'id' => $prefix . 'criteria-sticky',
      'type' => 'checkbox',
      'cols'=>6,
      'default' => false,
    ),
    array(
      'name' => __('Skip N posts', 'pzucd'),
      'id' => $prefix . 'criteria-skip',
      'type' => 'pzspinner',
      'cols'=>6,
      'min'=>0,
      'max'=>9999,
      'step'=>1,
      'desc' => __('Note: Skipping breaks pagination. This is a known WordPres bug.', 'pzucd'),
    ),
    array(
      'name' => __('Include sub-categories on archives', 'pzucd'),
      'id' => $prefix . 'criteria-sub-cat-archives',
      'type' => 'checkbox',
      'default' => false,
    ),
    array(
      'name' => __('Filters', 'pzucd'),
      'id' => $prefix . 'criteria-filters-heading',
      'type' => 'title',
    ),
    array(
      'name' => __('Taxonomies', 'pzucd'),
      'id' => $prefix . 'criteria-filters-taxonomies-heading',
      'type' => 'title',
      'default' => '',
    ),
    array(
      'name' => __('Include categories', 'pzucd'),
      'id' => $prefix . 'criteria-inc-cats',
      'type' => 'taxonomy_select',
      'cols'=>4,
      'taxonomy'=>'category'
    ),
    array(
      'name' => __('Must be in ALL categories', 'pzucd'),
      'id' => $prefix . 'criteria-all-cats',
      'type' => 'checkbox',
      'cols'=>4,
      'default' => false,
    ),
    array(
      'name' => __('Exclude categories', 'pzucd'),
      'id' => $prefix . 'criteria-exc-cats',
      'type' => 'taxonomy_select',
      'cols'=>4,
      'taxonomy'=>'category'
    ),
    array(
      'name' => __('Tags', 'pzucd'),
      'id' => $prefix . 'criteria-inc-tags',
      'type' => 'taxonomy_select',
      'cols'=>4,
      'taxonomy'=>'tags'
    ),
    array(
      'name' => __('Other taxonomies', 'pzucd'),
      'id' => $prefix . 'criteria-other-tax',
      'type' => 'text',
      'cols'=>4,
      'default' => 'All',
    ),
    array(
      'name' => __('Taxonomies operator', 'pzucd'),
      'id' => $prefix . 'criteria-tax-op',
      'type' => 'text',
      'cols'=>4,
      'default' => 'All',
    ),
    array(
      'name' => __('Others', 'pzucd'),
      'id' => $prefix . 'criteria-filters-others-heading',
      'type' => 'title',
      'default' => '',
    ),
    array(
      'name' => __('Days to show', 'pzucd'),
      'id' => $prefix . 'criteria-days',
      'type' => 'text',
      'cols'=>6,
      'default' => 'All',
    ),
    array(
      'name' => __('Days to show until', 'pzucd'),
      'id' => $prefix . 'criteria-days-until',
      'type' => 'text',
      'cols'=>6,
      'default' => '',
    ),
    array(
      'name' => __('Authors', 'pzucd'),
      'id' => $prefix . 'criteria-authors',
      'type' => 'text',
      'default' => 'All',
    ),
  );

  $meta_boxes[ ] = array(
    'title'    => 'Criteria',
    'pages'    => 'ucd-criterias',
    'fields'   => $fields,
    'context'  => 'normal',
    'priority' => 'high'

  );

  return $meta_boxes;

}