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
//var_dump($this->build->blueprint[ '_content_posts_exclude-posts' ]);
    $exclude=array();
    if (!empty($this->build->blueprint[ '_content_posts_exclude-posts' ])) {
      if ( ! is_array( $this->build->blueprint[ '_content_posts_exclude-posts' ] ) ) {
        $exclude = implode( ',', $this->build->blueprint[ '_content_posts_exclude-posts' ] );
      } else {
        $exclude = $this->build->blueprint[ '_content_posts_exclude-posts' ];
      }
    }
    if (!empty($this->build->blueprint[ '_content_posts_exclude-current-post' ])){
      $exclude[]=get_the_ID();
    }
    $this->query_options[ 'post_type' ] = 'post';
    $this->query_options['post__not_in']=$exclude;
  }

} 