<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 13/2/18
   * Time: 8:55 PM
   */

  define('_amb_tabs',3200);

  /**
   * @param      $metaboxes
   * @param bool $defaults_only
   *
   * @return array
   */
  class arc_mbTabs extends arc_Blueprints_Designer {

    function __construct( $defaults = FALSE ) {
      parent::__construct( $defaults );
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'mb_tabs', ), 10, 1 );
    }

    function mb_tabs( $metaboxes, $defaults_only = FALSE ) {
      pzdb( __FUNCTION__ );
      $prefix   = '_blueprint_tabs_'; // declare prefix
      $sections = array();
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }
      $fields = array();
//      if (!empty($_architect_options[ 'architect_enable_styling' ])) {
      $fields = array(
          array(
              'id'      => $prefix . 'tabs',
              'type'    => 'tabbed',
              'default' => 'layout',
              'options' => array(
                  'layout'       => '<span class="pzarc-tab-title">' . __( 'Blueprint', 'pzarchitect' ) . '</span>',
                  'type'         => '<span class="pzarc-tab-title pzarc-blueprint-type">' . __( '', 'pzarchitect' ) . '</span>',
                  'content'      => '<span class="pzarc-tab-title">' . __( 'Source', 'pzarchitect' ) . '</span>',
                  'panels'       => '<span class="pzarc-tab-title">' . __( 'Panels', 'pzarchitect' ) . '</span>',
                  'titles'       => '<span class="pzarc-tab-title">' . __( 'Title', 'pzarchitect' ) . '</span>',
                  'meta'         => '<span class="pzarc-tab-title">' . __( 'Meta', 'pzarchitect' ) . '</span>',
                  'features'     => '<span class="pzarc-tab-title">' . __( 'Feature', 'pzarchitect' ) . '</span>',
                  'body'         => '<span class="pzarc-tab-title">' . __( 'Body/Excerpt', 'pzarchitect' ) . '</span>',
                  'customfields' => '<span class="pzarc-tab-title">' . __( 'Custom Fields', 'pzarchitect' ) . '</span>',
                  //                    'content_styling' => '<span class="pzarc-tab-title">Content Styling</span>',
                  //                    'styling'         => '<span class="pzarc-tab-title">Blueprint Styling</span>',
              ),
              'targets' => array(
                  'layout'       => array( 'layout-settings' ),
                  'type'         => array( 'type-settings' ),
                  'content'      => array( 'content-selections' ),
                  'panels'       => array( 'panels-design' ),
                  'titles'       => array( 'titles-settings' ),
                  'meta'         => array( 'meta-settings' ),
                  'features'     => array( 'features-settings' ),
                  'body'         => array( 'body-settings' ),
                  'customfields' => array( 'customfields-settings' ),
                  //                    'content_styling' => array('panels-styling'),
                  //                    'styling'         => array('blueprint-stylings'),
                  //                    'presets'         => array('presets'),
              ),
          ),
      );

//      } else {
//        $fields = array(
//            array(
//                'id'      => $prefix . 'tabs',
//                'type'    => 'tabbed',
//                'options' => array(
//                    'layout'  => '<span><span class="stepno">1</span> <span class="pzarc-tab-title">Blueprint Design</span></span>',
//                    'content' => '<span><span class="stepno">2</span> <span class="pzarc-tab-title">Content Selection</span></span>',
//                    'panels'  => '<span><span class="stepno">3</span> <span class="pzarc-tab-title">Content Layout</span></span>',
//                ),
//                'targets' => array(
//                    'layout'  => array('layout-settings'),
//                    'content' => array('content-selections'),
//                    'panels'  => array('panels-design'),
//                )
//            ),
//        );
//      }

      $fields = apply_filters( 'arc_editor_tabs', $fields );

      $sections[_amb_tabs] = array(//          'title'      => __('General Settings', 'pzarchitect'),
                                            'show_title' => TRUE,
                                            'icon_class' => 'icon-large',
                                            'icon'       => 'el-icon-home',
                                            'fields'     => $fields,
      );
      $metaboxes[]                  = array(
          'id'         => $prefix . 'blueprints',
          //'title'      => __('Sections:', 'pzarchitect'),
          'post_types' => array( 'arc-blueprints' ),
          'sections'   => $sections,
          'position'   => 'normal',
          'priority'   => 'high',
          'sidebar'    => FALSE,

      );

      return $metaboxes;
    }

//    function pzarc_mb_blueprint_presets($metaboxes, $defaults_only = false)
//    {
//      $fields = array(
//          array(
//              'id'      => '_presets_choose',
//              'title'   => 'Choose a preset (optional)',
//              'type'    => 'image_select',
//              'default' => 'none',
//              'height'  => '200px',
//              'options' => array(
//                  'none'                    => array(
//                      'alt' => 'Build your own',
//                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/presets-none.png'
//                  ),
//                  'featured-posts-slider'   => array(
//                      'alt' => 'Featured Posts Slider',
//                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/preset-featured-posts-slider.jpg'
//                  ),
//                  'gallery-carousel'        => array(
//                      'alt' => 'Gallery carousel',
//                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/preset-gallery-carousel.jpg'
//                  ),
//                  'full-width-image-slider' => array(
//                      'alt' => 'Full width image slider',
//                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/presets-full-width-image-slider.png'
//                  ),
//                  'featured-video'          => array(
//                      'alt' => 'Featured videos',
//                      'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/presets-featured-video.png'
//                  ),
//              ),
//          ),
//      );
//
//      $sections[]  = array(
//        //          'title'      => __('General Settings', 'pzarchitect'),
//        'show_title' => true,
//        'icon_class' => 'icon-large',
//        'icon'       => 'el-icon-home',
//        'fields'     => $fields
//      );
//      $metaboxes[] = array(
//          'id'         => 'presets',
//          'title'      => __('Presets', 'pzarchitect'),
//          'post_types' => array('arc-blueprints'),
//          'sections'   => $sections,
//          'position'   => 'normal',
//          'priority'   => 'low',
//          'sidebar'    => false
//
//      );
//
//      return $metaboxes;
//    }


    // TODO: ADD FILTER OPTION FOR RELATED POSTS

  }

new arc_mbTabs();







