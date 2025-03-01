<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_panel_rss.php
   * User: chrishoward
   * Date: 19/10/14
   * Time: 11:03 PM
   */
  class arc_Panel_RSS extends arc_Panel_Generic {

    // This one will need a lot of stuff!
    public function __construct( &$build ) {

      parent::__construct( $build );
    }

    function get_title( &$post ) {
      $this->data['title']['title'] = $post['title'];
    }

    public function get_content( &$post ) {
      /** CONTENT */
      $this->data['content'] = $post['content'];
    }

    public function get_excerpt( &$post ) {

      switch ( $this->section["_panels_design_excerpts-trim-type"] ) {
        case 'characters':
          $this->data['excerpt'] = wp_html_excerpt( $post['content'], strlen( $post['content'] ) );
          $cutoff                = min( $this->section["_panels_design_excerpts-word-count"], strlen( $this->data['excerpt'] ) );
          $this->data['excerpt'] = wp_html_excerpt( $post['content'], $cutoff, pzarc_make_excerpt_more( $this->section, $post ) );
          break;

        case 'paragraphs':
          $this->data['excerpt'] = '';
          $the_content           = array_filter( wp_html_split( strip_tags( $post['content'], '<p><br>' ) ), 'pzarc_strip_empty_lines', ARRAY_FILTER_USE_BOTH );
          $i                     = 1;
          foreach ( $the_content as $key => $value ) {

            if ( ++ $i > $this->section["_panels_design_excerpts-word-count"] ) {
              if ( count( $this->section["_panels_design_excerpts-word-count"] ) < count( $the_content ) ) {
                $this->data['excerpt'] .= '<p>' . trim( strip_tags( $value ) ) . pzarc_make_excerpt_more( $this->section, $post ) . '</p>';
              } else {
                $this->data['excerpt'] .= '<p>' . trim( strip_tags( $value ) ) . '</p>';
              }
              break;
            } else {
              $this->data['excerpt'] .= '<p>' . trim( strip_tags( $value ) ) . '</p>';
            }

          }
          break;

        case 'words':
        default:
          $this->data['excerpt'] = implode( ' ', array_slice( explode( ' ', wp_html_excerpt( $post['content'], strlen( $post['content'] ) ) ), 0, $this->section["_panels_design_excerpts-word-count"] ) );

          if ( strlen( $this->data['excerpt'] ) < strlen( wp_html_excerpt( $post['content'], strlen( $post['content'] ) ) ) ) {
            $this->data['excerpt'] .= pzarc_make_excerpt_more( $this->section, $post );
          }

          break;

      }


    }

    public function get_meta( &$post ) {
      $meta_string = $this->toshow['meta1']['show'] ? $this->section['_panels_design_meta1-config'] : '';
      $meta_string .= $this->toshow['meta2']['show'] ? $this->section['_panels_design_meta2-config'] : '';
      $meta_string .= $this->toshow['meta3']['show'] ? $this->section['_panels_design_meta3-config'] : '';

      /** META */
      if ( strpos( $meta_string, '%id%' ) !== FALSE ) {
        $this->data['meta']['id'] = $post['id'];
        $this->data['meta']['id'] .= ' blueprint=' . $this->build->blueprint['blueprint-id'];
      }
      if ( strpos( $meta_string, '%date%' ) !== FALSE ) {
        $this->data['meta']['datetime'] = $post['date'];
//        $this->data[ 'meta' ][ 'fdatetime' ] = date_i18n(strip_tags($this->section[ '_panels_design_meta-date-format' ]), str_replace(',', ' ', strtotime(get_the_date())));
//        $this->data['meta']['fdatetime'] = date_i18n( strip_tags( $this->section['_panels_design_meta-date-format'] ), strtotime( str_replace( ',', ' ', get_the_date() ) ) );
        $this->data['meta']['fdatetime'] = wp_date( strip_tags( $this->section['_panels_design_meta-date-format'] ), get_post_timestamp() ); //v11.3
      }
//      if (strpos($meta_string, '%categories%') !== FALSE) {
////      $this->data['meta']['categorieslinks'] = get_the_category_list(', ');
//        $this->data['meta']['categories'] = pzarc_tax_string_list($post['categories'], 'category-', '', ' ');
//      }
      if ( strpos( $meta_string, '%tags%' ) !== FALSE ) {
//      $this->data['meta']['tagslinks'] = get_the_tag_list(null, ', ');
        $this->data['meta']['tags'] = pzarc_tax_string_list( $post['tags'], 'tag-', '', ' ' );
      }
//    if (strpos($meta_string, '%author%') !== false) {
//
//      $this->data['meta']['authorlink'] = get_author_posts_url(get_the_author_meta('ID'));
//      $this->data['meta']['authorname'] = sanitize_text_field(get_the_author_meta('display_name'));
//      $rawemail                         = sanitize_email(get_the_author_meta('user_email'));
//      $encodedmail                      = '';
//      for ($i = 0; $i < strlen($rawemail); $i++) {
//        $encodedmail .= "&#" . ord($rawemail[$i]) . ';';
//      }
//      $this->data['meta']['authoremail'] = $encodedmail;
//    }
//    if (!empty($this->section['_panels_design_avatar']) && $this->section['_panels_design_avatar'] !== 'none') {
//      if ($this->section['_panels_design_avatar'] === 'before') {
//        $this->data['meta']['avatarb'] = get_avatar(get_the_author_meta('ID'), (!empty($this->section['_panels_design_avatar-size']) ? $this->section['_panels_design_avatar-size'] : 96));
//      }
//      else {
//        $this->data['meta']['avatara'] = get_avatar(get_the_author_meta('ID'), (!empty($this->section['_panels_design_avatar-size']) ? $this->section['_panels_design_avatar-size'] : 96));
//      }
//    }
//    $this->data['meta']['comments-count'] = get_comments_number();
//
//    // Extract and find any custom taxonomies - i.e. preceded with ct:
//    if (strpos($meta_string, 'ct:') !== false) {
//      $this->data['meta']['custom'][1] = $this->toshow['meta1']['show'] ? pzarc_get_post_terms(get_the_id(), $this->section['_panels_design_meta1-config']) : '';
//      $this->data['meta']['custom'][2] = $this->toshow['meta2']['show'] ? pzarc_get_post_terms(get_the_id(), $this->section['_panels_design_meta2-config']) : '';
//      $this->data['meta']['custom'][3] = $this->toshow['meta3']['show'] ? pzarc_get_post_terms(get_the_id(), $this->section['_panels_design_meta3-config']) : '';
//    }
//  }
    }

    function get_image( &$post ) {
      $width           = (int) str_replace( 'px', '', $this->section['_panels_design_image-max-dimensions']['width'] );
      $height          = (int) str_replace( 'px', '', $this->section['_panels_design_image-max-dimensions']['height'] );
      $max_wh          = $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_design_image-max-dimensions'];
      $cropping_method = $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_settings_image-focal-point'];
      $focal_point     = explode( ',', pzarc_get_option( 'architect_focal_point_default', '50,10' ) );

      if ( in_array( $cropping_method, array( 'topcentre', 'middlecentre', 'bottomcentre' ) ) ) {
        $focal_point[0] = 50;
        $focal_point[1] = array_search( $cropping_method, array( 0 => 'topcentre', 50 => 'middlecentre', 100 => 'bottomcentre' ) );
      }
      $crop = $focal_point[0] . 'x' . $focal_point[1] . 'x' . $this->section['_panels_settings_image-focal-point'];
      switch ( $cropping_method ) {
        case 'scale':
          $wh = 'width="' . $max_wh['width'] . '"';
          break;
        case 'scale_height':
          $wh = 'height="' . $max_wh['height'] . '"';
          break;
        default:
        case 'respect':
        case 'topcentre':
        case 'middlecentre':
        case 'bottomcentre':
        case 'shrink':
        case 'none':
          $wh = 'width="' . $max_wh['width'] . '"';
          $wh .= ' height="' . $max_wh['height'] . '"';
          break;
      }
      // Need to cache the image locally.
      $quality = ( ! empty( $this->section['_panels_design_image-quality'] ) ? $this->section['_panels_design_image-quality'] : 82 );


      $bfi_thumb                          = bfi_thumb( $post['image'], array(
          'width'   => $width,
          'height'  => $height,
          'quality' => $quality,
          'crop'    => $crop,
      ) );
      $this->data['image']['image']       = '<img ' . $wh . ' src="' . $bfi_thumb . '";>';
      $this->data['image']['original'][0] = $post['image'];

    }

    function get_bgimage( &$post ) {
      $width  = (int) str_replace( 'px', '', $this->section['_panels_design_image-max-dimensions']['width'] );
      $height = (int) str_replace( 'px', '', $this->section['_panels_design_image-max-dimensions']['height'] );
      $max_wh = $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_design_image-max-dimensions'];
      switch ( $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_settings_image-focal-point'] ) {
        case 'scale':
          $wh = 'width="' . $max_wh['width'] . '"';
          break;
        case 'scale_height':
          $wh = 'height="' . $max_wh['height'] . '"';
          break;
        default:
        case 'respect':
        case 'centre':
        case 'none':
          $wh = 'width="' . $max_wh['width'] . '"';
          $wh .= ' height="' . $max_wh['height'] . '"';
          break;
      }
      $this->data['bgimage']['thumb']     = '<img ' . $wh . ' src="' . bfi_thumb( $post['image'], array(
              'width'  => $width,
              'height' => $height,
              'crop'   => '50x50x' . $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_settings_image-focal-point'],
          ) ) . '">';
      $this->data['image']['original'][0] = $post['image'];
    }

    public function get_miscellanary( &$post ) {
      global $_architect_options;
      $this->data['inherit-hw-block-type'] = ( ! empty( $_architect_options['architect_hw-content-class'] ) ? 'block-type-content ' : '' );
      $this->data['postid']                = $post['id'];
//      $this->data['poststatus']            = get_post_status();
//      $this->data[ 'posttype' ]    = get_post_type();
      $this->data['posttype']  = 'rssfeed';
      $this->data['permalink'] = $post['permalink'];
//      $post_format               = get_post_format();
//      $this->data ['postformat'] = (empty($post_format) ? 'standard' : $post_format);
    }


    public function loop( $section_no, &$architect, &$panel_class, $class ) {
      parent::loop_from_array( $section_no, $architect, $panel_class, $class );
    }

    public function get_nav_items( $blueprints_navigator, &$arc_query, $nav_labels, $nav_title_len = 0 ) {
      return parent::get_nav_items_from_array( $blueprints_navigator, $arc_query, $nav_labels, $nav_title_len );
    }

  }
