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
      pzdb('pages content filters');
      $this->query_options[ 'post_type' ] = 'page';
      if (!empty($this->criteria['_content_pages_specific-pages'])) {
        $this->query_options[ 'post__in' ]       = $this->criteria['_content_pages_specific-pages'];
      }

      $exclude=array();
      if (!empty($this->build->blueprint[ '_content_pages_exclude-pages' ])) {
        if ( ! is_array( $this->build->blueprint[ '_content_pages_exclude-pages' ] ) ) {
          $exclude = implode( ',', $this->build->blueprint[ '_content_pages_exclude-pages' ] );
        } else {
          $exclude = $this->build->blueprint[ '_content_pages_exclude-pages' ];
        }
      }
      if (!empty($this->build->blueprint[ '_content_pages_exclude-current-page' ])){
        $exclude[]=get_the_ID();
      }
      $this->query_options['post__not_in']=$exclude;
    }

  }