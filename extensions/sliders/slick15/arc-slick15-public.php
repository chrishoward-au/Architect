<?php
  /**
   * Project pizazzwp-architect.
   * File: arc-slick15-public.php
   * User: chrishoward
   * Date: 14/05/15
   * Time: 11:43 PM
   *
   * Loads the slick slider 1.5
   */

  add_filter('arc-set-slider-data', 'pzarc_slick_slider15_data', 10, 2);
  function pzarc_slick_slider15_data($slider, $blueprint)
  {
    wp_enqueue_script( 'js-slickjs' );
    wp_enqueue_style( 'css-slickjs' );
    wp_enqueue_style( 'css-arcslick' );

    $pzarc_slick_data = '';
    $pzarc_slick_data = '{"slidesToShow": 4, "slidesToScroll": 4}';
    $slider['data']= 'data-slick=\''.$pzarc_slick_data.'\'';
    global $pzarchitect_slider_scripts;

    $pzarchitect_slider_scripts = isset($pzarchitect_slider_scripts)?$pzarchitect_slider_scripts:'';
    $pzarchitect_slider_scripts .= 'jQuery(".pzarc-section-using-'.$blueprint['_blueprints_short-name'].'").slick({asNavFor:\'.pzarc-navigator-'.$blueprint['_blueprints_short-name'].'\'});'."\n";
    $pzarchitect_slider_scripts .= 'jQuery(".pzarc-navigator-'.$blueprint['_blueprints_short-name'].'").slick({asNavFor:\'.pzarc-section-using-'.$blueprint['_blueprints_short-name'].'\'});'."\n";
    return $slider;
  }

  add_filter('arc-navigation-skipper', 'pzarc_slick15_nav_skipper', 10, 2);
  function pzarc_slick15_nav_skipper($hover_nav, $blueprint)
  {
    $skip_left  = 'backward';
    $skip_right = 'forward';
    $hover_nav .= '<div class="arc-slider-nav arc-slider-container icomoon ' . $blueprint[ '_blueprints_navigator' ] . ' has-pager">';
    $hover_nav .= '<button class="pager skip-left icon-btn-style"><span class="icon-' . $skip_left . ' ' . $blueprint[ '_blueprints_navigator-skip-button' ] . '"></span></button>';
    $hover_nav .= '<button class="pager skip-right icon-btn-style"><span class="icon-' . $skip_right . ' ' . $blueprint[ '_blueprints_navigator-skip-button' ] . '"></span></button>';
    return $hover_nav;
  }

  add_filter('arc-navigator-class', 'pzarc_set_nav15_class', 10, 2);
  function pzarc_set_nav15_class($class, $blueprint)
  {
    if ($blueprint[ '_blueprints_navigator' ] === 'thumbs') {
      // Use cusotm thumbs
      $class = 'arc_Navigator_Slick15_Thumbs';

    } else {
      $class = 'arc_Navigator_' . $blueprint[ '_blueprints_navigator' ];

    }

    return $class;
  }

  class arc_Navigator_Slick15_Thumbs extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[ ] = __CLASS__;

    }

    function render()
    {
      $i        = 1;
      $nav_html = '';
      foreach ($this->navitems as $nav_item) {
        $active = ($i === 1 ? ' active' : '');
        $nav_html .= '<div class="arc-slider-slide arc-slider-slide-nav-item' . $this->sizing . $active . '" data-index="' . $i . '" >' . $nav_item . '</div>';
        $i++;
      }

      echo $nav_html;
    }


  }

  add_filter('arc-add-hover-buttons', 'pzarc_add_hover15_buttons', 10, 2);
  function pzarc_add_hover15_buttons($return_val, $blueprint)
  {
    return $return_val;
  }

  add_action('wp_print_footer_scripts', 'pzarc_slick15_scripts');
  function pzarc_slick15_scripts()
  {
    echo '<!-- Architect slider scripts -->';
    global $pzarchitect_slider_scripts;
    if (!empty($pzarchitect_slider_scripts)) {
      echo '<script type="text/javascript" id="architect-slick15">';
      echo $pzarchitect_slider_scripts;
      echo '</script>';
    }
    // Make sure this isn't acidentally saved in anyway
    unset($pzarchitect_slider_scripts);
  }


  add_filter('arc-nav-close', 'pzarc_slick15_nav_close', 10, 2);
  function pzarc_slick15_nav_close($close, $blueprint)
  {
    // Slick nav needs an extra div
    if ('thumbs' === $blueprint[ '_blueprints_navigator' ]) {
      $close = '</div><!-- end thumbs nav --></div><!-- End pzarc-navigator -->';
    } else {
      $close = '</div><!-- End pzarc-navigator -->';
    }

    return $close;
  }