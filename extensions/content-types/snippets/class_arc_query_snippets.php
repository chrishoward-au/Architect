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
      $specific_snippets = null;
      foreach ( $this->criteria[ '_content_snippets_specific-snippets' ] as $k => $v ) {
        switch ( true ) {
          case ( is_numeric( $v ) ):
            $specific_snippets[] = $v;
            break;
          case ( is_string( $this->criteria[ '_content_snippets_specific-snippets' ] ) && ($pos=strpos($v,':'))>0):
            $specific_snippets[] = substr($v,0,($pos-1));
            break;
          default:
            $specific_snippets[] = $v;
        }

      }

      $this->query_options[ 'post__in' ] = $specific_snippets;
    }

  }

} 