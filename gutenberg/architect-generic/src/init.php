<?php
  /**
   * Blocks Initializer
   *
   * Enqueue CSS/JS of all the blocks.
   *
   * @since   1.0.0
   * @package CGB
   */

// Exit if accessed directly.
  if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }


  require_once plugin_dir_path( __FILE__ ) . '/block/index.php';

  /**
   * Enqueue Gutenberg block assets for both frontend + backend.
   *
   * `wp-blocks`: includes block type registration and related functions.
   *
   * @since 1.0.0
   */
  function pizazzwp_arc_guten_cgb_block_assets() {
    // Styles.
    wp_enqueue_style( 'pizazzwp_arc_guten-cgb-style-css', // Handle.
        plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
        array( 'wp-blocks' ) // Dependency to include the CSS after it.
    //, filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' ) // Version: filemtime — Gets file modification time.
    );

    // Load JS

//    // Get Blueprints
//    $arc_blueprints = get_option( 'arc_blueprints' );
//    $arc_blueprints = empty( $arc_blueprints ) ? array( '' => __( 'No Blueprints found. Either create some or clear the Archtiect cache.', 'pzarchitect-gb' ) ) : $arc_blueprints;
//
//    var_Dump($arc_blueprints);
//    foreach ($arc_blueprints as $arc_bp_shortname => $arc_bp_title ){
//      if ($arc_bp_shortname!==''){
//        wp_enqueue_style('arc-css-'.$arc_bp_shortname,PZARC_CACHE_URL."pzarc_blueprint_{$arc_bp_shortname}.css",null,time());
//      }
//      break;
//    }

  } // End function pizazzwp_arc_guten_cgb_block_assets().

// Hook: Frontend assets.
  add_action( 'enqueue_block_assets', 'pizazzwp_arc_guten_cgb_block_assets' );

  /**
   * Enqueue Gutenberg block assets for backend editor.
   *
   * `wp-blocks`: includes block type registration and related functions.
   * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
   * `wp-i18n`: To internationalize the block's text.
   *
   * @since 1.0.0
   */
  function pizazzwp_arc_guten_cgb_editor_assets() {
    // Scripts.

    wp_enqueue_script( 'pizazzwp_arc_guten-cgb-block-js', // Handle.
        plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
        array( 'wp-blocks', 'wp-i18n', 'wp-element' ) //, filemtime( plugin_dir_path( __FILE__ ) . 'block.js' ) // Version: filemtime — Gets file modification time.
    );


    // Get Blueprints
    $arc_blueprints = get_option( 'arc_blueprints' );
    $arc_blueprints = empty( $arc_blueprints ) ? array( '' => __( 'No Blueprints found. Either create some or clear the Archtiect cache.', 'pzarchitect-gb' ) ) : $arc_blueprints;

    wp_localize_script( 'pizazzwp_arc_guten-cgb-block-js', 'arc_data', array( 'blueprints' => $arc_blueprints, 'fields' => pizazzwp_arc_guten_cgb_fields() ) );
    foreach ($arc_blueprints as $key => $blueprint ){
      if ($key!==''){
        wp_register_style('arc-css-'.$key,"pzarc_blueprint_{$key}.css",null,time());
      }
    }

    // Styles.
    wp_enqueue_style( 'pizazzwp_arc_guten-cgb-block-editor-css', // Handle.
        plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
        array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
    //, filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' ) // Version: filemtime — Gets file modification time.
    );
  } // End function pizazzwp_arc_guten_cgb_editor_assets().

// Hook: Editor assets.
  add_action( 'enqueue_block_editor_assets', 'pizazzwp_arc_guten_cgb_editor_assets' );

  function pizazzwp_arc_guten_cgb_fields() {

    $pizazz_fields = array(
        'blueprint_default' => array(
            'class'       => 'arc-gb-blueprintDefault',
            'type'        => 'string',
            'fieldtype'   => 'text',
            'default'     => '',
            'label'       => __( 'Default Blueprint: ' ),
            'placeholder' => __( 'Enter a Blueprint shortname' ),
        ),
        'blueprint_tablet'  => array(
            'class'       => 'arc-gb-blueprintTablet',
            'type'        => 'string',
            'fieldtype'   => 'text',
            'default'     => '',
            'label'       => __( 'Tablet Blueprint: ' ),
            'placeholder' => __( 'Enter a Blueprint shortname' ),
        ),
        'blueprint_phone'   => array(
            'class'       => 'arc-gb-blueprintPhone',
            'type'        => 'string',
            'fieldtype'   => 'text',
            'default'     => '',
            'label'       => __( 'Phone Blueprint: ' ),
            'placeholder' => __( 'Enter a Blueprint shortname' ),
        ),
        'override_ids'      => array(
            'class'       => 'arc-gb-overrideIDS',
            'type'        => 'string',
            'fieldtype'   => 'text',
            'default'     => '',
            'label'       => __( 'Override IDs: ' ),
            'placeholder' => __( 'Enter comma separated list of post IDs' ),

        ),
        'override_taxonomy' => array(
            'class'       => 'arc-gb-overrideTaxonomy',
            'type'        => 'string',
            'fieldtype'   => 'text',
            'default'     => '',
            'label'       => __( 'Override Taxonomy: ' ),
            'placeholder' => __( 'Enter comma separated list of taxonomy IDs' ),
        ),
        'override_terms'    => array(
            'class'       => 'arc-gb-overrideTerms',
            'type'        => 'string',
            'fieldtype'   => 'text',
            'default'     => '',
            'label'       => __( 'Override Terms: ' ),
            'placeholder' => __( 'Enter comma separated list of term IDs' ),
        ),
        'blueprint_title'   => array(
            'class'       => 'arc-gb-blueprintTitle',
            'type'        => 'string',
            'fieldtype'   => 'text',
            'default'     => '',
            'label'       => __( 'Override Blueprint title: ' ),
            'placeholder' => __( 'Enter custom Blueprint title' ),
        ),
        'custom_overrides'  => array(
            'class'       => 'arc-gb-customOverrides',
            'type'        => 'string',
            'fieldtype'   => 'textarea',
            'rows'        => 2,
            'default'     => '',
            'label'       => __( 'Custom overrides: ' ),
            'placeholder' => __( 'Enter custom overrides in the form parameter="value" ' ),
        ),
    );

    return $pizazz_fields;
  }