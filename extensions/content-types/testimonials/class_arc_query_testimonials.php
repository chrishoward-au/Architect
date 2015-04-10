<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_query_snippets.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 9:15 PM
   */
  class arc_query_testimonials extends arc_query_generic {

    protected function content_filters( $source, $overrides ) {

      $this->query_options[ 'post_type' ] = 'pz_testimonials';
      if ( ! empty( $this->criteria[ '_content_testimonials_specific-testimonials' ] ) ) {
          $specific_testimonials = null;
          foreach ( $this->criteria[ '_content_testimonials_specific-testimonials' ] as $k => $v ) {
            switch ( true ) {
              case ( is_numeric( $v ) ):
                $specific_testimonials[] = $v;
                break;
              case ( is_string( $this->criteria[ '_content_testimonials_specific-testimonials' ] ) && ($pos=strpos($v,':'))>0):
                $specific_testimonials[] = substr($v,0,($pos-1));
                break;
              default:
                $specific_testimonials[] = $v;
            }

          }

          $this->query_options[ 'post__in' ] = $specific_testimonials;

        }
    }

  }