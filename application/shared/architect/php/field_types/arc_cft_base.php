<?php
  /**
   * Name: class_arc_custom_fields.php
   * Author: chrishoward
   * Date: 14/6/19
   * Purpose:
   */

  function arc_cft_base_get( &$i, &$section, &$post, &$postmeta, $data ) {
    /** CUSTOM FIELDS **/

    $data['data']['group']        = $section[ '_panels_design_cfield-' . $i . '-group' ];
    $data['data']['name']         = $section[ '_panels_design_cfield-' . $i . '-name' ];
    $data['data']['field-type']   = $section[ '_panels_design_cfield-' . $i . '-field-type' ];
    $data['data']['field-source'] = $section[ '_panels_design_cfield-' . $i . '-field-source' ];
    $data['data']['wrapper-tag']  = $section[ '_panels_design_cfield-' . $i . '-wrapper-tag' ];
    $data['data']['class-name']   = isset( $section[ '_panels_design_cfield-' . $i . '-class-name' ] ) ? $section[ '_panels_design_cfield-' . $i . '-class-name' ] : '';

    // Date settings
    $data['data']['date-format']         = $section[ '_panels_design_cfield-' . $i . '-date-format' ];
    $data['data']['use-acf-date-format'] = ! ( empty( $section[ '_panels_design_cfield-' . $i . '-use-acf-date-format' ] ) || $section[ '_panels_design_cfield-' . $i . '-use-acf-date-format' ] == 'no' );

    // Link settings
    $data['data']['link-field']     = $section[ '_panels_design_cfield-' . $i . '-link-field' ]; // This will be populated with the actual value later
    $data['data']['link-behaviour'] = isset( $section[ '_panels_design_cfield-' . $i . '-link-behaviour' ] ) ? $section[ '_panels_design_cfield-' . $i . '-link-behaviour' ] : '_self';
    $data['data']['link-text']      = ! empty( $section[ '_panels_design_cfield-' . $i . '-link-text' ] ) ? $section[ '_panels_design_cfield-' . $i . '-link-text' ] : '';

    // Prefix/Suffix
    $params = array(
        'width'   => str_replace( $section[ '_panels_design_cfield-' . $i . '-ps-images-width' ]['units'], '', $section[ '_panels_design_cfield-' . $i . '-ps-images-width' ]['width'] ),
        'height'  => str_replace( $section[ '_panels_design_cfield-' . $i . '-ps-images-height' ]['units'], '', $section[ '_panels_design_cfield-' . $i . '-ps-images-height' ]['height'] ),
        'quality' => ( ! empty( $section['_panels_design_image-quality'] ) ? $section['_panels_design_image-quality'] : 82 ),
    );

    $data['data']['prefix-text']  = ! empty( $section[ '_panels_design_cfield-' . $i . '-prefix-text' ] ) ? '<span class="pzarc-prefix-text">' . $section[ '_panels_design_cfield-' . $i . '-prefix-text' ] . '</span>' : '';
    $data['data']['prefix-image'] = function_exists( 'bfi_thumb' ) ? bfi_thumb( $section[ '_panels_design_cfield-' . $i . '-prefix-image' ]['url'], $params ) : $section[ '_panels_design_cfield-' . $i . '-prefix-image' ]['url'];
    $data['data']['suffix-text']  = ! empty( $section[ '_panels_design_cfield-' . $i . '-suffix-text' ] ) ? '<span class="pzarc-suffix-text">' . $section[ '_panels_design_cfield-' . $i . '-suffix-text' ] . '</span>' : '';
    $data['data']['suffix-image'] = function_exists( 'bfi_thumb' ) ? bfi_thumb( $section[ '_panels_design_cfield-' . $i . '-suffix-image' ]['url'], $params ) : $section[ '_panels_design_cfield-' . $i . '-prefix-image' ]['url'];

    // Get link field if set
    if ( ! empty( $section[ '_panels_design_cfield-' . $i . '-link-field' ] ) ) {
      $link_field = explode( '/', $section[ '_panels_design_cfield-' . $i . '-link-field' ] );
      if ( count( $link_field ) == 1 ) {
        $data['data']['link-field'] = ( ! empty( $postmeta[ $link_field[0] ] ) ? $postmeta[ $link_field[0] ][0] : NULL );
      } elseif ( count( $link_field ) == 2 ) {
        $data['data']['link-field'] = do_shortcode( '[arccf table="' . $link_field[0] . '" field="' . $link_field[1] . '"]' );
      }
    }

    // Get the content
    switch ( $section[ '_panels_design_cfield-' . $i . '-name' ] ) {
      case'post_title':
        $data['data']['value'] = $post->post_title;
        break;
      case'post_excerpt':
        $data['data']['value'] = $post->post_excerpt;
        break;
      case'post_content':
        $data['data']['value'] = $post->post_content;
        break;
      case'post_thumbnail':
        $data['data']['value'] = get_post_thumbnail_id( $post->ID );
        break;
      case'post_date':
        $data['data']['value'] = $post->post_date;
        break;
      case'post_author':
        $data['data']['value'] = $post->post_author;
        break;
      case'use_empty':
        $data['data']['value'] = '{{empty}}';
        break;
      case'specific_code':
        $data['data']['value'] = strip_tags( $section[ '_panels_design_cfield-' . $i . '-code' ], '<br><p><a><strong><em><ul><ol><li><pre><code><blockquote><h1><h2><h3><h4><h5><h6><img>' );
        break;
//           case'tablefield':
//              $tablefield                          = explode( '___', $section[ '_panels_design_cfield-' . $i . '-name-table-field' ] );
//              $data['data']['value'] = do_shortcode( '[arccf table="' . $tablefield[0] . '" field="' . $tablefield[1] . '"]' );
//              break;
      default:
        $custom_field = explode( '/', $section[ '_panels_design_cfield-' . $i . '-name' ] );
        if ( count( $custom_field ) == 1 ) {
          $func = 'arc_get_field_' . $data['data']['field-source'];
          $data = $func( $custom_field[0], $postmeta, $data );
        } elseif ( count( $custom_field ) == 2 ) {
          $data['data']['value'] = do_shortcode( '[arccf table="' . $custom_field[0] . '" field="' . $custom_field[1] . '"]' );
        }
    }
//var_dump($data);
    // TODO : Add other attributes
    return $data;
  }

  /****
   * functions
   */
  function arc_get_field_wp( $arc_field_name, &$postmeta, $data ) {
    $data['data']['value'] = ! empty( $postmeta[ $arc_field_name ] ) ? $postmeta[ $arc_field_name ][0] : NULL;
    $data['meta']          = array( 'acf_settings' => __( 'Not an ACF field', 'pzarchitect' ), 'raw_value' => NULL );

    return $data;
  }

  function arc_get_field_wooc( $arc_field_name, &$postmeta, $data ) {
    $data['data']['value'] = ! empty( $postmeta[ $arc_field_name ] ) ? $postmeta[ $arc_field_name ][0] : NULL;
    $data['meta']          = array( 'acf_settings' => __( 'Not an ACF field', 'pzarchitect' ), 'raw_value' => NULL );

    return $data;
  }

  function arc_get_field_acf( $arc_field_name, &$postmeta, $data, $subfield = FALSE ) {
    if ( ! empty( $arc_field_name ) && function_exists( 'get_field' ) ) {
      if ( $subfield ) {
        $data['data']['value']        = get_sub_field( $arc_field_name );
        $data['meta']['acf_settings'] = get_sub_field_object( $arc_field_name );
        $data['meta']['raw_value']    = get_sub_field( $arc_field_name, FALSE );

      } else {
        $data['data']['value']        = get_field( $arc_field_name );
        $data['meta']['acf_settings'] = get_field_object( $arc_field_name );
        $data['meta']['raw_value']    = get_field( $arc_field_name, FALSE, FALSE );
      }
    }

    return $data;
  }


