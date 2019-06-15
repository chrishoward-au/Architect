<?php

  if ((function_exists('arc_fs') &&  arc_fs()->is__premium_only())  || defined('PZARC_PRO')) {


    function pzarcpro_init_cpt() {

      pzdb( 'pre content field_types load' );

      /** Create additional post field_types */
      global $_architect_options;
      if ( ! isset( $GLOBALS['_architect_options'] ) ) {
        $GLOBALS['_architect_options'] = get_option( '_architect_options', array() );
      }

//    var_dump($_architect_options);
      if ( ! isset( $_architect_options['architect_add-content-types']['pz_snippets'] ) || $_architect_options['architect_add-content-types']['pz_snippets'] == 1 ) {
        require_once plugin_dir_path( __FILE__ ) . '/content-types/snippets/arc-cpt-snippets.php';
      }

      if ( ! isset( $_architect_options['architect_add-content-types']['pz_testimonials'] ) || $_architect_options['architect_add-content-types']['pz_testimonials'] == 1 ) {
        require_once plugin_dir_path( __FILE__ ) . '/content-types/testimonials/arc-cpt-testimonials.php';
      }

      if ( ! isset( $_architect_options['architect_add-content-types']['pz_showcases'] ) || $_architect_options['architect_add-content-types']['pz_showcases'] == 1 ) {
        require_once plugin_dir_path( __FILE__ ) . '/content-types/showcases/arc-cpt-showcases.php';
      }

      if ( ! isset( $_architect_options['architect_add-content-types']['pz_arcgallery'] ) || $_architect_options['architect_add-content-types']['pz_arcgallery'] == 1 ) {
  //      require_once plugin_dir_path( __FILE__ ) . '/content-types/arcgallery/arc-cpt-arcgallery.php';
      }
      pzdb( 'post content field_types load' );

      // require_once plugin_dir_path( __FILE__ ). '/content-types/rss/class_arc_content_rss.php';
//  require_once plugin_dir_path( __FILE__ ). '/content-types/widgets/class_arc_content_widgets.php';
      // Add content field_types: Testimonials, FAQs, Features, Contacts, Movies, Books, Recipes

      /** Nav field_types? */


      //    $registry = arc_Registry::getInstance();
      //     $registry->set('status',true);
//      $registry = arc_Registry::getInstance();
//      $pzstatus =$registry->get('status');
//      use empty($pzstatus[0]) || $pzstatus==='key not set'

    } // EOF

    pzarcpro_init_cpt();
    // ... premium only logic ...
  }


