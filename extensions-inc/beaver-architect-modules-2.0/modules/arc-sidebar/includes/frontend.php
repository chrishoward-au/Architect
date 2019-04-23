<?php


  if ( ! class_exists( 'Mobile_Detect' ) ) {
    require_once( PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/Mobile-Detect/Mobile_Detect.php' );
  }

  $detect = new Mobile_Detect;
  $orientation = empty($settings->orientation)?'vertical':$settings->orientation;

?>
  <div class="arc-adaptive-sidebar orientation-<?php echo $orientation; ?>"><?php
  // Switch Sidebar depending on device
  switch ( TRUE ) {
    case ( $detect->isMobile() && ! $detect->isTablet() ):
      // Phone
      if ( ! empty( $settings->phone_sidebar ) && $settings->phone_sidebar !=='none') {
        if ( ! dynamic_sidebar( $settings->phone_sidebar ) ) {
          dynamic_sidebar();
        }
      }
      break;
    case ( $detect->isTablet() ):
      // Tablet
      if ( ! empty( $settings->tablet_sidebar ) && $settings->tablet_sidebar !=='none') {
        if ( ! dynamic_sidebar( $settings->tablet_sidebar ) ) {
          dynamic_sidebar();
        }
      }
      break;
    default:
      if ( ! empty( $settings->default_sidebar ) && $settings->default_sidebar !=='none') {
        if ( ! dynamic_sidebar( $settings->default_sidebar ) ) {
          dynamic_sidebar();
        }
      }
      break;
  }
  ?></div><?php

