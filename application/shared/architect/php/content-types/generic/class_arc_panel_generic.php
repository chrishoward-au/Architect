<?php

  class arc_Panel_Generic {
    public $data = array();
    public $toshow = array();
    public $section = array();
    public $thumb_id;
    public $focal_point = array();
    public $build;
    public $arc_query;
    public $panel_number;

    public function __construct( &$build ) {
      // If you create you own construct, remember to include these two lines!
      $this->build = $build;
      pzdb( 'arc_panel_generic before initialise' );
      self::initialise_data();
      pzdb( 'arc_panel_generic after initialise' );
//      add_filter('arc_set_post',array($this,'set_post'),10,2);
//      add_filter('arc_set_postid',array($this,'set_postid'),10,2);
      add_filter( 'arc_set_accordion_title', array( $this, 'set_accordion_title' ), 10 );
    }

    public function initialise_data() {
      // Null up everything to prevent warnings later on
      $this->data[ 'title' ]            = null;
      $this->data[ 'title' ][ 'title' ] = null;
      $this->data[ 'title' ][ 'thumb' ] = null;

      $this->data[ 'content' ] = null;

      $this->data[ 'excerpt' ] = null;

      $this->data[ 'meta' ][ 'categories' ] = null;
      $this->data[ 'meta' ][ 'tags' ]       = null;

      $this->data[ 'image' ][ 'image' ]    = null;
      $this->data[ 'image' ][ 'caption' ]  = null;
      $this->data[ 'image' ][ 'original' ] = null;
      $this->data[ 'image' ][ 'id' ]       = null;

      $this->data[ 'bgimage' ][ 'thumb' ] = null;

      $this->data[ 'video' ][ 'source' ] = null;

      $this->data[ 'meta' ][ 'id' ]              = null;
      $this->data[ 'meta' ][ 'datetime' ]        = null;
      $this->data[ 'meta' ][ 'fdatetime' ]       = null;
      $this->data[ 'meta' ][ 'categorieslinks' ] = null;
      $this->data[ 'meta' ][ 'tagslinks' ]       = null;
      $this->data[ 'meta' ][ 'authorlink' ]      = null;
      $this->data[ 'meta' ][ 'avatara' ]         = null;
      $this->data[ 'meta' ][ 'avatarb' ]         = null;
      $this->data[ 'meta' ][ 'authorname' ]      = null;
      $this->data[ 'meta' ][ 'authoremail' ]     = null;
      $this->data[ 'meta' ][ 'comments-count' ]  = null;
      $this->data[ 'cfield' ]                    = null;
      $this->data[ 'postid' ]                    = null;
      $this->data[ 'poststatus' ]                = null;
      $this->data[ 'permalink' ]                 = null;
      $this->data[ 'postformat' ]                = null;

      // $this->data[ 'bgimage' ][ 'original' ] = null;

      $this->data = apply_filters( 'arc_init_data', $this->data );
    }

    /**
     * Method: panel_def
     *
     * Defines the standard panel definition. Overwrite with own as necessary. See gallery post type for example
     *
     */
    public function panel_def() {
      //TODO: Need to get a way to always wrap components in pzarc-compenents div.Problem is...dev has to create definition correctly.
      $panel_def[ 'components-open' ]  = '<article id="post-{{postid}}" class="{{extensionclass}} {{mimic-block-type}} post-{{postid}} {{posttype}} type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{categories}} {{tags}} {{pzclasses}}" {{extensiondata}}>';
      $panel_def[ 'components-close' ] = '</article>';
      $panel_def[ 'postlink' ]         = '<a href="{{permalink}}" title="{{title}}">';
      //     $panel_def[ 'header' ]           = '<header class="entry-header">{{headerinnards}}</header>';
      $panel_def[ 'title' ] = '{{h1open}} class="{{extensionclass}} entry-title" {{extensiondata}}>{{postlink}}{{title}}{{closepostlink}}{{h1close}}';
      $panel_def[ 'meta1' ] = '<{{div}} class="{{extensionclass}} entry-meta entry-meta1" {{sortable}} {{extensiondata}}>{{meta1innards}}</{{div}}>';
      $panel_def[ 'meta2' ] = '<{{div}} class="{{extensionclass}} entry-meta entry-meta2" {{sortable}} {{extensiondata}}>{{meta2innards}}</{{div}}>';
      $panel_def[ 'meta3' ] = '<{{div}} class="{{extensionclass}} entry-meta entry-meta3" {{sortable}} {{extensiondata}}>{{meta3innards}}</{{div}}>';
      // TODO Make this only used in tables
      $panel_def[ 'datetime' ]   = '<span class="entry-date"><a href="{{permalink}}" ><time class="entry-date" datetime="{{datetime}}">{{fdatetime}}</time></a></span>';
      $panel_def[ 'categories' ] = '<span class="categories-links">{{categorieslinks}}</span>';
      $panel_def[ 'tags' ]       = '<span class="tags-links">{{tagslinks}}</span>';
      $panel_def[ 'author' ]     = '<span class="byline"><span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{avatarb}}{{authorname}}{{avatara}}</a></span></span>';
      $panel_def[ 'email' ]      = '<span class="byline email"><span class="author vcard"><a class="url fn n" href="mailto:{{authoremail}}" title="Email {{authorname}}" rel="author">{{authoremail}}</a></span></span>';
      //     $panel_def[ 'image' ]       = '<figure class="entry-thumbnail {{incontent}}">{{postlink}}<img width="{{width}}" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}">{{closepostlink}}{{captioncode}}</figure>';
      $panel_def[ 'image' ]   = '{{figopen}} class="{{extensionclass}} entry-thumbnail {{incontent}} {{centred}} {{nofloat}} {{location}}" {{extensiondata}} {{extrastyling}}>{{postlink}}{{image}}{{closelink}}{{captioncode}}{{figclose}}';
      $panel_def[ 'bgimage' ] = '<figure class="{{extensionclass}} entry-bgimage pzarc-bg-image {{trim-scale}}" {{extensiondata}}>{{postlink}}{{bgimage}}{{closelink}}</figure>';
      $panel_def[ 'caption' ] = '<figcaption class="caption">{{caption}}</figcaption>';
      $panel_def[ 'content' ] = '<{{div}} class="{{extensionclass}} entry-content {{nothumb}}" {{extensiondata}}>{{image-in-content}}{{content}}</{{div}}>';
      $panel_def[ 'custom1' ] = '<{{div}} class="{{extensionclass}} entry-customfieldgroup entry-customfieldgroup-1" {{extensiondata}}>{{custom1innards}}</{{div}}>';
      $panel_def[ 'custom2' ] = '<{{div}} class="{{extensionclass}} entry-customfieldgroup entry-customfieldgroup-2" {{extensiondata}}>{{custom2innards}}</{{div}}>';
      $panel_def[ 'custom3' ] = '<{{div}} class="{{extensionclass}} entry-customfieldgroup entry-customfieldgroup-3" {{extensiondata}}>{{custom3innards}}</{{div}}>';
      $panel_def[ 'cfield' ]  = '<{{cfieldwrapper}} class="entry-customfield entry-customfield-{{cfieldname}} {{cfieldname}} entry-customfield-{{cfieldnumber}}" {{cfielddata}}>{{cfieldcontent}}</{{cfieldwrapper}}>';
//      $panel_def[ 'footer' ]        = '<footer class="entry-footer">{{footerinnards}}</footer>';
      $panel_def[ 'excerpt' ]       = '<{{div}} class="{{extensionclass}} entry-excerpt {{nothumb}}" {{extensiondata}}>{{image-in-content}}{{excerpt}}</{{div}}>';
      $panel_def[ 'feature' ]       = '{{feature}}';
      $panel_def[ 'editlink' ]      = '<span class="edit-link"><a class="post-edit-link" href="{{permalink}}" title="Edit post {{title}}">Edit</a></span>';
      $panel_def[ 'comments-link' ] = '<span class="comments-link"><a href="{{permalink}}/#comments" title="Comment on {{title}}">Comments: {{commentscount}}</a></span>';
      $panel_def[ 'customtax' ]     = '<span class="{{customtax}}-links">{{customtaxlinks}}</span>';


//TODO This has to be changed back once we.if we use a link instead of theget thumnail
      //$panel_def[ 'image' ]        = '<img class="entry-image" src="{{image}}">';
      // Yes, WP themes (T13, T14 etc) actually link the date to the post, not the archive for the date. Maybe it's an SEO thing, but I'm going to remove it
      // $panel_def[ 'datetime' ]      = '<span class="date"><a href="{{permalink}}" title="{{title}}" rel="bookmark"><time class="entry-date" datetime="{{datetime}}">{{fdatetime}}</time></a></span>';
      // oops should be using this for featured image

      $panel_def = apply_filters( 'arc_panel_def', $panel_def );

      // a bit of housekeeping incase third parties don't remove their filters
      remove_all_filters( 'pzarc_panel_def' );

      return $panel_def;
    }


    public function set_data( &$post, &$toshow, &$section, $panel_number ) {
      $this->section      = $section;
      $this->toshow       = $toshow;
      $this->panel_number = $panel_number;
//      if ( $this->toshow[ 'title' ][ 'show' ] ) {
      // We always need the title  for images
      $this->get_title( $post );
      pzdb( 'after get title' );
//      }
      if ( $this->toshow[ 'meta1' ][ 'show' ] ||
           $this->toshow[ 'meta2' ][ 'show' ] ||
           $this->toshow[ 'meta3' ][ 'show' ]
      ) {
        $this->get_meta( $post );
        pzdb( 'after get meta' );
      }

      if ( $this->toshow[ 'content' ][ 'show' ] ) {
        $this->get_content( $post );
        pzdb( 'after get content' );
      }

      if ( $this->toshow[ 'excerpt' ][ 'show' ] ) {
        $this->get_excerpt( $post );
        pzdb( 'after get excerpt' );
      }

      if ( $this->toshow[ 'image' ][ 'show' ] ) {
        switch ( $this->section[ '_panels_design_feature-location' ] ) {
          case 'fill':
            $this->get_bgimage( $post );
            pzdb( 'after get bgimage' );
            break;
          default:
            $this->get_image( $post );
            pzdb( 'after get image' );
            break;
        }
        $this->get_video( $post );
        pzdb( 'after get video' );
      }

      if ( $this->toshow[ 'custom1' ][ 'show' ] ||
           $this->toshow[ 'custom2' ][ 'show' ] ||
           $this->toshow[ 'custom3' ][ 'show' ]
      ) {
        $postmeta = apply_filters( 'arc_get_custom_data', $this->get_content_meta( $post ) );
        $this->get_custom( $post, $postmeta );
        pzdb( 'after get custom' );
      }

      $this->get_miscellanary( $post );
      pzdb( 'after get misc' );

      // Allow other plugins to do their data set and get here.
      do_action( 'arc_set_data' );
    }

    /**
     * @param $post
     */
    public function get_title( &$post ) {
      /** TITLE */
      if ( class_exists( 'HeadwayLayoutOption' ) && ! empty( $this->section[ '_panels_design_alternate-titles' ] ) && ( true == ( $alt_title = HeadwayLayoutOption::get( $post->ID, 'alternate-title', false, true ) ) ) ) {
        $this->data[ 'title' ][ 'title' ] = $alt_title;
      } else {
        $this->data[ 'title' ][ 'title' ] = get_the_title();
      }
      if ( 'thumb' === $this->section[ '_panels_design_title-prefix' ] ) {
        $thumb_id    = get_post_thumbnail_id();
        $focal_point = get_post_meta( $thumb_id, 'pzgp_focal_point', true );
        if ( empty( $focal_point ) ) {
          $focal_point = get_post_meta( get_the_id(), 'pzgp_focal_point', true );
        }
        $focal_point = ( empty( $focal_point ) ? array( 50, 50 ) : explode( ',', $focal_point ) );
        if ( ! empty( $thumb_id ) ) {
          $thumb_prefix                     = wp_get_attachment_image( $thumb_id, array(
            $this->section[ '_panels_design_title-thumb-width' ],
            $this->section[ '_panels_design_title-thumb-width' ],
            'bfi_thumb' => true,
            'crop'      => (int) $focal_point[ 0 ] . 'x' . (int) $focal_point[ 1 ]
          ) );
          $this->data[ 'title' ][ 'thumb' ] = '<span class="pzarc-title-thumb">' . $thumb_prefix . '</span> ';
        } else {
          $this->data[ 'title' ][ 'thumb' ] = '<span class="pzarc-title-thumb" style="width:' . $this->section[ '_panels_design_title-thumb-width' ] . 'px;height:' . $this->section[ '_panels_design_title-thumb-width' ] . 'px;"></span> ';
        }
      }


    }

    public function get_meta( &$post ) {
      $meta_string = $this->toshow[ 'meta1' ][ 'show' ] ? $this->section[ '_panels_design_meta1-config' ] : '';
      $meta_string .= $this->toshow[ 'meta2' ][ 'show' ] ? $this->section[ '_panels_design_meta2-config' ] : '';
      $meta_string .= $this->toshow[ 'meta3' ][ 'show' ] ? $this->section[ '_panels_design_meta3-config' ] : '';

      /** META */
      if ( strpos( $meta_string, '%id%' ) !== false ) {
        $this->data[ 'meta' ][ 'id' ] = get_the_id();
        $this->data[ 'meta' ][ 'id' ] .= ' blueprint=' . $this->build->blueprint[ 'blueprint-id' ];
      }
      if ( strpos( $meta_string, '%date%' ) !== false ) {
        $this->data[ 'meta' ][ 'datetime' ] = get_the_date();
//        $this->data[ 'meta' ][ 'fdatetime' ] = date_i18n(strip_tags($this->section[ '_panels_design_meta-date-format' ]), str_replace(',', ' ', strtotime(get_the_date())));
        $this->data[ 'meta' ][ 'fdatetime' ] = date_i18n( strip_tags( $this->section[ '_panels_design_meta-date-format' ] ), strtotime( str_replace( ',', ' ', get_the_date() ) ) );
      }
      if ( strpos( $meta_string, '%categories%' ) !== false ) {
        $this->data[ 'meta' ][ 'categorieslinks' ] = get_the_category_list( ', ' );
        $this->data[ 'meta' ][ 'categories' ]      = pzarc_tax_string_list( get_the_category(), 'category-', '', ' ' );
      }
      if ( strpos( $meta_string, '%tags%' ) !== false ) {
        $this->data[ 'meta' ][ 'tagslinks' ] = get_the_tag_list( null, ', ' );
        $this->data[ 'meta' ][ 'tags' ]      = pzarc_tax_string_list( get_the_tags(), 'tag-', '', ' ' );
      }
      if ( strpos( $meta_string, '%author%' ) !== false ) {

        $this->data[ 'meta' ][ 'authorlink' ] = get_author_posts_url( get_the_author_meta( 'ID' ) );
        $this->data[ 'meta' ][ 'authorname' ] = sanitize_text_field( get_the_author_meta( 'display_name' ) );
        $rawemail                             = sanitize_email( get_the_author_meta( 'user_email' ) );
        $encodedmail                          = '';
        for ( $i = 0; $i < strlen( $rawemail ); $i ++ ) {
          $encodedmail .= "&#" . ord( $rawemail[ $i ] ) . ';';
        }
        $this->data[ 'meta' ][ 'authoremail' ] = $encodedmail;
      }
      if ( ! empty( $this->section[ '_panels_design_avatar' ] ) && $this->section[ '_panels_design_avatar' ] !== 'none' ) {
        if ( $this->section[ '_panels_design_avatar' ] === 'before' ) {
          $this->data[ 'meta' ][ 'avatarb' ] = get_avatar( get_the_author_meta( 'ID' ), ( ! empty( $this->section[ '_panels_design_avatar-size' ] ) ? $this->section[ '_panels_design_avatar-size' ] : 96 ) );
        } else {
          $this->data[ 'meta' ][ 'avatara' ] = get_avatar( get_the_author_meta( 'ID' ), ( ! empty( $this->section[ '_panels_design_avatar-size' ] ) ? $this->section[ '_panels_design_avatar-size' ] : 96 ) );
        }
      }
      $this->data[ 'meta' ][ 'comments-count' ] = get_comments_number();

      // Extract and find any custom taxonomies - i.e. preceded with ct:
      if ( strpos( $meta_string, 'ct:' ) !== false ) {
        $this->data[ 'meta' ][ 'custom' ][ 1 ] = $this->toshow[ 'meta1' ][ 'show' ] ? pzarc_get_post_terms( get_the_id(), $this->section[ '_panels_design_meta1-config' ] ) : '';
        $this->data[ 'meta' ][ 'custom' ][ 2 ] = $this->toshow[ 'meta2' ][ 'show' ] ? pzarc_get_post_terms( get_the_id(), $this->section[ '_panels_design_meta2-config' ] ) : '';
        $this->data[ 'meta' ][ 'custom' ][ 3 ] = $this->toshow[ 'meta3' ][ 'show' ] ? pzarc_get_post_terms( get_the_id(), $this->section[ '_panels_design_meta3-config' ] ) : '';
      }
    }


    public function get_image( &$post ) {

      /** FEATURED IMAGE */
      $thumb_id    = get_post_thumbnail_id();
      $focal_point = get_post_meta( $thumb_id, 'pzgp_focal_point', true );
      if ( empty( $focal_point ) ) {
        $focal_point = get_post_meta( get_the_id(), 'pzgp_focal_point', true );
      }
      $focal_point = ( empty( $focal_point ) ? array( 50, 50 ) : explode( ',', $focal_point ) );

      if ( ! $thumb_id && $this->section[ '_panels_settings_use-embedded-images' ] ) {
        //TODO: Change to more reliable check if image is in the content?
        preg_match( "/(?<=wp-image-)(\\d)*/uimx", get_the_content(), $matches );
        $thumb_id = ( ! empty( $matches[ 0 ] ) ? $matches[ 0 ] : false );
      }

      if ( $post->post_type === 'attachment' ) {
        $thumb_id = $post->ID;
      }
      $this->data[ 'image' ][ 'id' ] = $thumb_id;

      //        if (false)
      //        {
      //          $post_image = ($this->panel_info[ '_panels_design_thumb-position' ] != 'none') ? job_resize($thumb_src, $params, PZARC_CACHE_PATH, PZARC_CACHE_URL) : null;
      //        }
      // BFI

      $width  = (int) str_replace( 'px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ] );
      $height = (int) str_replace( 'px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ] );

      // TODO: Add all the focal point stuff to all the post types images and bgimages
      // Easiest to do via a reusable function or all this stuff could be done once!!!!!!!!!
      // could pass $this->data thru a filter

      $this->data[ 'image' ][ 'image' ] = wp_get_attachment_image( $thumb_id, array(
        $width,
        $height,
        'bfi_thumb' => true,
        'crop'      => (int) $focal_point[ 0 ] . 'x' . (int) $focal_point[ 1 ] . 'x' . $this->section[ '_panels_settings_image-focal-point' ]

      ) );

      $this->data[ 'image' ][ 'original' ] = wp_get_attachment_image_src( $thumb_id, 'full' );
      preg_match( "/(?<=src\\=\")(.)*(?=\" )/uiUs", $this->data[ 'image' ][ 'image' ], $results );
      if ( isset( $results[ 0 ] ) && ! empty( $this->section[ '_panels_settings_use-retina-images' ] ) && function_exists( 'bfi_thumb' ) ) {
        $params = array( 'width' => ( $width * 2 ), 'height' => ( $height * 2 ) );
        // We need the crop to be identical. :/ So how about we just double the size of the image! I'm sure I Saw somewhere that works still.
        $thumb_2X                         = bfi_thumb( $results[ 0 ], $params );
        $this->data[ 'image' ][ 'image' ] = str_replace( '/>', 'data-at2x="' . $thumb_2X . '" />', $this->data[ 'image' ][ 'image' ] );
      }
      $image = get_post( $thumb_id );

      $this->data[ 'image' ][ 'caption' ] = is_object( $image ) ? $image->post_excerpt : '';

      //Use lorempixel
      if ( empty( $this->data[ 'image' ][ 'image' ] )
           && ! empty( $this->section[ '_panels_design_use-filler-image-source' ] )
           && 'none' !== $this->section[ '_panels_design_use-filler-image-source' ]
           && 'specific' !== $this->section[ '_panels_design_use-filler-image-source' ]
      ) {
        $ch = curl_init( 'http://lorempixel.com' );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $cexec      = curl_exec( $ch );
        $cinfo      = curl_getinfo( $ch );
        $is_offline = ( $cexec == false || $cinfo[ 'http_code' ] == 302 );
        curl_close( $ch );


        $cats                = array(
          'abstract',
          'animals',
          'business',
          'cats',
          'city',
          'food',
          'nightlife',
          'fashion',
          'people',
          'nature',
          'sports',
          'technics',
          'transport'
        );
        $lorempixel_category = in_array( $this->section[ '_panels_design_use-filler-image-source' ], $cats ) ? $this->section[ '_panels_design_use-filler-image-source' ] : $cats[ rand( 0, count( $cats ) - 1 ) ];
        $imageURL            = 'http://lorempixel.com/' . $width . '/' . $height . '/' . $lorempixel_category . '/' . rand( 1, 10 );
//        $imageURL = 'http://lorempixel.com/' . $image_grey . $width . '/' . $height . '/' . $post[ 'image' ][ 'original' ];
        $this->data[ 'image' ][ 'image' ]    = ! $is_offline ? '<img src="' . $imageURL . '" >' : '';
        $this->data[ 'image' ][ 'original' ] = ! $is_offline ? array( $imageURL, $width, $height, false ) : false;
        $this->data[ 'image' ][ 'caption' ]  = '';

      }
      // TODO: Add retina for this maybe. Tho client side retina may fix anyway
      if ( empty( $this->data[ 'image' ][ 'image' ] )
           && 'specific' === $this->section[ '_panels_design_use-filler-image-source' ]
           && ! empty( $this->section[ '_panels_design_use-filler-image-source-specific' ][ 'url' ] )
      ) {
        if ( function_exists( 'bfi_thumb' ) ) {
          $imageURL = bfi_thumb( $this->section[ '_panels_design_use-filler-image-source-specific' ][ 'url' ], array(
            'width'  => $width,
            'height' => $height
          ) );
        } else {
          $imageURL = $this->section[ '_panels_design_use-filler-image-source-specific' ][ 'url' ];
        }
        $this->data[ 'image' ][ 'image' ]    = '<img src="' . $imageURL . '" >';
        $this->data[ 'image' ][ 'original' ] = array( $imageURL, $width, $height, false );
        $this->data[ 'image' ][ 'caption' ]  = '';
      }
    }

    public function get_bgimage( &$post ) {
      /** BACKGROUND IMAGE */

      $thumb_id    = get_post_thumbnail_id();
      $focal_point = get_post_meta( $thumb_id, 'pzgp_focal_point', true );
      if ( $post->post_type === 'attachment' ) {
        $thumb_id = $post->ID;
      }
      $this->data[ 'image' ][ 'id' ] = $thumb_id;
      // If the post is already passing the attachment,the above won't work so we need to use the post id
      if ( empty( $focal_point ) ) {
        $focal_point = get_post_meta( get_the_id(), 'pzgp_focal_point', true );
      }
      $focal_point = ( empty( $focal_point ) ? array( 50, 50 ) : explode( ',', $focal_point ) );

      $showbgimage = ( has_post_thumbnail()
                       && $this->section[ '_panels_design_feature-location' ] === 'fill'
                       && ( $this->section[ '_panels_design_components-position' ] == 'top' || $this->section[ '_panels_design_components-position' ] == 'left' ) )
                     || ( $this->section[ '_panels_design_feature-location' ] === 'fill'
                          && ( $this->section[ '_panels_design_components-position' ] == 'bottom' || $this->section[ '_panels_design_components-position' ] == 'right' ) );
      // Need to setup for break points.

      //  TODO: data-imagesrcs ="1,2,3", data-breakpoints="1,2,3". Then use js to change src.
      $width = (int) str_replace( 'px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ] );
      // TODO: Should this just choose the greater? Or could that be too stupid if  someone puts a very large max-height?
      if ( $this->section[ '_panels_settings_panel-height-type' ] === 'height' ) {
        $height = (int) str_replace( 'px', '', $this->section[ '_panels_settings_panel-height' ][ 'height' ] );
      } else {
        $height = (int) str_replace( 'px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ] );
      }

      pzdb( 'pre get image bg' );

      // Need to grab image again because it uses different dimensions for the bgimge
      $this->data[ 'bgimage' ][ 'thumb' ] = wp_get_attachment_image( $thumb_id, array(
        $width,
        $height,
        'bfi_thumb' => true,
        'crop'      => (int) $focal_point[ 0 ] . 'x' . (int) $focal_point[ 1 ] . 'x' . $this->section[ '_panels_settings_image-focal-point' ]
      ) );
      pzdb( 'post get image bg' );
      $this->data[ 'image' ][ 'original' ] = wp_get_attachment_image_src( $thumb_id, 'full' );
      pzdb( 'post get original bg' );
      preg_match( "/(?<=src\\=\")(.)*(?=\" )/uiUs", $this->data[ 'bgimage' ][ 'thumb' ], $results );
      if ( isset( $results[ 0 ] ) && ! empty( $this->section[ '_panels_settings_use-retina-images' ] ) && function_exists( 'bfi_thumb' ) ) {
        $params = array( 'width' => ( $width * 2 ), 'height' => ( $height * 2 ) );
        // We need the crop to be identical. :/ So how about we just double the size of the image! I'm sure I Saw somewhere that works still. In fact, we have no choice, since the double sized image could be bigger than the original.
        $thumb_2X                           = bfi_thumb( $results[ 0 ], $params );
        $this->data[ 'bgimage' ][ 'thumb' ] = str_replace( '/>', 'data-at2x="' . $thumb_2X . '" />', $this->data[ 'bgimage' ][ 'thumb' ] );
        pzdb( 'after get 2X bg' );
      }

      pzdb( 'end get bgimage' );

      //Use lorempixel
      if ( empty( $this->data[ 'bgimage' ][ 'thumb' ] )
           && ! empty( $this->section[ '_panels_design_use-filler-image-source' ] )
           && 'none' !== $this->section[ '_panels_design_use-filler-image-source' ]
           && 'specific' !== $this->section[ '_panels_design_use-filler-image-source' ]
      ) {
        $ch = curl_init( 'http://lorempixel.com' );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $cexec      = curl_exec( $ch );
        $cinfo      = curl_getinfo( $ch );
        $is_offline = ( $cexec == false || $cinfo[ 'http_code' ] == 302 );
        curl_close( $ch );


        $cats                = array(
          'abstract',
          'animals',
          'business',
          'cats',
          'city',
          'food',
          'nightlife',
          'fashion',
          'people',
          'nature',
          'sports',
          'technics',
          'transport'
        );
        $lorempixel_category = in_array( $this->section[ '_panels_design_use-filler-image-source' ], $cats ) ? $this->section[ '_panels_design_use-filler-image-source' ] : $cats[ rand( 0, count( $cats ) - 1 ) ];
        $imageURL            = 'http://lorempixel.com/' . $width . '/' . $height . '/' . $lorempixel_category . '/' . rand( 1, 10 );
//        $imageURL = 'http://lorempixel.com/' . $image_grey . $width . '/' . $height . '/' . $post[ 'image' ][ 'original' ];
        $this->data[ 'bgimage' ][ 'thumb' ]  = ! $is_offline ? '<img src="' . $imageURL . '" >' : '';
        $this->data[ 'image' ][ 'original' ] = ! $is_offline ? array( $imageURL, $width, $height, false ) : false;
        $this->data[ 'image' ][ 'caption' ]  = '';

      }
      if ( empty( $this->data[ 'bgimage' ][ 'thumb' ] )
           && 'specific' === $this->section[ '_panels_design_use-filler-image-source' ]
           && ! empty( $this->section[ '_panels_design_use-filler-image-source-specific' ][ 'url' ] )
      ) {
        if ( function_exists( 'bfi_thumb' ) ) {
          $imageURL = bfi_thumb( $this->section[ '_panels_design_use-filler-image-source-specific' ][ 'url' ], array(
            'width'  => $width,
            'height' => $height
          ) );
        } else {
          $imageURL = $this->section[ '_panels_design_use-filler-image-source-specific' ][ 'url' ];
        }
        $this->data[ 'bgimage' ][ 'thumb' ]  = '<img src="' . $imageURL . '" >';
        $this->data[ 'image' ][ 'original' ] = array( $imageURL, $width, $height, false );
        $this->data[ 'image' ][ 'caption' ]  = '';
      }

    }

    public function get_video( &$post ) {
      $video_source = ( is_object( $post ) ? get_post_meta( $post->ID, 'pzarc_features-video', true ) : '' );
      if ( ! empty( $this->section[ '_panels_settings_use-embedded-images' ] ) && empty( $video_source ) ) {
        $video_source = '[video]';
      }
      $this->data[ 'video' ][ 'source' ] = pzarc_process_video( $video_source );
    }

    public function get_content( &$post ) {
      /** CONTENT */
      $this->data[ 'content' ] = apply_filters( 'the_content', get_the_content() );
    }

    public function get_excerpt( &$post ) {
      switch ( true ) {

        case ! empty( $this->section[ '_panels_design_manual-excerpts' ] ) && ! has_excerpt():
          $this->data[ 'excerpt' ] = '';
          break;

        case ! empty( $this->section[ '_panels_design_excerpts-trim-type' ] ) && $this->section[ '_panels_design_excerpts-trim-type' ] === 'characters':
          $this->data[ 'excerpt' ] = apply_filters( 'the_excerpt', substr( wp_strip_all_tags( get_the_content() ), 0, $this->section[ '_panels_design_excerpts-word-count' ] ) . pzarc_make_excerpt_more( $this->section ) );
          break;

        case ! empty( $this->section[ '_panels_design_excerpts-trim-type' ] ) && $this->section[ '_panels_design_excerpts-trim-type' ] === 'paragraphs':
          $the_lot = wpautop( get_the_content() );
          if ( empty( $this->section[ '_panels_design_process-excerpts-shortcodes' ] ) || $this->section[ '_panels_design_process-excerpts-shortcodes' ] === 'process' ) {
            $the_lot = do_shortcode( $the_lot );
          } else {
            $the_lot = strip_shortcodes( $the_lot );
          }
          $the_lot   = str_replace( '</p>', '{/EOP/}', $the_lot );
          $the_lot   = str_replace( '<p>', '', $the_lot );
          $the_lot   = str_replace( '{/EOP/}{/EOP/}', '{/EOP/}', $the_lot );
          $the_paras = explode( '{/EOP/}', $the_lot );

          // get rid of any blank ones
          $the_new_paras = array();
          foreach ( $the_paras as $k => $the_para ) {
            if ( ! empty( $the_para ) ) {
              $the_new_paras[] = $the_para;
            }
          }
          $the_paras               = $the_new_paras;
          $this->data[ 'excerpt' ] = '';
          $i                       = 1;
          while ( $i <= (int) $this->section[ '_panels_design_excerpts-word-count' ] && $i <= count( $the_paras ) ) {
            $this->data[ 'excerpt' ] .= '<p>' . $the_paras[ $i - 1 ] . '</p>';
            $i ++;
          }
          $this->data[ 'excerpt' ] = apply_filters( 'the_excerpt', do_shortcode( $this->data[ 'excerpt' ] ) . pzarc_make_excerpt_more( $this->section ) );

          break;

        case
          ! empty( $this->section[ '_panels_design_excerpts-trim-type' ] ) && $this->section[ '_panels_design_excerpts-trim-type' ] === 'moretag':
          // More tags are automatically executed on the non-single pages. And no way to override. Pain!
          //
          $the_lot = get_extended( get_the_content() );
          if ( ! empty( $the_lot[ 'extended' ] ) ) {
            $this->data[ 'excerpt' ] = $the_lot[ 'main' ];
          } else {
            $this->data[ 'excerpt' ] = get_the_excerpt();
          }
          if ( empty( $this->section[ '_panels_design_process-excerpts-shortcodes' ] ) || $this->section[ '_panels_design_process-excerpts-shortcodes' ] === 'process' ) {
            $this->data[ 'excerpt' ] = apply_filters( 'the_excerpt', do_shortcode( $this->data[ 'excerpt' ] ) );
          } else {
            $this->data[ 'excerpt' ] = apply_filters( 'the_excerpt', strip_shortcodes( $this->data[ 'excerpt' ] ) );
          }
          break;

        default:
          $this->data[ 'excerpt' ] = get_the_excerpt();
          if ( empty( $this->section[ '_panels_design_process-excerpts-shortcodes' ] ) || $this->section[ '_panels_design_process-excerpts-shortcodes' ] === 'process' ) {
            $this->data[ 'excerpt' ] = apply_filters( 'the_excerpt', do_shortcode( $this->data[ 'excerpt' ] ) );
          } else {
            $this->data[ 'excerpt' ] = apply_filters( 'the_excerpt', strip_shortcodes( $this->data[ 'excerpt' ] ) );
          }
      }

    }


    public function get_custom( &$post, &$postmeta ) {
      /** CUSTOM FIELDS **/
      $cfcount = $this->section[ '_panels_design_custom-fields-count' ];
      for ( $i = 1; $i <= $cfcount; $i ++ ) {
        // the settings come from section
        if ( ! empty( $this->section[ '_panels_design_cfield-' . $i . '-name' ] ) ) {
          $this->data[ 'cfield' ][ $i ][ 'group' ]          = $this->section[ '_panels_design_cfield-' . $i . '-group' ];
          $this->data[ 'cfield' ][ $i ][ 'name' ]           = $this->section[ '_panels_design_cfield-' . $i . '-name' ];
          $this->data[ 'cfield' ][ $i ][ 'field-type' ]     = $this->section[ '_panels_design_cfield-' . $i . '-field-type' ];
          $this->data[ 'cfield' ][ $i ][ 'date-format' ]    = $this->section[ '_panels_design_cfield-' . $i . '-date-format' ];
          $this->data[ 'cfield' ][ $i ][ 'wrapper-tag' ]    = $this->section[ '_panels_design_cfield-' . $i . '-wrapper-tag' ];
          $this->data[ 'cfield' ][ $i ][ 'class-name' ]     = isset( $this->section[ '_panels_design_cfield-' . $i . '-class-name' ] ) ? $this->section[ '_panels_design_cfield-' . $i . '-class-name' ] : '';
          $this->data[ 'cfield' ][ $i ][ 'link-field' ]     = $this->section[ '_panels_design_cfield-' . $i . '-link-field' ];
          $this->data[ 'cfield' ][ $i ][ 'link-behaviour' ] = isset( $this->section[ '_panels_design_cfield-' . $i . '-link-behaviour' ] ) ? $this->section[ '_panels_design_cfield-' . $i . '-link-behaviour' ] : '_self';
          $this->data[ 'cfield' ][ $i ][ 'decimals' ]       = $this->section[ '_panels_design_cfield-' . $i . '-number-decimals' ];
          $this->data[ 'cfield' ][ $i ][ 'decimal-char' ]   = $this->section[ '_panels_design_cfield-' . $i . '-number-decimal-char' ];
          $this->data[ 'cfield' ][ $i ][ 'thousands-sep' ]  = $this->section[ '_panels_design_cfield-' . $i . '-number-thousands-separator' ];
          $params                                           = array(
            'width'  => str_replace( $this->section[ '_panels_design_cfield-' . $i . '-ps-images-width' ][ 'units' ], '', $this->section[ '_panels_design_cfield-' . $i . '-ps-images-width' ][ 'width' ] ),
            'height' => str_replace( $this->section[ '_panels_design_cfield-' . $i . '-ps-images-height' ][ 'units' ], '', $this->section[ '_panels_design_cfield-' . $i . '-ps-images-height' ][ 'height' ] )
          );

          $this->data[ 'cfield' ][ $i ][ 'prefix-text' ]  = '<span class="pzarc-prefix-text">' . $this->section[ '_panels_design_cfield-' . $i . '-prefix-text' ] . '</span>';
          $this->data[ 'cfield' ][ $i ][ 'prefix-image' ] = function_exists( 'bfi_thumb' ) ? bfi_thumb( $this->section[ '_panels_design_cfield-' . $i . '-prefix-image' ][ 'url' ], $params ) : $this->section[ '_panels_design_cfield-' . $i . '-prefix-image' ][ 'url' ];
          $this->data[ 'cfield' ][ $i ][ 'suffix-text' ]  = '<span class="pzarc-suffix-text">' . $this->section[ '_panels_design_cfield-' . $i . '-suffix-text' ] . '</span>';
          $this->data[ 'cfield' ][ $i ][ 'suffix-image' ] = function_exists( 'bfi_thumb' ) ? bfi_thumb( $this->section[ '_panels_design_cfield-' . $i . '-suffix-image' ][ 'url' ], $params ) : $this->section[ '_panels_design_cfield-' . $i . '-prefix-image' ][ 'url' ];

          // The content itself comes from post meta or post title
          if ( $this->section[ '_panels_design_cfield-' . $i . '-name' ] === 'post_title' ) {
            $this->data[ 'cfield' ][ $i ][ 'value' ] = $post->post_title;

          } elseif ( $this->section[ '_panels_design_cfield-' . $i . '-name' ] === 'use_empty' ) {
            $this->data[ 'cfield' ][ $i ][ 'value' ] = '';
          } else {
            $this->data[ 'cfield' ][ $i ][ 'value' ] = ( ! empty( $postmeta[ $this->section[ '_panels_design_cfield-' . $i . '-name' ] ] )
              ? $postmeta[ $this->section[ '_panels_design_cfield-' . $i . '-name' ] ]
              : null );
          }
          if ( is_Array( maybe_unserialize( $this->data[ 'cfield' ][ $i ][ 'value' ] ) ) ) {
            $this->data[ 'cfield' ][ $i ][ 'value' ] = implode( ',', maybe_unserialize( $this->data[ 'cfield' ][ $i ][ 'value' ] ) );
          }
          // TODO:Bet this doesn't work!
          if ( ! empty( $this->section[ '_panels_design_cfield-' . $i . '-link-field' ] ) ) {
            $this->data[ 'cfield' ][ $i ][ 'link-field' ] = ( ! empty( $postmeta[ $this->section[ '_panels_design_cfield-' . $i . '-link-field' ] ] )
              ? $postmeta[ $this->section[ '_panels_design_cfield-' . $i . '-link-field' ] ]
              : null );
          }

          if ( $this->section[ '_panels_design_cfield-' . $i . '-field-type' ] === 'date' ) {
            $cfdate                                 = is_numeric( $this->data[ 'cfield' ][ $i ][ 'value' ] ) ? $this->data[ 'cfield' ][ $i ][ 'value' ] : str_replace( ',', ' ', strtotime( $this->data[ 'cfield' ][ $i ][ 'value' ] ) ); //convert field value to date
            $cfdate                                 = empty( $cfdate ) ? '000000' : $cfdate;
            $this->data[ 'cfield' ][ $i ][ 'data' ] = "data-sort-date='{$cfdate}'";
          }

          if ( $this->section[ '_panels_design_cfield-' . $i . '-field-type' ] === 'number' ) {
            $cfnumeric                              = @number_format( $this->data[ 'cfield' ][ $i ][ 'value' ], $this->data[ 'cfield' ][ $i ][ 'decimals' ], '', '' );
            $cfnumeric                              = @number_format( $this->data[ 'cfield' ][ $i ][ 'value' ], $this->data[ 'cfield' ][ $i ][ 'decimals' ], '', '' );
            $cfnumeric                              = empty( $cfnumeric ) ? '0000' : $cfnumeric;
            $this->data[ 'cfield' ][ $i ][ 'data' ] = "data-sort-numeric='{$cfnumeric}'";
          }
          // TODO : Add other attributes
        }
//        var_dump($this->data[ 'cfield' ][ $i ]);
      }
    }

    public
    function get_miscellanary(
      &$post
    ) {
      global $_architect_options;
      $this->data[ 'inherit-hw-block-type' ] = ( ! empty( $_architect_options[ 'architect_hw-content-class' ] ) ? 'block-type-content ' : '' );

      $this->data[ 'postid' ]      = get_the_ID();
      $this->data[ 'poststatus' ]  = get_post_status();
      $this->data[ 'posttype' ]    = get_post_type();
      $this->data[ 'permalink' ]   = get_the_permalink();
      $post_format                 = get_post_format();
      $this->data [ 'postformat' ] = ( empty( $post_format ) ? 'standard' : $post_format );

    }

    /****************************************
     * End of data collect
     ***************************************/

    /****************************************
     * Begin rendering
     ***************************************/

    public
    function render_title(
      $component, $content_type, $panel_def, $rsid, $layout_mode = false
    ) {
      if ( 'thumb' === $this->section[ '_panels_design_title-prefix' ] ) {
        $panel_def[ $component ] = str_replace( '{{title}}', $this->data[ 'title' ][ 'thumb' ] . '<span class="pzarc-title-wrap">' . $this->data[ 'title' ][ 'title' ] . '</span>', $panel_def[ $component ] );
      } else {
        $panel_def[ $component ] = str_replace( '{{title}}', $this->data[ 'title' ][ 'title' ], $panel_def[ $component ] );
      }

      if ( $this->section[ '_panels_design_link-titles' ] ) {
        $panel_def[ $component ] = str_replace( '{{postlink}}', $panel_def[ 'postlink' ], $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{closepostlink}}', '</a>', $panel_def[ $component ] );
      }
      switch ( true ) {
        case ! empty( $this->section[ '_panels_design_use-scale-fonts-title' ] ) && ! empty( $this->section[ '_panels_design_use-responsive-font-size-title' ] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', ' is-responsive-scaled ', $panel_def[ $component ] );
          break;
        case ! empty( $this->section[ '_panels_design_use-responsive-font-size-title' ] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', ' is-responsive ', $panel_def[ $component ] );
          break;
      }

      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );

    }

    public
    function render_meta(
      $component, $content_type, $panel_def, $rsid, $layout_mode = false
    ) {
      $panel_def[ $component ] = str_replace( '{{id}}', $this->data[ 'meta' ][ 'id' ], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{datetime}}', $this->data[ 'meta' ][ 'datetime' ], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{fdatetime}}', $this->data[ 'meta' ][ 'fdatetime' ], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{sortable}}', ' data-order="' . str_replace( ',', ' ', strtotime( $this->data[ 'meta' ][ 'fdatetime' ] ) ) . '"', $panel_def[ $component ] );

      if ( empty( $this->section[ '_panels_design_excluded-authors' ] ) || ! in_array( get_the_author_meta( 'ID' ), $this->section[ '_panels_design_excluded-authors' ] ) ) {
        //Remove text indicators
        $panel_def[ $component ] = str_replace( '//', '', $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{authorname}}', $this->data[ 'meta' ][ 'authorname' ], $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{authorlink}}', $this->data[ 'meta' ][ 'authorlink' ], $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{authoremail}}', $this->data[ 'meta' ][ 'authoremail' ], $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{avatara}}', $this->data[ 'meta' ][ 'avatara' ], $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{avatarb}}', $this->data[ 'meta' ][ 'avatarb' ], $panel_def[ $component ] );
      } else {
        // Removed unused text and indicators
        $panel_def[ $component ] = preg_replace( "/\\/\\/(.)*\\/\\//uiUm", "", $panel_def[ $component ] );
      }
      $panel_def[ $component ] = str_replace( '{{categories}}', $this->data[ 'meta' ][ 'categories' ], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{categorieslinks}}', $this->data[ 'meta' ][ 'categorieslinks' ], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{tags}}', $this->data[ 'meta' ][ 'tags' ], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{tagslinks}}', $this->data[ 'meta' ][ 'tagslinks' ], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{commentslink}}', $panel_def[ 'comments-link' ], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{commentscount}}', $this->data[ 'meta' ][ 'comments-count' ], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{editlink}}', $panel_def[ 'editlink' ], $panel_def[ $component ] );
      if ( ! empty( $this->data[ 'meta' ][ 'custom' ] ) ) {
        foreach ( $this->data[ 'meta' ][ 'custom' ] as $meta ) {
          if ( ! empty( $meta ) ) {
            if ( is_array( $meta ) ) { // this may need to be a bit more intelligent
              foreach ( $meta as $meta_tax ) {
                foreach ( $meta_tax as $km => $vm ) {
                  if ( $vm ) {
                    $vm                      = ( is_array( $vm ) ? explode( ',', $vm ) : $vm );
                    $panel_def[ $component ] = str_replace( '{{ct:' . $km . '}}', $vm, $panel_def[ $component ] );
                  }
                }
              }
            }
          }
        }
      }

      return self::render_generics( $component, $content_type, do_shortcode( $panel_def[ $component ] ), $layout_mode );
    }

    public
    function render_content(
      $component, $content_type, $panel_def, $rsid, $layout_mode = false
    ) {
      $panel_def[ $component ] = str_replace( '{{content}}', $this->data[ 'content' ], $panel_def[ $component ] );
      if ( $this->section[ '_panels_design_feature-location' ] === 'content-left' || $this->section[ '_panels_design_feature-location' ] === 'content-right' && in_array( 'content', $this->section[ '_panels_design_feature-in' ] ) ) {
        if ( ! empty( $this->data[ 'image' ][ 'image' ] ) ) {
          $panel_def[ $component ] = str_replace( '{{image-in-content}}', $panel_def[ 'image' ], $panel_def[ $component ] );

          if ( $this->section[ '_panels_design_image-captions' ] ) {
            $panel_def[ $component ] = str_replace( '{{captioncode}}', '<span class="caption">' . $this->data[ 'image' ][ 'caption' ] . '</span>', $panel_def[ $component ] );
          }

          $panel_def[ $component ] = str_replace( '{{image}}', $this->data[ 'image' ][ 'image' ], $panel_def[ $component ] );
          $panel_def[ $component ] = str_replace( '{{incontent}}', 'in-content-thumb', $panel_def[ $component ] );

          if ( 'none' !== $this->section[ '_panels_design_link-image' ] ) {
            $link                    = self::get_link( $rsid, 'incontent', $panel_def[ 'postlink' ] );
            $panel_def[ $component ] = str_replace( '{{postlink}}', $link, $panel_def[ $component ] );
            $panel_def[ $component ] = str_replace( '{{closepostlink}}', '</a>', $panel_def[ $component ] );
          }


//          if ($this->section[ '_panels_design_link-image' ]) {
//            $panel_def[ $component ] = str_replace('{{postlink}}', $panel_def[ 'postlink' ], $panel_def[ $component ]);
//            $panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $panel_def[ $component ]);
//          }
        }
      }
      if ( empty( $this->data[ 'image' ][ 'image' ] ) ) {
        //TODO: Add an option to set if width spreads
        if ( ! empty( $this->section[ '_panels_design_maximize-content' ] ) ) {
          $panel_def[ $component ] = str_replace( '{{nothumb}}', 'nothumb maxwidth', $panel_def[ $component ] );
        } else {
          $panel_def[ $component ] = str_replace( '{{nothumb}}', 'nothumb', $panel_def[ $component ] );
        }
      }
      switch ( true ) {
        case ! empty( $this->section[ '_panels_design_use-scale-fonts' ] ) && ! empty( $this->section[ '_panels_design_use-responsive-font-size' ] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', ' is-responsive-scaled ', $panel_def[ $component ] );
          break;
        case ! empty( $this->section[ '_panels_design_use-responsive-font-size' ] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', ' is-responsive ', $panel_def[ $component ] );
          break;
      }

      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );
    }

    public
    function render_excerpt(
      $component, $content_type, $panel_def, $rsid, $layout_mode = false
    ) {
      $panel_def[ $component ] = str_replace( '{{excerpt}}', $this->data[ 'excerpt' ], $panel_def[ $component ] );

      if ( $this->section[ '_panels_design_feature-location' ] === 'content-left' || $this->section[ '_panels_design_feature-location' ] === 'content-right' && in_array( 'excerpt', $this->section[ '_panels_design_feature-in' ] ) ) {
        if ( ! empty( $this->data[ 'image' ][ 'image' ] ) ) {
          $panel_def[ $component ] = str_replace( '{{image-in-content}}', $panel_def[ 'image' ], $panel_def[ $component ] );

          if ( $this->section[ '_panels_design_image-captions' ] ) {
            $panel_def[ $component ] = str_replace( '{{captioncode}}', '<span class="caption">' . $this->data[ 'image' ][ 'caption' ] . '</span>', $panel_def[ $component ] );
          }

          $panel_def[ $component ] = str_replace( '{{image}}', $this->data[ 'image' ][ 'image' ], $panel_def[ $component ] );
          $panel_def[ $component ] = str_replace( '{{incontent}}', 'in-content-thumb', $panel_def[ $component ] );

          if ( 'none' !== $this->section[ '_panels_design_link-image' ] ) {
            $link                    = self::get_link( $rsid, 'inexcerpt', $panel_def[ 'postlink' ] );
            $panel_def[ $component ] = str_replace( '{{postlink}}', $link, $panel_def[ $component ] );
            $panel_def[ $component ] = str_replace( '{{closepostlink}}', '</a>', $panel_def[ $component ] );
          }
        }
      }
      if ( empty( $this->data[ 'image' ][ 'image' ] ) ) {
        //TODO: Add an option to set if width spreads
        if ( ! empty( $this->section[ '_panels_design_maximize-content' ] ) ) {
          $panel_def[ $component ] = str_replace( '{{nothumb}}', 'nothumb maxwidth', $panel_def[ $component ] );
        } else {
          $panel_def[ $component ] = str_replace( '{{nothumb}}', 'nothumb', $panel_def[ $component ] );
        }
      }

//_panels_design_thumb-position
      switch ( true ) {
        case ! empty( $this->section[ '_panels_design_use-scale-fonts' ] ) && ! empty( $this->section[ '_panels_design_use-responsive-font-size' ] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', ' is-responsive-scaled ', $panel_def[ $component ] );
          break;
        case ! empty( $this->section[ '_panels_design_use-responsive-font-size' ] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', ' is-responsive ', $panel_def[ $component ] );
          break;
      }


      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );
    }

    /**
     * render_image()
     *
     * @param      $component
     * @param      $content_type
     * @param      $panel_def
     * @param      $rsid
     * @param bool $layout_mode
     *
     * @return mixed
     */
    public
    function render_image(
      $component, $content_type, $panel_def, $rsid, $layout_mode = false
    ) {
      if ( 'video' === $this->section[ '_panels_settings_feature-type' ] ) {
        $panel_def[ $component ] = str_replace( '{{image}}', $this->data[ 'video' ][ 'source' ], $panel_def[ $component ] );

      } else {
        if ( 'none' !== $this->section[ '_panels_design_link-image' ] ) {
          $link                    = self::get_link( $rsid, 'inimage', $panel_def[ 'postlink' ] );
          $panel_def[ $component ] = str_replace( '{{postlink}}', $link, $panel_def[ $component ] );
          $panel_def[ $component ] = str_replace( '{{closepostlink}}', '</a>', $panel_def[ $component ] );
        }


        if ( $this->section[ '_panels_design_image-captions' ] ) {
          $caption                 = str_replace( '{{caption}}', $this->data[ 'image' ][ 'caption' ], $panel_def[ 'caption' ] );
          $panel_def[ $component ] = str_replace( '{{captioncode}}', $caption, $panel_def[ $component ] );
        }

        if ( $this->section[ '_panels_settings_image-focal-point' ] === 'scale_height' ) {
          $this->data[ 'image' ][ 'image' ] = preg_replace( "/width=\"(\\d)*\"\\s/uiUmx", "", $this->data[ 'image' ][ 'image' ] );
        }
        if ( $this->section[ '_panels_settings_image-focal-point' ] === 'scale' ) {
          $this->data[ 'image' ][ 'image' ] = preg_replace( "/height=\"(\\d)*\"\\s/uiUmx", "", $this->data[ 'image' ][ 'image' ] );
        }

        $panel_def[ $component ] = str_replace( '{{image}}', $this->data[ 'image' ][ 'image' ], $panel_def[ $component ] );

        if ( ! empty( $this->section[ '_panels_design_centre-image' ] ) ) {
          $panel_def[ $component ] = str_replace( '{{centred}}', 'centred', $panel_def[ $component ] );
        }
        if ( 'float' === $this->section[ '_panels_design_feature-location' ] ) {
          $panel_def[ $component ] = str_replace( '{{location}}', 'pzarc-components-' . $this->section[ '_panels_design_components-position' ], $panel_def[ $component ] );

        }
        if ( ! empty( $this->section[ '_panels_design_rotate-image' ] ) ) {
          $rot = rand( - 50, 50 ) / 10;
          // TODO: this is bad! Not dumb at all
          $panel_def[ $component ] = str_replace( '{{extrastyling}}', 'style="transform:rotate(' . $rot . 'deg);"', $panel_def[ $component ] );
        }

        switch ( true ) {
          case ( empty( $this->data[ 'image' ][ 'image' ] ) && 'table' === $layout_mode ) :
            $panel_def[ $component ] = '<td class="td-entry-thumbnail"></td>';
            break;
          case ( empty( $this->data[ 'image' ][ 'image' ] ) ) :
            $panel_def[ $component ] = '';
            break;
        }


      }
//      foreach ($this->data[ 'image' ] as $key => $value) {
//        $template[ $type ] = str_replace('{{' . $key . '}}', $value, $template[ $type ]);
//      }

      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );
    }


    public
    function render_bgimage(
      $component, $content_type, $panel_def, $rsid, $layout_mode = false
    ) {
      if ( 'video' === $this->section[ '_panels_settings_feature-type' ] ) {
        $panel_def[ $component ] = str_replace( '{{bgimage}}', $this->data[ 'video' ][ 'source' ], $panel_def[ $component ] );

      } else {

        if ( ! empty( $this->data[ 'bgimage' ][ 'thumb' ] ) ) {
          if ( $this->section[ '_panels_settings_image-focal-point' ] === 'scale_height' || $this->section[ '_panels_settings_image-focal-point' ] === 'shrink' ) {
            $this->data[ 'bgimage' ][ 'thumb' ] = preg_replace( "/width=\"(\\d)*\"\\s/uiUmx", "", $this->data[ 'bgimage' ][ 'thumb' ] );
          }
          if ( $this->section[ '_panels_settings_image-focal-point' ] === 'scale' || $this->section[ '_panels_settings_image-focal-point' ] === 'shrink' ) {
            $this->data[ 'bgimage' ][ 'thumb' ] = preg_replace( "/height=\"(\\d)*\"\\s/uiUmx", "", $this->data[ 'bgimage' ][ 'thumb' ] );
          }

          $panel_def[ $component ] = str_replace( '{{bgimage}}', $this->data[ 'bgimage' ][ 'thumb' ], $panel_def[ $component ] );
        } else {
          // Gotta fill the background with something, else it collapses
          $width  = $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ];
          $height = $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ];

          $fakethumb               = '<div class="pzarc-fakethumb" style="width:' . $width . ';height:' . $height . ';"></div>';
          $panel_def[ $component ] = str_replace( '{{bgimage}}', $fakethumb, $panel_def[ $component ] );

        }
        $panel_def[ $component ] = str_replace( '{{trim-scale}}', ' ' . $this->section[ '_panels_design_feature-location' ] . ' ' . $this->section[ '_panels_design_background-image-resize' ], $panel_def[ $component ] );

        if ( 'none' !== $this->section[ '_panels_design_link-image' ] ) {
          $link                    = self::get_link( $rsid, 'inbackg', $panel_def[ 'postlink' ] );
          $panel_def[ $component ] = str_replace( '{{postlink}}', $link, $panel_def[ $component ] );
          $panel_def[ $component ] = str_replace( '{{closepostlink}}', '</a>', $panel_def[ $component ] );
        }
      }


      // we shoudl filte rthis then we can do stuff to itwith add ons.

      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );
    }


    public function render_custom( $component, $content_type, $panel_def, $rsid, $layout_mode = false ) {
      // Show each custom field in this group
      if ( ! empty( $this->data[ 'cfield' ] ) ) {
        $panel_def_cfield = $panel_def[ 'cfield' ];
        $build_field      = '';
        $i                = 1;
        foreach ( $this->data[ 'cfield' ] as $k => $v ) {
          if ( $v[ 'group' ] === $component && ( ! empty( $v[ 'value' ] ) || $v[ 'name' ] === 'use_empty' ) ) {
            switch ( $v[ 'field-type' ] ) {

              case 'image':
                if ( function_exists( 'bfi_thumb' ) ) {

                  $content = '<img src="' . bfi_thumb( $v[ 'value' ] ) . '">';
                } else {
                  $content = '<img src="' . $v[ 'value' ] . '">';
                }
                break;

              case 'embed':
                $content = wp_oembed_get( $v[ 'value' ] );
                break;

              case 'date':
                if ( is_numeric( $v[ 'value' ] ) ) {
                  $content = date( $v[ 'date-format' ], $v[ 'value' ] );
                } else {
                  $content = $v[ 'value' ];
                }
                $content = '<time datetime="' . $content . '">' . $content . '</time>';
                break;

              case 'number':
                $content = @number_format( $v[ 'value' ], $v[ 'decimals' ], $v[ 'decimal-char' ], $v[ 'thousands-sep' ] );
                break;

              case 'text-with-paras':
                $content = wpautop( $v[ 'value' ] );
                break;

              case 'text':
              default:
                $content = $v[ 'value' ];
                break;

            }

            $prefix_image = '';
            $suffix_image = '';
            if ( ! empty( $v[ 'prefix-image' ] ) ) {
              $prefix_image = '<img src="' . $v[ 'prefix-image' ] . '" class="pzarc-presuff-image prefix-image">';
            }
            if ( ! empty( $v[ 'suffix-image' ] ) ) {
              $suffix_image = '<img src="' . $v[ 'suffix-image' ] . '" class="pzarc-presuff-image suffix-image">';
            }


            $content = $prefix_image . $v[ 'prefix-text' ] . $content . $v[ 'suffix-text' ] . $suffix_image;

            if ( ! empty( $v[ 'link-field' ] ) ) {
              $content = '<a href="' . $v[ 'link-field' ] . '" target="' . $v[ 'link-behaviour' ] . '">' . $content . '</a>';
            }

            if ( $v[ 'name' ] === 'use_empty' && empty( $v[ 'link-field' ] ) ) {
              $content = '';
            }
//            if ('none' !== $v[ 'wrapper-tag' ]) {
//              $class_name = !empty($v[ 'class-name' ]) ? ' class="' . $v[ 'class-name' ] . '"' : null;
//              $content    = '<' . $v[ 'wrapper-tag' ] . $class_name . '>' . $content . '</' . $v[ 'wrapper-tag' ] . '>';
//            }

            // TODO: Should apply filters here?
            $panel_def_cfield = str_replace( '{{cfieldwrapper}}', $v[ 'wrapper-tag' ], $panel_def_cfield );
            $panel_def_cfield = str_replace( '{{cfieldcontent}}', $content, $panel_def_cfield );
            $panel_def_cfield = str_replace( '{{cfieldname}}', $v[ 'name' ], $panel_def_cfield );
            $panel_def_cfield = str_replace( '{{cfieldnumber}}', $k, $panel_def_cfield );
            if ( ! empty( $v[ 'data' ] ) ) {
              $panel_def_cfield = str_replace( '{{cfielddata}}', $v[ 'data' ], $panel_def_cfield );
            }

            $build_field .= $panel_def_cfield;
          }
          $panel_def_cfield = $panel_def[ 'cfield' ];
        }
        $panel_def[ $component ] = str_replace( '{{' . $component . 'innards}}', $build_field, $panel_def[ $component ] );
      } else {
        $panel_def[ $component ] = '';
      }

      return self::render_generics( $component, $content_type, do_shortcode( $panel_def[ $component ] ), $layout_mode );

    }

    public
    function render_wrapper(
      $component, $content_type, $panel_def, $rsid, $layout_mode = false
    ) {
      $panel_def[ $component ] = str_replace( '{{mimic-block-type}}', $this->data[ 'inherit-hw-block-type' ], $panel_def[ $component ] );

      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );
    }

    public
    function render_generics(
      $component, $source, $line, $layout_mode
    ) {

      // Devs can plugin here. Filter must return $line value
      $line = apply_filters( 'arc_render_components', $line, $component, $source, $layout_mode );


      //todo: make sure source is actual WP valid eg. soemthings might be attachment
      // Do any generic replacements
      $line = str_replace( '{{postid}}', $this->data[ 'postid' ], $line );
      $line = str_replace( '{{title}}', $this->data[ 'title' ][ 'title' ], $line );
      $line = str_replace( '{{permalink}}', $this->data[ 'permalink' ], $line );
      $line = str_replace( '{{closelink}}', '</a>', $line );
      $line = str_replace( '{{categories}}', $this->data[ 'meta' ][ 'categories' ], $line );
      $line = str_replace( '{{tags}}', $this->data[ 'meta' ][ 'tags' ], $line );
      $line = str_replace( '{{poststatus}}', $this->data[ 'poststatus' ], $line );
      $line = str_replace( '{{postformat}}', $this->data[ 'postformat' ], $line );
      $line = str_replace( '{{posttype}}', $source, $line );

      $pzclasses = 'pzarc-components ';
      $pzclasses .= ( $this->section[ '_panels_design_components-position' ] === 'left' || $this->section[ '_panels_design_components-position' ] === 'right' ) ? 'vertical-content pzarc-align-' . $this->section[ '_panels_design_components-position' ] : '';

      $line = str_replace( '{{pzclasses}}', $pzclasses, $line );

      if ( 'table' === $layout_mode ) {
        $line = str_replace( '{{div}}', 'td', $line );
        $line = str_replace( '{{h1open}}', '<td class="td-entry-title"><h1 ', $line );
        $line = str_replace( '{{h1close}}', '</h1></td>', $line );
        $line = str_replace( '{{figopen}}', '<td class="td-entry-thumbnail"><figure ', $line );
        $line = str_replace( '{{figclose}}', '</figure></td>', $line );
      } else {
        $line      = str_replace( '{{div}}', 'div', $line );
        $title_tag = ! empty( $this->section[ '_panels_design_title-wrapper-tag' ] ) ? $this->section[ '_panels_design_title-wrapper-tag' ] : 'h1';
        $line      = str_replace( '{{h1open}}', '<' . $title_tag . ' ', $line );
        $line      = str_replace( '{{h1close}}', '</' . $title_tag . '>', $line );
        $line      = str_replace( '{{figopen}}', '<figure ', $line );
        $line      = str_replace( '{{figclose}}', '</figure>', $line );

      }

      return $line;
    }


    /**
     * Default Loop
     */
    public function loop( $section_no, &$architect, &$panel_class, $class ) {
      $this->build            = $architect->build;
      $this->arc_query        = $architect->arc_query;
      $section[ $section_no ] = $this->build->blueprint[ 'section_object' ][ $section_no ];

      $panel_def = $panel_class->panel_def();

      // Setup meta tags
      $panel_def = self::build_meta_header_footer_groups( $panel_def, $section[ $section_no ]->section[ 'section-panel-settings' ] );

      $i = 1;

      $section[ $section_no ]->open_section();
      pzdb( 'pre_generic_loop' );

      // For custom conetnet such as NGG or RSS, this will look quite different!
      $loopmax   = ( defined( 'PZARC_PRO' ) ? 999999999 : 15 );
      $loopcount = 0;

      // Weird nudge needed when Arc is called inside a main loop with defaults.
      if ( $this->build->blueprint[ '_blueprints_content-source' ] === 'defaults' ) {
        $this->arc_query->have_posts();
      }

      while ( $this->arc_query->have_posts() && $loopcount ++ < $loopmax ) {
        //  var_dump("You is here");
        $this->arc_query->the_post();
        pzdb( 'top_of_loop Post:' . get_the_id() );
        $section[ $section_no ]->render_panel( $panel_def, $i, $class, $panel_class, $this->arc_query->post, get_the_id() );

        if ( $i ++ >= $this->build->blueprint[ '_blueprints_section-' . ( $section_no - 1 ) . '-panels-per-view' ] && ! empty( $this->build->blueprint[ '_blueprints_section-' . ( $section_no - 1 ) . '-panels-limited' ] ) ) {
          if ( $i !== count( $this->arc_query->posts ) ) {
            break;
          } else {
            // it will break anyways!
            // Without this check, we get weird errors in page builder if limited content and show 1 and default content type.
          }
          break;
        }
        pzdb( 'bottom_of_loop Post:' . get_the_id() );

      }
      pzdb( 'post_generic_loop' );
      $section[ $section_no ]->close_section();

      // Unsetting causes it to run the destruct, which closes the div. :D
      unset( $section[ $section_no ] );

    }

    /**
     * get_nav_items
     */
    public
    function get_nav_items(
      $blueprints_navigator, &$arc_query, $nav_labels, $nav_title_len = 0
    ) {
      // We shouldn't have to pass arc_query! And we don't need to in this one, but for some unsolved reason in arc_Panel_Dummy, we do. So for consistency, doing it here too.
      $nav_items = array();
      $i         = 0;
      foreach ( $arc_query->posts as $the_post ) {

        switch ( $blueprints_navigator ) {

          case 'tabbed':
            if ( class_exists( 'HeadwayLayoutOption' ) && ( true == ( $alt_title = HeadwayLayoutOption::get( $the_post->ID, 'alternate-title', false, true ) ) ) ) {
              $post_title = $alt_title;
            } else {
              $post_title = $the_post->post_title;
            }
            if ( ! empty( $nav_title_len ) && strlen( $post_title ) > $nav_title_len ) {
              $post_title = trim( substr( $post_title, 0, ( $nav_title_len - 1 ) ) ) . '&hellip;';
            }


            $nav_items[] = '<span class="' . $blueprints_navigator . '">' . $post_title . '</span>';
            break;

          case 'labels':
            global $pzarc_post_id;
            $pzarc_post_id = $the_post->ID;
            if ( isset( $nav_labels[ $i ] ) ) {
              $label = do_shortcode( $nav_labels[ $i ] );
            } else {
              $label = 1 + $i;
            }
            $nav_items[] = '<span class="' . $blueprints_navigator . '">' . $label . '</span>';
            $i ++;
            break;

          case 'thumbs':

            $thumb_id    = get_post_thumbnail_id( $the_post->ID );
            $focal_point = get_post_meta( $thumb_id );

            // Usually the post will be the attachment, so won't have a thumb id!
            if ( empty( $focal_point ) ) {
              $focal_point = get_post_meta( $the_post->ID, 'pzgp_focal_point', true );
            }

            $focal_point = ( empty( $focal_point ) || ! is_string( $focal_point ) ? array(
              50,
              50
            ) : explode( ',', $focal_point ) );
            if ( ! $thumb_id && ! empty( $this->build->blueprint[ 'section_object' ][ 1 ]->section[ 'section-panel-settings' ][ '_panels_settings_use-embedded-images' ] ) ) {
              //TODO: Changed to more reliable check if image is in the content?
              preg_match( "/(?<=wp-image-)(\\d)*/uimx", get_the_content(), $matches );
              $thumb_id = ( ! empty( $matches[ 0 ] ) ? $matches[ 0 ] : false );
            }

            //  $focal_point = array( 50, 50 );


            if ( 'attachment' === $the_post->post_type ) {

              $thumb = wp_get_attachment_image( $the_post->ID, array(
                                                               self::get_thumbsize( 'w' ),
                                                               self::get_thumbsize( 'h' ),
                                                               'bfi_thumb' => true,
                                                               'crop'      => (int) $focal_point[ 0 ] . 'x' . (int) $focal_point[ 1 ]
                                                             )
              );

            } else {

              $thumb = get_the_post_thumbnail( $the_post->ID, array(
                self::get_thumbsize( 'w' ),
                self::get_thumbsize( 'h' ),
                'bfi_thumb' => true,
                'crop'      => (int) $focal_point[ 0 ] . 'x' . (int) $focal_point[ 1 ]
              ) );

            }
            if ( empty( $thumb )
                 && 'specific' === $this->build->blueprint[ 'section_object' ][ 1 ]->section[ 'section-panel-settings' ][ '_panels_design_use-filler-image-source' ]
                 && ! empty( $this->build->blueprint[ 'section_object' ][ 1 ]->section[ 'section-panel-settings' ][ '_panels_design_use-filler-image-source-specific' ][ 'url' ] )
            ) {
              if ( function_exists( 'bfi_thumb' ) ) {
                $imageURL = bfi_thumb( $this->build->blueprint[ 'section_object' ][ 1 ]->section[ 'section-panel-settings' ][ '_panels_design_use-filler-image-source-specific' ][ 'url' ], array(
                  'width'  => self::get_thumbsize( 'w' ),
                  'height' => self::get_thumbsize( 'h' )
                ) );
              } else {
                $imageURL = $this->build->blueprint[ 'section_object' ][ 1 ]->section[ 'section-panel-settings' ][ '_panels_design_use-filler-image-source-specific' ][ 'url' ];
              }
              $thumb = '<img src="' . $imageURL . '" width="' . self::get_thumbsize( 'w' ) . '" height="' . self::get_thumbsize( 'h' ) . '">';
            } elseif ( empty( $thumb ) ) {
              $thumb = '<img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/missing-image.png" width="' . self::get_thumbsize( 'w' ) . '" height="' . self::get_thumbsize( 'h' ) . '" style="width:' . self::get_thumbsize( 'w' ) . 'px">';

            }

            // Added this class so can filter it out of Advanced Lazy Load
            $thumb       = preg_replace( "/class=\\\"a/uUm", "$0rc-nav-thumb a", $thumb );
            $nav_items[] = '<span class="' . $blueprints_navigator . '" title="' . $the_post->post_title . '">' . $thumb . '</span>';
            break;

          case 'bullets':
          case 'numbers':
          case 'buttons':
            //No need for content on these
            $nav_items[] = '';
            break;

        }

      }

      return apply_filters( 'arc_nav_items', $nav_items );
    }


    /**
     * @param $dim
     *
     * @return int|mixed
     */
    protected
    function get_thumbsize(
      $dim
    ) {

      // $dim for later development with rectangular thumbs
      $thumbsize = 60;
      if ( ! empty( $this->build->blueprint[ '_blueprints_navigator-thumb-dimensions' ][ 'width' ] ) && $dim === 'w' ) {
        $thumbsize = str_replace( array( 'px' ), '', $this->build->blueprint[ '_blueprints_navigator-thumb-dimensions' ][ 'width' ] );

      } elseif ( ! empty( $this->build->blueprint[ '_blueprints_navigator-thumb-dimensions' ][ 'height' ] ) && $dim === 'h' ) {
        $thumbsize = str_replace( array( 'px' ), '', $this->build->blueprint[ '_blueprints_navigator-thumb-dimensions' ][ 'height' ] );
      }

      return $thumbsize;
    }

    /*************************************************
     *
     * Method: build_panel_definition
     *
     * Purpose: build meta defs
     *
     * @param $panel_def
     * @param $section_panel_settings
     *
     * @return mixed
     *
     * Returns:
     *
     *************************************************/
    public
    function build_meta_header_footer_groups(
      $panel_def, $section_panel_settings
    ) {
      //replace meta1innards etc
      $meta = array_pad( array(), 3, null );
      foreach ( $meta as $key => $value ) {
        $i = $key + 1;
//        $meta[ $key ]             = preg_replace('/%(\\w*)%/u', '{{$1}}', (!empty($section_panel_settings[ '_panels_design_meta' . $i . '-config' ]) ? $section_panel_settings[ '_panels_design_meta' . $i . '-config' ] : null));
        $first_pass               = preg_replace( '/%(\\w|[\\:\\-])*%/uiUmx', '{{$0}}', ( ! empty( $section_panel_settings[ '_panels_design_meta' . $i . '-config' ] ) ? strip_tags( $section_panel_settings[ '_panels_design_meta' . $i . '-config' ], '<p><span><br><strong><em><a>' ) : null ) );
        $meta[ $key ]             = preg_replace( "/%(.*)%/uiUmx", "$1", $first_pass );
        $panel_def[ 'meta' . $i ] = str_replace( '{{meta' . $i . 'innards}}', $meta[ $key ], $panel_def[ 'meta' . $i ] );
        $panel_def[ 'meta' . $i ] = str_replace( '{{date}}', $panel_def[ 'datetime' ], $panel_def[ 'meta' . $i ] );
        $panel_def[ 'meta' . $i ] = str_replace( '{{author}}', $panel_def[ 'author' ], $panel_def[ 'meta' . $i ] );
        $panel_def[ 'meta' . $i ] = str_replace( '{{email}}', $panel_def[ 'email' ], $panel_def[ 'meta' . $i ] );
        $panel_def[ 'meta' . $i ] = str_replace( '{{categories}}', $panel_def[ 'categories' ], $panel_def[ 'meta' . $i ] );
        $panel_def[ 'meta' . $i ] = str_replace( '{{tags}}', $panel_def[ 'tags' ], $panel_def[ 'meta' . $i ] );
// TODO: This maybe meant to be editlink
//        $panel_def[ 'meta' . $i ] = str_replace('{{edit}}', $panel_def[ 'edit' ], $panel_def[ 'meta' . $i ]);
      }


      $panel_layout = json_decode( $section_panel_settings[ '_panels_design_preview' ], true );

      $header_state  = false;
      $footer_state  = false;
      $seen_all_body = 0;
      $last_hf_key   = '';

      $max_body = (int) $panel_layout[ 'excerpt' ][ 'show' ]
                  + (int) $panel_layout[ 'content' ][ 'show' ]
                  + (int) $panel_layout[ 'custom1' ][ 'show' ]
                  + (int) $panel_layout[ 'custom2' ][ 'show' ]
                  + (int) $panel_layout[ 'custom3' ][ 'show' ]
                  + ( (int) $panel_layout[ 'image' ][ 'show' ] * (int) ( $section_panel_settings[ '_panels_design_feature-location' ] === 'components' ) );

      $header_open  = empty( $section_panel_settings[ '_panels_design_components-headers-footers' ] ) ? '' : '<header class="entry-header">';
      $footer_open  = empty( $section_panel_settings[ '_panels_design_components-headers-footers' ] ) ? '' : '<footer class="entry-header">';
      $header_close = empty( $section_panel_settings[ '_panels_design_components-headers-footers' ] ) ? '' : '</header>';
      $footer_close = empty( $section_panel_settings[ '_panels_design_components-headers-footers' ] ) ? '' : '</footer>';

      foreach ( (array) $panel_layout as $key => $value ) {
        if ( $value[ 'show' ] ) {
          if ( ( $key === 'title' || $key === 'meta1' || $key == 'meta2' || $key === 'meta3' ) ) {
            $last_hf_key = $key;
          }
          switch ( true ) {

            case ( $key === 'title' || $key === 'meta1' || $key == 'meta2' || $key === 'meta3' ) && ! $header_state && ! $footer_state:
              $header_state      = 'open';
              $panel_def[ $key ] = $header_open . $panel_def[ $key ];
              break;

            case ( $key === 'meta1' || $key === 'meta2' || $key === 'meta3' ) && $seen_all_body === $max_body && ! $footer_state && $header_state === 'closed':
              $panel_def[ $key ] = $footer_open . $panel_def[ $key ];
              $footer_state      = 'open';
              break;

            case $key === 'content':
            case $key === 'excerpt':
            case $key === 'custom1':
            case $key === 'custom2':
            case $key === 'custom3':
            case $key === 'image' && ( $section_panel_settings[ '_panels_design_feature-location' ] === 'components' ):
              // TODO: We need to work out a method of wrapping content in a div that copes with some content eg images not always being there.
              if ( $header_state === 'open' ) {
                $header_state = 'closed';
                $panel_def[ $last_hf_key ] .= $header_close;
              }
              $seen_all_body ++; // Once we've seen all the body, any meta will be for footer
              break;
          }
        }


      }
      if ( $header_state === 'open' ) {
        $panel_def[ $last_hf_key ] = $panel_def[ $last_hf_key ] . $header_close;
      }
      if ( $footer_state === 'open' ) {
        $panel_def[ $last_hf_key ] = $panel_def[ $last_hf_key ] . $footer_close;
      }

      return $panel_def;
    }

    private function get_link( $rsid, $location, $postlink ) {
      $link = '';
      switch ( $this->section[ '_panels_design_link-image' ] ) {
        case 'page':
        case 'url':
          $link = ( 'url' === $this->section[ '_panels_design_link-image' ] ) ? '<a href="' . $this->section[ '_panels_design_link-image-url' ] . '" title="' . $this->section[ '_panels_design_link-image-url-tooltip' ] . '">' : $postlink;
          break;
        case 'destination-url':
          $destination_url    = get_post_meta( $this->data[ 'image' ][ 'id' ], '_gallery_link_url', true );
          $destination_target = get_post_meta( $this->data[ 'image' ][ 'id' ], '_gallery_link_target', true );
          $link               = ! empty( $destination_url ) ? '<a href="' . $destination_url . '" title="' . $this->section[ '_panels_design_link-image-url-tooltip' ] . '" ' . ( ! empty( $destination_target ) ? 'target=' . $destination_target : '' ) . '>' : '';
          break;
        case 'original':
          if ( empty( $this->section[ '_panels_design_alternate-lightbox' ] ) ) {
            wp_enqueue_script( 'js-magnific' );
            wp_enqueue_script( 'js-magnific-arc' );
            wp_enqueue_style( 'css-magnific' );

            $link = '<a class="lightbox lightbox-' . $rsid . ' ' . $location . '" href="' . $this->data[ 'image' ][ 'original' ][ 0 ] . '" title="' . $this->data[ 'title' ][ 'title' ] . '" >';
          } else {
            $link = '<a class="lightbox-' . $rsid . ' ' . $location . '" href="' . $this->data[ 'image' ][ 'original' ][ 0 ] . '" title="' . $this->data[ 'title' ][ 'title' ] . '" rel="lightbox">';
          }
          break;
      }

      return $link;
    }

    function get_content_meta( $post ) {
      return pzarc_flatten_wpinfo( get_post_meta( $post->ID ) );
    }

    function set_accordion_title( $post ) {
      return $post->post_title;
    }

  }