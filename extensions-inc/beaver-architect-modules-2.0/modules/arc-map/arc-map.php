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

  // 1.17.0 - Only run the option call when in the bb editor
  if ( ArcFun::is_bb_active() ) {
    $arc_fields_caller = ArcFun::get_all_table_fields_flat();
  } else {
    $arc_fields_caller = array();
  }


  /**
   * Register the module and its form settings.
   */
  FLBuilder::register_module( 'ArcMapModule', array(
    'general' => array(
      'title'       => __( 'General', 'pzarchitect' ),
      'description' => __( 'Maps Extended is a clone of the BB Maps module that adds the option to use fields from any table.' ),
      'sections'    => array(
        'general' => array(
          'title'  => '',
          'fields' => array(
            'address'       => array(
              'type'          => 'textarea',
              'rows'			=> '3',
              'label'         => __( 'Address', 'fl-builder' ),
              'placeholder'   => __( '1865 Winchester Blvd #202 Campbell, CA 95008', 'fl-builder' ),
              'preview'       => array(
                'type'            => 'refresh',
              ),
              'connections'	=> array( 'custom_field' ),
            ),
            'other_address' => array(
              'type'        => 'select',
              'multiple'=>true,
              'label'       => __( 'Other address field', 'pzarchitect' ),
              'description' => '<br>'.__( 'Select other fields for address that are not selectable in the main field', 'pzarchitect' ),
              'preview'     => array(
                'type' => 'refresh',
              ),
              'options'=>$arc_fields_caller
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


