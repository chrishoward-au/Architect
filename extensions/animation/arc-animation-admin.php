<?php

  /**
   * Project pizazzwp-architect.
   * File: arc-animation-admin.php
   * User: chrishoward
   * Date: 13/04/15
   * Time: 2:15 PM
   */
  class arcAnimationAdmin {

    function __construct() {

      global $_architect_options;
      if ( ! isset( $GLOBALS[ '_architect_options' ] ) ) {
        $GLOBALS[ '_architect_options' ] = get_option( '_architect_options', array() );
      }

      add_filter( 'pzarc-extend-options', array( $this, 'options' ) );

      if ( ! empty( $_architect_options[ 'architect_animation-enable' ] ) ) {
        add_action( "redux/metaboxes/_architect/boxes", array( $this, 'pzarc_mb_animation' ), 10, 1 );
        add_filter( 'pzarc_editor_tabs', array( $this, 'add_editor_tabs' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
      }
    }

    function admin_init() {

      wp_enqueue_style( 'css-animate', plugin_dir_url(__FILE__) . '/animate.min.css' );
      wp_enqueue_style( 'arc-animation-css', plugin_dir_url(__FILE__) . '/arc-animation-admin.css', null, PZARC_VERSION );
      wp_enqueue_script( 'arc-animation-js', plugin_dir_url(__FILE__) . '/arc-animation-admin.js', array( 'jquery' ), PZARC_VERSION, true );
    }

    function init() {

    }

    function options( $sections ) {
      $sections[ '_animation' ] = array(
        'title'      => 'Animation',
        'show_title' => true,
        'icon'       => 'el-icon-film',
        'fields'     => array(
          array(
            'title'   => __( 'Enable Animation', 'pzarchitect' ),
            'id'      => 'architect_animation-enable',
            'type'    => 'switch',
            'default' => false,
            'on'      => __( 'Yes', 'pzarchitect' ),
            'off'     => __( 'No', 'pzarchitect' )
          ),
        )
      );

      return $sections;
    }

    /** EDIT
     * This hooks into the Blueprint editor
     *
     * @param $fields
     */
    function add_editor_tabs( $fields ) {
      $fields[ 0 ][ 'options' ][ 'animation' ] = '<span><i class="el el-film"></i> Animation</span>';
      $fields[ 0 ][ 'targets' ][ 'animation' ] = array( 'animation' );

      return $fields;

    }

    // SAVE
    // This hooks into the save process

    // DISPLAY

    // FIELDS
    function pzarc_mb_animation( $metaboxes, $defaults_only = false ) {
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }

      $animation_options = array();
//      $animations        = explode( ',', 'bounce,bounceIn,bounceInDown,bounceInLeft,bounceInRight,bounceInUp,bounceOut,bounceOutDown,bounceOutLeft,bounceOutRight,bounceOutUp,fadeIn,fadeInDown,fadeInDownBig,fadeInLeft,fadeInLeftBig,fadeInRight,fadeInRightBig,fadeInUp,fadeInUpBig,fadeOut,fadeOutDown,fadeOutDownBig,fadeOutLeft,fadeOutLeftBig,fadeOutRight,fadeOutRightBig,fadeOutUp,fadeOutUpBig,flash,flipInX,flipInY,flipOutX,flipOutY,hinge,lightSpeedIn,lightSpeedOut,pulse,rollIn,rollOut,rotateIn,rotateInDownLeft,rotateInDownRight,rotateInUpLeft,rotateInUpRight,rotateOut,rotateOutDownLeft,rotateOutDownRight,rotateOutUpLeft,rotateOutUpRight,rubberBand,shake,slideInDown,slideInLeft,slideInRight,slideInUp,slideOutDown,slideOutLeft,slideOutRight,slideOutUp,swing,tada,wobble,zoomIn,zoomInDown,zoomInLeft,zoomInRight,zoomInUp,zoomOut,zoomOutDown,zoomOutLeft,zoomOutRight,zoomOutUp' );

      $animations = explode( ',', 'bounce,bounceIn,bounceInDown,bounceInLeft,bounceInRight,bounceInUp,fadeIn,fadeInDown,fadeInDownBig,fadeInLeft,fadeInLeftBig,fadeInRight,fadeInRightBig,fadeInUp,fadeInUpBig,flash,flipInX,flipInY,hinge,lightSpeedIn,pulse,rollIn,rotateIn,rotateInDownLeft,rotateInDownRight,rotateInUpLeft,rotateInUpRight,rubberBand,shake,slideInDown,slideInLeft,slideInRight,slideInUp,swing,tada,wobble,zoomIn,zoomInDown,zoomInLeft,zoomInRight,zoomInUp' );

      foreach ( $animations as $animation ) {
        $animation_options[ $animation ] = $animation;
      }
      $prefix                  = '_animation_';
      $sections                = array();
      $sections[ '_settings' ] = array(
        'title'      => __( 'Settings ', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-adjust-alt',
        'fields'     => array(
          array(
            'title'   => __( 'Animate Panels or Content', 'pzarchitect' ),
            'id'      => $prefix . 'target',
            'type'    => 'button_set',
            'default' => 'panels',
            //            'subtitle' => __( '', 'pzarchitect' ),
            'options' => array(
              'panels' => __( 'Panels', 'pzarchitect' ),
              'fields' => __( 'Content', 'pzarchitect' ),
            )
          ),
          array(
            'title'    => 'Content sequence',
            'id'       => $prefix . 'content-sequence-start-section',
            'type'     => 'section',
            'required' => array(
              array( '_animation_target', '=', 'fields' ),
            ),
            'indent'   => true,
          ),
          array(
            'id'       => $prefix . 'sequence',
            'type'     => 'select',
            'multi'    => true,
            'sortable' => true,
            'title'    => 'Animation order',
            'desc'     => 'Drag and drop to activate and arrange the order of the content animation',
            'required' => array( '_animation_target', '=', 'fields' ),
            'options'  => array(
              'title'   => 'Title',
              'meta1'   => 'Meta1',
              'meta2'   => 'Meta2',
              'meta3'   => 'Meta3',
              'content' => 'Body/Excerpt',
              'feature' => 'Featured image',
              'custom1' => 'Custom fields group 1',
              'custom2' => 'Custom fields group 2',
              'custom3' => 'Custom fields group 3'
            ),
            'default'  => array(),
          ),
          array(
            'title'    => __( 'Content synchronization', 'pzarchitect' ),
            'id'       => $prefix . 'sequence-sync',
            'type'     => 'button_set',
            'default'  => 'serial',
            'subtitle' => __( ' Set whether content animation should happen in the order set or simultaneously. This does not affect the individual synchronization', 'pzarchitect' ),
            'options'  => array(
              'serial'   => __( 'As ordered', 'pzarchitect' ),
              'parallel' => __( 'Simultaneously', 'pzarchitect' ),
            )
          ),
          array(
            'id'     => $prefix . 'conntent-sequence-end-section',
            'type'   => 'section',
            'indent' => false,
          )

        )
      );
      $sections[ '_panels' ]   = array(
        'title'      => __( 'Panels ', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-th-large',
        'fields'     => array(
          array(
            'id'       => $prefix . 'panels-demo',
            'type'     => 'info',
            'class'    => $prefix . 'panels-demo animation-demo',
            'default'  => __( 'Animation Demonstration', 'pzarchitect' ),
            'required' => array(
              array( '_animation_target', '=', 'panels' ),
            )
          ),
          array(
            'title'    => 'Panels',
            'id'       => $prefix . 'panels-start-section',
            'type'     => 'section',
            'required' => array(
              array( '_animation_target', '=', 'panels' ),
              //              array( '_animation_sequence', '=', 'components' ),
              //              array( '_animation_sequence', '=', 'blueprint' )
            ),
            'indent'   => true,
          ),
          array(
            'title'   => __( 'Animation', 'pzarchitect' ),
            'id'      => $prefix . 'panels-animation',
            'type'    => 'select',
            'class'   => 'js--animations ' . $prefix . 'panels',
            'options' => $animation_options,
            'select2' => array( 'allowClear' => false ),
            'default' => 'none',
          ),
          array(
            'title'      => __( 'Duration', 'pzarchitect' ),
            'id'         => $prefix . 'panels-duration',
            'type'       => 'slider',
            'default'    => 0.5,
            'min'        => 0,
            'step'       => 0.1,
            'max'        => 10,
            'resolution' => 0.1
          ),
          array(
            'title'      => __( 'Delay', 'pzarchitect' ),
            'id'         => $prefix . 'panels-delay',
            'type'       => 'slider',
            'default'    => 0,
            'min'        => 0,
            'step'       => 0.1,
            'max'        => 20,
            'resolution' => 0.1
          ),
          array(
            'title'    => __( 'Synchronization', 'pzarchitect' ),
            'id'       => $prefix . 'panels-sync',
            'type'     => 'button_set',
            'default'  => 'serial',
            'subtitle' => __( 'Where there are multiple posts, should animations for this component type happen simultaneously or consecutively', 'pzarchitect' ),
            'options'  => array(
              'serial'   => __( 'Consecutively', 'pzarchitect' ),
              'parallel' => __( 'Simultaneously', 'pzarchitect' ),
              'random'   => __( 'Randomly', 'pzarchitect' )
            )
          ),
          array(
            'title'      => __( 'Overlap (%)', 'pzarchitect' ),
            'subtitle'   => __( 'An overlap of zero will wait until the previous animation completes, an over lap of 50 will wait until it is half completed' ),
            'id'         => $prefix . 'panels-overlap',
            'type'       => 'slider',
            'required'   => array( $prefix . 'panels-sync', '=', 'serial' ),
            'default'    => 50,
            'min'        => 0,
            'step'       => 1,
            'max'        => 100,
            'resolution' => 1
          ),
          array(
            'id'     => $prefix . 'panels-end-section',
            'type'   => 'section',
            'indent' => false,
          )
        )

      );

      $sections[ '_titles' ]  = array(
        'title'      => __( 'Titles ', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-font',
        'fields'     => self::animation_fields( $prefix . 'title', $animation_options, 'Titles', 'title' )
      );
      $sections[ '_meta' ]    = array(
        'title'      => __( 'Meta groups', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-calendar',
        'fields'     => pzarc_fields( self::animation_fields( $prefix . 'meta1', $animation_options, 'Meta 1', 'meta1' ), self::animation_fields( $prefix . 'meta2', $animation_options, 'Meta 2', 'meta2' ), self::animation_fields( $prefix . 'meta3', $animation_options, 'Meta 3', 'meta3' ) )

      );
      $sections[ '_content' ] = array(
        'title'      => __( 'Body/Excerpt', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-align-left',
        'fields'     => self::animation_fields( $prefix . 'content', $animation_options, 'Body/Excerpt', 'content' )
      );
      $sections[ '_feature' ] = array(
        'title'      => __( 'Featured image', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-picture',
        'fields'     => self::animation_fields( $prefix . 'feature', $animation_options, 'Feature', 'feature' )
      );
      $sections[ '_custom' ]  = array(
        'title'      => __( 'Custom field groups ', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-wrench',
        'fields'     => pzarc_fields( self::animation_fields( $prefix . 'customfieldgroup1', $animation_options, 'Custom Field Group 1', 'custom1' ), self::animation_fields( $prefix . 'customfieldgroup2', $animation_options, 'Custom Field Group 2', 'custom2' ), self::animation_fields( $prefix . 'customfield3group', $animation_options, 'Custom Field Group 3', 'custom3' ) )

      );
      $metaboxes[ ]           = array(
        'id'         => 'animation',
        'title'      => 'Animation',
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => true

      );

      //pzdebug($metaboxes);

//        foreach ($metaboxes as $k => $v) {
//          var_dump($v['id'],$v['post_types']);
//        }


      return $metaboxes;
    }

    private function animation_fields( $component = null, $animation_options = array(), $section_title = null, $section = null ) {
      $fields = array(
        array(
          'id'       => $component . '-demo',
          'type'     => 'info',
          'class'    => $component . '-demo animation-demo',
          'default'  => __( 'Animation Demonstration', 'pzarchitect' ),
          'required' => array(
            array( '_animation_sequence', 'contains', $section ),
            array( '_animation_target', '=', 'fields' ),
          )
        ),
        ( $section_title ?
          array(
            'title'    => $section_title,
            'id'       => $component . 'start-section',
            'type'     => 'section',
            'required' => array(
              array( '_animation_sequence', 'contains', $section ),
              array( '_animation_target', '=', 'fields' ),
            ),
            'indent'   => true,
          ) : null ),
        array(
          'title'   => __( 'Animation', 'pzarchitect' ),
          'id'      => $component . '-animation',
          'type'    => 'select',
          'class'   => 'js--animations ' . $component,
          'options' => $animation_options,
          'select2' => array( 'allowClear' => false ),
          'default' => 'none',
        ),
        array(
          'title'      => __( 'Duration', 'pzarchitect' ),
          'id'         => $component . '-duration',
          'type'       => 'slider',
          'default'    => 0.5,
          'min'        => 0,
          'step'       => 0.1,
          'max'        => 10,
          'resolution' => 0.1
        ),
        array(
          'title'      => __( 'Delay', 'pzarchitect' ),
          'id'         => $component . '-delay',
          'type'       => 'slider',
          'default'    => 0,
          'min'        => 0,
          'step'       => 0.1,
          'max'        => 20,
          'resolution' => 0.1
        ),
        array(
          'title'    => __( 'Synchronization', 'pzarchitect' ),
          'id'       => $component . '-sync',
          'type'     => 'button_set',
          'default'  => 'serial',
          'subtitle' => __( 'Where there are multiple posts, should animations for this component type happen simultaneously or consecutively', 'pzarchitect' ),
          'options'  => array(
            'serial'   => __( 'Consecutively', 'pzarchitect' ),
            'parallel' => __( 'Simultaneously', 'pzarchitect' ),
            'random'   => __( 'Randomly', 'pzarchitect' )
          )
        ),
        array(
          'title'      => __( 'Overlap (%)', 'pzarchitect' ),
          'subtitle'   => __( 'An overlap of zero will wait until the previous animation completes, an over lap of 50 will wait until it is half completed' ),
          'id'         => $component . '-overlap',
          'type'       => 'slider',
          'required'   => array( $component . '-sync', '=', 'serial' ),
          'default'    => 50,
          'min'        => 0,
          'step'       => 1,
          'max'        => 100,
          'resolution' => 1
        ),
        ( $section_title ?
          array(
            'id'     => $component . 'end-section',
            'type'   => 'section',
            'indent' => false,
          ) : null ),


      );

      return $fields;
    }


  }

