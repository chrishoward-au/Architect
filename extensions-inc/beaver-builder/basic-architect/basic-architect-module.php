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
                              'name'          => __('Architect', 'pzarchitect'),
                              'description'   => __('Display an Architect Blueprint.', 'pzarchitect'),
                              'category'      => __('Advanced Modules', 'pzarchitect'),
                              'dir'           => FL_MODULE_ARCHITECT_DIR . 'basic-architect/',
                              'url'           => FL_MODULE_ARCHITECT_URL . 'basic-architect/',
                              'editor_export' => true, // Defaults to true and can be omitted.
                              'enabled'       => true, // Defaults to true and can be omitted.
                          ));

    }
  }

  $blueprint_list = pzarc_get_posts_in_post_type('arc-blueprints', true, true);

  $blueprint_list = array_merge(array('none' => 'None'), $blueprint_list, array('show-none' => 'DO NOT SHOW ANY BLUEPRINT'));
  $blank_array    = array('none' => '');
  $taxonomy_list  = pzarc_get_taxonomies(true);
//  var_dump($taxonomy_list);

  /**
   * Register the module and its form settings.
   */
  FLBuilder::register_module('FLBasicExampleModule',
                             array(
                                 'general' =>
                                     array( // Tab
                                            'title'    => __('Blueprints', 'pzarchitect'),  // Tab title
                                            'sections' =>
                                                array( // Tab Sections
                                                       'general' =>
                                                           array( // Section
                                                                  'title'  => __('Select Blueprint(s)', 'pzarchitect'),
                                                                  'fields' =>
                                                                      array(
                                                                          'blueprint_default' => array(
                                                                              'type'    => 'select',
                                                                              'label'   => __('Blueprint (default)', 'pzarchitect'),
                                                                              'default' => 'single',
                                                                              'options' => $blueprint_list
                                                                          ),
                                                                          'blueprint_tablet'  => array(
                                                                              'type'    => 'select',
                                                                              'label'   => __('Blueprint (tablet)', 'pzarchitect'),
                                                                              'default' => '',
                                                                              'options' => $blueprint_list
                                                                          ),
                                                                          'blueprint_phone'   => array(
                                                                              'type'    => 'select',
                                                                              'label'   => __('Blueprint (phone)', 'pzarchitect'),
                                                                              'default' => '',
                                                                              'options' => $blueprint_list
                                                                          ),
                                                                      ),
                                                           ),
                                                ),
                                     ),

                                 'overrides' =>
                                     array( // Tab
                                            'title'    => __('Overrides', 'pzarchitect'),  // Tab title
                                            'sections' =>
                                                array( // Tab Sections
                                                       'overrides' =>
                                                           array( // Section
                                                                  'title'  => __('Set optional overrides', 'pzarchitect'),
                                                                  'fields' =>
                                                                      array(
                                                                          'override_ids'   => array(
                                                                              'type'    => 'text',
                                                                              'label'   => __('Specific IDs', 'fl-builder'),
                                                                              'default' => '',
                                                                          ),
                                                                          'override_tax'   => array(
                                                                              'type'    => 'select',
                                                                              'label'   => __('Taxonomy', 'fl-builder'),
                                                                              'default' => '',
                                                                              'options'=> $taxonomy_list
                                                                          ),
                                                                          'override_terms' => array(
                                                                              'type'    => 'text',
                                                                              'label'   => __('Terms', 'fl-builder'),
                                                                              'default' => '',
                                                                          ),
                                                                      ),
                                                           )
                                                )
                                     )
                             )
  );