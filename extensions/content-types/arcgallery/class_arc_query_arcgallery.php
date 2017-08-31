<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_query_snippets.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 9:15 PM
   */
  class arc_query_arcgallery extends arc_query_generic {

    protected function content_filters( $source, $overrides ) {

      $this->query_options[ 'post_type' ] = 'pz_arcgallery';
      if ( ! empty( $this->criteria[ '_content_arcgallery_specific-arcgallery' ] ) ) {
          $specific_arcgallery = null;
          foreach ( $this->criteria[ '_content_arcgallery_specific-arcgallery' ] as $k => $v ) {
            switch ( true ) {
              case ( is_numeric( $v ) ):
                $specific_arcgallery[] = $v;
                break;
              case ( is_string( $this->criteria[ '_content_arcgallery_specific-arcgallery' ] ) && ($pos=strpos($v,':'))>0):
                $specific_arcgallery[] = substr($v,0,($pos-1));
                break;
              default:
                $specific_arcgallery[] = $v;
            }

          }

          $this->query_options[ 'post__in' ] = $specific_arcgallery;

        }
    }

  }