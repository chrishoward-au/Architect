<?php
/**
 * Project pizazzwp-architect.
 * File: arc-slick-init.php
 * User: chrishoward
 * Date: 16/05/15
 * Time: 11:58 AM
 */

  $settings = array(
    'name'=>'slick',
      'admin'  => PZARC_PLUGIN_PATH . '/extensions-inc/sliders/slick/arc-slick-admin.php',
      'public' => PZARC_PLUGIN_PATH . '/extensions-inc/sliders/slick/arc-slick-public.php'
  );
  $registry = arc_Registry::getInstance();
  $registry->set( 'slider_types', $settings );
