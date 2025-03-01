<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_query_snippets.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 9:15 PM
   */
  class arc_query_snippets extends arc_query_generic {

     function content_filters($source, $overrides) {
      $this->query_options['post_type'] = 'pz_snippets';
      if (!empty($this->criteria['_content_snippets_specific-snippets'])) {
        $specific_snippets = NULL;
        foreach ($this->criteria['_content_snippets_specific-snippets'] as $k => $v) {
          switch (TRUE) {
            case (is_numeric($v)):
              $specific_snippets[] = $v;
              break;
            case (is_string($this->criteria['_content_snippets_specific-snippets']) && ($pos = strpos($v, ':')) > 0):
              $specific_snippets[] = substr($v, 0, ($pos - 1));
              break;
            default:
              $specific_snippets[] = $v;
          }

        }

        $this->query_options['post__in'] = $specific_snippets;
      }

      $exclude = array();
//    if (!empty($this->criteria[ '_content_snippets_exclude-snippets' ])) {
//      if ( ! is_array( $this->criteria[ '_content_snippets_exclude-snippets' ] ) ) {
//        $exclude = implode( ',', $this->criteria[ '_content_snippets_exclude-snippets' ] );
//      } else {
//        $exclude = $this->criteria[ '_content_snippets_exclude-snippets' ];
//      }
//    }
      if (!empty($this->criteria['_content_snippets_exclude-current-snippet'])) {
        $exclude[] = get_the_ID();
      }
      $this->query_options['post__not_in'] = $exclude;


    }

  }
