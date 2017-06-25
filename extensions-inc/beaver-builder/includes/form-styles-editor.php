<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 18/6/17
   * Time: 11:55 AM
   */


  FLBuilder::register_settings_form('form_styles_editor', array(
      'title' => __('Styles Editor', 'fl-builder'),
      'tabs'  => array(
          'typography' => array(
              'title'    => __('Typography', 'fl-builder'),
              'sections' => array(
                  'typography' => array(
                      'title'  => '',
                      'fields' => array(
                          'font'                 => array(
                              'type'    => 'font',
                              'label'   => __('Font', 'fl-builder'),
                              'default' => array(
                                  'family' => 'Helvetica',
                                  'weight' => '400',
                              ),
                          ),
                          'margins'              => array(
                              'type'  => 'color',
                              'label' => __('Colour', 'fl-builder'),
                          ),
                          'size'                 => array(
                              'type'  => 'unit',
                              'label' => __('Size', 'fl-builder'),
                          ),
                          'size_units'           => array(
                              'type'    => 'select',
                              'label'   => __('Units', 'fl-builder'),
                              'options' => array(
                                  ''    => '',
                                  'px'  => 'px',
                                  '%'   => '%',
                                  'em'  => 'em',
                                  'rem' => 'rem',
                              ),
                          ),
                          'letter_spacing'       => array(
                              'type'  => 'text',
                              'label' => __('Letter spacing', 'fl-builder'),
                          ),
                          'letter_spacing_units' => array(
                              'type'    => 'select',
                              'label'   => __('Units', 'fl-builder'),
                              'options' => array(
                                  ''    => '',
                                  'px'  => 'px',
                                  '%'   => '%',
                                  'em'  => 'em',
                                  'rem' => 'rem',
                              ),
                          ),
                          'line_height'          => array(
                              'type'  => 'text',
                              'label' => __('Line height', 'fl-builder'),
                          ),
                          'line_height_units'    => array(
                              'type'    => 'select',
                              'label'   => __('Units', 'fl-builder'),
                              'options' => array(
                                  ''    => '',
                                  'px'  => 'px',
                                  '%'   => '%',
                                  'em'  => 'em',
                                  'rem' => 'rem',
                              ),
                          ),
                      ),
                  ),
              ),
          ),
          'layout'     => array(
              'title'    => __('Layout', 'fl-builder'),
              'sections' => array(

                  'layout' => array(
                      'title'  => '',
                      'fields' => array(
                          'margins' => array(
                              'type'  => 'text',
                              'label' => __('Margins', 'fl-builder'),
                          ),
                          'padding' => array(
                              'type'  => 'text',
                              'label' => __('Padding', 'fl-builder'),
                          ),
                      ),
                  ),
              ),
          ),
          'design'     => array(
              'title'    => __('Design', 'fl-builder'),
              'sections' => array(

                  'design' => array(
                      'title'  => '',
                      'fields' => array(
                          'margins'          => array(
                              'type'  => 'color',
                              'label' => __('Background', 'fl-builder'),
                          ),
                          'background_image' => array(
                              'type'        => 'photo',
                              'label'       => __('Background image', 'fl-builder'),
                              'show_remove' => FALSE,
                          ),
                          'padding'          => array(
                              'type'  => 'text',
                              'label' => __('Border', 'fl-builder'),
                          ),
                      ),
                  ),

              ),
          ),
          'extras'     => array(
              'title'    => __('Extras', 'fl-builder'),
              'sections' => array(
                  'extra' => array(
                      'title'  => '',
                      'fields' => array(
                          'alternate_classes' => array(
                              'type'  => 'text',
                              'label' => __('Alternate classes', 'fl-builder'),
                          ),
                      ),
                  ),

              ),
          ),
      ),
  ));