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

    public static function render($type, $template, $source, &$data, &$section) { }

    /**
     * @param $section
     * @return mixed
     */
    static function set_data(&$section)
    {
      var_dump($section);
      $showbg_after_components                = ($section[ '_panels_design_background-position' ] != 'none' && ($section[ '_panels_design_components-position' ] == 'top' || $section[ '_panels_design_components-position' ] == 'left'));
      $showbg_before_components               = ($section[ '_panels_design_background-position' ] != 'none' && ($section[ '_panels_design_components-position' ] == 'bottom' || $section[ '_panels_design_components-position' ] == 'right'));
      $data[ 'postid' ]                       = get_the_ID();
      $data[ 'poststatus' ]                   = get_post_status();
      $data[ 'permalink' ]                    = get_the_permalink();
      $post_format                            = get_post_format();
      $data [ 'postformat' ]                  = (empty($post_format) ? 'standard' : $post_format);
      $data[ 'panel-open' ][ 'bgimagetl' ]  = ($showbg_before_components ? get_the_post_thumbnail(null, array($section[ '_panels_design_background-image-width' ],
                                                                                                                $section[ '_panels_design_background-image-width' ])) : null); //WP seems to smartly figure out which of its saved images to use! Now we jsut gotta get it t work with focal point
      $data[ 'panel-close' ][ 'bgimagebr' ] = ($showbg_after_components ? get_the_post_thumbnail(null, array($section[ '_panels_design_background-image-width' ],
                                                                                                               $section[ '_panels_design_background-image-width' ])) : null);
      $data[ 'image' ][ 'image' ]             = get_the_post_thumbnail(null, 'thumbnail');
      $data[ 'meta' ][ 'datetime' ]           = get_the_date();
      $data[ 'meta' ][ 'fdatetime' ]          = date_i18n($section[ '_panels_design_meta-date-format' ], strtotime(get_the_date()));
      $data[ 'meta' ][ 'categorieslinks' ]    = get_the_category_list(', ');
      $data[ 'categories' ]         = arc_tax_string_list(get_the_category(), 'category-', '', ' ');
      $data[ 'meta' ][ 'tagslinks' ]          = get_the_tag_list(null, ', ');
      $data[ 'tags' ]               = arc_tax_string_list(get_the_tags(), 'tag-', '', ' ');
      $data[ 'meta' ][ 'authorlink' ]         = get_author_posts_url(get_the_author_meta('ID'));
      $data[ 'meta' ][ 'authorname' ]         = get_the_author_meta('display_name');
      $data[ 'meta' ][ 'commentcount' ]       = get_comments_number();
      $data[ 'meta' ][ 'editlink' ]           = '<span class="edit-link"><a class="post-edit-link" href="' . get_edit_post_link() . '" title="Edit post ' . get_the_title() . '">Edit</a></span>';

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

    public function process_generics(&$data, $line, $source)
    {
      //todo: make sure source is actual WP valid eg. soemthings might be attachment
      // Do any generic replacements
      $line = str_replace('{{postid}}', $data[ 'postid' ], $line);
      $line = str_replace('{{permalink}}', $data[ 'permalink' ], $line);
      $line = str_replace('{{closelink}}', '</a>', $line);
      $line = str_replace('{{categories}}', $data['categories'], $line);
      $line = str_replace('{{tags}}', $data['tags'], $line);
      $line = str_replace('{{poststatus}}', $data['poststatus'], $line);
      $line = str_replace('{{postformat}}', $data['postformat'], $line);
      $line = str_replace('{{posttype}}', $source, $line);

      return $line;
    }
  }

  class arc_Panel_Wrapper extends arc_Panel
  {
    public static function render($type, $template, $source, &$data, &$section)
    {
      foreach ($data[ 'panel-open' ] as $key => $value) {
        $template = str_replace('{{' . $key . '}}', $value, $template);
      }
      foreach ($data[ 'panel-close' ] as $key => $value) {
        $template = str_replace('{{' . $key . '}}', $value, $template);
      }

      return parent::process_generics($data, $template, $source);
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
    public static function render($type, $template, $source, &$data, &$section)
    {
      switch ($source) {
        case 'post':
        case 'page':
          $template = str_replace('{{title}}', get_the_title(), $template);
          if (true) {
            $template = str_replace('{{postlink}}', '<a href="' . get_the_permalink() . '">', $template);
            $template = str_replace('{{closepostlink}}', '</a>', $template);
          }
      };

      // this only works for posts! need different rules for different types! :S
      return parent::process_generics($data, $template, $source);
    }
  }


  class arc_Panel_Meta extends arc_Panel
  {
    public static function render($type, $template, $source, &$data, &$section)
    {
//      var_dump($type, $template, $source, $data);
      // get $metaX definition and construct string, then replace metaXinnards
      switch ($source) {
        case 'post':
        case 'page':
          $template = str_replace('{{datetime}}', $data[ 'meta' ][ 'datetime' ], $template);
          $template = str_replace('{{fdatetime}}', $data[ 'meta' ][ 'fdatetime' ], $template);
          $template = str_replace('{{authorname}}', $data[ 'meta' ][ 'authorname' ], $template);
          $template = str_replace('{{authorlink}}', $data[ 'meta' ][ 'authorlink' ], $template);
          $template = str_replace('{{categories}}', $data[ 'meta' ][ 'categories' ], $template);
          $template = str_replace('{{categorieslinks}}', $data[ 'meta' ][ 'categorieslinks' ], $template);
          $template = str_replace('{{tags}}', $data[ 'meta' ][ 'tags' ], $template);
          $template = str_replace('{{tagslinks}}', $data[ 'meta' ][ 'tagslinks' ], $template);
          $template = str_replace('{{commentcount}}', $data[ 'meta' ][ 'commentcount' ], $template);
          $template = str_replace('{{editlink}}', $data[ 'meta' ][ 'editlink' ], $template);

      }
      return parent::process_generics($data, $template, $source);
    }

  }


  class arc_Panel_Image extends arc_Panel
  {
    public static function render($type, $template, $source, &$data, &$section)
    {
      if (true) {
        $template = str_replace('{{postlink}}', '<a href="' . get_the_permalink() . '">', $template);
        $template = str_replace('{{closepostlink}}', '</a>', $template);
      }
      if (true) {
        $image    = get_post(get_post_thumbnail_id());
        $template = str_replace('{{captioncode}}', $image->post_excerpt, $template);
      }
      foreach ($data[ 'image' ] as $key => $value) {
        $template = str_replace('{{' . $key . '}}', $value, $template);
      }

      return parent::process_generics($data, $template, $source);
    }

  }


  class arc_Panel_Content extends arc_Panel
  {
    public static function render($type, $template, $source, &$data, &$section)
    {
      switch ($source) {
        case 'post':
        case 'page':
          $template = str_replace('{{content}}', get_the_content(), $template);
      };
      return parent::process_generics($data, $template, $source);
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
    public static function render($type, $template, $source, &$data, &$section)
    {
      switch ($source) {
        case 'post':
        case 'page':
          $template = str_replace('{{excerpt}}', get_the_excerpt(), $template);
      };
      if ($section['_panels_design_thumb-position'] != 'none') {
        $template = str_replace('{{image-in-content}}', $data[ 'image' ][ 'image' ], $template);
      }

//_panels_design_thumb-position


      return parent::process_generics($data, $template, $source);
    }

  }


  class arc_Panel_Custom extends arc_Panel
  {
    public static function render($type, $template, $source, &$data, &$section)
    {
      // this only works for posts! need different rules for different types! :S
      return parent::process_generics($data, $template, $source);
    }

  }


