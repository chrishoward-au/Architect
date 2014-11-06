<?php
/**
 * Project pizazzwp-architect.
 * File: class_arc_query_slides.php
 * User: chrishoward
 * Date: 20/10/14
 * Time: 9:14 PM
 */

class arc_query_slide extends arc_query_generic{

  protected function content_filters($source, $overrides)
  {

    $this->query_options[ 'post_type' ] = 'pzsp-slides';
  }

} 