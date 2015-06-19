<?php
  /**
   * Project pizazzwp-architect.
   * File: arc-slick-public.php
   * User: chrishoward
   * Date: 14/05/15
   * Time: 11:43 PM
   *
   * Loads the slick slider v1.3
   */

  add_filter('arc-set-slider-data', 'pzarc_slick_slider_data', 10, 2);

  function pzarc_slick_slider_data($slider, $blueprint)
  {

    // Slick
    wp_register_script( 'js-arc-front-slickjs', PZARC_PLUGIN_URL . '/extensions/sliders/slick/arc-front-slick.js', array( 'jquery' ), null, true );
    wp_register_script( 'js-slickjs', PZARC_PLUGIN_URL . '/extensions/sliders/slick/slick/slick/slick.min.js', array( 'jquery' ), null, true );
    wp_register_style( 'css-slickjs', PZARC_PLUGIN_URL . '/extensions/sliders/slick/slick/slick/slick.css' );
    wp_register_style( 'css-arcslick', PZARC_PLUGIN_URL . '/extensions/sliders/slick/arc-slick.css' );

    wp_enqueue_script('js-arc-front-slickjs');
    wp_enqueue_script('js-slickjs');
    wp_enqueue_style('css-slickjs');
    wp_enqueue_style('css-arcslick');

    $bp_transtype = $blueprint[ '_blueprints_transitions-type' ];
    $bp_shortname = $blueprint[ '_blueprints_short-name' ];
    $bp_nav_type  = 'navigator';

    $slider[ 'dataid' ]   = '';
    $slider[ 'datauid' ]  = '';
    $slider[ 'datatype' ] = '';

    $slider[ 'class' ]     = ' arc-slider-container slider arc-slider-container-' . $bp_shortname;
    $slider[ 'dataid' ]    = ' data-sliderid="' . $bp_shortname . '"';
    $slider[ 'datauid' ]   = ' data-bpuid="' . $blueprint[ 'uid' ] . '"';
    $slider[ 'datatype' ]  = ' data-navtype="' . $bp_nav_type . '"';
    $slider[ 'datatrans' ] = ' data-transtype="' . $bp_transtype . '"';

    $duration    = $blueprint[ '_blueprints_transitions-duration' ] * 1000;
    $interval    = $blueprint[ '_blueprints_transitions-interval' ] * 1000;
    $skip_thumbs = $blueprint[ '_blueprints_navigator-skip-thumbs' ];
    $no_across   = $blueprint[ '_blueprints_section-0-columns-breakpoint-1' ];
    $is_vertical = (!in_array($blueprint[ '_blueprints_navigator' ], array(
            'thumbs',
            'none'
        )) && ('left' === $blueprint[ '_blueprints_navigator-position' ] || 'right' === $blueprint[ '_blueprints_navigator-position' ])) ? 'true' : 'false';

    $infinite = (!empty($blueprint[ '_blueprints_transitions-infinite' ]) && 'infinite' === $blueprint[ '_blueprints_transitions-infinite' ]) ? 'true' : 'false';

    $slider[ 'dataopts' ] = 'data-opts="{#tduration#:' . $duration . ',#tinterval#:' . $interval . ',#tshow#:' . $skip_thumbs . ',#tskip#:' . $skip_thumbs . ',#tisvertical#:' . $is_vertical . ',#tinfinite#:' . $infinite . ',#tacross#:' . $no_across . '}"';

    $slider[ 'data' ] = $slider[ 'dataid' ] . $slider[ 'datauid' ] . $slider[ 'datatype' ] . $slider[ 'dataopts' ] . $slider[ 'datatrans' ];

    return $slider;
  }

  add_filter('arc-navigation-skipper', 'pzarc_slick_nav_skipper', 10, 2);
  function pzarc_slick_nav_skipper($hover_nav, $blueprint)
  {
    if ($blueprint[ '_blueprints_section-0-layout-mode' ] === 'slider' && $blueprint[ '_blueprints_navigator']==='thumbs' && $blueprint[ '_blueprints_navigator-skip-button']!=='none') {
      $skip_left  = 'backward';
      $skip_right = 'forward';

      $hover_nav .= '<button class="pager skip-left icon-btn-style"><span class="icon-' . $skip_left . ' ' . $blueprint[ '_blueprints_navigator-skip-button' ] . '"></span></button>';
      $hover_nav .= '<button class="pager skip-right icon-btn-style"><span class="icon-' . $skip_right . ' ' . $blueprint[ '_blueprints_navigator-skip-button' ] . '"></span></button>';
    }

    return $hover_nav;
  }


  add_filter('arc-add-hover-buttons', 'pzarc_add_hover_buttons', 10, 2);
  function pzarc_add_hover_buttons($return_val, $blueprint)
  {
    $return_val .= '<button type="button" class="pager arrow-left icon-arrow-left4 hide"></button>';
    $return_val .= '<button type="button" class="pager arrow-right icon-uniE60D"></button>';

    return $return_val;
  }


