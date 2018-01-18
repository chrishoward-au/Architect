<?php
  /**
   * Project pizazzwp-architect.
   * File: arc_shortcodes.php
   * User: chrishoward
   * Date: 8/10/15
   * Time: 3:02 PM
   *  Details: A collection of useful shortcodes
   */

  class arc_shortcodes {
    //public $blueprint;

    function __construct( &$blueprint ) {
      $this->blueprint = $blueprint->blueprint;
      add_shortcode( 'arcpagetitle', array( $this, 'display_page_title' ) );
      add_shortcode( 'arc_debug', array( $this, 'arc_debug' ) );
    }


    function display_page_title( $atts ) {
      global $_architect_options;
      $htag = ! empty( $atts['tag'] ) ? $atts['tag'] : 'p';

      return pzarc_display_page_title( $this->blueprint, $_architect_options, $htag );
    }

    function arc_debug( $atts = NULL, $content = NULL ) {
      global $wp_query;
      ob_start();
      echo '<h3>Architect debug info</h3>';
      echo '<p><strong>Blueprint: </strong>' . $this->blueprint['_blueprints_short-name'] . '</p>';
      echo '<p><strong>Layout: </strong>' . $this->blueprint['_blueprints_section-0-layout-mode'] . '</p>';
      echo '<p><strong>Source: </strong>' . $this->blueprint['_blueprints_content-source'] . '</p>';
      var_dump( $wp_query );
      $arc_buffer = ob_get_contents();
      ob_end_clean();

      return $arc_buffer;
    }


  }