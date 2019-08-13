<?php
  /**
   * Name: class_arc_cft_map.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */




    function arc_cft_map_get( &$i, &$section, &$post, &$postmeta,$data ) {
      $data['meta']['is_address'] = ! empty( $section[ '_panels_design_cfield-' . $i . '-map-is-address' ] ) ? $section[ '_panels_design_cfield-' . $i . '-map-is-address' ] : 'no';
      $data['meta']['use']        = ! empty( $section[ '_panels_design_cfield-' . $i . '-display-map-using' ] ) ? $section[ '_panels_design_cfield-' . $i . '-display-map-using' ] : 'acf';

      if ( $data['data']['field-source'] == 'acf' && $data['meta']['is_address'] != 'yes'  && function_exists('get_field_object')) {


        $defaults            = array( 'address' => '10 Collins Street, Melbourne VIC, Australia', 'lat' => '-37.8138267', 'lng' => '144.97372799999994' );
        $data['data']['value'] = is_array( $data['data']['value'] ) ? $data['data']['value'] : array( 'address' => '', 'lat' => '', 'lng' => '' );
        if ( empty( $data['data']['value']['address'] ) ) {
          $data['data']['value']['address'] = empty( $section[ '_panels_design_cfield-' . $i . '-default-map-address' ] ) ? $defaults['address'] : $section[ '_panels_design_cfield-' . $i . '-default-map-address' ];
        }
        if ( empty( $data['data']['value']['lat'] ) ) {
          $data['data']['value']['lat'] = empty( $section[ '_panels_design_cfield-' . $i . '-default-map-latitude' ] ) ? $defaults['lat'] : $section[ '_panels_design_cfield-' . $i . '-default-map-latitude' ];
        }
        if ( empty( $data['data']['value']['lng'] ) ) {
          $data['data']['value']['lng'] = empty( $section[ '_panels_design_cfield-' . $i . '-default-map-longitude' ] ) ? $defaults['lng'] : $section[ '_panels_design_cfield-' . $i . '-default-map-longitude' ];
        }
      } else {
        if ( $data['meta']['is_address'] == 'yes' ) {
          switch ( $data['meta']['use'] ) {
            case ( 'acf' ):
              // Need to convert it to co-ordinates
              break;
            case ( 'leaflet' ):
              $data['data']['value'] = '[leaflet-map address="' . $data['data']['value'] . '" zoomcontrol zoom=17][leaflet-marker address="' . $data['data']['value'] . '" visible]' . $data['data']['value'] . '[/leaflet-marker]';
              break;
          }
        }
      }
 //     var_dump($data);
      $content = '';
      if ( $data['data']['field-source'] == 'acf' && $data['meta']['is_address'] != 'yes' ) {

        if ( ! empty( $data['data']['value'] ) && isset( $data['data']['value']['lat'] ) && isset( $data['data']['value']['lng'] ) ) {

          $content = '<div class="acf-map"><div class="marker" data-lat="' . $data['data']['value']['lat'] . '" data-lng="' . $data['data']['value']['lng'] . '"></div></div>';

        }
      } else {
        if ( $data['meta']['is_address'] == 'yes' ) {
          switch ( $data['meta']['use'] ) {
            case ( 'acf' ):
              break;
            case ( 'leaflet' ):
              $content = do_shortcode( $data['data']['value']);
              break;
          }
        }

      }
      $data['data']['value']=$content;
      return $data;
    }



