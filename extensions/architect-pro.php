<?php



  function pzarcpro_init() {

    pzdb( 'pre content types load' );
    /** Content types */
    require_once plugin_dir_path( __FILE__ ) . '/content-types/gallery/class_arc_content_gallery.php';
    require_once plugin_dir_path( __FILE__ ) . '/content-types/slide/class_arc_content_slide.php';
    require_once plugin_dir_path( __FILE__ ) . '/content-types/nextgen/class_arc_content_nextgen.php';
    require_once plugin_dir_path( __FILE__ ) . '/content-types/cpt/class_arc_content_cpt.php';

    /** Load additional functionality */

    // Animation add on
    require_once plugin_dir_path( __FILE__ ) . '/animation/arc-animation.php';

    /** Create additional post types */
    global $_architect_options;
    if ( ! isset( $GLOBALS[ '_architect_options' ] ) ) {
      $GLOBALS[ '_architect_options' ] = get_option( '_architect_options', array() );
    }

    if ( ! isset( $_architect_options[ 'architect_add-content-types' ][ 'pz_snippets' ] ) || $_architect_options[ 'architect_add-content-types' ][ 'pz_snippets' ] == 1 ) {
      require_once plugin_dir_path( __FILE__ ) . '/content-types/snippets/class_arc_content_snippets.php';
      require_once plugin_dir_path( __FILE__ ) . '/content-types/snippets/arc-cpt-snippets.php';
    }

    if ( ! isset( $_architect_options[ 'architect_add-content-types' ][ 'pz_testimonials' ] ) || $_architect_options[ 'architect_add-content-types' ][ 'pz_testimonials' ] == 1 ) {
      require_once plugin_dir_path( __FILE__ ) . '/content-types/testimonials/class_arc_content_testimonials.php';
      require_once plugin_dir_path( __FILE__ ) . '/content-types/testimonials/arc-cpt-testimonials.php';
    }

//    if ( ! isset( $_architect_options[ 'architect_add-content-types' ][ 'pz_showcases' ] ) || $_architect_options[ 'architect_add-content-types' ][ 'pz_showcases' ] == 1 ) {
//      require_once plugin_dir_path( __FILE__ ) . '/content-types/showcase/class_arc_content_showcase.php';
//      require_once plugin_dir_path( __FILE__ ) . '/content-types/showcase/arc-cpt-showcase.php';
//    }

    pzdb( 'post content types load' );

//  require_once plugin_dir_path( __FILE__ ). '/content-types/rss/class_arc_content_rss.php';
//  require_once plugin_dir_path( __FILE__ ). '/content-types/widgets/class_arc_content_widgets.php';
    // Add content types: Testimonials, FAQs, Features, Contacts, Movies, Books, Recipes

    /** Nav types? */


    //    $registry = arc_Registry::getInstance();
    //     $registry->set('status',true);
//      $registry = arc_Registry::getInstance();
//      $pzstatus =$registry->get('status');
//      use empty($pzstatus[0]) || $pzstatus==='key not set'

  }


  add_action( 'arc_load_extensions', 'pzarcpro_init' );
  //  }


  // Reminder of content types
  //                                    'options'  => array(
  //                                        'defaults' => 'Defaults',
  //                                        'post'     => 'Posts',
  //                                        'page'     => 'Pages',
  //                                        'snippets' => 'Snippets',
  //                                        'gallery'  => 'Galleries',
  //                                        'slides'   => 'Slides',
  //                                        'dummy'    => 'Dummy content',
  //                                        //                          'images'      => 'Specific Images',
  //                                        //                          'wpgallery'   => 'WP Gallery from post',
  //                                        //                          'galleryplus' => 'GalleryPlus',
  //                                        //                          'nggallery'   => 'NextGen',
  //                                        //                        'widgets' => 'Widgets',  // This one may not be workable if user can't control where sidebars appear
  //                                        //                          'custom-code' => 'Custom code',
  //                                        //                        'rss'     => 'RSS Feed',
  //                                        'cpt'      => 'Custom Post Types'
  //                                    ),
