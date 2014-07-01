<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 29/04/2014
   * Time: 12:16 PM
   */
  // TODO: These should also definethe content filtering menu in Blueprints options :/

  class arc_Panel_gallery
  {
//    private $data;

    //TODO: Shouldn't data be a this?
    /**
     * @param $component : Component type
     * @param $panel_def : Panel definition
     * @param $content_type : Content type
     * @param $data : Post data array
     * @param $section : Section settings
     */
    // $panel_def is not by reference to  ensure it doesn't get changed accidentally.
    public static function render($component, $panel_def, $content_type, &$data, &$section) { }

    static function panel_def()
    {

//      $panel_def[ 'wrapper' ]  = '{{bgimagetl}}<div id="galleryimage-{{postid}}" class="pzarc-{{classname}}  galleryimage-{{postid}} galleryimage {{pzclasses}}">{{wrapperinnards}}</div>{{bgimagebr}}';
//      $panel_def[ 'header' ]   = '<header class="entry-header">{{headerinnards}}</header>';
//      $panel_def[ 'title' ]    = '<h3 class="entry-title">{{title}}</h3>';
//      $panel_def[ 'excerpt' ]  = ' <div class="entry-excerpt">{{excerpt}}</div>';
//      $panel_def[ 'content' ]  = ' <div class="entry-content">{{content}}</div>';
//      $panel_def[ 'footer' ]   = '<footer class="entry-meta">{{footerinnards}}</footer>';
//      $panel_def[ 'postlink' ] = '<a href="{{permalink}}" title="{{title}}">';
//      $panel_def[ 'image' ]    = '<figure class="entry-thumbnail {{incontent}}">{{postlink}}<img width="{{width}}" src="{{image}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}">{{closepostlink}}{{captioncode}}</figure>';
//      // Need atrick to include caption to hide when responsive and caption is description
//      $panel_def[ 'caption' ] = '<figcaption class="caption">{{caption}}</figcaption>';


      //TODO: Need to get a way to always wrap components in pzarc-compenents div.Problem is...dev has to create definition correctly.

      // Using this for gallery at themoment, butprobablycan trum it down.
      $panel_def[ 'components-open' ]  = '<article id="post-{{postid}}" class="{{mimic-block-type}} post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{categories}} {{tags}} {{pzclasses}}">';
      $panel_def[ 'components-close' ] = '</article>';
      $panel_def[ 'postlink' ]         = '<a href="{{permalink}}" title="{{title}}">';
      $panel_def[ 'header' ]           = '<header class="entry-header">{{headerinnards}}</header>';
      $panel_def[ 'title' ]            = '<h1 class="entry-title">{{postlink}}{{title}}{{closepostlink}}</h1>';
      $panel_def[ 'meta1' ]            = '<div class="entry-meta entry-meta1">{{meta1innards}}</div>';
      $panel_def[ 'meta2' ]            = '<div class="entry-meta entry-meta2">{{meta2innards}}</div>';
      $panel_def[ 'meta3' ]            = '<div class="entry-meta entry-meta3">{{meta3innards}}</div>';
      $panel_def[ 'datetime' ]         = '<span class="entry-date"><a href="{{permalink}}"<time class="entry-date" datetime="{{datetime}}">{{fdatetime}}</time></span></a></span>';
      $panel_def[ 'categories' ]       = '<span class="categories-links">{{categorieslinks}}</span>';
      $panel_def[ 'tags' ]             = '<span class="tags-links">{{tagslinks}}</span>';
      $panel_def[ 'author' ]           = '<span class="byline"><span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span></span>';
      $panel_def[ 'email' ]            = '<span class="byline email"><span class="author vcard"><a class="url fn n" href="mailto:{{authoremail}}" title="Email {{authorname}}" rel="author">{{authoremail}}</a></span></span>';
      //     $panel_def[ 'image' ]       = '<figure class="entry-thumbnail {{incontent}}">{{postlink}}<img width="{{width}}" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}">{{closepostlink}}{{captioncode}}</figure>';
      $panel_def[ 'image' ]         = '<figure class="entry-thumbnail {{incontent}} {{centred}}">{{postlink}}{{image}}{{closelink}}{{captioncode}}</figure>';
      $panel_def[ 'bgimage' ]       = '<figure class="entry-bgimage pzarc-bg-image {{trim-scale}}">{{bgimage}}</figure>';
      $panel_def[ 'caption' ]       = '<figcaption class="caption">{{caption}}</figcaption>';
      $panel_def[ 'content' ]       = ' <div class="entry-content {{nothumb}}">{{image-in-content}}{{content}}</div>';
      $panel_def[ 'custom1' ]       = '<div class="entry-customfieldgroup entry-customfieldgroup-1">{{custom1innards}}</div>';
      $panel_def[ 'custom2' ]       = '<div class="entry-customfieldgroup entry-customfieldgroup-2">{{custom2innards}}</div>';
      $panel_def[ 'custom3' ]       = '<div class="entry-customfieldgroup entry-customfieldgroup-3">{{custom3innards}}</div>';
      $panel_def[ 'cfield' ]        = '<div class="entry-customfield entry-customfield-{{cfieldname}}">{{cfieldcontent}}</div>';
      $panel_def[ 'footer' ]        = '<footer class="entry-footer">{{footerinnards}}</footer>';
      $panel_def[ 'excerpt' ]       = ' <div class="entry-excerpt {{nothumb}}">{{image-in-content}}{{excerpt}}</div>';
      $panel_def[ 'feature' ]       = '{{feature}}';
      $panel_def[ 'editlink' ]      = '<span class="edit-link"><a class="post-edit-link" href="{{permalink}}" title="Edit post {{title}}">Edit</a></span>';
      $panel_def[ 'comments-link' ] = '<span class="comments-link"><a href="{{permalink}}/#comments" title="Comment on {{title}}">Comments: {{commentscount}}</a></span>';
//TODO This has to be changed back once we.if we use a link instead of theget thumnail
      //$panel_def[ 'image' ]        = '<img class="entry-image" src="{{image}}">';
      // Yes, WP themes (T13, T14 etc) actually link the date to the post, not the archive for the date. Maybe it's an SEO thing, but I'm going to remove it
      // $panel_def[ 'datetime' ]      = '<span class="date"><a href="{{permalink}}" title="{{title}}" rel="bookmark"><time class="entry-date" datetime="{{datetime}}">{{fdatetime}}</time></a></span>';
      // oops should be using this for featured image

      return $panel_def;
    }

    /**
     * @param $section
     * @return mixed
     */
    static function set_data(&$section)
    {
      global $post;

      $toshow   = json_decode($section[ '_panels_design_preview' ], true);
      $data     = array();
      $postmeta = get_post_meta(get_the_ID());

      // TODO: Will need to refine these to be based on content type
      if ($toshow[ 'title' ][ 'show' ]) {
        $data[ 'title' ] = get_the_title();
      }
      if ($toshow[ 'content' ][ 'show' ]) {
        $data[ 'content' ] = apply_filters('the_content', get_the_content());
      }

      if ($toshow[ 'excerpt' ][ 'show' ]) {
        $data[ 'excerpt' ] = apply_filters('the_excerpt', get_the_excerpt());
      }

      if ($toshow[ 'image' ][ 'show' ] || $section[ '_panels_design_thumb-position' ] != 'none') {
        //        if (false)
        //        {
        //          $post_image = ($this->panel_info[ '_panels_design_thumb-position' ] != 'none') ? job_resize($thumb_src, $params, PZARC_CACHE_PATH, PZARC_CACHE_URL) : null;
        //        }
        // BFI

        $width  = (int)str_replace('px', '', $section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
        $height = (int)str_replace('px', '', $section[ '_panels_design_image-max-dimensions' ][ 'height' ]);

        $data[ 'image' ][ 'image' ]   = wp_get_attachment_image($post->ID, array($width,
                                                                                 $height,
                                                                                 'bfi_thumb' => true,
                                                                                 'crop'      => true));
        $data[ 'image' ][ 'caption' ] = $post->post_excerpt;
      }

      if ($toshow[ 'meta1' ][ 'show' ] ||
          $toshow[ 'meta2' ][ 'show' ] ||
          $toshow[ 'meta3' ][ 'show' ]
      ) {

        $data[ 'meta' ][ 'datetime' ]        = get_the_date();
        $data[ 'meta' ][ 'fdatetime' ]       = date_i18n($section[ '_panels_design_meta-date-format' ], strtotime(get_the_date()));
        $data[ 'meta' ][ 'categorieslinks' ] = get_the_category_list(', ');
        $data[ 'categories' ]                = pzarc_tax_string_list(get_the_category(), 'category-', '', ' ');
        $data[ 'meta' ][ 'tagslinks' ]       = get_the_tag_list(null, ', ');
        $data[ 'tags' ]                      = pzarc_tax_string_list(get_the_tags(), 'tag-', '', ' ');

        $data[ 'meta' ][ 'authorlink' ] = get_author_posts_url(get_the_author_meta('ID'));
        $data[ 'meta' ][ 'authorname' ] = sanitize_text_field(get_the_author_meta('display_name'));

        $rawemail    = sanitize_email(get_the_author_meta('user_email'));
        $encodedmail = '';

        for ($i = 0; $i < strlen($rawemail); $i++) {

          $encodedmail .= "&#" . ord($rawemail[ $i ]) . ';';
        }

        $data[ 'meta' ][ 'authoremail' ]    = $encodedmail;
        $data[ 'meta' ][ 'comments-count' ] = get_comments_number();
      }
//var_dump($postmeta);
      //     var_dump($section);

      $cfcount = $section[ '_panels_design_custom-fields-count' ];

      for ($i = 1; $i <= $cfcount; $i++) {

        // var_dump($section);
        // the settings come from section
        $data[ 'cfield' ][ $i ][ 'name' ]  = $section[ '_panels_design_cfield-' . $i . '-name' ];
        $data[ 'cfield' ][ $i ][ 'group' ] = $section[ '_panels_design_cfield-' . $i . '-group' ];
        // The content itself comes from post meta
        $data[ 'cfield' ][ $i ][ 'value' ] = $postmeta[ $section[ '_panels_design_cfield-' . $i . '-name' ] ][ 0 ];
        // TODO : Add other attributes

      }

      // NEVER include HTML in these, only should get WP values.
      $showbgimage = (has_post_thumbnail()
              && $section[ '_panels_design_background-position' ] != 'none'
              && ($section[ '_panels_design_components-position' ] == 'top' || $section[ '_panels_design_components-position' ] == 'left'))
          || ($section[ '_panels_design_background-position' ] != 'none'
              && ($section[ '_panels_design_components-position' ] == 'bottom' || $section[ '_panels_design_components-position' ] == 'right'));

      $data[ 'postid' ]     = get_the_ID();
      $data[ 'poststatus' ] = get_post_status();
      $data[ 'permalink' ]  = get_the_permalink();
      $post_format          = get_post_format();

      // Need to setup for break points.

      //  data-imagesrcs ="1,2,3", data-breakpoints="1,2,3". Then use js to change src.
      $width = (int)str_replace('px', '', $section[ '_panels_design_background-image-max' ][ 'width' ]);

      // TODO: Should this just choose the greater? Or could that be too stupid if  someone puts a very large max-height?
      if ($section[ '_panels_settings_panel-height-type' ] === 'height') {
        $height = (int)str_replace('px', '', $section[ '_panels_settings_panel-height' ][ 'height' ]);
      } else {
        $height = (int)str_replace('px', '', $section[ '_panels_design_background-image-max' ][ 'height' ]);
      }


      $data[ 'bgimage' ] = '';
      if ($showbgimage) {

        $focal_point = get_post_meta($post->ID, 'pzgp_focal_point', true);

        list($fp_x, $fp_y) = (empty($focal_point) ? array(50, 50) : explode(',', $focal_point));

        $fp_x = $fp_x > 1 ? $fp_x / 100 : $fp_x;
        $fp_y = $fp_y > 1 ? $fp_y / 100 : $fp_y;

        $data[ 'bgimage' ] = wp_get_attachment_image($post->ID, array($width,
                                                                      $height,
                                                                      'bfi_thumb' => true,
                                                                      'crop'      => array(
                                                                          $fp_x,
                                                                          $fp_y,
                                                                          //                                                                            'focalpt'   => true
                                                                      ),
                                                              )
        ); //WP seems to smartly figure out which of its saved images to use! Now we jsut gotta get it t work with focal point
//       $data[ 'bgimage' ] = '<img src="'.bfi_thumb($post->guid, array($width, $height, 'crop' => true)).'">'; //WP seems to smartly figure out which of its saved images to use! Now we jsut gotta get it t work with focal point

      }

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
      $line = str_replace('{{postid}}', $data[ 'postid' ], $line);
      $line = str_replace('{{title}}', $data[ 'title' ], $line);
      //TODO:: Need to make this link to a lightbox
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

  class arc_Panel_gallery_Wrapper extends arc_Panel_gallery
  {
    public static function render($component, $panel_def, $content_type, &$data, &$section)
    {
//      foreach ($data[ 'components-open' ] as $key => $value) {
//        $template[ $type ] = str_replace('{{' . $key . '}}', $value, $template[ $type ]);
//      }
//      foreach ($data[ 'components-close' ] as $key => $value) {
//        $template[ $type ] = str_replace('{{' . $key . '}}', $value, $template[ $type ]);
//      }

      //   $panel_def[ $component ] = str_replace('{{using-bg-image}}', (!empty($data[ 'bgimage' ]) ? 'has-bgimage ' : 'no-bgimage '), $panel_def[ $component ]);

      return parent::process_generics($data, $panel_def[ $component ], $content_type, $section);
    }
  }

  /**
   * Class arc_Panel_Title
   */
  class arc_Panel_gallery_Title extends arc_Panel_gallery
  {
    /**
     * @param $component (Line type, e.g.excerpt, meta, image, title etc)
     * @param $panel_def
     * @param $content_type (Post type source - eg. post, page, gallery)
     * @param $data
     * @param $section
     * @return mixed|void
     */
    public static function render($component, $panel_def, $content_type, &$data, &$section)
    {
      $panel_def[ $component ] = str_replace('{{title}}', $data[ 'title' ], $panel_def[ $component ]);
      if ($section[ '_panels_design_link-titles' ]) {
        $panel_def[ $component ] = str_replace('{{postlink}}', $panel_def[ 'postlink' ], $panel_def[ $component ]);
        $panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $panel_def[ $component ]);
      };

      // this only works for posts! need different rules for different types! :S
      return parent::process_generics($data, $panel_def[ $component ], $content_type, $section);
    }
  }


  class arc_Panel_gallery_Meta extends arc_Panel_gallery
  {
    public static function render($component, $panel_def, $content_type, &$data, &$section)
    {
      // get $metaX definition and construct string, then replace metaXinnards
      $panel_def[ $component ] = str_replace('{{datetime}}', $data[ 'meta' ][ 'datetime' ], $panel_def[ $component ]);
      $panel_def[ $component ] = str_replace('{{fdatetime}}', $data[ 'meta' ][ 'fdatetime' ], $panel_def[ $component ]);
      if (empty($section[ '_panels_design_excluded-authors' ]) || !in_array(get_the_author_meta('ID'), $section[ '_panels_design_excluded-authors' ])) {
        //Remove text indicators
        $panel_def[ $component ] = str_replace('//', '', $panel_def[ $component ]);
        $panel_def[ $component ] = str_replace('{{authorname}}', $data[ 'meta' ][ 'authorname' ], $panel_def[ $component ]);
        $panel_def[ $component ] = str_replace('{{authorlink}}', $data[ 'meta' ][ 'authorlink' ], $panel_def[ $component ]);
        $panel_def[ $component ] = str_replace('{{authoremail}}', $data[ 'meta' ][ 'authoremail' ], $panel_def[ $component ]);
      } else {
        // Removed unused text and indicators
        $panel_def[ $component ] = preg_replace("/\\/\\/(.)*\\/\\//uiUm", "", $panel_def[ $component ]);
      }
      $panel_def[ $component ] = str_replace('{{categories}}', $data[ 'meta' ][ 'categories' ], $panel_def[ $component ]);
      $panel_def[ $component ] = str_replace('{{categorieslinks}}', $data[ 'meta' ][ 'categorieslinks' ], $panel_def[ $component ]);
      $panel_def[ $component ] = str_replace('{{tags}}', $data[ 'meta' ][ 'tags' ], $panel_def[ $component ]);
      $panel_def[ $component ] = str_replace('{{tagslinks}}', $data[ 'meta' ][ 'tagslinks' ], $panel_def[ $component ]);
      $panel_def[ $component ] = str_replace('{{commentslink}}', $panel_def[ 'comments-link' ], $panel_def[ $component ]);
      $panel_def[ $component ] = str_replace('{{commentscount}}', $data[ 'comments-count' ], $panel_def[ $component ]);
      $panel_def[ $component ] = str_replace('{{editlink}}', $panel_def[ 'editlink' ], $panel_def[ $component ]);


      return parent::process_generics($data, $panel_def[ $component ], $content_type, $section);
    }

  }


  class arc_Panel_gallery_Image extends arc_Panel_gallery
  {
    public static function render($component, $panel_def, $content_type, &$data, &$section)
    {
      if ($section[ '_panels_design_link-image' ]) {
        $panel_def[ $component ] = str_replace('{{postlink}}', $panel_def[ 'postlink' ], $panel_def[ $component ]);
        $panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $panel_def[ $component ]);
      }

      if ($section[ '_panels_design_image-captions' ]) {
        $caption                 = str_replace('{{caption}}', $data[ 'image' ][ 'caption' ], $panel_def[ 'caption' ]);
        $panel_def[ $component ] = str_replace('{{captioncode}}', $caption, $panel_def[ $component ]);
      }

      $panel_def[ $component ] = str_replace('{{image}}', $data[ 'image' ][ 'image' ], $panel_def[ $component ]);

      if (!empty($section[ '_panels_design_centre-image' ])) {
        $panel_def[ $component ] = str_replace('{{centred}}', 'centred', $panel_def[ $component ]);
      }

      if (empty($data[ 'image' ][ 'image' ])) {
        $panel_def[ $component ] = '';
      }

//      foreach ($data[ 'image' ] as $key => $value) {
//        $template[ $type ] = str_replace('{{' . $key . '}}', $value, $template[ $type ]);
//      }

      return parent::process_generics($data, $panel_def[ $component ], $content_type, $section);
    }

  }

  class arc_Panel_gallery_bgimage extends arc_Panel_gallery
  {
    public static function render($component, $panel_def, $content_type, &$data, &$section)
    {
      $panel_def[ $component ] = str_replace('{{bgimage}}', $data[ 'bgimage' ], $panel_def[ $component ]);
      $panel_def[ $component ] = str_replace('{{trim-scale}}', ' ' . $section[ '_panels_design_background-position' ] . ' ' . $section[ '_panels_design_background-image-resize' ], $panel_def[ $component ]);

      return parent::process_generics($data, $panel_def[ $component ], $content_type, $section);
    }

  }


  class arc_Panel_gallery_Content extends arc_Panel_gallery
  {
    public static function render($component, $panel_def, $content_type, &$data, &$section)
    {
      $panel_def[ $component ] = str_replace('{{content}}', $data[ 'content' ], $panel_def[ $component ]);
      if ($section[ '_panels_design_thumb-position' ] != 'none') {
        if (!empty($data[ 'image' ][ 'image' ])) {
          $panel_def[ $component ] = str_replace('{{image-in-content}}', $panel_def[ 'image' ], $panel_def[ $component ]);

          if ($section[ '_panels_design_image-captions' ]) {
            $panel_def[ $component ] = str_replace('{{captioncode}}', '<span class="caption">' . $data[ 'image' ][ 'caption' ] . '</span>', $panel_def[ $component ]);
          }

          $panel_def[ $component ] = str_replace('{{image}}', $data[ 'image' ][ 'image' ], $panel_def[ $component ]);
          $panel_def[ $component ] = str_replace('{{incontent}}', 'in-content-thumb', $panel_def[ $component ]);

          if ($section[ '_panels_design_link-image' ]) {
            $panel_def[ $component ] = str_replace('{{postlink}}', $panel_def[ 'postlink' ], $panel_def[ $component ]);
            $panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $panel_def[ $component ]);
          }
        }
        if (empty($data[ 'image' ][ 'image' ]) && $section[ '_panels_design_maximize-content' ]) {
          //TODO: Add an option to set if width spreads
          $panel_def[ $component ] = str_replace('{{nothumb}}', 'nothumb', $panel_def[ $component ]);
        }
      }

      return parent::process_generics($data, $panel_def[ $component ], $content_type, $section);
    }

  }


  class arc_Panel_gallery_Excerpt extends arc_Panel_gallery
  {
    /**
     * @param $component
     * @param $panel_def
     * @param $content_type
     * @param $data
     * @param $section
     * @return mixed|void
     */
    public static function render($component, $panel_def, $content_type, &$data, &$section)
    {
      //    var_dump($data);
      $panel_def[ $component ] = str_replace('{{excerpt}}', $data[ 'excerpt' ], $panel_def[ $component ]);

      //  var_dump($section[ '_panels_design_thumb-position' ]);
      if ($section[ '_panels_design_thumb-position' ] != 'none') {
        if (!empty($data[ 'image' ][ 'image' ]) && !empty($section[ '_panels_design_thumb-position' ])) {
          $panel_def[ $component ] = str_replace('{{image-in-content}}', $panel_def[ 'image' ], $panel_def[ $component ]);

          if ($section[ '_panels_design_image-captions' ]) {
            $panel_def[ $component ] = str_replace('{{captioncode}}', '<span class="caption">' . $data[ 'image' ][ 'caption' ] . '</span>', $panel_def[ $component ]);
          }

          $panel_def[ $component ] = str_replace('{{image}}', $data[ 'image' ][ 'image' ], $panel_def[ $component ]);
          $panel_def[ $component ] = str_replace('{{incontent}}', 'in-content-thumb', $panel_def[ $component ]);

          if ($section[ '_panels_design_link-image' ]) {
            $panel_def[ $component ] = str_replace('{{postlink}}', $panel_def[ 'postlink' ], $panel_def[ $component ]);
            $panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $panel_def[ $component ]);
          }
        }
        if (empty($data[ 'image' ][ 'image' ]) && $section[ '_panels_design_maximize-content' ]) {
          //TODO: Add an option to set if width spreads
          $panel_def[ $component ] = str_replace('{{nothumb}}', 'nothumb', $panel_def[ $component ]);
        }
      }

//_panels_design_thumb-position


      return parent::process_generics($data, $panel_def[ $component ], $content_type, $section);
    }

  }


  class arc_Panel_gallery_Custom extends arc_Panel_gallery
  {
    public static function render($component, $panel_def, $content_type, &$data, &$section)
    {

      // TODO: Is this still relevant?
      // This only works for posts! need different rules for different types! :S ???????????
//      if (!empty($data[ $component ]['value'])){
      // Show each custom field in this group

      if (!empty($data[ 'cfield' ])) {
        $panel_def_cfield = $panel_def[ 'cfield' ];
        $build_field      = '';
        foreach ($data[ 'cfield' ] as $k => $v) {
          if ($v[ 'group' ] === $component && !empty($v[ 'value' ])) {
            $panel_def_cfield = str_replace('{{cfieldcontent}}', $v[ 'value' ], $panel_def_cfield);
            $panel_def_cfield = str_replace('{{cfieldname}}', $v[ 'name' ], $panel_def_cfield);
            $build_field .= $panel_def_cfield;
          }
          $panel_def_cfield = $panel_def[ 'cfield' ];
        }
        $panel_def[ $component ] = str_replace('{{' . $component . 'innards}}', $build_field, $panel_def[ $component ]);
      } else {
        $panel_def[ $component ] = '';
      }


      return parent::process_generics($data, $panel_def[ $component ], $content_type, $section);
    }

  }


