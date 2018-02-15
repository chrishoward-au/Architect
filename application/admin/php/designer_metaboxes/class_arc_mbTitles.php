<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 13/2/18
   * Time: 8:59 PM
   */

  define('_amb_titles',2100);
  define('_amb_titles_help',2199);
  define('_amb_styling_titles',2150);
  define('_amb_titles_responsive',2100);

  /**
   * TITLES
   *
   * @param $metaboxes
   *
   * @return array
   */
  class arc_mbTitles extends arc_Blueprints_Designer {
    function __construct( $defaults = FALSE ) {
      parent::__construct( $defaults );
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'mb_titles', ), 10, 1 );
    }

  function mb_titles( $metaboxes, $defaults_only = FALSE ) {
    pzdb( __FUNCTION__ );
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
    $sections[_amb_titles] = array(
        'title'      => 'Titles settings',
        'show_title' => FALSE,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-adjust-alt',
        'fields'     => array(
            array(
                'title'   => __( 'Title prefix', 'pzarchitect' ),
                'id'      => $prefix . 'title-prefix',
                'type'    => 'select',
                'select2' => array( 'allowClear' => FALSE ),
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
                'top'            => FALSE,
                'bottom'         => FALSE,
                'left'           => TRUE,
                'right'          => TRUE,
                'required'       => array(
                    '_panels_design_title-prefix',
                    '!=',
                    'none',
                ),
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
                'required'      => array(
                    '_panels_design_title-prefix',
                    '=',
                    'thumb',
                ),
                'hint'          => array( 'content' => __( '', 'pzarchitect' ) ),
            ),
            array(
                'title'    => __( 'Prefix separator', 'pzarchitect' ),
                'id'       => $prefix . 'title-bullet-separator',
                'type'     => 'text',
                'class'    => 'textbox-small',
                'default'  => '. ',
                'required' => array(
                    array(
                        '_panels_design_title-prefix',
                        '!=',
                        'none',
                    ),
                    array(
                        '_panels_design_title-prefix',
                        '!=',
                        'disc',
                    ),
                    array(
                        '_panels_design_title-prefix',
                        '!=',
                        'circle',
                    ),
                    array(
                        '_panels_design_title-prefix',
                        '!=',
                        'square',
                    ),
                    array(
                        '_panels_design_title-prefix',
                        '!=',
                        'thumb',
                    ),
                ),
            ),
            array(
                'title'   => __( 'Link titles', 'pzarchitect' ),
                'id'      => $prefix . 'link-titles',
                'type'    => 'switch',
                'on'      => __( 'Yes', 'pzarchitect' ),
                'off'     => __( 'No', 'pzarchitect' ),
                'default' => TRUE,
                'hint'    => array( 'content' => __( 'If enabled, clicking on the Title will take the viewer to the post.', 'pzarchitect' ) ),

                /// can't set defaults on checkboxes!
            ),
            array(
                'title'    => __( 'Use Headway/Blox alternate titles', 'pzarchitect' ),
                'subtitle' => __( 'Headway/Blox theme only', 'pzarchitect' ),
                'id'       => $prefix . 'alternate-titles',
                'type'     => 'switch',
                'on'       => __( 'Yes', 'pzarchitect' ),
                'off'      => __( 'No', 'pzarchitect' ),
                'default'  => TRUE,
                'hint'     => array( 'content' => __( 'If enabled, this will display the Headway/Blox alternative title if set. Note: If you change from Headway/Blox to another theme, this may still be displayed.', 'pzarchitect' ) ),

                /// can't set defaults on checkboxes!
            ),
            array(
                'title'    => __( 'Wrapper tag', 'pzarchitect' ),
                'id'       => $prefix . 'title-wrapper-tag',
                'type'     => 'select',
                //						'default'  => 'h1',  // Don't ever change this. It might break existing sites
                'default'  => 'h2',  // So I changed it! v1.10.0
                'options'  => array(
                    'h1'   => 'h1',
                    'h2'   => 'h2',
                    'h3'   => 'h3',
                    'h4'   => 'h4',
                    'h5'   => 'h5',
                    'h6'   => 'h6',
                    'p'    => 'p',
                    'span' => 'span',
                ),
                'subtitle' => __( 'Select the wrapper element for the title field', 'pzarchitect' ),

            ),
        ),
    );
    $sections[_amb_titles_responsive] = array(
        'title'      => __( 'Responsive overrides', 'pzarchitect' ),
        'show_title' => FALSE,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-screen',
        'fields'     => array(

            array(
                'title'    => __( 'Use responsive font sizes', 'pzarchitect' ),
                'id'       => $prefix . 'use-responsive-font-size-title',
                'type'     => 'switch',
                'default'  => FALSE,
                //'required' => array('show_advanced', 'equals', true),
                'subtitle' => __( 'Enabling this will override all other CSS title sizing', 'pzarchitect' ),
            ),
            array(
                'title'    => __( 'Fluid fonts', 'pzarchitect' ),
                'id'       => $prefix . 'use-scale-fonts-title',
                'type'     => 'switch',
                'default'  => TRUE,
                'required' => array(
                    $prefix . 'use-responsive-font-size-title',
                    'equals',
                    TRUE,
                ),
                'subtitle' => __( 'This makes the fonts scale in size from one breakpoint to the next, rather than suddenly changing at each breakpoint.', 'pzarchitect' ),
            ),
            array(
                'id'              => $prefix . 'title-font-size-bp1',
                'title'           => __( 'Maximum font size - large screen ', 'pzarchitect' ),
                'subtitle'        => $_architect_options['architect_breakpoint_1']['width'] . __( ' and above', 'pzarchitect' ),
                'required'        => array(
                    $prefix . 'use-responsive-font-size-title',
                    'equals',
                    TRUE,
                ),
                'type'            => 'typography',
                'default'         => array(
                    'font-size'   => '32px',
                    'line-height' => '40',
                ),
                'text-decoration' => FALSE,
                'font-variant'    => FALSE,
                'text-transform'  => FALSE,
                'font-family'     => FALSE,
                'font-size'       => TRUE,
                'font-weight'     => FALSE,
                'font-style'      => FALSE,
                'font-backup'     => FALSE,
                'google'          => FALSE,
                'subsets'         => FALSE,
                'custom_fonts'    => FALSE,
                'text-align'      => FALSE,
                //'text-shadow'       => false, // false
                'color'           => FALSE,
                'preview'         => FALSE,
                'line-height'     => TRUE,
                'word-spacing'    => FALSE,
                'letter-spacing'  => FALSE,
            ),
            array(
                'id'              => $prefix . 'title-font-size-bp2',
                'title'           => __( 'Font size - medium screen ', 'pzarchitect' ),
                'subtitle'        => $_architect_options['architect_breakpoint_2']['width'] . ' to ' . $_architect_options['architect_breakpoint_1']['width'],
                'required'        => array(
                    array(
                        $prefix . 'use-responsive-font-size-title',
                        'equals',
                        TRUE,
                    ),
                    array(
                        $prefix . 'use-scale-fonts-title',
                        'equals',
                        FALSE,
                    ),
                ),
                'type'            => 'typography',
                'default'         => array(
                    'font-size'   => '24px',
                    'line-height' => '28',
                ),
                'text-decoration' => FALSE,
                'font-variant'    => FALSE,
                'text-transform'  => FALSE,
                'font-family'     => FALSE,
                'font-size'       => TRUE,
                'font-weight'     => FALSE,
                'font-style'      => FALSE,
                'font-backup'     => FALSE,
                'google'          => FALSE,
                'subsets'         => FALSE,
                'custom_fonts'    => FALSE,
                'text-align'      => FALSE,
                //'text-shadow'       => false, // false
                'color'           => FALSE,
                'preview'         => FALSE,
                'line-height'     => TRUE,
                'word-spacing'    => FALSE,
                'letter-spacing'  => FALSE,
            ),
            array(
                'id'              => $prefix . 'title-font-size-bp3',
                'title'           => __( 'Minimum font size - small screen ', 'pzarchitect' ),
                'subtitle'        => $_architect_options['architect_breakpoint_2']['width'] . ' and below',
                'required'        => array(
                    $prefix . 'use-responsive-font-size-title',
                    'equals',
                    TRUE,
                ),
                'type'            => 'typography',
                'default'         => array(
                    'font-size'   => '20px',
                    'line-height' => '24',
                ),
                'text-decoration' => FALSE,
                'font-variant'    => FALSE,
                'text-transform'  => FALSE,
                'font-family'     => FALSE,
                'font-size'       => TRUE,
                'font-weight'     => FALSE,
                'font-style'      => FALSE,
                'font-backup'     => FALSE,
                'google'          => FALSE,
                'subsets'         => FALSE,
                'custom_fonts'    => FALSE,
                'text-align'      => FALSE,
                //'text-shadow'       => false, // false
                'color'           => FALSE,
                'preview'         => FALSE,
                'line-height'     => TRUE,
                'word-spacing'    => FALSE,
                'letter-spacing'  => FALSE,
            ),
            array(
                'id'       => $prefix . 'title-font-scale-upper-bp',
                'title'    => __( 'Override large screen breakpoint', 'pzarchitect' ),
                'type'     => 'text',
                'default'  => 1280,
                'subtitle' => __( 'Above this window width, no scaling will be done.' ),
                'required' => array(
                    array(
                        $prefix . 'use-responsive-font-size-title',
                        'equals',
                        TRUE,
                    ),
                    array(
                        $prefix . 'use-scale-fonts-title',
                        'equals',
                        TRUE,
                    ),
                ),
            ),
            array(
                'id'       => $prefix . 'title-font-scale-lower-bp',
                'title'    => __( 'Override small screen breakpoint', 'pzarchitect' ),
                'type'     => 'text',
                'default'  => 360,
                'subtitle' => __( 'Below this window width, no scaling will be done.' ),
                'required' => array(
                    array(
                        $prefix . 'use-responsive-font-size-title',
                        'equals',
                        TRUE,
                    ),
                    array(
                        $prefix . 'use-scale-fonts-title',
                        'equals',
                        TRUE,
                    ),
                ),
            ),

        ),
    );
    /**
     * TITLES
     */
    // architect_config_entry-title-font-margin
    if ( ! empty( $_architect_options['architect_enable_styling'] ) ) {
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

      $sections[_amb_styling_titles] = array(
          'title'      => __( 'Titles styling', 'pzarchitect' ),
          'show_title' => FALSE,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'desc'       => 'Class: .entry-title<br><strong class="arc-important">' . __( 'Note: If your Titles are linked, you will need to set their colour in the Links section', 'pzarchitect' ) . '</strong>',
          'fields'     => pzarc_fields( pzarc_redux_font( $prefix . 'entry-title' . $font, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font ] ), pzarc_redux_bg( $prefix . 'entry-title' . $font . $background, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font . $background ] ), pzarc_redux_padding( $prefix . 'entry-title' . $font . $padding, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font . $padding ] ), pzarc_redux_margin( $prefix . 'entry-title' . $font . $margin, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $font . $margin ], 'tb' ), pzarc_redux_borders( $prefix . 'entry-title' . $border, array( '.entry-title' ), $defaults[ $optprefix . 'entry-title' . $border ] ), pzarc_redux_links( $prefix . 'entry-title' . $font . $link, array( '.entry-title a' ), $defaults[ $optprefix . 'entry-title' . $font . $link ] ) ),
      );
    }
    $sections[_amb_titles_help] = array(
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(
            array(
                'title'    => __( 'Online documentation', 'pzarchitect' ),
                'id'       => $prefix . 'help-content-online-docs',
                'type'     => 'raw',
                'markdown' => FALSE,
                'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>' . __( 'Architect Online Documentation', 'pzarchitect' ) . '</a><br>' . __( 'This is a growing resource. Please check back regularly.', 'pzarchitect' ),

            ),
        )
    );

    $metaboxes[] = array(
        'id'         => 'titles-settings',
        'title'      => __( 'Titles settings and styling.', 'pzarchitect' ),
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'default',
        'sidebar'    => FALSE,

    );

    return $metaboxes;

  }
  }

  new arc_mbTitles();