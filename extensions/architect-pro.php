<?php



    function pzarcpro_init()
    {
      if (is_admin()) {
        add_action('admin_init', 'pzarc_initiate_updater');

        function pzarc_initiate_updater()
        {
          // Check on Headway if enabled since it was probably bought there
          if (class_exists('HeadwayUpdaterAPI') && defined('PZARC_HWREL') && PZARC_HWREL) {

            $updater = new HeadwayUpdaterAPI(array(
                                                 'slug'            => 'architect',
                                                 'path'            => plugin_basename(__FILE__),
                                                 'name'            => 'Architect',
                                                 'type'            => 'block',
                                                 'current_version' => PZARC_VERSION
                                             ));
          } else {
            require_once('wp-updates-plugin.php');
            new WPUpdatesPluginUpdater_429( 'http://wp-updates.com/api/2/plugin', plugin_basename(__FILE__));
          }


          /**
           * Display update notices
           */
//    @include_once PZARC_DOCUMENTATION_PATH . 'updates/1000.php';

        }
      }


      /** Content types */
      require_once plugin_dir_path(__FILE__) . '/content-types/dummy/class_arc_content_dummy.php';
      require_once plugin_dir_path(__FILE__) . '/content-types/slide/class_arc_content_slide.php';
      require_once plugin_dir_path(__FILE__) . '/content-types/cpt/class_arc_content_cpt.php';
      require_once plugin_dir_path(__FILE__) . '/content-types/page/class_arc_content_pages.php';
      require_once plugin_dir_path(__FILE__) . '/content-types/gallery/class_arc_content_gallery.php';
      require_once plugin_dir_path(__FILE__) . '/content-types/gallery/class_arc_content_gallery.php';
      require_once plugin_dir_path(__FILE__) . '/content-types/nextgen/class_arc_content_nextgen.php';
      require_once plugin_dir_path(__FILE__) . '/content-types/snippets/class_arc_content_snippets.php';

      /** Create additional post types */
      require_once plugin_dir_path(__FILE__) . '/content-types/snippets/arc-cpt-snippets.php';

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

   add_action('arc_load_extensions','pzarcpro_init');
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
