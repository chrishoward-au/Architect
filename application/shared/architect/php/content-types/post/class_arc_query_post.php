<?php
/**
 * Project pizazzwp-architect.
 * File: class_arc_query_post.php
 * User: chrishoward
 * Date: 20/10/14
 * Time: 5:05 PM
 */

class arc_query_post  extends arc_query_generic{

  protected function content_filters($source, $overrides)
  {

    $this->query_options[ 'post_type' ] = 'post';
  }

} 