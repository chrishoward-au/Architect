<?php
  /**
   * Name: class_arc_cft_image.php
   * Author: chrishoward
   * Date: 10/6/19
   * Purpose:
   */

  class arc_cft_image extends arc_custom_fields {

    function __construct( $i, $section, &$post, &$postmeta ) {
      parent::__construct( $i, $section, $post, $postmeta );
      self::get( $i, $section, $post, $postmeta );
    }

    function get( &$i, &$section, &$post, &$postmeta ) {
      // Image settings
      $this->data ['image-quality']        = isset( $section[ '_panels_design_cfield-' . $i . '-image-quality' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-quality' ] : 85;
      $this->data ['image-max-dimensions'] = isset( $section[ '_panels_design_cfield-' . $i . '-image-max-dimensions' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-max-dimensions' ] : array( 'width' => 100, 'height' => 100 );
      $this->data ['image-focal-point']    = isset( $section[ '_panels_design_cfield-' . $i . '-image-focal-point' ] ) ? $section[ '_panels_design_cfield-' . $i . '-image-focal-point' ] : array( 50, 10 );
      $width                               = $this->data['image-max-dimensions']['width'];
      $height                              = $this->data['image-max-dimensions']['height'];
      $crop                                = is_array( $this->data ['image-focal-point'] ) ? implode( 'x', $this->data ['image-focal-point'] ) : $this->data ['image-focal-point'];
      $quality                             = ( ! empty( $this->data['image-quality'] ) ? $this->data['image-quality'] : 82 );

      if ( $this->data['field-source'] == 'acf' ) {
        if ( function_exists( 'get_field' ) ) {
          $acf_image = get_field( $this->data['name'] );
          $mage_url  = $acf_image['url'];
        } elseif ( is_numeric( $this->data['value'] ) ) {
          $acf_image = wp_get_attachment_image_src( $this->data['value'] );
          $image_url = $acf_image[0];
        }

      } else {
        $image_url = $this->data['value'];
      }

      if ( function_exists( 'bfi_thumb' ) ) {

        $this->data['value'] = bfi_thumb( $image_url, array(
            'width'   => $width,
            'height'  => $height,
            'crop'    => $crop,
            'quality' => $quality,
        ) );
      } else {
        $this->data['value'] = $image_url;
      }

    }

    function render() {
      $content ='';
      if (!empty($this->data['value'])) {
        $width   = $this->data['image-max-dimensions']['width'];
        $height  = $this->data['image-max-dimensions']['height'];
        $crop    = is_array( $this->data ['image-focal-point'] ) ? implode( 'x', $this->data ['image-focal-point'] ) : $this->data ['image-focal-point'];
        $quality = ( ! empty( $this->data['image-quality'] ) ? $this->data['image-quality'] : 82 );


        $content = '<img width="' . $width . '" height="' . $height . '" src="' . $this->data['value'] . '" class="attachment-' . $width . 'x' . $height . 'x1x' . $crop . 'x' . $quality . '" alt="">';
      }

      return $content;
    }
  }
