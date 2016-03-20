<?php
  /**
   * Project pizazzwp-architect.
   * File: class_componentMeta.php
   * User: chrishoward
   * Date: 18/03/2016
   * Time: 10:20 PM
   */

  add_action( "redux/metaboxes/_architect/boxes",  'pzarc_mb_meta_settings', 10, 1 );

  function pzarc_mb_meta_settings( $metaboxes, $defaults_only = false ) {
    global $_architect;
    global $_architect_options;
    if ( empty( $_architect_options ) ) {
      $_architect_options = get_option( '_architect_options' );
    }

    if ( empty( $_architect ) ) {
      $_architect = get_option( '_architect' );
    }

    $sections = array();
    $prefix   = '_panels_design_';
    // Settings
    $sections[] = array(
      'title'      => __( 'Meta settings', 'pzarchitect' ),
      'show_title' => false,
      'icon_class' => 'icon-large',
      'icon'       => 'el-icon-adjust-alt',
      'desc'       => __( 'Available tags are <span class="pzarc-text-highlight">%author%, %email%,   %date%,   %categories%,   %tags%,   %commentslink%,   %editlink%,   %id%</span>. For custom taxonomies, prefix with ct:. e.g. To display the Woo Testimonials category, you would use %ct:testimonial-category%. Or to display WooCommerce product category, use: %ct:product_cat%', 'pzarchitect' ) . '<br>' .
                      __( 'Allowed HTML tags:', 'pzarchitect' ) . ' p, br, span, strong & em<br><br>' .
                      __( 'Use shortcodes to add custom functions to meta. e.g. [add_to_cart id="%id%"]', 'pzarchitect' ) . '<br>' .
                      __( 'Note: Enclose any author related text in <span class="pzarc-text-highlight">//</span> to hide it when using excluded authors.', 'pzarchitect' ) . '<br>' .
                      __( 'Note: The email address will be encoded to prevent automated harvesting by spammers.', 'pzarchitect' ),
      'fields'     => array(
        // ======================================
        // META
        // ======================================
        array(
          'title'   => __( 'Meta1 config', 'pzarchitect' ),
          'id'      => $prefix . 'meta1-config',
          'type'    => 'textarea',
          'cols'    => 4,
          'rows'    => 2,
          'default' => '%date% //by// %author%',
        ),
        array(
          'title'   => __( 'Meta2 config', 'pzarchitect' ),
          'id'      => $prefix . 'meta2-config',
          'type'    => 'textarea',
          'rows'    => 2,
          'default' => __( 'Categories', 'pzarchitect' ) . ': %categories%   ' . __( 'Tags', 'pzarchitect' ) . ': %tags%',
        ),
        array(
          'title'   => __( 'Meta3 config', 'pzarchitect' ),
          'id'      => $prefix . 'meta3-config',
          'type'    => 'textarea',
          'rows'    => 2,
          'default' => '%commentslink%   %editlink%',
        ),
        array(
          'id'      => $prefix . 'meta-date-format',
          'title'   => __( 'Date format', 'pzarchitect' ),
          'type'    => 'text',
          'default' => 'l, F j, Y g:i a',
          'desc'    => __( 'See here for information on <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>formatting date and time</a>', 'pzarchitect' ),
        ),
        array(
          'title'    => __( 'Excluded authors', 'pzarchitect' ),
          'id'       => $prefix . 'excluded-authors',
          'type'     => 'select',
          'multi'    => true,
          'default'  => array(),
          'data'     => 'callback',
          //'required' => array('show_advanced', 'equals', true),
          //TODO: Findout how to pass parameters. currently that is doing nothing!
          'args'     => array( 'pzarc_get_authors', array( false, 0 ) ),
          'subtitle' => __( 'Choose any authors here you want to exclude from showing when the %author% or %email% tag is used.', 'pzarchitect' )
        ),
        array(
          'title'   => __( 'Author avatar', 'pzarchitect' ),
          'id'      => $prefix . 'avatar',
          'type'    => 'button_set',
          'default' => 'none',
          'options' => array(
            'none'   => __( 'None', 'pzarchitect' ),
            'before' => __( 'Before', 'pzarchitect' ),
            'after'  => __( 'After', 'pzarchitect' )
          ),
        ),
        array(
          'title'    => __( 'Avatar size', 'pzarchitect' ),
          'id'       => $prefix . 'avatar-size',
          'type'     => 'spinner',
          'default'  => 32,
          'min'      => 1,
          'max'      => 256,
          'step'     => 1,
          'subtitle' => __( 'Width and height of avatar if displayed.', 'pzarchitect' )
        ),
      )
    );

    // Stylings
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
        'title'      => __( 'Meta styling', 'pzarchitect' ),
        'show_title' => false,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-brush',
        'desc'       => 'Class: .entry_meta',
        'fields'     => pzarc_fields(
          pzarc_redux_font( $prefix . 'entry-meta' . $font, array( '.entry-meta' ), $defaults[ $optprefix . 'entry-meta' . $font ] ),
          pzarc_redux_bg( $prefix . 'entry-meta' . $font . $background, array( '.entry-meta' ), $defaults[ $optprefix . 'entry-meta' . $font . $background ] ),
          pzarc_redux_padding( $prefix . 'entry-meta' . $font . $padding, array( '.entry-meta' ), $defaults[ $optprefix . 'entry-meta' . $font . $padding ] ),
          pzarc_redux_margin( $prefix . 'entry-meta' . $font . $margin, array( '.entry-meta' ), $defaults[ $optprefix . 'entry-meta' . $font . $margin ], 'tb' ),
          pzarc_redux_links( $prefix . 'entry-meta' . $font . $link, array( '.entry-meta a' ), $defaults[ $optprefix . 'entry-meta' . $font . $link ] ),
          array(
            'title'  => __( 'Author avatar', 'pzarc' ),
            'id'     => $prefix . 'author-avatar',
            'desc'   => 'Class: .author img.avatar',
            'type'   => 'section',
            'indent' => true,
            'class'  => 'heading',
          ),
          pzarc_redux_margin( $prefix . 'author-avatar' . $margin,
                              array( '.author img.avatar' ),
                              $defaults[ $optprefix . 'author-avatar' . $margin ] )
        )
      );
    }
    $metaboxes[] = array(
      'id'         => 'meta-settings',
      'title'      => __( 'Meta settings and styling.', 'pzarchitect' ),
      'post_types' => array( 'arc-blueprints' ),
      'sections'   => $sections,
      'position'   => 'normal',
      'priority'   => 'default',
      'sidebar'    => false

    );

    return $metaboxes;

  }
