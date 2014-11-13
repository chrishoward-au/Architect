<?php

  function pzarc_create_blueprint_css($pzarc_blueprints, $pzarc_contents, $postid)
  {

    global $_architect;
    global $_architect_options;
//    // var_dump($_architect_options);
//    pzarc_set_defaults();
//    $defaults = $_architect[ 'defaults' ];
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


    //   var_dump($pzarc_blueprints);
    foreach ($pzarc_blueprints as $key => $value) {


      // First off process the styling settings, which should be automatable

      if (substr_count($key, '_blueprints_styling_') === 1 && !empty($_architect_options[ 'architect_enable_styling' ])) {
        $bpkeys            = array();
        $bpkey             = str_replace('_blueprints_styling_', '', $key);
        $bpkeys[ 'style' ] = substr($bpkey, strrpos($bpkey, "-") + 1);;
        $bpkeys[ 'id' ] = substr($bpkey, 0, strrpos($bpkey, "-"));

        $pzarc_contents .= pzarc_get_styling('blueprint', $bpkeys, $value, '.pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ]);
      }

    }

    $pzarc_contents .= 'body.pzarchitect .pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' {max-width:' . $pzarc_blueprints[ '_blueprints_blueprint-width' ][ 'width' ] . ';margin-left:auto;margin-right:auto}' . $nl;

    /** Vertical nav styling  */
    $pzarc_vert_width = str_replace('%', '', $pzarc_blueprints[ '_blueprints_navigator-vertical-width' ][ 'width' ]);

    if ('navigator' === $pzarc_blueprints[ '_blueprints_navigation' ] && 'none' !== $pzarc_blueprints[ '_blueprints_navigator' ]) {

      if ('left' === $pzarc_blueprints[ '_blueprints_navigator-position' ]) {

        $pzarc_contents .= '
       .pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' .pzarc-navigator{width: ' . $pzarc_vert_width . '%;float:left;}
       .pzarc-sections_' . $pzarc_blueprints[ '_blueprints_short-name' ] . '{float:right; width:' . (100 - $pzarc_vert_width) . '%; }
       .pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . '.nav-navigator button.pager.arrow-left {left:' . ($pzarc_vert_width + 1) . '%;}
      ';
      }
      if ('right' === $pzarc_blueprints[ '_blueprints_navigator-position' ]) {

        $pzarc_contents .= '
       .pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' .pzarc-navigator{width: ' . $pzarc_vert_width . '%;float:right;}
       .pzarc-sections_' . $pzarc_blueprints[ '_blueprints_short-name' ] . '{float:left; width:' . (100 - $pzarc_vert_width) . '%; }
       .pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . '.nav-navigator button.pager.arrow-right {right:' . ($pzarc_vert_width + 1) . '%;}
      ';
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
    if (empty($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ])) {
      return array(null, null);
    }
    $panel_id = pzarc_convert_name_to_id($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ]);

    $pzarc_panels     = get_post_meta($panel_id);
    $sections_class   = 'body.pzarchitect .pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' .pzarc-section_' . ($i + 1);
    $panels_class     = $sections_class . '.pzarc-section-using-panel_' . $pzarc_panels[ '_panels_settings_short-name' ][ 0 ] . ' .pzarc-panel';
    $pzarc_import_css = '';
    $pzarc_mediaq_css = '';
    $pzarc_css        = '';
    if (!empty($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ])) {
      // var_dump($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ]);

      $filename = PZARC_CACHE_URL . '/pzarc-panels-layout-' . $pzarc_panels[ '_panels_settings_short-name' ][ 0 ] . '.css';

      /** TODO Need to insert these not import. However, that means we need to track what blueprints contain what panels, so can update. Or update all blueprints on panel save. blergh! Or could we move it to the front end?*/
      /** What if we created an array of all the css and saved it in an option. Then we could replace the relevent key and at the end of the save process right the lot to a single file*/

      global $wp_filesystem;
      if (false === ($pzarc_import_css = $wp_filesystem->get_contents($filename, FS_CHMOD_FILE))) {
        echo 'error reading css file for Panel ' . $pzarc_panels[ '_panels_settings_short-name' ][ 0 ];
      }

//      $pzarc_import_css .= '@import url("' . PZARC_CACHE_URL . '/pzarc-panels-layout-' . $pzarc_panels[ '_panels_settings_short-name' ][ 0 ] . '.css");' . $nl;


      $hmargin = str_replace('%', '', $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-right' ]);

      $em_width[ 1 ] = (str_replace('px', '', $_architect_options[ 'architect_breakpoint_1' ][ 'width' ]) / 16);
      $em_width[ 2 ] = (str_replace('px', '', $_architect_options[ 'architect_breakpoint_2' ][ 'width' ]) / 16);

      // Large
      $columns = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-1' ]);
      $pzarc_mediaq_css .= '@media all and (min-width:' . $em_width[ 1 ] . 'em) {';
      $pzarc_mediaq_css .= $panels_class . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-bottom' ] . ' ;}';

      //  Don't want gutters here iff using masonry layoutt
      if ('basic' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]) {
        $pzarc_mediaq_css .= $panels_class . ':nth-child(n) {margin-right: ' . ($hmargin) . '%;}';
        $pzarc_mediaq_css .= $panels_class . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
      } elseif ('masonry' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]) {
        $pzarc_mediaq_css .= str_replace('.pzarc-panel', '', $panels_class . ' .gutter-sizer{width: ' . ($hmargin) . '%;}');
        $column_width = (100 - ($hmargin * ($columns - 1))) / $columns;
        $pzarc_mediaq_css .= str_replace('.pzarc-panel', '', $panels_class . ' .grid-sizer { width:' . $column_width . '%;}');
      }
      //              $pzarc_contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins']['margin-bottom'] . '%;}';
      $pzarc_mediaq_css .= '}' . $nl;


      // Medium
      $columns = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-2' ]);
      $pzarc_mediaq_css .= '@media all and (min-width: ' . $em_width[ 2 ] . 'em) and (max-width: ' . ($em_width[ 1 ] - 0.1) . 'em) {';
      $pzarc_mediaq_css .= $panels_class . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-bottom' ] . ' ;}';

      //  Don't want gutters here iff using masonry layoutt
      if ('basic' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]) {
        $pzarc_mediaq_css .= $panels_class . ':nth-child(n) {margin-right: ' . ($hmargin) . '%;}';
        $pzarc_mediaq_css .= $panels_class . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
      } elseif ('masonry' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]) {
        $pzarc_mediaq_css .= str_replace('.pzarc-panel', '', $panels_class . ' .gutter-sizer {width: ' . ($hmargin) . '%;}');
        $column_width = (100 - ($hmargin * ($columns - 1))) / $columns;
        $pzarc_mediaq_css .= str_replace('.pzarc-panel', '', $panels_class . ' .grid-sizer { width:' . $column_width . '%;}');
      }
//              $pzarc_contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins']['margin-bottom'] . '%;}';
      $pzarc_mediaq_css .= $panels_class . ' .grid-sizer { width:' . ((100 - ($hmargin * ($columns - 1))) / $columns) . '%;}';
      $pzarc_mediaq_css .= '}' . $nl;


      // Small
      $columns = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-3' ]);
      $pzarc_mediaq_css .= '@media all and (max-width: ' . ($em_width[ 2 ] - 0.1) . 'em) {';
      $pzarc_mediaq_css .= $panels_class . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-bottom' ] . ' ;}';

      //  Don't want gutters here iff using masonry layoutt
      if ('basic' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]) {
        $pzarc_mediaq_css .= $panels_class . ':nth-child(n) {margin-right: ' . ($hmargin) . '%;}';
        $pzarc_mediaq_css .= $panels_class . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
      } elseif ('masonry' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]) {
        $pzarc_mediaq_css .= str_replace('.pzarc-panel', '', $panels_class . ' .gutter-sizer {width: ' . ($hmargin) . '%;}');
        $column_width = (100 - ($hmargin * ($columns - 1))) / $columns;
        $pzarc_mediaq_css .= str_replace('.pzarc-panel', '', $panels_class . ' .grid-sizer { width:' . $column_width . '%;}');
      }
      //              $pzarc_contents_css .= $classes . ' {width:' . (((100 - ($hmargin * ($columns - 1))) / $columns)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins']['margin-bottom'] . '%;}';
      $pzarc_mediaq_css .= $panels_class . ' .grid-sizer { width:' . ((100 - ($hmargin * ($columns - 1))) / $columns) . '%;}';
      $pzarc_mediaq_css .= '}' . $nl;

      $pzarc_width_val = (100 - str_replace($pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ], '', $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'width' ]));
      switch ($pzarc_blueprints[ '_blueprints_sections-align' . $i ]) {
        case 'left':
          $pzarc_sections_align = 'left:0;';
          break;
        case 'centre':
        case 'center':
          $pzarc_sections_align = 'left:' . ($pzarc_width_val / 2) . '%;';
          break;
        case 'right':
          $pzarc_sections_align = 'left:' . $pzarc_width_val . '%;';
          break;
      }
      $pzarc_css = $pzarc_mediaq_css;
      $pzarc_css .= $sections_class . ' {width:' . $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'width' ] . ';' . $pzarc_sections_align . '}';

      // Can't do this!! OTherwise would have to regenerate all blueprints on panel save
////        // TODO: do we have to use the bg image height instead if it is set??
//      var_dump($pzarc_panels,$pzarc_panels[ '_panels_settings_panel-height' ][ 'height' ],$pzarc_panels[ '_panels_settings_panel-height-type' ]);
//        $pzarc_panel_height = $pzarc_panels[ '_panels_settings_panel-height' ][ 'height' ];
//        $pzarc_height_type = (empty($pzarc_panels[ '_panels_settings_panel-height-type' ])?'min-height':$pzarc_panels[ '_panels_settings_panel-height-type' ]);
//        $pzarc_mediaq_css .= '.pzarchitect .arc-slider-container.arc-slider-container-' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' {'.$pzarc_panels[ '_panels_settings_panel-height-type' ].':' . $pzarc_panel_height . ';}' . $nl;


    }

    return array($pzarc_import_css, $pzarc_css);
  }

