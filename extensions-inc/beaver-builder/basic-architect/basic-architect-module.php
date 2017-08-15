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
  $blueprint_list = pzarc_get_posts_in_post_type('arc-blueprints', TRUE, TRUE, TRUE, TRUE);
  $blueprint_list = array_merge(array('none' => 'None'), $blueprint_list, array('show-none' => 'DO NOT SHOW ANY BLUEPRINT'));
  $blank_array    = array('none' => '');
  $taxonomy_list  = pzarc_get_taxonomies(TRUE);

  if ( ! isset( $GLOBALS[ '_architect_options' ] ) ) {
    $GLOBALS[ '_architect_options' ] = get_option( '_architect_options', array() );
  }
  global $_architect_options;
  $global_settings = FLBuilderModel::get_global_settings();
  // $styles = fl_render_styles_settings($arc_styles);

  /**
   * Register the module and its form settings.
   */
  FLBuilder::register_module('FLBasicExampleModule', array(
      'general' => array(
          'title'       => __('Blueprints', 'pzarchitect'),
          'description' => __('Note: If you select a Blueprint that uses sliders, tabbed, masonry, accordion or tabular layouts, this may not render correctly in Builder until the page is saved/published.', 'pzarchitect'),
          'sections'    => array(
              'general' => array(
                  'fields' => array(
                      'blueprint_default' => array(
                          'type'    => 'select',
                          'label'   => __('Default Blueprint', 'pzarchitect'),
                          'default' => 'single',
                          'options' => $blueprint_list,
                      ),
                      'blueprint_tablet'  => array(
                          'type'    => 'select',
                          'label'   => __('Alternate Blueprint for tablets', 'pzarchitect'),
                          'default' => '',
                          'options' => $blueprint_list,
                      ),
                      'blueprint_phone'   => array(
                          'type'    => 'select',
                          'label'   => __('Alternate Blueprint for phones', 'pzarchitect'),
                          'default' => '',
                          'options' => $blueprint_list,
                      ),
                      'blueprint_title'   => array(
                        'type'    => 'text',
                        'label'   => __('Override Blueprint display title', 'pzarchitect'),
                        'default' => '',
                      ),
                  ),
              ),
          ),
      ),

      'filters'        => array(
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
      'styling'        => array(
          'title'       => __('Styling', 'pzarchitect'),
          'description' => __('Even if you have not selected a specific Blueprint for tablets and phones, you can still customise styling for those devices.  Note: Breakpoints can be changed in Architect > Options > Breakpoints menu in the WP admin.'),
          'sections'    => array(
              'styles_toggle'  => array(
                  'fields' => array(
                      'custom_styles' => array(
                          'type'    => 'select',
                          'label'   => __('Enable custom styling of this Architect module', 'pzarchitect'),
                          'default' => 'none',
                          'options' => array(
                              'no'              => __('No', 'pzarchitect'),
                              'defaults'        => __('Defaults', 'pzarchitect'),
                              'all'             => __('All', 'pzarchitect'),
                              'medium'          => __('Medium screen devices', 'pzarchitect'),
                              'defaults_medium' => __('Defaults and Medium screen devices', 'pzarchitect'),
                              'small'           => __('Small screen devices', 'pzarchitect'),
                              'defaults_small'  => __('Defaults and Small screen devices', 'pzarchitect'),
                              'medium_small'    => __('Medium Small screen devices', 'pzarchitect'),
                          ),
                          'toggle'  => array(
                              'defaults'        => array(
                                  'sections' => array('styles_default'),
                              ),
                              'medium'          => array(
                                  'tabs' => array('styling_medium'),
                              ),
                              'defaults_medium' => array(
                                  'sections' => array('styles_default'),
                                  'tabs'     => array('styling_medium'),
                              ),
                              'small'           => array(
                                  'tabs' => array('styling_small'),
                              ),
                              'defaults_small'  => array(
                                  'sections' => array('styles_default'),
                                  'tabs'     => array('styling_small'),
                              ),
                              'medium_small'    => array(
                                  'tabs' => array('styling_medium', 'styling_small'),
                              ),
                              'all'             => array(
                                  'tabs'     => array('styling_medium', 'styling_small'),
                                  'sections' => array('styles_default'),
                              ),
                              'no'              => array(),
                          ),
                      ),
                  ),
              ),
              'styles_default' => array(
                  'fields' => array(
                      'default__.entry-title'           => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Entry title',
                          'help'         => '.entry-title',
                      ),
                      'default__.entry-title a'         => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Entry title linked',
                          'help'         => '.entry-title a',
                      ),
                      'default__.entry-title a:hover'   => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Entry title linked hover',
                          'help'         => '.entry-title a:hover',
                      ),
                      'default__.entry-meta'            => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry meta', '.pzarchitect'),
                          'help'         => '.entry-meta',
                      ),
                      'default__.entry-meta a'          => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry meta links', 'pzarchitect'),
                          'help'         => __('.entry-meta a', 'pzarchitect'),
                      ),
                      'default__.entry-meta a:hover'    => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry meta links hover', 'pzarchitect'),
                          'help'         => __('.entry-meta a:hover', 'pzarchitect'),
                      ),
                      'default__.entry-content'         => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry content', 'pzarchitect'),
                          'help'         => __('.entry-content', 'pzarchitect'),
                      ),
                      'default__.entry-content a'       => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry content links', 'pzarchitect'),
                          'help'         => __('.entry-content a', 'pzarchitect'),
                      ),
                      'default__.entry-content a:hover' => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry content links hover', 'pzarchitect'),
                          'help'         => __('.entry-content a:hover', 'pzarchitect'),
                      ),
                      'default__.entry-excerpt'         => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry excerpt', 'pzarchitect'),
                          'help'         => __('.entry-excerpt', 'pzarchitect'),
                      ),
                      'default__.entry-excerpt a'       => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry excerpt links', 'pzarchitect'),
                          'help'         => __('.entry-excerpt a', 'pzarchitect'),
                      ),
                      'default__.entry-excerpt a:hover' => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry excerpt links hover', 'pzarchitect'),
                          'help'         => __('.entry-excerpt a:hover', 'pzarchitect'),
                      ),
                      'default__.pzarc-components'      => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Components group',
                          'help'         => '.pzarc-components',
                      ),
                      'default__.pzarc-panel'           => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Panels',
                          'help'         => '.pzarc-panel',
                      ),
                      'default__.pzarc-blueprint'       => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Blueprint',
                          'help'         => '.pzarc-blueprint',
                      ),
                      'default__custom_css'             => array(
                          'type'        => 'code',
                          'editor'      => 'css',
                          'description' => __('Usable class names can be seen in the help info for each of the above'),
                          'rows'        => '10',
                          'label'       => __('Custom CSS', 'pzarchitect'),
                          'help'        => __('', 'pzarchitect'),
                          'default'     => __('.pzarc-blueprint {}'),
                      ),
                  ),
              ),
          ),
      ),
      'styling_medium' => array(
          'title'       => __('Medium', 'pzarchitect'),
          'description' => __('Even if you have not selected a specific Blueprint for tablets and phones, you can still customise styling for those devices.  Note: Breakpoints can be changed in Architect > Options > Breakpoints menu in the WP admin.'),
          'sections'    => array(

              'styles_medium' => array(
                  'fields' => array(
                      'medium__breakpoint'=>array(
                          'type'=>'select',
                          'label'=> __('Use medium breakpoint from','pzarchitect'),
                          'default'=>'beaver',
                          'options'=>array(
                              'architect'=>'Architect ('.$_architect_options['architect_breakpoint_1']['width'].'px)',
                              'beaver'=>'Beaver ('.$global_settings->medium_breakpoint.'px)'
                          )
                      ),
                      'medium__.entry-title'           => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Entry title',
                          'help'         => '.entry-title',
                      ),
                      'medium__.entry-title a'         => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Entry title linked',
                          'help'         => '.entry-title a',
                      ),
                      'medium__.entry-title a:hover'   => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Entry title linked hover',
                          'help'         => '.entry-title a:hover',
                      ),
                      'medium__.entry-meta'            => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry meta', '.pzarchitect'),
                          'help'         => '.entry-meta',
                      ),
                      'medium__.entry-meta a'          => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry meta links', 'pzarchitect'),
                          'help'         => __('.entry-meta a', 'pzarchitect'),
                      ),
                      'medium__.entry-meta a:hover'    => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry meta links hover', 'pzarchitect'),
                          'help'         => __('.entry-meta a:hover', 'pzarchitect'),
                      ),
                      'medium__.entry-content'         => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry content', 'pzarchitect'),
                          'help'         => __('.entry-content', 'pzarchitect'),
                      ),
                      'medium__.entry-content a'       => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry content links', 'pzarchitect'),
                          'help'         => __('.entry-content a', 'pzarchitect'),
                      ),
                      'medium__.entry-content a:hover' => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry content links hover', 'pzarchitect'),
                          'help'         => __('.entry-content a:hover', 'pzarchitect'),
                      ),
                      'medium__.entry-excerpt'         => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry excerpt', 'pzarchitect'),
                          'help'         => __('.entry-excerpt', 'pzarchitect'),
                      ),
                      'medium__.entry-excerpt a'       => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry excerpt links', 'pzarchitect'),
                          'help'         => __('.entry-excerpt a', 'pzarchitect'),
                      ),
                      'medium__.entry-excerpt a:hover' => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry excerpt links hover', 'pzarchitect'),
                          'help'         => __('.entry-excerpt a:hover', 'pzarchitect'),
                      ),
                      'medium__.pzarc-components'      => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Components group',
                          'help'         => '.pzarc-components',
                      ),
                      'medium__.pzarc-panel'           => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Panels',
                          'help'         => '.pzarc-panel',
                      ),
                      'medium__.pzarc-blueprint'       => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Blueprint',
                          'help'         => '.pzarc-blueprint',
                      ),
                      'medium__custom_css'             => array(
                          'type'        => 'code',
                          'editor'      => 'css',
                          'description' => __('Usable class names can be seen in the help info for each of the above'),
                          'rows'        => '10',
                          'label'       => __('Custom CSS', 'pzarchitect'),
                          'help'        => __('', 'pzarchitect'),
                          'default'     => __('.pzarc-blueprint {}'),
                      ),
                  ),
              ),
          ),
      ),
      'styling_small'  => array(
          'title'       => __('Small', 'pzarchitect'),
          'description' => __('Even if you have not selected a specific Blueprint for tablets and phones, you can still customise styling for those devices.  Note: Breakpoints can be changed in Architect > Options > Breakpoints menu in the WP admin.'),
          'sections'    => array(

              'styles_small' => array(
                  'fields' => array(
                      'small__breakpoint'=>array(
                          'type'=>'select',
                          'label'=> __('Use small breakpoint from','pzarchitect'),
                          'default'=>'beaver',
                          'options'=>array(
                              'architect'=>'Architect ('.$_architect_options['architect_breakpoint_2']['width'].'px)',
                              'beaver'=>'Beaver ('.$global_settings->responsive_breakpoint.'px)'
                          )
                      ),
                      'small__.entry-title'           => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Entry title',
                          'help'         => '.entry-title',
                      ),
                      'small__.entry-title a'         => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Entry title linked',
                          'help'         => '.entry-title a',
                      ),
                      'small__.entry-title a:hover'   => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Entry title linked hover',
                          'help'         => '.entry-title a:hover',
                      ),
                      'small__.entry-meta'            => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry meta', '.pzarchitect'),
                          'help'         => '.entry-meta',
                      ),
                      'small__.entry-meta a'          => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry meta links', 'pzarchitect'),
                          'help'         => __('.entry-meta a', 'pzarchitect'),
                      ),
                      'small__.entry-meta a:hover'    => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry meta links hover', 'pzarchitect'),
                          'help'         => __('.entry-meta a:hover', 'pzarchitect'),
                      ),
                      'small__.entry-content'         => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry content', 'pzarchitect'),
                          'help'         => __('.entry-content', 'pzarchitect'),
                      ),
                      'small__.entry-content a'       => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry content links', 'pzarchitect'),
                          'help'         => __('.entry-content a', 'pzarchitect'),
                      ),
                      'small__.entry-content a:hover' => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry content links hover', 'pzarchitect'),
                          'help'         => __('.entry-content a:hover', 'pzarchitect'),
                      ),
                      'small__.entry-excerpt'         => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry excerpt', 'pzarchitect'),
                          'help'         => __('.entry-excerpt', 'pzarchitect'),
                      ),
                      'small__.entry-excerpt a'       => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry excerpt links', 'pzarchitect'),
                          'help'         => __('.entry-excerpt a', 'pzarchitect'),
                      ),
                      'small__.entry-excerpt a:hover' => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => __('Entry excerpt links hover', 'pzarchitect'),
                          'help'         => __('.entry-excerpt a:hover', 'pzarchitect'),
                      ),
                      'small__.pzarc-components'      => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Components group',
                          'help'         => '.pzarc-components',
                      ),
                      'small__.pzarc-panel'           => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Panels',
                          'help'         => '.pzarc-panel',
                      ),
                      'small__.pzarc-blueprint'       => array(
                          'type'         => 'form',
                          'form'         => 'form_styles_editor',
                          'preview_text' => 'enable_styling',
                          'label'        => 'Blueprint',
                          'help'         => '.pzarc-blueprint',
                      ),
                      'small__custom_css'             => array(
                          'type'        => 'code',
                          'editor'      => 'css',
                          'description' => __('Usable class names can be seen in the help info for each of the above'),
                          'rows'        => '10',
                          'label'       => __('Custom CSS', 'pzarchitect'),
                          'help'        => __('', 'pzarchitect'),
                          'default'     => __('.pzarc-blueprint {}'),
                      ),
                  ),
              ),
          ),
      ),

  ));