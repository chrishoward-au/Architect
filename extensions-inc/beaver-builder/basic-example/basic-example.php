<?php

  /**
   * This is an example module with only the basic
   * setup necessary to get it working.
   *
   * @class FLBasicExampleModule
   */
  class FLBasicExampleModule extends FLBuilderModule
  {

    /**
     * Constructor function for the module. You must pass the
     * name, description, dir and url in an array to the parent class.
     *
     * @method __construct
     */

    public function __construct()
    {
      parent::__construct(array(
                              'name'          => __('Basic Example', 'fl-builder'),
                              'description'   => __('An basic example for coding new modules.', 'fl-builder'),
                              'category'      => __('Advanced Modules', 'fl-builder'),
                              'dir'           => FL_MODULE_EXAMPLES_DIR . 'basic-example/',
                              'url'           => FL_MODULE_EXAMPLES_URL . 'basic-example/',
                              'editor_export' => true, // Defaults to true and can be omitted.
                              'enabled'       => true, // Defaults to true and can be omitted.
                          ));

    }
  }

  $blueprint_list = pzarc_get_posts_in_post_type('arc-blueprints', false, true);

  $blueprint_list = array_merge(array('none' => 'None'), $blueprint_list, array('show-none' => 'DO NOT SHOW ANY BLUEPRINT'));
  $blank_array    = array('none' => '');
  $taxonomy_list  = pzarc_get_taxonomies(true);

  /**
   * Register the module and its form settings.
   */
  FLBuilder::register_module('FLBasicExampleModule', array(
                                                       'general' => array( // Tab
                                                                           'title'    => __('General', 'fl-builder'),
                                                                           // Tab title
                                                                           'sections' => array( // Tab Sections
                                                                                                'general' => array( // Section
                                                                                                                    'title'  => __('Blueprint settings', 'fl-builder'),
                                                                                                                    // Section Title
                                                                                                                    'fields' => array( // Section Fields
                                                                                                                                       'blueprint_field' => array(
                                                                                                                                           'type'    => 'select',
                                                                                                                                           'label'   => __('Blueprint', 'fl-builder'),
                                                                                                                                           'default' => 'bananas',
                                                                                                                                           'options' => $blueprint_list
                                                                                                                                       ),
                                                                                                                                       'select_field'    => array(
                                                                                                                                           'type'    => 'select',
                                                                                                                                           'label'   => __('Select Field', 'fl-builder'),
                                                                                                                                           'default' => 'option-2',
                                                                                                                                           'options' => array(
                                                                                                                                               'option-1' => __('Option 1', 'fl-builder'),
                                                                                                                                               'option-2' => __('Option 2', 'fl-builder'),
                                                                                                                                               'option-3' => __('Option 3', 'fl-builder')
                                                                                                                                           )
                                                                                                                                       ),
                                                                                                                                       'color_field'     => array(
                                                                                                                                           'type'       => 'color',
                                                                                                                                           'label'      => __('Color Picker', 'fl-builder'),
                                                                                                                                           'default'    => '333333',
                                                                                                                                           'show_reset' => true,
                                                                                                                                           'preview'    => array(
                                                                                                                                               'type'     => 'css',
                                                                                                                                               'selector' => '.fl-example-text',
                                                                                                                                               'property' => 'color'
                                                                                                                                           )
                                                                                                                                       ),
                                                                                                                    ),
                                                                                                )
                                                                           )
                                                       )
                                                   )
  );