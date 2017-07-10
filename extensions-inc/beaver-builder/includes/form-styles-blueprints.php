<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 1/7/17
   * Time: 6:04 PM
   */

  FLBuilder::register_settings_form('form_styles_content', array(
      'title' => __('Blueprint styling', 'fl-builder'),
      'tabs'  => array(
          'content_classes'   => array(
              'title'    => __('Content classes', 'pzarchitect'),
              'sections' => array(
                  'styles' => array(
                      'fields' => array(
                          'entry_title'       => array(
                              'type'         => 'form',
                              'form'         => 'form_styles_editor',
                              'preview_text' => 'alternate_classes',
                              'label'        => 'Entry title',
                              'help'         => '.entry-title',
                          ),
                          'entry_title_a'     => array(
                              'type'         => 'form',
                              'form'         => 'form_styles_editor',
                              'preview_text' => 'alternate_classes',
                              'label'        => 'Entry title linked',
                              'help'         => '.entry-title a',
                          ),
                          'entry_meta'        => array(
                              'type'         => 'form',
                              'form'         => 'form_styles_editor',
                              'preview_text' => 'alternate_classes',
                              'label'        => __('Entry meta', '.pzarchitect'),
                              'help'         => '.entry-meta',
                          ),
                          'entry_meta_a'      => array(
                              'type'         => 'form',
                              'form'         => 'form_styles_editor',
                              'preview_text' => 'alternate_classes',
                              'label'        => __('Entry meta links', 'pzarchitect'),
                              'help'         => __('.entry-meta a', 'pzarchitect'),
                          ),
                          'entry_content'     => array(
                              'type'         => 'form',
                              'form'         => 'form_styles_editor',
                              'preview_text' => 'alternate_classes',
                              'label'        => __('Entry content', 'pzarchitect'),
                              'help'         => __('.entry-content', 'pzarchitect'),
                          ),
                          'entry_content_a'   => array(
                              'type'         => 'form',
                              'form'         => 'form_styles_editor',
                              'preview_text' => 'alternate_classes',
                              'label'        => __('Entry content links', 'pzarchitect'),
                              'help'         => __('.entry-content a', 'pzarchitect'),
                          ),
                          'entry_excerpt'     => array(
                              'type'         => 'form',
                              'form'         => 'form_styles_editor',
                              'preview_text' => 'alternate_classes',
                              'label'        => __('Entry excerpt', 'pzarchitect'),
                              'help'         => __('.entry-excerpt', 'pzarchitect'),
                          ),
                          'entry_excerpt_a'   => array(
                              'type'         => 'form',
                              'form'         => 'form_styles_editor',
                              'preview_text' => 'alternate_classes',
                              'label'        => __('Entry excerpt links', 'pzarchitect'),
                              'help'         => __('.entry-excerpt a', 'pzarchitect'),
                          ),
                          'alternate_classes' => array(
                              'type'  => 'textarea',
                              'label' => __('Alternate classes', 'pzarchitect'),
                          ),

                      ),
                  ),
              ),

          ),
          'panel_classes'     => array(
              'title'    => __('Panel classes', 'pzarchitect'),
              'sections' => array(
                  'styles' => array(
                      'fields' => array(
                          'panel' => array(
                              'type'         => 'form',
                              'form'         => 'form_styles_editor',
                              'preview_text' => 'alternate_classes',
                              'label'        => 'Panels class',
                              'help'         => '.pzarc-panel',
                          ),
                      ),
                  ),
              ),
          ),
          'blueprint_classes' => array(
              'title'    => __('Blueprint classes', 'pzarchitect'),
              'sections' => array(
                  'styles' => array(
                      'fields' => array(
                          'blueprint' => array(
                              'type'         => 'form',
                              'form'         => 'form_styles_editor',
                              'preview_text' => 'alternate_classes',
                              'label'        => 'Blueprint class',
                              'help'         => '.pzarc-blueprint',
                          ),
                      ),
                  ),
              ),
          ),
          'custom_css'        => array(
              'title'    => __('Custom CSS', 'pzarchitect'),
              'sections' => array(
                  'styles' => array(
                      'fields' => array(
                          'custom_css' => array(
                              'type'    => 'code',
                              'editor'  => 'css',
                              'rows'    => '10',
                              'label'   => __('Custom CSS', 'pzarchitect'),
                              'help'    => __('', 'pzarchitect'),
                              'default' => __('.pzarc-blueprint {}'),
                          ),
                      ),
                  ),
              ),
          ),
      ),
  ));
