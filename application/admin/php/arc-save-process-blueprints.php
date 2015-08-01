<?php
  /**
   * Create the Blueprint CSS
   *
   * @param $pzarc_blueprints
   * @param $pzarc_contents
   * @param $postid
   *
   * @return string
   *
   *
   */
  function pzarc_create_blueprint_css( $pzarc_blueprints, $pzarc_contents, $postid ) {

    global $_architect;
    global $_architect_options;

//    // Need to create the file contents
//    // For each field in stylings, create css
    $nl = "\n";
    // $pzarc_contents .= '/* This is the css for blueprint ' . $postid . ' ' . $pzarc_blueprints[ '_blueprints_short-name' ] . '*/' . $nl;
    $pzarc_bp_css = array();

    // Process the sections
    $i = 0;
    $pzarc_contents .= '{{fontface}}';
    global $pzarc_fontface;
    $pzarc_fontface     = '';
    $pzarc_bp_css[ $i ] = pzarc_process_bp_sections( $pzarc_blueprints, $i, $nl, $_architect_options );
    $specificity_class  = '#pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ];

    $pzarc_contents .= $pzarc_bp_css[ $i ];


    foreach ( $pzarc_blueprints as $key => $value ) {


      // First off process the styling settings, which should be automatable
      if ( substr_count( $key, '_blueprints_styling_' ) === 1 && ! empty( $_architect_options[ 'architect_enable_styling' ] ) ) {

        $pzarc_fontface .= strpos( $key, 'font' ) ? pzarc_check_googlefont( $value ) : '';
        $bpkeys            = array();
        $bpkey             = str_replace( '_blueprints_styling_', '', $key );
        $bpkeys[ 'style' ] = substr( $bpkey, strrpos( $bpkey, "-" ) + 1 );;
        $bpkeys[ 'id' ] = substr( $bpkey, 0, strrpos( $bpkey, "-" ) );
        if ( $bpkeys[ 'id' ] === 'pzarc-navigator' && $pzarc_blueprints[ '_blueprints_navigator' ] === 'thumbs' ) {
          // UGH! Need to hack this for when is thumb nav
          $bpkeys[ 'id' ] = 'arc-slider-nav';
        }
        if ( 'blueprint-custom' === $bpkeys[ 'id' ] ) {
          $pzarc_contents .= str_replace( array( 'MYBLUEPRINT', 'MYPANELS'), $specificity_class, $value );
        }
        if ( ! in_array( $bpkeys[ 'id' ], array( 'blueprint-custom', 'blueprints-load', 'blueprints-section' ) ) ) {

          // Filter out old selector names hanging arouind in existing bblueprints
          if ( isset( $_architect[ 'architect_config_' . $bpkeys[ 'id' ] . '-selectors' ] ) ) {
            $bpkeys[ 'classes' ] = ( is_array( $_architect[ 'architect_config_' . $bpkeys[ 'id' ] . '-selectors' ] ) ? $_architect[ 'architect_config_' . $bpkeys[ 'id' ] . '-selectors' ] : array( '0' => $_architect[ 'architect_config_' . $bpkeys[ 'id' ] . '-selectors' ] ) );

            foreach ( $bpkeys[ 'classes' ] as $k => $v ) {
              switch ( true ) {
                case ( '.pzarc-sections_{shortname}' === $v ):
                  $bpkeys[ 'classes' ][ $k ] = str_replace( '{shortname}', $pzarc_blueprints[ '_blueprints_short-name' ], $v );
                  break;
                case ( '.pzarc-blueprint_{shortname}' === $v ):
                  $bpkeys[ 'classes' ][ $k ] = '';
                  break;
              }
            }
            $pzarc_contents .= pzarc_get_styling( 'blueprint',
                                                  $bpkeys,
                                                  $value,
//                                                 $specificity_class.'.pzarchitect.pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ],
                                                  $specificity_class,
                                                  $bpkeys[ 'classes' ] );
          }
        }
      }
    }
    switch ( $pzarc_blueprints[ '_blueprints_blueprint-align' ] ) {
      case 'right':
        $bp_align = 'right:calc(-100% + ' . $pzarc_blueprints[ '_blueprints_blueprint-width' ][ 'width' ] . ');';
        break;
      case 'center':
        $bp_align = 'margin-left:auto;margin-right:auto;';
        break;
      case 'left':
      default:
        $bp_align = 'left:0;';
        break;

    }
    $pzarc_contents .= $specificity_class . ' {max-width:' . $pzarc_blueprints[ '_blueprints_blueprint-width' ][ 'width' ] . ';' . $bp_align . '}' . $nl;

    /** Vertical nav styling  */
    $pzarc_vert_width = str_replace( '%', '', $pzarc_blueprints[ '_blueprints_navigator-vertical-width' ][ 'width' ] );

    if ( ( 'slider' === $pzarc_blueprints[ '_blueprints_section-0-layout-mode' ] || 'tabbed' === $pzarc_blueprints[ '_blueprints_section-0-layout-mode' ] ) && 'none' !== $pzarc_blueprints[ '_blueprints_navigator' ] ) {

      $nav_class = ( $pzarc_blueprints[ '_blueprints_navigator' ] === 'thumbs' ? ' .arc-slider-nav' : ' .pzarc-navigator' );

      //TODO: when this is vertical, need to test using absolute and top 0, bottom 0 to fill it.
      if ( ! in_array( $pzarc_blueprints[ '_blueprints_navigator' ], array(
          'thumbs',
          'none'
        ) ) && 'left' === $pzarc_blueprints[ '_blueprints_navigator-position' ]
      ) {

        $pzarc_contents .= $specificity_class . $nav_class . ' {width: ' . $pzarc_vert_width . '%;float:left;}' .
                           $specificity_class . ' .pzarc-sections_' . $pzarc_blueprints[ '_blueprints_short-name' ] . '{float:right; width:' . ( 100 - $pzarc_vert_width ) . '%; }' .
                           $specificity_class . '.nav-navigator button.pager.arrow-left {left:' . ( $pzarc_vert_width + 1 ) . '%;}
      ';
      }
      if ( ! in_array( $pzarc_blueprints[ '_blueprints_navigator' ], array(
          'thumbs',
          'none'
        ) ) && 'right' === $pzarc_blueprints[ '_blueprints_navigator-position' ]
      ) {

        $pzarc_contents .= $specificity_class . $nav_class . ' {width: ' . $pzarc_vert_width . '%;float:right;}' .
                           $specificity_class . ' .pzarc-sections_' . $pzarc_blueprints[ '_blueprints_short-name' ] . '{float:left; width:' . ( 100 - $pzarc_vert_width ) . '%; }' .
                           $specificity_class . '.nav-navigator button.pager.arrow-right {right:' . ( $pzarc_vert_width + 1 ) . '%;}
      ';
      }
    }
    $pzarc_contents = str_replace( '{{fontface}}', $pzarc_fontface, $pzarc_contents );

    return apply_filters('arc_blueprint_css', $pzarc_contents );
  }


  /**
   * @param $pzarc_blueprints
   * @param $i
   * @param $nl
   * @param $_architect_options
   *
   * @return string
   *
   *   */
  function pzarc_process_bp_sections( &$pzarc_blueprints, $i, $nl, &$_architect_options ) {
//    var_dump(array_keys($pzarc_blueprints));
    $specificity_class    = '#pzarc-blueprint_' . $pzarc_blueprints[ '_blueprints_short-name' ];
    $sections_class       = $specificity_class . ' > .pzarc-sections  .pzarc-section_' . ( $i + 1 );
    $panels_class         = $sections_class . ' .pzarc-panel';
    $pzarc_mediaq_css     = '';
    $pzarc_sections_align = '';

//      $hmargin = str_replace('%', '', $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-right' ]);
//      $hmargin = $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-right' ];
//    $hmargin_value = pzarc_maths_sum( array(
//                                        $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-left' ],
//                                        $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-right' ]
//                                      ) );

    // WHY DID WE DIVIDE BY TWO????
    // WHY DO WE EVEN HAVE THIS???
//    $hmargin = ( $hmargin_value[ 'result' ] / 2 ) . $hmargin_value[ 'type' ];

//    $lmargin = floatval($pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-left' ]);
//    $rmargin = floatval($pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-right' ]);
//    $margin_units = $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ]['units'];

    $em_width[ 1 ] = ( str_replace( 'px', '', $_architect_options[ 'architect_breakpoint_1' ][ 'width' ] ) / 16 );
    $em_width[ 2 ] = ( str_replace( 'px', '', $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] ) / 16 );

    /** Large */
    $columns                 = intval( $pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-1' ] );
    $pzarc_mediaq_opener_css = '@media all and (min-width:' . $em_width[ 1 ] . 'em) {';
//    $pzarc_mediaq_css .= pzarc_breakpoint_css( $pzarc_mediaq_opener_css, $pzarc_blueprints, $i, $pzarc_mediaq_css, $columns, $panels_class, $hmargin, $nl );
    $pzarc_mediaq_css .= pzarc_breakpoint_css( $pzarc_mediaq_opener_css, $pzarc_blueprints, $i, $pzarc_mediaq_css, $columns, $panels_class, $nl );


    /** Medium */
    $columns                 = intval( $pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-2' ] );
    $pzarc_mediaq_opener_css = '@media all and (min-width: ' . $em_width[ 2 ] . 'em) and (max-width: ' . ( $em_width[ 1 ] - 0.1 ) . 'em) {';
//    $pzarc_mediaq_css .= pzarc_breakpoint_css( $pzarc_mediaq_opener_css, $pzarc_blueprints, $i, $pzarc_mediaq_css, $columns, $panels_class, $hmargin, $nl );
    $pzarc_mediaq_css .= pzarc_breakpoint_css( $pzarc_mediaq_opener_css, $pzarc_blueprints, $i, $pzarc_mediaq_css, $columns, $panels_class, $nl );

    /** Small */
    $columns                 = intval( $pzarc_blueprints[ '_blueprints_section-' . $i . '-columns-breakpoint-3' ] );
    $pzarc_mediaq_opener_css = '@media all and (max-width: ' . ( $em_width[ 2 ] - 0.1 ) . 'em) {';
//    $pzarc_mediaq_css .= pzarc_breakpoint_css( $pzarc_mediaq_opener_css, $pzarc_blueprints, $i, $pzarc_mediaq_css, $columns, $panels_class, $hmargin, $nl );
    $pzarc_mediaq_css .= pzarc_breakpoint_css( $pzarc_mediaq_opener_css, $pzarc_blueprints, $i, $pzarc_mediaq_css, $columns, $panels_class, $nl );


//    if ( $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ] === '%' ) {
//      $pzarc_width_val = ( 100 - str_replace( $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ], '', $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'width' ] ) );
//    } else {
//      $pzarc_width_val = ( str_replace( $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ], '', $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'width' ] ) );
//    }
//      switch ($pzarc_blueprints[ '_blueprints_sections-align' . $i ]) {
//        case 'left':
//          $pzarc_sections_align = 'left:0;';
//          break;
//        case 'centre':
//        case 'center':
//          if ($pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ] === '%') {
//            $pzarc_sections_align = 'left:' . ($pzarc_width_val / 2) . $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ];
//          } else {
//            $pzarc_sections_align = 'margin-left:auto;margin-right:auto;';
//
//          }
//          break;
//        case 'right':
//          if ($pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ] === '%') {
//            $pzarc_sections_align = 'left:' . $pzarc_width_val . $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'units' ];
//          } else {
//            $pzarc_sections_align = 'right: calc(-100% + '.$pzarc_width_val.'px);';
//          }
//          break;
//      }
    $pzarc_css = $pzarc_mediaq_css;
    //   $pzarc_css .= $sections_class . ' {width:' . $pzarc_blueprints[ '_blueprints_sections-width' . $i ][ 'width' ] . ';' . $pzarc_sections_align . '}';

    // Can't do this!! OTherwise would have to regenerate all blueprints on panel save
////        // TODO: do we have to use the bg image height instead if it is set??
//      var_dump($pzarc_panels,$pzarc_panels[ '_panels_settings_panel-height' ][ 'height' ],$pzarc_panels[ '_panels_settings_panel-height-type' ]);
//        $pzarc_panel_height = $pzarc_panels[ '_panels_settings_panel-height' ][ 'height' ];
//        $pzarc_height_type = (empty($pzarc_panels[ '_panels_settings_panel-height-type' ])?'min-height':$pzarc_panels[ '_panels_settings_panel-height-type' ]);
//        $pzarc_mediaq_css .= '.pzarchitect .arc-slider-container.arc-slider-container-' . $pzarc_blueprints[ '_blueprints_short-name' ] . ' {'.$pzarc_panels[ '_panels_settings_panel-height-type' ].':' . $pzarc_panel_height . ';}' . $nl;

    $pzarc_css .= pzarc_create_panels_css( $pzarc_blueprints, $pzarc_css );


    return $pzarc_css;
  }

  /**
   * @param $pzarc_mediaq_opener_css
   * @param $pzarc_blueprints
   * @param $i
   * @param $pzarc_mediaq_css
   * @param $columns
   * @param $panels_class
   * @param $hmargin
   * @param $nl
   *
   * @return string
   */
  function pzarc_breakpoint_css( $pzarc_mediaq_opener_css, &$pzarc_blueprints, $i, $pzarc_mediaq_css, $columns, $panels_class, $nl ) {

    $pzarc_mediaq_css .= $pzarc_mediaq_opener_css;
//  $column_width = (100 - ($hmargin * ($columns - 1))) / $columns.'%';
    // Calc allows us to do crazy maths like 25% -3px
    $lmargin = floatval($pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-left' ]);
    $rmargin = floatval($pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ][ 'margin-right' ]);
    $margin_units = $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ]['units'];

    // Default units are %
    if (!empty($pzarc_blueprints['_blueprints_section-' . $i . '-panels-margins-guttered']) && 'basic' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ] && ! empty( $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ] )) {
      $hmargin = (($lmargin+$rmargin)*($columns-1)/$columns).(empty($margin_units)?'%':$margin_units);
// 15px 15px 3c.... 15+15*2/3 = 20px instead of 30px
    } else {
      $hmargin = ($lmargin+$rmargin).(empty($margin_units)?'%':$margin_units);

    }
    $column_width = ( 0 == ($lmargin+$rmargin) ? 'calc(100%  / ' . $columns . ')' : 'calc(100% / ' . $columns . ' - ' . $hmargin . ')' );

    $panels_margins = pzarc_process_spacing($pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins' ]);

    $pzarc_mediaq_css .= $panels_class . ' {width:' . $column_width . ';'.$panels_margins . ' ;}';

//    pzdebug($pzarc_mediaq_css);
    //  Don't want gutters here iff using masonry layoutt
    switch ( true ) {

      case 'masonry' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]:
        $pzarc_mediaq_css .= str_replace( '.pzarc-panel', '', $panels_class . ' .gutter-sizer {width: ' . ( $hmargin ) . ';}' );
        $pzarc_mediaq_css .= str_replace( '.pzarc-panel', '', $panels_class . ' .grid-sizer { width:' . $column_width . ';}' );
        break;
      case 'basic' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ] && ! empty( $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ] ):
        if (!empty($pzarc_blueprints['_blueprints_section-' . $i . '-panels-margins-guttered'])) {
          $pzarc_mediaq_css .= $panels_class . ':nth-child(-n+' .  $columns  . ') {margin-top: 0;}';
//          $pzarc_mediaq_css .= $panels_class . ':nth-last-child(-n+' .  $columns  . ') {margin-bottom: 0;}'; // this only works for perfect grids
          $pzarc_mediaq_css .= $panels_class . ':nth-child(' . $columns . 'n-' . ( $columns - 1 ) . ') {margin-left: 0;}';
          $pzarc_mediaq_css .= $panels_class . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
        }
        break;
      case 'slider' === $pzarc_blueprints[ '_blueprints_section-' . $i . '-layout-mode' ]:
              //$pzarc_mediaq_css .= $panels_class . ':nth-child(n) {margin-right: ' . ( $rmargin ) .$margin_units. ';}';
        break;
      default:
        $pzarc_mediaq_css .= $panels_class . ':nth-child(' . $columns . 'n) {margin-right: 0;}';
        break;
    }
    //              $pzarc_contents_css .= $classes . ' {width:' . (($column_width)) . '%;margin-bottom:' . $pzarc_blueprints[ '_blueprints_section-' . $i . '-panels-margins']['margin-bottom'] . '%;}';
//    pzdebug($pzarc_mediaq_css);

    //   $pzarc_mediaq_css .= $panels_class . ' .grid-sizer { width:' . $column_width . ';}';
    $pzarc_mediaq_css .= '}' . $nl;

    return $pzarc_mediaq_css;
  }

  /**
   * @param $pzarc_panels (blueprint)
   * @param $pzarc_contents
   * @param $postid
   *
   * @return string
   */
  function pzarc_create_panels_css( &$pzarc_panels, $pzarc_contents ) {

    global $_architect;
    global $_architect_options;
    pzdb( 'create panel css top' );
    $nl = "\n";
//var_dump($pzarc_panels);
    // Step thru each field looking for ones to format
    $class_prefix = '#pzarc-blueprint_' . $pzarc_panels[ '_blueprints_short-name' ] . ' .pzarc-panel';

    // DANGER WILL ROBINSON!
    // json_decode on different enviroments converts UTF-8 data in different ways. I end up getting on of values '240.00' locally and '240' on production - massive dissaster. Morover if conversion fails string get's returned as NULL
    // http://stackoverflow.com/questions/1869091/convert-array-to-object-php

    $toshow      = json_decode( $pzarc_panels[ '_panels_design_preview' ], true );
    $sum_to_show = 0;
    $checksum    = 0;
    foreach ( $toshow as $k => $v ) {
      $sum_to_show += ( $v[ 'show' ] ? $v[ 'width' ] : 0 );
      $checksum += (int) $v[ 'show' ];
    }
    // This is to ensure if there's only one field it will be not be assumed for use in a tabular
    $sum_to_show = $checksum > 1 ? $sum_to_show : 0;

    $feature_in_components = $toshow[ 'image' ][ 'show' ] && ( $pzarc_panels[ '_panels_design_feature-location' ] !== 'float' && $pzarc_panels[ '_panels_design_feature-location' ] !== 'fill' );

    $pzarc_components_position = ( ! empty( $pzarc_panels[ '_panels_design_components-position' ] ) ? $pzarc_panels[ '_panels_design_components-position' ] : 'top' );
    $pzarc_components_nudge_x  = ( ! empty( $pzarc_panels[ '_panels_design_components-nudge-x' ] ) ? $pzarc_panels[ '_panels_design_components-nudge-x' ] : 0 );
    $pzarc_components_nudge_y  = ( ! empty( $pzarc_panels[ '_panels_design_components-nudge-y' ] ) ? $pzarc_panels[ '_panels_design_components-nudge-y' ] : 0 );
    $pzarc_components_width    = ( ! empty( $pzarc_panels[ '_panels_design_components-widths' ] ) ? $pzarc_panels[ '_panels_design_components-widths' ] : 100 );
    $pzarc_tb                  = ( $pzarc_components_position == 'left' || $pzarc_components_position == 'right' ? 'top' : $pzarc_components_position );
    $pzarc_lr                  = ( $pzarc_components_position == 'top' || $pzarc_components_position == 'bottom' ? 'left' : $pzarc_components_position );

    foreach ( $pzarc_panels as $key => $value ) {

      if ( strpos( $key, '_panel' ) === false ) {
        continue;
      }

      /**
       * Panels settings and design
       */
      switch ( true ) {

        case ( $key == '_panels_settings_panel-height' ):

          if ( $pzarc_panels[ '_panels_settings_panel-height-type' ] !== 'none' ) {

            $pzarc_contents .= $class_prefix . ' {' . $pzarc_panels[ '_panels_settings_panel-height-type' ] . ':' . $value[ 'height' ] . ';}' . $nl;

          }
          break;

        case ( $key == '_panels_design_responsive-hide-content' && ! empty( $pzarc_panels[ '_panels_design_responsive-hide-content' ] ) && $pzarc_panels[ '_panels_design_responsive-hide-content' ] !== 'none' ):
          $em_width = (int) str_replace( 'px', '', $_architect_options[ 'architect_breakpoint_' . $pzarc_panels[ '_panels_design_responsive-hide-content' ] ][ 'width' ] ) / 16;
          $pzarc_contents .= '@media (max-width: ' . $em_width . 'em) { ' . $class_prefix . ' .entry-content, ' . $class_prefix . ' .entry-excerpt {display:none!important;}}' . $nl;

          break;

        // TODO: Lazy ass! Work outhow to do this in case statement!
        case ( $key == '_panels_design_content-font-size-bp1' && ! empty( $pzarc_panels[ '_panels_design_content-font-size-bp1' ] ) && ! empty( $pzarc_panels[ '_panels_design_use-responsive-font-size' ] ) ):

          $em_width = (int) str_replace( 'px', '', $_architect_options[ 'architect_breakpoint_1' ][ 'width' ] ) / 16;

          $pzarc_contents .= '@media (min-width: ' . $em_width . 'em) { ' . $class_prefix . ' .entry-content, ' . $class_prefix . ' .entry-excerpt {font-size:' . $pzarc_panels[ '_panels_design_content-font-size-bp1' ][ 'font-size' ] . '!important;line-height:' . $pzarc_panels[ '_panels_design_content-font-size-bp1' ][ 'line-height' ] . ';!important}}' . $nl;

          break;

        case ( $key == '_panels_design_content-font-size-bp2' && ! empty( $pzarc_panels[ '_panels_design_content-font-size-bp2' ] ) && ! empty( $pzarc_panels[ '_panels_design_use-responsive-font-size' ] ) ):

          $em_widthU = (int) str_replace( 'px', '', $_architect_options[ 'architect_breakpoint_1' ][ 'width' ] ) / 16;
          $em_widthL = (int) str_replace( 'px', '', $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] ) / 16;

          $pzarc_contents .= '@media (min-width: ' . $em_widthL . 'em and max-width: ' . $em_widthU . 'em) { ' . $class_prefix . ' .entry-content, ' . $class_prefix . ' .entry-excerpt {font-size:' . $pzarc_panels[ '_panels_design_content-font-size-bp2' ][ 'font-size' ] . '!important;line-height:' . $pzarc_panels[ '_panels_design_content-font-size-bp2' ][ 'line-height' ] . '!important;}}' . $nl;

          break;

        case ( $key == '_panels_design_content-font-size-bp3' && ! empty( $pzarc_panels[ '_panels_design_content-font-size-bp3' ] ) && ! empty( $pzarc_panels[ '_panels_design_use-responsive-font-size' ] ) ):

          $em_width = (int) str_replace( 'px', '', $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] ) / 16;

          $pzarc_contents .= '@media (max-width: ' . $em_width . 'em) { ' . $class_prefix . ' .entry-content, ' . $class_prefix . ' .entry-excerpt {font-size:' . $pzarc_panels[ '_panels_design_content-font-size-bp3' ][ 'font-size' ] . '!important;line-height:' . $pzarc_panels[ '_panels_design_content-font-size-bp3' ][ 'line-height' ] . '!important;}}' . $nl;

          break;


        case ( $key == '_panels_design_preview' ):
          $pzarc_left_margin  = ( ! empty( $pzarc_panels[ '_panels_design_image-margin-left' ] ) ? $pzarc_panels[ '_panels_design_image-margin-left' ] : 0 );
          $pzarc_right_margin = ( ! empty( $pzarc_panels[ '_panels_design_image-margin-right' ] ) ? $pzarc_panels[ '_panels_design_image-margin-right' ] : 0 );
          $pzarc_layout       = json_decode( $value, true );

          $titles_bullets = '';
          switch ( $pzarc_panels[ '_panels_design_title-prefix' ] ) {

            case 'none':
            case 'thumb':
              $titles_bullets = '';
              break;

            case 'disc':
            case 'circle':
            case 'square':
              $title_margins  = (int) $pzarc_panels[ '_panels_design_title-margins' ][ 'margin-left' ] + (int) $pzarc_panels[ '_panels_design_title-margins' ][ 'margin-right' ];
              $titles_bullets = 'list-style:' . $pzarc_panels[ '_panels_design_title-prefix' ] . ' outside none;display:list-item;margin-left:' . $pzarc_panels[ '_panels_design_title-margins' ][ 'margin-left' ] . ';margin-right:' . $pzarc_panels[ '_panels_design_title-margins' ][ 'margin-right' ] . ';';
              break;

            case 'decimal':
            case 'decimal-leading-zero':
            case 'upper-roman':
            case 'lower-roman':
            case 'upper-greek':
            case 'lower-greek':
            case 'lower-latin':
            case 'upper-latin':
            case 'armenian':
            case 'georgian':
            case 'lower-alpha':
            case 'upper-alpha':
              // TODO: Is it possible to make this CSS based?
              $pzarc_contents .= $class_prefix . ' .entry-title {counter-increment: arc-title-counter;}' . $nl;
              $pzarc_contents .= $class_prefix . ' .entry-title:before {content: counter(arc-title-counter, ' . $pzarc_panels[ '_panels_design_title-prefix' ] . ')"' . $pzarc_panels[ '_panels_design_title-bullet-separator' ] . '";}' . $nl;

              break;
          }

          if ( ! empty( $title_margins ) ) {
            $title_width = 'calc(' . $pzarc_layout[ 'title' ][ 'width' ] . '%' . ' - ' . $title_margins . 'px)';
            $pzarc_contents .= $class_prefix . ' .entry-title {width:' . $title_width . ';' . $titles_bullets . '}' . $nl;
            $pzarc_contents .= $class_prefix . ' .td-entry-title {width:' . $title_width . ';' . $titles_bullets . '}' . $nl;
          } else {
            $pzarc_contents .= $class_prefix . ' .entry-title {width:' . $pzarc_layout[ 'title' ][ 'width' ] . '%;' . $titles_bullets . '}' . $nl;
            $pzarc_contents .= $class_prefix . ' .td-entry-title {width:' . $pzarc_layout[ 'title' ][ 'width' ] . '%;' . $titles_bullets . '}' . $nl;
          }

          // Don't give thumbnail div a width if it's in the content
          if ($pzarc_panels[ '_panels_design_centre-image' ]) {
            $margins_arr= $pzarc_panels[ '_panels_design_image-spacing' ];
            $margins_arr['margin-left']='';
            $margins_arr['margin-right']='';
            $margins = pzarc_process_spacing( $margins_arr ).'margin-left:auto;margin-right:auto;';
          } else {
            $margins = pzarc_process_spacing( $pzarc_panels[ '_panels_design_image-spacing' ] );
          }
          $left    = $pzarc_panels[ '_panels_design_image-spacing' ][ 'margin-left' ];
          $left    = preg_replace( "/([\\%px])/uiUm", "", $left );
          $right   = $pzarc_panels[ '_panels_design_image-spacing' ][ 'margin-right' ];
          $right   = preg_replace( "/([\\%px])/uiUm", "", $right );

          if ( 'float' === $pzarc_panels[ '_panels_design_feature-location' ] ) {
            if ( 'top' === $pzarc_components_position || 'bottom' === $pzarc_components_position ) {
              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . ( 100 - $left - $right ) . '%;max-width:' . $pzarc_panels[ '_panels_design_image-max-dimensions' ][ 'width' ] . ';' . $margins . ';}' . $nl;
            } else {
              $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . max( ( 100 - $pzarc_components_width - $left - $right ), 0 ) . '%;max-width:' . $pzarc_panels[ '_panels_design_image-max-dimensions' ][ 'width' ] . ';' . $margins . ';float:' . ( 'left' === $pzarc_components_position ? 'right' : 'left' ) . '}' . $nl;

            }
          } elseif ( 'fill' === $pzarc_panels[ '_panels_design_feature-location' ] && 'image' === $pzarc_panels[ '_panels_settings_feature-type' ] ) {
            $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:100%;max-width:' . $pzarc_panels[ '_panels_design_image-max-dimensions' ][ 'width' ] . ';}' . $nl;
          } elseif ( 'video' === $pzarc_panels[ '_panels_settings_feature-type' ] ) {
            $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . ( $pzarc_layout[ 'image' ][ 'width' ] - $left - $right ) . '%;}' . $nl;
          } else {
            $pzarc_contents .= $class_prefix . ' .entry-thumbnail {width:' . ( $pzarc_layout[ 'image' ][ 'width' ] - $left - $right ) . '%;max-width:' . $pzarc_panels[ '_panels_design_image-max-dimensions' ][ 'width' ] . ';' . $margins . ';}' . $nl;
          }
//              }

          $pzarc_contents .= $class_prefix . ' .entry-content {width:' . $pzarc_layout[ 'content' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-excerpt {width:' . $pzarc_layout[ 'excerpt' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-meta1 {width:' . $pzarc_layout[ 'meta1' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-meta2 {width:' . $pzarc_layout[ 'meta2' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-meta3 {width:' . $pzarc_layout[ 'meta3' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-customfieldgroup-1 {width:' . $pzarc_layout[ 'custom1' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-customfieldgroup-2 {width:' . $pzarc_layout[ 'custom2' ][ 'width' ] . '%;}' . $nl;
          $pzarc_contents .= $class_prefix . ' .entry-customfieldgroup-3 {width:' . $pzarc_layout[ 'custom3' ][ 'width' ] . '%;}' . $nl;
          break;


        // This is in content
        /********************************************************
         *    PANELS FEATURE LOCATION
         *********************************************************/

        case ( $key == '_panels_design_feature-location' ):

          if ( $value === 'content-left' || $value === 'content-right' ) {

            $margins = pzarc_process_spacing( $pzarc_panels[ '_panels_design_image-spacing' ] );
            $twidth  = $pzarc_panels[ '_panels_design_thumb-width' ] - ( str_replace( '%', '', $pzarc_panels[ '_panels_design_image-spacing' ][ 'margin-left' ] ) + str_replace( '%', '', $pzarc_panels[ '_panels_design_image-spacing' ][ 'margin-right' ] ) );
            $float   = ($value === 'content-left') ? 'left' : 'right';
            $pzarc_contents .= $class_prefix . ' .in-content-thumb {width:' . $twidth . '%;' . $margins . '}' . $nl;
            if ($pzarc_panels['_panels_design_alternate-feature-position']==='on') {
              $pzarc_contents .= $class_prefix . '.odd-panel .in-content-thumb {float:' . $float . ';}' . $nl;
              $float   = ($value === 'content-left') ? 'right' : 'left';
              $pzarc_contents .= $class_prefix . '.even-panel .in-content-thumb {float:' . $float . ';}' . $nl;
            } else {
              $pzarc_contents .= $class_prefix . ' .in-content-thumb {float:' . $float . '!important;}' . $nl;
            }
          }

          if ( $value === 'components' &&  $pzarc_panels[ '_panels_design_feature-float' ] !== 'default') {
                $pzarc_contents .= $class_prefix . ' .entry-thumbnail {float:' . $pzarc_panels[ '_panels_design_feature-float' ] . ';}' . $nl;
            }

          if ( $value === 'fill' ) {

          }

          if ( $value === 'float' ) {
            if ($pzarc_panels['_panels_design_alternate-feature-position']==='on') {
              switch ($pzarc_panels['_panels_design_components-position'] ){
                case 'left':
                  $pzarc_contents .= $class_prefix . '.even-panel .entry-thumbnail {float:left;}' . $nl;
                  break;
                case 'right':
                  $pzarc_contents .= $class_prefix . '.even-panel .entry-thumbnail {float:right;}' . $nl;
                  $pzarc_contents .= $class_prefix . '.even-panel .pzarc-components {float:left;}' . $nl;
                  break;
              }

            } else {
            }

          }
          break;

        /********************************************************
         *    PANELS COMPONENTS POSITION
         *********************************************************/

        case ( $key == '_panels_design_components-position' ):

          if ( $value != 'none' ) {

          }
          break;


        /********************************************************
         *    PANELS STYLING
         *********************************************************/

        case ( strpos( $key, '_panels_styling' ) === 0 && ! empty( $value ) && ! empty( $_architect_options[ 'architect_enable_styling' ] ) ):
          pzdb( 'styling top' );
          $pkeys            = array();
          $pkey             = str_replace( '_panels_styling_', '', $key );
          $pkey             = str_replace( '-font-', '-', $pkey );
          $splitter         = ( substr_count( $pkey, '-font-' ) === 1 ? strrpos( $pkey, '-font-' ) : strrpos( $pkey, '-' ) );
          $pkeys[ 'style' ] = str_replace( '-', '', substr( $pkey, $splitter + 1 ) );
          $pkeys[ 'id' ]    = substr( $pkey, 0, $splitter );
          global $pzarc_fontface;
          $pzarc_fontface .= strpos( $pkey, 'font' ) ? pzarc_check_googlefont( $value ) : '';
//          if ( ! in_array( $pkeys[ 'id' ], array( 'custom', 'panels-load' ) ) ) {
            // Filter out old selector names hanging arouind in existing panels
            switch ( true ) {
              case (strpos($pkeys['id'],'entry-customfield-')===0) :
                $pkeys['classes'] = array('0'=>'.'.$pkeys['id']);
                $pzarc_contents .= pzarc_get_styling( 'panel', $pkeys, $value, $class_prefix . ' ', $pkeys[ 'classes' ] );
                break;
              case ( isset( $_architect[ 'architect_config_' . $pkeys[ 'id' ] . '-selectors' ] ) ) :
                $pkeys[ 'classes' ] = ( is_array( $_architect[ 'architect_config_' . $pkeys[ 'id' ] . '-selectors' ] ) ? $_architect[ 'architect_config_' . $pkeys[ 'id' ] . '-selectors' ] : array( '0' => $_architect[ 'architect_config_' . $pkeys[ 'id' ] . '-selectors' ] ) );
                $pzarc_contents .= pzarc_get_styling( 'panel', $pkeys, $value, $class_prefix . ' ', $pkeys[ 'classes' ] );
             //   var_dump($pkeys,pzarc_get_styling( 'panel', $pkeys, $value, $class_prefix . ' ', $pkeys[ 'classes' ] ));
                break;
              case ( $pkeys[ 'id' ] === 'custom' ) :
                $pzarc_contents .= str_replace( array( 'MYBLUEPRINT','MYPANELS'), $class_prefix, $value );
                break;
            }
  //        }
          break;
      }
    }


    // TODO: Why is using bgimages here??
    // TODO: Extend this for multiple breakpoints
    // Put this in an if since it's only when we are using bg images.
    // NOTE: It can affect wide screen content if you set the image smaller than the panel width

    if ( ! empty( $toshow[ 'image' ][ 'show' ] ) ) {
      if ( 'fill' === $pzarc_panels[ '_panels_design_feature-location' ] ) {
        $pzarc_contents .= $class_prefix . '.using-bgimages > .pzarc-components{' . $pzarc_tb . ':' . $pzarc_components_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_components_nudge_x . '%;width:' . $pzarc_components_width . '%;}';
      } else {
        $pzarc_contents .= $class_prefix . ' >.pzarc-components{' . $pzarc_tb . ':' . $pzarc_components_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_components_nudge_x . '%;width:' . $pzarc_components_width . '%;}';
      }
    }

    return $pzarc_contents;
  }
