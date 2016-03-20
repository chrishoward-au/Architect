<?php
  /**
   * Project pizazzwp-architect.
   * File: class_componentTitles.php
   * User: chrishoward
   * Date: 18/03/2016
   * Time: 10:20 PM
   */

  add_action( "redux/metaboxes/_architect/boxes",  'pzarc_mb_titles_settings' , 10, 1 );

  /**
   * TITLES
   *
   * @param $metaboxes
   *
   * @return array
   */
  function pzarc_mb_titles_settings( $metaboxes, $defaults_only = false ) {
    global $_architect;
    global $_architect_options;
    if ( empty( $_architect_options ) ) {
      $_architect_options = get_option( '_architect_options' );
    }

    if ( empty( $_architect ) ) {
      $_architect = get_option( '_architect' );
    }
    $prefix     = '_panels_design_';
    $sections   = array();
    $sections[] = array(
      'title'      => 'Titles settings',
      'show_title' => false,
      'icon_class' => 'icon-large',
      'icon'       => 'el-icon-adjust-alt',
      'fields'     => array(
        array(
          'title'   => __( 'Title prefix', 'pzarchitect' ),
          'id'      => $prefix . 'title-prefix',
          'type'    => 'select',
          'select2' => array( 'allowClear' => false ),
          'default' => 'none',
          'class'   => ' horizontal',
          //'required' => array('show_advanced', 'equals', true),
          'options' => array(
            'none'                 => __( 'None', 'pzarchitect' ),
            'disc'                 => __( 'Disc', 'pzarchitect' ),
            'circle'               => __( 'Circle', 'pzarchitect' ),
            'square'               => __( 'Square', 'pzarchitect' ),
            'thumb'                => __( 'Thumbnail', 'pzarchitect' ),
            'decimal'              => __( 'Number', 'pzarchitect' ),
            'decimal-leading-zero' => __( 'Number with leading zero', 'pzarchitect' ),
            'lower-alpha'          => __( 'Alpha lower', 'pzarchitect' ),
            'upper-alpha'          => __( 'Alpha upper', 'pzarchitect' ),
            'lower-roman'          => __( 'Roman lower', 'pzarchitect' ),
            'upper-roman'          => __( 'Roman upper', 'pzarchitect' ),
            'lower-greek'          => __( 'Greek lower', 'pzarchitect' ),
            'upper-greek'          => __( 'Greek upper', 'pzarchitect' ),
            'lower-latin'          => __( 'Latin lower', 'pzarchitect' ),
            'upper-latin'          => __( 'Latin upper', 'pzarchitect' ),
            'armenian'             => __( 'Armenian', 'pzarchitect' ),
            'georgian'             => __( 'Georgian', 'pzarchitect' ),
          ),
        ),
        array(
          'id'             => $prefix . 'title-margins',
          'type'           => 'spacing',
          'mode'           => 'margin',
          'units'          => array( 'px' ),
          'units_extended' => 'false',
          'title'          => __( 'Title margins', 'pzarchitect' ),
          'desc'           => __( 'You must set a left margin on titles for bullets to show.', 'pzarchitect' ),
          'default'        => array(
            'margin-right' => '0',
            'margin-left'  => '20',
            'units'        => 'px',
          ),
          'top'            => false,
          'bottom'         => false,
          'left'           => true,
          'right'          => true,
          'required'       => array( '_panels_design_title-prefix', '!=', 'none' ),
        ),
        array(
          'title'         => __( 'Title thumbnail width', 'pzarchitect' ),
          'id'            => $prefix . 'title-thumb-width',
          'type'          => 'spinner',
          'default'       => 32,
          'min'           => 8,
          'max'           => 1000,
          'step'          => 1,
          'display_value' => 'label',
          'required'      => array( '_panels_design_title-prefix', '=', 'thumb' ),
          'hint'          => array( 'content' => __( '', 'pzarchitect' ) ),
        ),
        array(
          'title'    => __( 'Prefix separator', 'pzarchitect' ),
          'id'       => $prefix . 'title-bullet-separator',
          'type'     => 'text',
          'class'    => 'textbox-small',
          'default'  => '. ',
          'required' => array(
            array( '_panels_design_title-prefix', '!=', 'none' ),
            array( '_panels_design_title-prefix', '!=', 'disc' ),
            array( '_panels_design_title-prefix', '!=', 'circle' ),
            array( '_panels_design_title-prefix', '!=', 'square' ),
            array( '_panels_design_title-prefix', '!=', 'thumb' )
          )
        ),
        array(
          'title'   => __( 'Link titles', 'pzarchitect' ),
          'id'      => $prefix . 'link-titles',
          'type'    => 'switch',
          'on'      => __( 'Yes', 'pzarchitect' ),
          'off'     => __( 'No', 'pzarchitect' ),
          'default' => true,
          'hint'    => array( 'content' => __( 'If enabled, clicking on the Title will take the viewer to the post.', 'pzarchitect' ) ),

          /// can't set defaults on checkboxes!
        ),
        array(
          'title'    => __( 'Use Headway alternate titles', 'pzarchitect' ),
          'subtitle' => __( 'Headway theme only', 'pzarchitect' ),
          'id'       => $prefix . 'alternate-titles',
          'type'     => 'switch',
          'on'       => __( 'Yes', 'pzarchitect' ),
          'off'      => __( 'No', 'pzarchitect' ),
          'default'  => true,
          'hint'     => array( 'content' => __( 'If enabled, this will display the Headway alternative title if set. Note: If you change from Headway to another theme, this may still be displayed.', 'pzarchitect' ) ),

          /// can't set defaults on checkboxes!
        ),
        array(
          'title'    => __( 'Wrapper tag', 'pzarchitect' ),
          'id'       => $prefix . 'title-wrapper-tag',
          'type'     => 'select',
          'default'  => 'h1',
          'options'  => array(
            'h1'   => 'h1',
            'h2'   => 'h2',
            'h3'   => 'h3',
            'h4'   => 'h4',
            'h5'   => 'h5',
            'h6'   => 'h6',
            'p'    => 'p',
            'span' => 'span'
          ),
          'subtitle' => __( 'Select the wrapper element for the title field', 'pzarchitect' )

        ),
      )
    );
    $sections[] = array(
      'title'      => __( 'Responsive overrides', 'pzarchitect' ),
      'show_title' => false,
      'icon_class' => 'icon-large',
      'icon'       => 'el-icon-screen',
      'fields'     => array(

        array(
          'title'    => __( 'Use responsive font sizes', 'pzarchitect' ),
          'id'       => $prefix . 'use-responsive-font-size-title',
          'type'     => 'switch',
          'default'  => false,
          //'required' => array('show_advanced', 'equals', true),
          'subtitle' => __( 'Enabling this will override all other CSS title sizing', 'pzarchitect' )
        ),
        array(
          'title'    => __( 'Fluid fonts', 'pzarchitect' ),
          'id'       => $prefix . 'use-scale-fonts-title',
          'type'     => 'switch',
          'default'  => true,
          'required' => array( $prefix . 'use-responsive-font-size-title', 'equals', true ),
          'subtitle' => __( 'This makes the fonts scale in size from one breakpoint to the next, rather than suddenly changing at each breakpoint.', 'pzarchitect' )
        ),
        array(
          'id'              => $prefix . 'title-font-size-bp1',
          'title'           => __( 'Maximum font size - large screen ', 'pzarchitect' ),
          'subtitle'        => $_architect_options[ 'architect_breakpoint_1' ][ 'width' ] . __( ' and above', 'pzarchitect' ),
          'required'        => array( $prefix . 'use-responsive-font-size-title', 'equals', true ),
          'type'            => 'typography',
          'default'         => array(),
          'text-decoration' => false,
          'font-variant'    => false,
          'text-transform'  => false,
          'font-family'     => false,
          'font-size'       => true,
          'font-weight'     => false,
          'font-style'      => false,
          'font-backup'     => false,
          'google'          => false,
          'subsets'         => false,
          'custom_fonts'    => false,
          'text-align'      => false,
          //'text-shadow'       => false, // false
          'color'           => false,
          'preview'         => false,
          'line-height'     => true,
          'word-spacing'    => false,
          'letter-spacing'  => false,
        ),
        array(
          'id'              => $prefix . 'title-font-size-bp2',
          'title'           => __( 'Font size - medium screen ', 'pzarchitect' ),
          'subtitle'        => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' to ' . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ],
          'required'        => array(
            array( $prefix . 'use-responsive-font-size-title', 'equals', true ),
            array( $prefix . 'use-scale-fonts-title', 'equals', false )
          ),
          'type'            => 'typography',
          'default'         => array(),
          'text-decoration' => false,
          'font-variant'    => false,
          'text-transform'  => false,
          'font-family'     => false,
          'font-size'       => true,
          'font-weight'     => false,
          'font-style'      => false,
          'font-backup'     => false,
          'google'          => false,
          'subsets'         => false,
          'custom_fonts'    => false,
          'text-align'      => false,
          //'text-shadow'       => false, // false
          'color'           => false,
          'preview'         => false,
          'line-height'     => true,
          'word-spacing'    => false,
          'letter-spacing'  => false,
        ),
        array(
          'id'              => $prefix . 'title-font-size-bp3',
          'title'           => __( 'Minimum font size - small screen ', 'pzarchitect' ),
          'subtitle'        => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' and below',
          'required'        => array( $prefix . 'use-responsive-font-size-title', 'equals', true ),
          'type'            => 'typography',
          'default'         => array(),
          'text-decoration' => false,
          'font-variant'    => false,
          'text-transform'  => false,
          'font-family'     => false,
          'font-size'       => true,
          'font-weight'     => false,
          'font-style'      => false,
          'font-backup'     => false,
          'google'          => false,
          'subsets'         => false,
          'custom_fonts'    => false,
          'text-align'      => false,
          //'text-shadow'       => false, // false
          'color'           => false,
          'preview'         => false,
          'line-height'     => true,
          'word-spacing'    => false,
          'letter-spacing'  => false,
        ),
        array(
          'id'       => $prefix . 'title-font-scale-upper-bp',
          'title'    => __( 'Override large screen breakpoint', 'pzarchitect' ),
          'type'     => 'text',
          'default'  => 1280,
          'subtitle' => __( 'Above this window width, no scaling will be done.' ),
          'required' => array(
            array( $prefix . 'use-responsive-font-size-title', 'equals', true ),
            array( $prefix . 'use-scale-fonts-title', 'equals', true )
          ),
        ),
        array(
          'id'       => $prefix . 'title-font-scale-lower-bp',
          'title'    => __( 'Override small screen breakpoint', 'pzarchitect' ),
          'type'     => 'text',
          'default'  => 360,
          'subtitle' => __( 'Below this window width, no scaling will be done.' ),
          'required' => array(
            array( $prefix . 'use-responsive-font-size-title', 'equals', true ),
            array( $prefix . 'use-scale-fonts-title', 'equals', true )
          ),
        )

      )
    );
    /**
     * TITLES
     */
    // architect_config_entry-title-font-margin
    if ( ! empty( $_architect_options[ 'architect_enable_styling' ] ) ) {
      $defaults = get_option( '_architect' );
      $prefix   = '_panels_styling_'; // declare prefix

      $font       = '-font';
      $link       = '-links';
      $padding    = '-padding';
      $margin     = '-margin';
      $background = '-background';
      $border     = '-borders';

      $stylingSections = array();
      $optprefix       = 'architect_config_';

      $sections[] = array(
        'title'      => __( 'Titles styling', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-brush',
        'desc'       => 'Class: .entry-title<br><strong class="arc-important">' . __( 'Note: If your Titles are linked, you will need to set their colour in the Links section', 'pzarchitect' ) . '</strong>',
        'fields'     => pzarc_fields(
          pzarc_redux_font( $prefix . 'entry-title' . $font, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font ] ),
          pzarc_redux_bg( $prefix . 'entry-title' . $font . $background, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font . $background ] ),
          pzarc_redux_padding( $prefix . 'entry-title' . $font . $padding, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font . $padding ] ),
          pzarc_redux_margin( $prefix . 'entry-title' . $font . $margin, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font . $margin ], 'tb' ),
          pzarc_redux_borders( $prefix . 'entry-title' . $border, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $border ] ),
          pzarc_redux_links( $prefix . 'entry-title' . $font . $link, array( '.entry-title a' ), $defaults[ $optprefix . 'entry-title' . $font . $link ] )
        ),
      );
    }
    $metaboxes[] = array(
      'id'         => 'titles-settings',
      'title'      => __( 'Titles settings and styling.', 'pzarchitect' ),
      'post_types' => array( 'arc-blueprints' ),
      'sections'   => $sections,
      'position'   => 'normal',
      'priority'   => 'default',
      'sidebar'    => false

    );

    return $metaboxes;

  }
