<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 29/04/2014
   * Time: 12:16 PM
   */
  class arc_Panel_testimonials extends arc_Panel_Generic {

    function __construct( &$build ) {

      parent::__construct( $build );
      parent::initialise_data();

      self::init_data();


      add_filter( 'arc_panel_def', array( $this, 'extend_panel_def' ) );
    }

    function extend_panel_def( $panel_def ) {
      $panel_def['company']  = '{{company}}';
      $panel_def['position'] = '{{position}}';

      // We don't want these filters applied to every occurrence of the plugin on the page!
      remove_filter( 'pzarc_panel_def', array( $this, 'extend_panel_def' ) );

      return $panel_def;
    }

    function init_data() {
      // set initial values
      $this->data['company']  = NULL;
      $this->data['position'] = NULL;
      $this->data['url']      = NULL;

    }

    function get_company() {

    }

    function get_position() {

    }

    // Renders are called by the arc_section
    function render_company() {

      $panel_def['company'] = "My company";
    }

    function render_position() {

    }
  }


