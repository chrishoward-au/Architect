<?php
/**
 * Project pizazzwp-architect.
 * File: arc-animation-admin.php
 * User: chrishoward
 * Date: 13/04/15
 * Time: 2:15 PM
 */

  if ( defined( 'PZARC_TESTER' ) && PZARC_TESTER ) {
  }

  class arcAnimationAdmin {

    function __construct(){

      add_action( "redux/metaboxes/_architect/boxes", array( $this, 'pzarc_mb_animation' ), 10, 1 );
      add_filter('pzarc_editor_tabs',array($this,'add_editor_tabs'));
      add_action('admin_init',array($this,'admin_init'));

    }

    function admin_init() {

      wp_enqueue_style('arc-animation-css',PZARC_PLUGIN_URL.'/extensions/css/arc-animation-admin.css');
    }

    function init() {

    }

    /** EDIT
     * This hooks into the Blueprint editor
     * @param $fields
     */
    function add_editor_tabs($fields) {
      $fields[ 0 ][ 'options' ][ 'animation' ] = '<span><i class="el el-film"></i> Animation</span>';
      $fields[ 0 ][ 'targets' ][ 'animation' ] = array( 'animations' );
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

      $animations = explode( ',', 'bounce,bounceIn,bounceInDown,bounceInLeft,bounceInRight,bounceInUp,bounceOut,bounceOutDown,bounceOutLeft,bounceOutRight,bounceOutUp,fadeIn,fadeInDown,fadeInDownBig,fadeInLeft,fadeInLeftBig,fadeInRight,fadeInRightBig,fadeInUp,fadeInUpBig,fadeOut,fadeOutDown,fadeOutDownBig,fadeOutLeft,fadeOutLeftBig,fadeOutRight,fadeOutRightBig,fadeOutUp,fadeOutUpBig,flash,flipInX,flipInY,flipOutX,flipOutY,hinge,lightSpeedIn,lightSpeedOut,pulse,rollIn,rollOut,rotateIn,rotateInDownLeft,rotateInDownRight,rotateInUpLeft,rotateInUpRight,rotateOut,rotateOutDownLeft,rotateOutDownRight,rotateOutUpLeft,rotateOutUpRight,rubberBand,shake,slideInDown,slideInLeft,slideInRight,slideInUp,slideOutDown,slideOutLeft,slideOutRight,slideOutUp,swing,tada,wobble,zoomIn,zoomInDown,zoomInLeft,zoomInRight,zoomInUp,zoomOut,zoomOutDown,zoomOutLeft,zoomOutRight,zoomOutUp' );
      foreach ( $animations as $animation ) {
        $animation_options[ $animation ] = $animation;
      }
      $prefix                  = '_animation_';
      $sections                = array();
      $sections[ '_sequence' ] = array(
        'title'      => __( 'Sequence', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-random',
        'fields'     => array(
          array(
            'id'      => $prefix . 'sequence',
            'type'    => 'select',
            'multi'=>true,
            'sortable'=>true,
            'title'   => 'Animation order',
            'desc'    => 'Drag and drop to activate and arrange the order of the components animation',
            'options' => array(
                'panels'  => 'Panels',
                'titles'  => 'Titles',
                'meta'    => 'Meta',
                'content' => 'Body/Excerpt',
                'feature' => 'Featured image',
                'custom'  => 'Custom fields'
            ),
          ),
        ),
      );
      $sections[ '_panels' ]   = array(
        'title'      => __( 'Panels ', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-th-large',
        'desc'       => '<a href="http://daneden.github.io/animate.css" target=_blank>Visit Animate.CSS for demonstrations</a>',
        'fields'     => self::animation_fields( $prefix . 'panel', $animation_options,'Panels','panels' )
      );
      $sections[ '_titles' ]   = array(
        'title'      => __( 'Titles ', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-font',
        'desc'       => '<a href="http://daneden.github.io/animate.css" target=_blank>Visit Animate.CSS for demonstrations</a>',
        'fields'     => self::animation_fields( $prefix . 'title', $animation_options,'Titles','titles' )
      );
      $sections[ '_meta' ]     = array(
        'title'      => __( 'Meta groups', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-calendar',
        'desc'       => '<a href="http://daneden.github.io/animate.css" target=_blank>Visit Animate.CSS for demonstrations</a>',
        'fields'     => pzarc_fields( self::animation_fields( $prefix . 'meta1', $animation_options, 'Meta 1','meta' ), self::animation_fields( $prefix . 'meta2', $animation_options, 'Meta 2','meta' ), self::animation_fields( $prefix . 'meta3', $animation_options, 'Meta 3','meta' ) )

      );
      $sections[ '_content' ]  = array(
        'title'      => __( 'Body/Excerpt', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-align-left',
        'desc'       => '<a href="http://daneden.github.io/animate.css" target=_blank>Visit Animate.CSS for demonstrations</a>',
        'fields'     => self::animation_fields( $prefix . 'content', $animation_options,'Body/Excerpt','content' )
      );
      $sections[ '_feature' ]  = array(
        'title'      => __( 'Featured image', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-picture',
        'desc'       => '<a href="http://daneden.github.io/animate.css" target=_blank>Visit Animate.CSS for demonstrations</a>',
        'fields'     => self::animation_fields( $prefix . 'feature', $animation_options,'Feature','feature' )
      );
      $sections[ '_custom' ]   = array(
        'title'      => __( 'Custom field groups ', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-wrench',
        'desc'       => '<a href="http://daneden.github.io/animate.css" target=_blank>Visit Animate.CSS for demonstrations</a>',
        'fields'     => pzarc_fields( self::animation_fields( $prefix . 'customfield1', $animation_options, 'Custom Field 1','custom' ), self::animation_fields( $prefix . 'customfield2', $animation_options, 'Custom Field 2','custom' ), self::animation_fields( $prefix . 'customfield3', $animation_options, 'Custom Field 3','custom' ) )

      );
      $metaboxes[ ]            = array(
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

    private function animation_fields( $component = null, $animation_options = array(), $section_title = null,$section =null ) {
      $fields = array(
        ( $section_title ?
          array(
            'title'  => $section_title,
            'id'     => $component . 'start-section',
            'type'   => 'section',
            'required' => array( '_animation_sequence', 'contains', $section ),
            'indent' => true,
          ) : null ),
        array(
          'title'    => __( 'Animation', 'pzarchitect' ),
          'id'       => $component . '-animation',
          'type'     => 'select',
          'options'  => $animation_options,
          'select2'  => array( 'allowClear' => false ),
          'default'  => 'none',
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
 new arcAnimationAdmin();
