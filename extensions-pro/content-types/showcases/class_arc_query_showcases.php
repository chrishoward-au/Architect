<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_query_snippets.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 9:15 PM
   */
  class arc_query_showcases extends arc_query_generic {

    protected function content_filters( $source, $overrides ) {

      $this->query_options[ 'post_type' ] = 'pz_showcases';
      if ( ! empty( $this->criteria[ '_content_showcases_specific-showcases' ] ) ) {
          $specific_showcases = null;
          foreach ( $this->criteria[ '_content_showcases_specific-showcases' ] as $k => $v ) {
            switch ( true ) {
              case ( is_numeric( $v ) ):
                $specific_showcases[] = $v;
                break;
              case ( is_string( $this->criteria[ '_content_showcases_specific-showcases' ] ) && ($pos=strpos($v,':'))>0):
                $specific_showcases[] = substr($v,0,($pos-1));
                break;
              default:
                $specific_showcases[] = $v;
            }

          }

          $this->query_options[ 'post__in' ] = $specific_showcases;

        }
    }

  }