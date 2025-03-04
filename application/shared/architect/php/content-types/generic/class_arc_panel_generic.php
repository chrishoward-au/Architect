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
    public $_architect;
    public $cfields;

    public function __construct( &$build ) {

      // Load field classes
      require_once PZARC_PLUGIN_APP_PATH . '/shared/architect/php/field_types/arc_cft_base.php';
      $field_types = ArcFun::field_types( TRUE );
      foreach ( $field_types as $ftk => $ftpath ) {
        require_once $ftpath . '/arc_cft_' . $ftk . '.php';
      }

      // If you create you own construct, remember to include these two lines!
      $this->build = $build;
      pzdb( 'arc_panel_generic before initialise' );
      self::initialise_data();
      pzdb( 'arc_panel_generic after initialise' );
      global $_architect;
      if ( empty( $_architect ) ) {
        $this->_architect = get_option( '_architect' );
      } else {
        $this->_architect = $_architect;
      }

      if ( ! empty( $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_settings_disable-image-saving'] ) ) {
        wp_enqueue_script( 'js-disableimagesaving' );
      }
    }

    public function initialise_data() {

      unset( $this->data );
      // Null up everything to prevent warnings later on
      $this->data['title']                   = NULL;
      $this->data['title']['title']          = NULL;
      $this->data['title']['thumb']          = NULL;
      $this->data['content']                 = NULL;
      $this->data['excerpt']                 = NULL;
      $this->data['meta']['categories']      = NULL;
      $this->data['meta']['tags']            = NULL;
      $this->data['image']['image']          = NULL;
      $this->data['image']['caption']        = NULL;
      $this->data['image']['srcset']         = NULL;
      $this->data['image']['original']       = NULL;
      $this->data['image']['id']             = NULL;
      $this->data['bgimage']['thumb']        = NULL;
      $this->data['video']['source']         = NULL;
      $this->data['meta']['id']              = NULL;
      $this->data['meta']['datetime']        = NULL;
      $this->data['meta']['fdatetime']       = NULL;
      $this->data['meta']['categorieslinks'] = NULL;
      $this->data['meta']['tagslinks']       = NULL;
      $this->data['meta']['authorlink']      = NULL;
      $this->data['meta']['avatara']         = NULL;
      $this->data['meta']['avatarb']         = NULL;
      $this->data['meta']['authorname']      = NULL;
      $this->data['meta']['authoremail']     = NULL;
      $this->data['meta']['comments-count']  = NULL;
      $this->data['cfield']                  = NULL;
      $this->data['postid']                  = NULL;
      $this->data['poststatus']              = NULL;
      $this->data['permalink']               = NULL;
      $this->data['postformat']              = NULL;

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
      $panel_def['components-open']  = '<article id="post-{{postid}}" class="{{extensionclass}} {{mimic-block-type}} post-{{postid}} {{posttype}} type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{categories}} {{tags}} {{pzclasses}} {{disable-save}}" {{extensiondata}}>';
      $panel_def['components-close'] = '</article>';
      $panel_def['postlink']         = '<a href="{{permalink}}" title="{{title}}">';
      //     $panel_def[ 'header' ]           = '<header class="entry-header">{{headerinnards}}</header>';
      $panel_def['title'] = '{{h1open}} class="{{extensionclass}}" {{extensiondata}}>{{postlink}}{{title}}{{closepostlink}}{{h1close}}';
      $panel_def['meta1'] = '<{{div}} class="{{extensionclass}} entry-meta1" {{sortable}} {{extensiondata}}>{{meta1innards}}</{{div}}>';
      $panel_def['meta2'] = '<{{div}} class="{{extensionclass}} entry-meta2" {{sortable}} {{extensiondata}}>{{meta2innards}}</{{div}}>';
      $panel_def['meta3'] = '<{{div}} class="{{extensionclass}} entry-meta3" {{sortable}} {{extensiondata}}>{{meta3innards}}</{{div}}>';
      // TODO Make this only used in tables
      $panel_def['datetime']   = '<span class="entry-date"><a href="{{permalink}}" ><time class="entry-date" datetime="{{datetime}}">{{fdatetime}}</time></a></span>';
      $panel_def['categories'] = '<span class="categories-links">{{categorieslinks}}</span>';
      $panel_def['tags']       = '<span class="tags-links">{{tagslinks}}</span>';
      $panel_def['author']     = '<span class="byline"><span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{avatarb}}{{authorname}}{{avatara}}</a></span></span>';
      $panel_def['email']      = '<span class="byline email"><span class="author vcard"><a class="url fn n" href="mailto:{{authoremail}}" title="Email {{authorname}}" rel="author">{{authoremail}}</a></span></span>';
      //     $panel_def[ 'image' ]       = '<figure class="entry-thumbnail {{incontent}}">{{postlink}}<img width="{{width}}" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}">{{closepostlink}}{{captioncode}}</figure>';
      $panel_def['image']   = '{{figopen}} class="{{extensionclass}} {{incontent}} {{centred}} {{nofloat}} {{location}} {{disable-save}}" {{extensiondata}} {{extrastyling}}>{{postlink}}{{image}}{{closelink}}{{captioncode}}{{figclose}}';
      $panel_def['bgimage'] = '<figure class="{{extensionclass}} entry-bgimage pzarc-bg-image {{trim-scale}} {{disable-save}}" {{extensiondata}}>{{postlink}}{{bgimage}}{{closelink}}</figure>';
      $panel_def['caption'] = '<figcaption class="caption">{{caption}}</figcaption>';
      $panel_def['content'] = '<{{div}} class="{{extensionclass}} {{nothumb}}" {{extensiondata}}>{{image-in-content}}{{content}}</{{div}}>';
      $panel_def['custom1'] = '<{{div}} class="{{extensionclass}} entry-customfieldgroup entry-customfieldgroup-1" {{extensiondata}}>{{custom1innards}}</{{div}}>';
      $panel_def['custom2'] = '<{{div}} class="{{extensionclass}} entry-customfieldgroup entry-customfieldgroup-2" {{extensiondata}}>{{custom2innards}}</{{div}}>';
      $panel_def['custom3'] = '<{{div}} class="{{extensionclass}} entry-customfieldgroup entry-customfieldgroup-3" {{extensiondata}}>{{custom3innards}}</{{div}}>';
      $panel_def['cfield']  = '<{{cfieldwrapper}} class="entry-customfield entry-customfield-{{cfieldname}} {{cfieldname}} entry-customfield-{{cfieldnumber}}" {{cfielddata}}>{{cfieldcontent}}</{{cfieldwrapper}}>';
//      $panel_def[ 'footer' ]        = '<footer class="entry-footer">{{footerinnards}}</footer>';
      $panel_def['excerpt']       = '<{{div}} class="{{extensionclass}} {{nothumb}}" {{extensiondata}}>{{image-in-content}}{{excerpt}}</{{div}}>';
      $panel_def['feature']       = '{{feature}}';
      $panel_def['editlink']      = '<span class="edit-link"><a class="post-edit-link" href="{{permalink}}" title="Edit post {{title}}">Edit</a></span>';
      $panel_def['comments-link'] = '<span class="comments-link"><a href="{{permalink}}/#comments" title="Comment on {{title}}">Comments: {{commentscount}}</a></span>';
      $panel_def['customtax']     = '<span class="{{customtax}}-links">{{customtaxlinks}}</span>';


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
      $this->initialise_data(); // v1.10.8: Ooops! Why hasn't the absence of this raised its ugly head previously!
      $this->section      = $section;
      $this->toshow       = $toshow;
      $this->panel_number = $panel_number;


//      if ( $this->toshow[ 'title' ][ 'show' ] ) {
      // We always need the title  for images
      $this->get_title( $post );
      pzdb( 'after get title' );
//      }
      if ( $this->toshow['meta1']['show'] || $this->toshow['meta2']['show'] || $this->toshow['meta3']['show'] ) {
        $this->get_meta( $post );
        pzdb( 'after get meta' );
      }

      if ( $this->toshow['content']['show'] ) {
        $this->get_content( $post );
        pzdb( 'after get content' );
      }

      if ( $this->toshow['excerpt']['show'] ) {
        $this->get_excerpt( $post );
        pzdb( 'after get excerpt' );
      }

      if ( $this->toshow['image']['show'] ) {
        switch ( $this->section['_panels_design_feature-location'] ) {
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

      if ( $this->toshow['custom1']['show'] || $this->toshow['custom2']['show'] || $this->toshow['custom3']['show'] ) {
        $this->get_custom( $post );
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
      if ( ArcFun::is_bb_active() && $post->post_type === 'fl-theme-layout' ) {
        $this->data['title']['title'] = "Beaver Builder editor - no preview";
      } else {


        $is_hw       = class_exists( 'HeadwayLayoutOption' );
        $is_blox     = class_exists( 'BloxLayoutOption' );
        $is_padma    = class_exists( 'PadmaLayoutOption' );

        $alt_title   = '';
        $hw_title    = $is_hw ? ( TRUE == ( $alt_title = HeadwayLayoutOption::get( $post->ID, 'alternate-title', FALSE, TRUE ) ) ) : FALSE;
        $blox_title  = $is_blox ? ( TRUE == ( $alt_title = BloxLayoutOption::get( $post->ID, 'alternate-title', FALSE, TRUE ) ) ) : FALSE;
        $padma_title = $is_padma ? ( TRUE == ( $alt_title = PadmaLayoutOption::get( $post->ID, 'alternate-title', FALSE, TRUE ) ) ) : FALSE;

        if ( ( $is_hw || $is_blox || $is_padma) && ! empty( $this->section['_panels_design_alternate-titles'] ) && ( $hw_title || $blox_title || $padma_title) ) {
          $this->data['title']['title'] = $alt_title;
        } else {
          $this->data['title']['title'] = get_the_title();
        }

        if ( ! empty( $this->section['_panels_design_title-prefix'] ) && 'thumb' === $this->section['_panels_design_title-prefix'] ) {
          $thumb_id    = get_post_thumbnail_id();
          $focal_point = get_post_meta( $thumb_id, 'pzgp_focal_point', TRUE );
          if ( empty( $focal_point ) ) {
            $focal_point = get_post_meta( get_the_ID(), 'pzgp_focal_point', TRUE );
          }
          $focal_point = ( empty( $focal_point ) ? explode( ',', pzarc_get_option( 'architect_focal_point_default', '50,10' ) ) : explode( ',', $focal_point ) );
          if ( ! empty( $thumb_id ) ) {
            $thumb_prefix                 = wp_get_attachment_image( $thumb_id, array(
                $this->section['_panels_design_title-thumb-width'],
                $this->section['_panels_design_title-thumb-width'],
                'bfi_thumb' => TRUE,
                'crop'      => (int) $focal_point[0] . 'x' . (int) $focal_point[1],
                'quality'   => ( ! empty( $this->section['_panels_design_image-quality'] ) ? $this->section['_panels_design_image-quality'] : 82 ),
            ) );
            $this->data['title']['thumb'] = '<span class="pzarc-title-thumb">' . $thumb_prefix . '</span> ';
          } else {
            $this->data['title']['thumb'] = '<span class="pzarc-title-thumb" style="width:' . $this->section['_panels_design_title-thumb-width'] . 'px;height:' . $this->section['_panels_design_title-thumb-width'] . 'px;"></span> ';
          }
        }
      }

    }

    /**
     * @param $post
     */
    public function get_meta( &$post ) {
      $meta_string = $this->toshow['meta1']['show'] ? $this->section['_panels_design_meta1-config'] : '';
      $meta_string .= $this->toshow['meta2']['show'] ? $this->section['_panels_design_meta2-config'] : '';
      $meta_string .= $this->toshow['meta3']['show'] ? $this->section['_panels_design_meta3-config'] : '';

      /** META */
      if ( strpos( $meta_string, '%id%' ) !== FALSE ) {
        $this->data['meta']['id'] = get_the_ID();
        $this->data['meta']['id'] .= ' blueprint=' . $this->build->blueprint['blueprint-id'];
      }
      if ( strpos( $meta_string, '%date%' ) !== FALSE ) {
        $this->data['meta']['datetime'] = get_the_date();
//        $this->data[ 'meta' ][ 'fdatetime' ] = date_i18n(strip_tags($this->section[ '_panels_design_meta-date-format' ]), str_replace(',', ' ', strtotime(get_the_date())));
//        $this->data['meta']['fdatetime'] = date_i18n( strip_tags( $this->section['_panels_design_meta-date-format'] ), strtotime( str_replace( ',', ' ', get_the_date() ) ) );
        $this->data['meta']['fdatetime'] = wp_date( strip_tags( $this->section['_panels_design_meta-date-format'] ), get_post_timestamp() ); //v11.3
      }
      if ( strpos( $meta_string, '%categories%' ) !== FALSE ) {
        $this->data['meta']['categorieslinks'] = get_the_category_list( ', ' );
        if ( ! empty( $this->section['_panels_design_hide-cats'] ) ) {
          $this->data['meta']['categorieslinks'] = pzarc_hide_categories( $this->data['meta']['categorieslinks'], $this->section['_panels_design_hide-cats'] );
        }
        $this->data['meta']['categories'] = pzarc_tax_string_list( get_the_category(), 'category-', '', ' ' );
      }
      if ( strpos( $meta_string, '%tags%' ) !== FALSE ) {
        $this->data['meta']['tagslinks'] = get_the_tag_list( NULL, ', ' );
        $this->data['meta']['tags']      = pzarc_tax_string_list( get_the_tags(), 'tag-', '', ' ' );
      }
      if ( strpos( $meta_string, '%author%' ) !== FALSE ) {
        $use_generic = FALSE;
        if ( ! empty( $this->section['_panels_design_authors-generic-emails'] ) ) {
          $user = new WP_User( get_the_author_meta( 'ID' ) );
          if ( ! empty( $user->roles ) && is_array( $user->roles ) ) {
            foreach ( $user->roles as $role ) {
              $use_generic = in_array( $role, $this->section['_panels_design_authors-generic-emails'] );
            }
          }
        }
        $generic_email                    = empty( $this->section['_panels_design_authors-generic-email-address'] ) ? '' : $this->section['_panels_design_authors-generic-email-address'];
        $this->data['meta']['authorlink'] = get_author_posts_url( get_the_author_meta( 'ID' ) );
        $this->data['meta']['authorname'] = sanitize_text_field( get_the_author_meta( 'display_name' ) );
        $rawemail                         = sanitize_email( $use_generic ? $generic_email : get_the_author_meta( 'user_email' ) );
        $encodedmail                      = '';
        for ( $i = 0; $i < strlen( $rawemail ); $i ++ ) {
          $encodedmail .= "&#" . ord( $rawemail[ $i ] ) . ';';
        }
        $this->data['meta']['authoremail'] = $encodedmail;
      }
      if ( ! empty( $this->section['_panels_design_avatar'] ) && $this->section['_panels_design_avatar'] !== 'none' ) {
        if ( $this->section['_panels_design_avatar'] === 'before' ) {
          $this->data['meta']['avatarb'] = get_avatar( get_the_author_meta( 'ID' ), ( ! empty( $this->section['_panels_design_avatar-size'] ) ? $this->section['_panels_design_avatar-size'] : 96 ) );
        } else {
          $this->data['meta']['avatara'] = get_avatar( get_the_author_meta( 'ID' ), ( ! empty( $this->section['_panels_design_avatar-size'] ) ? $this->section['_panels_design_avatar-size'] : 96 ) );
        }
      }
      $this->data['meta']['comments-count'] = get_comments_number();

      // Extract and find any custom taxonomies - i.e. preceded with ct:
      if ( strpos( $meta_string, 'ct:' ) !== FALSE ) {
        $this->data['meta']['custom'][1] = $this->toshow['meta1']['show'] ? pzarc_get_post_terms( get_the_id(), $this->section['_panels_design_meta1-config'] ) : '';
        $this->data['meta']['custom'][2] = $this->toshow['meta2']['show'] ? pzarc_get_post_terms( get_the_id(), $this->section['_panels_design_meta2-config'] ) : '';
        $this->data['meta']['custom'][3] = $this->toshow['meta3']['show'] ? pzarc_get_post_terms( get_the_id(), $this->section['_panels_design_meta3-config'] ) : '';
      }
    }


    /**
     * @param $post
     */
    public function get_image( &$post ) {

      /** FEATURED IMAGE */
      $thumb_id    = get_post_thumbnail_id();
      $focal_point = get_post_meta( $thumb_id, 'pzgp_focal_point', TRUE );
      if ( empty( $focal_point ) ) {
        $focal_point = get_post_meta( get_the_id(), 'pzgp_focal_point', TRUE );
      }
      $focal_point = ( empty( $focal_point ) ? explode( ',', pzarc_get_option( 'architect_focal_point_default', '50,10' ) ) : explode( ',', $focal_point ) );

      if ( ! $thumb_id && $this->section['_panels_settings_use-embedded-images'] ) {
        //TODO: Change to more reliable check if image is in the content?
        preg_match( "/(?<=wp-image-)(\\d)*/uimx", get_the_content(), $matches );
        $thumb_id = ( ! empty( $matches[0] ) ? $matches[0] : FALSE );
      }

      if ( $post->post_type === 'attachment' ) {
        $thumb_id = $post->ID;
      }
      $this->data['image']['id'] = $thumb_id;

      $width  = (int) str_replace( 'px', '', $this->section['_panels_design_image-max-dimensions']['width'] );
      $height = (int) str_replace( 'px', '', $this->section['_panels_design_image-max-dimensions']['height'] );

      $copyright             = array();
      $copyright['size']     = ! empty( $this->section['_panels_settings_image-copyright-text-size'] ) ? $this->section['_panels_settings_image-copyright-text-size'] : 20;
      $copyright['colour']   = ! empty( $this->section['_panels_settings_image-copyright-text-colour'] ) ? str_replace( '#', '', $this->section['_panels_settings_image-copyright-text-colour'] ) : 'ffffff';
      $copyright['font']     = PZARC_PLUGIN_APP_PATH . 'shared/assets/fonts/Open_Sans/OpenSans-Bold.ttf';
      $copyright['text']     = html_entity_decode( ! empty( $this->section['_panels_settings_image-copyright-text'] ) ? $this->section['_panels_settings_image-copyright-text'] : '&copy; Copyright ' . date( 'Y', time() ) );
      $copyright['position'] = ! empty( $this->section['_panels_settings_image-copyright-text-position'] ) ? $this->section['_panels_settings_image-copyright-text-position'] : 'middle';
      $copyright['array']    = '';

      if ( ! empty( $this->section['_panels_settings_image-copyright-add'] ) ) {
        $copyright['array'] = maybe_serialize( array( 'size' => $copyright['size'], 'colour' => $copyright['colour'], 'font' => $copyright['font'], 'text' => $copyright['text'], 'position' => $copyright['position'] ) );
      }

      $quality = ( ! empty( $this->section['_panels_design_image-quality'] ) ? $this->section['_panels_design_image-quality'] : 82 );

      $crop = (int) $focal_point[0] . 'x' . (int) $focal_point[1] . 'x' . $this->section['_panels_settings_image-focal-point'];

      if ( ! empty( $this->data['image']['id'] ) ) {
        $image = get_post( $thumb_id );
        // TODO: Add all the focal point stuff to all the post field_types images and bgimages
        // Easiest to do via a reusable function or all this stuff could be done once!!!!!!!!!
        // could pass $this->data thru a filter
        /** Get the image */
        $image_src                    = wp_get_attachment_image_src( $thumb_id, array(
            $width,
            $height,
            'bfi_thumb' => TRUE,
            'crop'      => $crop,
            'quality'   => $quality,
            'text'      => ( $copyright['array'] && in_array( 'featured', $this->section['_panels_settings_image-copyright-add'] ) ? $copyright['array'] : '' ),
        ) );
        $this->data['image']['image'] = '<img width="' . $width . '" height="' . $height . '" src="' . $image_src[0] . '" class="attachment-' . $width . 'x' . $height . 'x1x' . (int) $focal_point[0] . 'x' . (int) $focal_point[1] . 'x' . $this->section['_panels_settings_image-focal-point'] . '" alt="">';

        // TODO: Add image sizes for each device
        /** Get the original image  */


        if ( $copyright['array'] && in_array( 'lightbox', $this->section['_panels_settings_image-copyright-add'] ) ) {
          $original_size                   = getimagesize( wp_get_attachment_image_url( $thumb_id, 'full' ) );
          $this->data['image']['original'] = wp_get_attachment_image_src( $thumb_id, array(
              $original_size[0],
              $original_size[1],
              'bfi_thumb' => TRUE,
              'crop'      => FALSE,
              'quality'   => 82,
              'text'      => $copyright['array'],
          ) );
        } else {
          $this->data['image']['original'] = wp_get_attachment_image_src( $thumb_id, 'full' );
        }
        preg_match( "/(?<=src\\=\")(.)*(?=\" )/uiUs", $this->data['image']['image'], $results );
        if ( isset( $results[0] ) && ! empty( $this->section['_panels_settings_use-retina-images'] ) && function_exists( 'bfi_thumb' ) ) {
          $params = array(
              'width'   => ( $width * 2 ),
              'height'  => ( $height * 2 ),
              'quality' => $quality,
          );
          // We need the crop to be identical. :/ So how about we just double the size of the image! I'm sure I Saw somewhere that works still.
          $thumb_2X                     = bfi_thumb( $results[0], $params );
          $this->data['image']['image'] = str_replace( '/>', 'data-at2x="' . $thumb_2X . '" />', $this->data['image']['image'] );
        }
        $this->data['image']['caption'] = is_object( $image ) ? $image->post_excerpt : '';

        if ( ! empty( $this->section['_panels_design_caption-alt-text'] ) ) {
          $result                       = preg_replace( '/alt="(.)*"/uiUsm', 'alt="' . $this->data['image']['caption'] . '"', $this->data['image']['image'] );
          $this->data['image']['image'] = $result;
        } elseif ( ! empty( $this->data['image']['id'] ) && strpos( $this->data['image']['image'], 'alt=""' ) && isset( $image->post_title ) ) {
          $this->data['image']['image'] = str_replace( 'alt=""', 'alt="' . esc_attr( $image->post_title ) . '"', $this->data['image']['image'] );
        }

      }
      //Use lorempixel
      // FILLER: Lorempixel
      // TODO: Should this be an action that is themn called by any things like Dummy
      if ( ( empty( $this->data['image']['image'] ) || ! $thumb_id ) && ! empty( $this->section['_panels_design_use-filler-image-source'] ) && 'none' !== $this->section['_panels_design_use-filler-image-source'] && 'specific' !== $this->section['_panels_design_use-filler-image-source'] && 'blank' !== $this->section['_panels_design_use-filler-image-source'] ) {
        $ch = curl_init( 'https://loremflickr.com' ); // v1.15.0
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        $cexec      = curl_exec( $ch );
        $cinfo      = curl_getinfo( $ch );
        $is_offline = ( $cexec == FALSE || $cinfo['http_code'] == 302 );
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
            'transport',
        );
        $lorempixel_category = in_array( $this->section['_panels_design_use-filler-image-source'], $cats ) ? $this->section['_panels_design_use-filler-image-source'] : $cats[ rand( 0, count( $cats ) - 1 ) ];
        $imageURL            = 'https://loremflickr.com/' . $width . '/' . $height . '/' . $lorempixel_category.'?random='.random_int(1,999999); // v1.15.0 Lorempixel died // v11.1 Added random to get unique images
//        $imageURL            = 'http://lorempixel.com/' . $width . '/' . $height . '/' . $lorempixel_category . '/' . rand( 1, 10 );
//        $imageURL = 'http://lorempixel.com/' . $image_grey . $width . '/' . $height . '/' . $post[ 'image' ][ 'original' ];
        $this->data['image']['image']    = ! $is_offline ? '<img src="' . $imageURL . '" >' : '';
        $this->data['image']['original'] = ! $is_offline ? array(
            $imageURL,
            $width,
            $height,
            FALSE,
        ) : FALSE;
        $this->data['image']['caption']  = '';

      }

      // FILLER: Specific
      // TODO: Add retina for this maybe. Tho client side retina may fix anyway
      if ( ( ! $thumb_id || empty( $this->data['image']['image'] ) ) && 'specific' === $this->section['_panels_design_use-filler-image-source'] && ! empty( $this->section['_panels_design_use-filler-image-source-specific']['url'] ) ) {
        if ( function_exists( 'bfi_thumb' ) ) {
          $imageURL = bfi_thumb( $this->section['_panels_design_use-filler-image-source-specific']['url'], array(
              'width'   => $width,
              'height'  => $height,
              'quality' => $quality,
          ) );
        } else {
          $imageURL = $this->section['_panels_design_use-filler-image-source-specific']['url'];
        }
        $this->data['image']['image']    = ! empty( $imageURL ) ? '<img src="' . $imageURL . '" >' : '';
        $this->data['image']['original'] = array( $imageURL, $width, $height, FALSE, );
        $this->data['image']['caption']  = '';
      }

      // If no image and using a blank. // v1.16.0
      if ( ( ! $thumb_id || empty( $this->data['image']['image'] ) ) && $this->section['_panels_design_use-filler-image-source'] === 'blank' && function_exists( 'bfi_thumb' ) ) {
        $blank_img = 'blank_w' . $width . 'h' . $height . '.png';
        $imageURL  = PZARC_CACHE_URL . $blank_img;
        if ( ! file_exists( $imageURL ) ) {
          // Create a blank image
          $img = imagecreatetruecolor( $width, $height );
          imagesavealpha( $img, TRUE );
          $color = imagecolorallocatealpha( $img, 0, 0, 0, 127 );
          imagefill( $img, 0, 0, $color );
          imagepng( $img, PZARC_CACHE_PATH . $blank_img );
        }
        $this->data['image']['image']    = ! empty( $imageURL ) ? '<img src="' . $imageURL . '" >' : '';
        $this->data['image']['original'] = array( $imageURL, $width, $height, FALSE, );
        $this->data['image']['caption']  = '';
      }

      $this->data['image']['image'] = ! empty( $this->data['image']['original'] ) ? $this->data['image']['image'] : '';
    }

    /**
     * BACKGROUND IMAGE
     *
     * This is virtually the same as get_image so needs to be rationalise
     * Beware tho, do need both bgimage and image sometimes
     * so will need to know
     */
    public function get_bgimage( &$post ) {

      $thumb_id = get_post_thumbnail_id( $post );
//      $focal_point = get_post_meta( $thumb_id, 'pzgp_focal_point', TRUE );
//      if ( $post->post_type === 'attachment' ) {
//        $thumb_id = $post->ID;
//      }
//      $this->data['image']['id'] = $thumb_id;
//      // If the post is already passing the attachment,the above won't work so we need to use the post id
//      if ( empty( $focal_point ) ) {
//        $focal_point = get_post_meta( get_the_id(), 'pzgp_focal_point', TRUE );
//      }
//      $focal_point = ( empty( $focal_point ) ? explode( ',', pzarc_get_option( 'architect_focal_point_default', '50,10' ) ) : explode( ',', $focal_point ) );

      // v10.9
      $fp                        = ArcFun::get_focal_point( $thumb_id );
      $focal_point               = $fp['focal_point'];
      $this->data['image']['id'] = $fp['thumb_id'];
      $showbgimage               = ( has_post_thumbnail() && $this->section['_panels_design_feature-location'] === 'fill' && ( $this->section['_panels_design_components-position'] == 'top' || $this->section['_panels_design_components-position'] == 'left' ) ) || ( $this->section['_panels_design_feature-location'] === 'fill' && ( $this->section['_panels_design_components-position'] == 'bottom' || $this->section['_panels_design_components-position'] == 'right' ) );
      // Need to setup for break points.

      // TODO: data-imagesrcs ="1,2,3", data-breakpoints="1,2,3". Then use js to change src.
      $width = (int) str_replace( 'px', '', $this->section['_panels_design_image-max-dimensions']['width'] );
      // TODO: Should this just choose the greater? Or could that be too stupid if  someone puts a very large max-height?
      if ( $this->section['_panels_settings_panel-height-type'] === 'height' ) {
        $height = (int) str_replace( 'px', '', $this->section['_panels_settings_panel-height']['height'] );
      } else {
        $height = (int) str_replace( 'px', '', $this->section['_panels_design_image-max-dimensions']['height'] );
      }

      $copyright             = array();
      $copyright['size']     = ! empty( $this->section['_panels_settings_image-copyright-text-size'] ) ? $this->section['_panels_settings_image-copyright-text-size'] : 20;
      $copyright['colour']   = ! empty( $this->section['_panels_settings_image-copyright-text-colour'] ) ? str_replace( '#', '', $this->section['_panels_settings_image-copyright-text-colour'] ) : 'ffffff';
      $copyright['font']     = PZARC_PLUGIN_APP_PATH . 'shared/assets/fonts/Open_Sans/OpenSans-Bold.ttf';
      $copyright['text']     = html_entity_decode( ! empty( $this->section['_panels_settings_image-copyright-text'] ) ? $this->section['_panels_settings_image-copyright-text'] : '&copy; Copyright ' . date( 'Y', time() ) );
      $copyright['position'] = ! empty( $this->section['_panels_settings_image-copyright-text-position'] ) ? $this->section['_panels_settings_image-copyright-text-position'] : 'middle';
      $copyright['array']    = '';

      if ( ! empty( $this->section['_panels_settings_image-copyright-add'] ) ) {
        $copyright['array'] = maybe_serialize( array( 'size' => $copyright['size'], 'colour' => $copyright['colour'], 'font' => $copyright['font'], 'text' => $copyright['text'], 'position' => $copyright['position'] ) );
      }

      $quality = ( ! empty( $this->section['_panels_design_image-quality'] ) ? $this->section['_panels_design_image-quality'] : 82 );

      $crop = (int) $focal_point[0] . 'x' . (int) $focal_point[1] . 'x' . $this->section['_panels_settings_image-focal-point'];

      pzdb( 'pre get image bg' );

      // Need to grab image again because it uses different dimensions for the bgimge
      $image_src = wp_get_attachment_image_src( $thumb_id, array(
          $width,
          $height,
          'bfi_thumb' => TRUE,
          'crop'      => $crop,
          'quality'   => $quality,
          'text'      => ( $copyright['array'] && in_array( 'featured', $this->section['_panels_settings_image-copyright-add'] ) ? $copyright['array'] : '' ),
      ) );
      // TODO: Use srcset
//      $image_srcset = wp_get_attachment_image_srcset( $thumb_id, array(
//          $width,
//          $height) );
//
//      var_Dump( $image_srcset);
//      var_Dump(bfi_thumb(array(
//          $width,
//          $height,
//          'crop'      => $crop,
//          'quality'   => $quality,
//          'text'      => ( $copyright['array'] && in_array( 'featured', $this->section['_panels_settings_image-copyright-add'] ) ? $copyright['array'] : '' ),
//      )) );
      $this->data['bgimage']['thumb'] = '<img width="' . $width . '" height="' . $height . '" src="' . $image_src[0] . '" class="attachment-' . $width . 'x' . $height . 'x1x' . (int) $focal_point[0] . 'x' . (int) $focal_point[1] . 'x' . $this->section['_panels_settings_image-focal-point'] . '" alt="">';

      // TODO: Add image sizes for each device
      /** Get the original image  */


      if ( $copyright['array'] && in_array( 'lightbox', $this->section['_panels_settings_image-copyright-add'] ) ) {
        $original_size                   = getimagesize( wp_get_attachment_image_url( $thumb_id, 'full' ) );
        $this->data['image']['original'] = wp_get_attachment_image_src( $thumb_id, array(
            $original_size[0],
            $original_size[1],
            'bfi_thumb' => TRUE,
            'crop'      => FALSE,
            'quality'   => 82,
            'text'      => $copyright['array'],
        ) );
      } else {
        $this->data['image']['original'] = wp_get_attachment_image_src( $thumb_id, 'full' );
      }
      pzdb( 'post get original bg' );
      preg_match( "/(?<=src\\=\")(.)*(?=\" )/uiUs", $this->data['bgimage']['thumb'], $results );
      if ( isset( $results[0] ) && ! empty( $this->section['_panels_settings_use-retina-images'] ) && function_exists( 'bfi_thumb' ) ) {
        $params = array(
            'width'   => ( $width * 2 ),
            'height'  => ( $height * 2 ),
            'quality' => ( ! empty( $this->section['_panels_design_image-quality'] ) ? $this->section['_panels_design_image-quality'] : 82 ),
        );
        // We need the crop to be identical. :/ So how about we just double the size of the image! I'm sure I Saw somewhere that works still. In fact, we have no choice, since the double sized image could be bigger than the original.
        $thumb_2X                       = bfi_thumb( $results[0], $params );
        $this->data['bgimage']['thumb'] = str_replace( '/>', 'data-at2x="' . $thumb_2X . '" />', $this->data['bgimage']['thumb'] );
        pzdb( 'after get 2X bg' );
      }

      pzdb( 'end get bgimage' );

      //Use lorempixel
      if ( empty( $this->data['bgimage']['thumb'] ) && ! empty( $this->section['_panels_design_use-filler-image-source'] ) && 'none' !== $this->section['_panels_design_use-filler-image-source'] && 'specific' !== $this->section['_panels_design_use-filler-image-source'] ) {
        $ch = curl_init( 'https://loremflickr.com' );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        $cexec      = curl_exec( $ch );
        $cinfo      = curl_getinfo( $ch );
        $is_offline = ( $cexec == FALSE || $cinfo['http_code'] == 302 );
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
            'transport',
        );
        $lorempixel_category = in_array( $this->section['_panels_design_use-filler-image-source'], $cats ) ? $this->section['_panels_design_use-filler-image-source'] : $cats[ rand( 0, count( $cats ) - 1 ) ];
        $imageURL            = 'https://loremflickr.com/' . $width . '/' . $height . '/' . $lorempixel_category  .'?random='.random_int(1,999999); // v11.1 Added random to get unique images
//        $imageURL = 'http://lorempixel.com/' . $image_grey . $width . '/' . $height . '/' . $post[ 'image' ][ 'original' ];
        $this->data['bgimage']['thumb']  = ! $is_offline ? '<img src="' . $imageURL . '" >' : '';
        $this->data['image']['original'] = ! $is_offline ? array(
            $imageURL,
            $width,
            $height,
            FALSE,
        ) : FALSE;
        $this->data['image']['caption']  = '';

      }
      if ( empty( $this->data['bgimage']['thumb'] ) && 'specific' === $this->section['_panels_design_use-filler-image-source'] && ! empty( $this->section['_panels_design_use-filler-image-source-specific']['url'] ) ) {
        if ( function_exists( 'bfi_thumb' ) ) {
          $imageURL = bfi_thumb( $this->section['_panels_design_use-filler-image-source-specific']['url'], array(
              'width'   => $width,
              'height'  => $height,
              'quality' => ( ! empty( $this->section['_panels_design_image-quality'] ) ? $this->section['_panels_design_image-quality'] : 82 ),
          ) );
        } else {
          $imageURL = $this->section['_panels_design_use-filler-image-source-specific']['url'];
        }
        $this->data['bgimage']['thumb']  = '<img src="' . $imageURL . '" >';
        $this->data['image']['original'] = array(
            $imageURL,
            $width,
            $height,
            FALSE,
        );
        $this->data['image']['caption']  = '';
      }

    }

    public function get_video( &$post ) {
      $video_source = ( is_object( $post ) ? get_post_meta( $post->ID, 'pzarc_features-video', TRUE ) : '' );
      if ( ! empty( $this->section['_panels_settings_use-embedded-images'] ) && empty( $video_source ) ) {
        $video_source = '[video]';
      }
      $this->data['video']['source'] = pzarc_process_video( $video_source );
    }

    /**
     * @param $post
     */
    public function get_content( &$post ) {
      /** CONTENT */

      if ( ArcFun::is_bb_active() && $post->post_type === 'fl-theme-layout' ) {
        $thecontent = dummy_text();
      } else {
        if ( ( empty( $this->section['_panels_design_process-body-shortcodes'] ) || $this->section['_panels_design_process-body-shortcodes'] === 'process' ) ) {
          // v1.11.1 Strip out Blueprint if it's already the one being displayed to stop infinite loops
//            $thecontent = preg_replace( '/\\[architect(.)*?'.$this->build->name.'(.)*?\\]/ui', '<!-- Architect Blueprint '.$this->build->name.' removed to prevent infinite loop -->', $post->post_content );
//            $thecontent = do_shortcode( $thecontent );
          $thecontent = do_shortcode( $post->post_content );
        } else {
          $thecontent = strip_shortcodes( $post->post_content );
        }

//        $more_tag_pos = strpos( $thecontent, (empty($this->section['_panels_design_more-tag-text'])?'<!--more-->':$this->section['_panels_design_more-tag-text'] ));
//        if ( ( $more_tag_pos && !empty( $this->section['_panels_design_more-tag-in-body'] ) && $this->section['_panels_design_more-tag-in-body'] === 'trim' ) && $more_tag_pos ) {
//          $thecontent = substr($thecontent,0,($more_tag_pos-1));
//        }

        // Insert shortcode if required
        if ( ! empty( $this->section['_panels_design_insert-content-shortcode'] ) ) {
          $thecontent      = wpautop( $thecontent ); // Add paragraph html
          $pattern         = "/<p(.)*>.*?<\/p>/uiUm";
          $paragraph_count = preg_match_all( $pattern, $thecontent, $matches );
          if ( count( $matches[0] ) > 0 && ! empty( $this->section['_panels_design_insert-after-paragraph'] ) && $this->section['_panels_design_insert-after-paragraph'] > 0 ) {
            $insert_after   = min( $this->section['_panels_design_insert-after-paragraph'], $paragraph_count );
            $insert_content = strip_tags( $this->section['_panels_design_insert-content-shortcode'], '<br><p><a><strong><em><ul><ol><li><pre><code><blockquote><h1><h2><h3><h4><h5><h6><img>' );
            array_splice( $matches[0], $insert_after, 0, '<p class="arc-inserted-content">' . $insert_content . '</p>' );
            $thecontent = implode( '', $matches[0] );
          }
        }

        // Append additional message
        if ( ! empty( $this->section['_panels_design_additional-message'] ) ) {
          $additional_message = strip_tags( $this->section['_panels_design_additional-message'], '<br><p><a><strong><em><ul><ol><li><pre><code><blockquote><h1><h2><h3><h4><h5><h6><img>' );
          $thecontent         .= '<p class="pzarc-additional-message" >' . $additional_message . '</p>';
        }
      }
      $this->data['content'] = apply_filters( 'the_content', $thecontent );
    }

    /**
     * @param $post
     */
    public function get_excerpt( &$post ) {
      if ( ( empty( $this->section['_panels_design_process-excerpts-shortcodes'] ) || $this->section['_panels_design_process-excerpts-shortcodes'] !== 'process' ) ) {
        $the_content = strip_shortcodes( $post->post_content );
        $the_excerpt = strip_shortcodes( ( empty( $post->post_excerpt ) ? $post->post_content : $post->post_excerpt ) );
      } else {
        // v1.11.1 Strip out Blueprint if it's already the one being displayed to stop infinite loops
        $the_content = $post->post_content;
        $the_excerpt = ( empty( $post->post_excerpt ) ? $post->post_content : $post->post_excerpt );
//          $the_content = preg_replace( '/\\[architect(.)*?'.$this->build->name.'(.)*?\\]/ui', '<!-- Architect Blueprint '.$this->build->name.' removed to prevent infinite loop -->', $post->post_content );
        $the_content = do_shortcode( $the_content );
//          $the_excerpt = preg_replace('/\\[architect(.)*?'.$this->build->name.'(.)*?\\]/ui', '<!-- Architect Blueprint '.$this->build->name.' removed to prevent infinite loop -->', (empty($post->post_excerpt)?$post->post_content:$post->post_excerpt));
        $the_excerpt = do_shortcode( $the_excerpt );
      }

      $truncation_link = pzarc_make_excerpt_more( array(
          '_panels_design_readmore-text'                 => $this->section['_panels_design_readmore-text'],
          '_panels_design_readmore-truncation-indicator' => $this->section['_panels_design_readmore-truncation-indicator'],
      ), $post );

      switch ( TRUE ) {

        case ! empty( $this->section['_panels_design_manual-excerpts'] ) && ! has_excerpt():
          $this->data['excerpt'] = '<!-- #1 arc no content found -->';
          break;

        // CHARACTERS
        case ! empty( $this->section['_panels_design_excerpts-trim-type'] ) && $this->section['_panels_design_excerpts-trim-type'] === 'characters':
          if ( ! empty( $the_content ) ) {
            $this->data['excerpt'] = substr( wp_strip_all_tags( $the_content ), 0, $this->section['_panels_design_excerpts-word-count'] ) . $truncation_link;
          } else {
            $this->data['excerpt'] = '<!-- #2 arc no content found -->';
          }
          break;

        // PARAGRAPHS
        case ! empty( $this->section['_panels_design_excerpts-trim-type'] ) && $this->section['_panels_design_excerpts-trim-type'] === 'paragraphs':
          if ( ! empty( $the_content ) ) {

            $the_lot   = wpautop( $the_content );
            $the_lot   = str_replace( '</p>', '{/EOP/}', $the_lot );
            $the_lot   = str_replace( '<p>', '', $the_lot );
            $the_lot   = str_replace( '{/EOP/}{/EOP/}', '{/EOP/}', $the_lot );
            $the_paras = explode( '{/EOP/}', $the_lot );
            // get rid of any blank ones
            $the_new_paras = array();
            foreach ( $the_paras as $k => $the_para ) {

              $stripped = trim( strip_tags( $the_para ) ); // Covers Gutenberg comments. 1.10.8

              if ( ! empty( $stripped ) ) {
                $the_new_paras[] = $the_para;
              }
            }
            $this->data['excerpt'] = '<!-- #3 arc no content found -->';
            $i                     = 1;
            while ( $i <= (int) $this->section['_panels_design_excerpts-word-count'] && $i <= count( $the_new_paras ) ) {
              $this->data['excerpt'] .= '<p>' . $the_new_paras[ $i - 1 ] . '</p>';
              $i ++;
            }
            $this->data['excerpt'] = $this->data['excerpt'] . $truncation_link;
          } else {
            $this->data['excerpt'] = '<!-- #4 arc no content found -->';
          }
          break;

        // MORETAG
        case ! empty( $this->section['_panels_design_excerpts-trim-type'] ) && $this->section['_panels_design_excerpts-trim-type'] === 'moretag':
          // More tags are automatically executed on the non-single pages. And no way to override. Pain!
          //
          $the_lot = get_extended( $the_content );
          if ( ! empty( $the_lot['extended'] ) ) {
            $this->data['excerpt'] = $the_lot['main'] . $truncation_link;
          } else {
            $this->data['excerpt'] = $the_excerpt;
          }
          break;

        // WORDS
        case ! empty( $this->section['_panels_design_excerpts-trim-type'] ) && $this->section['_panels_design_excerpts-trim-type'] === 'words':
        default:
          $this->data['excerpt'] = wp_trim_words( $the_excerpt, $this->section['_panels_design_excerpts-word-count'], $truncation_link );
      }
      $this->data['excerpt'] = apply_filters( 'the_excerpt', $this->data['excerpt'] );
    }


    /**
     *
     * Gets all custom field data for the post
     *
     * @param $post
     */
    public function get_custom( &$post ) {

      $postmeta = apply_filters( 'arc_get_custom_data', get_post_meta( get_the_ID() ) );
      $cfcount  = $this->section['_panels_design_custom-fields-count'];
      if ( $cfcount ) {
        for ( $i = 1; $i <= $cfcount; $i ++ ) {
          // the settings come from section
          if ( ! empty( $this->section[ '_panels_design_cfield-' . $i . '-name' ] ) && ! empty( $this->section[ '_panels_design_cfield-' . $i . '-field-type' ] && $this->section[ '_panels_design_cfield-' . $i . '-name' ] != 'not_used' ) ) {
            $this->data['cfield'][ $i ] = arc_cft_base_get( $i, $this->section, $post, $postmeta, NULL );
            //var_dump($this->data['cfield'][ $i ]);
            $func = str_replace( '-', '_', 'arc_cft_' . $this->section[ '_panels_design_cfield-' . $i . '-field-type' ] ) . '_get';
            // var_Dump($func);
            $this->data['cfield'][ $i ] = $func( $i, $this->section, $post, $postmeta, $this->data['cfield'][ $i ] );
          }

        }
      }

    }

    public function get_miscellanary( &$post ) {
      global $_architect_options;
      $this->data['inherit-hw-block-type'] = ( ! empty( $_architect_options['architect_hw-content-class'] ) ? 'block-type-content ' : '' );
      $this->data['postid']                = get_the_ID();
      $this->data['poststatus']            = get_post_status();
//      $this->data[ 'posttype' ]    = get_post_type();
      $this->data['posttype']    = ! empty( $post ) ? $post->post_type : 'unknown'; // v1.15.0 Happening when post type is users... TODO: Fix for unknown post field_types
      $this->data['permalink']   = get_the_permalink();
      $post_format               = get_post_format();
      $this->data ['postformat'] = ( empty( $post_format ) ? 'standard' : $post_format );
    }

    /****************************************
     * End of data collect
     ***************************************/

    /****************************************
     * Begin rendering
     ***************************************/

    public function render_title( $component, $content_type, $panel_def, $rsid, $layout_mode = FALSE ) {

      if ( ! empty( $this->section['_panels_design_title-prefix'] ) && 'thumb' === $this->section['_panels_design_title-prefix'] ) {
        $panel_def[ $component ] = str_replace( '{{title}}', $this->data['title']['thumb'] . '<span class="pzarc-title-wrap">' . $this->data['title']['title'] . '</span>', $panel_def[ $component ] );
      } else {
        $panel_def[ $component ] = str_replace( '{{title}}', $this->data['title']['title'], $panel_def[ $component ] );
      }

      if ( ! empty( $this->section['_panels_design_link-titles'] ) && $this->section['_panels_design_link-titles'] ) {
        $panel_def[ $component ] = str_replace( '{{postlink}}', $panel_def['postlink'], $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{closepostlink}}', '</a>', $panel_def[ $component ] );
      };
      switch ( TRUE ) {
        case ! empty( $this->section['_panels_design_use-scale-fonts-title'] ) && ! empty( $this->section['_panels_design_use-responsive-font-size-title'] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', '{{extensionclass}} is-responsive-scaled ', $panel_def[ $component ] );
          break;
        case ! empty( $this->section['_panels_design_use-responsive-font-size-title'] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', '{{extensionclass}} is-responsive ', $panel_def[ $component ] );
          break;
      }

      $selectors               = str_replace( array( ',', '.', '  ' ), ' ', $this->_architect['architect_config_entry-title-selectors'] );
      $panel_def[ $component ] = str_replace( '{{extensionclass}}', $selectors, $panel_def[ $component ] );


      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );

    }

    /**
     * @param      $component
     * @param      $content_type
     * @param      $panel_def
     * @param      $rsid
     * @param bool $layout_mode
     *
     * @return mixed|void
     */
    public function render_meta( $component, $content_type, $panel_def, $rsid, $layout_mode = FALSE ) {
      $panel_def[ $component ] = str_replace( '{{id}}', $this->data['meta']['id'], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{datetime}}', $this->data['meta']['datetime'], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{fdatetime}}', $this->data['meta']['fdatetime'], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{sortable}}', ' data-order="' . str_replace( ',', ' ', strtotime( $this->data['meta']['fdatetime'] ) ) . '"', $panel_def[ $component ] );

      if ( empty( $this->section['_panels_design_excluded-authors'] ) || ! in_array( get_the_author_meta( 'ID' ), $this->section['_panels_design_excluded-authors'] ) ) {
        //Remove text indicators
        $panel_def[ $component ] = str_replace( '//', '', $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{authorname}}', $this->data['meta']['authorname'], $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{authorlink}}', $this->data['meta']['authorlink'], $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{authoremail}}', $this->data['meta']['authoremail'], $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{avatara}}', $this->data['meta']['avatara'], $panel_def[ $component ] );
        $panel_def[ $component ] = str_replace( '{{avatarb}}', $this->data['meta']['avatarb'], $panel_def[ $component ] );
      } else {
        // Removed unused text and indicators
        $panel_def[ $component ] = preg_replace( "/\\/\\/(.)*\\/\\//uiUm", "", $panel_def[ $component ] );
      }
      $panel_def[ $component ] = str_replace( '{{categories}}', $this->data['meta']['categories'], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{categorieslinks}}', $this->data['meta']['categorieslinks'], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{tags}}', $this->data['meta']['tags'], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{tagslinks}}', $this->data['meta']['tagslinks'], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{commentslink}}', $panel_def['comments-link'], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{commentscount}}', $this->data['meta']['comments-count'], $panel_def[ $component ] );
      $panel_def[ $component ] = str_replace( '{{editlink}}', $panel_def['editlink'], $panel_def[ $component ] );
      if ( ! empty( $this->data['meta']['custom'] ) ) {
        foreach ( $this->data['meta']['custom'] as $meta ) {
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

      $selectors               = str_replace( array( ',', '.', '  ' ), ' ', $this->_architect['architect_config_entry-meta-selectors'] );
      $panel_def[ $component ] = str_replace( '{{extensionclass}}', $selectors, $panel_def[ $component ] );


      return self::render_generics( $component, $content_type, do_shortcode( $panel_def[ $component ] ), $layout_mode );
    }

    /**
     * @param      $component
     * @param      $content_type
     * @param      $panel_def
     * @param      $rsid
     * @param bool $layout_mode
     *
     * @return mixed
     */
    public function render_content( $component, $content_type, $panel_def, $rsid, $layout_mode = FALSE ) {
      $panel_def[ $component ] = str_replace( '{{content}}', $this->data['content'], $panel_def[ $component ] );
      if ( $this->section['_panels_design_feature-location'] === 'content-left' || $this->section['_panels_design_feature-location'] === 'content-right' && in_array( 'content', $this->section['_panels_design_feature-in'] ) ) {
        if ( ! empty( $this->data['image']['image'] ) ) {
          $selectors = str_replace( array( ',', '.', '  ' ), ' ', $this->_architect['architect_config_entry-image-selectors'] );
          $image_def = str_replace( '{{extensionclass}}', $selectors, $panel_def['image'] );

          $panel_def[ $component ] = str_replace( '{{image-in-content}}', $image_def, $panel_def[ $component ] );

          if ( $this->section['_panels_design_image-captions'] ) {
            $panel_def[ $component ] = str_replace( '{{captioncode}}', '<span class="caption">' . $this->data['image']['caption'] . '</span>', $panel_def[ $component ] );
          }

          $panel_def[ $component ] = str_replace( '{{image}}', $this->data['image']['image'], $panel_def[ $component ] );
          $panel_def[ $component ] = str_replace( '{{incontent}}', 'in-content-thumb', $panel_def[ $component ] );

          if ( 'none' !== $this->section['_panels_design_link-image'] ) {
            $link                    = self::get_link( $rsid, 'incontent', $panel_def['postlink'] );
            $panel_def[ $component ] = str_replace( '{{postlink}}', $link, $panel_def[ $component ] );
            $panel_def[ $component ] = str_replace( '{{closepostlink}}', '</a>', $panel_def[ $component ] );
          }


//          if ($this->section[ '_panels_design_link-image' ]) {
//            $panel_def[ $component ] = str_replace('{{postlink}}', $panel_def[ 'postlink' ], $panel_def[ $component ]);
//            $panel_def[ $component ] = str_replace('{{closepostlink}}', '</a>', $panel_def[ $component ]);
//          }
        }
      }
      if ( empty( $this->data['image']['image'] ) ) {
        //TODO: Add an option to set if width spreads
        if ( ! empty( $this->section['_panels_design_maximize-content'] ) ) {
          $panel_def[ $component ] = str_replace( '{{nothumb}}', 'nothumb maxwidth', $panel_def[ $component ] );
        } else {
          $panel_def[ $component ] = str_replace( '{{nothumb}}', 'nothumb', $panel_def[ $component ] );
        }
      }
      switch ( TRUE ) {
        case ! empty( $this->section['_panels_design_use-scale-fonts'] ) && ! empty( $this->section['_panels_design_use-responsive-font-size'] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', '{{extensionclass}} is-responsive-scaled ', $panel_def[ $component ] );
          break;
        case ! empty( $this->section['_panels_design_use-responsive-font-size'] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', '{{extensionclass}} is-responsive ', $panel_def[ $component ] );
          break;
      }

      $selectors               = str_replace( array( ',', '.', '  ' ), ' ', $this->_architect['architect_config_entry-content-selectors'] );
      $panel_def[ $component ] = str_replace( '{{extensionclass}}', $selectors, $panel_def[ $component ] );

      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );
    }

    /**
     * @param      $component
     * @param      $content_type
     * @param      $panel_def
     * @param      $rsid
     * @param bool $layout_mode
     *
     * @return mixed
     */
    public function render_excerpt( $component, $content_type, $panel_def, $rsid, $layout_mode = FALSE ) {
      $panel_def[ $component ] = str_replace( '{{excerpt}}', $this->data['excerpt'], $panel_def[ $component ] );

      if ( $this->section['_panels_design_feature-location'] === 'content-left' || $this->section['_panels_design_feature-location'] === 'content-right' && in_array( 'excerpt', $this->section['_panels_design_feature-in'] ) ) {
        if ( ! empty( $this->data['image']['image'] ) ) {

          $selectors = str_replace( array( ',', '.', '  ' ), ' ', $this->_architect['architect_config_entry-image-selectors'] );
          $image_def = str_replace( '{{extensionclass}}', $selectors, $panel_def['image'] );

          $panel_def[ $component ] = str_replace( '{{image-in-content}}', $image_def, $panel_def[ $component ] );

          if ( $this->section['_panels_design_image-captions'] ) {
            $panel_def[ $component ] = str_replace( '{{captioncode}}', '<span class="caption">' . $this->data['image']['caption'] . '</span>', $panel_def[ $component ] );
          }

          $panel_def[ $component ] = str_replace( '{{image}}', $this->data['image']['image'], $panel_def[ $component ] );
          $panel_def[ $component ] = str_replace( '{{incontent}}', 'in-content-thumb', $panel_def[ $component ] );

          if ( 'none' !== $this->section['_panels_design_link-image'] ) {
            $link                    = self::get_link( $rsid, 'inexcerpt', $panel_def['postlink'] );
            $panel_def[ $component ] = str_replace( '{{postlink}}', $link, $panel_def[ $component ] );
            $panel_def[ $component ] = str_replace( '{{closepostlink}}', '</a>', $panel_def[ $component ] );
          }
        }
      }
      if ( empty( $this->data['image']['image'] ) ) {
        //TODO: Add an option to set if width spreads
        if ( ! empty( $this->section['_panels_design_maximize-content'] ) ) {
          $panel_def[ $component ] = str_replace( '{{nothumb}}', 'nothumb maxwidth', $panel_def[ $component ] );
        } else {
          $panel_def[ $component ] = str_replace( '{{nothumb}}', 'nothumb', $panel_def[ $component ] );
        }
      }

      //_panels_design_thumb-position
      switch ( TRUE ) {
        case ! empty( $this->section['_panels_design_use-scale-fonts'] ) && ! empty( $this->section['_panels_design_use-responsive-font-size'] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', '{{extensionclass}} is-responsive-scaled ', $panel_def[ $component ] );
          break;
        case ! empty( $this->section['_panels_design_use-responsive-font-size'] ):
          $panel_def[ $component ] = str_replace( '{{extensionclass}}', '{{extensionclass}} is-responsive ', $panel_def[ $component ] );
          break;
      }

      $selectors               = str_replace( array( ',', '.', '  ' ), ' ', $this->_architect['architect_config_entry-excerpt-selectors'] );
      $panel_def[ $component ] = str_replace( '{{extensionclass}}', $selectors, $panel_def[ $component ] );

      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );
    }

    /**
     * @function: render_image()
     *
     * @param      $component
     * @param      $content_type
     * @param      $panel_def
     * @param      $rsid
     * @param bool $layout_mode
     *
     * @return mixed
     */
    // NOTE: This will not be called if the image is displayed in the content.
    public function render_image( $component, $content_type, $panel_def, $rsid, $layout_mode = FALSE ) {

      if ( 'video' === $this->section['_panels_settings_feature-type'] ) {
        $panel_def[ $component ] = str_replace( '{{image}}', $this->data['video']['source'], $panel_def[ $component ] );

      } else {
        if ( 'none' !== $this->section['_panels_design_link-image'] ) {
          $link                    = self::get_link( $rsid, 'inimage', $panel_def['postlink'] );
          $panel_def[ $component ] = str_replace( '{{postlink}}', $link, $panel_def[ $component ] );
          $panel_def[ $component ] = str_replace( '{{closepostlink}}', '</a>', $panel_def[ $component ] );
        }


        if ( $this->section['_panels_design_image-captions'] ) {
          $caption                 = str_replace( '{{caption}}', $this->data['image']['caption'], $panel_def['caption'] );
          $panel_def[ $component ] = str_replace( '{{captioncode}}', $caption, $panel_def[ $component ] );
        }

        if ( $this->section['_panels_settings_image-focal-point'] === 'scale_height' ) {
          $this->data['image']['image'] = preg_replace( "/width=\"(\\d)*\"\\s/uiUmx", "", $this->data['image']['image'] );
        }
        if ( $this->section['_panels_settings_image-focal-point'] === 'scale' ) {
          $this->data['image']['image'] = preg_replace( "/height=\"(\\d)*\"\\s/uiUmx", "", $this->data['image']['image'] );
        }

        $panel_def[ $component ] = str_replace( '{{image}}', $this->data['image']['image'], $panel_def[ $component ] );

        if ( ! empty( $this->section['_panels_design_centre-image'] ) ) {
          $panel_def[ $component ] = str_replace( '{{centred}}', 'centred', $panel_def[ $component ] );
        }
        if ( 'float' === $this->section['_panels_design_feature-location'] ) {
          $panel_def[ $component ] = str_replace( '{{location}}', 'pzarc-components-' . $this->section['_panels_design_components-position'], $panel_def[ $component ] );

        }
        if ( ! empty( $this->section['_panels_design_rotate-image'] ) ) {
          $rot = rand( - 50, 50 ) / 10;
          // TODO: this is bad! Not dumb at all
          $panel_def[ $component ] = str_replace( '{{extrastyling}}', 'style="transform:rotate(' . $rot . 'deg);"', $panel_def[ $component ] );
        }

        switch ( TRUE ) {
          case ( empty( $this->data['image']['image'] ) && 'table' === $layout_mode ) :
            $panel_def[ $component ] = '<td class="td-entry-thumbnail"></td>';
            break;
          case ( empty( $this->data['image']['image'] ) ) :
            $panel_def[ $component ] = '';
            break;
        }


      }
//      foreach ($this->data[ 'image' ] as $key => $value) {
//        $template[ $type ] = str_replace('{{' . $key . '}}', $value, $template[ $type ]);
//      }

      $selectors               = str_replace( array( ',', '.', '  ' ), ' ', $this->_architect['architect_config_entry-image-selectors'] );
      $panel_def[ $component ] = str_replace( '{{extensionclass}}', $selectors, $panel_def[ $component ] );

      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );
    }


    /**
     * @function: render_bgimage
     *
     * @param      $component
     * @param      $content_type
     * @param      $panel_def
     * @param      $rsid
     * @param bool $layout_mode
     *
     * @return mixed|void
     */
    public function render_bgimage( $component, $content_type, $panel_def, $rsid, $layout_mode = FALSE ) {
      if ( 'video' === $this->section['_panels_settings_feature-type'] ) {
        $panel_def[ $component ] = str_replace( '{{bgimage}}', $this->data['video']['source'], $panel_def[ $component ] );

      } else {

        if ( ! empty( $this->data['bgimage']['thumb'] ) ) {
          if ( $this->section['_panels_settings_image-focal-point'] === 'scale_height' || $this->section['_panels_settings_image-focal-point'] === 'shrink' ) {
            $this->data['bgimage']['thumb'] = preg_replace( "/width=\"(\\d)*\"\\s/uiUmx", "", $this->data['bgimage']['thumb'] );
          }
          if ( $this->section['_panels_settings_image-focal-point'] === 'scale' || $this->section['_panels_settings_image-focal-point'] === 'shrink' ) {
            $this->data['bgimage']['thumb'] = preg_replace( "/height=\"(\\d)*\"\\s/uiUmx", "", $this->data['bgimage']['thumb'] );
          }

          $panel_def[ $component ] = str_replace( '{{bgimage}}', $this->data['bgimage']['thumb'], $panel_def[ $component ] );
        } else {
          // Gotta fill the background with something, else it collapses
          $width  = $this->section['_panels_design_image-max-dimensions']['width'];
          $height = $this->section['_panels_design_image-max-dimensions']['height'];

          $fakethumb               = '<div class="pzarc-fakethumb" style="width:' . $width . ';height:' . $height . ';"></div>';
          $panel_def[ $component ] = str_replace( '{{bgimage}}', $fakethumb, $panel_def[ $component ] );

        }
        $panel_def[ $component ] = str_replace( '{{trim-scale}}', ' ' . $this->section['_panels_design_feature-location'] . ' ' . $this->section['_panels_design_background-image-resize'], $panel_def[ $component ] );

        if ( 'none' !== $this->section['_panels_design_link-image'] ) {
          $link                    = self::get_link( $rsid, 'inbackg', $panel_def['postlink'] );
          $panel_def[ $component ] = str_replace( '{{postlink}}', $link, $panel_def[ $component ] );
          $panel_def[ $component ] = str_replace( '{{closepostlink}}', '</a>', $panel_def[ $component ] );
        }
      }

      $selectors               = str_replace( array( ',', '.', '  ' ), ' ', $this->_architect['architect_config_entry-image-selectors'] );
      $panel_def[ $component ] = str_replace( '{{extensionclass}}', $selectors, $panel_def[ $component ] );

      // we shoudl filte rthis then we can do stuff to itwith add ons.

      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );
    }


    /**
     * @param      $component
     * @param      $content_type
     * @param      $panel_def
     * @param      $rsid
     * @param bool $layout_mode
     *
     * @return mixed
     */
    public function render_custom( $component, $content_type, $panel_def, $rsid, $layout_mode = FALSE ) {


      // Show each custom field in this group
      if ( ! empty( $this->data['cfield'] ) ) {
        $panel_def_cfield = $panel_def['cfield'];
        $build_field      = '';
        $i                = 1;
        // var_dump($this->data['cfield']);
        foreach ( $this->data['cfield'] as $k => $v ) {
//          $panel_def[$component] = ArcFun::render_custom_field();

          if ( $v['data']['group'] === $component && ( ! empty( $v['data']['value'] ) || $v['data']['name'] === 'use_empty' ) & $v['data']['name'] != 'not_used' ) {

            $acf_class = ! empty( $v['meta']['acf_settings']['wrapper']['class'] ) ? $v['meta']['acf_settings']['wrapper']['class'] : '';
            $acf_id    = ! empty( $v['meta']['acf_settings']['wrapper']['id'] ) ? $v['meta']['acf_settings']['wrapper']['id'] : '';
            $acf_width = ! empty( $v['meta']['acf_settings']['wrapper']['width'] ) ? 'width:' . $v['meta']['acf_settings']['wrapper']['width'] . '%;' : '';
            $acf_style = ! empty( $acf_width ) ? 'display:block;' . $acf_width : '';


            // Can't put a div in a p or hn tag. Changed to span.

            $content = '<span id="' . $acf_id . '" class="arc-cfield arc-cfield-' . $v['data']['field-type'] . ' ' . $acf_class . ( $acf_style ? '" style="' . $acf_style : '' ) . '">' . $v['data']['value'] . '</span>';

            $prefix_image = '';
            $suffix_image = '';
            if ( ! empty( $v['data']['prefix-image'] ) ) {
              $prefix_image = '<img src="' . $v['prefix-image'] . '" class="pzarc-presuff-image prefix-image">';
            }
            if ( ! empty( $v['data']['suffix-image'] ) ) {
              $suffix_image = '<img src="' . $v['data']['suffix-image'] . '" class="pzarc-presuff-image suffix-image">';
            }


            $content = $prefix_image . $v['data']['prefix-text'] . $content . $v['data']['suffix-text'] . $suffix_image;
            if ( ! empty( $v['data']['link-field'] ) ) {
              $content = '<a href="' . $v['data']['link-field'] . '" target="' . $v['data']['link-behaviour'] . '" rel="noopener">' . $content . '</a>';
            }

            // Not sure why this limitation was set. Removed in 1.10.0
//            if ($v['name'] === 'use_empty' && empty($v['link-field'])) {
//              $content = '';
//            }

//            if ('none' !== $v[ 'wrapper-tag' ]) {
//              $class_name = !empty($v[ 'class-name' ]) ? ' class="' . $v[ 'class-name' ] . '"' : null;
//              $content    = '<' . $v[ 'wrapper-tag' ] . $class_name . '>' . $content . '</' . $v[ 'wrapper-tag' ] . '>';
//            }

            // TODO: Should apply filters here?
            $panel_def_cfield = str_replace( '{{cfieldwrapper}}', $v['data']['wrapper-tag'], $panel_def_cfield );
            $panel_def_cfield = str_replace( '{{cfieldcontent}}', $content, $panel_def_cfield );
            $panel_def_cfield = str_replace( '{{cfieldname}}', $v['data']['name'], $panel_def_cfield );
            $panel_def_cfield = str_replace( '{{cfieldnumber}}', $k, $panel_def_cfield );
            if ( ! empty( $v['data']['data'] ) ) {
              $panel_def_cfield = str_replace( '{{cfielddata}}', $v['data']['data'], $panel_def_cfield );
            }

            $build_field .= $panel_def_cfield;
          }
          $panel_def_cfield = $panel_def['cfield'];
        }
        $panel_def[ $component ] = str_replace( '{{' . $component . 'innards}}', $build_field, $panel_def[ $component ] );

      } else {
        $panel_def[ $component ] = '';
      }

//      $selectors             = str_replace(array(',', '.', '  '), ' ', $this->_architect['architect_config_entry-custom-selectors']);
//      $panel_def[$component] = str_replace('{{extensionclass}}', $selectors, $panel_def[$component]);

      return self::render_generics( $component, $content_type, do_shortcode( $panel_def[ $component ] ), $layout_mode );

    }

    public function render_wrapper( $component, $content_type, $panel_def, $rsid, $layout_mode = FALSE ) {
      $panel_def[ $component ] = str_replace( '{{mimic-block-type}}', $this->data['inherit-hw-block-type'], $panel_def[ $component ] );

      return self::render_generics( $component, $content_type, $panel_def[ $component ], $layout_mode );
    }

    /**
     * @param $component
     * @param $source
     * @param $line
     * @param $layout_mode
     *
     * @return mixed
     */
    public function render_generics( $component, $source, $line, $layout_mode ) {

      // Devs can plugin here. Filter must return $line value
      $line = apply_filters( 'arc_render_components', $line, $component, $source, $layout_mode );


      //todo: make sure source is actual WP valid eg. soemthings might be attachment
      // Do any generic replacements
      $line = str_replace( '{{postid}}', $this->data['postid'], $line );
      $line = str_replace( '{{title}}', $this->data['title']['title'], $line );
      $line = str_replace( '{{permalink}}', $this->data['permalink'], $line );
      $line = str_replace( '{{closelink}}', '</a>', $line );
      $line = str_replace( '{{categories}}', $this->data['meta']['categories'], $line );
      $line = str_replace( '{{tags}}', $this->data['meta']['tags'], $line );
      $line = str_replace( '{{poststatus}}', $this->data['poststatus'], $line );
      $line = str_replace( '{{postformat}}', $this->data['postformat'], $line );
      $line = str_replace( '{{posttype}}', $this->data['posttype'], $line );

      $pzclasses = 'pzarc-components ';
      $pzclasses .= ( $this->section['_panels_design_components-position'] === 'left' || $this->section['_panels_design_components-position'] === 'right' ) ? 'vertical-content pzarc-align-' . $this->section['_panels_design_components-position'] : '';

      $line = str_replace( '{{pzclasses}}', $pzclasses, $line );

      if ( 'table' === $layout_mode ) {
        $line = str_replace( '{{div}}', 'td', $line );
        $line = str_replace( '{{h1open}}', '<td class="td-entry-title"><h1 ', $line );
        $line = str_replace( '{{h1close}}', '</h1></td>', $line );
        $line = str_replace( '{{figopen}}', '<td class="td-entry-thumbnail"><figure ', $line );
        $line = str_replace( '{{figclose}}', '</figure></td>', $line );
      } else {
        $line      = str_replace( '{{div}}', 'div', $line );
        $title_tag = ! empty( $this->section['_panels_design_title-wrapper-tag'] ) ? $this->section['_panels_design_title-wrapper-tag'] : 'h1';
        $line      = str_replace( '{{h1open}}', '<' . $title_tag . ' ', $line );
        $line      = str_replace( '{{h1close}}', '</' . $title_tag . '>', $line );
        $line      = str_replace( '{{figopen}}', '<figure ', $line );
        $line      = str_replace( '{{figclose}}', '</figure>', $line );

      }
      if ( ! empty( $this->section['_panels_settings_disable-image-saving'] ) ) {
        $line = str_replace( '{{disable-save}}', 'disable-save', $line );
      } else {
        $line = str_replace( '{{disable-save}}', 'd', $line );
      }

      return $line;
    }


    /**
     * Default Loop
     */
    public function loop( $section_no, &$architect, &$panel_class, $class ) {
      $this->build            = $architect->build;
      $this->arc_query        = $architect->arc_query;
      $section[ $section_no ] = $this->build->blueprint['section_object'][ $section_no ];

      $panel_def = $panel_class->panel_def();

      // Setup meta tags
      $panel_def = self::build_meta_header_footer_groups( $panel_def, $section[ $section_no ]->section['section-panel-settings'] );

      $i = 1;

      $section[ $section_no ]->open_section();
      pzdb( 'pre_generic_loop' );

      // For custom conetnet such as NGG or RSS, this will look quite different!
      $loopmax   = ( defined( 'PZARC_PRO' ) ? 999999999 : 15 );
      $loopcount = 0;

      // Weird nudge needed when Arc is called inside a main loop with defaults.
      if ( $this->build->blueprint['_blueprints_content-source'] === 'defaults' ) {
        $this->arc_query->have_posts();
      }
// RESUME: Was doing something here!
//      d($this->arc_query);

      // This didn't work coz of shortcodes. But could be repurposed to insert a panel
//      $insert_shortcodes = array();
//      $shortcodes        = array();
//      if ( ! empty( $this->build->blueprint['_blueprints_insert-shortcodes'] ) ) {
//        $insert_shortcodes = explode( ',', $this->build->blueprint['_blueprints_insert-shortcodes'] );
//        $key               = '';
//        foreach ( $insert_shortcodes as $v ) {
//          if ( is_numeric( $v ) ) {
//            $key = $v;
//          } elseif ( is_string( $v ) && ! empty( $key ) ) {
//            $shortcodes[ $key ] = '[' . $v . ']';
//            $key                = '';
//          }
//        }
//      }
      while ( $this->arc_query->have_posts() && $loopcount ++ < $loopmax ) {
        //  var_dump("You is here");
        $this->arc_query->the_post();
        pzdb( 'top_of_loop Post:' . get_the_id() );
        $section[ $section_no ]->render_panel( $panel_def, $i, $class, $panel_class, $this->arc_query );

        $panels_per_view  = $this->build->blueprint[ '_blueprints_section-' . ( $section_no - 1 ) . '-panels-per-view' ];
        $panels_unlimited = empty( $this->build->blueprint[ '_blueprints_section-' . ( $section_no - 1 ) . '-panels-limited' ] );

//        if ( array_key_exists( $i, $shortcodes ) ) {
//          echo '<div class="pzarc-inserted-shortcode">'.do_shortcode( $shortcodes[ $i ] ).'</div>';
//        }

        if ( $i ++ >= $panels_per_view && ! $panels_unlimited ) {
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
    public function get_nav_items( $blueprints_navigator, &$arc_query, $nav_labels, $nav_title_len = 0 ) {
      // We shouldn't have to pass arc_query! And we don't need to in this one, but for some unsolved reason in arc_Panel_Dummy, we do. So for consistency, doing it here too.
      $nav_items = array();
      $i         = 0;
      foreach ( $arc_query->posts as $the_post ) {

        switch ( $blueprints_navigator ) {

          case 'tabbed':
            if ( class_exists( 'HeadwayLayoutOption' ) && ( TRUE == ( $alt_title = HeadwayLayoutOption::get( $the_post->ID, 'alternate-title', FALSE, TRUE ) ) ) ) {
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
              $focal_point = get_post_meta( $the_post->ID, 'pzgp_focal_point', TRUE );
            }

            $focal_point = ( empty( $focal_point ) ? explode( ',', pzarc_get_option( 'architect_focal_point_default', '50,10' ) ) : explode( ',', $focal_point ) );
            if ( ! $thumb_id && ! empty( $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_settings_use-embedded-images'] ) ) {
              //TODO: Changed to more reliable check if image is in the content?
              preg_match( "/(?<=wp-image-)(\\d)*/uimx", get_the_content(), $matches );
              $thumb_id = ( ! empty( $matches[0] ) ? $matches[0] : FALSE );
            }

            //  $focal_point = array( 50, 50 );


            if ( 'attachment' === $the_post->post_type ) {

              $thumb = wp_get_attachment_image( $the_post->ID, array(
                  self::get_thumbsize( 'w' ),
                  self::get_thumbsize( 'h' ),
                  'bfi_thumb' => TRUE,
                  'crop'      => (int) $focal_point[0] . 'x' . (int) $focal_point[1],
                  'quality'   => ( ! empty( $this->section['_panels_design_image-quality'] ) ? $this->section['_panels_design_image-quality'] : 82 ),
              ) );

            } else {

              $thumb = get_the_post_thumbnail( $the_post->ID, array(
                  self::get_thumbsize( 'w' ),
                  self::get_thumbsize( 'h' ),
                  'bfi_thumb' => TRUE,
                  'crop'      => (int) $focal_point[0] . 'x' . (int) $focal_point[1],
                  'quality'   => ( ! empty( $this->section['_panels_design_image-quality'] ) ? $this->section['_panels_design_image-quality'] : 82 ),
              ) );

            }
            if ( empty( $thumb ) && 'specific' === $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_design_use-filler-image-source'] && ! empty( $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_design_use-filler-image-source-specific']['url'] ) ) {
              if ( function_exists( 'bfi_thumb' ) ) {
                $imageURL = bfi_thumb( $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_design_use-filler-image-source-specific']['url'], array(
                    'width'   => self::get_thumbsize( 'w' ),
                    'height'  => self::get_thumbsize( 'h' ),
                    'quality' => ( ! empty( $this->section['_panels_design_image-quality'] ) ? $this->section['_panels_design_image-quality'] : 82 ),
                ) );
              } else {
                $imageURL = $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_design_use-filler-image-source-specific']['url'];
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
     * Custom loop for Dummy data
     */
    public function loop_from_array( $section_no, &$architect, &$panel_class, $class ) {
      static $j = 1;
      $this->build     = $architect->build;
      $this->arc_query = $architect->arc_query;

      $section[ $section_no ] = $this->build->blueprint['section_object'][ $section_no ];

      $panel_def = $panel_class->panel_def();

      // Setup meta tags
      $panel_def = self::build_meta_header_footer_groups( $panel_def, $section[ $section_no ]->section['section-panel-settings'] );

      //   var_dump(esc_html($panel_def));

      $i = 1;

      // Does this work for non
      $section[ $section_no ]->open_section();
      $post_count = ( defined( 'PZARC_PRO' ) ? count( $this->arc_query ) : 15 );
      for ( $j = 0; $j < $post_count; $j ++ ) {

        $section[ $section_no ]->render_panel( $panel_def, $i, $class, $panel_class, $this->arc_query );

        if ( $i ++ >= $this->build->blueprint[ '_blueprints_section-' . ( $section_no - 1 ) . '-panels-per-view' ] && ! empty( $this->build->blueprint[ '_blueprints_section-' . ( $section_no - 1 ) . '-panels-limited' ] ) ) {
          break;

        }

      }
      $section[ $section_no ]->close_section();

      // Unsetting causes it to run the destruct, which closes the div!
      unset( $section[ $section_no ] );

    }

    public function get_nav_items_from_array( $blueprints_navigator, &$arc_query, $nav_labels, $nav_title_len = 0 ) {
      $nav_items = array();
      for ( $j = 0; $j < count( $arc_query ); $j ++ ) {
        switch ( $blueprints_navigator ) {

          case 'tabbed':
            $post_title = $arc_query[ $j ]['title']['title'];
            if ( ! empty( $nav_title_len ) && strlen( $post_title ) > $nav_title_len ) {
              $post_title = trim( substr( $post_title, 0, ( $nav_title_len - 1 ) ) ) . '&hellip;';
            }

            $nav_items[] = '<span class="' . $blueprints_navigator . '">' . $post_title . '</span>';
            break;

          case 'thumbs':

            $thumb       = '<img src="https://loremflickr.com/' . self::get_thumbsize( 'w' ) . '/' . self::get_thumbsize( 'h' ) . '/' . $arc_query[ $j ]['image']['original'] . '" class="arc-nav-thumb" width="' . self::get_thumbsize( 'w' ) . '" height="' . self::get_thumbsize( 'h' ) . '">';
            $nav_items[] = '<span class="' . $blueprints_navigator . '" title="' . $arc_query[ $j ]['title']['title'] . '">' . $thumb . '</span>';
            break;

          case 'bullets':
          case 'numbers':
          case 'buttons':
            //No need for content on these
            $nav_items[] = '';
            break;

        }
      }

      return $nav_items;

    }


    /**
     * @param $dim
     *
     * @return int|mixed
     */
    protected function get_thumbsize( $dim ) {

      // $dim for later development with rectangular thumbs
      $thumbsize = 60;
      if ( ! empty( $this->build->blueprint['_blueprints_navigator-thumb-dimensions']['width'] ) && $dim === 'w' ) {
        $thumbsize = str_replace( array( 'px' ), '', $this->build->blueprint['_blueprints_navigator-thumb-dimensions']['width'] );

      } elseif ( ! empty( $this->build->blueprint['_blueprints_navigator-thumb-dimensions']['height'] ) && $dim === 'h' ) {
        $thumbsize = str_replace( array( 'px' ), '', $this->build->blueprint['_blueprints_navigator-thumb-dimensions']['height'] );
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
    public function build_meta_header_footer_groups( $panel_def, $section_panel_settings ) {
      //replace meta1innards etc
      $meta = array_pad( array(), 3, NULL );
      foreach ( $meta as $key => $value ) {
        $i = $key + 1;
//        $meta[ $key ]             = preg_replace('/%(\\w*)%/u', '{{$1}}', (!empty($section_panel_settings[ '_panels_design_meta' . $i . '-config' ]) ? $section_panel_settings[ '_panels_design_meta' . $i . '-config' ] : null));
        $first_pass               = preg_replace( '/%(\\w|[\\:\\-])*%/uiUmx', '{{$0}}', ( ! empty( $section_panel_settings[ '_panels_design_meta' . $i . '-config' ] ) ? strip_tags( $section_panel_settings[ '_panels_design_meta' . $i . '-config' ], '<p><span><br><strong><em><a>' ) : NULL ) );
        $meta[ $key ]             = preg_replace( "/%(.*)%/uiUmx", "$1", $first_pass );
        $panel_def[ 'meta' . $i ] = str_replace( '{{meta' . $i . 'innards}}', $meta[ $key ], $panel_def[ 'meta' . $i ] );
        $panel_def[ 'meta' . $i ] = str_replace( '{{date}}', $panel_def['datetime'], $panel_def[ 'meta' . $i ] );
        $panel_def[ 'meta' . $i ] = str_replace( '{{author}}', $panel_def['author'], $panel_def[ 'meta' . $i ] );
        $panel_def[ 'meta' . $i ] = str_replace( '{{email}}', $panel_def['email'], $panel_def[ 'meta' . $i ] );
        $panel_def[ 'meta' . $i ] = str_replace( '{{categories}}', $panel_def['categories'], $panel_def[ 'meta' . $i ] );
        $panel_def[ 'meta' . $i ] = str_replace( '{{tags}}', $panel_def['tags'], $panel_def[ 'meta' . $i ] );
// TODO: This maybe meant to be editlink
//        $panel_def[ 'meta' . $i ] = str_replace('{{edit}}', $panel_def[ 'edit' ], $panel_def[ 'meta' . $i ]);
      }


      $panel_layout = json_decode( $section_panel_settings['_panels_design_preview'], TRUE );

      $header_state  = FALSE;
      $footer_state  = FALSE;
      $seen_all_body = 0;
      $last_hf_key   = '';

      $max_body = (int) $panel_layout['excerpt']['show'] + (int) $panel_layout['content']['show'] + (int) $panel_layout['custom1']['show'] + (int) $panel_layout['custom2']['show'] + (int) $panel_layout['custom3']['show'] + ( (int) $panel_layout['image']['show'] * (int) ( $section_panel_settings['_panels_design_feature-location'] === 'components' ) );

      $header_open  = empty( $section_panel_settings['_panels_design_components-headers-footers'] ) ? '' : '<header class="entry-header">';
      $footer_open  = empty( $section_panel_settings['_panels_design_components-headers-footers'] ) ? '' : '<footer class="entry-header">';
      $header_close = empty( $section_panel_settings['_panels_design_components-headers-footers'] ) ? '' : '</header>';
      $footer_close = empty( $section_panel_settings['_panels_design_components-headers-footers'] ) ? '' : '</footer>';

      foreach ( (array) $panel_layout as $key => $value ) {
        if ( $value['show'] ) {
          if ( ( $key === 'title' || $key === 'meta1' || $key == 'meta2' || $key === 'meta3' ) ) {
            $last_hf_key = $key;
          }
          switch ( TRUE ) {

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
            case $key === 'image' && ( $section_panel_settings['_panels_design_feature-location'] === 'components' ):
              // TODO: We need to work out a method of wrapping content in a div that copes with some content eg images not always being there.
              if ( $header_state === 'open' ) {
                $header_state              = 'closed';
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
      switch ( $this->section['_panels_design_link-image'] ) {
        case 'page':
        case 'url':
          $link = ( 'url' === $this->section['_panels_design_link-image'] ) ? '<a href="' . $this->section['_panels_design_link-image-url'] . '" title="' . $this->section['_panels_design_link-image-url-tooltip'] . '">' : $postlink;
          break;
        case 'destination-url':
          $destination_url    = get_post_meta( $this->data['image']['id'], '_gallery_link_url', TRUE );
          $destination_target = get_post_meta( $this->data['image']['id'], '_gallery_link_target', TRUE );
          $link               = ! empty( $destination_url ) ? '<a href="' . $destination_url . '" title="' . $this->section['_panels_design_link-image-url-tooltip'] . '" ' . ( ! empty( $destination_target ) ? 'target="' . $destination_target . '" rel="noopener"' : '' ) . '>' : '';
          break;
        case 'showcase-url':
          // pzarc_showcase-url
          $showcase_url = get_post_meta( get_the_id(), 'pzarc_showcase-url', TRUE );
//          $showcase_target = get_post_meta( $this->data['image']['id'], '_gallery_link_target', TRUE );
          $showcase_target = '_blank';
          $link            = ! empty( $showcase_url ) ? '<a href="' . $showcase_url . '" title="' . get_the_title() . '" ' . ( ! empty( $showcase_target ) ? 'target="' . $showcase_target . '" rel="noopener"' : '' ) . '>' : '';
          break;
        case 'original':
          if ( empty( $this->section['_panels_design_alternate-lightbox'] ) ) {
            wp_enqueue_script( 'js-magnific' );
            wp_enqueue_script( 'js-magnific-arc' );
            wp_enqueue_style( 'css-magnific' );

            $link = '<a class="lightbox lightbox-' . $rsid . ' ' . $location . '" href="' . $this->data['image']['original'][0] . '" title="' . $this->data['title']['title'] . '" >';
          } else {
            $link = '<a class="lightbox-' . $rsid . ' ' . $location . '" href="' . $this->data['image']['original'][0] . '" title="' . $this->data['title']['title'] . '" rel="lightbox">';
          }
          break;
      }

      return $link;
    }

  }
