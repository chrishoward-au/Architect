<?php
  /**
   * Name: class_arc_cft_image.php
   * Author: chrishoward
   * Date: 10/6/19
   * Purpose:
   */

  class arc_cft_image extends arc_custom_fields {


    function __construct( $i, $section, &$post, &$postmeta ) {
      self::get( $i, $section, $post, $postmeta );
    }

    function get( &$i, &$section, &$post, &$postmeta ) {
      $this->data ['image-quality']        = isset( $section[ '_panels_design_cfield-' . $i . '-image-quality' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-quality' ] : 85;
      $this->data ['image-max-dimensions'] = isset( $section[ '_panels_design_cfield-' . $i . '-image-max-dimensions' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-max-dimensions' ] : array( 'width' => 100, 'height' => 100 );
      $this->data ['image-focal-point']    = isset( $section[ '_panels_design_cfield-' . $i . '-image-focal-point' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-focal-point' ] : array( 50, 10 );

      $image_url = $this->data['value'];

      $image_id = '';
      if ( $this->data['field-source'] == 'acf' ) {

        switch ( TRUE ) {
          case( is_Array( $this->data['value'] ) ):
            $image_url = $this->data['value']['url'];
            $image_id  = $this->data['value']['id'];
            break;
          case( is_numeric( $this->data['value'] ) ):
            $image_id  = $this->data['value'];
            $acf_image = wp_get_attachment_image_src( $this->data['value'], 'full' );
            $image_url = $acf_image[0];
            break;
          case( is_string( $this->data['value'] ) ):
            $possible_id = attachment_url_to_postid( $this->data['value'] );
            $image_id    = $possible_id ? $possible_id : 0;
            $image_url   = $this->data['value'];
            break;
          default:
            $image_url = '';
        }
      }

      if ( function_exists( 'bfi_thumb' ) ) {
        $focal_point           = $image_id ? ArcFun::get_focal_point( $image_id ) : array( 'focal_point' => array( 50, 10 ) );
        $this->meta['width']   = intval( $this->data['image-max-dimensions']['width'] );
        $this->meta['height']  = intval( $this->data['image-max-dimensions']['height'] );
        $this->meta['quality'] = ( ! empty( $this->data['image-quality'] ) ? $this->data['image-quality'] : 82 );
        $this->meta['crop']    = (int) intval( $focal_point['focal_point'][0] ) . 'x' . (int) $focal_point['focal_point'][1] . 'x' . $this->data ['image-focal-point'];


        $this->data['value'] = bfi_thumb( $image_url, array(
            'width'   => $this->meta['width'],
            'height'  => $this->meta['height'],
            'crop'    => $this->meta['crop'],
            'quality' => $this->meta['quality'],
        ) );
      } else {
        $this->data['value'] = $image_url;
      }

    }

    function render() {
      // TODO:neeed to get alt text
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        $content = '<img width="' . $this->meta['width'] . '" height="' . $this->meta['height'] . '" src="' . $this->data['value'] . '" class="attachment-' . $this->meta['width'] . 'x' . $this->meta['height'] . 'x1x' . $this->meta['crop'] . 'x' . $this->meta['quality'] . '" alt="">';
      }

      return $content;
    }
  }
