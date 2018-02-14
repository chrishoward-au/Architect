<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 13/2/18
   * Time: 8:59 PM
   */

class arc_mbPanels extends arc_Blueprints_Designer {
  function __construct( $defaults = FALSE ) {
    parent::__construct( $defaults );
    add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'mb_panels', ), 10, 1 );
  }

  function mb_panels( $metaboxes, $defaults_only = FALSE ) {
    pzdb( __FUNCTION__ );
    global $_architect;
    global $_architect_options;
    if ( empty( $_architect_options ) ) {
      $_architect_options = get_option( '_architect_options' );
    }

    if ( empty( $_architect ) ) {
      $_architect = get_option( '_architect' );
    }

    $prefix     = '_panels_design_'; // declare prefix
    $sections   = array();
    $sections[] = array(
        'title'      => __( 'Content Panels Layout ', 'pzarchitect' ),
        'show_title' => FALSE,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-adjust-alt',
        'fields'     => array(
            array(
                'title'    => __( 'Components to show', 'pzarchitect' ),
                'id'       => $prefix . 'components-to-show',
                'type'     => 'button_set',
                'multi'    => TRUE,
                'subtitle' => __( 'Feature can be either the Featured Image of the post, or the Featured Video (added by Architect).', 'pzarchitect' ),
                'default'  => array(
                    'title',
                    'excerpt',
                    'meta1',
                    'image',
                ),
                'options'  => array(
                    'title'   => __( 'Title', 'pzarchitect' ),
                    'excerpt' => __( 'Excerpt', 'pzarchitect' ),
                    'content' => __( 'Body', 'pzarchitect' ),
                    'image'   => __( 'Feature', 'pzarchitect' ),
                    'meta1'   => __( 'Meta1', 'pzarchitect' ),
                    'meta2'   => __( 'Meta2', 'pzarchitect' ),
                    'meta3'   => __( 'Meta3', 'pzarchitect' ),
                    'custom1' => __( 'Custom 1', 'pzarchitect' ),
                    'custom2' => __( 'Custom 2', 'pzarchitect' ),
                    'custom3' => __( 'Custom 3', 'pzarchitect' ),
                ),
                'hint'     => array(
                    'title'   => __( 'Components to show', 'pzarchitect' ),
                    'content' => __( 'Select which base components to include in this post\'s layout.', 'pzarchitect' ),
                ),
            ),
            array(
                'title'        => __( 'Layout', 'pzarchitect' ),
                'id'           => $prefix . 'preview',
                'type'         => 'code',
                'readonly'     => FALSE,
                // Readonly fields can't be written to by code! Weird
                'code'         => draw_panel_layout(),
                'default_show' => FALSE,
                'subtitle'     => __( 'Drag and drop to reposition and resize components', 'pzarchitect' ),
                'default'      => json_encode( array(
                    'title'   => array(
                        'width' => 100,
                        'show'  => TRUE,
                        'name'  => 'title',
                    ),
                    'meta1'   => array(
                        'width' => 100,
                        'show'  => TRUE,
                        'name'  => 'meta1',
                    ),
                    'image'   => array(
                        'width' => 25,
                        'show'  => TRUE,
                        'name'  => 'image',
                    ),
                    'excerpt' => array(
                        'width' => 75,
                        'show'  => TRUE,
                        'name'  => 'excerpt',
                    ),
                    //                                            'caption' => array('width' => 100, 'show' => false),
                    'content' => array(
                        'width' => 100,
                        'show'  => FALSE,
                        'name'  => 'content',
                    ),
                    'meta2'   => array(
                        'width' => 100,
                        'show'  => FALSE,
                        'name'  => 'meta2',
                    ),
                    'meta3'   => array(
                        'width' => 100,
                        'show'  => FALSE,
                        'name'  => 'meta3',
                    ),
                    'custom1' => array(
                        'width' => 100,
                        'show'  => FALSE,
                        'name'  => 'custom1',
                    ),
                    'custom2' => array(
                        'width' => 100,
                        'show'  => FALSE,
                        'name'  => 'custom2',
                    ),
                    'custom3' => array(
                        'width' => 100,
                        'show'  => FALSE,
                        'name'  => 'custom1',
                    ),
                ) ),
                'hint'         => array(
                    'title'   => __( 'Post layout', 'pzarchitect' ),
                    'content' => __( 'Drag and drop to sort the order of your elements. <strong>Heights are fluid, so not indicative of how it will look on the page</strong>.', 'pzarchitect' ),
                ),
            ),
            array(
                'title'    => __( 'Feature location', 'pzarchitect' ),
                'id'       => $prefix . 'feature-location',
                'width'    => '100%',
                'type'     => 'button_set',
                'default'  => 'components',
                'required' => array(
                    $prefix . 'components-to-show',
                    'contains',
                    'image',
                ),
                'subtitle' => 'Use Background when you need the image to fill the post layout.',
                'options'  => array(
                    'components'    => __( 'In Components Group', 'pzarchitect' ),
                    'float'         => __( 'Outside components', 'pzarchitect' ),
                    'content-left'  => __( 'In body/excerpt left', 'pzarchitect' ),
                    'content-right' => __( 'In body/excerpt right', 'pzarchitect' ),
                    'fill'          => __( 'Background', 'pzarchitect' ),
                ),
                'hint'     => array(
                    'title'   => __( 'Feature location', 'pzarchitect' ),
                    'content' => __( 'Select where within the post layout you want to display the Feature.', 'pzarchitect' ),
                ),
            ),
            array(
                'title'    => __( 'Feature align', 'pzarchitect' ),
                'id'       => $prefix . 'feature-float',
                'type'     => 'button_set',
                'default'  => 'default',
                'required' => array(
                    array(
                        $prefix . 'components-to-show',
                        'contains',
                        'image',
                    ),
                    array(
                        $prefix . 'feature-location',
                        '=',
                        'components',
                    ),
                ),
                'subtitle' => __( 'Float the feature left or right to help close gaps between images and other components.', 'pzarchitect' ),
                'options'  => array(
                    'default' => __( 'Default', 'pzarchitect' ),
                    'left'    => __( 'Left', 'pzarchitect' ),
                    'right'   => __( 'Right', 'pzarchitect' ),
                ),
            ),
            array(
                'title'    => __( 'Alternate features position', 'pzarchitect' ),
                'id'       => $prefix . 'alternate-feature-position',
                'type'     => 'button_set',
                'default'  => 'off',
                'required' => array(
                    array(
                        $prefix . 'feature-location',
                        '!=',
                        'fill',
                    ),
                    array(
                        $prefix . 'feature-location',
                        '!=',
                        'components',
                    ),
                    array(
                        $prefix . 'components-position',
                        '!=',
                        'top',
                    ),
                    array(
                        $prefix . 'components-position',
                        '!=',
                        'bottom',
                    ),
                ),
                'options'  => array(
                    'off' => __( 'No', 'pzarchitect' ),
                    'on'  => __( 'Yes', 'pzarchitect' ),
                ),
                'hint'     => array(
                    'title'   => __( 'Alternate features position', 'pzarchitect' ),
                    'content' => __( 'Alternate the features position left/right for each post.', 'pzarchitect' ),
                ),
            ),
            array(
                'title'    => __( 'Feature in', 'pzarchitect' ),
                'id'       => $prefix . 'feature-in',
                'type'     => 'button_set',
                'multi'    => TRUE,
                //                  'class'=> 'arc-field-advanced' ,
                'default'  => array(
                    'excerpt',
                    'content',
                ),
                'required' => array(
                    array(
                        $prefix . 'feature-location',
                        '!=',
                        'components',
                    ),
                    array(
                        $prefix . 'feature-location',
                        '!=',
                        'float',
                    ),
                    array(
                        $prefix . 'feature-location',
                        '!=',
                        'fill',
                    ),
                    //                      array('show_advanced', 'equals', true),
                ),
                'options'  => array(
                    'excerpt' => __( 'Excerpt', 'pzarchitect' ),
                    'content' => __( 'Body', 'pzarchitect' ),
                ),
                'hint'     => array(
                    'title'   => __( 'Feature in', 'pzarchitect' ),
                    'content' => __( 'Set whether to display the Feature in the Excerpt, the Body or both. The default is both.<br><br> If you are using the Excerpt in full layouts as an introduction paragraph, this is one example of when you would turn off the Feature for the Excerpt.', 'pzarchitect' ),
                ),
            ),
            array(
                'title'   => __( 'Components area position', 'pzarchitect' ),
                'id'      => $prefix . 'components-position',
                'type'    => 'button_set',
                'width'   => '100%',
                'default' => 'top',
                'options' => array(
                    'top'    => __( 'Top', 'pzarchitect' ),
                    'bottom' => __( 'Bottom', 'pzarchitect' ),
                    'left'   => __( 'Left', 'pzarchitect' ),
                    'right'  => __( 'Right', 'pzarchitect' ),
                ),
                'hint'    => array(
                    'title'   => __( 'Components area position', 'pzarchitect' ),
                    'content' => __( 'Position for all the components as a group.', 'pzarchitect' ),
                ),
                'desc'    => __( 'Left/right will only take affect when components area width is less than 100%', 'pzarchitect' ),
            ),
            array(
                'title'         => __( 'Components area width %', 'pzarchitect' ),
                'id'            => $prefix . 'components-widths',
                'type'          => 'slider',
                'default'       => 100,
                'min'           => 1,
                'max'           => 100,
                'step'          => 1,
                'class'         => ' percent',
                'display_value' => 'label',
                'hint'          => array(
                    'title'   => __( 'Components area width', 'pzarchitect' ),
                    'content' => __( 'Set the overall width for the components area. Necessary for left or right positioning of sections', 'pzarchitect' ),
                ),
            ),
            array(
                'title'         => __( 'Nudge components area up/down %', 'pzarchitect' ),
                'id'            => $prefix . 'components-nudge-y',
                'type'          => 'slider',
                'default'       => 0,
                'min'           => 0,
                'max'           => 100,
                'step'          => 1,
                'class'         => ' percent',
                'required'      => array(
                    $prefix . 'feature-location',
                    '=',
                    'fill',
                ),
                'display_value' => 'label',
                'hint'          => array(
                    'title'   => __( 'Nudge components are up/down', 'pzarchitect' ),
                    'content' => __( 'Enter percent to move the components area up/down. </br<br>NOTE: These measurements are percentage of the post layout.', 'pzarchitect' ),
                ),
            ),
            array(
                'title'         => __( 'Nudge components area left/right %', 'pzarchitect' ),
                'id'            => $prefix . 'components-nudge-x',
                'type'          => 'slider',
                'default'       => 0,
                'min'           => 0,
                'max'           => 100,
                'step'          => 1,
                'class'         => ' percent',
                'required'      => array(
                    $prefix . 'feature-location',
                    '=',
                    'fill',
                ),
                'display_value' => 'label',
                'hint'          => array(
                    'title'   => __( 'Nudge components are left/right', 'pzarchitect' ),
                    'content' => __( 'Enter percent to move the components area left/right. </br><br>NOTE: These measurements are percentage of the post layout.', 'pzarchitect' ),
                ),
            ),
            array(
                'title'         => __( 'Feature as thumbnail width %', 'pzarchitect' ),
                'id'            => $prefix . 'thumb-width',
                'type'          => 'slider',
                'default'       => 15,
                'min'           => 0,
                'max'           => 100,
                'step'          => 1,
                'class'         => ' percent',
                'required'      => array(
                    array(
                        $prefix . 'feature-location',
                        '!=',
                        'fill',
                    ),
                    array(
                        $prefix . 'feature-location',
                        '!=',
                        'float',
                    ),
                    array(
                        $prefix . 'feature-location',
                        '!=',
                        'components',
                    ),
                ),
                'display_value' => 'label',
                'subtitle'      => __( 'Set to zero to use image at actual size.', 'pzarchitect' ),
                'hint'          => array(
                    'title'   => __( 'Feature as thumbnail width', 'pzarchitect' ),
                    'content' => __( 'When you have set the featured image to appear in the body/excerpt, this determines its width.', 'pzarchitect' ),
                ),
            ),
            array(
                'title'   => __( 'Make header and footer', 'pzarchitect' ),
                'id'      => $prefix . 'components-headers-footers',
                'type'    => 'switch',
                'on'      => __( 'Yes', 'pzarchitect' ),
                'off'     => __( 'No', 'pzarchitect' ),
                //              'class'=> 'arc-field-advanced' ,
                //'required' => array('show_advanced', 'equals', true),
                'default' => TRUE,
                'hint'    => array(
                    'title'   => __( 'Make header and footer', 'pzarchitect' ),
                    'content' => __( 'When enabled, Architect will automatically wrap the header and footer components of the post layout in header and footer tags to maintain compatibility with current WP layout trends.<br><br>However, some layouts, such as tabular, are not suited to using the headers and footers.', 'pzarchitect' ),
                ),
            ),
            array(
                'title'   => __( 'Link whole panel', 'pzarchitect' ),
                'id'      => $prefix . 'link-panel',
                //            'cols'    => 4,
                'type'    => 'switch',
                'on'      => __( 'Yes', 'pzarchitect' ),
                'off'     => __( 'No', 'pzarchitect' ),
                'default' => FALSE,
                'hint'    => array(
                    'title'   => 'Link whole panel',
                    'content' => __( 'If enabled, clicking anywhere on the panel will take the viewer to the post. Note: No other links within the panel will work.', 'pzarchitect' ),
                ),

                /// can't set defaults on checkboxes!
            ),
        ),
    );

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

      $sections[] = array(
          'title'      => __( 'Content Panels Styling', 'pzarchitect' ),
          'show_title' => FALSE,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'fields'     => pzarc_fields(//                array(
//                    'title'    => __('Load style'),
//                    'id'       => $prefix . 'panels-load-style',
//                    'type'     => 'select',
//                    'subtitle' => 'Sorry to tease, but this isn\'t implemented yet.',
//                    'options'  => array('none', 'dark', 'light'),
//                    'default'  => 'none'
//                ),
              array(
                  'title'    => __( 'Panels', 'pzarchitect' ),
                  'id'       => $prefix . 'panels-section',
                  'type'     => 'section',
                  'indent'   => TRUE,
                  'class'    => 'heading',
                  'subtitle' => 'Class: .pzarc-panel',
                  'hint'     => array( 'content' => 'Class: .pzarc-panel' ),
              ), pzarc_redux_bg( $prefix . 'panels' . $background, '', $defaults[ $optprefix . 'panels' . $background ] ), pzarc_redux_padding( $prefix . 'panels' . $padding, '', $defaults[ $optprefix . 'panels' . $padding ] ), pzarc_redux_borders( $prefix . 'panels' . $border, '', $defaults[ $optprefix . 'panels' . $border ] ), array(
              'title'    => __( 'Components group', 'pzarchitect' ),
              'id'       => $prefix . 'components-section',
              'type'     => 'section',
              'indent'   => TRUE,
              'class'    => 'heading',
              'hint'     => array( 'content' => 'Class: .pzarc_components' ),
              'subtitle' => 'Class: .pzarc_components',
          ), pzarc_redux_bg( $prefix . 'components' . $background, array( '.pzarc_components' ), $defaults[ $optprefix . 'components' . $background ] ), pzarc_redux_padding( $prefix . 'components' . $padding, array( '.pzarc_components' ), $defaults[ $optprefix . 'components' . $padding ] ), pzarc_redux_borders( $prefix . 'components' . $border, array( '.pzarc_components' ), $defaults[ $optprefix . 'components' . $border ] ), array(
              'title'    => __( 'Entry', 'pzarchitect' ),
              'id'       => $prefix . 'hentry-section',
              'type'     => 'section',
              'indent'   => TRUE,
              'class'    => 'heading',
              'hint'     => array( 'content' => 'Class: .hentry' ),
              'subtitle' => ! $this->defaults ? ( is_array( $_architect['architect_config_hentry-selectors'] ) ? 'Classes: ' . implode( ', ', $_architect['architect_config_hentry-selectors'] ) : 'Class: ' . $_architect['architect_config_hentry-selectors'] ) : '',
          ), // id,selectors,defaults
              // need to grab selectors from options
              // e.g. $_architect['architect_config_hentry-selectors']
              // Then we need to get them back later
              pzarc_redux_bg( $prefix . 'hentry' . $background, ! $this->defaults ? $_architect['architect_config_hentry-selectors'] : '', $defaults[ $optprefix . 'hentry' . $background ] ), pzarc_redux_padding( $prefix . 'hentry' . $padding, ! $this->defaults ? $_architect['architect_config_hentry-selectors'] : '', $defaults[ $optprefix . 'hentry' . $padding ] ), pzarc_redux_margin( $prefix . 'hentry' . $margin, ! $this->defaults ? $_architect['architect_config_hentry-selectors'] : '', $defaults[ $optprefix . 'hentry' . $margin ] ), pzarc_redux_borders( $prefix . 'hentry' . $border, ! $this->defaults ? $_architect['architect_config_hentry-selectors'] : '', $defaults[ $optprefix . 'hentry' . $border ] ) ),
      );

      $sections[] = array(
          'id'         => 'custom-css',
          'title'      => __( 'Custom CSS', 'pzarchitect' ),
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-wrench',
          'fields'     => array(
              array(
                  'id'       => $prefix . 'custom-css',
                  'type'     => 'ace_editor',
                  'title'    => __( 'Custom CSS', 'pzarchitect' ),
                  'mode'     => 'css',
                  'options'  => array( 'minLines' => 25 ),
                  'default'  => $defaults[ $optprefix . 'custom-css' ],
                  'subtitle' => __( 'As a shorthand, you can prefix your CSS class with MYBLUEPRINT or MYPANELS and Architect will substitute the correct class for this Blueprint. e.g. MYPANELS .entry-content{border-radius:5px;}', 'pzarchitect' ),
              ),
          ),
      );
    }


    // Create the metaboxes


    $metaboxes[] = array(
        'id'         => 'panels-design',
        'title'      => __( 'Content Panels Layout and stylings: Setup the layout of the content itself and panel stylings.', 'pzarchitect' ),
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => FALSE,

    );

    return $metaboxes;

  }

}
new arc_mbPanels();