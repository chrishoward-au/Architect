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

      // These are all the inbound animations. The outbound ones can be set later for slides.
      $animations = explode( ',', 'bounce,bounceIn,bounceInDown,bounceInLeft,bounceInRight,bounceInUp,fadeIn,fadeInDown,fadeInDownBig,fadeInLeft,fadeInLeftBig,fadeInRight,fadeInRightBig,fadeInUp,fadeInUpBig,flash,flipInX,flipInY,lightSpeedIn,pulse,rollIn,rotateIn,rotateInDownLeft,rotateInDownRight,rotateInUpLeft,rotateInUpRight,rubberBand,shake,slideInDown,slideInLeft,slideInRight,slideInUp,swing,tada,wobble,zoomIn,zoomInDown,zoomInLeft,zoomInRight,zoomInUp' );

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
        'desc'=>__('Architect provides a lot of control over animation. In testing, I found if you try and do too much, it\'s quite easy for this to get very confusing and hard to follow what and why is happening during the playing of the animations. So I would suggest the old rule: <strong>Keep it simple</strong>.<p>Note: Using Masonry and Animation together can and will likely interfere with each other, producing unwanted results.</p>'),
        'fields'     => array(
          array(
            'id'     => $prefix . '-caveats',
            'type'   => 'info',
            'desc'=>__('<p>Animations activate when the element they are attached to becomes visible on the screen and after any delay set for them.</p><p>When setting the synchronization as Consecutive the delay used is the accumulation of the durations of the preceding animations. Thus, the last one could trigger some 30 seconds after the first one. If you need to scroll to see additional posts and their animations, the animations will obey the first rule i.e. you might be waiting 30 seconds for that last animation <strong>after it becomes visible</strong>, not after the first one!</p><p>Considering all this, it\'s not a good idea to use the Consecutive sync when you have many animations being performed and may posts the viewer has to scroll to see.</p><p>In that situation, it\'s better to use the simultaneous sync with an optional short delay on each element or use a high overlap, of 70% or more.</p><p>Animations do require a lot of trial and error to find what works. But most of all <strong>keep it simple</strong>!</p><br>','pzarchitect')
          ),
          array(
            'title'   => __( 'Animate', 'pzarchitect' ),
            'id'      => $prefix . 'target',
            'type'    => 'button_set',
            'default' => 'panels',
            'options' => array(
              'panels' => __( 'Panels', 'pzarchitect' ),
              'fields' => __( 'Content', 'pzarchitect' ),
            )
          ),
//          array(
//            'title'      => __( 'Trigger point', 'pzarchitect' ),
//            'id'         => $prefix . 'trigger-point',
//            'type'       => 'spinner',
//            'subtitle'=>__('When scrolling, this is how far from the bottom of the screen the animation triggers','pzarchitect'),
//            'default'    => 0,
//            'min'        => 0,
//            'step'       => 1,
//            'max'        => 1000,
//          ),
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

          //          array(
//            'title'    => __( 'Content synchronization', 'pzarchitect' ),
//            'id'       => $prefix . 'sequence-sync',
//            'type'     => 'button_set',
//            'default'  => 'serial-panel',
//            'subtitle' => __( ' Set whether content animation should happen in the order of the panels  or simultaneously. This does not affect the individual synchronization', 'pzarchitect' ),
//            'options'  => array(
//              'serial'   => __( 'Consecutively', 'pzarchitect' ),
//              'parallel' => __( 'Simultaneously', 'pzarchitect' ),
//            )
//          ),
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
        'desc' => __( 'Some animations are not as suitable for panels. For example, use fadeInLeftBig rather than slideInLeft', 'pzarchitect' ),
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
            'title'   => __( 'Animation - alternate', 'pzarchitect' ),
            'subtitle'=>__('Display a different animation on alternate panels','pzarchitect'),
            'id'      => $prefix . 'panels-animation-alt',
            'type'    => 'select',
            'class'   => 'js--animations ' . $prefix . 'panels',
            'options' => $animation_options,
            'default' => 'none',
          ),
          array(
            'title'      => __( 'Duration', 'pzarchitect' ),
            'id'         => $prefix . 'panels-duration',
            'type'       => 'slider',
            'subtitle'=>__('This is how long the animation runs for each panel','pzarchitect'),
            'default'    => 1,
            'min'        => 0,
            'step'       => 0.1,
            'max'        => 10,
            'resolution' => 0.1
          ),
          array(
            'title'      => __( 'Delay', 'pzarchitect' ),
            'id'         => $prefix . 'panels-delay',
            'type'       => 'slider',
            'subtitle'=>__('This is the delay before the animation begins. For consecutive it is applied to the first only, for simultaneous, it is applied to each','pzarchitect'),
            'default'    => 1,
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
            ),
            'desc'=>__('In consecutive, the delay is only applied to the first occurrence. In simultaneous, it is applied to each','pzarchitect'),
          ),
          array(
            'title'      => __( 'Overlap (%)', 'pzarchitect' ),
            'subtitle'   => __( 'E.g. An overlap of zero will wait until the previous animation completes, an over lap of 50 will wait until the previous animation is half completed.' ),
            'id'         => $prefix . 'panels-overlap',
            'type'       => 'slider',
            'required'   => array( $prefix . 'panels-sync', '=', 'serial' ),
            'default'    => 50,
            'min'        => 0,
            'step'       => 5,
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
        'fields'     => pzarc_fields( self::animation_fields( $prefix . 'custom1', $animation_options, 'Custom Field Group 1', 'custom1' ), self::animation_fields( $prefix . 'custom2', $animation_options, 'Custom Field Group 2', 'custom2' ), self::animation_fields( $prefix . 'custom3', $animation_options, 'Custom Field Group 3', 'custom3' ) )

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
          'default' => 'none',
        ),
        array(
          'title'      => __( 'Duration', 'pzarchitect' ),
          'id'         => $component . '-duration',
          'type'       => 'slider',
          'subtitle'=>__('This is how long the animation runs for this component','pzarchitect'),
          'default'    => 1,
          'min'        => 0,
          'step'       => 0.1,
          'max'        => 10,
          'resolution' => 0.1
        ),
        array(
          'title'      => __( 'Delay', 'pzarchitect' ),
          'id'         => $component . '-delay',
          'type'       => 'slider',
          'subtitle'=>__('This is the delay before the animation begins. For consecutive it is applied to the first only, for simultaneous, it is applied to each.','pzarchitect'),
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
          'desc'=>__('In consecutive, the delay is only applied to the first occurrence. In simultaneous, it is applied to each.','pzarchitect'),
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
          'default'    => 75,
          'min'        => 0,
          'step'       => 5,
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

