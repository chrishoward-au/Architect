<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_class_generic.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 9:39 PM
   */
  class arc_content_generic extends arc_set_data
  {

    protected function __construct()
    {

      $registry = arc_Registry::getInstance();
      $registry->set('content_source', array('generic'=>plugin_dir_path(__FILE__)));

    }
  }

