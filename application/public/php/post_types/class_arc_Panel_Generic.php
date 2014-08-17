<?php

  class arc_Panel_Generic
  {
    public $data = array();
    public $toshow = array();

    public function __construct(&$section)
    {

      $this->toshow = json_decode($section[ '_panels_design_preview' ], true);

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

    public function get_title($postid)
    {
      /** TITLE */
      if ($toshow[ 'title' ][ 'show' ]) {
        $data[ 'title' ] = get_the_title();
      }
    }

    public function get_meta($postid)
    {
      $postmeta = get_post_meta(get_the_ID());
      /** META */
      if ($toshow[ 'meta1' ][ 'show' ] ||
          $toshow[ 'meta2' ][ 'show' ] ||
          $toshow[ 'meta3' ][ 'show' ]
      ) {
        $data[ 'meta' ][ 'datetime' ]        = get_the_date();
        $data[ 'meta' ][ 'fdatetime' ]       = date_i18n($section[ '_panels_design_meta-date-format' ], strtotime(get_the_date()));
        $data[ 'meta' ][ 'categorieslinks' ] = get_the_category_list(', ');
        $data[ 'meta' ][ 'categories' ]      = pzarc_tax_string_list(get_the_category(), 'category-', '', ' ');
        $data[ 'meta' ][ 'tagslinks' ]       = get_the_tag_list(null, ', ');
        $data[ 'meta' ][ 'tags' ]            = pzarc_tax_string_list(get_the_tags(), 'tag-', '', ' ');

        $data[ 'meta' ][ 'authorlink' ] = get_author_posts_url(get_the_author_meta('ID'));
        $data[ 'meta' ][ 'authorname' ] = sanitize_text_field(get_the_author_meta('display_name'));
        $rawemail                       = sanitize_email(get_the_author_meta('user_email'));
        $encodedmail                    = '';
        for ($i = 0; $i < strlen($rawemail); $i++) {
          $encodedmail .= "&#" . ord($rawemail[ $i ]) . ';';
        }
        $data[ 'meta' ][ 'authoremail' ]    = $encodedmail;
        $data[ 'meta' ][ 'comments-count' ] = get_comments_number();

        $data[ 'meta' ][ 'custom' ][ 1 ] = self::get_post_terms(get_the_id(), $section[ '_panels_design_meta1-config' ]);
        $data[ 'meta' ][ 'custom' ][ 2 ] = self::get_post_terms(get_the_id(), $section[ '_panels_design_meta2-config' ]);
        $data[ 'meta' ][ 'custom' ][ 3 ] = self::get_post_terms(get_the_id(), $section[ '_panels_design_meta3-config' ]);
      }
    }

    public function get_fimage($postid)
    {
      /** FEATURED IMAGE */
      $thumb_id = get_post_thumbnail_id();

      if (!$thumb_id && $section[ '_panels_settings_use-embedded-images' ]) {
        //TODO: Changed to more reliable check if image is in the content?
        preg_match("/(?<=wp-image-)(\\d)*/uimx", get_the_content(), $matches);
        $thumb_id = (!empty($matches[ 0 ]) ? $matches[ 0 ] : false);
      }

      $focal_point = get_post_meta($thumb_id, 'pzgp_focal_point', true);
      $focal_point = (empty($focal_point) ? array(50, 50) : explode(',', $focal_point));

      if ($toshow[ 'image' ][ 'show' ] || $section[ '_panels_design_thumb-position' ] != 'none') {
        //        if (false)
        //        {
        //          $post_image = ($this->panel_info[ '_panels_design_thumb-position' ] != 'none') ? job_resize($thumb_src, $params, PZARC_CACHE_PATH, PZARC_CACHE_URL) : null;
        //        }
        // BFI

        $width  = (int)str_replace('px', '', $section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
        $height = (int)str_replace('px', '', $section[ '_panels_design_image-max-dimensions' ][ 'height' ]);

        // TODO: Add all the focal point stuff to all the post types images and bgimages
        // Easiest to do via a reusable function or all this stuff could be done once!!!!!!!!!
        // could pass $data thru a filter
        $data[ 'image' ][ 'image' ]    = wp_get_attachment_image($thumb_id, array($width,
                                                                                  $height,
                                                                                  'bfi_thumb' => true,
                                                                                  'crop'      => (int)$focal_point[ 0 ] . 'x' . (int)$focal_point[ 1 ] . 'x' . $section[ '_panels_settings_image-focal-point' ]

        ));
        $data[ 'image' ][ 'original' ] = wp_get_attachment_image_src($thumb_id, 'full');
        $image                         = get_post($thumb_id);
        $data[ 'image' ][ 'caption' ]  = $image->post_excerpt;


      }
    }

    public function get_content($postid)
    {
      /** CONTENT */
      if ($toshow[ 'content' ][ 'show' ]) {
        $data[ 'content' ] = apply_filters('the_content', get_the_content());
      }
    }

    public function get_excerpt($postid)
    {
    }

    public function get_bgimage($postid)
    {
      /** BACKGROUND IMAGE */
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

      //  TODO: data-imagesrcs ="1,2,3", data-breakpoints="1,2,3". Then use js to change src.
      $width = (int)str_replace('px', '', $section[ '_panels_design_background-image-max' ][ 'width' ]);
      // TODO: Should this just choose the greater? Or could that be too stupid if  someone puts a very large max-height?
      if ($section[ '_panels_settings_panel-height-type' ] === 'height') {
        $height = (int)str_replace('px', '', $section[ '_panels_settings_panel-height' ][ 'height' ]);
      } else {
        $height = (int)str_replace('px', '', $section[ '_panels_design_background-image-max' ][ 'height' ]);
      }

      // Need to grab image again because it uses different dimensions for the bgimge
      $data[ 'bgimage' ][ 'thumb' ] = wp_get_attachment_image($thumb_id, array($width,
                                                                               $height,
                                                                               'bfi_thumb' => true,
                                                                               'crop'      => (int)$focal_point[ 0 ] . 'x' . (int)$focal_point[ 1 ] . 'x' . $section[ '_panels_settings_image-focal-point' ]
      )); //WP seems to smartly figure out which of its saved images to use! Now we jsut gotta get it t work with focal point

      $data[ 'bgimage' ][ 'original' ] = wp_get_attachment_image_src($thumb_id, 'full');
    }

    public function get_custom($postid)
    {
      /** CUSTOM FIELDS **/
      $cfcount = $section[ '_panels_design_custom-fields-count' ];
      for ($i = 1; $i <= $cfcount; $i++) {
        // var_dump($section);
        // the settings come from section
        $data[ 'cfield' ][ $i ][ 'group' ]       = $section[ '_panels_design_cfield-' . $i . '-group' ];
        $data[ 'cfield' ][ $i ][ 'name' ]        = $section[ '_panels_design_cfield-' . $i . '-name' ];
        $data[ 'cfield' ][ $i ][ 'field-type' ]  = $section[ '_panels_design_cfield-' . $i . '-field-type' ];
        $data[ 'cfield' ][ $i ][ 'date-format' ] = $section[ '_panels_design_cfield-' . $i . '-date-format' ];
        $data[ 'cfield' ][ $i ][ 'wrapper-tag' ] = $section[ '_panels_design_cfield-' . $i . '-wrapper-tag' ];
        $data[ 'cfield' ][ $i ][ 'class-name' ]  = $section[ '_panels_design_cfield-' . $i . '-class-name' ];
        $data[ 'cfield' ][ $i ][ 'link-field' ]  = $section[ '_panels_design_cfield-' . $i . '-link-field' ];
        $data[ 'cfield' ][ $i ][ 'prefix-text' ] = $section[ '_panels_design_cfield-' . $i . '-prefix-text' ];
        $params                                  = array('width'  => str_replace($section[ '_panels_design_cfield-' . $i . '-ps-images-width' ][ 'units' ], '', $section[ '_panels_design_cfield-' . $i . '-ps-images-width' ][ 'width' ]),
                                                         'height' => str_replace($section[ '_panels_design_cfield-' . $i . '-ps-images-height' ][ 'units' ], '', $section[ '_panels_design_cfield-' . $i . '-ps-images-height' ][ 'height' ]));

        $data[ 'cfield' ][ $i ][ 'prefix-image' ] = bfi_thumb($section[ '_panels_design_cfield-' . $i . '-prefix-image' ][ 'url' ], $params);
        $data[ 'cfield' ][ $i ][ 'suffix-text' ]  = $section[ '_panels_design_cfield-' . $i . '-suffix-text' ];
        $data[ 'cfield' ][ $i ][ 'suffix-image' ] = bfi_thumb($section[ '_panels_design_cfield-' . $i . '-suffix-image' ][ 'url' ], $params);

        // The content itself comes from post meta
        $data[ 'cfield' ][ $i ][ 'value' ] = (!empty($postmeta[ $section[ '_panels_design_cfield-' . $i . '-name' ] ]) ? $postmeta[ $section[ '_panels_design_cfield-' . $i . '-name' ] ][ 0 ] : null);
        // TODO:Bet this doesn't work!
        if (!empty($section[ '_panels_design_cfield-' . $i . '-link-field' ])) {
          $data[ 'cfield' ][ $i ][ 'link-field' ] = (!empty($postmeta[ $section[ '_panels_design_cfield-' . $i . '-link-field' ] ]) ? $postmeta[ $section[ '_panels_design_cfield-' . $i . '-link-field' ] ][ 0 ] : null);
        }

        // TODO : Add other attributes
      }

    }

  }