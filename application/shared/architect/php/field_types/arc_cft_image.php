<?php
  /**
   * Name: class_arc_cft_image.php
   * Author: chrishoward
   * Date: 10/6/19
   * Purpose:
   */


  function arc_cft_image_get( &$i, &$section, &$post, &$postmeta, $data ) {
    $data['data']['image-quality']        = isset( $section[ '_panels_design_cfield-' . $i . '-image-quality' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-quality' ] : 85;
    $data['data']['image-max-dimensions'] = isset( $section[ '_panels_design_cfield-' . $i . '-image-max-dimensions' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-max-dimensions' ] : array( 'width' => 100, 'height' => 100 );
    $data['data']['image-focal-point']    = isset( $section[ '_panels_design_cfield-' . $i . '-image-focal-point' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-focal-point' ] : array( 50, 10 );
    $data['meta']['width']                = intval( $data['data']['image-max-dimensions']['width'] );
    $data['meta']['height']               = intval( $data['data']['image-max-dimensions']['height'] );
    $data['meta']['quality']              = ( ! empty( $data['data']['image-quality'] ) ? $data['data']['image-quality'] : 82 );
    $data['meta']['crop']                 = $data['meta']['width'] . 'x' . $data['meta']['height'];

    $image_url = $data['data']['value'];

    $image_id = '';
    if ( $data['data']['field-source'] == 'acf' && function_exists( 'get_field_object' ) ) {

      switch ( TRUE ) {
        case( is_Array( $data['data']['value'] ) ):
          $image_url = $data['data']['value']['url'];
          $image_id  = $data['data']['value']['id'];
          break;
        case( is_numeric( $data['data']['value'] ) ):
          $image_id  = $data['data']['value'];
          $acf_image = wp_get_attachment_image_src( $data['data']['value'], 'full' );
          $image_url = $acf_image[0];
          break;
        case( is_string( $data['data']['value'] ) ):
          $possible_id = attachment_url_to_postid( $data['data']['value'] );
          $image_id    = $possible_id ? $possible_id : 0;
          $image_url   = $data['data']['value'];
          break;
        default:
          $image_url = '';
      }
    }
//var_dump($image_url,$image_id);
    if ( function_exists( 'bfi_thumb' ) ) {
      $focal_point  = $image_id ? ArcFun::get_focal_point( $image_id ) : array( 'focal_point' => array( 50, 10 ) );
      $data['meta']['crop'] = (int) intval( $focal_point['focal_point'][0] ) . 'x' . (int) $focal_point['focal_point'][1] . 'x' . $data['data']['image-focal-point'];

      $data['data']['value'] = bfi_thumb( $image_url, array(
          'width'   => $data['meta']['width'],
          'height'  => $data['meta']['height'],
          'crop'    => $data['meta']['crop'],
          'quality' => $data['meta']['quality'],
      ) );
    } else {
      $data['data']['value'] = $image_url;
    }

    // TODO:neeed to get alt text
    if ( ! empty( $data['data']['value'] ) ) {
      $data['data']['value'] = '<img width="' . $data['meta']['width'] . '" height="' . $data['meta']['height'] . '" src="' . $data['data']['value'] . '" class="attachment-' . $data['meta']['width'] . 'x' . $data['meta']['height'] . 'x1x' . $data['meta']['crop'] . 'x' . $data['meta']['quality'] . '" alt="">';
    }
    return $data;
  }
