<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 25/04/2014
   * Time: 9:11 PM
   */

  //TODO: Accordions will have a different structure. Like thus:
  //
  // SECTION
  // -TITLE
  // -- PANEL
  // -TITLE
  // - PANEL
  // as opposed to current of
  // SECTION
  // - PANEL
  // - PANEL
  // - PANEL

  class arc_Navigator
  {

    protected $nav_types = '';
    protected $blueprint = '';
    protected $navitems = array();
    protected $sizing = 'medium';

    function __construct($blueprint, $navitems)
    {
      $this->blueprint = $blueprint;
      $this->navitems  = $navitems;
      $this->sizing    = ' ' . $this->blueprint[ '_blueprints_navigator-sizing' ];

      $skip_left  = $this->blueprint[ '_blueprints_navigator-skip-left' ];
      $skip_right = $this->blueprint[ '_blueprints_navigator-skip-right' ];

      if ('thumbs' === $this->blueprint[ '_blueprints_navigator' ]) {
        echo '<div class="swiper-nav swiper-container icomoon ' . $this->blueprint[ '_blueprints_navigator' ] . '">';
        echo '<a class="pager skip-left icon-btn-styled" href="#"><span class="icon-' . $skip_left . '"></span></a>';
        echo '<a class="pager skip-right icon-btn-style" href="#"><span class="icon-' . $skip_right . '"></span></a>';
      }

      echo '<div class="pzarc-navigator pzarc-navigator-' . $this->blueprint[ '_blueprints_short-name' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator-position' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator-location' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator-align' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator-bullet-shape' ] . ' swiper-wrapper ">';

    }

    function render()
    {
    }

    function __destruct()
    {
      if ('thumbs' === $this->blueprint[ '_blueprints_navigator' ]) {
        echo '</div></div>';
      } else {
        echo '</div>';
      }
    }
  }

  class arc_Navigator_Tabbed extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[ ] = __CLASS__;
    }

    function render()
    {

      $i = 1;
      foreach ($this->navitems as $nav_item) {
        $active = ($i === 1 ? ' active' : '');

        echo '<span class="swiper-slide swiper-pagination-switch' . $active . '">' . $nav_item . '</span>';
        $i++;
      }
    }

  }

  class arc_Navigator_Accordion extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[ ] = __CLASS__;

    }

    function render()
    {
      echo "<h2>A A A A A</h2>";
    }

  }

  class arc_Navigator_Buttons extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[ ] = __CLASS__;

    }

    function render()
    {
      echo '<h2><< < [] >></h2>';
    }

  }

  class arc_Navigator_Bullets extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[ ] = __CLASS__;

    }

    function render()
    {
      $i = 1;
      foreach ($this->navitems as $nav_item) {
        $active = ($i === 1 ? ' active' : '');
        if ($this->blueprint[ '_blueprints_navigator-slider-engine' ] === 'bxslider') {
          echo '<a data-slide-index="' . $i++ . '" style="cursor:pointer;">' . $nav_item . '</a>';
        } else {
          echo '<span class="swiper-pagination-switch' . $this->sizing . $active . '"></span>';
          $i++;
        }
      }
    }

  }

  class arc_Navigator_Numbers extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[ ] = __CLASS__;

    }

    function render()
    {
      $i = 1;
      foreach ($this->navitems as $nav_item) {
        $active = ($i === 1 ? ' active' : '');
        if ($this->blueprint[ '_blueprints_navigator-slider-engine' ] === 'bxslider') {
          echo '<a data-slide-index="' . $i++ . '" style="cursor:pointer;">' . $nav_item . '</a>';
        } else {
          echo '<span class="swiper-pagination-switch' . $this->sizing . $active . '">' . $i++ . '</span>';
        }
      }
    }

  }

  class arc_Navigator_Thumbs extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[ ] = __CLASS__;

    }

    function render()
    {
      $i = 1;
      foreach ($this->navitems as $nav_item) {
        $active = ($i === 1 ? ' active' : '');
        echo '<span class="swiper-slide swiper-pagination-switch' . $this->sizing . $active . '">' . $nav_item . '</span>';
        $i++;
      }
    }

  }
