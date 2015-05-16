<?php
  /**
   * Project pizazzwp-architect.
   * File: arc-slick-init.php
   * User: chrishoward
   * Date: 16/05/15
   * Time: 12:03 PM
   */

  $settings = array(
    'name'   => 'slick',
    'admin'  => PZARC_PLUGIN_PATH . '/extensions/sliders/slick/arc-slick-admin.php',
    'public' => PZARC_PLUGIN_PATH . '/extensions/sliders/slick/arc-slick-public.php'
  );
  $registry = arc_Registry::getInstance();
  $registry->set( 'slider_types', $settings );
