<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 13/2/18
   * Time: 9:03 PM
   */

  define('_amb_features',2600);
  define('_amb_features_help',2699);
  define('_amb_styling_features',2650);

class arc_mbFeatures extends arc_Blueprints_Designer {
  function __construct( $defaults = FALSE ) {
    parent::__construct( $defaults );
    add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'mb_features', ), 10, 1 );
  }

  function mb_features( $metaboxes, $defaults_only = FALSE ) {
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
    $sections[_amb_features] = array(
        'title'      => 'Feature settings',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-adjust-alt',
        'subtitle'   => __( 'Left and right margins are included in the image width in the designer. e.g if Feature width is 25% and right margin is 3%, Feature width will be adjusted to 22%', 'pzarchitect' ),
        'fields'     => array(
            array(
                'title'    => __( 'Feature type', 'pzarchitect' ),
                'id'       => '_panels_settings_feature-type',
                'type'     => 'button_set',
                'default'  => 'image',
                'options'  => array(
                    'image' => __( 'Images', 'pzarchitect' ),
                    'video' => __( 'Videos', 'pzarchitect' ),
                ),
                'subtitle' => __( 'Choose whether Feature is images or videos.', 'pzarchitect' ),
                'required' => array(
                    $prefix . 'components-to-show',
                    'contains',
                    'image',
                ),
            ),
            array(
                'title'    => __( 'Image cropping', 'pzarchitect' ),
                'id'       => '_panels_settings_image-focal-point',
                'type'     => 'select',
                'default'  => 'respect',
                'select2'  => array( 'allowClear' => FALSE ),
                'required' => array(
                    '_panels_settings_feature-type',
                    '=',
                    'image',
                ),
                'options'  => array(
                    'respect'      => __( 'Use focal point', 'pzarchitect' ),
                    //                      'centre'       => __('Centre focal point', 'pzarchitect'),
                    //                      'topleft'      => __('Crop to top left', 'pzarchitect'),
                    'topcentre'    => __( 'Crop to top centre', 'pzarchitect' ),
                    //                      'topright'     => __('Crop to top right', 'pzarchitect'),
                    //                      'midleft'      => __('Crop to middle left', 'pzarchitect'),
                    'midcentre'    => __( 'Crop to middle centre', 'pzarchitect' ),
                    //                      'midright'     => __('Crop to middle right', 'pzarchitect'),
                    //                      'bottomleft'   => __('Crop to bottom left', 'pzarchitect'),
                    'bottomcentre' => __( 'Crop to bottom centre', 'pzarchitect' ),
                    //                      'bottomright'  => __('Crop to bottom right', 'pzarchitect'),
                    'scale'        => __( 'Preserve aspect, fit to width. No cropping', 'pzarchitect' ),
                    'scale_height' => __( 'Preserve aspect, fit to height. No cropping', 'pzarchitect' ),
                    'shrink'       => __( 'Scale. Fit to width and height. No cropping', 'pzarchitect' ),
                ),
            ),
            array(
                'id'            => $prefix . 'image-quality',
                'title'         => __( 'Image quality', 'pzarchitect' ),
                'type'          => 'slider',
                'display_value' => 'label',
                'default'       => 82,
                'min'           => 1,
                'max'           => 100,
                'step'          => 1,
                'units'         => '%',
                'hint'          => array( 'content' => 'Quality to use when processing images' ),
                'required'      => array( '_panels_settings_feature-type', '=', 'image' ),

            ),
            array(
                'title'    => __( 'Disable image saving', 'pzarchitect' ),
                'id'       => '_panels_settings_disable-image-saving',
                'type'     => 'switch',
                'on'       => __( 'Yes', 'pzarchitect' ),
                'off'      => __( 'No', 'pzarchitect' ),
                'default'  => FALSE,
                'subtitle' => __( 'Disable right clicking to save images', 'pzarchitect' ),
                'required' => array(//array('show_advanced', 'equals', true),
                                    array(
                                        '_panels_settings_feature-type',
                                        '=',
                                        'image',
                                    ),
                ),
            ),
            array(
                'title'    => __( 'Use embedded images', 'pzarchitect' ),
                'id'       => '_panels_settings_use-embedded-images',
                'type'     => 'switch',
                'on'       => __( 'Yes', 'pzarchitect' ),
                'off'      => __( 'No', 'pzarchitect' ),
                'default'  => FALSE,
                'required' => array(//array('show_advanced', 'equals', true),
                                    array(
                                        '_panels_settings_feature-type',
                                        '=',
                                        'image',
                                    ),
                ),
                'subtitle' => __( 'Enable this to use the first found attached image in the body of the post if no featured image is set.', 'pzarchitect' ),
            ),
            //          array(
            //            'title'    => __( 'Use retina images', 'pzarchitect' ),
            //            'id'       => '_panels_settings_use-retina-images',
            //            'type'     => 'switch',
            //            'on'       => __( 'Yes', 'pzarchitect' ),
            //            'off'      => __( 'No', 'pzarchitect' ),
            //            'default'  => true,
            //            'required' => array(
            //              //array('show_advanced', 'equals', true),
            //              array( '_panels_settings_feature-type', '=', 'image' ),
            //            ),
            //            'hint'     => array(
            //              'title'   => __( 'Use retina images', 'pzarchitect' ),
            //              'content' => __( 'If enabled, a retina version of the featured image will be created and displayed. <strong>Ensure the global setting in Architect Options is on as well</strong>. NOTE: This will make your site load slower on retina devices, so you may only want consider which panels you have it enabled on.', 'pzarchitect' )
            //            )
            //          ),
            array(
                'title'    => __( 'Filler image', 'pzarchitect' ),
                'id'       => $prefix . 'use-filler-image-source',
                'type'     => 'select',
                'select2'  => array( 'allowClear' => FALSE ),
                'default'  => 'none',
                'subtitle' => __( 'Use a filler image from lorempixel if post has no image.', 'pzarchitect' ),
                'options'  => array(
                    'none'       => 'None',
                    'specific'   => 'Custom specific image',
                    'lorempixel' => __( 'Random Picture', 'pzarchitect' ),
                    'abstract'   => ucfirst( 'abstract' ),
                    'animals'    => ucfirst( 'animals' ),
                    'business'   => ucfirst( 'business' ),
                    'cats'       => ucfirst( 'cats' ),
                    'city'       => ucfirst( 'city' ),
                    'food'       => ucfirst( 'food' ),
                    'nightlife'  => ucfirst( 'nightlife' ),
                    'fashion'    => ucfirst( 'fashion' ),
                    'people'     => ucfirst( 'people' ),
                    'nature'     => ucfirst( 'nature' ),
                    'sports'     => ucfirst( 'sports' ),
                    'technics'   => ucfirst( 'transport' ),
                ),
                'required' => array(
                    array(
                        '_panels_settings_feature-type',
                        '=',
                        'image',
                    ),
                ),
            ),
            array(
                'id'             => $prefix . 'use-filler-image-source-specific',
                'type'           => 'media',
                'title'          => __( 'Specific filler image', 'pzarchitect' ),
                'subtitle'       => __( 'This single image will display for all posts with no featured image.', 'pzarchitect' ),
                'required'       => array(
                    $prefix . 'use-filler-image-source',
                    '=',
                    'specific',
                ),
                'library_filter' => array(
                    'jpg',
                    'jpeg',
                    'png',
                ),
            ),
            array(
                'id'       => $prefix . 'image-max-dimensions',
                'title'    => __( 'Limit image dimensions', 'pzarchitect' ),
                'type'     => 'dimensions',
                'desc'     => __( 'The displayed width of the image is determined by it\'s size in the Content Layout designer. This setting is used limit the size of the image created and used.', 'pzarchitect' ),
                'units'    => 'px',
                'default'  => array(
                    'width'  => '400',
                    'height' => '300',
                ),
                'required' => array(
                    array(
                        '_panels_settings_feature-type',
                        '=',
                        'image',
                    ),
                ),
            ),
            //          array(
            //            'title'    => __( 'Fill screen', 'pzarchitect' ),
            //            'id'       => $prefix . 'fill-screen',
            //            'type'     => 'switch',
            //            'on'       => __( 'Yes', 'pzarchitect' ),
            //            'off'      => __( 'No', 'pzarchitect' ),
            //            'default'  => false,
            //            'required' => array(
            //              //array('show_advanced', 'equals', true),
            //              array( '_panels_settings_feature-type', '=', 'image' ),
            //            ),
            //            'subtitle' => __( 'When enabled, featured images will fill the whole screen.', 'pzarchitect' )
            //          ),
            array(
                'title'    => __( 'Background images effect on screen resize', 'pzarchitect' ),
                'id'       => $prefix . 'background-image-resize',
                'type'     => 'button_set',
                'subtitle' => __( 'Scale Vertically & Horizontally ', 'pzarchitect' ) . '<br>' . __( 'Trim horizontally, fill height', 'pzarchitect' ) . '<br>' . __( 'None uses default image sizing settings', 'pzarchitect' ),
                'options'  => array(
                    'scale'            => __( 'Scale', 'pzarchitect' ),
                    'trim'             => __( 'Trim', 'pzarchitect' ),
                    'no-resize-effect' => __( 'None', 'pzarchitect' ),
                ),
                'required' => array(//array('show_advanced', 'equals', true),
                                    array(
                                        '_panels_settings_feature-type',
                                        '=',
                                        'image',
                                    ),
                                    array(
                                        '_panels_design_feature-location',
                                        '=',
                                        'fill',
                                    ),
                ),
                'default'  => 'scale',
            ),
            array(
                'id'             => $prefix . 'image-spacing',
                'type'           => 'spacing',
                'mode'           => 'margin',
                'units'          => '%',
                'units_extended' => 'false',
                'title'          => __( 'Margins (%)', 'pzarchitect' ),
                'default'        => array(
                    'margin-top'    => '0',
                    'margin-right'  => '0',
                    'margin-bottom' => '0',
                    'margin-left'   => '0',
                    'units'         => '%',
                ),
            ),
            array(
                'title'    => __( 'Link to', 'pzarchitect' ),
                'id'       => $prefix . 'link-image',
                'type'     => 'button_set',
                'options'  => array(
                    'none'            => __( 'None', 'pzarchitect' ),
                    'page'            => __( 'Post', 'pzarchitect' ),
                    'image'           => __( 'Attachment page', 'pzarchitect' ),
                    'original'        => __( 'Lightbox', 'pzarchitect' ),
                    'url'             => __( 'Specific URL', 'pzarchitect' ),
                    'destination-url' => __( 'Gallery Link URL', 'pzarchitect' ),
                ),
                'default'  => 'page',
                'required' => array(
                    '_panels_settings_feature-type',
                    '=',
                    'image',
                ),
                'subtitle' => __( 'Set what happens when a viewer clicks on the image', 'pzazrchitect' ),
                'desc'     => __( 'Gallery Link URL requires the WP Gallery Custom Links plugin', 'pzarchitect' ),
            ),
            array(
                'title'    => __( 'Use alternate lightbox', 'pzarchitect' ),
                'id'       => $prefix . 'alternate-lightbox',
                'type'     => 'switch',
                'on'       => __( 'Yes', 'pzarchitect' ),
                'off'      => __( 'No', 'pzarchitect' ),
                'default'  => FALSE,
                'required' => array(
                    $prefix . 'link-image',
                    '=',
                    'original',
                ),
                'subtitle' => __( 'This adds rel="lightbox" to image links. Your lightbox plugin needs to support that (most do).', 'pzarchitect' ),
            ),
            array(
                'title'    => __( 'Specific URL', 'pzarchitect' ),
                'id'       => $prefix . 'link-image-url',
                'type'     => 'text',
                'required' => array(
                    array(
                        $prefix . 'link-image',
                        'equals',
                        'url',
                    ),
                    array(
                        '_panels_settings_feature-type',
                        '=',
                        'image',
                    ),
                ),
                'validate' => 'url',
                'subtitle' => __( 'Enter the URL that all images will link to', 'pzazrchitect' ),
            ),
            array(
                'title'    => __( 'Specific URL tooltip', 'pzarchitect' ),
                'id'       => $prefix . 'link-image-url-tooltip',
                'type'     => 'text',
                'required' => array(
                    array(
                        $prefix . 'link-image',
                        'equals',
                        'url',
                    ),
                    array(
                        '_panels_settings_feature-type',
                        '=',
                        'image',
                    ),
                ),
                'subtitle' => __( 'Enter the text that appears when the user hovers over the link', 'pzazrchitect' ),
            ),
            array(
                'title'    => __( 'Image Captions', 'pzarchitect' ),
                'id'       => $prefix . 'image-captions',
                'type'     => 'switch',
                'on'       => __( 'Yes', 'pzarchitect' ),
                'off'      => __( 'No', 'pzarchitect' ),
                'default'  => FALSE,
                'required' => array(
                    '_panels_settings_feature-type',
                    '=',
                    'image',
                ),
            ),
            array(
                'title'    => __( 'Use caption for image alt text', 'pzarchitect' ),
                'id'       => $prefix . 'caption-alt-text',
                'type'     => 'switch',
                'on'       => __( 'Yes', 'pzarchitect' ),
                'off'      => __( 'No', 'pzarchitect' ),
                'default'  => FALSE,
                'required' => array(//array('show_advanced', 'equals', true),
                                    array(
                                        '_panels_settings_feature-type',
                                        '=',
                                        'image',
                                    ),
                ),
            ),
            array(
                'title'    => __( 'Centre feature', 'pzarchitect' ),
                'id'       => $prefix . 'centre-image',
                'type'     => 'switch',
                'on'       => __( 'Yes', 'pzarchitect' ),
                'off'      => __( 'No', 'pzarchitect' ),
                'default'  => FALSE,
                'required' => array(//array('show_advanced', 'equals', true),
                                    array(
                                        '_panels_settings_feature-type',
                                        '=',
                                        'image',
                                    ),
                ),
                'subtitle' => __( 'Centres the image horizontally. It is best to display it on its own row, and the other components to be 100% wide.', 'pzarchitect' ),
            ),
            array(
                'title'    => __( 'Add copyright to images', 'pzarchitect' ),
                'id'       => '_panels_settings_image-copyright-add',
                'type'     => 'button_set',
                'options'  => array(
                    'featured' => 'Featured',
                    'lightbox' => 'Lightbox'
                ),
                'multi'    => TRUE,
                'required' => array(//array('show_advanced', 'equals', true),
                                    array(
                                        '_panels_settings_feature-type',
                                        '=',
                                        'image',
                                    ),
                ),
            ),
            array(
                'title'    => __( 'Copyright message', 'pzarchitect' ),
                'id'       => '_panels_settings_image-copyright-text',
                'type'     => 'text',
                'default'  => '&copy; Copyright ' . date( 'Y', time() ),
                'required' => array(//array('show_advanced', 'equals', true),
                                    array(
                                        '_panels_settings_feature-type',
                                        '=',
                                        'image',
                                    ),
                ),
            ),
            array(
                'title'       => __( 'Copyright text colour', 'pzarchitect' ),
                'id'          => '_panels_settings_image-copyright-text-colour',
                'type'        => 'color',
                'default'     => '#ffffff',
                'transparent' => FALSE,
                'required'    => array(//array('show_advanced', 'equals', true),
                                       array(
                                           '_panels_settings_feature-type',
                                           '=',
                                           'image',
                                       ),
                ),
            ),
            array(
                'title'    => __( 'Copyright text size', 'pzarchitect' ),
                'id'       => '_panels_settings_image-copyright-text-size',
                'type'     => 'text',
                'default'  => 20,
                'required' => array(//array('show_advanced', 'equals', true),
                                    array(
                                        '_panels_settings_feature-type',
                                        '=',
                                        'image',
                                    ),
                ),
            ),
            array(
                'title'    => __( 'Copyright text position', 'pzarchitect' ),
                'id'       => '_panels_settings_image-copyright-text-position',
                'type'     => 'button_set',
                'options'  => array(
                    'top'    => __( 'Top', 'pzarchitect' ),
                    'middle' => __( 'Middle', 'pzarchitect' ),
                    'bottom' => __( 'Bottom', 'pzarchitect' ),
                ),
                'default'  => 'middle',
                'required' => array(//array('show_advanced', 'equals', true),
                                    array(
                                        '_panels_settings_feature-type',
                                        '=',
                                        'image',
                                    ),
                ),
            ),
            //              array(
            //                  'title'    => __('Rotate feature', 'pzarchitect'),
            //                  'id'       => $prefix . 'rotate-image',
            //                  'type'     => 'switch',
            //                  'on'       => __('Yes', 'pzarchitect'),
            //                  'off'      => __('No', 'pzarchitect'),
            //                  'default'  => false,
            //                  'required' => array(
            //                    //array('show_advanced', 'equals', true),
            //                    array('_panels_settings_feature-type', '=', 'image'),
            //                  ),
            //                  'subtitle' => __('Randomly rotates images up to 5 degrees.', 'pzarchitect')
            //              ),
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

      $sections[_amb_styling_features] = array(
          'title'      => __( 'Feature styling', 'pzarchitect' ),
          'show_title' => FALSE,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'fields'     => pzarc_fields( array(
              'title'  => __( 'Image', 'pzarchitect' ),
              'id'     => $prefix . 'entry-image',
              'type'   => 'section',
              'indent' => TRUE,
              'class'  => 'heading',
              'hint'   => array( 'content' => 'Class: figure.entry-thumbnail' ),
              //     'hint'    => __('Format the entry featured image', 'pzarchitect')
          ), array(
              'title'                 => __( 'Background', 'pzarchitect' ),
              'id'                    => $prefix . 'entry-image' . $background,
              'type'                  => 'spectrum',
              'background-image'      => FALSE,
              'background-repeat'     => FALSE,
              'background-size'       => FALSE,
              'background-attachment' => FALSE,
              'background-position'   => FALSE,
              'preview'               => FALSE,
              'output'                => array( '.pzarc_entry_featured_image' ),
              'default'               => $defaults[ $optprefix . 'entry-image' . $background ],
          ), array(
              'title'   => __( 'Border', 'pzarchitect' ),
              'id'      => $prefix . 'entry-image' . $border,
              'type'    => 'border',
              'all'     => FALSE,
              'output'  => array( '.pzarc_entry_featured_image' ),
              'default' => $defaults[ $optprefix . 'entry-image' . $border ],
          ), array(
              'title'   => __( 'Padding', 'pzarchitect' ),
              'id'      => $prefix . 'entry-image' . $padding,
              'mode'    => 'padding',
              'type'    => 'spacing',
              'units'   => array(
                  'px',
                  '%',
              ),
              'default' => $defaults[ $optprefix . 'entry-image' . $padding ],
          ), array(
              'title'   => __( 'Margin', 'pzarchitect' ),
              'id'      => $prefix . 'entry-image' . $margin,
              'mode'    => 'margin',
              'type'    => 'spacing',
              'units'   => array(
                  'px',
                  '%',
              ),
              'default' => $defaults[ $optprefix . 'entry-image' . $margin ],
              'top'     => TRUE,
              'bottom'  => TRUE,
              'left'    => FALSE,
              'right'   => FALSE,
          ), array(
              'title'  => __( 'Caption', 'pzarchitect' ),
              'id'     => $prefix . 'entry-image-caption',
              'type'   => 'section',
              'indent' => TRUE,
              'class'  => 'heading',
          ), pzarc_redux_font( $prefix . 'entry-image-caption' . $font, array( 'figure.entry-thumbnail .caption' ), $defaults[ $optprefix . 'entry-image-caption' . $font ] ), pzarc_redux_bg( $prefix . 'entry-image-caption' . $font . $background, array( 'figure.entry-thumbnail .caption' ), $defaults[ $optprefix . 'entry-image-caption' . $font . $background ] ), pzarc_redux_padding( $prefix . 'entry-image-caption' . $font . $padding, array( 'figure.entry-thumbnail .caption' ), $defaults[ $optprefix . 'entry-image-caption' . $font . $padding ] ) ),
      );
    }
    $sections[_amb_features_help] = array(
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
        'id'         => 'features-settings',
        'title'      => __( 'Featured images/videos settings and stylings.', 'pzarchitect' ),
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'default',
        'sidebar'    => FALSE,

    );

    return $metaboxes;

  }
}
new arc_mbFeatures();