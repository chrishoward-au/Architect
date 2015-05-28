<?php
/**
 * Project pizazzwp-architect.
 * File: arc-slick15-admin.php
 * User: chrishoward
 * Date: 15/05/15
 * Time: 8:17 PM
 */

  add_filter('arc-slider-engine','pzarc_slick15');
  function pzarc_slick15($sliders){
    $sliders['slick15']='Slick 1.5';
    return $sliders;
  }

  add_filter('arc-extend-slider-settings','pzarc_slick15_slider_settings');
  function pzarc_slick15_slider_settings($settings) {

    $prefix='_slick15_';

    $settings['fields'][] =
    array(
      'title'    => __( 'Slick 1.5', 'pzarchitect' ),
      'id'       => $prefix . 'section-slick15-heading',
      'type'     => 'section',
      'indent'   => true,
      'required' => array( '_blueprints_slider-engine', '=', 'slick15' ),
    );
    $settings['fields'][] =
        array(
            'title'   => __('Options', 'pzarchitect'),
            'id'      => $prefix . 'extra-options',
            'type'    => 'button_set',
            'multi'=>true,
            'default' => array('infinite','pause','adaptive'),
            'options' => array('infinite'=>'Infinite loop','pause'=>'Pause on hover','adaptive'=>'Adaptive height')
        );

//    $settings['fields'][] =
//                  array(
//                      'title'   => __('Infinite loop', 'pzarchitect'),
//                      'id'      => $prefix . 'infinite',
//                      'type'    => 'switch',
//                      'default' => true,
//                      'hint'    => array('content' => __('Loop back to the first slide after reaching the last one', 'pzarchitect')),
//                  );
//
//    $settings['fields'][] =
//                array(
//                    'title'   => 'Pause on hover',
//                    'id'      => $prefix . 'pause-on-hover',
//                    'type'    => 'switch',
//                    'default' => true,
//                );
//
//    $settings['fields'][] =
//        array(
//            'title'   => 'Adaptive height',
//            'id'      => $prefix . 'adaptive-height',
//            'type'    => 'switch',
//            'default' => true,
//        );

    $settings['fields'][] = array(
      'id'       => $prefix . 'section-slick15-close',
      'type'     => 'section',
      'indent'   => false,
      'required' => array( '_blueprints_slider-engine', '=', 'slick15' ),
    );

    return $settings;
  }