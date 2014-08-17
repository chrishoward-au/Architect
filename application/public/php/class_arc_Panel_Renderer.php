<?php

  class arc_Panel_Renderer
  {
    public $data = array();
    public $toshow = array();
    public $panel_def = array();
    public $section = array();
    public $thumb_id;
    public $focal_point=array();

    public function __construct(&$section)
    {

      $this->section = $section['section-panel-settings'];

      $this->toshow = apply_filters('arc-toshow', json_decode($this->section[ '_panels_design_preview' ], true));

      // Null up everything to prevent warnings later on
      $this->data[ 'title' ] = null;

      $this->data[ 'content' ] = null;

      $this->data[ 'excerpt' ] = null;

      $this->data[ 'meta' ][ 'categories' ] = null;
      $this->data[ 'meta' ][ 'tags' ]       = null;

      $this->data[ 'image' ][ 'image' ]    = null;
      $this->data[ 'image' ][ 'caption' ]  = null;
      $this->data[ 'image' ][ 'original' ] = null;

      $this->data[ 'meta' ][ 'datetime' ]        = null;
      $this->data[ 'meta' ][ 'fdatetime' ]       = null;
      $this->data[ 'meta' ][ 'categorieslinks' ] = null;
      $this->data[ 'meta' ][ 'tagslinks' ]       = null;
      $this->data[ 'meta' ][ 'authorlink' ]      = null;
      $this->data[ 'meta' ][ 'authorname' ]      = null;
      $this->data[ 'meta' ][ 'authoremail' ]     = null;
      $this->data[ 'meta' ][ 'comments-count' ]  = null;
      $this->data[ 'postid' ]                    = null;
      $this->data[ 'poststatus' ]                = null;
      $this->data[ 'permalink' ]                 = null;
      $this->data[ 'postformat' ]                = null;

      $this->data[ 'bgimage' ][ 'thumb' ]    = null;
      $this->data[ 'bgimage' ][ 'original' ] = null;


    }

    /**
     * Method: panel_def
     *
     * Defines the standard panel definition. Overwrite with own as necessary. See gallery post type for example
     *
     */
    public function panel_def()
    {
      //TODO: Need to get a way to always wrap components in pzarc-compenents div.Problem is...dev has to create definition correctly.
      $this->panel_def[ 'components-open' ]  = '<article id="post-{{postid}}" class="{{mimic-block-type}} post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{categories}} {{tags}} {{pzclasses}}">';
      $this->panel_def[ 'components-close' ] = '</article>';
      $this->panel_def[ 'postlink' ]         = '<a href="{{permalink}}" title="{{title}}">';
      $this->panel_def[ 'header' ]           = '<header class="entry-header">{{headerinnards}}</header>';
      $this->panel_def[ 'title' ]            = '<h1 class="entry-title">{{postlink}}{{title}}{{closepostlink}}</h1>';
      $this->panel_def[ 'meta1' ]            = '<div class="entry-meta entry-meta1">{{meta1innards}}</div>';
      $this->panel_def[ 'meta2' ]            = '<div class="entry-meta entry-meta2">{{meta2innards}}</div>';
      $this->panel_def[ 'meta3' ]            = '<div class="entry-meta entry-meta3">{{meta3innards}}</div>';
      $this->panel_def[ 'datetime' ]         = '<span class="entry-date"><a href="{{permalink}}"<time class="entry-date" datetime="{{datetime}}">{{fdatetime}}</time></span></a></span>';
      $this->panel_def[ 'categories' ]       = '<span class="categories-links">{{categorieslinks}}</span>';
      $this->panel_def[ 'tags' ]             = '<span class="tags-links">{{tagslinks}}</span>';
      $this->panel_def[ 'author' ]           = '<span class="byline"><span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span></span>';
      $this->panel_def[ 'email' ]            = '<span class="byline email"><span class="author vcard"><a class="url fn n" href="mailto:{{authoremail}}" title="Email {{authorname}}" rel="author">{{authoremail}}</a></span></span>';
      //     $this->panel_def[ 'image' ]       = '<figure class="entry-thumbnail {{incontent}}">{{postlink}}<img width="{{width}}" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}">{{closepostlink}}{{captioncode}}</figure>';
      $this->panel_def[ 'image' ]         = '<figure class="entry-thumbnail {{incontent}} {{centred}}">{{postlink}}{{image}}{{closelink}}{{captioncode}}</figure>';
      $this->panel_def[ 'bgimage' ]       = '<figure class="entry-bgimage pzarc-bg-image {{trim-scale}}">{{bgimage}}</figure>';
      $this->panel_def[ 'caption' ]       = '<figcaption class="caption">{{caption}}</figcaption>';
      $this->panel_def[ 'content' ]       = ' <div class="entry-content {{nothumb}}">{{image-in-content}}{{content}}</div>';
      $this->panel_def[ 'custom1' ]       = '<div class="entry-customfieldgroup entry-customfieldgroup-1">{{custom1innards}}</div>';
      $this->panel_def[ 'custom2' ]       = '<div class="entry-customfieldgroup entry-customfieldgroup-2">{{custom2innards}}</div>';
      $this->panel_def[ 'custom3' ]       = '<div class="entry-customfieldgroup entry-customfieldgroup-3">{{custom3innards}}</div>';
      $this->panel_def[ 'cfield' ]        = '<div class="entry-customfield entry-customfield-{{cfieldname}} entry-customfield-{{cfieldnumber}}">{{cfieldcontent}}</div>';
      $this->panel_def[ 'footer' ]        = '<footer class="entry-footer">{{footerinnards}}</footer>';
      $this->panel_def[ 'excerpt' ]       = ' <div class="entry-excerpt {{nothumb}}">{{image-in-content}}{{excerpt}}</div>';
      $this->panel_def[ 'feature' ]       = '{{feature}}';
      $this->panel_def[ 'editlink' ]      = '<span class="edit-link"><a class="post-edit-link" href="{{permalink}}" title="Edit post {{title}}">Edit</a></span>';
      $this->panel_def[ 'comments-link' ] = '<span class="comments-link"><a href="{{permalink}}/#comments" title="Comment on {{title}}">Comments: {{commentscount}}</a></span>';

//TODO This has to be changed back once we.if we use a link instead of theget thumnail
      //$this->panel_def[ 'image' ]        = '<img class="entry-image" src="{{image}}">';
      // Yes, WP themes (T13, T14 etc) actually link the date to the post, not the archive for the date. Maybe it's an SEO thing, but I'm going to remove it
      // $this->panel_def[ 'datetime' ]      = '<span class="date"><a href="{{permalink}}" title="{{title}}" rel="bookmark"><time class="entry-date" datetime="{{datetime}}">{{fdatetime}}</time></a></span>';
      // oops should be using this for featured image

    }


    public function set_data(&$post)
    {
      $this->get_title($post);
      $this->get_meta($post);
      $this->get_content($post);
      $this->get_excerpt($post);
      $this->get_image($post);
      $this->get_bgimage($post);
      $this->get_customflds($post);
    }

    /**
     * @param $post
     */
    public function get_title(&$post)
    {
      /** TITLE */
      if ($this->toshow[ 'title' ][ 'show' ]) {
        $this->data[ 'title' ]['title'] = get_the_title();
      }

    }

    public function get_meta(&$post)
    {
      $postmeta = get_post_meta(get_the_ID());
      /** META */
      if ($this->toshow[ 'meta1' ][ 'show' ] ||
          $this->toshow[ 'meta2' ][ 'show' ] ||
          $this->toshow[ 'meta3' ][ 'show' ]
      ) {
        $this->data[ 'meta' ][ 'datetime' ]        = get_the_date();
        $this->data[ 'meta' ][ 'fdatetime' ]       = date_i18n($this->section[ '_panels_design_meta-date-format' ], strtotime(get_the_date()));
        $this->data[ 'meta' ][ 'categorieslinks' ] = get_the_category_list(', ');
        $this->data[ 'meta' ][ 'categories' ]      = pzarc_tax_string_list(get_the_category(), 'category-', '', ' ');
        $this->data[ 'meta' ][ 'tagslinks' ]       = get_the_tag_list(null, ', ');
        $this->data[ 'meta' ][ 'tags' ]            = pzarc_tax_string_list(get_the_tags(), 'tag-', '', ' ');

        $this->data[ 'meta' ][ 'authorlink' ] = get_author_posts_url(get_the_author_meta('ID'));
        $this->data[ 'meta' ][ 'authorname' ] = sanitize_text_field(get_the_author_meta('display_name'));
        $rawemail                             = sanitize_email(get_the_author_meta('user_email'));
        $encodedmail                          = '';
        for ($i = 0; $i < strlen($rawemail); $i++) {
          $encodedmail .= "&#" . ord($rawemail[ $i ]) . ';';
        }
        $this->data[ 'meta' ][ 'authoremail' ]    = $encodedmail;
        $this->data[ 'meta' ][ 'comments-count' ] = get_comments_number();

        $this->data[ 'meta' ][ 'custom' ][ 1 ] = pzarc_get_post_terms(get_the_id(), $this->section[ '_panels_design_meta1-config' ]);
        $this->data[ 'meta' ][ 'custom' ][ 2 ] = pzarc_get_post_terms(get_the_id(), $this->section[ '_panels_design_meta2-config' ]);
        $this->data[ 'meta' ][ 'custom' ][ 3 ] = pzarc_get_post_terms(get_the_id(), $this->section[ '_panels_design_meta3-config' ]);
      }
    }

    public function get_image(&$post)
    {
      /** FEATURED IMAGE */
      $this->thumb_id    = get_post_thumbnail_id();
      $this->focal_point = get_post_meta($this->thumb_id, 'pzgp_focal_point', true);
      $this->focal_point = (empty($this->focal_point) ? array(50, 50) : explode(',', $this->focal_point));

      if (!$this->thumb_id && $this->section[ '_panels_settings_use-embedded-images' ]) {
        //TODO: Changed to more reliable check if image is in the content?
        preg_match("/(?<=wp-image-)(\\d)*/uimx", get_the_content(), $matches);
        $this->thumb_id = (!empty($matches[ 0 ]) ? $matches[ 0 ] : false);
      }

      $focal_point = get_post_meta($this->thumb_id, 'pzgp_focal_point', true);
      $focal_point = (empty($focal_point) ? array(50, 50) : explode(',', $focal_point));

      if ($this->toshow[ 'image' ][ 'show' ] || $this->section[ '_panels_design_thumb-position' ] != 'none') {
        //        if (false)
        //        {
        //          $post_image = ($this->panel_info[ '_panels_design_thumb-position' ] != 'none') ? job_resize($thumb_src, $params, PZARC_CACHE_PATH, PZARC_CACHE_URL) : null;
        //        }
        // BFI

        $width  = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
        $height = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ]);

        // TODO: Add all the focal point stuff to all the post types images and bgimages
        // Easiest to do via a reusable function or all this stuff could be done once!!!!!!!!!
        // could pass $this->data thru a filter
        $this->data[ 'image' ][ 'image' ]    = wp_get_attachment_image($this->thumb_id, array($width,
                                                                                              $height,
                                                                                              'bfi_thumb' => true,
                                                                                              'crop'      => (int)$focal_point[ 0 ] . 'x' . (int)$focal_point[ 1 ] . 'x' . $this->section[ '_panels_settings_image-focal-point' ]

        ));
        $this->data[ 'image' ][ 'original' ] = wp_get_attachment_image_src($this->thumb_id, 'full');
        $image                               = get_post($this->thumb_id);
        $this->data[ 'image' ][ 'caption' ]  = $image->post_excerpt;


      }
    }

    public function get_content(&$post)
    {
      /** CONTENT */
      if ($this->toshow[ 'content' ][ 'show' ]) {
        $this->data[ 'content' ] = apply_filters('the_content', get_the_content());
      }
    }

    public function get_excerpt(&$post)
    {
    }

    public function get_bgimage(&$post)
    {
      /** BACKGROUND IMAGE */
      if (empty($this->focal_point)) {
        $this->thumb_id    = get_post_thumbnail_id();
        $this->focal_point = get_post_meta($this->thumb_id, 'pzgp_focal_point', true);
        $this->focal_point = (empty($this->focal_point) ? array(50, 50) : explode(',', $this->focal_point));
      }
      $showbgimage                 = (has_post_thumbnail()
              && $this->section[ '_panels_design_background-position' ] != 'none'
              && ($this->section[ '_panels_design_components-position' ] == 'top' || $this->section[ '_panels_design_components-position' ] == 'left'))
          || ($this->section[ '_panels_design_background-position' ] != 'none'
              && ($this->section[ '_panels_design_components-position' ] == 'bottom' || $this->section[ '_panels_design_components-position' ] == 'right'));
      $this->data[ 'postid' ]      = get_the_ID();
      $this->data[ 'poststatus' ]  = get_post_status();
      $this->data[ 'permalink' ]   = get_the_permalink();
      $post_format                 = get_post_format();
      $this->data [ 'postformat' ] = (empty($post_format) ? 'standard' : $post_format);

      // Need to setup for break points.

      //  TODO: data-imagesrcs ="1,2,3", data-breakpoints="1,2,3". Then use js to change src.
      $width = (int)str_replace('px', '', $this->section[ '_panels_design_background-image-max' ][ 'width' ]);
      // TODO: Should this just choose the greater? Or could that be too stupid if  someone puts a very large max-height?
      if ($this->section[ '_panels_settings_panel-height-type' ] === 'height') {
        $height = (int)str_replace('px', '', $this->section[ '_panels_settings_panel-height' ][ 'height' ]);
      } else {
        $height = (int)str_replace('px', '', $this->section[ '_panels_design_background-image-max' ][ 'height' ]);
      }

      // Need to grab image again because it uses different dimensions for the bgimge
      $this->data[ 'bgimage' ][ 'thumb' ] = wp_get_attachment_image($this->thumb_id, array($width,
                                                                                           $height,
                                                                                           'bfi_thumb' => true,
                                                                                           'crop'      => (int)$this->focal_point[ 0 ] . 'x' . (int)$this->focal_point[ 1 ] . 'x' . $this->section[ '_panels_settings_image-focal-point' ]
      ));

      $this->data[ 'bgimage' ][ 'original' ] = wp_get_attachment_image_src($this->thumb_id, 'full');
    }

    public function get_customflds(&$post)
    {
      /** CUSTOM FIELDS **/
      $cfcount = $this->section[ '_panels_design_custom-fields-count' ];
      for ($i = 1; $i <= $cfcount; $i++) {
        // var_dump($this->section);
        // the settings come from section
        $this->data[ 'cfield' ][ $i ][ 'group' ]       = $this->section[ '_panels_design_cfield-' . $i . '-group' ];
        $this->data[ 'cfield' ][ $i ][ 'name' ]        = $this->section[ '_panels_design_cfield-' . $i . '-name' ];
        $this->data[ 'cfield' ][ $i ][ 'field-type' ]  = $this->section[ '_panels_design_cfield-' . $i . '-field-type' ];
        $this->data[ 'cfield' ][ $i ][ 'date-format' ] = $this->section[ '_panels_design_cfield-' . $i . '-date-format' ];
        $this->data[ 'cfield' ][ $i ][ 'wrapper-tag' ] = $this->section[ '_panels_design_cfield-' . $i . '-wrapper-tag' ];
        $this->data[ 'cfield' ][ $i ][ 'class-name' ]  = $this->section[ '_panels_design_cfield-' . $i . '-class-name' ];
        $this->data[ 'cfield' ][ $i ][ 'link-field' ]  = $this->section[ '_panels_design_cfield-' . $i . '-link-field' ];
        $this->data[ 'cfield' ][ $i ][ 'prefix-text' ] = $this->section[ '_panels_design_cfield-' . $i . '-prefix-text' ];
        $params                                        = array('width'  => str_replace($this->section[ '_panels_design_cfield-' . $i . '-ps-images-width' ][ 'units' ], '', $this->section[ '_panels_design_cfield-' . $i . '-ps-images-width' ][ 'width' ]),
                                                               'height' => str_replace($this->section[ '_panels_design_cfield-' . $i . '-ps-images-height' ][ 'units' ], '', $this->section[ '_panels_design_cfield-' . $i . '-ps-images-height' ][ 'height' ]));

        $this->data[ 'cfield' ][ $i ][ 'prefix-image' ] = bfi_thumb($this->section[ '_panels_design_cfield-' . $i . '-prefix-image' ][ 'url' ], $params);
        $this->data[ 'cfield' ][ $i ][ 'suffix-text' ]  = $this->section[ '_panels_design_cfield-' . $i . '-suffix-text' ];
        $this->data[ 'cfield' ][ $i ][ 'suffix-image' ] = bfi_thumb($this->section[ '_panels_design_cfield-' . $i . '-suffix-image' ][ 'url' ], $params);

        // The content itself comes from post meta
        $this->data[ 'cfield' ][ $i ][ 'value' ] = (!empty($postmeta[ $this->section[ '_panels_design_cfield-' . $i . '-name' ] ]) ? $postmeta[ $this->section[ '_panels_design_cfield-' . $i . '-name' ] ][ 0 ] : null);
        // TODO:Bet this doesn't work!
        if (!empty($this->section[ '_panels_design_cfield-' . $i . '-link-field' ])) {
          $this->data[ 'cfield' ][ $i ][ 'link-field' ] = (!empty($postmeta[ $this->section[ '_panels_design_cfield-' . $i . '-link-field' ] ]) ? $postmeta[ $this->section[ '_panels_design_cfield-' . $i . '-link-field' ] ][ 0 ] : null);
        }

        // TODO : Add other attributes
      }

    }

    /** End of data collect */

    /** Begin rendering */

    public function render_title($component, $content_type, $rsid)
    {
      if ('thumb' === $this->section[ '_panels_design_title-prefix' ]) {
        $this->panel_def[ $component ] = str_replace('{{title}}', $this->data[ 'title' ][ 'thumb' ] . '<span class="pzarc-title-wrap">' . $this->data[ 'title' ][ 'title' ] . '</span>', $this->panel_def[ $component ]);
      } else {
        $this->panel_def[ $component ] = str_replace('{{title}}', $this->data[ 'title' ][ 'title' ], $this->panel_def[ $component ]);
      }
      if ($this->section[ '_panels_design_link-titles' ]) {
        $this->panel_def[ $component ] = str_replace('{{postlink}}', $this->panel_def[ 'postlink' ], $this->panel_def[ $component ]);
        $this->panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $this->panel_def[ $component ]);
      }

      return self::render_generics($component,$content_type);

    }

    public function render_meta(
        $component, $content_type, $rsid
    ) {
      $this->panel_def[ $component ] = str_replace('{{datetime}}', $this->data[ 'meta' ][ 'datetime' ], $this->panel_def[ $component ]);
      $this->panel_def[ $component ] = str_replace('{{fdatetime}}', $this->data[ 'meta' ][ 'fdatetime' ], $this->panel_def[ $component ]);
      if (empty($this->section[ '_panels_design_excluded-authors' ]) || !in_array(get_the_author_meta('ID'), $this->section[ '_panels_design_excluded-authors' ])) {
        //Remove text indicators
        $this->panel_def[ $component ] = str_replace('//', '', $this->panel_def[ $component ]);
        $this->panel_def[ $component ] = str_replace('{{authorname}}', $this->data[ 'meta' ][ 'authorname' ], $this->panel_def[ $component ]);
        $this->panel_def[ $component ] = str_replace('{{authorlink}}', $this->data[ 'meta' ][ 'authorlink' ], $this->panel_def[ $component ]);
        $this->panel_def[ $component ] = str_replace('{{authoremail}}', $this->data[ 'meta' ][ 'authoremail' ], $this->panel_def[ $component ]);
      } else {
        // Removed unused text and indicators
        $this->panel_def[ $component ] = preg_replace("/\\/\\/(.)*\\/\\//uiUm", "", $this->panel_def[ $component ]);
      }
      $this->panel_def[ $component ] = str_replace('{{categories}}', $this->data[ 'meta' ][ 'categories' ], $this->panel_def[ $component ]);
      $this->panel_def[ $component ] = str_replace('{{categorieslinks}}', $this->data[ 'meta' ][ 'categorieslinks' ], $this->panel_def[ $component ]);
      $this->panel_def[ $component ] = str_replace('{{tags}}', $this->data[ 'meta' ][ 'tags' ], $this->panel_def[ $component ]);
      $this->panel_def[ $component ] = str_replace('{{tagslinks}}', $this->data[ 'meta' ][ 'tagslinks' ], $this->panel_def[ $component ]);
      $this->panel_def[ $component ] = str_replace('{{commentslink}}', $this->panel_def[ 'comments-link' ], $this->panel_def[ $component ]);
      $this->panel_def[ $component ] = str_replace('{{commentscount}}', $this->data[ 'meta' ][ 'comments-count' ], $this->panel_def[ $component ]);
      $this->panel_def[ $component ] = str_replace('{{editlink}}', $this->panel_def[ 'editlink' ], $this->panel_def[ $component ]);
      foreach ($this->data[ 'meta' ][ 'custom' ] as $meta) {
        if (!empty($meta)) {
          $this->panel_def[ $component ] = str_replace('{{ct:' . key($meta) . '}}', $meta[ key($meta) ], $this->panel_def[ $component ]);
        }
      }

      return self::render_generics($component,$content_type);
    }

    public function render_content($component, $content_type, $rsid)
    {
      $this->panel_def[ $component ] = str_replace('{{content}}', $this->data[ 'content' ], $this->panel_def[ $component ]);
      if ($this->section[ '_panels_design_thumb-position' ] != 'none') {
        if (!empty($this->data[ 'image' ][ 'image' ])) {
          $this->panel_def[ $component ] = str_replace('{{image-in-content}}', $this->panel_def[ 'image' ], $this->panel_def[ $component ]);

          if ($this->section[ '_panels_design_image-captions' ]) {
            $this->panel_def[ $component ] = str_replace('{{captioncode}}', '<span class="caption">' . $this->data[ 'image' ][ 'caption' ] . '</span>', $this->panel_def[ $component ]);
          }

          $this->panel_def[ $component ] = str_replace('{{image}}', $this->data[ 'image' ][ 'image' ], $this->panel_def[ $component ]);
          $this->panel_def[ $component ] = str_replace('{{incontent}}', 'in-content-thumb', $this->panel_def[ $component ]);

          if ('none' !== $this->section[ '_panels_design_link-image' ]) {
            $link = '';
            switch ($this->section[ '_panels_design_link-image' ]) {
              case 'page':
              case 'url':
                $link = ('url' === $this->section[ '_panels_design_link-image' ]) ? '<a href="' . $this->section[ '_panels_design_link-image-url' ] . '" title="' . $this->section[ '_panels_design_link-image-url-tooltip' ] . '">' : $this->panel_def[ 'postlink' ];
                break;
              case 'original':
                $link = '<a class="lightbox lightbox-' . $rsid . '" href="' . $this->data[ 'image' ][ 'original' ][ 0 ] . '" title="' . $this->data[ 'title' ][ 'title' ] . '">';
                break;
            }
            $this->panel_def[ $component ] = str_replace('{{postlink}}', $link, $this->panel_def[ $component ]);
            $this->panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $this->panel_def[ $component ]);
          }


//          if ($this->section[ '_panels_design_link-image' ]) {
//            $this->panel_def[ $component ] = str_replace('{{postlink}}', $this->panel_def[ 'postlink' ], $this->panel_def[ $component ]);
//            $this->panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $this->panel_def[ $component ]);
//          }
        }
      }
      if (empty($this->data[ 'image' ][ 'image' ]) && $this->section[ '_panels_design_maximize-content' ]) {
        //TODO: Add an option to set if width spreads
        $this->panel_def[ $component ] = str_replace('{{nothumb}}', 'nothumb', $this->panel_def[ $component ]);
      }

      return self::render_generics($component,$content_type);
    }

    public function render_excerpt($component, $content_type, $rsid)
    {
      $this->panel_def[ $component ] = str_replace('{{excerpt}}', $this->data[ 'excerpt' ], $this->panel_def[ $component ]);

      if ($this->section[ '_panels_design_thumb-position' ] != 'none') {
        if (!empty($this->data[ 'image' ][ 'image' ]) && !empty($this->section[ '_panels_design_thumb-position' ])) {
          $this->panel_def[ $component ] = str_replace('{{image-in-content}}', $this->panel_def[ 'image' ], $this->panel_def[ $component ]);

          if ($this->section[ '_panels_design_image-captions' ]) {
            $this->panel_def[ $component ] = str_replace('{{captioncode}}', '<span class="caption">' . $this->data[ 'image' ][ 'caption' ] . '</span>', $this->panel_def[ $component ]);
          }

          $this->panel_def[ $component ] = str_replace('{{image}}', $this->data[ 'image' ][ 'image' ], $this->panel_def[ $component ]);
          $this->panel_def[ $component ] = str_replace('{{incontent}}', 'in-content-thumb', $this->panel_def[ $component ]);

          if ('none' !== $this->section[ '_panels_design_link-image' ]) {
            $link = '';
            switch ($this->section[ '_panels_design_link-image' ]) {
              case 'page':
              case 'url':
                $link = ('url' === $this->section[ '_panels_design_link-image' ]) ? '<a href="' . $this->section[ '_panels_design_link-image-url' ] . '" title="' . $this->section[ '_panels_design_link-image-url-tooltip' ] . '">' : $this->panel_def[ 'postlink' ];
                break;
              case 'original':
                $link = '<a class="lightbox lightbox-' . $rsid . '" href="' . $this->data[ 'image' ][ 'original' ][ 0 ] . '" title="' . $this->data[ 'title' ][ 'title' ] . '">';
                break;
            }
            $this->panel_def[ $component ] = str_replace('{{postlink}}', $link, $this->panel_def[ $component ]);
            $this->panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $this->panel_def[ $component ]);
          }
        }
      }
      if (empty($this->data[ 'image' ][ 'image' ]) && $this->section[ '_panels_design_maximize-content' ]) {
        //TODO: Add an option to set if width spreads
        $this->panel_def[ $component ] = str_replace('{{nothumb}}', 'nothumb', $this->panel_def[ $component ]);
      }

//_panels_design_thumb-position


      return self::render_generics($component,$content_type);
    }

    public function render_image($component, $content_type, $rsid)
    {
      if ('none' !== $this->section[ '_panels_design_link-image' ]) {
        $link = '';
        switch ($this->section[ '_panels_design_link-image' ]) {
          case 'page':
          case 'url':
            $link = ('url' === $this->section[ '_panels_design_link-image' ]) ? '<a href="' . $this->section[ '_panels_design_link-image-url' ] . '" title="' . $this->section[ '_panels_design_link-image-url-tooltip' ] . '">' : $this->panel_def[ 'postlink' ];
            break;
          case 'original':
            $link = '<a class="lightbox lightbox-' . $rsid . '" href="' . $this->data[ 'image' ][ 'original' ][ 0 ] . '" title="' . $this->data[ 'title' ][ 'title' ] . '">';
            break;
        }
        $this->panel_def[ $component ] = str_replace('{{postlink}}', $link, $this->panel_def[ $component ]);
        $this->panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $this->panel_def[ $component ]);
      }


      if ($this->section[ '_panels_design_image-captions' ]) {
        $caption                       = str_replace('{{caption}}', $this->data[ 'image' ][ 'caption' ], $this->panel_def[ 'caption' ]);
        $this->panel_def[ $component ] = str_replace('{{captioncode}}', $caption, $this->panel_def[ $component ]);
      }

      $this->panel_def[ $component ] = str_replace('{{image}}', $this->data[ 'image' ][ 'image' ], $this->panel_def[ $component ]);

      if (!empty($this->section[ '_panels_design_centre-image' ])) {
        $this->panel_def[ $component ] = str_replace('{{centred}}', 'centred', $this->panel_def[ $component ]);
      }


      if (empty($this->data[ 'image' ][ 'image' ])) {
        $this->panel_def[ $component ] = '';
      }

//      foreach ($this->data[ 'image' ] as $key => $value) {
//        $template[ $type ] = str_replace('{{' . $key . '}}', $value, $template[ $type ]);
//      }

      return self::render_generics($component,$content_type);
    }


    public function render_bgimage($component, $content_type, $rsid)
    {
      $this->panel_def[ $component ] = str_replace('{{bgimage}}', $this->data[ 'bgimage' ][ 'thumb' ], $this->panel_def[ $component ]);
      $this->panel_def[ $component ] = str_replace('{{trim-scale}}', ' ' . $this->section[ '_panels_design_background-position' ] . ' ' . $this->section[ '_panels_design_background-image-resize' ], $this->panel_def[ $component ]);

      if ('none' !== $this->section[ '_panels_design_link-bgimage' ]) {
        $link = '';
        switch ($this->section[ '_panels_design_link-bgimage' ]) {
          case 'page':
          case 'url':
            $link = ('url' === $this->section[ '_panels_design_link-bgimage' ]) ? '<a href="' . $this->section[ '_panels_design_link-bgimage-url' ] . '" title="' . $this->section[ '_panels_design_link-bgimage-url-tooltip' ] . '">' : $this->panel_def[ 'postlink' ];
            break;
          case 'original':
            $link = '<a class="lightbox lightbox-' . $rsid . '" href="' . $this->data[ 'bgimage' ][ 'original' ][ 0 ] . '" title="' . $this->data[ 'title' ][ 'title' ] . '">';
            break;
        }
        $this->panel_def[ $component ] = str_replace('{{postlink}}', $link, $this->panel_def[ $component ]);
        $this->panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $this->panel_def[ $component ]);
      }

      return self::render_generics($component,$content_type);
    }

    public function render_customflds($component, $content_type, $rsid)
    {
    }

    public function render_wrapper($component, $content_type, $rsid)
    {
      $this->panel_def[ $component ] = str_replace('{{mimic-block-type}}', $this->data[ 'inherit-hw-block-type' ], $this->panel_def[ $component ]);

      return self::render_generics($component,$content_type);
    }

    public function render_generics($component,$source)
    {
      $line =  $this->panel_def[ $component ];
      //todo: make sure source is actual WP valid eg. soemthings might be attachment
      // Do any generic replacements
      $line      = str_replace('{{postid}}', $this->data[ 'postid' ], $line);
      $line      = str_replace('{{title}}', $this->data[ 'title' ][ 'title' ], $line);
      $line      = str_replace('{{permalink}}', $this->data[ 'permalink' ], $line);
      $line      = str_replace('{{closelink}}', '</a>', $line);
      $line      = str_replace('{{categories}}', $this->data[ 'meta' ][ 'categories' ], $line);
      $line      = str_replace('{{tags}}', $this->data[ 'meta' ][ 'tags' ], $line);
      $line      = str_replace('{{poststatus}}', $this->data[ 'poststatus' ], $line);
      $line      = str_replace('{{postformat}}', $this->data[ 'postformat' ], $line);
      $line      = str_replace('{{posttype}}', $source, $line);
      $pzclasses = 'pzarc-components ';
      $pzclasses .= ($this->section[ '_panels_design_components-position' ] === 'left' || $this->section[ '_panels_design_components-position' ] === 'right') ? 'vertical-content ' : '';

      $line = str_replace('{{pzclasses}}', $pzclasses, $line);
      return $line;
    }
  }