<?php
  /**
   * Name: class_arc_cft_map.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_map extends arc_custom_fields {

    function __construct( $i, $section, &$post, &$postmeta ) {
      parent::__construct( $i, $section, $post, $postmeta );
      self::get( $i, $section, $post, $postmeta );
    }

    function get( &$i, &$section, &$post, &$postmeta ) {
      $this->meta['is_address'] = ! empty( $section[ '_panels_design_cfield-' . $i . '-map-is-address' ] ) ? $section[ '_panels_design_cfield-' . $i . '-map-is-address' ] : 'no';
      $this->meta['use']        = ! empty( $section[ '_panels_design_cfield-' . $i . '-display-map-using' ] ) ? $section[ '_panels_design_cfield-' . $i . '-display-map-using' ] : 'acf';

      if ( $this->data['field-source'] == 'acf' && $this->meta['is_address'] != 'yes'  && function_exists('get_field_object')) {


        $defaults            = array( 'address' => '10 Collins Street, Melbourne VIC, Australia', 'lat' => '-37.8138267', 'lng' => '144.97372799999994' );
        $this->data['value'] = is_array( $this->data['value'] ) ? $this->data['value'] : array( 'address' => '', 'lat' => '', 'lng' => '' );
        if ( empty( $this->data['value']['address'] ) ) {
          $this->data['value']['address'] = empty( $section[ '_panels_design_cfield-' . $i . '-default-map-address' ] ) ? $defaults['address'] : $section[ '_panels_design_cfield-' . $i . '-default-map-address' ];
        }
        if ( empty( $this->data['value']['lat'] ) ) {
          $this->data['value']['lat'] = empty( $section[ '_panels_design_cfield-' . $i . '-default-map-latitude' ] ) ? $defaults['lat'] : $section[ '_panels_design_cfield-' . $i . '-default-map-latitude' ];
        }
        if ( empty( $this->data['value']['lng'] ) ) {
          $this->data['value']['lng'] = empty( $section[ '_panels_design_cfield-' . $i . '-default-map-longitude' ] ) ? $defaults['lng'] : $section[ '_panels_design_cfield-' . $i . '-default-map-longitude' ];
        }
      } else {
        if ( $this->meta['is_address'] == 'yes' ) {
          switch ( $this->meta['use'] ) {
            case ( 'acf' ):
              // Need to convert it to co-ordinates
              break;
            case ( 'leaflet' ):
              $this->data['value'] = '[leaflet-map address="' . $this->data['value'] . '" zoomcontrol zoom=17][leaflet-marker address="' . $this->data['value'] . '" visible]' . $this->data['value'] . '[/leaflet-marker]';
              break;
          }
        }
      }
    }

    function render() {
      var_dump($this->meta);
      $content = '';
      if ( $this->data['field-source'] == 'acf' && $this->meta['is_address'] != 'yes' ) {

        if ( ! empty( $this->data['value'] ) && isset( $this->data['value']['lat'] ) && isset( $this->data['value']['lng'] ) ) {

          $content = '<div class="acf-map"><div class="marker" data-lat="' . $this->data['value']['lat'] . '" data-lng="' . $this->data['value']['lng'] . '"></div></div>';

        }
      } else {
        if ( $this->meta['is_address'] == 'yes' ) {
          switch ( $this->meta['use'] ) {
            case ( 'acf' ):
              break;
            case ( 'leaflet' ):
              $content = do_shortcode( $this->data['value']);
              break;
          }
        }

      }

      return $content;
    }


  }
