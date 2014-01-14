<?php

class pzarc_Galleries
{

  private $mb_fields;

  /**
   * [__construct description]
   */
  function __construct()
  {
    add_action('init', array($this, 'create_galleries_post_type'));
    // This overrides the one in the parent class

    if (is_admin())
    {
      //	add_action('admin_init', 'pzarc_preview_meta');
      //		add_action('add_meta_boxes', array($this, 'contents_meta'));
//      add_action('admin_head', array($this, 'contents_admin_head'));
//      add_action('admin_enqueue_scripts', array($this, 'contents_admin_enqueue'));
//      add_action('views_edit-arc-contents', array($this, 'pzarc_cells_description'));
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
    if ('arc-gallery' == $screen->id)
    {


//      wp_enqueue_style('pzarc-admin-contents-css', PZARC_PLUGIN_URL . 'admin/css/arc-admin-contents.css');
//
//      wp_enqueue_script('jquery-pzarc-metaboxes-contents', PZARC_PLUGIN_URL . 'admin/js/arc-metaboxes-contents.js', array('jquery'));
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
//  public function add_contents_columns($columns)
//  {
//    unset($columns[ 'thumbnail' ]);
//    $pzarc_front  = array_slice($columns, 0, 2);
//    $pzarc_back   = array_slice($columns, 2);
//    $pzarc_insert =
//            array
//            (
//                    'pzarc_contents_short_name' => __('Contents short name', 'pzsp'),
//            );
//
//    return array_merge($pzarc_front, $pzarc_insert, $pzarc_back);
//  }
//
//  /**
//   * [add_contents_column_content description]
//   * @param [type] $column  [description]
//   * @param [type] $post_id [description]
//   */
//  public function add_contents_column_content($column, $post_id)
//  {
//    switch ($column)
//    {
//      case 'pzarc_short_name':
//        echo get_post_meta($post_id, 'pzarc_contents_short-name', true);
//        break;
//    }
//  }

  /**
   * [create_contents_post_type description]
   * @return [type] [description]
   */
  public function create_galleries_post_type()
  {
if (post_type_exists('gp_gallery')) {
  return;
}
    $labels	 = array(
            'name'							 => _x('Galleries', 'post type general name'),
            'singular_name'			 => _x('Gallery', 'post type singular name'),
            'add_new'						 => _x('Add New Gallery', 'gallery'),
            'add_new_item'			 => __('Add New Gallery'),
            'edit_item'					 => __('Edit Gallery'),
            'new_item'					 => __('New Gallery'),
            'view_item'					 => __('View Gallery'),
            'search_items'			 => __('Search Gallerys'),
            'not_found'					 => __('No gallerys found'),
            'not_found_in_trash' => __('No gallerys found in Trash'),
            'parent_item_colon'	 => '',
            'menu_name'          => _x('<span class="dashicons dashicons-format-gallery"></span>Galleries', 'pzarc-galleries'),
    );
    $args		 = array(
            'labels'						 => $labels,
            'public'						 => false,
            'publicly_queryable' => false,
            'show_ui'						 => true,
            'show_in_menu'			 => 'pzarc',
            'query_var'					 => true,
            'rewrite'						 => true,
            'capability_type'		 => 'post',
            'has_archive'				 => true,
            'hierarchical'			 => false,
            'menu_position'			 => 999,
            'supports'					 => array('title', 'editor', 'excerpt')
    );


    register_post_type('gp_gallery', $args);


  }

//  function pzarc_cells_description($post)
//  {
//
//    echo '<div class="after-title-help postbox">
//      <div class="inside">
//        <p class="howto">' . __('Cell layouts are...', 'pzsp') . '
//      </div>
//      <!-- .inside -->
//    </div><!-- .postbox -->';
//  }


} // EOC

/*
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
                  'cols'        => 4,
                  'taxonomy'    => 'category'
          ),
          array(
                  'name'    => __('Must be in ALL categories', 'pzarc'),
                  'id'      => $prefix . 'contents-all-cats',
                  'type'    => 'checkbox',
                  'cols'    => 4,
                  'default' => false,
          ),
          array(
                  'name'        => __('Exclude categories', 'pzarc'),
                  'id'          => $prefix . 'contents-exc-cats',
                  'type'        => 'taxonomy_select',
                  'default'     => '',
                  'allow_none ' => true,
                  'cols'        => 4,
                  'taxonomy'    => 'category'
          ),
          array(
                  'name'    => __('Include sub-categories on archives', 'pzarc'),
                  'id'      => $prefix . 'contents-sub-cat-archives',
                  'type'    => 'checkbox',
                  'cols'    => 4,
                  'default' => false,
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
          'pages'    => 'arc-contents',
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
          'pages'    => 'arc-contents',
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
          'pages'    => 'arc-contents',
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
          'pages'    => 'arc-contents',
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
          array(
                  'name' => __('Contents selection name', 'pzarc'),
                  'id'   => $prefix . 'contents-name',
                  'type' => 'text',
          ),
          array(
                  'name'    => __('Content source', 'pzarc'),
                  'id'      => $prefix . 'contents-content-source',
                  'type'    => 'pzselect',
                  'default' => 'post',
                  'cols'    => 12,
                  'options' => array(
                          'post'    => 'Posts',
                          'page'    => 'Pages',
                          'gallery' => 'Galleries',
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
                  'name'    => 'Save Contents Selection',
                  'id'      => $prefix . 'contents-selection-save',
                  'type'    => 'pzsubmit',
                  'default' => 'Save'
          ),
  );

  $meta_boxes[ ] = array(
          'title'    => 'Contents Selection settings',
          'pages'    => 'arc-contents',
          'fields'   => $fields,
          'context'  => 'side',
          'priority' => 'high'

  );

  return $meta_boxes;
}
*/