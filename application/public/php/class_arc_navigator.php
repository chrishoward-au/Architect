<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 25/04/2014
   * Time: 9:11 PM
   */
  class arc_Navigator
  {

    protected $nav_types = '';
    protected $blueprint = '';
    protected $navitems = array();
    protected $sizing = 'medium';

    function __construct($blueprint, $navitems)
    {

      // Enqueue registered scripts and styles
      // TODO: make optional
      wp_enqueue_style('css-icomoon-arrows');

      $this->blueprint = $blueprint;
      $this->navitems  = $navitems;
      $this->sizing    = ' ' . $this->blueprint[ '_blueprints_navigator-sizing' ];

      if ($this->blueprint[ '_blueprints_navigator' ] === 'none') {
        return;
      }
//      $skip_left  = $this->blueprint[ '_blueprints_navigator-skip-left' ];
//      $skip_right = $this->blueprint[ '_blueprints_navigator-skip-right' ];


      $skipper_nav = '';
      if ('thumbs' === $this->blueprint[ '_blueprints_navigator' ]) {
        $nav_position = $this->blueprint[ '_blueprints_navigator-thumbs-position' ];
      } else {
        $nav_position = $this->blueprint[ '_blueprints_navigator-position' ];
      }

//      $hover_nav .= '<div class="arc-slider-nav arc-slider-container icomoon ' . $blueprint[ '_blueprints_navigator' ] . ' has-pager">';

      // We need an extra div container for the nav otherwise slick treats the skip buttons as nav items!
      // And that's why we have the extra close div

      $open = '<div class="arc-slider-nav arc-slider-container icomoon ' . $blueprint[ '_blueprints_navigator' ] . ' has-pager">';

      // Not all sliders will work this way, so we pass it thru a filter in case
      echo apply_filters('arc-open-nav-container', $open, $this->blueprint);

      echo apply_filters('arc-navigation-skipper', $skipper_nav, $this->blueprint);

      $custom_classes = $this->blueprint[ '_blueprints_navigator-bullet-shape' ];
      $custom_classes = apply_filters('arc-navigator-custom-classes', $custom_classes, $this->blueprint);
      echo '<div class="pzarc-navigator pzarc-navigator-' . $this->blueprint[ '_blueprints_short-name' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator' ] .
          ' ' . $nav_position .
          ' ' . $this->blueprint[ '_blueprints_navigator-location' ] .
          ' nav-align-' . $this->blueprint[ '_blueprints_navigator-align' ] .
          ' ' . $custom_classes . '">';

    }

    function render()
    {
    }


    function __destruct()
    {
      if ($this->blueprint[ '_blueprints_navigator' ] !== 'none') {

        echo '</div><!-- End pzarc-navigator -->';
        echo apply_filters('arc-close-nav-container', '</div><!-- end nav container -->', $this->blueprint);
      }
    }
  }

  /**
   * Class arc_Navigator_Tabbed
   */
  class arc_Navigator_Tabbed extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[] = __CLASS__;
    }

    function render()
    {

      $i      = 1;
      $styles = '';
      // TODO: Make this not inline css
      $nav_width_type = !empty($this->blueprint[ '_blueprints_navtabs-width-type' ]) ? $this->blueprint[ '_blueprints_navtabs-width-type' ] : '';

      switch ($nav_width_type) {
        case'fixed':
          $styles .= 'width:' . $this->blueprint[ '_blueprints_navtabs-width' ][ 'width' ] . ';';
          break;
        case 'even':
          $margins_compensation = ($this->blueprint[ '_blueprints_navtabs-margins-compensation' ][ 'width' ]) ? $this->blueprint[ '_blueprints_navtabs-margins-compensation' ][ 'width' ] * count($this->navitems) : '0';
          $margins_compensation .= ($this->blueprint[ '_blueprints_navtabs-margins-compensation' ][ 'units' ]) ? $this->blueprint[ '_blueprints_navtabs-margins-compensation' ][ 'units' ] : 'px';
          $percent_width = (100 / count($this->navitems)) . '%';
          $styles .= 'width:calc(' . $percent_width . ' - ' . $margins_compensation . ');';
          break;
        case 'fluid':
        default:
          $styles .= '';

      }
      if (!empty($this->blueprint[ '_blueprints_navtabs-textwrap' ])) {
        switch ($this->blueprint[ '_blueprints_navtabs-textwrap' ]) {
          case 'break-word':
            $styles .= 'word-break:break-word;';
            break;
          case 'nowrap':
            $styles .= 'white-space:nowrap;';
            break;
          case 'wraplines':
            $styles .= 'white-space:normal;word-break:normal;';
            break;
        }
      }

      if (!empty($this->blueprint[ '_blueprints_navtabs-textoverflow' ])) {
        switch ($this->blueprint[ '_blueprints_navtabs-textoverflow' ]) {
          case 'visible':
            $styles .= 'overflow:visible;';
            break;
          case 'hidden-ellipses':
            $styles .= 'overflow:hidden;text-overflow:ellipsis';
            break;
          case 'hidden-clip':
            $styles .= 'overflow:hidden;text-overflow:clip';
            break;
        }
      }

      $styles = $styles ? ' style="' . $styles . '"' : $styles;
      foreach ($this->navitems as $nav_item) {
        $active = ($i === 1 ? ' active' : '');
        echo '<div class="arc-slider-slide arc-slider-slide-nav-item' . $active . ' arc-navitem-' . $i . ' arc-navitem-' . sanitize_title($nav_item) . ' arc-width-'.$nav_width_type.'" data-index="' . $i . '" ' . $styles . '>';
        echo $nav_item;
        echo '</div>';
        $i++;
      }
    }

  }

  /**
   * Class arc_Navigator_Labels
   */
  class arc_Navigator_Labels extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[] = __CLASS__;
    }

    function render()
    {
      $i = 1;
      foreach ($this->navitems as $nav_item) {
        $active = ($i === 1 ? ' active' : '');

        echo '<div class="arc-slider-slide arc-slider-slide-nav-item' . $active . ' arc-navitem-' . $i . ' arc-navitem-' . sanitize_title($nav_item) . '" data-index="' . $i . '">' . $nav_item . '</div>';
        $i++;
      }
    }

  }

  // Todo. these are the player type controls. << < o > >>
  /**
   * Class arc_Navigator_Buttons
   */
  class arc_Navigator_Buttons extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[] = __CLASS__;

    }

    function render()
    {
      echo '<h2><< < [] >></h2>';
    }

  }

  /**
   * Class arc_Navigator_Bullets
   */
  class arc_Navigator_Bullets extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[] = __CLASS__;

    }

    function render()
    {
      $i = 1;
      foreach ($this->navitems as $nav_item) {
        $active = ($i === 1 ? ' active' : '');
        echo '<div class="arc-slider-slide-nav-item' . $this->sizing . $active . ' arc-navitem-' . $i . ' arc-navitem-' . sanitize_title($nav_item) . '" data-index="' . $i++ . '"></div>';
      }
    }

  }

  /**
   * Class arc_Navigator_Numbers
   */
  class arc_Navigator_Numbers extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[] = __CLASS__;

    }

    function render()
    {
      $i = 1;
      foreach ($this->navitems as $nav_item) {
        $active = ($i === 1 ? ' active' : '');
        echo '<div class="arc-slider-slide arc-slider-slide-nav-item' . $this->sizing . $active . ' arc-navitem-' . $i . ' arc-navitem-' . sanitize_title($nav_item) . '" data-index="' . $i . '">' . $i++ . '</div>';
      }
    }

  }

  /**
   * Class arc_Navigator_None
   */
  class arc_Navigator_None extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[] = __CLASS__;

    }

    function render()
    {
    }

  }

  /**
   * Class arc_Navigator_Thumbs
   */
  class arc_Navigator_Thumbs extends arc_Navigator
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
        $nav_html .= '<div class="arc-slider-slide arc-slider-slide-nav-item' . $this->sizing . $active . ' arc-navitem-' . $i . ' arc-navitem-' . sanitize_title($nav_item) . '" data-index="' . $i . '">' . $nav_item . '</div>';
        $i++;
      }

      echo $nav_html;
    }


  }
