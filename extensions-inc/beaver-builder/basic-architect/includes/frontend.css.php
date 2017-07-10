<?php
  /**
  *
  */

  if ( ! isset( $GLOBALS[ '_architect_options' ] ) ) {
    $GLOBALS[ '_architect_options' ] = get_option( '_architect_options', array() );
  }
  global $_architect_options;

  $pz_css = array(
    'defaults'=>array(),
    'medium'=>array(),
    'small'=>array(),
  );


  $em_width[1] = (str_replace('px', '', $_architect_options['architect_breakpoint_1']['width']) / 16);
  $em_width[2] = (str_replace('px', '', $_architect_options['architect_breakpoint_2']['width']) / 16);

  foreach ($settings as $k => $v ){

    if (strpos( $k,'default__') === 0){
      $pz_css['defaults'][str_replace('default__','',$k)]=$v;
    }

    if (strpos( $k,'medium__') === 0){
      $pz_css['medium'][str_replace('medium__','',$k)]=$v;
    }

    if (strpos( $k,'small__') === 0){
      $pz_css['small'][str_replace('small__','',$k)]=$v;
    }

  }


  if((!empty($settings->blueprint_default) && $settings->blueprint_default !== 'none') || in_array($settings->custom_styles,array('defaults','all','defaults_medium','defaults_small'))) {
    echo '/* Architect Module - Default devices Blueprint:'.$settings->blueprint_default.'  */';
    foreach ($pz_css['defaults'] as $k => $v) {
      if ($k !== 'custom_css') {
        $pz_generated_css = pzarc_generate_beaver_css($v);
        if (!empty($pz_generated_css)) {
          echo '.fl-node-'.$id .' #pzarc-blueprint_'.$settings->blueprint_default.' .pzarc-panel_'.$settings->blueprint_default.' '.$k .'{';
          echo $pz_generated_css;
          echo '}';
        }
      } else {
        if (!empty($v) && trim($v) != '.pzarc-blueprint {}') {
          echo '.fl-node-'.$id .' #pzarc-blueprint_'.$settings->blueprint_default.' '.$v;
        }
      }
    }
  }

  if((!empty($settings->blueprint_medium) && $settings->blueprint_medium !== 'none') || in_array($settings->custom_styles,array('medium','all','defaults_medium','medium_small'))) {
    if ((empty($settings->blueprint_medium) || $settings->blueprint_medium === 'none') && (!empty($settings->blueprint_default) && $settings->blueprint_default !== 'none')) {
      $settings->blueprint_medium = $settings->blueprint_default;
    } else {
      // This doesn't matter coz it won't display anything anyway.
      $settings->blueprint_medium = 'no_blueprint';
    }
      echo '/* Architect Module - Medium devices Blueprint:'.$settings->blueprint_medium.'  */';
    echo '@media all and (min-width: ' . $em_width[2] . 'em) and (max-width: ' . ($em_width[1] - 0.1) . 'em) {';
      foreach ($pz_css['medium'] as $k => $v) {
        if ($k !== 'custom_css') {
          $pz_generated_css = pzarc_generate_beaver_css($v);
          if (!empty($pz_generated_css)) {
            echo '.fl-node-'.$id .' #pzarc-blueprint_'.$settings->blueprint_medium.' .pzarc-panel_'.$settings->blueprint_medium.' '.$k .'{';
            echo $pz_generated_css;
            echo '}';
          }
        } else {
          if (!empty($v) && trim($v) != '.pzarc-blueprint {}') {
            echo '.fl-node-'.$id .' #pzarc-blueprint_'.$settings->blueprint_medium.' '.$v;
          }
        }
      }
    echo '}';
  }

  if((!empty($settings->blueprint_small) && $settings->blueprint_small !== 'none') || in_array($settings->custom_styles,array('small','all','medium_small','defaults_small'))) {
    if ((empty($settings->blueprint_small) || $settings->blueprint_small === 'none') && (!empty($settings->blueprint_default) && $settings->blueprint_default !== 'none')) {
      $settings->blueprint_small = $settings->blueprint_default;
    } else {
      // This doesn't matter coz it won't display anything anyway.
      $settings->blueprint_small = 'no_blueprint';
    }
    echo '/* Architect Module - Small devices Blueprint:'.$settings->blueprint_small.'  */';
   echo '@media all and (max-width: ' . ($em_width[2] - 0.1) . 'em) {';

    foreach ($pz_css['small'] as $k => $v) {
      if ($k !== 'custom_css') {
        $pz_generated_css = pzarc_generate_beaver_css($v);
        if (!empty($pz_generated_css)) {
          echo '.fl-node-'.$id .' #pzarc-blueprint_'.$settings->blueprint_small.' .pzarc-panel_'.$settings->blueprint_small.' '.$k .'{';
          echo $pz_generated_css;
          echo '}';
        }
      } else {
        if (!empty($v) && trim($v) != '.pzarc-blueprint {}') {
          echo '.fl-node-'.$id .' #pzarc-blueprint_'.$settings->blueprint_small.' '.$v;
        }
      }
    }
    echo '}';

  }

