<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 13/2/18
   * Time: 9:06 PM
   */


  class arc_mbLayouts  extends arc_Blueprints_Designer {
    function __construct( $defaults = FALSE ) {
      parent::__construct( $defaults );
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'mb_layouts', ), 10, 1 );
    }

    function mb_layouts( $metaboxes, $defaults_only = FALSE ) {
      pzdb( __FUNCTION__ );
      global $_architect;
      global $_architect_options;
      if ( empty( $_architect_options ) ) {
        $_architect_options = get_option( '_architect_options' );
      }

      if ( empty( $_architect ) ) {
        $_architect = get_option( '_architect' );
      }

      $prefix                 = '_blueprints_'; // declare prefix
      $sections               = array();
      $sections['_tabular']   = array(
          'title'      => __( 'Tabular settings', 'pzarchitect' ),
          'show_title' => TRUE,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-adjust-alt',
          //          'desc'       => 'When the navigation type is set to navigator, presentation will always be in a slider form. You can have multiple navigators on a page, thus multiple sliders.',
          'fields'     => array(
              array(
                  'id'         => $prefix . 'section-0-table-column-titles',
                  'title'      => __( 'Table column titles', 'pzarchitect' ),
                  'type'       => 'multi_text',
                  'show_empty' => FALSE,
                  'add_text'   => 'Add a title',
                  'default'    => array(),
              ),
          ),
      );
      $sections['_accordion'] = array(
          'title'      => __( 'Accordion settings', 'pzarchitect' ),
          'show_title' => TRUE,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-adjust-alt',
          'fields'     => array(
              array(
                  'title'    => __( 'Accordion open', 'pzarchitect' ),
                  'id'       => $prefix . 'accordion-closed',
                  'type'     => 'switch',
                  'on'       => __( 'Yes', 'pzarchitect' ),
                  'off'      => __( 'No', 'pzarchitect' ),
                  'default'  => FALSE,
                  'subtitle' => __( 'Turn off to have accordion closed on startup.', 'pzarchitect' ),
              ),
              array(
                  'id'         => $prefix . 'section-0-accordion-titles',
                  'title'      => __( 'Accordion titles', 'pzarchitect' ),
                  'type'       => 'multi_text',
                  'show_empty' => FALSE,
                  'add_text'   => 'Add a title',
                  'subtitle'   => 'Optional. Leave as none to use post titles',
                  'default'    => array(),
              ),
          ),
      );
      /**
       *
       * SLIDERS & TABBED
       */
      $tabbed = array(
          'tabbed' => array(
              'alt' => 'Titles',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-tabbed.png',
          ),
          'labels' => array(
              'alt' => 'Labels',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-labels.png',
          ),
      );
      $slider = array(
          'bullets' => array(
              'alt' => 'Bullets',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-bullets.png',
          ),
          'tabbed'  => array(
              'alt' => 'Titles',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-tabbed.png',
          ),
          'labels'  => array(
              'alt' => 'Labels',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-labels.png',
          ),
          'numbers' => array(
              'alt' => 'Numbers',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-numeric.png',
          ),
          'thumbs'  => array(
              'alt' => 'Thumbs',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-thumbs.png',
          ),
          'none'    => array(
              'alt' => 'None',
              'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-type-none.png',
          ),
      );

      $sections['_slidertabbed'] = array(
          'title'      => __( 'Sliders & Tabbed settings', 'pzarchitect' ),
          'show_title' => TRUE,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-adjust-alt',
          //          'desc'       => 'When the navigation type is set to navigator, presentation will always be in a slider form. You can have multiple navigators on a page, thus multiple sliders.',
          'fields'     => array(
              array(
                  'id'       => $prefix . 'slider-engine',
                  'title'    => __( 'Slider engine', 'pzarchitect' ),
                  'type'     => 'button_set',
                  'default'  => 'slick',
                  'options'  => apply_filters( 'arc-slider-engine', array() ),
                  'required' => array(
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'basic',
                      ),
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'masonry',
                      ),
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'accordion',
                      ),
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'table',
                      ),
                  ),
                  'hint'     => array(
                      'title'   => __( 'Slider engine', 'pzarchitect' ),
                      'content' => __( 'Sliders and Tabbed are controlled by a slider engine. Developers can add their own', 'pzarchitect' ),
                  ),
              ),
              array(
                  'id'      => $prefix . 'navigator',
                  'title'   => __( 'Type', 'pzarchitect' ),
                  'type'    => 'image_select',
                  'default' => 'bullets',
                  'hint'    => array( 'content' => __( 'Bullets,Titles, Labels, Numbers, Thumbnails or none', 'pzarchitect' ) ),
                  'options' => $slider,
              ),
              array(
                  'title'    => __( 'Titles & Labels', 'pzarchitect' ),
                  'id'       => $prefix . 'section-navtabs-heading',
                  'type'     => 'section',
                  'indent'   => TRUE,
                  'required' => array(
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'buttons',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'numbers',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'bullets',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'thumbs',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'none',
                      ),
                      //                      array($prefix . 'navigator-position', '!=', 'left'),
                      //                      array($prefix . 'navigator-position', '!=', 'right')
                  ),
              ),
              array(
                  'title'      => 'Labels',
                  'id'         => $prefix . 'navigator-labels',
                  'type'       => 'multi_text',
                  'show_empty' => FALSE,
                  'add_text'   => 'Add a label',
                  'default'    => 'Label name',
                  'required'   => array(
                      array(
                          $prefix . 'navigator',
                          'equals',
                          'labels',
                      ),
                  ),
                  'subtitle'   => 'One label per panel. Labels only work for a fixed number of panels',
              ),
              array(
                  'title'    => 'Width type',
                  'id'       => $prefix . 'navtabs-width-type',
                  'type'     => 'button_set',
                  'default'  => 'fluid',
                  'options'  => array(
                      'fluid' => 'Fluid',
                      'even'  => 'Even',
                      'fixed' => 'Fixed',
                  ),
                  'required' => array(
                      array(
                          $prefix . 'navigator-position',
                          '!=',
                          'left',
                      ),
                      array(
                          $prefix . 'navigator-position',
                          '!=',
                          'right',
                      ),
                  ),
                  'hint'     => array(
                      'title'   => __( 'Tab width type', 'pzarchitect' ),
                      'content' => __( 'Fluid: Adjusts to the width of the content<br>Even: Distributes evenly across the width of the blueprint<br>Fixed: Set a specific width', 'pzarchitect' ),
                  ),
              ),
              array(
                  'id'       => $prefix . 'navtabs-margins-compensation',
                  'type'     => 'dimensions',
                  'units'    => array(
                      '%',
                      'px',
                      'em',
                  ),
                  'width'    => TRUE,
                  'height'   => FALSE,
                  'title'    => __( 'Margins compensation', 'pzarchitect' ),
                  'default'  => array(
                      'width' => '0',
                      'units' => '%',
                  ),
                  'subtitle' => __( 'If you set margins for the tabs anywhere else, enter the sum of the left and right margins and units of a single tab. e.g. if margins are 5px, enter 10px', 'pzarchitect' ),
                  'required' => array(
                      array(
                          $prefix . 'navtabs-width-type',
                          'contains',
                          'even',
                      ),
                  ),
              ),
              array(
                  'id'       => $prefix . 'navtabs-width',
                  'type'     => 'dimensions',
                  'units'    => array(
                      '%',
                      'px',
                      'em',
                  ),
                  'width'    => TRUE,
                  'height'   => FALSE,
                  'title'    => __( 'Fixed width', 'pzarchitect' ),
                  'default'  => array(
                      'width' => '10',
                      'units' => '%',
                  ),
                  'required' => array(
                      array(
                          $prefix . 'navtabs-width-type',
                          'contains',
                          'fixed',
                      ),
                  ),
              ),
              array(
                  'id'       => $prefix . 'navtabs-maxlen',
                  'type'     => 'spinner',
                  'title'    => __( 'Max length (characters)', 'pzarchitect' ),
                  'default'  => 0,
                  'min'      => 0,
                  'max'      => 1000,
                  'subtitle' => __( '0 for no max', 'pzarchitect' ),
              ),
              array(
                  'title'   => 'Text wrap',
                  'id'      => $prefix . 'navtabs-textwrap',
                  'type'    => 'button_set',
                  'default' => '',
                  'options' => array(
                      ''           => 'Default',
                      'wraplines'  => 'Wrap lines',
                      'break-word' => 'Break word',
                      'nowrap'     => 'No wrap',
                  ),
              ),
              array(
                  'title'    => 'Text overflow',
                  'id'       => $prefix . 'navtabs-textoverflow',
                  'type'     => 'button_set',
                  'default'  => '',
                  'required' => array(
                      $prefix . 'navtabs-textwrap',
                      '!=',
                      'break-word',
                  ),
                  'options'  => array(
                      ''                => 'Default',
                      'visible'         => 'Visible',
                      'hidden-ellipses' => 'Hidden with ellipses',
                      'hidden-clip'     => 'Hidden with clipping',
                  ),
              ),
              array(
                  'id'       => $prefix . 'section-navtabs-end',
                  'type'     => 'section',
                  'indent'   => FALSE,
                  'required' => array(
                      array(
                          $prefix . 'section-0-layout-mode',
                          '=',
                          'tabbed',
                      ),
                  ),
              ),
              array(
                  'title'  => __( 'Layout', 'pzarchitect' ),
                  'id'     => $prefix . 'section-navlayout-heading',
                  'type'   => 'section',
                  'indent' => TRUE,
              ),
              array(
                  'title'    => 'Position',
                  'id'       => $prefix . 'navigator-position',
                  'type'     => 'image_select',
                  'default'  => 'bottom',
                  'hint'     => array( 'content' => __( 'Bottom, top,left, right', 'pzarchitect' ) ),
                  'height'   => 75,
                  'options'  => array(
                      'bottom' => array(
                          'alt' => 'Bottom',
                          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-bottom.png',
                      ),
                      'top'    => array(
                          'alt' => 'Top',
                          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-top.png',
                      ),
                      'left'   => array(
                          'alt' => 'Left',
                          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-left.png',
                      ),
                      'right'  => array(
                          'alt' => 'Right',
                          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-right.png',
                      ),
                  ),
                  'required' => array(
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'thumbs',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'none',
                      ),
                  ),
              ),
              array(
                  'title'    => 'Position',
                  'id'       => $prefix . 'navigator-thumbs-position',
                  'type'     => 'image_select',
                  'default'  => 'bottom',
                  'hint'     => array( 'content' => __( 'Bottom, top', 'pzarchitect' ) ),
                  'height'   => 75,
                  'options'  => array(
                      'bottom' => array(
                          'alt' => 'Bottom',
                          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-bottom.png',
                      ),
                      'top'    => array(
                          'alt' => 'Top',
                          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-pos-top.png',
                      ),
                  ),
                  'required' => array(
                      array(
                          $prefix . 'navigator',
                          '=',
                          'thumbs',
                      ),
                  ),
              ),
              array(
                  'title'    => 'Location',
                  'id'       => $prefix . 'navigator-location',
                  'hint'     => array( 'content' => __( 'Select whether navigator should appear over the content area, or outside of it', 'pzarchitect' ) ),
                  'type'     => 'image_select',
                  'default'  => 'outside',
                  'height'   => 75,
                  'options'  => array(
                      'inside'  => array(
                          'alt' => 'Inside',
                          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-loc-inside.png',
                      ),
                      'outside' => array(
                          'alt' => 'Outside',
                          'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/nav-loc-outside.png',
                      ),
                  ),
                  'required' => array(
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'accordion',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'none',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'tabbed',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'thumbs',
                      ),
                      array(
                          $prefix . 'navigator-position',
                          '!=',
                          'left',
                      ),
                      array(
                          $prefix . 'navigator-position',
                          '!=',
                          'right',
                      ),
                  ),

              ),
              array(
                  'title'    => 'Horizontal alignment',
                  'id'       => $prefix . 'navigator-align',
                  'type'     => 'button_set',
                  'default'  => 'center',
                  'options'  => array(
                      'left'    => 'Left',
                      'center'  => 'Centre',
                      'right'   => 'Right',
                      'justify' => 'Justified',
                  ),
                  'required' => array(
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'accordion',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'buttons',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'thumbs',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'none',
                      ),
                      array(
                          $prefix . 'navigator-position',
                          '!=',
                          'left',
                      ),
                      array(
                          $prefix . 'navigator-position',
                          '!=',
                          'right',
                      ),
                  ),
              ),
              array(
                  'title'    => 'Vertical width',
                  'id'       => $prefix . 'navigator-vertical-width',
                  'type'     => 'dimensions',
                  'default'  => array( 'width' => '15%' ),
                  'height'   => FALSE,
                  'units'    => '%',
                  'subtitle' => __( 'Note: Set left and right padding on the navigator to zero.', 'pzarchitect' ),
                  'required' => array(
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'thumbs',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'accordion',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'none',
                      ),
                      array(
                          $prefix . 'navigator-position',
                          '!=',
                          'top',
                      ),
                      array(
                          $prefix . 'navigator-position',
                          '!=',
                          'bottom',
                      ),
                  ),
              ),
              array(
                  'title'    => 'Bullet shape',
                  'id'       => $prefix . 'navigator-bullet-shape',
                  'type'     => 'button_set',
                  'default'  => 'circle',
                  'options'  => array(
                      'circle' => 'Circle',
                      'square' => 'Square',
                  ),
                  'required' => array(
                      array(
                          $prefix . 'navigator',
                          'equals',
                          'bullets',
                      ),
                  ),
              ),
              array(
                  'title'    => 'Sizing',
                  'id'       => $prefix . 'navigator-sizing',
                  'type'     => 'button_set',
                  'default'  => 'small',
                  'options'  => array(
                      'small'  => 'Small',
                      'medium' => 'Medium',
                      'large'  => 'Large',
                  ),
                  'required' => array(
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'none',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'thumbs',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'tabbed',
                      ),
                      array(
                          $prefix . 'navigator',
                          '!=',
                          'labels',
                      ),
                  ),
              ),
              array(
                  'title'    => 'Thumb dimensions',
                  'id'       => $prefix . 'navigator-thumb-dimensions',
                  'type'     => 'dimensions',
                  'default'  => array(
                      'width'  => '50px',
                      'height' => '50px',
                  ),
                  'units'    => 'px',
                  'required' => array(
                      array(
                          $prefix . 'navigator',
                          '=',
                          'thumbs',
                      ),
                  ),
              ),
              array(
                  'title'    => 'Slide Pager',
                  'id'       => $prefix . 'navigator-pager',
                  'type'     => 'button_set',
                  'default'  => 'hover',
                  'options'  => array(
                      'none'  => 'None',
                      'hover' => 'Hover',
                      /// TODO: Add inline pager to nav
                      //                      'inline' => 'Inline with navigator',
                      //                      'both'   => 'Both'
                  ),
                  'required' => array(
                      $prefix . 'section-0-layout-mode',
                      '=',
                      'slider',
                  ),
              ),
              //                            array(
              //                                'title'    => 'Skip left icon',
              //                                'id'       => $prefix . 'navigator-skip-left',
              //                                'type'     => 'button_set',
              //                                'default'  => 'backward',
              //                                'options'  => array(
              //                                    'backward'      => 'Backward',
              //                                    'step-backward' => 'Step Backward',
              //                                ),
              //                                'required' => array(
              //                                    array($prefix . 'navigator', '=', 'thumbs'),
              //                                )
              //                            ),
              //                            array(
              //                                'title'    => 'Skip right icon',
              //                                'id'       => $prefix . 'navigator-skip-right',
              //                                'type'     => 'button_set',
              //                                'default'  => 'forward',
              //                                'options'  => array(
              //                                    'forward'      => 'Forward',
              //                                    'step-forward' => 'Step Forward',
              //                                ),
              //                                'required' => array(
              //                                    array($prefix . 'navigator', '=', 'thumbs'),
              //                                )
              //          ),
              array(
                  'title'    => 'Navigation skip button',
                  'id'       => $prefix . 'navigator-skip-button',
                  'type'     => 'button_set',
                  'default'  => 'circle',
                  'options'  => array(
                      'none'   => __( 'None', 'pzarchitect' ),
                      'circle' => __( 'Circle', 'pzarchitect' ),
                      'square' => __( 'Square', 'pzarchitect' ),
                  ),
                  'required' => array(
                      array(
                          $prefix . 'section-0-layout-mode',
                          '=',
                          'slider',
                      ),
                      array(
                          $prefix . 'navigator',
                          '=',
                          'thumbs',
                      ),
                  ),
              ),
              array(
                  'id'       => $prefix . 'navigator-skip-thumbs',
                  'title'    => __( 'Number of thumbs visible', 'pzarchitect' ),
                  'type'     => 'spinner',
                  'default'  => 5,
                  'min'      => 1,
                  'max'      => 100,
                  'hint'     => array(
                      'title'   => __( 'Number of thumbs visible', 'pzarchitect' ),
                      'content' => __( 'Number of thumbs to fully show at once in the navigator. If Continuous is enabled, partial thumbs will additionally be shown left and right of the full thumbs.', 'pzarchitect' ),
                  ),
                  'required' => array(
                      array(
                          $prefix . 'section-0-layout-mode',
                          '=',
                          'slider',
                      ),
                      array(
                          $prefix . 'navigator',
                          '=',
                          'thumbs',
                      ),
                  ),
              ),
              array(
                  'title'    => 'Continuous thumbs',
                  'id'       => $prefix . 'navigator-continuous',
                  'type'     => 'button_set',
                  'default'  => 'continuous',
                  'options'  => array(
                      'off'        => __( 'No', 'pzarchitect' ),
                      'continuous' => __( 'Yes', 'pzarchitect' ),
                  ),
                  'required' => array(
                      array(
                          $prefix . 'section-0-layout-mode',
                          '=',
                          'slider',
                      ),
                      array(
                          $prefix . 'navigator',
                          '=',
                          'thumbs',
                      ),
                  ),
              ),
              array(
                  'id'     => $prefix . 'section-navlayout-end',
                  'type'   => 'section',
                  'indent' => FALSE,
              ),
              /** TRANSITIONS
               ******************/

              array(
                  'title'    => __( 'Transitions timing', 'pzarchitect' ),
                  'id'       => $prefix . 'section-transitions-heading',
                  'type'     => 'section',
                  'indent'   => TRUE,
                  'required' => array(
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'basic',
                      ),
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'masonry',
                      ),
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'tabular',
                      ),
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'accordion',
                      ),
                  ),
              ),
              array(
                  'title'         => 'Duration (seconds)',
                  'id'            => $prefix . 'transitions-duration',
                  'type'          => 'slider',
                  'min'           => 0,
                  'max'           => 10,
                  'resolution'    => 0.1,
                  'step'          => 0.5,
                  'hint'          => array( 'content' => __( 'Time taken for the transition to display', 'pzarchitect' ) ),
                  'default'       => 2,
                  'display_value' => 'label',
                  'required'      => array(
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'basic',
                      ),
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'masonry',
                      ),
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'tabular',
                      ),
                      array(
                          $prefix . 'section-0-layout-mode',
                          '!=',
                          'accordion',
                      ),
                  ),
              ),
              array(
                  'title'         => 'Interval (seconds)',
                  'id'            => $prefix . 'transitions-interval',
                  'type'          => 'slider',
                  'resolution'    => 0.1,
                  'step'          => 0.5,
                  'default'       => 0,
                  'min'           => 0,
                  'max'           => 60,
                  'display_value' => 'label',
                  'desc'          => __( 'Set to zero to disable autoplay', 'pzarchitect' ),
                  'hint'          => array( 'content' => __( 'Time slide is shown with no transitions active. Set to zero to disable autoplay', 'pzarchitect' ) ),
                  'required'      => array(
                      $prefix . 'section-0-layout-mode',
                      '=',
                      'slider',
                  ),
              ),
          ),
      );

      $sections['_slidertabbed'] = apply_filters( 'arc-extend-slider-settings', $sections['_slidertabbed'] );

      /**
       * MASONRY
       */
      $sort_fields = apply_filters( 'arc_masonry_sorting', array_merge( array(
          'Standard' => array(
              'random'       => __( 'Random', 'pzarchitect' ),
              '.entry-title' => __( 'Title', 'pzarchitect' ),
              '[data-order]' => __( 'Date', 'pzarchitect' ),
              '.author'      => __( 'Author', 'pzarchitect' ),
          ),
      ), array( 'Custom Fields' => $this->custom_fields ) ) );
//      global $pzarc_masonry_filter_taxes;
      //     var_dump( $_GET, $this->postmeta);
      if ( is_admin() && ! empty( $_GET['post'] ) && ! empty( $this->postmeta[ $prefix . 'masonry-filtering' ] ) ) {
        $pzarc_masonry_filter_taxes = apply_filters( '_arc_add_tax_titles', maybe_unserialize( $this->postmeta[ $prefix . 'masonry-filtering' ][0] ) );
      } else {
        $pzarc_masonry_filter_taxes = array();
      }
      $sections['_masonry'] = array(
          'title'      => __( 'Masonry settings', 'pzarchitect' ),
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-adjust-alt',
          'fields'     => array(
              array(
                  'title'         => __( 'Animation duration (ms)', 'pzarchitect' ),
                  'id'            => '_blueprints_masonry-animation-duration',
                  'type'          => 'slider',
                  'default'       => 300,
                  'display_value' => 'label',
                  'min'           => 0,
                  'max'           => 1000,
                  'step'          => 10,
                  'subtitle'      => __( 'Set the duration in milliseconds of the animation of panels when arranging', 'pzarchitect' ),
              ),
              array(
                  'title'         => __( 'Stagger animation duration (ms)', 'pzarchitect' ),
                  'id'            => '_blueprints_masonry-animation-stagger',
                  'type'          => 'slider',
                  'default'       => 30,
                  'display_value' => 'label',
                  'min'           => 0,
                  'max'           => 300,
                  'step'          => 10,
                  'subtitle'      => __( 'Set the delay in milliseconds between the animation of panels when arranging', 'pzarchitect' ),
              ),
              array(
                  'title'   => __( 'Features', 'pzarchitect' ),
                  'id'      => '_blueprints_masonry-features',
                  'type'    => 'button_set',
                  'multi'   => TRUE,
                  'options' => array(// Infinite scroll requires a method to load next set, so would would best leveraging off pagination -maybe... And that is a lot harder!
                                     // Waypoints provides infinite scroll support.
                                     //                      'infinite-scroll' => __('Infinite scroll', 'pzarchitect'),
                                     'filtering'     => __( 'Filtering', 'pzarchitect' ),
                                     'sorting'       => __( 'Sorting', 'pzarchitect' ),
                                     'bidirectional' => __( 'Packed masonry', 'pzarchitect' ),
                  ),
                  'default' => array(),
                  'desc'    => __( '', 'pzarchitect' ),
              ),
              array(
                  'title'    => __( 'Packed masonry', 'pzarchitect' ),
                  'id'       => '_blueprints_masonry-full-section-open',
                  'type'     => 'section',
                  'required' => array(
                      '_blueprints_masonry-features',
                      'contains',
                      'bidirectional',
                  ),
                  'indent'   => TRUE,
                  'subtitle' => __( 'In Packed Masonry mode, Panels Across and Fixed Width Panels are ignored. The width and height of each panel is determined by its the image\'s dimensions. Therefore, it is important you choose the Shrink to fit width and height limit option for Image Cropping to get the effect. Packed Masonry has no settings.', 'pzarchitect' ),
              ),
              array(
                  'id'     => '_blueprints_masonry-packed-section-close',
                  'type'   => 'section',
                  'indent' => FALSE,
              ),
              array(
                  'title'    => __( 'Filtering and Sorting Controls', 'pzarchitect' ),
                  'id'       => '_blueprints_masonry-controls-section-open',
                  'type'     => 'section',
                  'required' => array(
                      '_blueprints_masonry-features',
                      'contains',
                      'ing',
                  ),
                  'indent'   => TRUE,
              ),
              array(
                  'title'    => __( 'Controls location', 'pzarchitect' ),
                  'id'       => '_blueprints_masonry-filtering-controls-location',
                  'type'     => 'button_set',
                  'default'  => 'top',
                  'options'  => array(
                      'top'    => __( 'Top', 'pzarchitect' ),
                      'right'  => __( 'Right', 'pzarchitect' ),
                      'bottom' => __( 'Bottom', 'pzarchitect' ),
                      'left'   => __( 'Left', 'pzarchitect' ),
                  ),
                  'subtitle' => __( 'Set where you would like to display the filtering and sorting buttons.', 'pzarchitect' ),
              ),
              array(
                  'title'         => __( 'Controls width (%)', 'pzarchitect' ),
                  'id'            => '_blueprints_masonry-filtering-controls-width',
                  'type'          => 'slider',
                  'default'       => 100,
                  'display_value' => 'label',
                  'min'           => 5,
                  'max'           => 100,
                  'step'          => 5,
                  'subtitle'      => __( 'Set the width for the filtering and sorting buttons section.', 'pzarchitect' ),
              ),
              array(
                  'id'     => '_blueprints_masonry-controls-section-close',
                  'type'   => 'section',
                  'indent' => FALSE,
              ),

              // Infinite scroll options: Show progress
              // Sorting: Choose what on (title, date, cat, tag) and order
              array(
                  'title'    => __( 'Sorting', 'pzarchitect' ),
                  'id'       => '_blueprints_masonry-sorting-section-open',
                  'type'     => 'section',
                  'indent'   => TRUE,
                  'required' => array(
                      $prefix . 'masonry-features',
                      'contains',
                      'sorting',
                  ),
              ),
              array(
                  'title'    => __( 'Sort fields', 'pzarchitect' ),
                  'id'       => '_blueprints_masonry-sort-fields',
                  'type'     => 'select',
                  'multi'    => TRUE,
                  'default'  => array(),
                  //                  'sortable' => true, //when enabled, it breaks coz $sort_Fields is a multilevel array
                  'options'  => $sort_fields,
                  'required' => array(
                      '_blueprints_masonry-features',
                      'contains',
                      'sorting',
                  ),
                  'subtitle' => __( 'Some plugins, such as Woo Commerce, you need to use the field beginning with an underscore', 'pzarchtiect' ),
                  'desc'     => __( 'It is up to YOU to ensure the sort fields are available in the Blueprint', 'pzarchitect' ),
              ),
              array(
                  'title'    => __( 'Numeric sort fields', 'pzarchitect' ),
                  'id'       => '_blueprints_masonry-sort-fields-numeric',
                  'type'     => 'select',
                  'default'  => array(),
                  'multi'    => TRUE,
                  'options'  => $this->custom_fields,
                  'required' => array(
                      $prefix . 'masonry-features',
                      'contains',
                      'sorting',
                  ),
                  'subtitle' => __( 'Select which of the above custom fields are numeric', 'pzarchitect' ),
              ),
              array(
                  'title'    => __( 'Date sort fields', 'pzarchitect' ),
                  'id'       => '_blueprints_masonry-sort-fields-date',
                  'type'     => 'select',
                  'default'  => array(),
                  'multi'    => TRUE,
                  'options'  => $this->custom_fields,
                  'required' => array(
                      '_blueprints_masonry-features',
                      'contains',
                      'sorting',
                  ),
                  'subtitle' => __( 'Select which of the above custom fields contain dates', 'pzarchitect' ),
              ),
              array(
                  'id'     => '_blueprints_masonry-sorting-section-close',
                  'type'   => 'section',
                  'indent' => FALSE,
              ),
              /**
               * filtering
               **/
              array(
                  'title'    => __( 'Filtering', 'pzarchitect' ),
                  'id'       => '_blueprints_masonry-filtering-section-open',
                  'type'     => 'section',
                  'required' => array(
                      '_blueprints_masonry-features',
                      'contains',
                      'filtering',
                  ),
                  'indent'   => TRUE,
              ),
              array(
                  'title'    => __( 'Taxonomies', 'pzarchitect' ),
                  'id'       => $prefix . 'masonry-filtering',
                  'type'     => 'select',
                  'default'  => array(),
                  'multi'    => TRUE,
                  'sortable' => TRUE,
                  'data'     => 'callback',
                  'args'     => array( 'pzarc_get_taxonomies_ctb' ),
                  //        'required' => array( $prefix . 'masonry-features', 'contains', 'filtering' ),
                  'desc'     => __( 'It is up to YOU to ensure the taxonomies match the content', 'pzarchitect' ),
                  'subtitle' => __( 'Note: You will have to publish/update to show the Taxonomy filters below', 'pzarchitect' ),
              ),
              array(
                  'title'    => __( 'Allow multiple filter terms', 'pzarchitect' ),
                  'id'       => '_blueprints_masonry-filtering-allow-multiple',
                  'type'     => 'button_set',
                  'default'  => 'multiple',
                  'options'  => array(
                      'multiple' => __( 'Yes', 'pzarchitect' ),
                      'single'   => __( 'No', 'pzarchitect' ),
                  ),
                  //          'required' => array( $prefix . 'masonry-features', 'contains', 'filtering' ),
                  'subtitle' => __( 'Allow multiple filters to be selected by site users. Note: Multiple selected filters narrow the selection shown. Therefore, ensure content is well tagged for best results.', 'pzarchitect' ),
              ),
              array(
                  'id'     => $prefix . 'masonry-filtering-section-close',
                  'type'   => 'section',
                  'indent' => FALSE,
              ),
          ),
      );

      foreach ( $pzarc_masonry_filter_taxes as $pzarc_tax ) {

        $sections['_masonry']['fields'][] = array(
            'title'    => __( 'Filter on ', 'pzarchitect' ) . ucwords( str_replace( array(
                    '_',
                    '-',
                ), ' ', $pzarc_tax ) ),
            'id'       => '_blueprints_masonry-filtering-section-open-' . $pzarc_tax,
            'type'     => 'section',
            'required' => array(
                '_blueprints_masonry-features',
                'contains',
                'filtering',
            ),
            'indent'   => TRUE,
        );
        // This extra field is necessary because changing the value of $pzarc_tax (as the loop does)
        // prevents the select field from being changed to no value. Seriously!!
        $sections['_masonry']['fields'][] = array(
            'title'    => __( 'Set default terms ', 'pzarchitect' ),
            'id'       => '_blueprints_masonry-filtering-set-defaults-' . $pzarc_tax,
            'type'     => 'button_set',
            'default'  => 'no',
            'options'  => array(
                'no'  => __( 'No', 'pzarchitect' ),
                'yes' => __( 'Yes', 'pzarchitect' ),
            ),
            'required' => array(
                '_blueprints_masonry-features',
                'contains',
                'filtering',
            ),
            'subtitle' => __( 'Control what terms are shown.', 'pzarchitect' ),
        );
        $sections['_masonry']['fields'][] = array(
            'title'    => __( 'Default selected terms', 'pzarchitect' ),
            'id'       => '_blueprints_masonry-filtering-default-terms-' . $pzarc_tax,
            'type'     => 'select',
            'multi'    => TRUE,
            'default'  => array(),
            'data'     => 'terms',
            //          'select2'  => array( 'allowClear' => true ),
            'args'     => array(
                'taxonomies' => $pzarc_tax,
                'hide_empty' => FALSE,
            ),
            'required' => array(
                '_blueprints_masonry-filtering-set-defaults-' . $pzarc_tax,
                'equals',
                'yes',
            ),
            'subtitle' => __( 'Select which terms to show by default.', 'pzarchitect' ),
        );

        $sections['_masonry']['fields'][] = array(
            'title'    => __( 'Limit ', 'pzarchitect' ),
            'id'       => '_blueprints_masonry-filtering-limit-' . $pzarc_tax,
            'type'     => 'button_set',
            'default'  => 'none',
            'options'  => array(
                'none'    => __( 'None', 'pzarchitect' ),
                'include' => __( 'Include', 'pzarchitect' ),
                'exclude' => __( 'Exclude', 'pzarchitect' ),
            ),
            'required' => array(
                '_blueprints_masonry-features',
                'contains',
                'filtering',
            ),
            'subtitle' => __( 'Control what terms are shown.', 'pzarchitect' ),
        );
        $sections['_masonry']['fields'][] = array(
            'title'    => __( 'Inclusions/Exclusions', 'pzarchitect' ),
            'id'       => '_blueprints_masonry-filtering-incexc-' . $pzarc_tax,
            'type'     => 'select',
            'multi'    => TRUE,
            'data'     => 'terms',
            'default'  => array(),
            'required' => array(
                '_blueprints_masonry-filtering-limit-' . $pzarc_tax,
                '!=',
                'none',
            ),
            'args'     => array(
                'taxonomies' => $pzarc_tax,
                'hide_empty' => FALSE,
            ),
        );
        $sections['_masonry']['fields'][] = array(
            'id'     => '_blueprints_masonry-filtering-section-close-' . $pzarc_tax,
            'type'   => 'section',
            'indent' => FALSE,
        );
      }
//      $sections[ '_masonry' ][ 'fields' ][] = array(
//        'id'     => $prefix . 'masonry-filtering-section-close',
//        'type'   => 'section',
//        'indent' => false,
//      );

//      var_Dump($sections['_masonry']['fields']);
//die();

      $sections['_masonry'] = apply_filters( 'arc-extend-masonry-settings', $sections['_masonry'] );

      /** PAGINATION  */
      $sections['_pagination'] = array(
          'title'      => 'Pagination',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-chevron-right',
          'fields'     => array(
              array(
                  'title'   => 'Pagination',
                  'id'      => '_blueprints_pagination',
                  'type'    => 'switch',
                  'on'      => 'Yes',
                  'off'     => 'No',
                  'default' => FALSE,
                  'desc'    => __( 'If this Blueprint is displaying blog posts on the blog page (thus Defaults is the Content Source) and you choose to enable overrides, pagination will likely mess up.', 'pzarchitect' ),
              ),
              array(
                  'title'    => __( 'Settings', 'pzarchitect' ),
                  'id'       => '_blueprint_pagination-settings-section',
                  'type'     => 'section',
                  'indent'   => TRUE,
                  'required' => array(
                      '_blueprints_pagination',
                      'equals',
                      TRUE,
                  ),
              ),
              array(
                  'title'    => __( 'Posts per page', 'pzarchitect' ),
                  'id'       => '_blueprints_pagination-per-page',
                  'type'     => 'spinner',
                  'default'  => get_option( 'posts_per_page' ),
                  'min'      => 1,
                  'max'      => 99,
                  'required' => array(
                      '_blueprints_pagination',
                      'equals',
                      TRUE,
                  ),
                  'desc'     => __( 'If this Blueprint is displaying blog posts on the blog page (thus Defaults is the Content Source), change WP <em>Settings > Reading > Blog pages show at most</em> to control posts per page', 'pzarchitect' ),
              ),
              array(
                  'id'      => '_blueprints_pager-location',
                  'title'   => __( 'Pagination location', 'pzarchitect' ),
                  'type'    => 'select',
                  'select2' => array( 'allowClear' => FALSE ),
                  'default' => 'bottom',
                  'options' => array(
                      'bottom' => 'Bottom',
                      'top'    => 'Top',
                      'both'   => 'Both',
                  ),
              ),
              array(
                  'id'      => '_blueprints_pager',
                  'title'   => __( 'Blog Pagination', 'pzarchitect' ),
                  'type'    => 'select',
                  'select2' => array( 'allowClear' => FALSE ),
                  'default' => 'prevnext',
                  'options' => array(//                    'none'     => 'None',
                                     'prevnext' => 'Previous/Next',
                                     'names'    => 'Post names',
                                     'pagenavi' => 'PageNavi',
                  ),
              ),
              array(
                  'id'      => '_blueprints_pager-single',
                  'title'   => __( 'Single Post Pagination', 'pzarchitect' ),
                  'type'    => 'select',
                  'select2' => array( 'allowClear' => FALSE ),
                  'default' => 'prevnext',
                  'options' => array(//                    'none'     => 'None',
                                     'prevnext' => 'Previous/Next',
                                     'names'    => 'Post names',
                                     'pagenavi' => 'PageNavi',
                  ),
              ),
              array(
                  'id'      => '_blueprints_pager-archives',
                  'title'   => __( 'Archives Pagination', 'pzarchitect' ),
                  'type'    => 'select',
                  'select2' => array( 'allowClear' => FALSE ),
                  'default' => 'prevnext',
                  'options' => array(//                    'none'     => 'None',
                                     'prevnext' => 'Previous/Next',
                                     'names'    => 'Post names',
                                     'pagenavi' => 'PageNavi',
                  ),
              ),
              array(
                  'id'      => '_blueprints_pager-custom-prev',
                  'title'   => __( 'Custom text for Previous', 'pzarchitect' ),
                  'type'    => 'text',
                  'default' => '',
              ),
              array(
                  'id'      => '_blueprints_pager-custom-next',
                  'title'   => __( 'Custom text for Next', 'pzarchitect' ),
                  'type'    => 'text',
                  'default' => '',
              ),
              array(
                  'id'       => '_blueprint_pagination-settings-section-end',
                  'type'     => 'section',
                  'indent'   => FALSE,
                  'required' => array(
                      '_blueprints_pagination',
                      'equals',
                      TRUE,
                  ),
              ),
          ),
      );

      //  Styling
      if ( ! empty( $_architect_options['architect_enable_styling'] ) ) {
        $defaults = get_option( '_architect' );
        $prefix   = '_blueprints_styling_'; // declare prefix
// TODO: need to get styling to take
        $font         = '-font';
        $link         = '-links';
        $padding      = '-padding';
        $margin       = '-margins';
        $background   = '-background';
        $border       = '-borders';
        $borderradius = '-borderradius';

        $stylingSections                   = array();
        $optprefix                         = 'architect_config_';
        $thisSection                       = 'blueprint';
        $thisSection                       = 'navigator';
        $sections['_styling_slidertabbed'] = array(
            'title'      => 'Sliders & Tabbed styling',
            'show_title' => FALSE,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-brush',
            'fields'     => array(

                array(
                    'title'    => __( 'Navigator container', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-nav-container-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Classes: ' . implode( ', ', array(
                            '.arc-slider-nav',
                            '.pzarc-navigator',
                        ) ),

                ),
                pzarc_redux_bg( $prefix . $thisSection . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
                pzarc_redux_padding( $prefix . $thisSection . $padding, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ] ),
                pzarc_redux_margin( $prefix . $thisSection . $margin, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ] ),
                pzarc_redux_borders( $prefix . $thisSection . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ] ),
                array(
                    'title'    => __( 'Navigator items', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-nav-items-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Class: ' . implode( ', ', array( '.pzarc-navigator .arc-slider-slide-nav-item' ) ),

                ),
                pzarc_redux_font( $prefix . $thisSection . '-items' . $font, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . '-items' . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $background ] ),
                pzarc_redux_padding( $prefix . $thisSection . '-items' . $padding, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $padding ] ),
                pzarc_redux_margin( $prefix . $thisSection . '-items' . $margin, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $margin ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-items' . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $border ] ),
                pzarc_redux_border_radius( $prefix . $thisSection . '-items' . $borderradius, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items' . $borderradius ] ),
                array(
                    'title'    => __( 'Navigator item hover', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-nav-hover-item-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Class: ' . implode( ', ', array( '.pzarc-navigator .arc-slider-slide-nav-item:hover' ) ),

                ),
                pzarc_redux_font( $prefix . $thisSection . '-items-hover' . $font, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-hover' . $font ], array(
                    'letter-spacing',
                    'font-variant',
                    'text-transform',
                    'font-family',
                    'font-style',
                    'text-align',
                    'line-height',
                    'word-spacing',
                ) ),
                pzarc_redux_bg( $prefix . $thisSection . '-items-hover' . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-hover' . $background ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-items-hover' . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-hover' . $border ] ),
                array(
                    'title'    => __( 'Navigator active item', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-nav-active-item-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Class: ' . implode( ', ', array(
                            '.pzarc-navigator .arc-slider-slide-nav-item.active',
                            '.pzarc-navigator .arc-slider-slide-nav-item.current',
                        ) ),

                ),
                pzarc_redux_font( $prefix . $thisSection . '-items-active' . $font, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-active' . $font ], array(
                    'letter-spacing',
                    'font-variant',
                    'text-transform',
                    'font-family',
                    'font-style',
                    'text-align',
                    'line-height',
                    'word-spacing',
                ) ),
                pzarc_redux_bg( $prefix . $thisSection . '-items-active' . $background, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-active' . $background ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-items-active' . $border, $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . '-items-active' . $border ] ),
            ),
        );

        $thisSection                  = 'masonry';
        $sections['_styling_masonry'] = array(
            'id'         => 'masonry-css',
            'title'      => 'Masonry styling',
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-brush',
            'fields'     => array(
                array(
                    'title'    => __( 'Filtering and sorting section', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-masonry-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-selectors' ],

                ),
                pzarc_redux_font( $prefix . $thisSection . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
                pzarc_redux_padding( $prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ] ),
                pzarc_redux_margin( $prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ] ),
                pzarc_redux_borders( $prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ] ),
                array(
                    'title'    => __( 'Buttons', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-masonry-buttons-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ],

                ),
                pzarc_redux_font( $prefix . $thisSection . '-buttons' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ], $defaults[ $optprefix . $thisSection . '-buttons' . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . '-buttons' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ], $defaults[ $optprefix . $thisSection . '-buttons' . $background ] ),
                pzarc_redux_padding( $prefix . $thisSection . '-buttons' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ], $defaults[ $optprefix . $thisSection . '-buttons' . $padding ] ),
                pzarc_redux_margin( $prefix . $thisSection . '-buttons' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ], $defaults[ $optprefix . $thisSection . '-buttons' . $margin ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-buttons' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-buttons-selectors' ], $defaults[ $optprefix . $thisSection . '-buttons' . $border ] ),
                array(
                    'title'    => __( 'Selected', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-masonry-selected-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-selected-selectors' ],
                ),
                pzarc_redux_font( $prefix . $thisSection . '-selected' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selected-selectors' ], $defaults[ $optprefix . $thisSection . '-selected' . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . '-selected' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selected-selectors' ], $defaults[ $optprefix . $thisSection . '-selected' . $background ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-selected' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selected-selectors' ], $defaults[ $optprefix . $thisSection . '-selected' . $border ] ),
                array(
                    'title'    => __( 'Hover', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-hover-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ],
                ),
                pzarc_redux_font( $prefix . $thisSection . '-hover' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . '-hover' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $background ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-hover' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $border ] ),
                array(
                    'title'    => __( 'Clear All and Defaults button', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-clear-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ],
                ),
                pzarc_redux_font( $prefix . $thisSection . '-clear' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-clear' . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . '-clear' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-clear' . $background ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-clear' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-clear-selectors' ], $defaults[ $optprefix . $thisSection . '-clear' . $border ] ),
            ),
        );

        $thisSection                    = 'accordion-titles';
        $sections['_styling_accordion'] = array(
            'id'         => 'accordion-css',
            'title'      => 'Accordion styling',
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-brush',
            'fields'     => array(
                array(
                    'title'    => __( 'Titles', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-accordion-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-selectors' ],

                ),
                pzarc_redux_font( $prefix . $thisSection . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ),
                pzarc_redux_padding( $prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ] ),
                pzarc_redux_margin( $prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ] ),
                pzarc_redux_borders( $prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ] ),

                array(
                    'title'    => __( 'Opened', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-accordion-open-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ],
                ),
                pzarc_redux_font( $prefix . $thisSection . '-open' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ], $defaults[ $optprefix . $thisSection . '-open' . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . '-open' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ], $defaults[ $optprefix . $thisSection . '-open' . $background ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-open' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-open-selectors' ], $defaults[ $optprefix . $thisSection . '-open' . $border ] ),

                //            array(
                //              'title'    => __( 'Closed', 'pzarchitect' ),
                //              'id'       => $prefix . 'blueprint-accordion-closed-css-heading',
                //              'type'     => 'section',
                //              'indent'   => true,
                //              'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-closed-selectors' ]
                //            ),
                //            pzarc_redux_font( $prefix . $thisSection . '-closed' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-closed-selectors' ], $defaults[ $optprefix . $thisSection . '-closed' . $font ] ),
                //            pzarc_redux_bg( $prefix . $thisSection . '-closed' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-closed-selectors' ], $defaults[ $optprefix . $thisSection . '-closed' . $background ] ),
                //            pzarc_redux_borders( $prefix . $thisSection . '-closed' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-closed-selectors' ], $defaults[ $optprefix . $thisSection . '-closed' . $border ] ),
                array(
                    'title'    => __( 'Hover', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-accordion-hover-css-heading',
                    'type'     => 'section',
                    'indent'   => TRUE,
                    'subtitle' => 'Class: ' . $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ],
                ),
                pzarc_redux_font( $prefix . $thisSection . '-hover' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . '-hover' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $background ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-hover' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-hover-selectors' ], $defaults[ $optprefix . $thisSection . '-hover' . $border ] ),
            ),
        );
        $thisSection                    = 'tabular';
        $sections['_styling_tabular']   = array(
            'id'         => 'tabular-css',
            'title'      => 'Tabular styling',
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-brush',
            'fields'     => array(
                array(
                    'title'    => __( 'Headings', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-tabular-css-heading',
                    'type'     => 'section',
                    'subtitle' => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ],
                    'indent'   => TRUE,

                ),
                pzarc_redux_font( $prefix . $thisSection . '-headings' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . '-headings' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $background ] ),
                pzarc_redux_padding( $prefix . $thisSection . '-headings' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $padding ] ),
                pzarc_redux_margin( $prefix . $thisSection . '-headings' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $margin ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-headings' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-headings-selectors' ], $defaults[ $optprefix . $thisSection . '-headings' . $border ] ),
                array(
                    'title'    => __( 'Odd rows', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-tabular-odd-rows-css-heading',
                    'type'     => 'section',
                    'subtitle' => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ],
                    'indent'   => TRUE,

                ),
                pzarc_redux_font( $prefix . $thisSection . '-odd-rows' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-odd-rows' . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . '-odd-rows' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-odd-rows' . $background ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-odd-rows' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-odd-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-odd-rows' . $border ] ),
                array(
                    'title'    => __( 'Even rows', 'pzarchitect' ),
                    'id'       => $prefix . 'blueprint-tabular-even-rows-css-heading',
                    'type'     => 'section',
                    'subtitle' => 'Class: ' . $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ],
                    'indent'   => TRUE,

                ),
                pzarc_redux_font( $prefix . $thisSection . '-even-rows' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-even-rows' . $font ] ),
                pzarc_redux_bg( $prefix . $thisSection . '-even-rows' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-even-rows' . $background ] ),
                pzarc_redux_borders( $prefix . $thisSection . '-even-rows' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-even-rows-selectors' ], $defaults[ $optprefix . $thisSection . '-even-rows' . $border ] ),
            ),
        );
      }
      $metaboxes[] = array(
          'id'         => 'type-settings',
          'title'      => 'Additional settings for chosen Blueprint layout',
          'post_types' => array( 'arc-blueprints' ),
          'sections'   => $sections,
          'position'   => 'normal',
          'priority'   => 'low',
          'sidebar'    => FALSE,

      );

      return $metaboxes;

    }
  }
  new arc_mbLayouts();