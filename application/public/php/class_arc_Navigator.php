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
      wp_enqueue_script('js-arc-front-slickjs');
      wp_enqueue_script('js-slickjs');
      wp_enqueue_style('css-slickjs');
      wp_enqueue_style('css-icomoon-arrows');

      $this->blueprint = $blueprint;
      $this->navitems  = $navitems;
      $this->sizing    = ' ' . $this->blueprint[ '_blueprints_navigator-sizing' ];

//      $skip_left  = $this->blueprint[ '_blueprints_navigator-skip-left' ];
//      $skip_right = $this->blueprint[ '_blueprints_navigator-skip-right' ];
      $skip_left  = 'backward';
      $skip_right = 'forward';

      if ('thumbs' === $this->blueprint[ '_blueprints_navigator' ]) {
        echo '<div class="arc-slider-nav arc-slider-container icomoon ' . $this->blueprint[ '_blueprints_navigator' ] . ' has-pager">';
        echo '<button class="pager skip-left icon-btn-style"><span class="icon-' . $skip_left . ' '.$this->blueprint['_blueprints_navigator-skip-button'].'"></span></button>';
        echo '<button class="pager skip-right icon-btn-style"><span class="icon-' . $skip_right . ' '.$this->blueprint['_blueprints_navigator-skip-button']. '"></span></button>';
        $nav_position = $this->blueprint[ '_blueprints_navigator-thumbs-position' ];
      } else {
        $nav_position = $this->blueprint[ '_blueprints_navigator-position' ];
      }

      echo '<div class="pzarc-navigator pzarc-navigator-' . $this->blueprint[ '_blueprints_short-name' ] .
          ' ' . $this->blueprint[ '_blueprints_navigator' ] .
          ' ' . $nav_position .
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

  /**
   * Class arc_Navigator_Tabbed
   */
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

  /**
   * Class arc_Navigator_Labels
   */
  class arc_Navigator_Labels extends arc_Navigator
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

  // Todo. these are the player type controls. << < o > >>
  /**
   * Class arc_Navigator_Buttons
   */
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

  /**
   * Class arc_Navigator_Bullets
   */
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

  /**
   * Class arc_Navigator_Numbers
   */
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

  /**
   * Class arc_Navigator_None
   */
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

  /**
   * Class arc_Navigator_Thumbs
   */
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
