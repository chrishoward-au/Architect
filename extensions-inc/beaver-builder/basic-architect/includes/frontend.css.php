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

  $global_settings = FLBuilderModel::get_global_settings();

  if (!empty($settings->medium__breakpoint) && $settings->medium__breakpoint == 'architect') {
    $em_width[1] = (str_replace('px', '', $_architect_options['architect_breakpoint_1']['width']) / 16);
  } else {
    $em_width[1] = (str_replace('px', '', $global_settings->medium_breakpoint) / 16);
  }

  if (!empty($settings->small__breakpoint) && $settings->small__breakpoint == 'architect') {
    $em_width[2] = (str_replace('px', '', $_architect_options['architect_breakpoint_2']['width']) / 16);
  } else {
    $em_width[2] = (str_replace('px', '', $global_settings->responsive_breakpoint) / 16);
  }

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
    pz_render_module_css($settings->blueprint_default,$pz_css,$id,'/* Architect Module - Default devices Blueprint:' . $settings->blueprint_default . '  */');
  }

  if((!empty($settings->blueprint_tablet) && $settings->blueprint_tablet !== 'none') || in_array($settings->custom_styles,array('medium','all','defaults_medium','medium_small'))) {
    if ((empty($settings->blueprint_tablet) || $settings->blueprint_tablet === 'none')) {
      $settings->blueprint_tablet = $settings->blueprint_default;
    }
    pz_render_module_css($settings->blueprint_tablet,$pz_css,$id,'/* Architect Module - Medium devices Blueprint:' . $settings->blueprint_medium . '  */');
  }

  if((!empty($settings->blueprint_phone) && $settings->blueprint_phone !== 'none') || in_array($settings->custom_styles,array('small','all','medium_small','defaults_small'))) {
    if ((empty($settings->blueprint_phone) || $settings->blueprint_phone === 'none') ) {
      $settings->blueprint_phone = $settings->blueprint_default;
    }
    pz_render_module_css($settings->blueprint_phone,$pz_css,$id,'/* Architect Module - Small devices Blueprint:' . $settings->blueprint_small . '  */');
  }
