<?php

  /**
   * Project pizazzwp-architect.
   * File: arc-animation-public.php
   * User: chrishoward
   * Date: 16/04/15
   * Time: 10:01 PM
   */
  class arcAnimationPublic {

    function __construct() {

    }

//    static $pno = 0;
//  $panel_def[$component] = self::process_animation('meta1',$panel_def[$component],$pno);
//  $panel_def[$component] = self::process_animation('meta2',$panel_def[$component],$pno);
//  $panel_def[$component] = self::process_animation('meta3',$panel_def[$component],$pno++);

//  $pzclasses .= ! empty( $this->section[ '_panels_design_animate-components' ] ) && $this->section[ '_panels_design_animate-components' ] !== 'none' ? ' animated ' . $this->section[ '_panels_design_animate-components' ] : '';
//  if ('components-open'===$component) {
//    static $pno = 0;
//  $line = self::process_animation( 'panel', $line, $pno ++ );
//  }


    private function process_animation( $ani_component, $panel_def_component, $pno, &$blueprint ) {

      if ( ! empty( $blueprint[ '_animation_' . $ani_component . '-enable' ] ) ) {
        $animation_classes   = 'pzarc-wow animated ' . $blueprint[ '_animation_' . $ani_component . '-animation' ] . ' ';
        $animation_data      = 'data-wow-duration="' . $blueprint[ '_animation_' . $ani_component . '-duration' ] . 's" data-wow-delay="' . ( $blueprint[ '_animation_' . $ani_component . '-delay' ] + ( $blueprint[ '_animation_' . $ani_component . '-duration' ] * $pno ) ) . 's" ';
        $panel_def_component = str_replace( '{{extensionclass}}', '{{extensionclass}} ' . $animation_classes, $panel_def_component );
        $panel_def_component = str_replace( '{{extensiondata}}', '{{extensiondata}}' . $animation_data, $panel_def_component );
      }

      return $panel_def_component;
    }

  }