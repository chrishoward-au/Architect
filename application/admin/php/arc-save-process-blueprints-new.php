<?php

  function pzarc_create_blueprint_css($pzarc_blueprints, $pzarc_contents, $postid)
  {

    global $pzarchitect;
    global $_architect_options;
//    // var_dump($_architect_options);
//    pzarc_set_defaults();
//    $defaults = $pzarchitect[ 'defaults' ];
//    // Need to create the file contents
//    // For each field in stylings, create css
//    $pzarc_contents = '';
//
    $nl = "\n";

    $pzarc_contents .= '/* This is the css for blueprint ' . $postid . ' ' . $pzarc_blueprints[ '_blueprints_short-name' ] . '*/' . $nl;
    $pzarc_bp_css = array();

//        $keyss = array_keys($pzarc_blueprints);
//        foreach ($keyss as $k => $v) {
//          $keyz[ $k ] = explode('_', $v);
//          if (empty($keyz[ $k ][ 0 ])) {
//            unset($keyz[ $k ][ 0 ]);
//          }
//        }
//        //    var_dump($keyz);

    // Process the sections
    for ($i = 0; $i < 3; $i++) {
      $pzarc_bp_css[ $i ] = pzarc_process_bp_sections($pzarc_blueprints, $i, $nl, $_architect_options);

    }
    $pzarc_contents .= $pzarc_bp_css[ 0 ][ 0 ] . $pzarc_bp_css[ 1 ][ 0 ] . $pzarc_bp_css[ 2 ][ 0 ] . $pzarc_bp_css[ 0 ][ 1 ] . $pzarc_bp_css[ 1 ][ 1 ] . $pzarc_bp_css[ 2 ][ 1 ];
    $sectionsclasses = '.pzarchitect .pzarc-sections_' . $pzarc_blueprints[ '_blueprints_short-name' ];
    $bpclasses       = '.pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ];

    //   var_dump($pzarc_blueprints);
    foreach ($pzarc_blueprints as $key => $value) {


      // First off process the styling settings, which should be automatable

      if (substr_count($key, '_blueprints_styling_') === 1 && !empty($_architect_options[ 'architect_enable_styling' ])) {
        $bpkeys            = array();
        $bpkey             = str_replace('_blueprints_styling_', '', $key);
        $bpkeys[ 'style' ] = substr($bpkey, strrpos($bpkey, "-") + 1);;
        $bpkeys[ 'id' ] = substr($bpkey, 0, strrpos($bpkey, "-"));

        $pzarc_contents .= pzarc_get_styling('blueprint', $bpkeys, $value, $bpclasses);
      }

    }

    return $pzarc_contents;
  }


  /**
   * @param $pzarc_blueprints
   * @param $i
   * @param $nl
   * @param $_architect_options
   * @return string
   *
   * Sets up import of panels CSS
   */
  function pzarc_process_bp_sections(&$pzarc_blueprints, $i, $nl, &$_architect_options)
  {
    $panel_id         = $pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ];
    $pzarc_panels     = get_post_meta($panel_id);
    $classes          = '.pzarchitect .pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' .pzarc-section_' . ($i + 1) . '.pzarc-section-using-panel_' . $pzarc_panels[ '_panels_settings_short-name' ][ 0 ] . ' .pzarc-panel';
    $pzarc_import_css = '';
    $pzarc_mediaq_css = '';

    if (!empty($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ])) {
      // var_dump($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ]);
      $pzarc_import_css .= '@import url("' . PZARC_CACHE_URL . '/pzarc-panels-layout-' . $panel_id . '-' . $pzarc_panels[ '_panels_settings_short-name' ][ 0 ] . '.css");' . $nl;
      $hmargin = $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-right' ];

      $em_width[ 1 ] = (str_replace('px', '', $_architect_options[ 'architect_breakpoint_1' ][ 'width' ]) / 16);
      $em_width[ 2 ] = (str_replace('px', '', $_architect_options[ 'architect_breakpoint_2' ][ 'width' ]) / 16);

      // Large
      $columns = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-1' ]);
      $pzarc_mediaq_css .= '@media all and (min-width:' . $em_width[ 1 ] . 'em) {';
      $pzarc_mediaq_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-bottom' ] . ' ;}';

      //  Don't want gutters here iff using masonry layoutt
      if ('basic' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]) {
        $pzarc_mediaq_css .= $classes . ':nth-child(n) {margin-right: ' . ($hmargin) . '%;}';
        $pzarc_mediaq_css .= $classes . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
      } elseif ('masonry'=== $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]){
        $pzarc_mediaq_css .= str_replace('.pzarc-panel','', $classes.'.gutter-sizer:{width: ' . ($hmargin) . ' ;}');
        $pzarc_mediaq_css .= str_replace(' .pzarc-panel','', $classes . ' .grid-sizer { width:' . (100 / $columns) . '%;}');
      }
      //              $pzarc_contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins']['margin-bottom'] . '%;}';
      $pzarc_mediaq_css .= '}' . $nl;


      // Medium
      $columns = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-2' ]);
      $pzarc_mediaq_css .= '@media all and (min-width: ' . $em_width[ 2 ] . 'em) and (max-width: ' . ($em_width[ 1 ] - 0.1) . 'em) {';
      $pzarc_mediaq_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-bottom' ] . ' ;}';

      //  Don't want gutters here iff using masonry layoutt
      if ('basic' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]) {
        $pzarc_mediaq_css .= $classes . ':nth-child(n) {margin-right: ' . ($hmargin) . '%;}';
        $pzarc_mediaq_css .= $classes . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
      } elseif ('masonry'=== $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]){
        $pzarc_mediaq_css .= str_replace('.pzarc-panel','', $classes.'.gutter-sizer:{width: ' . ($hmargin) . ' ;}');
        $pzarc_mediaq_css .= str_replace(' .pzarc-panel','', $classes . ' .grid-sizer { width:' . (100 / $columns) . '%;}');
      }
//              $pzarc_contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins']['margin-bottom'] . '%;}';
      $pzarc_mediaq_css .= $classes . ' .grid-sizer { width:' . (100 / $columns) . '%;}';
      $pzarc_mediaq_css .= '}' . $nl;


      // Small
      $columns = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-3' ]);
      $pzarc_mediaq_css .= '@media all and (max-width: ' . ($em_width[ 2 ] - 0.1) . 'em) {';
      $pzarc_mediaq_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-bottom' ] . ' ;}';

      //  Don't want gutters here iff using masonry layoutt
      if ('basic' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]) {
        $pzarc_mediaq_css .= $classes . ':nth-child(n) {margin-right: ' . ($hmargin) . '%;}';
        $pzarc_mediaq_css .= $classes . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
      } elseif ('masonry'=== $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]){
        $pzarc_mediaq_css .= str_replace('.pzarc-panel','', $classes.'.gutter-sizer:{width: ' . ($hmargin) . ' ;}');
        $pzarc_mediaq_css .= str_replace(' .pzarc-panel','', $classes . ' .grid-sizer { width:' . (100 / $columns) . '%;}');
      }
      //              $pzarc_contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins']['margin-bottom'] . '%;}';
      $pzarc_mediaq_css .= $classes . ' .grid-sizer { width:' . (100 / $columns) . '%;}';
      $pzarc_mediaq_css .= '}' . $nl;


      // Can't do this!! OTherwise would have to regenerate all blueprints on panel save
////        // TODO: do we have to use the bg image height instead if it is set??
//      var_dump($pzarc_panels,$pzarc_panels[ '_panels_settings_panel-height' ][ 'height' ],$pzarc_panels[ '_panels_settings_panel-height-type' ]);
//        $pzarc_panel_height = $pzarc_panels[ '_panels_settings_panel-height' ][ 'height' ];
//        $pzarc_height_type = (empty($pzarc_panels[ '_panels_settings_panel-height-type' ])?'min-height':$pzarc_panels[ '_panels_settings_panel-height-type' ]);
//        $pzarc_mediaq_css .= '.pzarchitect .arc-slider-container.arc-slider-container-' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' {'.$pzarc_panels[ '_panels_settings_panel-height-type' ].':' . $pzarc_panel_height . ';}' . $nl;


    }

    return array($pzarc_import_css, $pzarc_mediaq_css);
  }

