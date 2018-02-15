<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 13/2/18
   * Time: 8:57 PM
   */


  define('_amb_section',1500);
  define('_amb_bp_custom_css',1600); // 0s are mains
  define('_amb_blueprint_help',1799); // 99s are help
  define('_amb_styling_sections_wrapper',1850); // 50s are styling
  define('_amb_styling_page',1950);
  define('_amb_styling_general',2050);


  /**
   * LAYOUT
   */
  class arc_mbBlueprintDesign extends arc_Blueprints_Designer {
    function __construct( $defaults = FALSE ) {
      parent::__construct( $defaults );
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'mb_blueprint_design', ), 10, 1 );
    }

    function mb_blueprint_design( $metaboxes, $defaults_only = FALSE ) {
    pzdb( __FUNCTION__ );
    $prefix   = '_blueprints_'; // declare prefix
    $sections = array();
    global $_architect_options;
    if ( empty( $_architect_options ) ) {
      $_architect_options = get_option( '_architect_options' );
    }
    if ( empty( $_architect ) ) {
      $_architect = get_option( '_architect' );
    }


    /** SECTIONS */
    $icons     = array(
        0 => 'el-icon-th-large',
        1 => 'el-icon-th',
        2 => 'el-icon-th-list',
    );
    $modes[0]  = array(
        'basic'     => __( 'Basic', 'pzarchitect' ),
        'slider'    => __( 'Slider', 'pzarchitect' ),
        'tabbed'    => __( 'Tabbed', 'pzarchitect' ),
        'masonry'   => __( 'Masonry', 'pzarchitect' ),
        'table'     => __( 'Tabular', 'pzarchitect' ),
        'accordion' => __( 'Accordion', 'pzarchitect' ),
        //          'fitRows'         => 'Fit rows',
        //          'fitColumns'      => 'Fit columns',
        //          'masonryVertical' => 'Masonry Vertical',
        //          'panelsByRow'      => 'Panels by row',
        //          'panelsByColumn'   => 'Panels by column',
        //          'vertical'    => 'Vertical',
        //          'horizontal'  => 'Horizontal',
    );
    $modes[1]  = array(
        'basic'     => __( 'Grid/Single', 'pzarchitect' ),
        'masonry'   => __( 'Masonry', 'pzarchitect' ),
        'table'     => __( 'Tabular', 'pzarchitect' ),
        'accordion' => __( 'Accordion', 'pzarchitect' ),
        //          'fitRows'         => 'Fit rows',
        //          'fitColumns'      => 'Fit columns',
        //          'masonryVertical' => 'Masonry Vertical',
        //          'panelsByRow'      => 'Panels by row',
        //          'panelsByColumn'   => 'Panels by column',
        //          'vertical'    => 'Vertical',
        //          'horizontal'  => 'Horizontal',
    );
    $modesx[0] = array(
        'basic'     => array(
            'alt' => 'Grid/Single',
            'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-grid.svg',
        ),
        'slider'    => array(
            'alt' => 'Slider',
            'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-slider.svg',
        ),
        'tabbed'    => array(
            'alt' => 'Tabbed',
            'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-tabbed.svg',
        ),
        'masonry'   => array(
            'alt' => 'Masonry',
            'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-masonry.svg',
        ),
        'table'     => array(
            'alt' => 'Tabular',
            'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-tabular.svg',
        ),
        'accordion' => array(
            'alt' => 'Accordion',
            'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-accordion.svg',
        ),
    );
    $modesx[1] = array(
        'basic'     => array(
            'alt' => 'Grid/Single',
            'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-grid.scg',
        ),
        'masonry'   => array(
            'alt' => 'Masonry',
            'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-masonry.svg',
        ),
        'table'     => array(
            'alt' => 'Tabular',
            'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-tabular.svg',
        ),
        'accordion' => array(
            'alt' => 'Accordion',
            'img' => PZARC_PLUGIN_APP_URL . 'shared/assets/images/metaboxes/layouts-accordion.svg',
        ),
    );
    $desc[0]   = 'Grid/Single, Slider, Tabbed, Masonry, Tabular, Accordion';
    $desc[1]   = 'Grid/Single, Masonry, Tabular, Accordion';
    for ( $i = 0; $i < 1; $i ++ ) {
      $sections[ _amb_section . ( $i + 1 ) ] = array(
          'title'      => __( 'Design', 'pzarchitect' ),
          'show_title' => TRUE,
          'desc'       => '<p class="arc-important-admin-message">' . __( 'Each post in WordPress is - in a coding context - displayed in its own box. In Architect we call that box a <strong>Panel</strong>. So when you see the term Panel, it means an individual post layout.
            <br>Panels can be laid out beside each other as in grids, masonry and tabular; or layered as in sliders, tabbed and accordions
            <br>Note: The term Posts is used but refers generically to any WordPress post type or custom post type.', 'pzarchitect' ) . '</p>',
          'icon_class' => 'icon-large',
          'icon'       => $icons[ $i ],
          'fields'     => array(
              array(
                  'title'    => __( 'Layout type', 'pzarchitect' ),
                  'id'       => $prefix . 'section-' . $i . '-layout-mode',
                  'type'     => 'image_select',
                  'default'  => 'basic',
                  'subtitle' => $desc[ (int) ( $i > 0 ) ],
                  'height'   => 80,
                  'options'  => $modesx[ (int) ( $i > 0 ) ],
                  'hint'     => array(
                      'title'   => 'Layout types',
                      'content' => __( '<strong>Grid</strong> is for flat designs like single posts, blog excerpts and magazine grids.<br>
<br><strong>Masonry</strong> is like Basic but formats for a Pinterest-like design.<br>
<br><strong>Slider</strong> for making sliders like featured posts, image slideshows etc.<br>
<br><strong>Tabbed</strong> for tabbed designs.<br>
<br><strong>Tabular</strong> displays the content in a table, and applies extra controls.<br>
<br><em>Note: To use Tabular, make sure your Content Layout is in a table-like design - that is, all fields on a single row</em><br>
<br><strong>Accordion</strong> for a vertical Accordion design. i.e. posts in a single column.<br>
', 'pzarchitect' ),
                  ),
              ),
              array(
                  'id'     => $prefix . 'section-' . $i . '-panels-heading',
                  'title'  => __( 'General panels settings', 'pzarchitect' ),
                  'type'   => 'section',
                  'indent' => TRUE,
              ),
              array(
                  'title'       => __( 'Limit posts', 'pzarchitect' ),
                  'id'          => $prefix . 'section-' . $i . '-panels-limited',
                  'type'        => 'switch',
                  'on'          => __( 'Yes', 'pzarchitect' ),
                  'off'         => __( 'No', 'pzarchitect' ),
                  'default'     => TRUE,
                  'subtitle'    => __( 'Each panel displays a single post from the selected content type.', 'pzarchitect' ),
                  'description' => ( ! defined( 'PZARC_PRO' ) ? '<br><strong>' . __( 'Architect Lite is always limited to a maximum of 15 posts/pages', 'pzarchitect' ) . '</strong>' : '' ),
              ),
              array(
                  'title'    => __( 'Limit number of posts to show to', 'pzarchitect' ),
                  'id'       => $prefix . 'section-' . $i . '-panels-per-view',
                  'type'     => 'spinner',
                  'default'  => 6,
                  'min'      => 1,
                  'max'      => 99,
                  'subtitle' => __( 'This is how many posts will show if Limit enabled above', 'pzarchitect' ),
                  'required' => array(
                      $prefix . 'section-' . $i . '-panels-limited',
                      'equals',
                      TRUE,
                  ),
              ),
              array(
                  'title'   => __( 'Fixed width panels', 'pzarchitect' ),
                  'id'      => $prefix . 'section-' . $i . '-panels-fixed-width',
                  'type'    => 'switch',
                  'on'      => __( 'Yes', 'pzarchitect' ),
                  'off'     => __( 'No', 'pzarchitect' ),
                  'default' => FALSE,
              ),
              array(
                  'id'       => $prefix . 'section-' . $i . '-columns-heading',
                  'title'    => __( 'Panels across', 'pzarchitect' ),
                  'type'     => 'section',
                  'indent'   => TRUE,
                  'required' => array(
                      $prefix . 'section-' . $i . '-panels-fixed-width',
                      'equals',
                      FALSE,
                  ),
              ),
              array(
                  'title'         => __( 'Wide screen', 'pzarchitect' ),
                  'subtitle'      => $_architect_options['architect_breakpoint_1']['width'] . ' and above',
                  'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-1',
                  'hint'          => array(
                      'title'   => __( 'Wide screen', 'pzarchitect' ),
                      'content' => __( 'Number of panels across on a wide screen as set in the breakpoints options. <br><br>In sliders, this would usually be one.', 'pzarchitect' ),
                  ),
                  'type'          => 'slider',
                  'default'       => 1,
                  'min'           => 1,
                  'max'           => 10,
                  'display_value' => 'label',
              ),
              array(
                  'title'         => __( 'Medium screen', 'pzarchitect' ),
                  'subtitle'      => $_architect_options['architect_breakpoint_2']['width'] . ' to ' . $_architect_options['architect_breakpoint_1']['width'],
                  'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-2',
                  'hint'          => array(
                      'title'   => __( 'Medium screen', 'pzarchitect' ),
                      'content' => __( 'Number of panels across on a medium screen as set in the breakpoints options', 'pzarchitect' ),
                  ),
                  'type'          => 'slider',
                  'default'       => 1,
                  'min'           => 1,
                  'max'           => 10,
                  'display_value' => 'label',
              ),
              array(
                  'title'         => __( 'Narrow screen', 'pzarchitect' ),
                  'subtitle'      => $_architect_options['architect_breakpoint_2']['width'] . ' and below',
                  'id'            => $prefix . 'section-' . $i . '-columns-breakpoint-3',
                  'hint'          => array(
                      'title'   => __( 'Narrow screen', 'pzarchitect' ),
                      'content' => __( 'Number of panels across on a narrow screen as set in the breakpoints options', 'pzarchitect' ),
                  ),
                  'type'          => 'slider',
                  'default'       => 1,
                  'min'           => 1,
                  'max'           => 10,
                  'display_value' => 'label',
              ),
              array(
                  'id'       => $prefix . 'section-' . $i . '-panel-width-heading',
                  'title'    => __( 'Panel width (px)', 'pzarchitect' ),
                  'type'     => 'section',
                  'indent'   => TRUE,
                  'required' => array(
                      $prefix . 'section-' . $i . '-panels-fixed-width',
                      'equals',
                      TRUE,
                  ),
              ),
              array(
                  'title'         => __( 'Wide screen', 'pzarchitect' ),
                  'subtitle'      => $_architect_options['architect_breakpoint_1']['width'] . ' and above',
                  'id'            => $prefix . 'section-' . $i . '-panel-width-breakpoint-1',
                  'hint'          => array(
                      'title'   => __( 'Wide screen', 'pzarchitect' ),
                      'content' => __( 'Width of the panels on wide screens', 'pzarchitect' ),
                  ),
                  'type'          => 'spinner',
                  'default'       => 250,
                  'min'           => 1,
                  'step'          => 1,
                  'max'           => 99999,
                  'display_value' => 'label',
              ),
              array(
                  'title'         => __( 'Medium screen', 'pzarchitect' ),
                  'subtitle'      => $_architect_options['architect_breakpoint_2']['width'] . ' to ' . $_architect_options['architect_breakpoint_1']['width'],
                  'id'            => $prefix . 'section-' . $i . '-panel-width-breakpoint-2',
                  'hint'          => array(
                      'title'   => __( 'Medium screen', 'pzarchitect' ),
                      'content' => __( 'Width of the panels on mediium screens', 'pzarchitect' ),
                  ),
                  'type'          => 'spinner',
                  'default'       => 350,
                  'min'           => 1,
                  'step'          => 1,
                  'max'           => 99999,
                  'display_value' => 'label',
              ),
              array(
                  'title'         => __( 'Narrow screen', 'pzarchitect' ),
                  'subtitle'      => $_architect_options['architect_breakpoint_2']['width'] . ' and below',
                  'id'            => $prefix . 'section-' . $i . '-panel-width-breakpoint-3',
                  'hint'          => array(
                      'title'   => __( 'Narrow screen', 'pzarchitect' ),
                      'content' => __( 'Panel width narrow screen', 'pzarchitect' ),
                  ),
                  'type'          => 'spinner',
                  'default'       => 320,
                  'min'           => 1,
                  'max'           => 99999,
                  'step'          => 1,
                  'display_value' => 'label',
              ),
              array(
                  'title'    => __( 'Justify panels', 'pzarchitect' ),
                  'id'       => $prefix . 'section-' . $i . '-panels-fixed-width-justify',
                  'type'     => 'button_set',
                  'options'  => array(
                      'justify-content: flex-start;'    => 'Start',
                      'justify-content: flex-end;'      => 'End',
                      'justify-content: center;'        => 'Centre',
                      'justify-content: space-between;' => 'Space between',
                      'justify-content: space-around;'  => 'Space around',
                  ),
                  'default'  => 'justify-content: space-between;',
                  'required' => array(
                      array(
                          $prefix . 'section-' . $i . '-panels-fixed-width',
                          'equals',
                          TRUE,
                      ),
                      array(
                          $prefix . 'section-0-layout-mode',
                          '=',
                          'basic',
                      ),
                  ),
                  'subtitle' => __( 'These are the standard Flexbox justification options', 'pzarchitect' ),
              ),
              array(
                  'title'    => __( 'Stretch panels to fill', 'pzarchitect' ),
                  'id'       => $prefix . 'section-' . $i . '-panels-fixed-width-fill',
                  'type'     => 'switch',
                  'on'       => __( 'Yes', 'pzarchitect' ),
                  'off'      => __( 'No', 'pzarchitect' ),
                  'subtitle' => __( 'Stretches panels to fill all available space per row, except margins.', 'pzarchitect' ),
                  'default'  => FALSE,
                  'required' => array(
                      array(
                          $prefix . 'section-' . $i . '-panels-fixed-width',
                          'equals',
                          TRUE,
                      ),
                      array(
                          $prefix . 'section-0-layout-mode',
                          '=',
                          'basic',
                      ),
                  ),
              ),
              array(
                  'id'     => $prefix . 'section-' . $i . '-panels-settings-heading',
                  'title'  => __( 'Panel dimensions', 'pzarchitect' ),
                  'type'   => 'section',
                  'indent' => TRUE,
              ),
              array(
                  'title'   => __( 'Minimum panel width', 'pzarchitect' ),
                  'id'      => $prefix . 'section-' . $i . '-min-panel-width',
                  'type'    => 'dimensions',
                  'height'  => FALSE,
                  'units'   => 'px',
                  'default' => array( 'width' => '0' ),
                  //      'hint'  => array('content' => __('Set the minimum width for panels in this section. This helps with responsive layout', 'pzarchitect'))
              ),
              array(
                  'title'   => __( 'Panel Height Type', 'pzarchitect' ),
                  'id'      => '_panels_settings_' . 'panel-height-type',
                  'type'    => 'select',
                  'default' => 'none',
                  //                  'class'=> 'arc-field-advanced' ,
                  //'required' => array('show_advanced', 'equals', true),
                  'select2' => array( 'allowClear' => FALSE ),
                  'options' => array(
                      'none'       => __( 'Fluid', 'pzarchitect' ),
                      'height'     => __( 'Exact', 'pzarchitect' ),
                      'max-height' => __( 'Max', 'pzarchitect' ),
                      'min-height' => __( 'Min', 'pzarchitect' ),
                  ),
                  'hint'    => array(
                      'title'   => __( 'Height type', 'pzarchitect' ),
                      'content' => __( 'Choose if you want an exact height or not for the panels. If you want totally fluid, choose Min, and set a height of 0.', 'pzarchitect' ),
                  ),
              ),
              // Hmm? How's this gunna sit with the min-height in templates?
              // We will want to use this for image height cropping when behind.

              array(
                  'title'    => __( 'Panel Height px', 'pzarchitect' ),
                  'id'       => '_panels_settings_' . 'panel-height',
                  'type'     => 'dimensions',
                  //          'class'=> 'arc-field-advanced' ,
                  'required' => array(
                      array(
                          '_panels_settings_' . 'panel-height-type',
                          '!=',
                          'none',
                      ),
                      //                      array('show_advanced', 'equals', true),,
                  ),
                  'width'    => FALSE,
                  'units'    => 'px',
                  'default'  => array( 'height' => '0' ),
                  'hint'     => array(
                      'title'   => __( 'Height', 'pzarchitect' ),
                      'content' => __( 'Set a height in pixels for the panel according to the height type you chose.', 'pzarchitect' ),
                  ),

              ),
              array(
                  'title'   => __( 'Panel margins', 'pzarchitect' ),
                  'id'      => $prefix . 'section-' . $i . '-panels-margins',
                  'type'    => 'spacing',
                  'units'   => array(
                      '%',
                      'px',
                      'em',
                  ),
                  'mode'    => 'margin',
                  'default' => array(
                      'margin-right'  => '0',
                      'margin-bottom' => '0',
                      'margin-left'   => '0',
                      'margin-top'    => '0',
                  ),
                  //'subtitle' => __('Right, bottom', 'pzarchitect')
                  //    'hint'  => array('content' => __('Set the vertical gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzarchitect'))
              ),
              array(
                  'id'       => $prefix . 'section-' . $i . '-panels-margins-guttered',
                  'type'     => 'switch',
                  'on'       => __( 'Yes', 'pzarchitect' ),
                  'off'      => __( 'No', 'pzarchitect' ),
                  'default'  => TRUE,
                  'title'    => __( 'Exclude top/left/right margins on outer panels', 'pzarchitect' ),
                  'required' => array(
                      $prefix . 'section-' . $i . '-layout-mode',
                      '=',
                      'basic',
                  ),
              ),
              array(
                  'id'     => $prefix . 'section-' . $i . '-blueprint-settings-heading',
                  'title'  => __( 'Blueprint settings', 'pzarchitect' ),
                  'type'   => 'section',
                  'indent' => TRUE,
              ),
              array(
                  'id'       => $prefix . 'blueprint-width',
                  'type'     => 'dimensions',
                  //               'mode'    => array('width' => true, 'height' => false),
                  'units'    => array(
                      '%',
                      'px',
                  ),
                  'width'    => TRUE,
                  'height'   => FALSE,
                  'title'    => __( 'Blueprint max width', 'pzarchitect' ),
                  'default'  => array(
                      'width' => '100',
                      'units' => '%',
                  ),
                  'subtitle' => __( 'Set a max width to stop spillage when the container is larger than you want the Blueprint to be.', 'pzarchitect' ),
              ),
              array(
                  'id'      => $prefix . 'blueprint-align',
                  'type'    => 'button_set',
                  'select2' => array( 'allowClear' => FALSE ),
                  'options' => array(
                      'left'   => __( 'Left', 'pzarchitect' ),
                      'center' => __( 'Centre', 'pzarchitect' ),
                      'right'  => __( 'Right', 'pzarchitect' ),
                  ),
                  'title'   => __( 'Blueprint align', 'pzarchitect' ),
                  'default' => 'center',
              ),
              array(
                  'title'    => __( 'Page title', 'pzarchitect' ),
                  'id'       => $prefix . 'page-title',
                  'type'     => 'switch',
                  'subtitle' => __( 'Show page title on single and archive pages', 'pzarchitect' ),
                  'on'       => 'Yes',
                  'off'      => 'No',
                  'default'  => FALSE,
              ),
              array(
                  'title'    => __( 'Hide archive title prefix', 'pzarchitect' ),
                  'id'       => $prefix . 'hide-archive-title-prefix',
                  'type'     => 'switch',
                  'subtitle' => __( 'When on an Archive page, hide the archive prefix from Architect > Options > Language', 'pzarchitect' ),
                  'on'       => 'Yes',
                  'off'      => 'No',
                  'default'  => FALSE,
                  'required' => array(
                      $prefix . 'page-title',
                      '=',
                      TRUE,
                  ),
              ),
              array(
                  'title'    => __( 'Show archive description', 'pzarchitect' ),
                  'id'       => $prefix . 'show-archive-description',
                  'type'     => 'switch',
                  'subtitle' => __( 'When on an Archive page, choose to show or not archive description', 'pzarchitect' ),
                  'on'       => 'Yes',
                  'off'      => 'No',
                  'default'  => FALSE,
              ),
              array(
                  'title'    => __( 'Blueprint display title', 'pzarchitect' ),
                  'id'       => $prefix . 'blueprint-title',
                  'type'     => 'text',
                  'subtitle' => __( 'Enter a title to display above the Blueprint', 'pzarchitect' ),
              ),
              array(
                  'title'    => __( 'Blueprint footer text', 'pzarchitect' ),
                  'id'       => $prefix . 'footer-text-link',
                  'type'     => 'text',
                  'subtitle' => __( 'Enter text to show at the foot of the Blueprint. You can also enter a shortcode here.', 'pzarchitect' ),
              ),
              array(
                  'title'   => __( 'Message if no content', 'pzarchitect' ),
                  'id'      => $prefix . 'no-content-message',
                  'type'    => 'text',
                  'default' => '',
              ),
              array(
                  'title'   => __( 'Hide Blueprint if no content', 'pzarchitect' ),
                  'id'      => $prefix . 'hide-blueprint',
                  'type'    => 'switch',
                  'on'      => 'Yes',
                  'off'     => 'No',
                  'default' => FALSE,
              ),
          ),

      );
    }

    /**
     * Styling
     */

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

      $stylingSections = array();
      $optprefix       = 'architect_config_';

      $thisSection = 'blueprint';

      $sections[_amb_styling_general] = array(
          'title'      => 'Blueprint styling',
          'show_title' => FALSE,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-brush',
          'fields'     => pzarc_fields(//                array(
//                    'title'   => __('Load style'),
//                    'id'      => $prefix . 'blueprints-load-style',
//                    'type'    => 'select',
//                    'desc'    => 'Sorry to tease, but this isn\'t implemented yet.',
//                    'options' => array('none', 'dark', 'light'),
//                    'default' => 'none'
//                ),
              array(
                  'title'    => __( 'Blueprint', 'pzarchitect' ),
                  'id'       => $prefix . 'blueprint-section-blueprint',
                  'type'     => 'section',
                  'indent'   => TRUE,
                  'class'    => 'heading',
                  'subtitle' => 'Class: .pzarc-blueprint_{shortname}',
              ),

              // TODO: Add shadows! Or maybe not!
              pzarc_redux_bg( $prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ), pzarc_redux_padding( $prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ] ), pzarc_redux_margin( $prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ] ), pzarc_redux_borders( $prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ] ), pzarc_redux_links( $prefix . $thisSection . $link, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $link ] ), array(
              'id'     => $prefix . 'blueprint-end-section-blueprint',
              'type'   => 'section',
              'indent' => FALSE,
          ), array(
              'title'    => __( 'Blueprint title', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-section-blueprint-title-css',
              'type'     => 'section',
              'indent'   => TRUE,
              'subtitle' => 'Class: .pzarc-blueprint-title',
          ), pzarc_redux_font( $prefix . $thisSection . '_blueprint-title' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-title' . $font ] ), pzarc_redux_bg( $prefix . $thisSection . '_blueprint-title' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-title' . $background ] ), pzarc_redux_padding( $prefix . $thisSection . '_blueprint-title' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-title' . $padding ] ), pzarc_redux_margin( $prefix . $thisSection . '_blueprint-title' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-title' . $margin ] ), pzarc_redux_borders( $prefix . $thisSection . '_blueprint-title' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-title' . $border ] ), array(
              'id'     => $prefix . 'blueprint-end-section-blueprint-title',
              'type'   => 'section',
              'indent' => FALSE,
          ), array(
              'title'    => __( 'Blueprint footer', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-section-blueprint-footer-css',
              'type'     => 'section',
              'indent'   => TRUE,
              'subtitle' => 'Class: .pzarc-blueprint-footer',
          ), pzarc_redux_font( $prefix . $thisSection . '_blueprint-footer' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $font ] ), pzarc_redux_bg( $prefix . $thisSection . '_blueprint-footer' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $background ] ), pzarc_redux_padding( $prefix . $thisSection . '_blueprint-footer' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $padding ] ), pzarc_redux_margin( $prefix . $thisSection . '_blueprint-footer' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $margin ] ), pzarc_redux_borders( $prefix . $thisSection . '_blueprint-footer' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $border ] ), pzarc_redux_links( $prefix . $thisSection . '_blueprint-footer' . $link, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_blueprint-footer' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_blueprint-footer' . $link ] ), array(
              'id'     => $prefix . 'blueprint-end-section-blueprint-footer',
              'type'   => 'section',
              'indent' => FALSE,
          ) ),
      );

      $thisSection               = 'page';
      $sections[_amb_styling_page] = array(
          'title'      => 'Page styling',
          'show_title' => FALSE,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-file',
          'fields'     => pzarc_fields( array(
              'title'    => __( 'Page title', 'pzarchitect' ),
              'id'       => $prefix . 'blueprint-section-page-title',
              'type'     => 'section',
              'indent'   => TRUE,
              'class'    => 'heading',
              'subtitle' => 'Class: .pzarc-page-title',
          ), pzarc_redux_font( $prefix . $thisSection . '_page-title' . $font, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $font ] ), pzarc_redux_bg( $prefix . $thisSection . '_page-title' . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $background ] ), pzarc_redux_padding( $prefix . $thisSection . '_page-title' . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $padding ] ), pzarc_redux_margin( $prefix . $thisSection . '_page-title' . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $margin ] ), pzarc_redux_borders( $prefix . $thisSection . '_page-title' . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '_page-title' . '-selectors' ], $defaults[ $optprefix . $thisSection . '_page-title' . $border ] ), array(
              'id'     => $prefix . 'blueprint-end-section-page-title',
              'type'   => 'section',
              'indent' => FALSE,
          ) ),
      );


      $thisSection                           = 'sections';
      $sections[_amb_styling_sections_wrapper] = array(
          'title'      => 'Panels wrapper styling',
          'show_title' => FALSE,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-check-empty',
          'desc'       => 'Class: .pzarc-sections_{shortname}',
          'fields'     => pzarc_fields(

          // TODO: Add shadows
          //$prefix . 'hentry' . $background, $_architect[ 'architect_config_hentry-selectors' ], $defaults[ $optprefix . 'hentry' . $background ]
              pzarc_redux_bg( $prefix . $thisSection . $background, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $background ] ), pzarc_redux_padding( $prefix . $thisSection . $padding, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $padding ] ), pzarc_redux_margin( $prefix . $thisSection . $margin, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $margin ] ), pzarc_redux_borders( $prefix . $thisSection . $border, $this->defaults ? '' : $_architect[ 'architect_config_' . $thisSection . '-selectors' ], $defaults[ $optprefix . $thisSection . $border ] ) ),
      );

      /**
       * CUSTOM CSS
       */
      $sections[_amb_bp_custom_css] = array(
          'id'         => 'bp-custom-css',
          'title'      => __( 'Custom CSS', 'pzarchitect' ),
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-wrench',
          'fields'     => array(
              array(
                  'id'       => $prefix . 'blueprint-custom-css',
                  'type'     => 'ace_editor',
                  'title'    => __( 'Custom CSS', 'pzarchitect' ),
                  'mode'     => 'css',
                  'options'  => array( 'minLines' => 25 ),
                  'subtitle' => __( 'As a shorthand, you can prefix your CSS class with MYBLUEPRINT and Architect will substitute the correct class for this Blueprint. e.g. MYBLUEPRINT {border-radius:5px;}', 'pzarchitect' ),
              ),
          ),
      );
    }
//      $file_contents          = file_get_contents(PZARC_DOCUMENTATION_PATH . PZARC_LANGUAGE . '/using-blueprints.md');
    $file_contents = '';
    if ( is_admin() && function_exists( 'curl_init' ) ) {
      $ch = curl_init( PZARC_DOCUMENTATION_URL . PZARC_LANGUAGE . '/using-blueprints.md' );
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
      $file_contents = curl_exec( $ch );
      curl_close( $ch );
    }
    $sections[_amb_blueprint_help] = array(
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(
            array(
                'title'    => __( 'Enabling styling tabs', 'pzarchitect' ),
                'id'       => $prefix . 'help-usingbp-styling-tabs',
                'type'     => 'raw',
                'markdown' => FALSE,
                'content'  => __( 'If you are using <strong>Headway</strong> or<strong>Blox</strong>, the Architect styling tabs are off by default. They can be enabled in Architect > Options > Use Architect Styling. Styling applied in the Headway/Blox Visual Editor will still be used, but the Architect styling will take precedence.', 'pzarchitect' ),
            ),
            array(
                'title'    => __( 'Enabling animation tab', 'pzarchitect' ),
                'id'       => $prefix . 'help-usingbp-animation-tabs',
                'type'     => 'raw',
                'markdown' => FALSE,
                'content'  => __( 'Animation is off by default. It can be enabled in Architect > Animation > Enable Animation', 'pzarchitect' ),
            ),

            array(
                'title'    => __( 'Displaying Blueprints', 'pzarchitect' ),
                'id'       => $prefix . 'help-blueprints',
                'type'     => 'raw',
                'class'    => 'plain',
                'markdown' => TRUE,
                'content'  => ( $defaults_only ? '' : $file_contents ),
                'pzarchitect',
            ),
            array(
                'title'    => __( 'Blueprint styling', 'pzarchitect' ),
                'id'       => $prefix . 'help',
                'type'     => 'raw',
                'markdown' => FALSE,
                //  'class' => 'plain',
                'content'  => '<h3>Adding underlines to hover links</h3>
                            <p>In the Custom CSS field, enter the following CSS</p>
                            <p>.pzarc-blueprint_SHORTNAME a:hover {text-decoration:underline;}</p>
                            <p>SHORTNAME = the short name you entered for this blueprint</p>
                            <h3>Make pager appear outside of panels</h3>
                            <p>If you want the pager to appear outside of the panels instead of over them, set a the sections width less than 100%.</p>
                            ',
            ),
            array(
                'title'    => __( 'Online documentation', 'pzarchitect' ),
                'id'       => $prefix . 'help-content-online-docs',
                'type'     => 'raw',
                'markdown' => FALSE,
                'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>' . __( 'Architect Online Documentation', 'pzarchitect' ) . '</a><br>' . __( 'This is a growing resource. Please check back regularly.', 'pzarchitect' ),

            ),
        ),
    );

    $metaboxes[] = array(
        'id'         => 'layout-settings',
        'title'      => 'Blueprint Design and stylings: Choose and setup the overall design and stylings',
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => FALSE,

    );

    return $metaboxes;
  }

  }

  new arc_mbBlueprintDesign();