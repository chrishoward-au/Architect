<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 13/2/18
   * Time: 8:58 PM
   */

class arc_mbCustomFields extends arc_Blueprints_Designer {
  function __construct( $defaults = FALSE ) {
    parent::__construct( $defaults );
    add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'mb_customfields', ), 10, 1 );
  }

  function mb_customfields( $metaboxes, $defaults_only = FALSE ) {
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
    /**
     * CUSTOM FIELDS
     * Why are these here even though they are somewhat content related. They're not choosing the content itself. Yes they do limit the usablity of the panel. Partly this came about because of the way WPdoesn't bind custom fields to specific content types.
     */
    $sections[] = array(
        'title'      => __( 'Custom fields general settings', 'pzarchitect' ),
        'show_title' => FALSE,
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-adjust-alt',
        'fields'     => array(
            array(
                'title'         => __( 'Number of custom fields', 'pzarchitect' ),
                'id'            => $prefix . 'custom-fields-count',
                'type'          => 'spinner',
                'default'       => 0,
                'min'           => 0,
                'max'           => 999,
                'step'          => 1,
                'display_value' => 'label',
                'required'      => array(
                    $prefix . 'components-to-show',
                    'contains',
                    'custom',
                ),
                'subtitle'      => __( 'Each of the three Custom groups can have multiple custom fields. Enter the <strong>total</strong> number of custom fields, click Publish/Update', 'pzarchitect' ),
                'desc'          => __( 'When you change this number, click Publish/Update to update Custom Fields tabs at left', 'pzarchitect' ),
                'hint'          => array(
                    'title'   => __( 'Number of custom fields', 'pzarchitect' ),
                    'content' => __( 'After selecting upto three custom field groups, you now need to set the total number of custom fields you will be displaying so Architect can create the settings tabs for each one.<br><br><strong>You will need to Publish/Update to see those new tabs.</strong>', 'pzarchitect' ),
                ),
            ),
        ),
    );

    if ( is_admin() && ! empty( $_GET['post'] ) ) {

      $cfcount = ( ! empty( $this->postmeta['_panels_design_custom-fields-count'][0] ) ? $this->postmeta['_panels_design_custom-fields-count'][0] : 0 );


      if ( $cfcount ) {

        $pzarc_custom_fields = array_merge( array(
            'use_empty'     => 'No field. Use prefix and suffix only',
            'post_title'    => 'Post Title',
            'specific_code' => 'Specific Text, HTML or Shortcodes',
        ), apply_filters( 'arc_custom_field_list', $this->custom_fields, $this->source ) );

        for ( $i = 1; $i <= $cfcount; $i ++ ) {
//            if (! empty( $this->postmeta[ '_panels_design_cfield-' . $i . '-name' ][0]) && $this->postmeta[ '_panels_design_cfield-' . $i . '-name' ][0]==='tablefield') {
//              $cfname     = ' custom field ' . $i . ( ! empty( $this->postmeta[ '_panels_design_cfield-' . $i . '-name-table-field' ][0] ) ? ': <br>' . $this->postmeta[ '_panels_design_cfield-' . $i . '-name-table-field' ][0] : '' );
//            } else {
          $cfname = ' custom field ' . $i . ( ! empty( $this->postmeta[ '_panels_design_cfield-' . $i . '-name' ][0] ) ? ': <br>' . $this->postmeta[ '_panels_design_cfield-' . $i . '-name' ][0] : '' );
//            }

//            $cftable = ( ! empty( $this->postmeta['_panels_design_cfield-'.$i.'-name-table'][0] ) ? $this->postmeta['_panels_design_cfield-'.$i.'-name-table'][0] : 'none' );
//            $cftablefields = $cftable=='none' ? array('none'=>__('Select a table','pzarchitect')):$this->tablesfields[$cftable];

          $link_fields = array_merge( array( 'Custom fields' => $this->custom_fields ), $this->tablesfields );
          $all_fields  = array_merge( $pzarc_custom_fields, $this->tablesfields );
          $sections[]  = array(
              'title'      => 'Settings ' . $cfname,
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-adjust-alt',
              'desc'       => __( 'Note: Only fields with content will show on the Blueprint.', 'pzarchitect' ),
              'fields'     => array(
                  array(
                      'title'   => __( 'Show in custom field group', 'pzarchitect' ),
                      'id'      => $prefix . 'cfield-' . $i . '-group',
                      'type'    => 'button_set',
                      'default' => 'custom1',
                      'options' => array(
                          'custom1' => __( 'Custom 1', 'pzarchitect' ),
                          'custom2' => __( 'Custom 2', 'pzarchitect' ),
                          'custom3' => __( 'Custom 3', 'pzarchitect' ),
                      ),
                  ),
                  array(
                      'title'    => __( 'Field name or content source', 'pzarchitect' ),
                      'id'       => $prefix . 'cfield-' . $i . '-name',
                      'type'     => 'select',
                      'default'  => '',
                      //                lightbox     => 'callback',
                      //                'args'     => array( 'pzarc_get_custom_fields' ),
                      'options'  => $all_fields,
                      'subtitle' => __( 'If a custom field is not shown in the dropdown, it is either because it has no data yet or the custom field list cache needs clearing. Go to Architect > Tools and clear the caches.', 'pzarchitect' ),
                      'desc'     => __( 'Type to search names. List includes custom fields and <strong>any field from any table</strong><br>Note: Fields must be available to the viewed post type to show content.', 'pzarchitect' ),
                  ),
                  //                array(
                  //                  'title'    => __( 'Select a table', 'pzarchitect' ),
                  //                  'id'       => $prefix . 'cfield-' . $i . '-name-table',
                  //                  'type'     => 'select',
                  //                  'default'  => 'none',
                  //                  'options'  => $this->tableset,
                  //                  'required' => array(
                  //                    $prefix . 'cfield-' . $i . '-name',
                  //                    '=',
                  //                    'tablefield',
                  //                  ),
                  //
                  //                ),
                  //                array(
                  //                  'title'    => __( 'Choose field from table', 'pzarchitect' ),
                  //                  'id'       => $prefix . 'cfield-' . $i . '-name-table-field',
                  //                  'type'     => 'select',
                  //                  'default'  => 'none',
                  //                  'options'  => $table_fields,
                  //                  'required' => array(
                  //                    $prefix . 'cfield-' . $i . '-name',
                  //                    '=',
                  //                    'tablefield',
                  //                  ),
                  //
                  //                ),
                  array(
                      'title'   => __( 'Field type', 'pzarchitect' ),
                      'id'      => $prefix . 'cfield-' . $i . '-field-type',
                      'type'    => 'button_set',
                      'default' => 'text',
                      'options' => array(
                          'text'            => __( 'Text', 'pzarchitect' ),
                          'text-with-paras' => __( 'Text with paragraph breaks', 'pzarchitect' ),
                          'image'           => __( 'Image', 'pzarchitect' ),
                          'date'            => __( 'Date', 'pzarchitect' ),
                          'number'          => __( 'Number', 'pzarchitect' ),
                          'embed'           => __( 'Embed URL', 'pzarchitect' ),
                          'group'           => __( 'Group', 'pzarchitect' ), // WTF is a group
                          //'acf-repeater' => _('ACF Repeater'pzarchitect),
                      ),
                      //                        'required' => array(
                      //                            $prefix . 'cfield-' . $i . '-name',
                      //                            '!=',
                      //                            'specific_code',
                      //                        ),

                  ),
                  array(
                      'title'    => __( 'Specific Text or Code', 'pzarchitect' ),
                      'id'       => $prefix . 'cfield-' . $i . '-code',
                      'type'     => 'textarea',
                      'default'  => '',
                      'subtitle' => __( 'This can only include basic HTML (br, p, a, strong, em, ul, ol, li, pre, code, blockquote, h1 to h6) or shortcodes. PHP and scripts will be stripped out. Create and use a shortcode if you need to use PHP.', 'pzarchitect' ),
                      'required' => array(
                          $prefix . 'cfield-' . $i . '-name',
                          '=',
                          'specific_code',
                      ),
                  ),
                  array(
                      'id'       => $prefix . 'cfield-' . $i . '-date-format',
                      'title'    => __( 'Date format', 'pzarchitect' ),
                      'type'     => 'text',
                      'default'  => 'l, F j, Y g:i a',
                      'desc'     => __( 'Visit here for information on <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>formatting date and time</a>', 'pzarchitect' ),
                      'required' => array(
                          $prefix . 'cfield-' . $i . '-field-type',
                          '=',
                          'date',
                      ),
                  ),
                  array(
                      'id'            => $prefix . 'cfield-' . $i . '-number-decimals',
                      'title'         => __( 'Decimals', 'pzarchitect' ),
                      'type'          => 'spinner',
                      'default'       => 0,
                      'min'           => 0,
                      'max'           => 100,
                      'step'          => 1,
                      'display_value' => 'label',
                      'subtitle'      => __( 'Number of decimal places.', 'pzarchitect' ),
                      'required'      => array(
                          $prefix . 'cfield-' . $i . '-field-type',
                          '=',
                          'number',
                      ),
                  ),
                  array(
                      'id'       => $prefix . 'cfield-' . $i . '-number-decimal-char',
                      'title'    => __( 'Decimal point character', 'pzarchitect' ),
                      'type'     => 'text',
                      'default'  => '.',
                      'required' => array(
                          $prefix . 'cfield-' . $i . '-field-type',
                          '=',
                          'number',
                      ),
                  ),
                  array(
                      'id'       => $prefix . 'cfield-' . $i . '-number-thousands-separator',
                      'title'    => __( 'Thousands separator', 'pzarchitect' ),
                      'type'     => 'text',
                      'default'  => ',',
                      'required' => array(
                          $prefix . 'cfield-' . $i . '-field-type',
                          '=',
                          'number',
                      ),
                  ),
                  array(
                      'title'    => __( 'Wrapper tag', 'pzarchitect' ),
                      'id'       => $prefix . 'cfield-' . $i . '-wrapper-tag',
                      'type'     => 'select',
                      'default'  => 'div',
                      'options'  => array(
                          'div'  => 'div',
                          'p'    => 'p',
                          'span' => 'span',
                          'h1'   => 'h1',
                          'h2'   => 'h2',
                          'h3'   => 'h3',
                          'h4'   => 'h4',
                          'h5'   => 'h5',
                          'h6'   => 'h6',
                      ),
                      'subtitle' => __( 'Select the wrapper element for this custom field', 'pzarchitect' ),

                  ),
                  array(
                      'title'   => __( 'Shortcodes in text fields', 'pzarchitect' ),
                      'id'      => $prefix . 'process-custom-field-shortcodes',
                      'type'    => 'button_set',
                      'options' => array(
                          'process' => __( 'Process', 'pzarchitect' ),
                          'remove'  => __( 'Remove', 'pzarchitect' ),
                      ),
                      'default' => 'process',

                  ),

                  // THis wasn't being added, plus we know the name of the field
                  //              array(
                  //                'id'    => $prefix . 'cfield-' . $i . '-class-name',
                  //                'title' => __( 'Add class name', 'pzarchitect' ),
                  //                'type'  => 'text',
                  //              ),
                  array(
                      'title'    => __( 'Link field', 'pzarchitect' ),
                      'id'       => $prefix . 'cfield-' . $i . '-link-field',
                      'type'     => 'select',
                      'default'  => '',
                      //                'data'     => 'callback',
                      //                'args'     => array( 'pzarc_get_custom_fields' ),
                      'options'  => $link_fields,
                      'subtitle' => 'Select a custom field that contains URLs you want to use as the link',
                  ),
                  array(
                      'title'   => __( 'Open link in', 'pzarchitect' ),
                      'id'      => $prefix . 'cfield-' . $i . '-link-behaviour',
                      'type'    => 'button_set',
                      'default' => '_self',
                      'options' => array(
                          '_self'  => __( 'Same tab', 'pzarchitect' ),
                          '_blank' => __( 'New tab', 'pzarchitect' ),
                      ),
                  ),
                  array(
                      'title'   => __( 'Prefix text', 'pzarchitect' ),
                      'id'      => $prefix . 'cfield-' . $i . '-prefix-text',
                      'type'    => 'text',
                      'default' => '',
                  ),
                  array(
                      'title'   => __( 'Prefix image', 'pzarchitect' ),
                      'id'      => $prefix . 'cfield-' . $i . '-prefix-image',
                      'type'    => 'media',
                      'default' => '',
                  ),
                  array(
                      'title'   => __( 'Suffix text', 'pzarchitect' ),
                      'id'      => $prefix . 'cfield-' . $i . '-suffix-text',
                      'type'    => 'text',
                      'default' => '',
                  ),
                  array(
                      'title'   => __( 'Suffix image', 'pzarchitect' ),
                      'id'      => $prefix . 'cfield-' . $i . '-suffix-image',
                      'type'    => 'media',
                      'default' => '',
                  ),
                  array(
                      'title'   => __( 'Prefix/suffix images width', 'pzarchitect' ),
                      'id'      => $prefix . 'cfield-' . $i . '-ps-images-width',
                      'type'    => 'dimensions',
                      'height'  => FALSE,
                      'default' => array( 'width' => '32px' ),
                      'units'   => 'px',
                  ),
                  array(
                      'title'   => __( 'Prefix/suffix images height', 'pzarchitect' ),
                      'id'      => $prefix . 'cfield-' . $i . '-ps-images-height',
                      'type'    => 'dimensions',
                      'width'   => FALSE,
                      'default' => array( 'height' => '32px' ),
                      'units'   => 'px',
                  ),
                  //                  array(
                  //                      'title'   => __('Prefix/suffix inside link', 'pzarchitect'),
                  //                      'id'      => $prefix . 'cfield-' . $i . '-ps-in-link',
                  //                      'type'    => 'button_set',
                  //                      'multi'   => true,
                  //                      'options' => array('prefix' => 'Prefix', 'suffix' => 'Suffix'),
                  //                      'required'=>array($prefix . 'cfield-' . $i . '-link-field','not_empty_and',null)
                  //                  ),
              ),
          );
        }
      }
    }

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
      if ( ! empty( $_GET['post'] ) ) {
        $cfcount = ( ! empty( $this->postmeta['_panels_design_custom-fields-count'][0] ) ? $this->postmeta['_panels_design_custom-fields-count'][0] : 0 );
        for ( $i = 1; $i <= $cfcount; $i ++ ) {
//            if (! empty( $this->postmeta[ '_panels_design_cfield-' . $i . '-name' ][0]) && $this->postmeta[ '_panels_design_cfield-' . $i . '-name' ][0]==='tablefield') {
//              $cfname     = ' custom field ' . $i . ( ! empty( $this->postmeta[ '_panels_design_cfield-' . $i . '-name-table-field' ][0] ) ? ': <br>' . $this->postmeta[ '_panels_design_cfield-' . $i . '-name-table-field' ][0] : '' );
//            } else {
          $cfname = ' custom field ' . $i . ( ! empty( $this->postmeta[ '_panels_design_cfield-' . $i . '-name' ][0] ) ? ': <br>' . $this->postmeta[ '_panels_design_cfield-' . $i . '-name' ][0] : '' );
//            }
          $sections[] = array(
              'title'      => 'Styling ' . $cfname,
              'show_title' => FALSE,
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-brush',
              'desc'       => 'Class: .entry-customfield-' . $i,
              'fields'     => pzarc_fields( pzarc_redux_font( $prefix . 'entry-customfield-' . $i . '' . $font, array( '.entry-customfield-' . $i . '' ) ), pzarc_redux_bg( $prefix . 'entry-customfield-' . $i . '' . $font . $background, array( '.entry-customfield-' . $i . '' ) ), pzarc_redux_padding( $prefix . 'entry-customfield-' . $i . '' . $font . $padding, array( '.entry-customfield-' . $i . '' ) ), pzarc_redux_margin( $prefix . 'entry-customfield-' . $i . '' . $font . $margin, array( '.entry-customfield-' . $i . '' ), NULL, 'tb' ), pzarc_redux_borders( $prefix . 'entry-customfield-' . $i . '' . $border, array( '.entry-customfield-' . $i . '' ) ), pzarc_redux_links( $prefix . 'entry-customfield-' . $i . '' . $font . $link, array( '.entry-customfield-' . $i . ' a' ) ) ),
          );
        }
      }
    }
    $metaboxes[] = array(
        'id'         => 'customfields-settings',
        'title'      => __( 'Custom fields settings and stylings.', 'pzarchitect' ),
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'default',
        'sidebar'    => FALSE,

    );

    return $metaboxes;

  }
  }
  new arc_mbCustomFields();