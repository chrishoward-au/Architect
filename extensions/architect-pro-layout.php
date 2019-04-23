<?php
  if ((function_exists('arc_fs') &&  arc_fs()->is__premium_only())  || defined('PZARC_PRO')) {

    add_action( 'arc_load_extensions', 'pzarcpro_load' );

    function pzarcpro_load() {

      pzdb( 'pre content types load' );
      /** Content types */
      require_once plugin_dir_path( __FILE__ ) . '/content-types/gallery/class_arc_content_gallery.php';
      require_once plugin_dir_path( __FILE__ ) . '/content-types/slide/class_arc_content_slide.php';
      require_once plugin_dir_path( __FILE__ ) . '/content-types/nextgen/class_arc_content_nextgen.php';
      require_once plugin_dir_path( __FILE__ ) . '/content-types/cpt/class_arc_content_cpt.php';
      require_once plugin_dir_path( __FILE__ ) . '/content-types/multi/class_arc_content_multi.php';
      require_once plugin_dir_path( __FILE__ ) . '/content-types/rss/class_arc_content_rss.php';

      /** Load additional functionality */

      // Animation add on
      require_once plugin_dir_path( __FILE__ ) . '/animation/arc-animation.php';

      /** Create additional post types */
      global $_architect_options;
      if ( ! isset( $GLOBALS['_architect_options'] ) ) {
        $GLOBALS['_architect_options'] = get_option( '_architect_options', array() );
      }


      if ( !is_admin() || (is_admin() && in_array(pzarc_get_post_type(),array('arc-blueprints','pz_showcases','pz_testimonials'))) ) { // Changed 1.10.0 // added showcases and testimonials check 1.21.0)

        if ( ! isset( $_architect_options['architect_add-content-types']['pz_snippets'] ) || $_architect_options['architect_add-content-types']['pz_snippets'] == 1 ) {
          require_once plugin_dir_path( __FILE__ ) . '/content-types/snippets/class_arc_content_snippets.php';
        }

        if ( ! isset( $_architect_options['architect_add-content-types']['pz_testimonials'] ) || $_architect_options['architect_add-content-types']['pz_testimonials'] == 1 ) {
          require_once plugin_dir_path( __FILE__ ) . '/content-types/testimonials/class_arc_content_testimonials.php';
        }

        if ( ! isset( $_architect_options['architect_add-content-types']['pz_showcases'] ) || $_architect_options['architect_add-content-types']['pz_showcases'] == 1 ) {
          require_once plugin_dir_path( __FILE__ ) . '/content-types/showcases/class_arc_content_showcases.php';
        }

        if ( ! isset( $_architect_options['architect_add-content-types']['pz_arcgallery'] ) || $_architect_options['architect_add-content-types']['pz_arcgallery'] == 1 ) {
//          require_once plugin_dir_path( __FILE__ ) . '/content-types/arcgallery/class_arc_content_arcgallery.php';
        }
      }
      pzdb( 'post content types load' );


    } // EOF
    // ... premium only logic ...
  }
