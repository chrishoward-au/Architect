<?php
/**
 * Created by PhpStorm.
 * User: chrishoward
 * Date: 25/04/2014
 * Time: 9:11 PM
 */

class arc_Pagination {


  function __construct($pagination_type)
  {
    self::render();
  }

  function render() {
    echo "<h2>Pagination</h2>";
  }
}


  class arc_Pagination_WP extends arc_Pagination {

  }
  class arc_Pagination_PageNavi extends arc_Pagination {

}
