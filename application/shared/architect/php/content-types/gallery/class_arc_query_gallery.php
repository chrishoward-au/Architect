<?php
/**
 * Project pizazzwp-architect.
 * File: class_arc_query_gallery.php
 * User: chrishoward
 * Date: 20/10/14
 * Time: 2:26 AM
 */

  class arc_query_gallery extends arc_query_generic {

    protected function content_filters($source,$overrides) {

      switch ($source) {
        case 'gallery':

          $prefix         = '_content_galleries_';
          $gallery_source = !empty($overrides) ? 'ids' : $this->build->blueprint[ $prefix . 'gallery-source' ];

          switch ($gallery_source) {

            case 'galleryplus':
              $gallery_post = get_post($this->build->blueprint[ $prefix . 'galleryplus' ]);
              preg_match_all('/' . get_shortcode_regex() . '/s', $gallery_post->post_content, $matches);
              $ids = '';
              foreach ($matches[ 0 ] as $match) {
                if (strpos($match, '[gallery ids=') === 0) {
                  $ids                                    = str_replace('[gallery ids="', '', $match);
                  $ids                                    = str_replace('"]', '', $ids);
                  $this->query_options[ 'post_type' ]           = 'attachment';
                  $this->query_options[ 'post__in' ]            = explode(',', $ids);
                  $this->query_options[ 'post_status' ]         = array('publish', 'inherit', 'private');
                  $this->query_options[ 'ignore_sticky_posts' ] = true;
                  // Only do first one
                  break;
                }
              }
              break;

            case 'images':
            case 'ids':

              $ids = !empty($overrides) ? $overrides : $this->criteria[ $prefix . 'specific-images' ];

              $this->query_options[ 'post_type' ]           = 'attachment';
              $this->query_options[ 'post__in' ]            = (!empty($ids) ? explode(',', $ids) : null);
              $this->query_options[ 'post_status' ]         = array('publish', 'inherit', 'private');
              $this->query_options[ 'ignore_sticky_posts' ] = true;
              break;
          }
          break;

      }
    }
  }
