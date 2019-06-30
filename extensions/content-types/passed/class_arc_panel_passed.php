<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 29/04/2014
   * Time: 12:16 PM
   */

  // This is used for Blueprints calling blueprints... so the content of the first blueprint calls it. Possible usage is ACF gallery fields and repeater fields

  class arc_Panel_passed extends arc_Panel_Generic
  {
    public function __construct(&$build) {

      parent::__construct( $build );
    }

  }

