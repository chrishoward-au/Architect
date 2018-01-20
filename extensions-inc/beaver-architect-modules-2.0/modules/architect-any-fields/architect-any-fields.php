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
              'field'      => array(
                'type'        => 'textarea',
                'rows'        => '3',
                'label'       => __( 'Field', 'pzarchitect' ),
                'placeholder' => __( 'Select a field or fields', 'pzarchitect' ),
                'preview'     => array(
                  'type' => 'refresh',
                ),
                'connections' => array( 'custom_field' ),
              ),
              'field-type' => array(
                'label'   => __( 'Field type', 'pzarchitect' ),
                'type'    => 'select',
                'default' => 'text',
                'options' => array(
                  'text'            => __( 'Text', 'pzarchitect' ),
                  'image'           => __( 'Image', 'pzarchitect' ),
                  'date'            => __( 'Date', 'pzarchitect' ),
                  'number'          => __( 'Number', 'pzarchitect' ),
                  'embed'           => __( 'Embed URL', 'pzarchitect' ),
                  'group'           => __( 'Multi select/checkboxes', 'pzarchitect' ), // WTF is a group??
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
                  'text'  => array(
                    'sections' => array( 'texts' ),
                  ),
                ),
              ),
              'name'       => array(
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
          'dates'    => array(
            // Section
            'title'  => 'Dates',
            'fields' => array(

              'date-format' => array(
                'label'       => __( 'Date format', 'pzarchitect' ),
                'type'        => 'text',
                'size'        => 10,
                'default'     => 'l, F j, Y g:i a',
                'description' => __( '<br><a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>Visit here for information on formatting date and time</a>', 'pzarchitect' ),
              ),
            ),
          ),
          'groups'   => array(
            // Section
            'title'  => 'Multi',
            'fields' => array(

              'group-joiner' => array(
                'label'   => __( 'Joiner', 'pzarchitect' ),
                'type'    => 'select',
                'default' => 'linebreak',
                'options' => array(
                  'linebreak' => __('Line break','pzarchitect'),
                  'comma' => __('Comma','pzarchitect')
                ),
              ),
            ),
          ),
          'texts'   => array(
            // Section
            'title'  => 'Text',
            'fields' => array(

              'text-paras' => array(
                'label'   => __( 'Add paragraph breaks', 'pzarchitect' ),
                'type'    => 'select',
                'default' => 'no',
                'options' => array(
                  'no' => __('No','pzarchitect'),
                  'yes' => __('Yes','pzarchitect')
                ),
              ),
            ),
          ),
          'embeds'   => array(
            // Section
            'title'  => 'Embed URLs',
            'fields' => array(

              'embed-width'  => array(
                'label'       => __( 'Width', 'pzarchitect' ),
                'type'        => 'unit',
                'default'     => '',
                'description' => 'px',
              ),
              'embed-height' => array(
                'label'       => __( 'Height', 'pzarchitect' ),
                'type'        => 'unit',
                'default'     => '',
                'description' => 'px',
              ),
            ),
          ),
          'numbers'  => array(
            // Section
            'title'  => 'Numbers',
            'fields' => array(
              'number-decimals'            => array(
                'label' => __( 'Decimals places', 'pzarchitect' ),
                'type'  => 'unit',
              ),
              'number-decimal-char'        => array(
                'label'   => __( 'Decimal point character', 'pzarchitect' ),
                'type'    => 'text',
                'size'    => 1,
                'default' => '.',
              ),
              'number-thousands-separator' => array(
                'label'   => __( 'Thousands separator', 'pzarchitect' ),
                'type'    => 'text',
                'size'    => 1,
                'default' => ',',
              ),
            ),
          ),
          'links'    => array(
            // Section
            'title'  => 'Links',
            'fields' => array(

              'link-field'     => array(
                'label'       => __( 'Link field', 'pzarchitect' ),
                'type'        => 'textarea',
                'rows'        => 3,
                'default'     => '',
                'connections' => array( 'custom_field' ),
                'placeholder' => 'Select a field that contains URLs you want to use as the link',
              ),
              'link-behaviour' => array(
                'label'   => __( 'Open link in', 'pzarchitect' ),
                'type'    => 'select',
                'default' => '_self',
                'options' => array(
                  '_self'  => __( 'Same tab', 'pzarchitect' ),
                  '_blank' => __( 'New tab', 'pzarchitect' ),
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