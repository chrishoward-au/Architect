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

      wp_enqueue_script('jquery-pzarc-metaboxes-contents', PZARC_PLUGIN_URL . 'admin/js/arc-metaboxes-contents.js', array('jquery'));
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
function pzarc_contents_posts_metabox($meta_boxes = array())
{
  $prefix = '_pzarc_posts_';

  $fields = array(
          array(
                  'name' => __('Categories', 'pzarc'),
                  'id'   => $prefix . 'contents-filters-categories-heading',
                  'type' => 'title',
          ),
          array(
                  'name'        => __('Include categories', 'pzarc'),
                  'id'          => $prefix . 'contents-inc-cats',
                  'type'        => 'taxonomy_select',
                  'allow_none ' => true,
                  'default'     => '',
                  'cols'        => 3,
                  'taxonomy'    => 'category'
          ),
          array(
                  'name'    => __('In ANY or ALL categories', 'pzarc'),
                  'id'      => $prefix . 'contents-all-cats',
                  'type'    => 'radio',
                  'options' => array('any'=>'Any','all'=>'All'),
                  'default' => 'any',
                  'cols'    => 3,
          ),
          array(
                  'name'        => __('Exclude categories', 'pzarc'),
                  'id'          => $prefix . 'contents-exc-cats',
                  'type'        => 'taxonomy_select',
                  'default'     => '',
                  'allow_none ' => true,
                  'cols'        => 3,
                  'taxonomy'    => 'category'
          ),
          array(
                  'name'    => __('Include sub-categories on archives', 'pzarc'),
                  'id'      => $prefix . 'contents-sub-cat-archives',
                  'type'    => 'radio',
                  'options' => array('yes'=>'Yes','no'=>'No'),
                  'default' => 'yes',
                  'cols'    => 3,
          ),
          array(
                  'name' => __('Tags', 'pzarc'),
                  'id'   => $prefix . 'contents-filters-tags-heading',
                  'type' => 'title',
          ),
          array(
                  'name'        => __('Tags', 'pzarc'),
                  'id'          => $prefix . 'contents-inc-tags',
                  'type'        => 'taxonomy_select',
                  'allow_none ' => true,
                  'default'     => '',
                  'cols'        => 4,
                  'taxonomy'    => 'tags'
          ),
          array(
                  'name'        => __('Exclude tags', 'pzarc'),
                  'id'          => $prefix . 'contents-exc-tags',
                  'type'        => 'taxonomy_select',
                  'default'     => '',
                  'allow_none ' => true,
                  'cols'        => 4,
                  'taxonomy'    => 'tags'
          ),
          array(
                  'name' => __('Custom taxonomies', 'pzarc'),
                  'id'   => $prefix . 'contents-filters-custom-taxonomies-heading',
                  'type' => 'title',
          ),

          array(
                  'name'    => __('Other taxonomies', 'pzarc'),
                  'id'      => $prefix . 'contents-other-tax',
                  'type'    => 'text',
                  'cols'    => 4,
                  'default' => 'All',
          ),
          array(
                  'name'    => __('Taxonomies operator', 'pzarc'),
                  'id'      => $prefix . 'contents-tax-op',
                  'type'    => 'text',
                  'cols'    => 4,
                  'default' => 'All',
          ),
          //    array(
          //      'name' => __('Days to show', 'pzarc'),
          //      'id' => $prefix . 'contents-days',
          //      'type' => 'text',
          //      'cols'=>6,
          //      'default' => 'All',
          //    ),
          //    array(
          //      'name' => __('Days to show until', 'pzarc'),
          //      'id' => $prefix . 'contents-days-until',
          //      'type' => 'text',
          //      'cols'=>6,
          //
          //    ),
          array(
                  'name'    => __('Authors', 'pzarc'),
                  'id'      => $prefix . 'contents-authors',
                  'type'    => 'text',
                  'default' => 'All',
          ),
  );

  $meta_boxes[ ] = array(
          'title'    => 'Posts filters',
          'pages'    => 'arc-blueprints',
          'fields'   => $fields,
          'context'  => 'normal',
          'priority' => 'default'

  );

  return $meta_boxes;

}

add_filter('cmb_meta_boxes', 'pzarc_contents_galleries_metabox');
function pzarc_contents_galleries_metabox($meta_boxes = array())
{

  $prefix = '_pzarc_galleries_';

  $fields        = array(
    // So what's thediffbetween contentsource and content types?
    //    array(
    //      'name' => __('Content type(s)', 'pzarc'),
    //      'id' => $prefix . 'contents-content-types',
    //      'type' => 'text',
    //      'default' => 'All',
    //      'desc' => __('.', 'pzarc'),
    //    ),
    array(
            'name'    => __('Gallery source', 'pzarc'),
            'id'      => $prefix . 'contents-gallery-source',
            'type'    => 'pzselect',
            'default' => 'images',
            'cols'    => 12,
            'options' => array(
                    'images'      => 'Specific Images',
                    'wpgallery'   => 'WP Gallery from post',
                    'galleryplus' => 'GalleryPlus',
                    'nggallery'   => 'NextGen',
            )
    ),
    array(
            'name'    => __('Specific IDs', 'pzarc'),
            'id'      => $prefix . 'contents-specific-ids',
            'type'    => 'text',
            'default' => 'All',
            'cols'    => 12,
    ),
    array(
            'name'       => __('Specific Images', 'pzarc'),
            'id'         => $prefix . 'contents-specific-images',
            'type'       => 'image',
            'size'       => 'height=100&width=100&crop=1',
            'repeatable' => true,
            'cols'       => 12,
            'desc'       => __('Can be overriden by shortcode like [pzarc blueprint="mytemplate" ids="1,2,3,4,5"]', 'pzarc')
    ),
  );
  $meta_boxes[ ] = array(
          'title'    => 'Galleries filters',
          'pages'    => 'arc-blueprints',
          'fields'   => $fields,
          'context'  => 'normal',
          'priority' => 'default'

  );

  return $meta_boxes;

}

add_filter('cmb_meta_boxes', 'pzarc_contents_pages_metabox');
function pzarc_contents_pages_metabox($meta_boxes = array())
{

  $prefix = '_pzarc_pages_';

  $fields        = array();
  $meta_boxes[ ] = array(
          'title'    => 'Pages filters',
          'pages'    => 'arc-blueprints',
          'fields'   => $fields,
          'context'  => 'normal',
          'priority' => 'default'

  );

  return $meta_boxes;

}

add_filter('cmb_meta_boxes', 'pzarc_contents_cpt_metabox');
function pzarc_contents_cpt_metabox($meta_boxes = array())
{

  $prefix = '_pzarc_pages_';

  $fields        = array();
  $meta_boxes[ ] = array(
          'title'    => 'Custom post types filters',
          'pages'    => 'arc-blueprints',
          'fields'   => $fields,
          'context'  => 'normal',
          'priority' => 'default'

  );

  return $meta_boxes;

}


add_filter('cmb_meta_boxes', 'pzarc_contents_settings_metabox');
function pzarc_contents_settings_metabox($meta_boxes = array())
{

  $prefix = '_pzarc_';

  $fields = array(
          // array(
          //         'name' => __('Contents selection name', 'pzarc'),
          //         'id'   => $prefix . 'contents-name',
          //         'type' => 'text',
          //         'cols'=> '4'
          // ),
          array(
                  'name'    => __('Content source', 'pzarc'),
                  'id'      => $prefix . 'contents-content-source',
                  'type'    => 'pzselect',
                  'default' => 'default',
                  'options' => array(
                          'default' => 'Default',
                          'post'    => 'Posts',
                          'page'    => 'Pages',
                          'gallery' => 'Galleries',
                          'slides' => 'Slides',
                          //                          'images'      => 'Specific Images',
                          //                          'wpgallery'   => 'WP Gallery from post',
                          //                          'galleryplus' => 'GalleryPlus',
                          //                          'nggallery'   => 'NextGen',
  //                        'widgets' => 'Widgets',
                          //                          'custom-code' => 'Custom code',
  //                        'rss'     => 'RSS Feed',
                          'cpt'     => 'Custom Post Types'
                  ),
                  'desc'    => 'todo: code all the js to show hide relevant sections'
          ),
          array(
                  'name'    => __('Order by', 'pzarc'),
                  'id'      => $prefix . 'contents-orderby',
                  'type'    => 'pzselect',
                  'default' => 'date',
                  'cols'    => 6,
                  'options' => array(
                          'date'  => 'Date',
                          'title' => 'Title',
                  ),
          ),
          array(
                  'name'    => __('Order direction', 'pzarc'),
                  'id'      => $prefix . 'contents-orderdir',
                  'type'    => 'pzselect',
                  'default' => 'DESC',
                  'cols'    => 6,
                  'options' => array(
                          'ASC'  => 'Ascending',
                          'DESC' => 'Descending',
                  ),
          ),
          array(
                  'name' => __('Skip N posts', 'pzarc'),
                  'id'   => $prefix . 'contents-skip',
                  'type' => 'pzspinner',
                  'min'  => 0,
                  'max'  => 9999,
                  'step' => 1,
                  'default'=>0,
                  'desc' => __('Note: Skipping breaks pagination. This is a known WordPress bug.', 'pzarc'),
          ),
          array(
                  'name'    => __('Sticky posts first', 'pzarc'),
                  'id'      => $prefix . 'contents-sticky',
                  'type'    => 'radio',
                  'options' => array('yes'=>'Yes','no'=>'No'),
                  'default' => 'no',
          ),

          // array(
          //         'name'    => 'Save Contents Selection',
          //         'id'      => $prefix . 'contents-selection-save',
          //         'type'    => 'pzsubmit',
          //         'default' => 'Save'
          // ),
  );

  $meta_boxes[ ] = array(
          'title'    => 'Contents Selection settings',
          'pages'    => 'arc-blueprints',
          'fields'   => $fields,
          'context'  => 'side',
          'priority' => 'high'

  );

  return $meta_boxes;
}
