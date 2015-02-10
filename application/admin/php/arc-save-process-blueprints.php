<?php
  /**
   * Create the Blueprint CSS
   *
   * @param $pzarc_blueprints
   * @param $pzarc_contents
   * @param $postid
   * @return string
   *
   *
   */


  function pzarc_create_blueprint_css($pzarc_blueprints, $pzarc_contents, $postid)
  {

    global $_architect;
    global $_architect_options;
//    // Need to create the file contents
//    // For each field in stylings, create css
    $nl = "\n";
    $pzarc_contents .= '/* This is the css for blueprint ' . $postid . ' ' . $pzarc_blueprints[ '_blueprints_short-name' ] . '*/' . $nl;
    $pzarc_bp_css = array();


    // Process the sections
    for ($i = 0; $i < 3; $i++) {
      $pzarc_bp_css[ $i ] = pzarc_process_bp_sections($pzarc_blueprints, $i, $nl, $_architect_options);
    }
    $specificity_class = 'body.pzarchitect ';

    $pzarc_contents .= $pzarc_bp_css[ 0 ][ 0 ] . $pzarc_bp_css[ 1 ][ 0 ] . $pzarc_bp_css[ 2 ][ 0 ] . $pzarc_bp_css[ 0 ][ 1 ] . $pzarc_bp_css[ 1 ][ 1 ] . $pzarc_bp_css[ 2 ][ 1 ];

    foreach ($pzarc_blueprints as $key => $value) {


      // First off process the styling settings, which should be automatable
      if (substr_count($key, '_blueprints_styling_') === 1 && !empty($_architect_options[ 'architect_enable_styling' ])) {
        $bpkeys            = array();
        $bpkey             = str_replace('_blueprints_styling_', '', $key);
        $bpkeys[ 'style' ] = substr($bpkey, strrpos($bpkey, "-") + 1);;
        $bpkeys[ 'id' ] = substr($bpkey, 0, strrpos($bpkey, "-"));
        if ($bpkeys[ 'id' ] === 'pzarc-navigator' && $pzarc_blueprints[ '_blueprints_navigator' ] === 'thumbs') {
          // UGH! Need to hack this for when is thumb nav
          $bpkeys[ 'id' ] = 'arc-slider-nav';
        }
        if ('blueprint-custom' === $bpkeys[ 'id' ]) {
          $pzarc_contents .= $value;
        }
        if (!in_array($bpkeys[ 'id' ], array('blueprint-custom', 'blueprints-load', 'blueprints-section'))) {

          // Filter out old selector names hanging arouind in existing bblueprints
          if (isset($_architect[ 'architect_config_' . $bpkeys[ 'id' ] . '-selectors' ])) {
            $bpkeys[ 'classes' ] = (is_array($_architect[ 'architect_config_' . $bpkeys[ 'id' ] . '-selectors' ]) ? $_architect[ 'architect_config_' . $bpkeys[ 'id' ] . '-selectors' ] : array('0' => $_architect[ 'architect_config_' . $bpkeys[ 'id' ] . '-selectors' ]));

            foreach ($bpkeys[ 'classes' ] as $k => $v) {
              switch (true) {
                case ('.pzarc-sections_{shortname}' === $v):
                  $bpkeys[ 'classes' ][ $k ] = str_replace('{shortname}', $pzarc_blueprints[ '_blueprints_short-name' ], $v);
                  break;
                case ('.pzarc-blueprint_{shortname}' === $v):
                  $bpkeys[ 'classes' ][ $k ] = '';
                  break;
              }
            }
            $pzarc_contents .= pzarc_get_styling('blueprint',
                                                 $bpkeys,
                                                 $value,
                                                 $specificity_class.'.pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ],
                                                 $bpkeys[ 'classes' ]);
          }
        }
      }
    }
    switch ($pzarc_blueprints[ '_blueprints_blueprint-align' ]) {
      case 'right':
        $bp_align = 'right:calc(-100% + '.$pzarc_blueprints[ '_blueprints_blueprint-width' ]['width'].');';
        break;
      case 'center':
        $bp_align = 'margin-left:auto;margin-right:auto;';
        break;
      case 'left':
      default:
        $bp_align = 'left:0;';
        break;

    }
    $pzarc_contents .= $specificity_class.'.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' {max-width:' . $pzarc_blueprints[ '_blueprints_blueprint-width' ][ 'width' ] . ';' . $bp_align . '}' . $nl;

    /** Vertical nav styling  */
    $pzarc_vert_width = str_replace('%', '', $pzarc_blueprints[ '_blueprints_navigator-vertical-width' ][ 'width' ]);

    if (('slider' === $pzarc_blueprints[ '_blueprints_section-0-layout-mode' ] || 'tabbed' === $pzarc_blueprints[ '_blueprints_section-0-layout-mode' ]) && 'none' !== $pzarc_blueprints[ '_blueprints_navigator' ]) {

      $nav_class = ($pzarc_blueprints[ '_blueprints_navigator' ] === 'thumbs' ? ' .arc-slider-nav' : ' .pzarc-navigator');

      //TODO: when this is vertical, need to test using absolute and top 0, bottom 0 to fill it.
      if (!in_array($pzarc_blueprints[ '_blueprints_navigator' ], array('thumbs',
                                                                        'none')) && 'left' === $pzarc_blueprints[ '_blueprints_navigator-position' ]
      ) {

        $pzarc_contents .= $specificity_class.'.pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . $nav_class . ' {width: ' . $pzarc_vert_width . '%;float:left;}'.
            $specificity_class.'.pzarc-sections_' . $pzarc_blueprints[ '_blueprints_short-name' ] . '{float:right; width:' . (100 - $pzarc_vert_width) . '%; }'.
            $specificity_class.'.pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . '.nav-navigator button.pager.arrow-left {left:' . ($pzarc_vert_width + 1) . '%;}
      ';
      }
      if (!in_array($pzarc_blueprints[ '_blueprints_navigator' ], array('thumbs',
                                                                        'none')) && 'right' === $pzarc_blueprints[ '_blueprints_navigator-position' ]
      ) {

        $pzarc_contents .= $specificity_class.'.pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . $nav_class . ' {width: ' . $pzarc_vert_width . '%;float:right;}'.
            $specificity_class.'.pzarc-sections_' . $pzarc_blueprints[ '_blueprints_short-name' ] . '{float:left; width:' . (100 - $pzarc_vert_width) . '%; }'.
            $specificity_class.'.pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . '.nav-navigator button.pager.arrow-right {right:' . ($pzarc_vert_width + 1) . '%;}
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
   *   */
  function pzarc_process_bp_sections(&$pzarc_blueprints, $i, $nl, &$_architect_options)
  {
    if (empty($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ])) {
      return array(null, null);
    }
    $panel_id = pzarc_convert_name_to_id($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ]);
    $specificity_class = 'body.pzarchitect ';

    $pzarc_panels         = get_post_meta($panel_id);
    $sections_class       = $specificity_class.'.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' > .pzarc-sections  .pzarc-section_' . ($i + 1);
    $panels_class         = $sections_class . '.pzarc-section-using-panel_' . $pzarc_panels[ '_panels_settings_short-name' ][ 0 ] . ' > .pzarc-panel';
    $pzarc_import_css     = '';
    $pzarc_mediaq_css     = '';
    $pzarc_css            = '';
    $pzarc_sections_align = '';
    if (!empty($pzarc_blueprints[ '_blueprints_section-' . $i . '-panel-layout' ])) {

//      $hmargin = str_replace('%', '', $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-right' ]);
//      $hmargin = $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-right' ];
      $hmargin_value = pzarc_maths_sum(array($pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-left' ],
                                             $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-right' ]));

      $hmargin = ($hmargin_value[ 'result' ] / 2) . $hmargin_value[ 'type' ];

      $em_width[ 1 ] = (str_replace('px', '', $_architect_options[ 'architect_breakpoint_1' ][ 'width' ]) / 16);
      $em_width[ 2 ] = (str_replace('px', '', $_architect_options[ 'architect_breakpoint_2' ][ 'width' ]) / 16);

      /** Large */
      $columns                 = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-1' ]);
      $pzarc_mediaq_opener_css = '@media all and (min-width:' . $em_width[ 1 ] . 'em) {';
      $pzarc_mediaq_css .= pzarc_breakpoint_css($pzarc_mediaq_opener_css, $pzarc_blueprints, $i, $pzarc_mediaq_css, $columns, $panels_class, $hmargin, $nl);


      /** Medium */
      $columns                 = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-2' ]);
      $pzarc_mediaq_opener_css = '@media all and (min-width: ' . $em_width[ 2 ] . 'em) and (max-width: ' . ($em_width[ 1 ] - 0.1) . 'em) {';
      $pzarc_mediaq_css .= pzarc_breakpoint_css($pzarc_mediaq_opener_css, $pzarc_blueprints, $i, $pzarc_mediaq_css, $columns, $panels_class, $hmargin, $nl);

      /** Small */
      $columns                 = intval($pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-3' ]);
      $pzarc_mediaq_opener_css = '@media all and (max-width: ' . ($em_width[ 2 ] - 0.1) . 'em) {';
      $pzarc_mediaq_css .= pzarc_breakpoint_css($pzarc_mediaq_opener_css, $pzarc_blueprints, $i, $pzarc_mediaq_css, $columns, $panels_class, $hmargin, $nl);


      if ($pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ] === '%') {
        $pzarc_width_val = (100 - str_replace($pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ], '', $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'width' ]));
      } else {
        $pzarc_width_val = (str_replace($pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ], '', $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'width' ]));
      }
      switch ($pzarc_blueprints[ '_blueprints_sections-align' . $i ]) {
        case 'left':
          $pzarc_sections_align = 'left:0;';
          break;
        case 'centre':
        case 'center':
          if ($pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ] === '%') {
            $pzarc_sections_align = 'left:' . ($pzarc_width_val / 2) . $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ];
          } else {
            $pzarc_sections_align = 'margin-left:auto;margin-right:auto;';

          }
          break;
        case 'right':
          if ($pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ] === '%') {
            $pzarc_sections_align = 'left:' . $pzarc_width_val . $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ];
          } else {
            $pzarc_sections_align = 'right: calc(-100% + '.$pzarc_width_val.'px);';
          }
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

  function pzarc_breakpoint_css($pzarc_mediaq_opener_css, &$pzarc_blueprints, $i, $pzarc_mediaq_css, $columns, $panels_class, $hmargin, $nl)
  {

    $pzarc_mediaq_css .= $pzarc_mediaq_opener_css;
//  $column_width = (100 - ($hmargin * ($columns - 1))) / $columns.'%';
    // Calc allows us to do crazy maths like 25% -3px
    $column_width = ('0' == $hmargin ? 'calc(100%  / ' . $columns . ')' : 'calc(100% / ' . $columns . ' - ' . $hmargin . ')');
    $pzarc_mediaq_css .= $panels_class . ' {width:' . $column_width . ';margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-bottom' ] . ' ;}';

//    pzdebug($pzarc_mediaq_css);
    //  Don't want gutters here iff using masonry layoutt
    switch ($pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]) {

      case 'masonry':
        $pzarc_mediaq_css .= str_replace('.pzarc-panel', '', $panels_class . ' .gutter-sizer {width: ' . ($hmargin) . ';}');
        $pzarc_mediaq_css .= str_replace('.pzarc-panel', '', $panels_class . ' .grid-sizer { width:' . $column_width . ';}');
        break;

      default:
        $pzarc_mediaq_css .= $panels_class . ':nth-child(n) {margin-right: ' . ($hmargin) . ';}';
        $pzarc_mediaq_css .= $panels_class . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
        break;

    }
    //              $pzarc_contents_css .= $classes . ' {width:' . (($column_width)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins']['margin-bottom'] . '%;}';
//    pzdebug($pzarc_mediaq_css);

    //   $pzarc_mediaq_css .= $panels_class . ' .grid-sizer { width:' . $column_width . ';}';
    $pzarc_mediaq_css .= '}' . $nl;

    return $pzarc_mediaq_css;
  }