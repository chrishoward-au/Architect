<?php
/**
 * Project pizazzwp-architect.
 * File: arc-sudo-init.php
 * User: chrishoward
 * Date: 15/09/15
 * Time: 11:58 AM
 */

  $settings = array(
    'name'=>'sudo',
      'admin'  => PZARC_PLUGIN_PATH . '/extensions/sliders/sudo-slider/arc-sudo-admin.php',
      'public' => PZARC_PLUGIN_PATH . '/extensions/sliders/sudo-slider/arc-sudo-public.php'
  );
  $registry = arc_Registry::getInstance();
  $registry->set( 'slider_types', $settings );
