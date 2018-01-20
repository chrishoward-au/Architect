<?php
  /**
  *
  */

  $settings_array= (array)$settings;
  echo '/* Any Field CSS */';

  $arc_css_unique=empty($settings_array['name'])?'':'-'.sanitize_title_with_dashes($settings_array['name']);

  if ($settings_array['.arcaf-anyfield']->enable_styling=='on') {
    echo '.arcaf-anyfield'.$arc_css_unique.' {'.pzarc_generate_beaver_css($settings_array['.arcaf-anyfield']).'}';
  }
  if ($settings_array['.arcaf-anyfield a']->enable_styling=='on') {
   echo '.arcaf-anyfield'.$arc_css_unique.' a {'.pzarc_generate_beaver_css($settings_array['.arcaf-anyfield a']).'}';
  }
  if ($settings_array['.arcaf-anyfield a:hover']->enable_styling=='on') {
   echo '.arcaf-anyfield'.$arc_css_unique.' a:hover {'.pzarc_generate_beaver_css($settings_array['.arcaf-anyfield a:hover']).'}';
  }
  if ($settings_array['.arcaf-presuff-image.prefix-image']->enable_styling=='on') {
   echo '.arcaf-anyfield'.$arc_css_unique.' .arcaf-presuff-image.prefix-image {'.pzarc_generate_beaver_css($settings_array['.arcaf-presuff-image.prefix-image']).'}';
  }
  if ($settings_array['.arcaf-presuff-image.suffix-image']->enable_styling=='on') {
   echo '.arcaf-anyfield'.$arc_css_unique.' .arcaf-presuff-image.suffix-image {'.pzarc_generate_beaver_css($settings_array['.arcaf-presuff-image.suffix-image']).'}';
  }
  if ($settings_array['.arcaf-prefix-text']->enable_styling=='on') {
   echo '.arcaf-anyfield'.$arc_css_unique.' .arcaf-prefix-text {'.pzarc_generate_beaver_css($settings_array['.arcaf-prefix-text']).'}';
  }
  if ($settings_array['.arcaf-suffix-text']->enable_styling=='on') {
   echo '.arcaf-anyfield'.$arc_css_unique.' .arcaf-suffix-text {'.pzarc_generate_beaver_css($settings_array['.arcaf-suffix-text']).'}';
  }
