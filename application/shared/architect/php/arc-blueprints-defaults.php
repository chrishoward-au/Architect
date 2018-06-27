<?php
  /**
   * Project pizazzwp-architect.
   * File: arc-blueprints-defaults.php
   * Desc: Keeps and manages all default values to speed up processing.
   * User: chrishoward
   * Date: 23/02/2016
   * Time: 4:37 PM
   */

  function pzarc_stored_defaults() {
    $pzarc_defaults = array(
      'settings' => 'settings',
      'styling'  => 'styling',
      'options'  => 'options'
    );
    // Settings defaults
    $pzarc_defaults[ 'settings' ][ 'general' ]    = array();
    $pzarc_defaults[ 'settings' ][ 'blueprints' ] = array();
    $pzarc_defaults[ 'settings' ][ 'content' ]    = array();

    // Include both the variable name and its default. This will make conversion easier between systems
    // $pzarc_defaults[ 'settings' ][ 'blueprints' ]['xyz'] = array('name'=>'_blueprints_xyz','default'=>123);

    // should we consider adding value to this??? So it is used instead of redux?

    // Styling defaults
    $pzarc_defaults[ 'styling' ][ 'general' ]    = array();
    $pzarc_defaults[ 'styling' ][ 'blueprints' ] = array();
    $pzarc_defaults[ 'styling' ][ 'content' ]    = array();

    // Options defaults

    $pzarc_defaults[ 'options' ] = array();

    return $pzarc_defaults;
  }