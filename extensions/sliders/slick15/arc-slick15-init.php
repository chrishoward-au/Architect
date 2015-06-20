<?php
/**
 * Project pizazzwp-architect.
 * File: arc-slick15-init.php
 * User: chrishoward
 * Date: 16/05/15
 * Time: 11:58 AM
 */

  $settings = array(
    'name'=>'slick',
      'admin'  => PZARC_PLUGIN_PATH . '/extensions/sliders/slick15/arc-slick15-admin.php',
      'public' => PZARC_PLUGIN_PATH . '/extensions/sliders/slick15/arc-slick15-public.php'
  );
  $registry = arc_Registry::getInstance();
  $registry->set( 'slider_types', $settings );
