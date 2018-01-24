<?php
/**
 * Plugin Name: Beaver Builder Custom Modules
 * Plugin URI: http://www.wpbeaverbuilder.com
 * Description: An example plugin for creating custom builder modules.
 * Version: 2.0
 * Author: The Beaver Builder Team
 * Author URI: http://www.wpbeaverbuilder.com
 */
define( 'FL_ARCHITECT_BB2_MODULE_DIR', plugin_dir_path( __FILE__ ) );
define( 'FL_ARCHITECT_BB2_MODULE_URL', plugins_url( '/', __FILE__ ) );

require_once FL_ARCHITECT_BB2_MODULE_DIR . 'classes/class-fl-architect-modules-loader.php';

/*
 * FUNCTIONS
 */

// Not using these - way too incosistent and messy
//  function arc_any_field_connection_getter( $settings ) {
//    $arc_field = explode( '/', $settings->arc_field );
//    $arc_field_val = arc_get_table_field_value(array('table'=>$arc_field[0],'field'=>$arc_field[1]));
//    return $arc_field_val;
////    return ("[arccf table={$arc_field[0]} field={$arc_field[1]}]" );
//  }
//
//  function arc_get_any_fields() {
//
//    $arc_tableset     = ArcFun::get_tables();
//    $arc_tablesfields = array();
//    foreach ( $arc_tableset as $arc_table ) {
//      $arc_fields = ArcFun::get_table_fields( $arc_table,true );
//      $arc_tablesfields = array_merge( $arc_tablesfields, $arc_fields );
//    }
//
//    return $arc_tablesfields;
//
//  }
//
//  function arc_any_cfield_connection_getter( $settings ) {
//    $cfield_val=get_post_meta(get_the_ID(),$settings->arc_cfield,true);
//    // Need to put all the display code somewhere and filtering types
//    return $cfield_val;
//  }
//
//  function arc_get_any_cfields() {
//    return pzarc_get_custom_fields();
//  }

  /*
   * HOOKS
   */

  add_action( 'fl_page_data_add_properties', function () {

    FLPageData::add_post_property( 'arc_any_field', array(
      'label'  => 'Any table field',
      'group'  => 'general',
      'type'   => 'custom_field',
      'getter' => 'arc_any_field_connection_getter',
    ) );

    FLPageData::add_post_property_settings_fields( 'arc_any_field', array(
      'arc_field' => array(
        'type'    => 'select',
        'label'   => 'Choose field',
        'options' => 'arc_get_any_fields',
      ),
    ) );
  } );

  add_action( 'fl_page_data_add_properties', function () {

    FLPageData::add_post_property( 'arc_any_cfield', array(
      'label'  => 'Any custom field',
      'group'  => 'general',
      'type'   => 'custom_field',
      'getter' => 'arc_any_cfield_connection_getter',
    ) );

    FLPageData::add_post_property_settings_fields( 'arc_any_cfield', array(
      'arc_cfield' => array(
        'type'    => 'select',
        'label'   => 'Choose field',
        'options' => 'arc_get_any_cfields',
      ),
    ) );
  } );
