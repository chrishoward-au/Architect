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
    // Slick
    wp_register_script('js-arc-front-slick15js', PZARC_PLUGIN_URL . '/extensions-inc/sliders/slick15/arc-front-slick15b.js', array('jquery'), null, true);
    wp_register_script('js-slick15js', PZARC_PLUGIN_URL . '/extensions-inc/sliders/slick15/slick-1.5.8/slick/slick.min.js', array('jquery'), null, true);
    wp_register_style('css-slick15js', PZARC_PLUGIN_URL . '/extensions-inc/sliders/slick15/slick-1.5.8/slick/slick.css');
    wp_register_style('css-arcslick15', PZARC_PLUGIN_URL . '/extensions-inc/sliders/slick15/arc-slick15.css');

    wp_enqueue_script('js-slick15js');
    wp_enqueue_script('js-arc-front-slick15js');
    wp_enqueue_style('css-slick15js');
    wp_enqueue_style('css-arcslick15');

    $is_rtl = is_rtl()?'true':'false';

    // Setup the slides
    $pzarc_slick_data = '{';
    $pzarc_slick_data .= ' "fade": ' . (!empty($blueprint[ '_blueprints_transitions-type' ]) && $blueprint[ '_blueprints_transitions-type' ] === 'fade' ? 'true' : 'false');
    $pzarc_slick_data .= ', "slidesToShow": ' . $blueprint[ '_blueprints_section-0-columns-breakpoint-1' ];
    $pzarc_slick_data .= ', "slidesToScroll": ' . $blueprint[ '_blueprints_section-0-columns-breakpoint-1' ];
    $pzarc_slick_data .= ', "autoplay":' . (!empty($blueprint[ '_blueprints_transitions-interval' ]) ? 'true' : 'false');
    $pzarc_slick_data .= ', "autoplaySpeed":' . (!empty($blueprint[ '_blueprints_transitions-interval' ]) ? ($blueprint[ '_blueprints_transitions-interval' ] * 1000) : 0);
    $pzarc_slick_data .= ', "speed":' . (!empty($blueprint[ '_blueprints_transitions-duration' ]) ? ($blueprint[ '_blueprints_transitions-duration' ] * 1000) : 0);
    $pzarc_slick_data .= ', "adaptiveHeight":' . (!isset($blueprint[ '_slick15_extra-options' ]) || in_array('adaptive', $blueprint[ '_slick15_extra-options' ]) ? 'true' : 'false');
    $pzarc_slick_data .= ', "pauseOnHover":' . (!isset($blueprint[ '_slick15_extra-options' ]) || in_array('pause', $blueprint[ '_slick15_extra-options' ]) ? 'true' : 'false');
    $pzarc_slick_data .= ', "vertical":false'; //this goes a bit weird if enabled
    $pzarc_slick_data .= ', "rtl":'.$is_rtl;
    $pzarc_slick_data .= ', "draggable":' . (!empty($blueprint[ '_blueprints_transitions-type' ]) && $blueprint[ '_blueprints_transitions-type' ] === 'slide' ? 'true' : 'false'); // Draggable i
    $pzarc_slick_data .= ', "infinite":' . (!isset($blueprint[ '_slick15_extra-options' ]) || in_array('infinite', $blueprint[ '_slick15_extra-options' ]) ? 'true' : 'false');
    $pzarc_slick_data .= ', "prevArrow":".' . $blueprint[ 'uid' ] . ' .pager.arrow-left"';
    $pzarc_slick_data .= ', "nextArrow":".' . $blueprint[ 'uid' ] . ' .pager.arrow-right"';

    if ($blueprint[ '_blueprints_navigator' ] !== 'none') {
      $pzarc_slick_data .= ', "asNavFor":".' . $blueprint[ 'uid' ] . ' .pzarc-navigator-' . $blueprint[ '_blueprints_short-name' ] . '"';
    }
    $pzarc_slick_data .= '}';

    $slider[ 'data' ] = 'data-slick=\'' . $pzarc_slick_data . '\'';
    global $pzarchitect_slider_scripts;

    $pzarchitect_slider_scripts = isset($pzarchitect_slider_scripts) ? $pzarchitect_slider_scripts : '';

    $pzarchitect_slider_scripts .= '
          var gotoPanel = localStorage.getItem("gotoPanel");
          var gotoBlueprint = localStorage.getItem("gotoBlueprint");
          var startPanel = "0";
          if (gotoPanel>0 && gotoBlueprint.length>0) {
              startPanel = gotoPanel-1;
          }
        localStorage.setItem("gotoBlueprint","");
        localStorage.setItem("gotoPanel","0");
        console.log(gotoPanel,gotoBlueprint,startPanel);
      '."\n\n";

    $pzarchitect_slider_scripts .= "\n\n".'var slick'.$blueprint[ 'blueprint-id' ].' = jQuery(".' . $blueprint[ 'uid' ] . ' .pzarc-section-using-' . $blueprint[ '_blueprints_short-name' ] . '");

    if (startPanel>0) {
      slick'.$blueprint[ 'blueprint-id' ].'.slick({initialSlide:startPanel});
    }else{
     slick'.$blueprint[ 'blueprint-id' ].'.slick();
    }
        '."\n\n";


//    $pzarchitect_slider_scripts .= ' jQuery(slick'.$blueprint[ 'blueprint-id' ].').slick("slickGoTo",3);
//      '."\n\n";

    $pzarchitect_slider_scripts .= ' slick'.$blueprint[ 'blueprint-id' ].'.on("beforeChange", function(event, slick, currentSlide, nextSlide){
        jQuery(this).find(".pzarc-panel").removeClass("active");
        jQuery(this).find("[data-slick-index=\""+nextSlide+"\"]").addClass("active");
      })';
    $pzarchitect_slider_scripts .= '.on("afterChange", function(event, slick, currentSlide){
        var realCurrent = jQuery("#pzarc-blueprint_' . $blueprint[ '_blueprints_short-name' ] . ' .pzarc-panel.active").attr("data-slick-index");
        jQuery(".' . $blueprint[ 'uid' ] . ' .pzarc-navigator .active").removeClass("active");
        jQuery(".' . $blueprint[ 'uid' ] . ' .pzarc-navigator").find("[data-slick-index=\""+realCurrent+"\"]").addClass("active");
      });';

    $pzarchitect_slider_scripts .= "\n";

    /**
     * Setup the Navigator
     */

    if ($blueprint[ '_blueprints_navigator' ] !== 'none') {
      $navs_items_toshow = $blueprint[ '_blueprints_navigator-skip-thumbs' ];
      $navs_items_toskip = $blueprint[ '_blueprints_navigator-skip-thumbs' ];
      $nav_skipper       = ($blueprint[ '_blueprints_navigator-skip-button' ] !== 'none');
      $nav_var_width     = 'false';
      $nav_continuous = ((!empty($blueprint[ '_blueprints_navigator-continuous' ]) && $blueprint[ '_blueprints_navigator-continuous' ]==='continuous') ? 'true' : 'false');
      switch (true) {
        case $blueprint[ '_blueprints_navigator' ] === 'bullets':
        case $blueprint[ '_blueprints_navigator' ] === 'numbers':
        case $blueprint[ '_blueprints_navigator' ] === 'tabbed':
        case $blueprint[ '_blueprints_navigator' ] === 'labels':
          $navs_items_toshow = (!empty($blueprint[ '_blueprints_section-0-panels-limited' ]) ? $blueprint[ '_blueprints_section-0-panels-per-view' ] : 10);
          $navs_items_toskip = 1;
          $nav_skipper       = false;
          $nav_var_width     = 'true';
          $nav_continuous ='false';
          break;
        case in_array($blueprint[ '_blueprints_navigator-location' ], array('left', array('right'))):
          $nav_skipper = false;
          $nav_continuous ='false';
          break;
      }

      $pzarchitect_slider_scripts .= '
      var nav'.$blueprint[ 'blueprint-id' ].' = jQuery(".' . $blueprint[ 'uid' ] . ' .pzarc-navigator-' . $blueprint[ '_blueprints_short-name' ] . '");
      if (startPanel>0) {
        jQuery(nav'.$blueprint[ 'blueprint-id' ].').find(".arc-slider-slide-nav-item.active").removeClass("active");
        jQuery(nav'.$blueprint[ 'blueprint-id' ].').find(".arc-navitem-"+(startPanel+1)).addClass("active");

      }
      nav'.$blueprint[ 'blueprint-id' ].'.slick({';
      $pzarchitect_slider_scripts .= '  asNavFor:".' . $blueprint[ 'uid' ] . ' .pzarc-section-using-' . $blueprint[ '_blueprints_short-name' ] . '"';
//      $pzarchitect_slider_scripts .= ', initialSlide:startPanel';
      $pzarchitect_slider_scripts .= ', slidesToShow:' . $navs_items_toshow;
      $pzarchitect_slider_scripts .= ', slidesToScroll:' . $navs_items_toskip;
      $pzarchitect_slider_scripts .= ', focusOnSelect:true';
      $pzarchitect_slider_scripts .= ', centerMode:'.$nav_continuous;
      $pzarchitect_slider_scripts .= ', draggable:true';
      $pzarchitect_slider_scripts .= ', infinite:'.$nav_continuous;
//      $pzarchitect_slider_scripts .= ', arrows:true';
      $pzarchitect_slider_scripts .= ', useCSS:false';
      $pzarchitect_slider_scripts .= ', rtl:'.$is_rtl;
      $pzarchitect_slider_scripts .= ',adaptiveHeight:true';
      $pzarchitect_slider_scripts .= ', variableWidth:' . $nav_var_width;

      if ($nav_skipper) {
        $pzarchitect_slider_scripts .= ', prevArrow:".' . $blueprint[ 'uid' ] . ' .pager.skip-left"';
        $pzarchitect_slider_scripts .= ', nextArrow:".' . $blueprint[ 'uid' ] . ' .pager.skip-right"';
      } else {
        $pzarchitect_slider_scripts .= ', prevArrow:false';
        $pzarchitect_slider_scripts .= ', nextArrow:false';
      }
      $pzarchitect_slider_scripts .= '})';

//    $pzarchitect_slider_scripts .= '.on("beforeChange", function(event, slick, currentSlide, nextSlide){
//        jQuery(this).find(".arc-slider-slide-nav-item").removeClass("active");
//      })';

// TODO Why isn't this doing anything for numbers and bullets?
//      $pzarchitect_slider_scripts .= '.on("afterChange", function(event, slick, currentSlide){
//        var realCurrent = jQuery("#pzarc-blueprint_' . $blueprint[ '_blueprints_short-name' ] . ' .pzarc-panel.active").attr("data-slick-index");
//        jQuery(".'.$blueprint['uid'].' .pzarc-navigator .active").removeClass("active");
//        console.log(currentSlide, realCurrent,this);
//        jQuery(this).find("[data-slick-index=\""+realCurrent+"\"]").addClass("active");
//      });';
      $pzarchitect_slider_scripts .= "\n";

    }

    return $slider;
  }

  add_filter('arc-navigation-skipper', 'pzarc_slick15_nav_skipper', 10, 2);
  function pzarc_slick15_nav_skipper($hover_nav, $blueprint)
  {
    if ($blueprint[ '_blueprints_section-0-layout-mode' ] === 'slider'
        && $blueprint[ '_blueprints_navigator-skip-button' ] !== 'none'
        && !in_array($blueprint[ '_blueprints_navigator' ], array('none',
                                                                  'numbers',
                                                                  'bullets',
                                                                  'tabbed',
                                                                  'labels'))
        && !in_array($blueprint[ '_blueprints_navigator-position' ], array('none',
                                                                           'right',
                                                                           'left'))
    ) {
      $skip_left  = 'backward';
      $skip_right = 'forward';
      // open the nav

      $hover_nav .= '<button class="pager skip-left icon-btn-style"><span class="icon-' . $skip_left . ' ' . $blueprint[ '_blueprints_navigator-skip-button' ] . '"></span></button>';
      $hover_nav .= '<button class="pager skip-right icon-btn-style"><span class="icon-' . $skip_right . ' ' . $blueprint[ '_blueprints_navigator-skip-button' ] . '"></span></button>';

    }

    return $hover_nav;
  }

  add_filter('arc-navigator-class', 'pzarc_set_nav15_class', 10, 2);
  function pzarc_set_nav15_class($class, $blueprint)
  {
    switch ($blueprint[ '_blueprints_navigator' ]) {

      case 'thumbs':
        // Use custom thumbs
        // This is exactly the same as the one in the default navigator and is just provided as an example
        $class = 'arc_Navigator_Slick15_Thumbs';
        break;

      default:
        $class = 'arc_Navigator_' . $blueprint[ '_blueprints_navigator' ];

    }

    return $class;
  }

  // This is exactly the same as the one in the default navigator and is just provided as an example
  class arc_Navigator_Slick15_Thumbs extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[] = __CLASS__;

    }

    function render()
    {
      $i        = 1;
      $nav_html = '';
      foreach ($this->navitems as $nav_item) {
        $active = ($i === 1 ? ' active' : '');
        $nav_html .= '<div class="arc-slider-slide arc-slider-slide-nav-item' . $this->sizing . $active . ' arc-navitem-'.$i.' arc-navitem-'.sanitize_title($nav_item).'" data-index="' . $i . '" >' . $nav_item . '</div>';
        $i++;
      }

      echo $nav_html;
    }


  }

  add_filter('arc-add-hover-buttons', 'pzarc_add_hover_buttons', 10, 2);
  function pzarc_add_hover_buttons($return_val, $blueprint)
  {
    $return_val .= '<button type="button" class="pager arrow-left icon-arrow-left4 hide"></button>';
    $return_val .= '<button type="button" class="pager arrow-right icon-uniE60D"></button>';

    return $return_val;
  }

  add_action('wp_print_footer_scripts', 'pzarc_slick15_scripts');
  function pzarc_slick15_scripts()
  {
    echo '<!-- Architect slider scripts -->';
    global $pzarchitect_slider_scripts;
    if (!empty($pzarchitect_slider_scripts)) {
      echo '<script type="text/javascript" id="architect-slick15">';
      echo '(function($){';
      echo $pzarchitect_slider_scripts;
      echo '})(jQuery);';
      echo '</script>';

    }
    // Make sure this isn't acidentally saved in any way
    unset($pzarchitect_slider_scripts);
  }

