<?php
  /**
   * Name: class_arc_cft_text.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


  function arc_cft_text_get( &$i, &$section, &$post, &$postmeta, $data ) {

    $data['data']['value'] = maybe_unserialize( $data['data']['value'] );
    switch ( TRUE ) {
      case ( is_string( $data['data']['value'] ) ) :
        if ( empty( $section['_panels_design_process-custom-field-shortcodes'] ) || $section['_panels_design_process-custom-field-shortcodes'] === 'process' ) {
          $data['data']['value'] = do_shortcode( $data['data']['value'] );
        } else {
          $data['data']['value'] = strip_shortcodes( $data['data']['value'] );
        }
        break;
      case ( is_array( $data['data']['value'] ) ) :
        $data['data']['value'] = implode( ', ', $data['data']['value'] );
        $data['data']['value'] = ( $data['data']['value'] == 'Array' ) ? __( 'This value field is not displayed with the Text type. Use Multivalue.', 'pzaarchitect' ) : $data['data']['value'];
        break;
      case ( is_bool( $data['data']['value'] ) ) :
        $data['data']['value'] = $data['data']['value'] ? 'True' : 'False';
        break;
    }

    return $data;


  }
