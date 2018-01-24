<?php

  /**
   * @class FLMapModule
   */
  class ArcMapModule extends FLBuilderModule {

    /**
     * @method __construct
     */
    public function __construct() {
      parent::__construct( array(
        'name'            => __( 'Maps Extended', 'pzarchitect' ),
        'description'     => __( 'Display a Google map.', 'pzarchitect' ),
        'category'        => __( 'Architect Modules', 'pzarchitect' ),
        'partial_refresh' => TRUE,
        'icon'            => 'location.svg',
      ) );

    }

  }

  /**
   * Register the module and its form settings.
   */
  FLBuilder::register_module( 'ArcMapModule', array(
    'general' => array(
      'title'       => __( 'General', 'pzarchitect' ),
      'description' => __( 'Maps Extended is a clone of the BB Maps module that supports processing of any shortcode in the Address field.' ),
      'sections'    => array(
        'general' => array(
          'title'  => '',
          'fields' => array(
            'address' => array(
              'type'        => 'select',
              'multiple'=>true,
              'label'       => __( 'Address fields', 'pzarchitect' ),
              'placeholder' => __( 'Select fields for address', 'pzarchitect' ),
              'preview'     => array(
                'type' => 'refresh',
              ),
              'options'=>'ArcFun::get_all_table_fields_flat'
//              'connections' => array( 'custom_field' ),
            ),
            'height'  => array(
              'type'        => 'text',
              'label'       => __( 'Height', 'pzarchitect' ),
              'default'     => '400',
              'size'        => '5',
              'description' => 'px',
              'sanitize'    => 'absint',
            ),
          ),
        ),
      ),
    ),
  ) );


