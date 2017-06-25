<?php

  /**
   * This is an example module with only the basic
   * setup necessary to get it working.
   *
   * @class FLBasicExampleModule
   */
  class FLBasicExampleModule extends FLBuilderModule {

    /**
     * Constructor function for the module. You must pass the
     * name, description, dir and url in an array to the parent class.
     *
     * @method __construct
     */

    public function __construct() {
      parent::__construct(array(
          'name'            => __('Architect', 'pzarchitect'),
          'description'     => __('Display an Architect Blueprint.', 'pzarchitect'),
          'category'        => __('Architect Modules', 'pzarchitect'),
          'dir'             => FL_MODULE_ARCHITECT_DIR . 'basic-architect/',
          'url'             => FL_MODULE_ARCHITECT_URL . 'basic-architect/',
          'editor_export'   => TRUE, // Defaults to true and can be omitted.
          'enabled'         => TRUE, // Defaults to true and can be omitted.
          'partial_refresh' => TRUE// Set this to true to enable partial refresh.
      ));

// this is the sort of thing needed... but it needs to be done conditionally depending on blueprint.
//      $this->add_css('jquery-bxslider');
//      $this->add_js('jquery-bxslider');

    }
  }

  // v1.9.2 changed last param to false from true.
  $blueprint_list = pzarc_get_posts_in_post_type('arc-blueprints', TRUE, TRUE, TRUE);
//var_dump( $blueprint_list );
  $blueprint_list = array_merge(array('none' => 'None'), $blueprint_list, array('show-none' => 'DO NOT SHOW ANY BLUEPRINT'));
  $blank_array    = array('none' => '');
  $taxonomy_list  = pzarc_get_taxonomies(TRUE);

  // $styles = fl_render_styles_settings($arc_styles);

  //  var_dump($taxonomy_list);

  /**
   * Register the module and its form settings.
   */
  FLBuilder::register_module('FLBasicExampleModule', array(
      'general' => array(
          'title'       => __('Blueprints', 'pzarchitect'),
          'description' => __('Note: If you select a Blueprint that uses sliders, tabbed, masonry, accordion or tabular layouts, this may not render correctly until the page is saved/published.', 'pzarchitect'),
          'sections'    => array(
              'general' => array(
                  'title'  => __('Select Blueprint(s)', 'pzarchitect'),
                  'fields' => array(
                      'blueprint_default' => array(
                          'type'    => 'select',
                          'label'   => __('Blueprint (default)', 'pzarchitect'),
                          'default' => 'single',
                          'options' => $blueprint_list,
                      ),
                      'blueprint_tablet'  => array(
                          'type'    => 'select',
                          'label'   => __('Blueprint (tablet)', 'pzarchitect'),
                          'default' => '',
                          'options' => $blueprint_list,
                      ),
                      'blueprint_phone'   => array(
                          'type'    => 'select',
                          'label'   => __('Blueprint (phone)', 'pzarchitect'),
                          'default' => '',
                          'options' => $blueprint_list,
                      ),
                  ),
              ),
          ),
      ),

      'filters' => array(
          'title'    => __('Filters', 'pzarchitect'),
          'sections' => array(
              'filter_toggle'  => array(
                  'fields' => array(
                      'override_filters' => array(
                          'type'    => 'select',
                          'label'   => __('Enable filter overrides', 'pzarchitect'),
                          'default' => 'no',
                          'options' => array(
                              'yes' => __('Yes', 'pzarchitect'),
                              'no'  => __('No', 'pzarchitect'),
                          ),
                          'toggle'  => array(
                              'yes' => array(
                                  'sections' => array('filter_section'),
                              ),
                              'no'  => array(),
                          ),
                      ),
                  ),
              ),
              'filter_section' => array(
                  'fields' => array(
                      'post__in'         => array(
                          'type'    => 'suggest',
                          'action'  => 'fl_as_posts',
                          'data'    => 'post',
                          'label'   => __('Specific IDs', 'pzarchitect'),
                          'help'    => __('Enter a comma separated list of actual post IDs to show those posts'),
                          'default' => '',
                      ),
                      'category__in'     => array(
                          'type'   => 'suggest',
                          'label'  => __('Include Categories', 'pzarchitect'),
                          'action' => 'fl_as_terms', // Search terms.
                          'data'   => 'category', // Slug of the post type to search.
                          //                          'limit'  => 10,// Limits the number of selections that can be made.
                      ),
                      'cat_all_any'      => array(
                          'type'    => 'select',
                          'label'   => __('In ANY or ALL categories', 'pzarchitect'),
                          'default' => 'any',
                          'options' => array(
                              'any' => __('Any', 'pzarchitect'),
                              'all' => __('All', 'pzarchitect'),
                          ),
                      ),
                      'category__not_in' => array(
                          'type'   => 'suggest',
                          'label'  => __('Exclude Categories', 'pzarchitect'),
                          'action' => 'fl_as_terms', // Search terms.
                          'data'   => 'category', // Slug of the post type to search.
                          //                          'limit'  => 10,// Limits the number of selections that can be made.
                      ),
                      'tag__in'          => array(
                          'type'   => 'suggest',
                          'label'  => __('Include Tags', 'pzarchitect'),
                          'action' => 'fl_as_terms', // Search terms.
                          'data'   => 'tags', // Slug of the post type to search.
                          //                          'limit'  => 10,// Limits the number of selections that can be made.
                      ),
                      'tag__not_in'      => array(
                          'type'   => 'suggest',
                          'label'  => __('Exclude Tags', 'pzarchitect'),
                          'action' => 'fl_as_terms', // Search terms.
                          'data'   => 'tags', // Slug of the post type to search.
                          //                          'limit'  => 10,// Limits the number of selections that can be made.
                      ),
                      'taxonomy'         => array(
                          'type'    => 'select',
                          'label'   => __('Other taxonomy', 'pzarchitect'),
                          'default' => '',
                          'options' => return_taxonomies(),
                      ),
                      'terms'            => array(
                          'type'    => 'text',
                          'label'   => __('Terms', 'pzarchitect'),
                          'default' => '',
                      ),
                  ),
              ),
          ),
      ),
      'styles'  => array(
          'title'    => __('Styles', 'pzarchitect'),
          'sections' => array(
              'styles' => array(
                  'fields' => array(
//                      'entry_title'     => array(
//                          'type'         => 'form',
//                          'form'         => 'form_styles_editor',
//                          'preview_text' => 'alternate_classes',
//                          'label'        => 'Entry title',
//                          'help'         => '.entry-title',
//                      ),
//                      'entry_title_a'   => array(
//                          'type'         => 'form',
//                          'form'         => 'form_styles_editor',
//                          'preview_text' => 'alternate_classes',
//                          'label'        => 'Entry title linked',
//                          'help'         => '.entry-title a',
//                      ),
//                      'entry_meta'      => array(
//                          'type'         => 'form',
//                          'form'         => 'form_styles_editor',
//                          'preview_text' => 'alternate_classes',
//                          'label'        => __('Entry meta', '.pzarchitect'),
//                          'help'         => '.entry-meta',
//                      ),
//                      'entry_meta_a'    => array(
//                          'type'         => 'form',
//                          'form'         => 'form_styles_editor',
//                          'preview_text' => 'alternate_classes',
//                          'label'        => __('Entry meta links', 'pzarchitect'),
//                          'help'         => __('.entry-meta a', 'pzarchitect'),
//                      ),
//                      'entry_content'   => array(
//                          'type'         => 'form',
//                          'form'         => 'form_styles_editor',
//                          'preview_text' => 'alternate_classes',
//                          'label'        => __('Entry content', 'pzarchitect'),
//                          'help'         => __('.entry-content', 'pzarchitect'),
//                      ),
//                      'entry_content_a' => array(
//                          'type'         => 'form',
//                          'form'         => 'form_styles_editor',
//                          'preview_text' => 'alternate_classes',
//                          'label'        => __('Entry content links', 'pzarchitect'),
//                          'help'         => __('.entry-content a', 'pzarchitect'),
//                      ),
//                      'entry_excerpt'   => array(
//                          'type'         => 'form',
//                          'form'         => 'form_styles_editor',
//                          'preview_text' => 'alternate_classes',
//                          'label'        => __('Entry excerpt', 'pzarchitect'),
//                          'help'         => __('.entry-excerpt', 'pzarchitect'),
//                      ),
//                      'entry_excerpt_a' => array(
//                          'type'         => 'form',
//                          'form'         => 'form_styles_editor',
//                          'preview_text' => 'alternate_classes',
//                          'label'        => __('Entry excerpt links', 'pzarchitect'),
//                          'help'         => __('.entry-excerpt a', 'pzarchitect'),
//                      ),
                  ),
              ),
          ),

      ),
  ));