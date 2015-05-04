<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arcBuilderAdmin.php
   * User: chrishoward
   * Date: 20/03/15
   * Time: 8:25 PM
   */
  class arcBuilderAdmin {
    public $redux_opt_name = '_architect';

    function __construct() {
      if ( is_admin() ) {
        add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array(
          $this,
          "pzarc_add_pagebuilder_metaboxes"
        ), 10, 1 );
      }
    }

    /** Page builder */
    function pzarc_add_pagebuilder_metaboxes( $metaboxes ) {
      $blueprint_list = pzarc_get_posts_in_post_type( 'arc-blueprints', true );

      asort( $blueprint_list );
      $blueprint_list = array_merge( $blueprint_list, array( 'comments' => __( 'Comments', 'pzarchitect' ) ),array('show-none' => 'DO NOT SHOW ANY BLUEPRINT') );

      $boxSections    = array();
      $boxSections[ ] = array(
        'desc'   =>
          '<p>' . __( 'Drag and drop the Blueprints to appear on this page from Disable to Enabled. To show this page\'s own content, use a single page type Blueprint. The comments option is a built in.', 'pzarchitect' ) . '</p>' .
          '<p style="color:tomato;">' . __( 'NOTE: For these Blueprints to show, you <strong>must</strong> set the Template to <strong>Architect Builder</strong> in the <strong>Page Attributes</strong> settings at the right.', 'pzarchitect' ) . '</p>',
        'fields' => array(


          array(
            'id'     => '_pzarc_pagebuilder_layout',
            'type'   => 'section',
            'subtitle' => __( 'Select the Blueprints to show and drag & drop to re-order them. These are optional.' , 'pzarchtiect' ),
            'title'  => __( 'Blueprints', 'pzarchitect' ),
            'indent' => true
          ),
//          array(
//            'id'      => '_pzarc_pagebuilder_sorter',
//            'type'    => 'sorter',
//            'options' => array(
//              'enabled'  => array(),
//              'disabled' => $blueprint_list
//            ),
//          ),
//          array(
//            'id'       => '_pzarc_pagebuilder_titles',
//            'type'     => 'multi_text',
//            'title'    => __( 'Blueprint titles', 'pzarchitect' ),
//            'subtitle' => __( 'Optionally add a title for each Blueprint. If you want to skip a Blueprint, leave the title blank.', 'pzarchitect' ),
//            'add_text' => __( 'Add title', 'pzarchitect' ),
//            'default'  => array()
//          ),
          array(
            'title'    => __( 'Show Blueprints for: Any device', 'pzarchitect' ),
            'id'       => '_pzarc_pagebuilder_any',
            'type'     => 'select',
            'multi'    => true,
            'sortable' => true,
            'options'  => $blueprint_list
          ),
          array(
            'title'    => __( 'Show Blueprints for: Phones', 'pzarchitect' ),
            'id'       => '_pzarc_pagebuilder_phones',
            'type'     => 'select',
            'multi'    => true,
            'sortable' => true,
            'options'  => $blueprint_list
          ),
          array(
            'title'    => __( 'Show Blueprints for: Tablets', 'pzarchitect' ),
            'id'       => '_pzarc_pagebuilder_tablets',
            'type'     => 'select',
            'multi'    => true,
            'sortable' => true,
            'options'  => $blueprint_list
          ),
//          array(
//            'title'   => __( 'Title html tag', 'pzarchitect' ),
//            'id'      => '_pzarc_pagebuilder_titles_tag',
//            'type'    => 'select',
//            'default' => 'h3',
//            'options' => array(
//              'h1' => 'h1',
//              'h2' => 'h2',
//              'h3' => 'h3',
//              'h4' => 'h4',
//              'h5' => 'h5',
//              'h6' => 'h6',
//            ),
//            'select2' => array( 'allowClear' => false ),
//
//          ),
          array(
            'id'     => '_pzarc_pagebuilder_wrapper_styling-section-start',
            'type'   => 'section',
            'title'  => __( 'Builder wrapper formatting', 'pzarchitect' ),
            'indent' => true
          ),
          array(
            'title'   => __( 'Width', 'pzarchitect' ),
            'id'      => '_pzarc_pagebuilder_wrapper-width',
            'type'    => 'dimensions',
            'height'  => false,
            'default' => array( 'width' => '100%', 'units' => '%' ),
            'units'   => array( '%', 'px', 'em' )
          ),
          array(
            'id'      => '_pzarc_pagebuilder_wrapper-float',
            'type'    => 'select',
            'options' => array(
              'left'  => __( 'Left', 'pzarchitect' ),
              'right' => __( 'Right', 'pzarchitect' )
            ),
            'default' => 'left',
            'title'   => __( 'Float', 'pzarchitect' ),
          ),
          array(
            'id'             => '_pzarc_pagebuilder_wrapper-margins',
            'type'           => 'spacing',
            'mode'           => 'margin',
            'units'          => array( '%', 'px', 'em' ),
            'units_extended' => 'false',
            'title'          => __( 'Margins', 'pzarchitect' ),
          ),
          array(
            'id'     => '_pzarc_pagebuilder_styling-section-start',
            'type'   => 'section',
            'title'  => __( 'Blueprints wrapper formatting', 'pzarchitect' ),
            'indent' => true
          ),
          array(
            'title'   => __( 'Width', 'pzarchitect' ),
            'id'      => '_pzarc_pagebuilder_width',
            'type'    => 'dimensions',
            'height'  => false,
            'default' => array( 'width' => '100%', 'units' => '%' ),
            'units'   => array( '%', 'px', 'em' )
          ),
          array(
            'id'       => '_pzarc_pagebuilder_float',
            'type'     => 'select',
            'options'  => array(
              'left'   => __( 'Left', 'pzarchitect' ),
              'centre' => __( 'Centre', 'pzarchitect' ),
              'right'  => __( 'Right', 'pzarchitect' )
            ),
            'title'    => __( 'Float', 'pzarchitect' ),
            'subtitle' => __( 'When set to centre, left and right margins will always be ignored.' )
          ),
          array(
            'id'             => '_pzarc_pagebuilder_margins',
            'type'           => 'spacing',
            'mode'           => 'margin',
            'units'          => array( '%', 'px', 'em' ),
            'units_extended' => 'false',
            'title'          => __( 'Margins', 'pzarchitect' ),
          ),
          array(
            'id'             => '_pzarc_pagebuilder_padding',
            'type'           => 'spacing',
            'mode'           => 'padding',
            'units'          => array( '%', 'px', 'em' ),
            'units_extended' => 'false',
            'title'          => __( 'Padding', 'pzarchitect' ),
          ),
          array(
            'id'                    => '_pzarc_pagebuilder_background',
            'type'                  => 'background',
            'title'                 => __( 'Background', 'pzarchitect' ),
            'background-size'       => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'preview'               => false,
            'transparent'           => false
          ),
//          array(
//            'id'          => '_pzarc_pagebuilder_titles_typography',
//            'type'        => 'typography',
//            'title'       => __( 'Titles', 'pzarchitect' ),
//            'google'      => true,
//            'font-backup' => true,
//            'units'       => 'px',
//          )
        )
      );

      // Declare your metaboxes
      $metaboxes[ ] = array(
        'id'         => 'pzarc_mb-pagebuilder',
        'title'      => __( 'Architect Builder', 'pzarchitect' ),
        'post_types' => array( 'page' ),
        'position'   => 'normal', // normal, advanced, side
        'priority'   => 'low', // high, core, default, low - Priorities of placement
        'sections'   => $boxSections,
        'sidebar'    => false
      );

      return $metaboxes;
    }

  }

  new arcBuilderAdmin();

