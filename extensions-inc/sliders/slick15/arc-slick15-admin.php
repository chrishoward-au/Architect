<?php
  /**
   * Project pizazzwp-architect.
   * File: arc-slick15-admin.php
   * User: chrishoward
   * Date: 15/05/15
   * Time: 8:17 PM
   */

  add_filter('arc-slider-engine', 'pzarc_slick15');
  function pzarc_slick15($sliders)
  {
    $sliders[ 'slick' ] = 'Slick';
//    var_dump($sliders);

    return $sliders;
  }

  add_filter('arc-extend-slider-settings', 'pzarc_slick15_slider_settings');
  function pzarc_slick15_slider_settings($settings)
  {

    $prefix = '_slick15_';

    $settings[ 'fields' ][] =
        array(
            'title'    => __('Slick Slider settings', 'pzarchitect'),
            'id'       => $prefix . 'section-slick15-heading',
            'type'     => 'section',
            'indent'   => true,
            'required' => array('_blueprints_slider-engine', '=', 'slick'),
        );
    $settings[ 'fields' ][] =
    array(
        'title'    => 'Transition type',
        'id'       => '_blueprints_transitions-type',
        'type'     => 'button_set',
        'default'  => 'fade',
        'subtitle' => __('When transition is set to fade, slides are not draggable', 'pzarchitect'),
        //              'select2' => array('allowClear' => false),
        'required' => array('_blueprints_section-0-layout-mode', '=', 'slider'),
        'options'  => array('fade' => 'Fade', 'slide' => 'Slide')
    );

    $settings[ 'fields' ][] =
        array(
            'title'   => __('Options', 'pzarchitect'),
            'id'      => $prefix . 'extra-options',
            'type'    => 'button_set',
            'multi'   => true,
            'default' => array('pause', 'adaptive'),
            'options' => array('infinite' => 'Infinite loop',
                               'pause'    => 'Pause on hover',
                               'adaptive' => 'Adaptive height')
        );


    $settings[ 'fields' ][] = array(
        'id'       => $prefix . 'section-slick15-close',
        'type'     => 'section',
        'indent'   => false,
        'required' => array('_blueprints_slider-engine', '=', 'slick'),
    );

    return $settings;
  }