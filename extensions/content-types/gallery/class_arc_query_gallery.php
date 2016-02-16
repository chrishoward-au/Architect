<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_query_gallery.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 2:26 AM
   */
  class arc_query_gallery extends arc_query_generic
  {

    protected function content_filters($source, $overrides)
    {

      if ($source === 'gallery') {

        $prefix         = '_content_galleries_';
        $gallery_source = !empty($overrides['ids']) ? 'ids' : $this->build->blueprint[ $prefix . 'gallery-source' ];

        if ($gallery_source === 'galleryplus' || $gallery_source === 'wpgallery') {
          if ($gallery_source === 'galleryplus') {
            $gallery_post = get_post($this->build->blueprint[ $prefix . 'galleryplus' ]);
          } else {
            $gallery_post = get_post($this->build->blueprint[ $prefix . 'wp-post-gallery' ]);
          }
          if (!empty($gallery_post)) {
            preg_match_all('/' . get_shortcode_regex() . '/s', $gallery_post->post_content, $matches);
            if (isset($matches[ 0 ][ 0 ])) {
              preg_match("/(?<=ids=\")([\\d,\\,])*/u", $matches[ 0 ][ 0 ], $ids);

              $this->query_options[ 'post_type' ]           = 'attachment';
              if (isset($ids[0])) {
                $this->query_options[ 'post__in' ] = explode(',', $ids[ 0 ]);
              }
              $this->query_options[ 'post_status' ]         = array('publish', 'inherit', 'private');
              $this->query_options[ 'ignore_sticky_posts' ] = true;
            }
          }
        } elseif ($gallery_source === 'images' || $gallery_source === 'ids') {
          if ($gallery_source === 'images') {
            $ids = !empty($overrides['ids']) ? $overrides['ids'] : $this->criteria[ $prefix . 'specific-images' ];
          } else {
            $ids = !empty($overrides['ids']) ? $overrides['ids'] : $this->criteria[ $prefix . 'specific-ids' ];
          }

          $this->query_options[ 'post_type' ]           = 'attachment';
          $this->query_options[ 'post__in' ]            = (!empty($ids) ? explode(',', $ids) : null);
          $this->query_options[ 'post_status' ]         = array('publish', 'inherit', 'private');
          $this->query_options[ 'ignore_sticky_posts' ] = true;
        } elseif ($gallery_source==='postimages'){

          $attached_images = get_attached_media( 'image',get_the_id() );

          if (!empty($attached_images)) {
            $this->query_options[ 'post_type' ]           = 'attachment';
            $this->query_options[ 'post__in' ]            = array_keys(($attached_images));
            $this->query_options[ 'post_status' ]         = array('publish', 'inherit', 'private');
            $this->query_options[ 'ignore_sticky_posts' ] = true;

          }
//          var_dump(get_the_id(),get_the_title(),get_attached_media( 'image' ));
        }
      }

    }
  }
