<?php
/**
 * Project pizazzwp-architect.
 * File: class_arc_query_post.php
 * User: chrishoward
 * Date: 20/10/14
 * Time: 5:05 PM
 */

class arc_query_multi  extends arc_query_generic{

  protected function content_filters($source, $overrides) {
    if ( ! empty( $this->build->blueprint[ '_content_multi_post-field_types' ] ) ) {
      $this->query_options[ 'post_type' ] = $this->build->blueprint[ '_content_multi_post-field_types' ];
    }
    if ( ! empty( $this->build->blueprint[ '_content_multi_specific-IDs' ] ) ) {
      $this->query_options[ 'post_type' ] = explode(',',$this->build->blueprint[ '_content_multi_specific-IDs' ]);
    }
  }

} 
