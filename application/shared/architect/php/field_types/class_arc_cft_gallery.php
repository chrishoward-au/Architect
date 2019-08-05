<?php
  /**
   * Name: class_arc_cft_gallery.php
   * Author: chrishoward
   * Date: 10/6/19
   * Purpose:
   */

  class arc_cft_gallery extends arc_cft {


    function __construct( $i, $section, &$post, &$postmeta ) {
      // parent::__construct( $i, $section, $post, $postmeta );
      self::get( $i, $section, $post, $postmeta );
    }

    function get( &$i, &$section, &$post, &$postmeta ) {
      $this->data ['image-quality']        = isset( $section[ '_panels_design_cfield-' . $i . '-image-quality' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-quality' ] : 85;
      $this->data ['image-max-dimensions'] = isset( $section[ '_panels_design_cfield-' . $i . '-image-max-dimensions' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-max-dimensions' ] : array( 'width' => 100, 'height' => 100 );
      $this->data ['image-focal-point']    = isset( $section[ '_panels_design_cfield-' . $i . '-image-focal-point' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-focal-point' ] : array( 50, 10 );

      $this->meta['width']  = intval( $this->data['image-max-dimensions']['width'] );
      $this->meta['height'] = intval( $this->data['image-max-dimensions']['height'] );
      $this->meta['quality'] = ( ! empty( $this->data['image-quality'] ) ? $this->data['image-quality'] : 82 );
      $this->meta['crop']    = $this->meta['width'].'x'.$this->meta['height'];

      // TODO: Add fields for image size, show caption,
      // when not ACF!
      //
      $gallery = array();
      if (!is_array($this->data['value'])){
        $this->data['value']=maybe_unserialize($this->data['value']);

      }

      if ( $this->data['field-source'] == 'acf' ) { // @v10.9 In Architect, Galleries are only available to ACF fields.
        foreach ( $this->data['value'] as $k => $v ) {

          $image_url = '';
          $image_id  = '';
          switch ( $this->data['field-source'] ) {

            case ( 'acf' ) :

              switch ( TRUE ) {
                case( is_Array( $v ) ):
                  $image_url = $v['url'];
                  $image_id  = $v['id'];
                  break;
                case( is_numeric( $v ) || intval($v)>0):
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
            $focal_point           = $image_id ? ArcFun::get_focal_point( $image_id ) : array( 'focal_point' => array( 50, 10 ) );
            $this->meta['quality'] = ( ! empty( $this->data['image-quality'] ) ? $this->data['image-quality'] : 82 );
            $this->meta['crop']    = (int) intval( $focal_point['focal_point'][0] ) . 'x' . (int) $focal_point['focal_point'][1] . 'x' . $this->data ['image-focal-point'];


            $image_url = bfi_thumb( $image_url, array(
                'width'   => $this->meta['width'],
                'height'  => $this->meta['height'],
                'crop'    => $this->meta['crop'],
                'quality' => $this->meta['quality'],
            ) );
          } else {
//            var_dump( $this->data['value'] );
          }
          $gallery[ $k ] = $image_url;
        }
      }
      $this->data['value'] = $gallery;
    }

    function render() {
      // TODO:neeed to get alt text
      // Need to have option to do a Architect shortcode!
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        foreach ($this->data['value'] as $k => $url ){
          $content .= '<img width="' . $this->meta['width'] . '" height="' . $this->meta['height'] . '" src="' . $url . '" class="pzarc-cf-gallery-image-'.$k.' attachment-' . $this->meta['width'] . 'x' . $this->meta['height'] . 'x1x' . $this->meta['crop'] . 'x' . $this->meta['quality'] . '" alt="">';
        }
      }

      return $content;
    }
  }
