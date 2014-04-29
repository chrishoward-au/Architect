<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 25/04/2014
   * Time: 9:11 PM
   */
  class arc_Navigator
  {

    public $nav_types = '';

    function __construct()
    {
      self::render();
    }

    function render()
    {
      echo '<h2>Navigator</h2>';
    }


  }

  class arc_Navigator_Tabbed extends arc_Navigator
  {
    function _construct(){
      $this->nav_types[] = __CLASS__;
      self::render();
    }
    function render()
    {
      echo "<h2>T T T T T</h2>";
    }

  }

  class arc_Navigator_Accordion extends arc_Navigator
  {
    function _construct(){
      $this->nav_types[] = __CLASS__;
      self::render();
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
      self::render();
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
      self::render();
    }
    function render()
    {
      echo "<h2>• • • • • •</h2>";
    }

  }

  class arc_Navigator_Numeric extends arc_Navigator
  {
    function _construct(){
      $this->nav_types[] = __CLASS__;
      self::render();
    }
    function render()
    {
      echo "<h2>1 2 3 4 5</h2>";
    }

  }

  class arc_Navigator_Thumbs extends arc_Navigator
  {
    function _construct(){
      $this->nav_types[] = __CLASS__;
      self::render();
    }
    function render()
    {
      echo "<h2>[ ] [ ] [ ] [ ] [ ]</h2>";
    }

  }
