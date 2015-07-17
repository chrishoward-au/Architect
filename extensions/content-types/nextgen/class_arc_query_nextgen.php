<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_query_gallery.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 2:26 AM
   */
  class arc_query_nextgen extends arc_query_generic
  {

    // Replace standard get_custom_qquery with NGG specific one
    public function get_custom_query($overrides)
    {

      if (!class_exists('nggdb')) {
        return null;
      }
      $prefix = '_content_nextgen_';

      $gallery_source = $this->build->blueprint[ $prefix . 'nggallery' ];

      $results = array();
      $inc     = 0;
      $images  = nggdb::get_gallery($gallery_source);

      foreach ($images as $image) {

        // TODO: We should do the image conversion with BFI here!
        $results[ $inc ] = array('post_id'   => $image->gid,
                                 'title'     => stripslashes($image->alttext),
                                 'excerpt'   => stripslashes($image->description),
                                 'content'   => stripslashes($image->description),
                                 'thumb_url' => $image->thumbURL,
                                 'image_url' => $image->imageURL,
                                 'link_url'  => '');
        $inc++;


      };

      return $results;
    }

  }