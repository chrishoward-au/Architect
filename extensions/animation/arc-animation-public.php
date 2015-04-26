<?php

  /**
   * Project pizazzwp-architect.
   * File: arc-animation-public.php
   * User: chrishoward
   * Date: 16/04/15
   * Time: 10:01 PM
   */
  class arcAnimationPublic {

    public $pno = array();
    public $blueprint;

    function __construct() {
      add_action( 'init', array( $this, 'init' ) );
      add_filter( 'pzarc_render_components', array( $this, 'process_animation' ), 10, 4 );
      add_filter( 'pzarc-load-blueprint', array( $this, 'load_blueprint' ) );
      add_filter('pzarc-extend-panel-classes',array($this,'add_classes'),10,2);
      add_filter('pzarc-extend-panel-data',array($this,'add_data'),10,2);

    }

    function init() {
      wp_register_style( 'css-animate',plugin_dir_url(__FILE__) . '/animate.min.css'  );
      wp_register_script( 'wow-front', plugin_dir_url(__FILE__) . '/arc-front-wow.js', array( 'jquery' ), '', false );
      wp_register_script( 'wow-js', plugin_dir_url(__FILE__) . '/WOW/wow.js', array(), null, true );
      wp_enqueue_style( 'css-animate' );
      wp_enqueue_script( 'wow-js' );
      wp_enqueue_script( 'wow-front' );
    }

    function load_blueprint( $blueprint ) {
      $animation          = new arcAnimationAdmin( true );
      $metaboxes          = array();
      $animation_settings = $animation->pzarc_mb_animation( $metaboxes, true );

      // Load the defaults
      foreach ( $animation_settings[ 0 ][ 'sections' ] as $section ) {
        foreach ( $section[ 'fields' ] as $field ) {
          if ( isset( $field[ 'default' ] ) && ! isset( $blueprint[ $field[ 'id' ] ] ) ) {
            $blueprint[ $field[ 'id' ] ] = $field[ 'default' ];
          }
        }
      }
      foreach ( $blueprint[ '_animation_sequence' ] as $value ) {
        $this->pno[ $value ] = 0;
      }
      $this->pno['panels']=0;
      $this->blueprint = $blueprint;
      return $blueprint;
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


//    private function process_animation( $ani_component, $panel_def_component, $pno, &$blueprint ) {
    function process_animation( $line, $component, $source, $layout_mode) {

      if ($this->blueprint['_animation_target']==='panels') {
        return $line;
      }
      $ani_component = $component;
      switch ( true ) {
        case 'excerpt'===$component:
          $ani_component ='content';
          break;
        case 'image'===$component:
          $ani_component = 'feature';
          break;
      }
      if ( in_array( $ani_component, $this->blueprint[ '_animation_sequence' ] ) ) {
        $delay = 0;
        switch(  $this->blueprint[ '_animation_' . $ani_component . '-sync' ]) {
          case 'serial':
            $delay= ( $this->blueprint[ '_animation_' . $ani_component . '-delay' ] + ( $this->blueprint[ '_animation_' . $ani_component . '-duration' ] * $this->pno[ $ani_component ] * (1 - $this->blueprint[ '_animation_' . $ani_component . '-overlap' ]/100) ) );
            break;
          case 'parallel':
            $delay = 0;
            break;
          case 'random':
            $delay = rand(0,$this->blueprint[ '_blueprints_section-0-panels-per-view' ]);
            break;

        }

        $offset = 0;
        if ($this->blueprint[ '_animation_sequence-sync' ] == 'serial') {
          $offset = array_search($ani_component,$this->blueprint[ '_animation_sequence' ])* $this->blueprint[ '_animation_' . $ani_component . '-duration' ]*$this->blueprint[ '_blueprints_section-0-panels-per-view' ];
    //      var_dump($offset,$delay);
        }
        $animation_classes           = 'pzarc-wow ' . $this->blueprint[ '_animation_' . $ani_component . '-animation' ] . ' ';
        $animation_data              = 'data-wow-duration="' . $this->blueprint[ '_animation_' . $ani_component . '-duration' ] . 's" data-wow-delay="' . ($delay+$offset) . 's" ';
        $line                        = str_replace( '{{extensionclass}}', '{{extensionclass}} ' . $animation_classes, $line );
        $line                        = str_replace( '{{extensiondata}}', '{{extensiondata}}' . $animation_data, $line );
        $this->pno[ $ani_component ] = $this->pno[ $ani_component ] + 1 > $this->blueprint[ '_blueprints_section-0-panels-per-view' ] ? 0 : $this->pno[ $ani_component ] + 1;
      }


      return $line;
    }

    function add_classes($classes,$blueprint) {
      if ($this->blueprint['_animation_target']!=='panels') {
        return $classes;
      }
      $classes.= ' pzarc-wow '.$this->blueprint[ '_animation_panels-animation' ];
      return $classes;
    }

    function add_data($data,$blueprint) {
      if ($this->blueprint['_animation_target']!=='panels') {
        return $data;
      }
      $delay = 0;
      switch(  $this->blueprint[ '_animation_panels-sync' ]) {
        case 'serial':
          $delay= ( $this->blueprint[ '_animation_panels-delay' ] + ( $this->blueprint[ '_animation_panels-duration' ] * $this->pno[ 'panels' ] * (1 - $this->blueprint[ '_animation_panels-overlap' ]/100) ) );
//          var_dump($this->blueprint[ '_animation_panels-delay' ] + ( $this->blueprint[ '_animation_panels-duration' ] * $this->pno[ 'panels' ] * (1 - $this->blueprint[ '_animation_panels-overlap' ]/100) ));
//          var_dump($this->blueprint[ '_animation_panels-delay' ] , $this->blueprint[ '_animation_panels-duration' ] , $this->pno[ 'panels' ] , (1 - $this->blueprint[ '_animation_panels-overlap' ]/100) );
          break;
        case 'parallel':
          $delay = 0;
          break;
        case 'random':
          $delay = rand(0,$this->blueprint[ '_blueprints_section-0-panels-per-view' ]);
          break;

      }
      $offset= 0;
      $data              .= 'data-wow-duration="' . $this->blueprint[ '_animation_panels-duration' ] . 's" data-wow-delay="' . ($delay+$offset) . 's" ';
      $this->pno[ 'panels'] = $this->pno[ 'panels' ] + 1 > $this->blueprint[ '_blueprints_section-0-panels-per-view' ] ? 0 : $this->pno[ 'panels' ] + 1;
      return $data;
    }

  }

  new arcAnimationPublic();