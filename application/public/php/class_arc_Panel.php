<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 29/04/2014
   * Time: 12:16 PM
   */
  class arc_Panel
  {
//    private $data;

    //TODO: Shouldn't data be a this?
    public static function render($type, &$template, $source, &$data, &$section) { }

    /**
     * @param $section
     * @return mixed
     */
    static function set_data(&$section)
    {
      $toshow = json_decode($section[ '_panels_design_preview' ], true);
      $data   = array();
      // TODO: Will need to refine these to be based on content type
      if ($toshow[ 'title' ][ 'show' ]) {
        $data[ 'title' ] = get_the_title();
      }

      if ($toshow[ 'content' ][ 'show' ]) {
        $data[ 'content' ] = get_the_content();
      }

      if ($toshow[ 'excerpt' ][ 'show' ]) {
        $data[ 'excerpt' ] = get_the_excerpt();
      }

      if ($toshow[ 'image' ][ 'show' ] || $section[ '_panels_design_thumb-position' ] != 'none') {
//        if (false)
//        {
//          $post_image = ($this->panel_info[ '_panels_design_thumb-position' ] != 'none') ? job_resize($thumb_src, $params, PZARC_CACHE_PATH, PZARC_CACHE_URL) : null;
//        }
        // BFI

        $width  = (int)str_replace('px', '', $section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
        $height = (int)str_replace('px', '', $section[ '_panels_design_image-max-dimensions' ][ 'height' ]);

        $data[ 'image' ][ 'image' ]   = get_the_post_thumbnail(null,
                                                               array($width,
                                                                     $height,
                                                                     'bfi_thumb' => true,
                                                                     'crop'      => true)
        );
        $image                        = get_post(get_post_thumbnail_id());
        $data[ 'image' ][ 'caption' ] = $image->post_excerpt;
      }
      if ($toshow[ 'meta1' ][ 'show' ] ||
          $toshow[ 'meta2' ][ 'show' ] ||
          $toshow[ 'meta3' ][ 'show' ]
      ) {
        $data[ 'meta' ][ 'datetime' ]        = get_the_date();
        $data[ 'meta' ][ 'fdatetime' ]       = date_i18n($section[ '_panels_design_meta-date-format' ], strtotime(get_the_date()));
        $data[ 'meta' ][ 'categorieslinks' ] = get_the_category_list(', ');
        $data[ 'categories' ]                = arc_tax_string_list(get_the_category(), 'category-', '', ' ');
        $data[ 'meta' ][ 'tagslinks' ]       = get_the_tag_list(null, ', ');
        $data[ 'tags' ]                      = arc_tax_string_list(get_the_tags(), 'tag-', '', ' ');
        $data[ 'meta' ][ 'authorlink' ]      = get_author_posts_url(get_the_author_meta('ID'));
        $data[ 'meta' ][ 'authorname' ]      = get_the_author_meta('display_name');
        $data[ 'meta' ][ 'comments-count' ]  = get_comments_number();
      }

      if ($toshow[ 'custom1' ][ 'show' ] ||
          $toshow[ 'custom2' ][ 'show' ] ||
          $toshow[ 'custom3' ][ 'show' ]
      ) {
        //TODO: Add custom content stuff
      }

      // NEVER include HTML in these, only should get WP values.
      $showbgimage           = (has_post_thumbnail()
              && $section[ '_panels_design_background-position' ] != 'none'
              && ($section[ '_panels_design_components-position' ] == 'top' || $section[ '_panels_design_components-position' ] == 'left'))
          || ($section[ '_panels_design_background-position' ] != 'none'
              && ($section[ '_panels_design_components-position' ] == 'bottom' || $section[ '_panels_design_components-position' ] == 'right'));
      $data[ 'postid' ]      = get_the_ID();
      $data[ 'poststatus' ]  = get_post_status();
      $data[ 'permalink' ]   = get_the_permalink();
      $post_format           = get_post_format();
      $data [ 'postformat' ] = (empty($post_format) ? 'standard' : $post_format);

      // Need to setup for break points.

      //  data-imagesrcs ="1,2,3", data-breakpoints="1,2,3". Then use js to change src.
      $width = (int)str_replace('px', '', $section[ '_panels_design_background-image-max' ][ 'width' ]);
      if ($section[ '_panels_settings_panel-height-type' ] === 'fixed') {
        $height = (int)str_replace('px', '', $section[ '_panels_settings_panel-height' ][ 'height' ]);
      } else {
        $height = (int)str_replace('px', '', $section[ '_panels_design_background-image-max' ][ 'height' ]);
      }
      $data[ 'bgimage' ] = ($showbgimage ? get_the_post_thumbnail(null, array($width,
                                                                              $height,
                                                                              'bfi_thumb' => true,
                                                                              'crop'      => false,
                                                                      )
      ) : null); //WP seems to smartly figure out which of its saved images to use! Now we jsut gotta get it t work with focal point

//        if (strpos($data[ 'image' ][ 'image' ], '<img') === 0) {
//          preg_match_all("/width=\"(\\d)*\"/uiUm", $data[ 'image' ][ 'image' ], $widthm);
//          preg_match_all("/src=\"(.)*\"/uiUm", $data[ 'image' ][ 'image' ], $srcm);
//          preg_match_all("/alt=\"(.)*\"/uiUm", $data[ 'image' ][ 'image' ], $altm);
//          $data[ 'image' ][ 'width' ]   = str_replace(array('width=', '"'), '', $widthm[ 0 ][ 0 ]);
//          $data[ 'image' ][ 'imgsrc' ]  = str_replace(array('src=', '"'), '', $srcm[ 0 ][ 0 ]);
//          $data[ 'image' ][ 'alttext' ] = str_replace(array('alt=', '"'), '', $altm[ 0 ][ 0 ]);
//        }
//      var_dump($data);
      return $data;

    }

    public function process_generics(&$data, $line, $source, &$section)
    {
      //todo: make sure source is actual WP valid eg. soemthings might be attachment
      // Do any generic replacements
      $line      = str_replace('{{postid}}', $data[ 'postid' ], $line);
      $line      = str_replace('{{title}}', $data[ 'title' ], $line);
      $line      = str_replace('{{permalink}}', $data[ 'permalink' ], $line);
      $line      = str_replace('{{closelink}}', '</a>', $line);
      $line      = str_replace('{{categories}}', $data[ 'categories' ], $line);
      $line      = str_replace('{{tags}}', $data[ 'tags' ], $line);
      $line      = str_replace('{{poststatus}}', $data[ 'poststatus' ], $line);
      $line      = str_replace('{{postformat}}', $data[ 'postformat' ], $line);
      $line      = str_replace('{{posttype}}', $source, $line);
      $pzclasses = 'pzarc-components ';
      $pzclasses .= ($section[ '_panels_design_components-position' ] === 'left' || $section[ '_panels_design_components-position' ] === 'right') ? 'vertical-content ' : '';

      $line = str_replace('{{pzclasses}}', $pzclasses, $line);

      return $line;
    }
  }

  class arc_Panel_Wrapper extends arc_Panel
  {
    public static function render($type, $template, $source, &$data, &$section)
    {
//      foreach ($data[ 'components-open' ] as $key => $value) {
//        $template[ $type ] = str_replace('{{' . $key . '}}', $value, $template[ $type ]);
//      }
//      foreach ($data[ 'components-close' ] as $key => $value) {
//        $template[ $type ] = str_replace('{{' . $key . '}}', $value, $template[ $type ]);
//      }

      $template[ $type ] = str_replace('{{using-bg-image}}', (!empty($data[ 'bgimage' ]) ? 'has-bgimage ' : 'no-bgimage '), $template[ $type ]);

      return parent::process_generics($data, $template[ $type ], $source, $section);
    }
  }

  /**
   * Class arc_Panel_Title
   */
  class arc_Panel_Title extends arc_Panel
  {
    /**
     * @param $type (Line type, e.g.excerpt, meta, image, title etc)
     * @param $template
     * @param $source (Post type source - eg. post, page, gallery)
     * @param $data
     * @param $section
     * @return mixed|void
     */
    public static function render($type, &$template, $source, &$data, &$section)
    {
      switch ($source) {
        case 'defaults':
        case 'post':
        case 'page':
          $template[ $type ] = str_replace('{{title}}', $data[ 'title' ], $template[ $type ]);
          if ($section[ '_panels_design_link-titles' ]) {
            $template[ $type ] = str_replace('{{postlink}}', $template[ 'postlink' ], $template[ $type ]);
            $template[ $type ] = str_replace('{{closepostlink}}', '</a>', $template[ $type ]);
          }
      };

      // this only works for posts! need different rules for different types! :S
      return parent::process_generics($data, $template[ $type ], $source, $section);
    }
  }


  class arc_Panel_Meta extends arc_Panel
  {
    public static function render($type, &$template, $source, &$data, &$section)
    {
      // get $metaX definition and construct string, then replace metaXinnards
      switch ($source) {
        case 'defaults':
        case 'post':
        case 'page':
          $template[ $type ] = str_replace('{{datetime}}', $data[ 'meta' ][ 'datetime' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{fdatetime}}', $data[ 'meta' ][ 'fdatetime' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{authorname}}', $data[ 'meta' ][ 'authorname' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{authorlink}}', $data[ 'meta' ][ 'authorlink' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{categories}}', $data[ 'meta' ][ 'categories' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{categorieslinks}}', $data[ 'meta' ][ 'categorieslinks' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{tags}}', $data[ 'meta' ][ 'tags' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{tagslinks}}', $data[ 'meta' ][ 'tagslinks' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{commentslink}}', $template[ 'comments-link' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{commentscount}}', $data[ 'comments-count' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{editlink}}', $template[ 'editlink' ], $template[ $type ]);


      }

      return parent::process_generics($data, $template[ $type ], $source, $section);
    }

  }


  class arc_Panel_Image extends arc_Panel
  {
    public static function render($type, &$template, $source, &$data, &$section)
    {
      if ($section[ '_panels_design_link-image' ]) {
        $template[ $type ] = str_replace('{{postlink}}', $template[ 'postlink' ], $template[ $type ]);
        $template[ $type ] = str_replace('{{closepostlink}}', '</a>', $template[ $type ]);
      }

      if ($section[ '_panels_design_image-captions' ]) {
        $template[ $type ] = str_replace('{{captioncode}}', $data[ 'image' ][ 'caption' ], $template[ $type ]);
      }

      $template[ $type ] = str_replace('{{image}}', $data[ 'image' ][ 'image' ], $template[ $type ]);

      if (!empty($section[ '_panels_design_centre-image' ])) {
        $template[ $type ] = str_replace('{{centred}}', 'centred', $template[ $type ]);
      }

      if (empty($data[ 'image' ][ 'image' ])) {
        $template[ $type ] = '';
      }

//      foreach ($data[ 'image' ] as $key => $value) {
//        $template[ $type ] = str_replace('{{' . $key . '}}', $value, $template[ $type ]);
//      }

      return parent::process_generics($data, $template[ $type ], $source, $section);
    }

  }

  class arc_Panel_bgimage extends arc_Panel
  {
    public static function render($type, &$template, $source, &$data, &$section)
    {
      $template[ $type ] = str_replace('{{bgimage}}', $data[ 'bgimage' ], $template[ $type ]);
      $template[ $type ] = str_replace('{{trim-scale}}', ' fill ' . $section[ '_panels_design_background-image-resize' ], $template[ $type ]);

      return parent::process_generics($data, $template[ $type ], $source, $section);
    }

  }


  class arc_Panel_Content extends arc_Panel
  {
    public static function render($type, &$template, $source, &$data, &$section)
    {
      switch ($source) {
        case 'defaults':
        case 'post':
        case 'page':
          $template[ $type ] = str_replace('{{content}}', $data[ 'content' ], $template[ $type ]);
      };
      if ($section[ '_panels_design_thumb-position' ] != 'none') {
        if (!empty($data[ 'image' ][ 'image' ])) {
          $template[ $type ] = str_replace('{{image-in-content}}', $template[ 'image' ], $template[ $type ]);

          if ($section[ '_panels_design_image-captions' ]) {
            $template[ $type ] = str_replace('{{captioncode}}', '<span class="caption">' . $data[ 'image' ][ 'caption' ] . '</span>', $template[ $type ]);
          }

          $template[ $type ] = str_replace('{{image}}', $data[ 'image' ][ 'image' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{incontent}}', 'in-content-thumb', $template[ $type ]);

          if ($section[ '_panels_design_link-image' ]) {
            $template[ $type ] = str_replace('{{postlink}}', $template[ 'postlink' ], $template[ $type ]);
            $template[ $type ] = str_replace('{{closepostlink}}', '</a>', $template[ $type ]);
          }
        }
        if (empty($data[ 'image' ][ 'image' ]) && $section[ '_panels_design_maximize-content' ]) {
          //TODO: Add an option to set if width spreads
          $template[ $type ] = str_replace('{{nothumb}}', 'nothumb', $template[ $type ]);
        }
      }

      return parent::process_generics($data, $template[ $type ], $source, $section);
    }

  }


  class arc_Panel_Excerpt extends arc_Panel
  {
    /**
     * @param $type
     * @param $template
     * @param $source
     * @param $data
     * @param $section
     * @return mixed|void
     */
    public static function render($type, &$template, $source, &$data, &$section)
    {
      //    var_dump($data);
      switch ($source) {
        case 'defaults':
        case 'post':
        case 'page':
          $template[ $type ] = str_replace('{{excerpt}}', $data[ 'excerpt' ], $template[ $type ]);
      };

      //  var_dump($section[ '_panels_design_thumb-position' ]);
      if ($section[ '_panels_design_thumb-position' ] != 'none') {
        if (!empty($data[ 'image' ][ 'image' ]) && !empty($section[ '_panels_design_thumb-position' ])) {
          $template[ $type ] = str_replace('{{image-in-content}}', $template[ 'image' ], $template[ $type ]);

          if ($section[ '_panels_design_image-captions' ]) {
            $template[ $type ] = str_replace('{{captioncode}}', '<span class="caption">' . $data[ 'image' ][ 'caption' ] . '</span>', $template[ $type ]);
          }

          $template[ $type ] = str_replace('{{image}}', $data[ 'image' ][ 'image' ], $template[ $type ]);
          $template[ $type ] = str_replace('{{incontent}}', 'in-content-thumb', $template[ $type ]);

          if ($section[ '_panels_design_link-image' ]) {
            $template[ $type ] = str_replace('{{postlink}}', $template[ 'postlink' ], $template[ $type ]);
            $template[ $type ] = str_replace('{{closepostlink}}', '</a>', $template[ $type ]);
          }
        }
        if (empty($data[ 'image' ][ 'image' ]) && $section[ '_panels_design_maximize-content' ]) {
          //TODO: Add an option to set if width spreads
          $template[ $type ] = str_replace('{{nothumb}}', 'nothumb', $template[ $type ]);
        }
      }

//_panels_design_thumb-position


      return parent::process_generics($data, $template[ $type ], $source, $section);
    }

  }


  class arc_Panel_Custom extends arc_Panel
  {
    public static function render($type, &$template, $source, &$data, &$section)
    {
      // this only works for posts! need different rules for different types! :S
      return parent::process_generics($data, $template, $source, $section);
    }

  }


