<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_query_page.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 9:16 PM
   */
  class arc_query_page extends arc_query_generic
  {

    protected function content_filters($source, $overrides)
    {

      $this->query_options[ 'post_type' ] = 'page';
      if (!empty($this->criteria['_content_pages_specific-pages'])) {
        $this->query_options[ 'post__in' ]       = $this->criteria['_content_pages_specific-pages'];
      }

    }
  }