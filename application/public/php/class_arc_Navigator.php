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


  // TODO: Consider changing echoes to $out .= and then echoing in a filter. This might make extra navs easier. Plus might make it easier to put it anywhere. maybe

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
        echo '<div class="arc-slider-nav arc-slider-container icomoon ' . $this->blueprint[ '_blueprints_navigator' ] . ' has-pager">';
        echo '<button class="pager skip-left icon-btn-style"><span class="icon-' . $skip_left . '"></span></button>';
        echo '<button class="pager skip-right icon-btn-style"><span class="icon-' . $skip_right . '"></span></button>';
      }

      echo '<div class="pzarc-navigator pzarc-navigator-' . $this->blueprint[ '_blueprints_short-name' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator-position' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator-location' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator-align' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator-bullet-shape' ] . '">';

    }

    function render()
    {
    }

    function __destruct()
    {
      if ('thumbs' === $this->blueprint[ '_blueprints_navigator' ]) {
        echo '</div><!-- end thumbs nav --></div><!-- End pzarc-navigator -->';
      } else {
        echo '</div><!-- End pzarc-navigator -->';
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

        echo '<span class="arc-slider-slide arc-slider-slide-nav-item' . $active . '" data-index="' . $i . '">' . $nav_item . '</span>';
        $i++;
      }
    }

  }

  class arc_Navigator_Accordion extends arc_Navigator
  {
    function _construct()
    {
      // are these even possible with the current structure?????
      // TODO Maybe we can use a add_filter in a loop? That's quite reasonable...just need a filter where they go
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
        echo '<span class="arc-slider-slide-nav-item' . $this->sizing . $active . '" data-index="' . $i++ . '"></span>';
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
        echo '<span class="arc-slider-slide-nav-item' . $this->sizing . $active . '" data-index="' . $i . '">' . $i++ . '</span>';
      }
    }

  }

  class arc_Navigator_None extends arc_Navigator
  {
    function _construct()
    {
      $this->nav_types[ ] = __CLASS__;

    }

    function render()
    {
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
        echo '<div class="arc-slider-slide arc-slider-slide-nav-item' . $this->sizing . $active . '" data-index="' . $i . '">' . $nav_item . '</div>';
        $i++;
      }
    }

  }
