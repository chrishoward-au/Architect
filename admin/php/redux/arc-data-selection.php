<?php

class pzarc_Contents
{

  private $mb_fields;

  /**
   * [__construct description]
   */
  function __construct()
  {
    //   add_action('init', array($this, 'create_contents_post_type'));
    // This overrides the one in the parent class

    if (is_admin())
    {
      //	add_action('admin_init', 'pzarc_preview_meta');
      //		add_action('add_meta_boxes', array($this, 'contents_meta'));
//      add_action('admin_head', array($this, 'contents_admin_head'));
      add_action('admin_enqueue_scripts', array($this, 'contents_admin_enqueue'));
      add_action('views_edit-arc-contents', array($this, 'pzarc_cells_description'));
//			add_filter('manage_rrrr_posts_columns', array($this, 'add_contents_columns'));
//			add_action('manage_arc-contents_posts_custom_column', array($this, 'add_contents_column_content'), 10, 2);

      // check screen arc-contents. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'arc-contents' )
//			{
//			}
    }

  }

  /**
   * [contents_admin_enqueue description]
   * @param  [type] $hook [description]
   * @return [type]       [description]
   */
  public function contents_admin_enqueue($hook)
  {
    $screen = get_current_screen();
    if ('arc-blueprints' == $screen->id)
    {


      wp_enqueue_style('pzarc-admin-contents-css', PZARC_PLUGIN_URL . 'admin/css/arc-admin-contents.css');

      //     wp_enqueue_script('jquery-pzarc-metaboxes-contents', PZARC_PLUGIN_URL . 'admin/js/arc-metaboxes-contents.js', array('jquery'));
    }
  }

  /**
   * [contents_admin_head description]
   * @return [type] [description]
   */
  public function contents_admin_head()
  {

  }

  /**
   * [add_contents_columns description]
   * @param [type] $columns [description]
   */
  public function add_contents_columns($columns)
  {
    unset($columns[ 'thumbnail' ]);
    $pzarc_front  = array_slice($columns, 0, 2);
    $pzarc_back   = array_slice($columns, 2);
    $pzarc_insert =
            array
            (
                    'pzarc_contents_short_name' => __('Contents short name', 'pzsp'),
            );

    return array_merge($pzarc_front, $pzarc_insert, $pzarc_back);
  }

  /**
   * [add_contents_column_content description]
   * @param [type] $column  [description]
   * @param [type] $post_id [description]
   */
  public function add_contents_column_content($column, $post_id)
  {
    switch ($column)
    {
      case 'pzarc_short_name':
        echo get_post_meta($post_id, 'pzarc_contents_short-name', true);
        break;
    }
  }

  /**
   * [create_contents_post_type description]
   * @return [type] [description]
   */
  public function create_contents_post_type()
  {
    $labels = array(
            'name'               => _x('Content selections', 'post type general name'),
            'singular_name'      => _x('Content selection', 'post type singular name'),
            'add_new'            => __('Add New Content selection'),
            'add_new_item'       => __('Add New Content selection'),
            'edit_item'          => __('Edit Content selection'),
            'new_item'           => __('New Content selection'),
            'view_item'          => __('View Content selection'),
            'search_items'       => __('Search Content selections'),
            'not_found'          => __('No Content selection found'),
            'not_found_in_trash' => __('No Content selections found in Trash'),
            'parent_item_colon'  => '',
            'menu_name'          => _x('<span class="dashicons dashicons-search"></span>Contents', 'pzarc-contents-designer'),
    );

    $args = array(
            'labels'              => $labels,
            'description'         => __('Architect Contents selection are used to create reusable criteria for use in your Architect blocks, widgets, shortcodes and WP template tags.'),
            'public'              => false,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => 'pzarc',
            'show_in_nav_menus'   => false,
            'query_var'           => true,
            'rewrite'             => true,
            'capability_type'     => 'post',
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_position'       => 20,
            'supports'            => array('title', 'revisions'),
            'exclude_from_search' => true,
    );

    register_post_type('arc-contents', $args);
  }

  function pzarc_cells_description($post)
  {

    echo '<div class="after-title-help postbox">
      <div class="inside">
        <p class="howto">' . __('Cell layouts are...', 'pzsp') . '
      </div>
      <!-- .inside -->
    </div><!-- .postbox -->';
  }


} // EOC


add_filter('cmb_meta_boxes', 'pzarc_contents_posts_metabox');



