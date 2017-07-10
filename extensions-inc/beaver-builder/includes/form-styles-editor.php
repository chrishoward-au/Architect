<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 18/6/17
   * Time: 11:55 AM
   */


  FLBuilder::register_settings_form('form_styles_editor', array(
      'title' => __('Styles Editor', 'pzarchitect'),
      'tabs'  => array(
          'typography' => array(
              'title'    => __('Typography', 'pzarchitect'),
              'sections' => array(
                  'typography' => array(
                      'title'  => '',
                      'fields' => array(
                          'font'            => array(
                              'type'    => 'font',
                              'label'   => __('Font', 'pzarchitect'),
                              'default' => array(
                                  'family' => 'Default',
                                  'weight' => 'Default',
                              ),
                          ),
                          'color'           => array(
                              'type'  => 'color',
                              'label' => __('Colour', 'pzarchitect'),
                          ),
                          'font-size'       => array(
                              'type'  => 'pz-box-styling',
                              'show'  => array('number', 'type'),
                              'label' => __('Size', 'pzarchitect'),
                          ),
                          'letter-spacing'  => array(
                              'type'  => 'pz-box-styling',
                              'show'  => array('number', 'type'),
                              'label' => __('Letter spacing', 'pzarchitect'),
                          ),
                          'line--eight'     => array(
                              'type'  => 'pz-box-styling',
                              'show'  => array('number', 'type'),
                              'label' => __('Line height', 'pzarchitect'),
                          ),
                          'text-align'      => array(
                              'type'    => 'select',
                              'label'   => __('Text alignment', 'pzarchitect'),
                              'options' => array(
                                  ''             => '',
                                  'left'         => 'left',
                                  'center'       => 'center',
                                  'right'        => 'right',
                                  'justify'      => 'justify',
                                  'justify-all'  => 'justify-all',
                                  'start'        => 'start',
                                  'end'          => 'end',
                                  'match-parent' => 'match-parent',
                                  'inherit'      => 'inherit',
                                  'initial'      => 'initial',
                              ),
                          ),
                          'text-decoration' => array(
                              'type'    => 'select',
                              'label'   => __('Text decoration', 'pzarchitect'),
                              'options' => array(
                                  ''          => '',
                                  'none'      => 'none',
                                  'underline' => 'underline',
                                  'inherit'   => 'inherit',
                                  'initial'   => 'initial',
                              ),
                          ),
                          'font-style'      => array(
                              'type'    => 'select',
                              'label'   => __('Style', 'pzarchitect'),
                              'options' => array(
                                  ''        => '',
                                  'normal'  => 'normal',
                                  'italic'  => 'italic',
                                  'oblique' => 'oblique',
                                  'inherit' => 'inherit',
                                  'initial' => 'initial',
                                  'unset'   => 'unset',
                              ),
                          ),
                          'font-variant'    => array(
                              'type'    => 'select',
                              'label'   => __('Variant', 'pzarchitect'),
                              'options' => array(
                                  ''                => '',
                                  'small-caps'      => 'small-caps',
                                  'all-caps'        => 'all-caps',
                                  'all-small-caps'  => 'all-small-caps',
                                  'petite-caps'     => 'petite-caps',
                                  'all-petite-caps' => 'all-petite-caps',
                                  'unicase'         => 'unicase',
                                  'titling-caps'    => 'titling-caps',
                                  'inherit'         => 'inherit',
                                  'initial'         => 'initial',
                                  'unset'           => 'unset',
                              ),
                          ),
                      ),
                  ),
              ),
          ),
          'layout'     => array(
              'title'    => __('Layout', 'pzarchitect'),
              'sections' => array(
                  'margins' => array(
                      'title'  => 'Margins',
                      'fields' => array(
                          'margin-top'    => array(
                              'type'  => 'pz-box-styling',
                              'show'  => array('number', 'type'),
                              'label' => __('Top', 'pzarchitect'),
                          ),
                          'margin-bottom' => array(
                              'type'  => 'pz-box-styling',
                              'show'  => array('number', 'type'),
                              'label' => __('Bottom', 'pzarchitect'),
                          ),
                          'margin-left'   => array(
                              'type'  => 'pz-box-styling',
                              'show'  => array('number', 'type'),
                              'label' => __('Left', 'pzarchitect'),
                          ),
                          'margin-right'  => array(
                              'type'  => 'pz-box-styling',
                              'show'  => array('number', 'type'),
                              'label' => __('Right', 'pzarchitect'),
                          ),
                      ),
                  ),
                  'padding' => array(
                      'title'  => 'Padding',
                      'fields' => array(
                          'padding-top'    => array(
                              'type'  => 'pz-box-styling',
                              'show'  => array('number', 'type'),
                              'label' => __('Padding top', 'pzarchitect'),
                          ),
                          'padding-bottom' => array(
                              'type'  => 'pz-box-styling',
                              'show'  => array('number', 'type'),
                              'label' => __('Padding bottom', 'pzarchitect'),
                          ),
                          'padding-left'   => array(
                              'type'  => 'pz-box-styling',
                              'show'  => array('number', 'type'),
                              'label' => __('Padding left', 'pzarchitect'),
                          ),
                          'padding-right'  => array(
                              'type'  => 'pz-box-styling',
                              'show'  => array('number', 'type'),
                              'label' => __('Padding right', 'pzarchitect'),
                          ),
                      ),
                  ),
              ),
          ),
          'design'     => array(
              'title'    => __('Design', 'pzarchitect'),
              'sections' => array(
                  'background' => array(
                      'title'  => __('Background', 'pzarchitect'),
                      'fields' => array(
                          'background-color'      => array(
                              'type'  => 'color',
                              'label' => __('Colour', 'pzarchitect'),
                          ),
                          'background-image'      => array(
                              'type'        => 'photo',
                              'label'       => __('Image', 'pzarchitect'),
                              'show_remove' => TRUE,
                          ),
                          'background-attachment' => array(
                              'type'    => 'select',
                              'label'   => __('Attachment', 'pzarchitect'),
                              'options' => array(
                                  'scroll'  => 'scroll',
                                  'fixed'   => 'fixed',
                                  'local'   => 'local',
                                  'inherit' => 'inherit',
                              ),
                          ),
                          'background-position-x' => array(
                              'type'    => 'select',
                              'label'   => __('Position X', 'pzarchitect'),
                              'options' => array(
                                  ''        => '',
                                  'left'    => 'left',
                                  'center'  => 'center',
                                  'right'   => 'right',
                                  'inherit' => 'inherit',
                                  'initial' => 'initial',
                                  'unset'   => 'unset',
                              ),
                          ),
                          'background-position-y' => array(
                              'type'    => 'select',
                              'label'   => __('Position Y', 'pzarchitect'),
                              'options' => array(
                                  ''        => '',
                                  'top'     => 'top',
                                  'middle'  => 'middle',
                                  'bottom'  => 'bottom',
                                  'inherit' => 'inherit',
                                  'initial' => 'initial',
                                  'unset'   => 'unset',
                              ),
                          ),
                          'background-repeat'     => array(
                              'type'    => 'select',
                              'label'   => __('Repeat', 'pzarchitect'),
                              'options' => array(
                                  ''          => '',
                                  'no-repeat' => 'no-repeat',
                                  'repeat'    => 'repeat',
                                  'repeat-x'  => 'repeat-x',
                                  'repeat-y'  => 'repeat-y',
                                  'space'     => 'space',
                                  'round'     => 'round',
                              ),
                          ),
                          'background-size'       => array(
                              'type'    => 'select',
                              'label'   => __('Size', 'pzarchitect'),
                              'options' => array(
                                  ''        => '',
                                  'cover'   => 'cover',
                                  'contain' => 'contain',
                                  'auto'    => 'auto',
                              ),
                          ),
                          'background-origin'     => array(
                              'type'    => 'select',
                              'label'   => __('Origin', 'pzarchitect'),
                              'options' => array(
                                  ''            => '',
                                  'border-box'  => 'border-box',
                                  'content-box' => 'content-box',
                                  'padding-box' => 'padding-box',
                                  'inherit'     => 'inherit',
                              ),
                          ),
                          'background-clip'       => array(
                              'type'    => 'select',
                              'label'   => __('Clip', 'pzarchitect'),
                              'options' => array(
                                  ''            => '',
                                  'border-box'  => 'border-box',
                                  'content-box' => 'content-box',
                                  'padding-box' => 'padding-box',
                                  'text'        => 'text',
                                  'inherit'     => 'inherit',
                                  'initial'     => 'initial',
                                  'unset'       => 'unset',
                              ),

                          ),
                      ),
                  ),
                  'borders'    => array(
                      'title'  => __('Borders', 'pzarchitect'),
                      'fields' => array(
                          'border-top'    => array(
                              'type'  => 'pz-box-styling',
                              'label' => __('Border top', 'pzarchitect'),
                              'show'  => array('number', 'type', 'colour', 'border_style'),
                          ),
                          'border-left'   => array(
                              'type'  => 'pz-box-styling',
                              'label' => __('Border left', 'pzarchitect'),
                              'show'  => array('number', 'type', 'colour', 'border_style'),
                          ),
                          'border-bottom' => array(
                              'type'  => 'pz-box-styling',
                              'label' => __('Border bottom', 'pzarchitect'),
                              'show'  => array('number', 'type', 'colour', 'border_style'),
                          ),
                          'border-right'  => array(
                              'type'  => 'pz-box-styling',
                              'label' => __('Border right', 'pzarchitect'),
                              'show'  => array('number', 'type', 'colour', 'border_style'),
                          ),
                      ),
                  ),
              ),
          ),
          'extras'     => array(
              'title'    => __('Extras', 'pzarchitect'),
              'sections' => array(
                  'extra' => array(
                      'title'  => '',
                      'fields' => array(
                          'alternate_classes' => array(
                              'type'  => 'text',
                              'label' => __('Alternate classes', 'pzarchitect'),
                          ),
                      ),
                  ),

              ),
          ),
      ),
  ));