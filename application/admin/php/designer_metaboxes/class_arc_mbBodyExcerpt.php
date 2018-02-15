<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 13/2/18
   * Time: 8:58 PM
   */

  define('_amb_body_excerpt',2200);
  define('_amb_body_excerpt_help',2299);
  define('_amb_styling_body',2250);
  define('_amb_styling_excerpt',2350);
  define('_amb_body_excerpt_responsive',2400);

class arc_mbBodyExcerpt extends arc_Blueprints_Designer {
  function __construct( $defaults = FALSE ) {
    parent::__construct( $defaults );
    add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'mb_body_excerpt', ), 10, 1 );
  }

  function mb_body_excerpt( $metaboxes, $defaults_only = FALSE ) {
    pzdb( __FUNCTION__ );
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
    $sections[_amb_body_excerpt] = array(
        'title'      => __( 'Body/excerpt settings', 'pzarchitect' ),
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-adjust-alt',
        'fields'     => array(
            array(
                'id'     => $prefix . 'excerpt-heading',
                'title'  => __( 'Body', 'pzarchitect' ),
                'type'   => 'section',
                'indent' => TRUE,
                'class'  => 'heading',
            ),
            array(
                'title'    => __( 'Maximize body', 'pzarchitect' ),
                'id'       => $prefix . 'maximize-content',
                'type'     => 'switch',
                'on'       => __( 'Yes', 'pzarchitect' ),
                'off'      => __( 'No', 'pzarchitect' ),
                'default'  => TRUE,
                //'required' => array('show_advanced', 'equals', true),
                'subtitle' => __( 'Make excerpt or body 100% width if no featured image.', 'pzarchitect' ),
            ),
            array(
                'title'    => __( 'Action on more click', 'pzarchitect' ),
                'id'       => $prefix . 'more-click-action',
                'type'     => 'button_set',
                'options'  => array(
                    'none'                => __( 'Default', 'pzarchitect' ),
                    'slidedown'           => __( 'Slide down', 'pzarchitect' ),
                    'slidedown-fullwidth' => __( 'Slide down full width', 'pzarchitect' ),
                ),
                'default'  => 'none',
                //'required' => array('show_advanced', 'equals', true),
                'subtitle' => __( 'Choose how body content will appear when clicking the more link', 'pzarchitect' ),
            ),
            array(
                'title'    => __( 'Additional message after Body content', 'pzarchitect' ),
                'subtitle' => __( 'Shortcodes and limited HTML only', 'pzarchitect' ),
                'id'       => $prefix . 'additional-message',
                'type'     => 'textarea',
                'default'  => __( '', 'pzarchitect' ),
            ),
            array(
                'title'    => __( 'Insert shortcode', 'pzarchitect' ),
                'subtitle' => __( 'Useful to insert advertising into content', 'pzarchitect' ),
                'id'       => $prefix . 'insert-content-shortcode',
                'type'     => 'text',
                'default'  => __( '', 'pzarchitect' ),
            ),
            array(
                'title'   => __( 'Paragraph number to insert shortcode after', 'pzarchitect' ),
                'id'      => $prefix . 'insert-after-paragraph',
                'type'    => 'spinner',
                'default' => '0',
                'min'     => '0',
                'max'     => '999',
            ),
            array(
                'title'   => __( 'Shortcodes in body', 'pzarchitect' ),
                'id'      => $prefix . 'process-body-shortcodes',
                'type'    => 'button_set',
                'options' => array(
                    'process' => __( 'Process', 'pzarchitect' ),
                    'remove'  => __( 'Remove', 'pzarchitect' ),
                ),
                'default' => 'process',
            ),


            array(
                'id'     => $prefix . 'excerpt-heading',
                'title'  => __( 'Excerpts', 'pzarchitect' ),
                'type'   => 'section',
                'indent' => TRUE,
                'class'  => 'heading',
            ),
            array(
                'title'    => __( 'Display entered excerpts only', 'pzarchitect' ),
                'id'       => $prefix . 'manual-excerpts',
                'type'     => 'switch',
                'on'       => __( 'Yes', 'pzarchitect' ),
                'off'      => __( 'No', 'pzarchitect' ),
                'default'  => FALSE,
                //'required' => array('show_advanced', 'equals', true),
                'subtitle' => __( 'Only display excerpts that are actually entered in the Excerpt field of the post editor.<br>Entered excerpts retain formatting.', 'pzarchitect' ),
            ),
            array(
                'id'       => $prefix . 'excerpts-trim-type',
                'title'    => __( 'Trim excerpts using', 'pzarchitect' ),
                'type'     => 'button_set',
                'default'  => 'words',
                'options'  => array(
                    'characters' => __( 'Character', 'pzarchitect' ),
                    'words'      => __( 'Words', 'pzarchitect' ),
                    'paragraphs' => __( 'Paragraphs', 'pzarchitect' ),
                    'moretag'    => __( 'More tag', 'pzarchitect' ),
                ),
                'required' => array(
                    $prefix . 'manual-excerpts',
                    '=',
                    FALSE,
                ),
                'subtitle' => __( 'Excerpts trimmed by characters and words do not retain formatting.', 'pzarchitect' ),
            ),
            array(
                'title'    => __( 'Shortcodes in excerpts', 'pzarchitect' ),
                'id'       => $prefix . 'process-excerpts-shortcodes',
                'type'     => 'button_set',
                'subtitle'=>__('Architect shortcodes are always removed to avoiding infinite loops','pzarchitect'),
                'options'  => array(
                    'process' => __( 'Process', 'pzarchitect' ),
                    'remove'  => __( 'Remove', 'pzarchitect' ),
                ),
                'default'  => 'process',
                'required' => array(
                    $prefix . 'manual-excerpts',
                    '=',
                    FALSE,
                ),
            ),
            array(
                'id'       => $prefix . 'excerpts-word-count',
                'title'    => __( 'Excerpt length', 'pzarchitect' ),
                'type'     => 'spinner',
                'default'  => 55,
                'min'      => 1,
                'max'      => 9999,
                'step'     => 1,
                'required' => array(
                    $prefix . 'manual-excerpts',
                    '=',
                    FALSE,
                ),
                'desc'     => __( 'If using More Tag, this is the maximum number of words to show', 'pzarchitect' ),
            ),
            array(
                'title'    => __( 'Truncation indicator', 'pzarchitect' ),
                'id'       => $prefix . 'readmore-truncation-indicator',
                'type'     => 'text',
                'class'    => 'textbox-small',
                'default'  => '[...]',
                'required' => array(
                    $prefix . 'manual-excerpts',
                    '=',
                    FALSE,
                ),
            ),

            array(
                'title'    => __( 'Read More', 'pzarchitect' ),
                'id'       => $prefix . 'readmore-text',
                'type'     => 'text',
                'class'    => 'textbox-medium',
                'default'  => __( 'Read more', 'pzarchitect' ),
                'required' => array(
                    $prefix . 'manual-excerpts',
                    '=',
                    FALSE,
                ),
            ),
            //              array(
            //                  'title'    => __('Additional image after content', 'pzarchitect'),
            //                  'id'       => $prefix . 'additional-message',
            //                  'type'     => 'text',
            //                  'default'  => __('', 'pzarchitect'),
            //              ),
            //              array(
            //                  'title'    => __('Additional image after content', 'pzarchitect'),
            //                  'id'       => $prefix . 'additional-message',
            //                  'type'     => 'text',
            //                  'default'  => __('', 'pzarchitect'),
            //              ),

        ),
    );
    $sections[_amb_body_excerpt_responsive] = array(
        'title'      => __( 'Responsive overrides', 'pzarchitect' ),
        'show_title' => FALSE,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-screen',
        'fields'     => array(
            array(
                'id'       => $prefix . 'responsive-hide-content',
                'title'    => __( 'Hide Body at breakpoint', 'pzarchitect' ),
                'type'     => 'select',
                //'required' => array('show_advanced', 'equals', true),
                'options'  => array(
                    'none' => __( 'None', 'pzarchitect' ),
                    '2'    => __( 'Small screen ', 'pzarchitect' ) . $_architect_options['architect_breakpoint_2']['width'],
                    '1'    => __( 'Medium screen ', 'pzarchitect' ) . $_architect_options['architect_breakpoint_1']['width'],
                ),
                'default'  => 'none',
                'subtitle' => __( 'Breakpoints can be changed in Architect Options', 'pzachitect' ),
            ),
            array(
                'title'    => __( 'Use responsive font sizes', 'pzarchitect' ),
                'id'       => $prefix . 'use-responsive-font-size',
                'type'     => 'switch',
                'default'  => FALSE,
                //'required' => array('show_advanced', 'equals', true),
                'subtitle' => __( 'Enabling this will override all other CSS for body/excerpt text', 'pzarchitect' ),
            ),
            array(
                'title'    => __( 'Fluid fonts', 'pzarchitect' ),
                'id'       => $prefix . 'use-scale-fonts',
                'type'     => 'switch',
                'default'  => TRUE,
                'required' => array(
                    $prefix . 'use-responsive-font-size',
                    'equals',
                    TRUE,
                ),
                'subtitle' => __( 'This makes the fonts scale in size from one breakpoint to the next, rather than suddenly changing at each breakpoint.', 'pzarchitect' ),
            ),
            array(
                'id'              => $prefix . 'content-font-size-bp1',
                'title'           => __( 'Maximum font size - large screen ', 'pzarchitect' ),
                'subtitle'        => $_architect_options['architect_breakpoint_1']['width'] . __( ' and above', 'pzarchitect' ),
                'required'        => array(
                    $prefix . 'use-responsive-font-size',
                    'equals',
                    TRUE,
                ),
                'type'            => 'typography',
                'default'         => array(
                    'font-size'   => '16px',
                    'line-height' => '20',
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
                'id'              => $prefix . 'content-font-size-bp2',
                'title'           => __( 'Font size - medium screen ', 'pzarchitect' ),
                'subtitle'        => $_architect_options['architect_breakpoint_2']['width'] . ' to ' . $_architect_options['architect_breakpoint_1']['width'],
                'required'        => array(
                    array(
                        $prefix . 'use-responsive-font-size',
                        'equals',
                        TRUE,
                    ),
                    array(
                        $prefix . 'use-scale-fonts',
                        'equals',
                        FALSE,
                    ),
                ),
                'type'            => 'typography',
                'default'         => array(
                    'font-size'   => '15px',
                    'line-height' => '18',
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
                'id'              => $prefix . 'content-font-size-bp3',
                'title'           => __( 'Minimum font size - small screen ', 'pzarchitect' ),
                'subtitle'        => $_architect_options['architect_breakpoint_2']['width'] . ' and below',
                'required'        => array(
                    $prefix . 'use-responsive-font-size',
                    'equals',
                    TRUE,
                ),
                'type'            => 'typography',
                'default'         => array(
                    'font-size'   => '14px',
                    'line-height' => '16',
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
                'id'       => $prefix . 'content-font-scale-upper-bp',
                'title'    => __( 'Override large screen breakpoint', 'pzarchitect' ),
                'type'     => 'text',
                'default'  => 1280,
                'subtitle' => __( 'Above this window width, no scaling will be done.' ),
                'required' => array(
                    array(
                        $prefix . 'use-responsive-font-size',
                        'equals',
                        TRUE,
                    ),
                    array(
                        $prefix . 'use-scale-fonts',
                        'equals',
                        TRUE,
                    ),
                ),
            ),
            array(
                'id'       => $prefix . 'content-font-scale-lower-bp',
                'title'    => __( 'Override small screen breakpoint', 'pzarchitect' ),
                'type'     => 'text',
                'default'  => 360,
                'subtitle' => __( 'Below this window width, no scaling will be done.' ),
                'required' => array(
                    array(
                        $prefix . 'use-responsive-font-size',
                        'equals',
                        TRUE,
                    ),
                    array(
                        $prefix . 'use-scale-fonts',
                        'equals',
                        TRUE,
                    ),
                ),
            )
            //            array(
            //                'id'      => $prefix . 'content-font-size-range',
            //                'title'   => __('Font size range', 'pzarchitect'),
            //                'type'    => 'slider',
            //                'min'     => 5,
            //                'max'     => 24,
            //                'step'    => 1,
            //                'handles' => 2,
            //                'default' => array(1=>10,2=>16)
            //            )
        ),


    );

    // Stylings
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


      $sections[_amb_styling_body] = array(
          'title'      => __( 'Body content styling', 'pzarchitect' ),
          'show_title' => FALSE,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'desc'       => 'Class: .entry-content<br><strong class="arc-important">' . __( 'Note: If your theme styles .entry-content and .entry-content p separately, you may need to style both there too', 'pzarchitect' ) . '</strong>',
          'fields'     => pzarc_fields( array(
              'title'  => __( 'Full content', 'pzarc' ),
              'id'     => $prefix . 'entry-content',
              'type'   => 'section',
              'indent' => TRUE,
              'class'  => 'heading',
          ), pzarc_redux_font( $prefix . 'entry-content' . $font, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $font ] ), pzarc_redux_bg( $prefix . 'entry-content' . $font . $background, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $font . $background ] ), pzarc_redux_padding( $prefix . 'entry-content' . $font . $padding, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $font . $padding ] ), pzarc_redux_margin( $prefix . 'entry-content' . $font . $margin, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $font . $margin ], 'tb' ), pzarc_redux_borders( $prefix . 'entry-content' . $border, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $border ] ), pzarc_redux_links( $prefix . 'entry-content' . $font . $link, array( '.entry-content a' ), $defaults[ $optprefix . 'entry-content' . $font . $link ] ), array(
              'title'  => __( 'Content paragraphs', 'pzarc' ),
              'id'     => $prefix . 'entry-content-p',
              'desc'   => 'Class: .entry-content p',
              'type'   => 'section',
              'indent' => TRUE,
              'class'  => 'heading',
          ), pzarc_redux_font( $prefix . 'entry-contentp' . $font, array( '.entry-content' ), $defaults[ $optprefix . 'entry-content' . $font ] ), pzarc_redux_padding( $prefix . 'entry-contentp' . $font . $padding, array( '.entry-content p' ), $defaults[ $optprefix . 'entry-contentp' . $font . $padding ] ), pzarc_redux_margin( $prefix . 'entry-contentp' . $font . $margin, array( '.entry-content p' ), $defaults[ $optprefix . 'entry-contentp' . $font . $margin ] ) ),
      );
      $sections[_amb_styling_excerpt] = array(
          'title'      => __( 'Excerpts styling', 'pzarchitect' ),
          'show_title' => FALSE,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'desc'       => 'Class: .entry-excerpt<br><strong class="arc-important">' . __( 'Note: If your theme styles .entry-excerpt and .entry-excerpt p separately, you may need to style both there too', 'pzarchitect' ) . '</strong>',
          'fields'     => pzarc_fields( array(
              'title'  => __( 'Excerpt', 'pzarchitect' ),
              'id'     => $prefix . 'entry-excerpt',
              'type'   => 'section',
              'indent' => TRUE,
              'class'  => 'heading',
          ), pzarc_redux_font( $prefix . 'entry-excerpt' . $font, array( '.entry-excerpt' ), $defaults[ $optprefix . 'entry-excerpt' . $font ] ), pzarc_redux_bg( $prefix . 'entry-excerpt' . $font . $background, array( '.entry-excerpt' ), $defaults[ $optprefix . 'entry-excerpt' . $font . $background ] ), pzarc_redux_padding( $prefix . 'entry-excerpt' . $font . $padding, array( '.entry-excerpt' ), $defaults[ $optprefix . 'entry-excerpt' . $font . $padding ] ), pzarc_redux_margin( $prefix . 'entry-excerpt' . $font . $margin, array( '.entry-excerpt' ), $defaults[ $optprefix . 'entry-excerpt' . $font . $margin ], 'tb' ), pzarc_redux_borders( $prefix . 'entry-excerpt' . $border, array( '.entry-excerpt' ), $defaults[ $optprefix . 'entry-excerpt' . $border ] ), pzarc_redux_links( $prefix . 'entry-excerpt' . $font . $link, array( '.entry-excerpt a' ), $defaults[ $optprefix . 'entry-excerpt' . $font . $link ] ), array(
              'title'  => __( 'Excerpt paragraphs', 'pzarc' ),
              'id'     => $prefix . 'entry-excerptp',
              'desc'   => 'Class: .entry-excerpt p',
              'type'   => 'section',
              'indent' => TRUE,
              'class'  => 'heading',
          ), pzarc_redux_font( $prefix . 'entry-excerptp' . $font, array( '.entry-excerpt p' ), $defaults[ $optprefix . 'entry-excerptp' . $font ] ), pzarc_redux_padding( $prefix . 'entry-excerptp' . $font . $padding, array( '.entry-excerpt p' ), $defaults[ $optprefix . 'entry-excerptp' . $font . $padding ] ), pzarc_redux_margin( $prefix . 'entry-excerptp' . $font . $margin, array( '.entry-excerpt p' ), $defaults[ $optprefix . 'entry-excerptp' . $font . $margin ] ), array(
              'title'  => __( 'Read more', 'pzarchitect' ),
              'id'     => $prefix . 'entry-readmore',
              'type'   => 'section',
              'indent' => TRUE,
              'class'  => 'heading',
              'hint'   => array( 'content' => 'Class: a.pzarc_readmore' ),
          ), pzarc_redux_font( $prefix . 'entry-readmore' . $font, array( '.readmore' ), $defaults[ $optprefix . 'entry-readmore' . $font ] ), pzarc_redux_bg( $prefix . 'entry-readmore' . $font . $background, array( '.readmore' ), $defaults[ $optprefix . 'entry-readmore' . $font . $background ] ), pzarc_redux_padding( $prefix . 'entry-readmore' . $font . $padding, array( '.readmore' ), $defaults[ $optprefix . 'entry-readmore' . $font . $padding ] ), pzarc_redux_links( $prefix . 'entry-readmore' . $font . $link, array( 'a.readmore' ), $defaults[ $optprefix . 'entry-readmore' . $font . $link ] ) ),
      );
    }
    $sections[_amb_body_excerpt_help] = array(
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
        'id'         => 'body-settings',
        'title'      => __( 'Body/Excerpt settings and stylings.', 'pzarchitect' ),
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'default',
        'sidebar'    => FALSE,

    );

    return $metaboxes;

  }
  }
  new arc_mbBodyExcerpt();