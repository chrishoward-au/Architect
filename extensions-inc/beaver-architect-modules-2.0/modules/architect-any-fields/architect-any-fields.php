<?php

  /**
   * This is an example module with only the basic
   * setup necessary to get it working.
   *
   * @class FLBasicExampleModule
   */
  class FLArchitectAnyFieldsModule extends FLBuilderModule {

    /**
     * Constructor function for the module. You must pass the
     * name, description, dir and url in an array to the parent class.
     *
     * @method __construct
     */
    public function __construct() {
      parent::__construct( array(
        'name'          => __( 'Any fields', 'fl-builder' ),
        'description'   => __( 'Display any custom fields and any fields from any tables.', 'fl-builder' ),
        'category'      => __( 'Architect Modules', 'fl-builder' ),
        'dir'           => FL_ARCHITECT_BB2_MODULE_DIR . 'modules/architect-any-fields/',
        'url'           => FL_ARCHITECT_BB2_MODULE_URL . 'modules/architect-any-fields/',
        'editor_export' => TRUE, // Defaults to true and can be omitted.
        'enabled'       => TRUE, // Defaults to true and can be omitted.
        'icon'          => 'editor-table.svg',

      ) );


    }
  }

  /**
   * Register the module and its form settings.
   */
  FLBuilder::register_module( 'FLArchitectAnyFieldsModule', array(
      'settings' => array(
        // Tab
        'title'    => __( 'Settings', 'fl-builder' ),
        // Tab title
        'sections' => array(
          // Tab Sections
          'general'  => array(
            // Section
            'title'  => 'General',
            'fields' => array(
              // Section Fields
              'fieldset' => array(
                //                 this is easier but doesn't allow for multiple movable selections. :/
                'type'         => 'form',
                'label'        => __( 'Field', 'pzarchitect' ),
                'placeholder'  => __( 'Select fields to show', 'pzarchitect' ),
                'multiple'     => TRUE,
                'form'         => 'arc_fieldset',
                'preview_text' => 'arc_fieldname',
                'preview'      => array(
                  'type' => 'refresh',
                ),
              ),

              //                              'field'      => array(
              //                'type'        => 'textarea',
              //                'rows'        => '3',
              //                'label'       => __( 'Field', 'pzarchitect' ),
              //                'placeholder' => __( 'Select a field or fields', 'pzarchitect' ),
              //                'preview'     => array(
              //                  'type' => 'refresh',
              //                ),
              //                'connections' => array( 'custom_field' ),
              //              ),
              'name'     => array(
                'label'       => __( 'Name', 'pzarchitect' ),
                'type'        => 'text',
                'default'     => '',
                'description' => __( 'Used to add a unique class to the field HTML for when you have multiple Any Fields modules on the same page', 'pzarchitect' ),
              ),

              'html-tag'           => array(
                'type'    => 'select',
                'label'   => __( 'Wrap field in HTML tag', 'fl-builder' ),
                'default' => 'div',
                'options' => array(
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
              ),
              'process-shortcodes' => array(
                'label'   => __( 'Shortcodes in text fields', 'pzarchitect' ),
                'type'    => 'select',
                'options' => array(
                  'process' => __( 'Process', 'pzarchitect' ),
                  'remove'  => __( 'Remove', 'pzarchitect' ),
                ),
                'default' => 'process',

              ),

            ),
          ),
          'links'    => array(
            // Section
            'title'  => 'Links',
            'fields' => array(

              'link-field'     => array(
                'label'       => __( 'Link field', 'pzarchitect' ),
                'type'        => 'select',
                'rows'        => 3,
                'default'     => '',
                'options'     => 'ArcFun::get_all_table_fields_flat',
                //                'connections' => array( 'custom_field' ),
                'placeholder' => 'Select a field that contains URL or email address you want to use as the link',
              ),
              'link-behaviour' => array(
                'label'   => __( 'Open link in', 'pzarchitect' ),
                'type'    => 'select',
                'default' => '_blank',
                'options' => array(
                  '_self'  => __( 'Same tab', 'pzarchitect' ),
                  '_blank' => __( 'New tab', 'pzarchitect' ),
                  'email' => __( 'Email', 'pzarchitect' ),
                ),
              ),
            ),
          ),
          'prefixes' => array(
            // Section
            'title'  => 'Prefixes & Suffixes',
            'fields' => array(

              'prefix-text'      => array(
                'label'   => __( 'Prefix text', 'pzarchitect' ),
                'type'    => 'text',
                'default' => '',
              ),
              'prefix-image'     => array(
                'label'   => __( 'Prefix image', 'pzarchitect' ),
                'type'    => 'photo',
                'default' => '',
              ),
              'suffix-text'      => array(
                'label'   => __( 'Suffix text', 'pzarchitect' ),
                'type'    => 'text',
                'default' => '',
              ),
              'suffix-image'     => array(
                'label'   => __( 'Suffix image', 'pzarchitect' ),
                'type'    => 'photo',
                'default' => '',
              ),
              'ps-images-width'  => array(
                'label'       => __( 'Prefix/suffix images width', 'pzarchitect' ),
                'type'        => 'unit',
                'default'     => 32,
                'description' => 'px',
              ),
              'ps-images-height' => array(
                'label'       => __( 'Prefix/suffix images height', 'pzarchitect' ),
                'type'        => 'unit',
                'default'     => 32,
                'description' => 'px',
              ),

            ),
          ),
        ),
      ),
      'styling'  => array(
        'title'    => __( 'Styling', 'pzarchitect' ),
        'sections' => array(
          'styles_toggle' => array(
            'fields' => array(

              '.arcaf-anyfield'                   => array(
                'type'         => 'form',
                'form'         => 'form_styles_editor',
                'preview_text' => 'enable_styling',
                'label'        => 'Field wrapper',
                'help'         => '.arcaf-anyfield',
              ),
              '.arcaf-anyfield a'                 => array(
                'type'         => 'form',
                'form'         => 'form_styles_editor',
                'preview_text' => 'enable_styling',
                'label'        => 'Field link',
                'help'         => '.arcaf-anyfield a',
              ),
              '.arcaf-anyfield a:hover'           => array(
                'type'         => 'form',
                'form'         => 'form_styles_editor',
                'preview_text' => 'enable_styling',
                'label'        => 'Field link hover',
                'help'         => '.arcaf-anyfield a:hover',
              ),
              '.arcaf-prefix-text'                => array(
                'type'         => 'form',
                'form'         => 'form_styles_editor',
                'preview_text' => 'enable_styling',
                'label'        => 'Prefix text',
                'help'         => '.arcaf-prefix-text',
              ),
              '.arcaf-suffix-text'                => array(
                'type'         => 'form',
                'form'         => 'form_styles_editor',
                'preview_text' => 'enable_styling',
                'label'        => 'Suffix text',
                'help'         => '.arcaf-suffix-text',
              ),
              '.arcaf-presuff-image.prefix-image' => array(
                'type'         => 'form',
                'form'         => 'form_styles_editor',
                'preview_text' => 'enable_styling',
                'label'        => 'Prefix image',
                'help'         => '.arcaf-presuff-image.prefix-image',
              ),
              '.arcaf-presuff-image.suffix-image' => array(
                'type'         => 'form',
                'form'         => 'form_styles_editor',
                'preview_text' => 'enable_styling',
                'label'        => 'Suffix image',
                'help'         => '.arcaf-presuff-image.suffix-image',
              ),

            ),
          ),
        ),
      ),
    )

  );

  FLBuilder::register_settings_form( 'arc_fieldset', array(
    'title' => __( 'Fields', 'fl-builder' ),
    'tabs'  => array(
      'general' => array(
        'title'    => __( 'Fields', 'fl-builder' ),
        'sections' => array(
          'general' => array(
            'title'  => '',
            'fields' => array(

              'arc_fieldbefore' => array(
                'type'        => 'text',
                'label'       => __( 'Before', 'fl-builder' ),
                'placeholder' => __( 'Text to show before this field', 'pzarchitect' ),
                'description'=>__('Allowed HTML tags: '.esc_html('<br><p><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6>'),'pzarchitect')
              ),
              'arc_fieldname'   => array(
                'type'    => 'select',
                'label'   => __( 'Field', 'fl-builder' ),
                'options' => 'ArcFun::get_all_table_fields_flat',
              ),
              'arc_fieldafter'  => array(
                'type'        => 'text',
                'label'       => __( 'After', 'fl-builder' ),
                'placeholder' => __( 'Text to show after this field', 'pzarchitect' ),
                'description'=>__('Allowed HTML tags: '.esc_html('<br><p><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6>'),'pzarchitect')
              ),
              'field_type'      => array(
                'label'   => __( 'Field type', 'pzarchitect' ),
                'type'    => 'select',
                'default' => 'text',
                'options' => array(
                  'text'   => __( 'Text', 'pzarchitect' ),
                  'image'  => __( 'Image', 'pzarchitect' ),
                  'date'   => __( 'Date', 'pzarchitect' ),
                  'number' => __( 'Number', 'pzarchitect' ),
                  'embed'  => __( 'Embed URL', 'pzarchitect' ),
                  'group'  => __( 'Multi select/checkboxes', 'pzarchitect' ), // WTF is a group??
                  //'acf-repeater' => _('ACF Repeater'pzarchitect),
                ),
                'toggle'  => array(
                  'date'   => array(
                    'sections' => array( 'dates' ),
                  ),
                  'number' => array(
                    'sections' => array( 'numbers' ),
                  ),
                  'embed'  => array(
                    'sections' => array( 'embeds' ),
                  ),
                  'group'  => array(
                    'sections' => array( 'groups' ),
                  ),
                  'text'   => array(
                    'sections' => array( 'texts' ),
                  ),
                ),
              ),
              'field_creator'   => array(
                'label'       => __( 'Field creator', 'pzarchitect' ),
                'description' => __( 'If this is a custom field, please indicate how it was created', 'pzarchitect' ),
                'type'        => 'select',
                'default'     => '',
                'options'     => array(
                  ''             => __( '', 'pzarchitect' ),
                  'acf'          => __( 'Advanced Custom Fields', 'pzarchitect' ),
                  'toolsettypes' => __( 'Toolset Types', 'pzarchitect' ),
                  'unknown'      => __( 'Unknown', 'pzarchitect' ),
                ),
              ),
            ),
          ),
          'dates'   => array(
            // Section
            'title'  => 'Dates',
            'fields' => array(

              'date_format' => array(
                'label'       => __( 'Date format', 'pzarchitect' ),
                'type'        => 'text',
                'size'        => 10,
                'default'     => 'l, F j, Y g:i a',
                'description' => __( '<br><a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>Visit here for information on formatting date and time</a>', 'pzarchitect' ),
              ),
            ),
          ),
          'groups'  => array(
            // Section
            'title'  => 'Multi',
            'fields' => array(

              'group_joiner' => array(
                'label'   => __( 'Joiner', 'pzarchitect' ),
                'type'    => 'select',
                'default' => 'linebreak',
                'options' => array(
                  'linebreak' => __( 'Line break', 'pzarchitect' ),
                  'comma'     => __( 'Comma', 'pzarchitect' ),
                  'ulist'     => __( 'List', 'pzarchitect' ),
                ),
              ),
            ),
          ),
          'texts'   => array(
            // Section
            'title'  => 'Text',
            'fields' => array(

              'text_paras' => array(
                'label'   => __( 'Add paragraph breaks', 'pzarchitect' ),
                'type'    => 'select',
                'default' => 'no',
                'options' => array(
                  'no'  => __( 'No', 'pzarchitect' ),
                  'yes' => __( 'Yes', 'pzarchitect' ),
                ),
              ),
            ),
          ),
          'embeds'  => array(
            // Section
            'title'  => 'Embed URLs',
            'fields' => array(

              'embed_width'  => array(
                'label'       => __( 'Width', 'pzarchitect' ),
                'type'        => 'unit',
                'default'     => '',
                'description' => 'px',
              ),
              'embed_height' => array(
                'label'       => __( 'Height', 'pzarchitect' ),
                'type'        => 'unit',
                'default'     => '',
                'description' => 'px',
              ),
            ),
          ),
          'numbers' => array(
            // Section
            'title'  => 'Numbers',
            'fields' => array(
              'number_decimals'            => array(
                'label' => __( 'Decimals places', 'pzarchitect' ),
                'type'  => 'unit',
              ),
              'number_decimal_char'        => array(
                'label'   => __( 'Decimal point character', 'pzarchitect' ),
                'type'    => 'text',
                'size'    => 1,
                'default' => '.',
              ),
              'number_thousands_separator' => array(
                'label'   => __( 'Thousands separator', 'pzarchitect' ),
                'type'    => 'text',
                'size'    => 1,
                'default' => ',',
              ),
            ),
          ),
        ),
      ),
    ),
  ) );