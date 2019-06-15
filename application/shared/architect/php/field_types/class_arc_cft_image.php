<?php
  /**
   * Name: class_arc_cft_image.php
   * Author: chrishoward
   * Date: 10/6/19
   * Purpose:
   */

class arc_cft_image extends arc_custom_fields {


  function get(){
    // Image settings
    $this->data ['image-quality']        = $this->section[ '_panels_design_cfield-' . $this->i . '-image-quality' ];
    $this->data ['image-max-dimensions'] = $this->section[ '_panels_design_cfield-' . $this->i . '-image-max-dimensions' ];
    $this->data ['image-focal-point']    = $this->section[ '_panels_design_cfield-' . $this->i . '-image-focal-point' ];

  }
  function render() {
    $this->content = '';

    if ( $data['field-source'] = 'acf' ) {
      if ( function_exists( 'get_field' ) ) {
        $acf_image     = get_field( $this->data['name'] );
        $acf_image_url = $acf_image['url'];
      } elseif ( is_numeric( $this->data['value'] ) ) {
        $acf_image     = wp_get_attachment_image_src( $this->data['value'] );
        $acf_image_url = $acf_image[0];
      }
      if ( function_exists( 'bfi_thumb' ) ) {

        $this->content = '<img src="' . bfi_thumb( $acf_image_url, array(
                'quality' => ( ! empty( $this->data['image-quality'] ) ? $this->data['image-quality'] : 82 ),
            ) ) . '">';
      } else {
        $this->content = '<img src="' . $acf_image_url . '">';
      }
    } else {
      if ( function_exists( 'bfi_thumb' ) ) {

        $this->content = '<img src="' . bfi_thumb( $this->data['value'], array(
                'quality' => ( ! empty( $this->data['image-quality'] ) ? $this->data['image-quality'] : 82 ),
            ) ) . '">';
      } else {
        $this->content = '<img src="' . $this->data['value'] . '">';
      }

    }

    return $this->content;
  }
}
