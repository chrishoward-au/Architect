<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 25/04/2014
   * Time: 9:11 PM
   */
  class arc_Navigator
  {

    protected  $nav_types = '';
    protected $blueprint = '';
    protected $navitems =array();
    protected $sizing ='medium';

    function __construct($blueprint,$navitems)
    {
      $this->blueprint = $blueprint;
      $this->navitems = $navitems;
      $this->sizing = ' '.$this->blueprint[ '_blueprints_navigator-sizing' ];
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

    function __descruct(){
      echo '</div>';

    }
  }

  class arc_Navigator_Tabbed extends arc_Navigator
  {
    function _construct(){
      $this->nav_types[] = __CLASS__;
    }
    function render()
    {
      $i=1;
      foreach ($this->navitems as $nav_item) {
        $active = ($i===1?' active':'');

        if ($this->blueprint[ '_blueprints_navigator-slider-engine' ] === 'bxslider') {
          echo '<a data-slide-index="'.$i++.'" style="cursor:pointer;">'.$nav_item.'</a>';
        } else {
          echo '<span class="swiper-pagination-switch'.$active.'">' . $nav_item . '</span>';
          $i++;
        }
      }
    }

  }

  class arc_Navigator_Accordion extends arc_Navigator
  {
    function _construct(){
      $this->nav_types[] = __CLASS__;

    }
    function render()
    {
      echo "<h2>A A A A A</h2>";
    }

  }

  class arc_Navigator_Buttons extends arc_Navigator
  {
    function _construct(){
      $this->nav_types[] = __CLASS__;

    }
    function render()
    {
      echo '<h2><< < [] >></h2>';
    }

  }

  class arc_Navigator_Bullets extends arc_Navigator
  {
    function _construct(){
      $this->nav_types[] = __CLASS__;

    }
    function render()
    {
      $i=1;
      foreach ($this->navitems as $nav_item) {
        $active = ($i===1?' active':'');
        if ($this->blueprint[ '_blueprints_navigator-slider-engine' ] === 'bxslider') {
          echo '<a data-slide-index="'.$i++.'" style="cursor:pointer;">'.$nav_item.'</a>';
        } else {
          echo '<span class="swiper-pagination-switch'.$this->sizing.$active.'"></span>';
          $i++;
        }
      }
    }

  }

  class arc_Navigator_Numbers extends arc_Navigator
  {
    function _construct(){
      $this->nav_types[] = __CLASS__;

    }
    function render()
    {
      $i=1;
      foreach ($this->navitems as $nav_item) {
        $active = ($i===1?' active':'');
        if ($this->blueprint[ '_blueprints_navigator-slider-engine' ] === 'bxslider') {
          echo '<a data-slide-index="'.$i++.'" style="cursor:pointer;">'.$nav_item.'</a>';
        } else {
          echo '<span class="swiper-pagination-switch'.$this->sizing.$active.'">'.$i++.'</span>';
        }
      }
    }

  }

  class arc_Navigator_Thumbs extends arc_Navigator
  {
    function _construct(){
      $this->nav_types[] = __CLASS__;

    }
    function render()
    {
      echo '<span class="dashicons dashicons-arrow-left"></span>';
      $i=1;
      foreach ($this->navitems as $nav_item) {
        $active = ($i===1?' active':'');
          echo '<span class="swiper-pagination-switch'.$this->sizing.$active.'">' . $nav_item . '</span>';
          $i++;
      }
      echo '<span class="dashicons dashicons-arrow-right"></span>';
    }

  }
