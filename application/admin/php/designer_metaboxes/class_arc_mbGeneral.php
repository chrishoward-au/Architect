<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 13/2/18
   * Time: 8:56 PM
   */

  define('_amb_general',2700);

  class arc_mbGeneral  extends arc_Blueprints_Designer {

    function __construct( $defaults = FALSE ) {
      parent::__construct( $this->defaults );
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'mb_general', ), 10, 1 );
    }

    /**
     * pzarc_blueprint_layout_general
     *
     * @param array $metaboxes
     *
     * @return array
     */
    function mb_general( $metaboxes, $defaults_only = FALSE ) {
      pzdb( __FUNCTION__ );
      $prefix = '_blueprints_'; // declare prefix
      global $_architect_options;
      $cfwarn          = FALSE;
      $animation_state = ! empty( $_architect_options['architect_animation-enable'] ) ? $_architect_options['architect_animation-enable'] : FALSE;
      if ( is_admin() && ! empty( $_GET['post'] ) ) {
        $cfcount = ( ! empty( $this->postmeta['_panels_design_custom-fields-count'][0] ) ? $this->postmeta['_panels_design_custom-fields-count'][0] : 0 );
      }
      $sections[_amb_general] = array(
          'fields' => array(
              array(
                  'id'       => $prefix . 'short-name',
                  'title'    => __( 'Blueprint Short Name', 'pzarchitect' ) . '<span class="pzarc-required el-icon-star" title="Required"></span>',
                  'type'     => 'text',
                  'subtitle' => __( 'Letters, numbers, dashes only. ', 'pzarchitect' ) . '</strong>' . __( 'Use this in shortcodes, template tags, and CSS classes', 'pzarchitect' ),
                  'hint'     => array(
                      'title'   => 'Blueprint Short Name',
                      'content' => '<strong>' . __( 'Letters, numbers, dashes only. ', 'pzarchitect' ) . '</strong>' . __( 'Use this in shortcodes, template tags, and CSS classes', 'pzarchitect' ),
                  ),
                  //TODO: Write  acomprehensive little help dialog here
                  'validate' => 'not_empty',
                  'default'  => '',
              ),
              array(
                  'id'    => $prefix . 'description',
                  'title' => __( 'Description', 'pzarchitect' ),
                  'type'  => 'textarea',
                  'rows'  => 2,
                  'hint'  => array( 'content' => __( 'A short description to help you or others know what this Blueprint is for', 'pzarchitect' ) ),
              ),
          ),
      );

      $current_theme = wp_get_theme();
      $is_hw         = ( ( $current_theme->get( 'Name' ) == 'Headway' || $current_theme->get( 'Name' ) == 'Headway Base' || $current_theme->get( 'Template' ) == 'headway' ) );
      $is_blox       = ( ( $current_theme->get( 'Name' ) === 'Blox' || $current_theme->get( 'Template' ) == 'blox' ) );

      if ( ! $_architect_options['architect_enable_styling'] ) {
        $sections['_general_bp']['fields'][] = array(
            'id'    => $prefix . 'headway-styling-message',
            'title' => __( 'Architect Styling', 'pzarchitect' ),
            'type'  => 'info',
            'desc'  => __( 'Architect Styling is turned off. You can still style Blueprints using custom CSS, or in the Headway/Blox Visual Editor if that is your theme. You can re-enable it in <em>Architect</em> > <em>Options</em> > <em>Use Architect Styling</em>. Note: Architect styling will take precedence.', 'pzarchitect' ),
        );
      }
      if ( ! $animation_state ) {
        $sections['_general_bp']['fields'][] = array(
            'id'    => $prefix . 'animation-message',
            'title' => __( 'Animation', 'pzarchitect' ),
            'type'  => 'info',
            'desc'  => __( 'To use Animation settings, first enable Animation in <em>Architect</em> > <em>Options</em> > <em>Animation</em>.', 'pzarchitect' ),
        );
      }

//      $sections['_general_bp']['fields'][] = array(
//        'title'    => __( 'Disable Blueprint styles', 'pzarchitect' ),
//        'id'       => '_blueprint_styles',
//        'type'     => 'button_set',
//        'default'  => '',
//        'options'  => array(
//          'on' => __( 'Yes', 'pzarchitect' ),
//          ''  => __( 'No', 'pzarchitect' ),
//        ),
//        'hint'     => array(
//          'title'   => __( 'Disable Blueprint styles', 'pzarchitect' ),
//          'content' => __( 'If you don\'t want to use the Blueprint styling. Only ap[plies to this Blueprint', 'pzarchitect' ),
//        ),
//      );

      $sections[_amb_general]['fields'][] = array(
          'title'    => __( 'Intended Device', 'pzarchitect' ),
          'id'       => '_blueprint_device',
          'type'     => 'button_set',
          'subtitle' => __( 'Choose the device you intend to display this Blueprint on. This is currently for information purposes only. That is, so anyone else working with this Blueprint is aware of why it is configured the way it is.', 'pzarchitect' ),
          'default'  => '',
          'options'  => array(
              ''       => __( 'Any', 'pzarchitect' ),
              'tablet' => __( 'Tablet', 'pzarchitect' ),
              'phone'  => __( 'Phone', 'pzarchitect' ),
          ),
          'hint'     => array(
              'title'   => __( 'Device', 'pzarchitect' ),
              'content' => __( 'Choose the device you intend to display this Blueprint on. This is currently for information purposes only. That is, co anyone else working with this Blueprint is aware.', 'pzarchitect' ),
          ),
      );
      $sections[_amb_general]['fields'][] = array(
          'title'   => 'Getting help',
          'id'      => $prefix . 'help-info',
          'type'    => 'raw',
          'indent'  => FALSE,
          'content' => '<div class="pzarc-help-section">
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a><br>
                        <a class="pzarc-button-help" href="mailto:support@pizazzwp.com?subject=Architect%20help" target="_blank">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Tech support</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/checkout/customer-dashboard/" target="_blank">
                        <span class="dashicons dashicons-admin-users"></span>
                        Customer dashboard</a>
                        </div>
                        <p style="font-size:0.8em;">Architect v' . PZARC_VERSION . '</p>
                        </div>',
      );


      $metaboxes[] = array(
          'id'         => $prefix . 'layout-general-settings',
          'title'      => 'General Settings ',
          'post_types' => array( 'arc-blueprints' ),
          'sections'   => $sections,
          'position'   => 'side',
          'priority'   => 'default',
          'sidebar'    => FALSE,

      );


      return $metaboxes;

    }
  }

  new arc_mbGeneral();