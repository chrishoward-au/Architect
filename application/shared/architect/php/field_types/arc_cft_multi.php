<?php
  /**
   * Name: class_arc_cft_multi.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


  function arc_cft_multi_get( &$i, &$section, &$post, &$postmeta, $data ) {
    $arc_data_values = array();
    if ( $data['data']['field-source'] == 'acf' ) {
      switch ( $data['meta']['acf_settings']['type'] ) {
        case 'taxonomy':
          switch ( $data['meta']['acf_settings']['return_format'] ) {
            case'object':
              foreach ( $data['data']['value'] as $dk => $dv ) {
                $arc_data_values[ $dv->term_id ] = $dv->name;
              }
              break;
            case 'id':
              $arc_data_terms = get_terms( $data['meta']['acf_settings']['taxonomy'] );
              foreach ( $arc_data_terms as $dk => $dv ) {
                if ( in_array( $dv->term_id, $data['data']['value'] ) ) {
                  $arc_data_values[ $dv->term_id ] = $dv->name;
                  // break;
                }
              }
              break;
          }
          break;

        case 'select':
        case 'radio':
        case 'checkbox':
          if ( ! empty( $data['data']['value'] ) ) {
            if ( ! is_array( $data['data']['value'] ) || $data['meta']['acf_settings']['type']=='radio') {
              $data['data']['value'] = array( $data['data']['value'] );
            }
            switch ( $data['meta']['acf_settings']['return_format'] ) {
              case 'value':
                foreach ( $data['data']['value'] as $dk => $dv ) {
                  $arc_data_values[ $dv ] = $data['meta']['acf_settings']['choices'][ $dv ];
                }
                break;
              case 'label':
                foreach ( $data['data']['value'] as $dk => $dv ) {
                  if ( ( $array_search = array_search( $dv, $data['meta']['acf_settings']['choices'] ) ) ) {
                    $arc_data_values[ $array_search ] = $dv;
                  }
                }
                break;
              case 'array':
                foreach ( $data['data']['value'] as $dk => $dv ) {
                  $arc_data_values[ $dv['value'] ] = $dv['label'];
                }
                break;
            }
          }
          break;

        default:
          $arc_data_values = NULL; // None of the other ACF fields support multivalues

      }
    } else {
      $arc_data_values_temp = maybe_unserialize( $data['data']['value'] );
      $arc_data_values_temp = !is_array($arc_data_values_temp)?array($data['data']['value']):$arc_data_values_temp;
      if ( ! empty( $section[ '_panels_design_cfield-' . $i . '-multi-source' ] ) ) {
        $arc_data_terms = get_terms( $section[ '_panels_design_cfield-' . $i . '-multi-source' ] );
        foreach ( $arc_data_terms as $dk => $dv ) {
          if ( in_array( $dv->term_id, $arc_data_values_temp ) ) {
            $arc_data_values[ $dv->term_id ] = $dv->name;
            // break;
          }
        }

      } else {
        foreach ( $arc_data_values_temp as $dk => $dv ) {
          $arc_data_values[ $dv ] = $dv;
        }
      }

    }


    ksort( $arc_data_values );
    $content       = '';
    $arc_separator = $section[ '_panels_design_cfield-' . $i . '-multi-separator' ];
    if ( ! empty( $arc_data_values ) ) {
      foreach ( $arc_data_values as $k => $v ) {
        // add fields for html tag, separator, prefix, and suffix
        $arc_separator = ArcFun::is_last( $arc_data_values, $k ) ? '' : $arc_separator;
        $content       .= '<span class="arc-multi-value">' . $v . $arc_separator . '</span>';
      }
    }
    $data['data']['value'] = $content;

    return $data;


  }
