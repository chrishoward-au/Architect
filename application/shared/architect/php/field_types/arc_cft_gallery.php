<?php
  /**
   * Name: class_arc_cft_gallery.php
   * Author: chrishoward
   * Date: 10/6/19
   * Purpose:
   */


  function arc_cft_gallery_get( &$i, &$section, &$post, &$postmeta, $data ) {
    $data['data']['image-quality']        = isset( $section[ '_panels_design_cfield-' . $i . '-image-quality' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-quality' ] : 85;
    $data['data']['image-max-dimensions'] = isset( $section[ '_panels_design_cfield-' . $i . '-image-max-dimensions' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-max-dimensions' ] : array( 'width' => 100, 'height' => 100 );
    $data['data']['image-focal-point']    = isset( $section[ '_panels_design_cfield-' . $i . '-image-focal-point' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-focal-point' ] : array( 50, 10 );

    $data['meta']['width']   = intval( $data['data']['image-max-dimensions']['width'] );
    $data['meta']['height']  = intval( $data['data']['image-max-dimensions']['height'] );
    $data['meta']['quality'] = ( ! empty( $data['data']['image-quality'] ) ? $data['data']['image-quality'] : 82 );
    $data['meta']['crop']    = $data['meta']['width'] . 'x' . $data['meta']['height'];

    // TODO: Add fields for image size, show caption,
    // when not ACF!
    //
    $gallery = array();
    if ( ! is_array( $data['data']['value'] ) ) {
      $data['data']['value'] = maybe_unserialize( $data['data']['value'] );

    }

    if ( $data['data']['field-source'] == 'acf' ) { // @v10.9 In Architect, Galleries are only available to ACF fields.
      foreach ( $data['data']['value'] as $k => $v ) {

        $image_url = '';
        $image_id  = '';
        switch ( $data['data']['field-source'] ) {

          case ( 'acf' ) :

            switch ( TRUE ) {
              case( is_Array( $v ) ):
                $image_url = $v['url'];
                $image_id  = $v['id'];
                break;
              case( is_numeric( $v ) || intval( $v ) > 0 ):
                $image_id  = $v;
                $acf_image = wp_get_attachment_image_src( $v, 'full' );
                $image_url = $acf_image[0];
                break;
              case( is_string( $v ) ):
                $possible_id = attachment_url_to_postid( $v );
                $image_id    = $possible_id ? $possible_id : 0;
                $image_url   = $v;
                break;
              default:
                $image_url = '';
            }
            break;
        }

        if ( function_exists( 'bfi_thumb' ) ) {
          $focal_point     = $image_id ? ArcFun::get_focal_point( $image_id ) : array( 'focal_point' => array( 50, 10 ) );
          $data['meta']['quality'] = ( ! empty( $data['data']['image-quality'] ) ? $data['data']['image-quality'] : 82 );
          $data['meta']['crop']    = (int) intval( $focal_point['focal_point'][0] ) . 'x' . (int) $focal_point['focal_point'][1] . 'x' . $data['data']['image-focal-point'];


          $image_url = bfi_thumb( $image_url, array(
              'width'   => $data['meta']['width'],
              'height'  => $data['meta']['height'],
              'crop'    => $data['meta']['crop'],
              'quality' => $data['meta']['quality'],
          ) );
        } else {
//            var_dump( $data['data']['value'] );
        }
        $gallery[ $k ] = $image_url;
      }
    }
    $data['data']['value'] = $gallery;

    // TODO:neeed to get alt text
    // Need to have option to do a Architect shortcode!
    $content = '';
    if ( ! empty( $data['data']['value'] ) ) {
      foreach ( $data['data']['value'] as $k => $url ) {
        $content .= '<img width="' . $data['meta']['width'] . '" height="' . $data['meta']['height'] . '" src="' . $url . '" class="pzarc-cf-gallery-image-' . $k . ' attachment-' . $data['meta']['width'] . 'x' . $data['meta']['height'] . 'x1x' . $data['meta']['crop'] . 'x' . $data['meta']['quality'] . '" alt="">';
      }
    }
    $data['data']['value']=$content;
    return $data;
  }

