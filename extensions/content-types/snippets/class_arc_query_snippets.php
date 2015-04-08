<?php
/**
 * Project pizazzwp-architect.
 * File: class_arc_query_snippets.php
 * User: chrishoward
 * Date: 20/10/14
 * Time: 9:15 PM
 */

class arc_query_snippets extends arc_query_generic{

  protected function content_filters($source, $overrides)
  {
    $this->query_options[ 'post_type' ] = 'pz_snippets';
    if (!empty($this->criteria['_content_snippets_specific-snippets'])) {
      $this->query_options[ 'post__in' ]       = $this->criteria['_content_snippets_specific-snippets'];
    }

  }

} 