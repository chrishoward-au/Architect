<?php
  /**
   * Name: class_arc_cft_group.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


  function arc_cft_group_get( &$i, &$section, &$post, &$postmeta, $data ) {
//      $data['data']['value']=$data['data']['value']; // For consistency and probably got to change it anyways
    $content         = '';
    $default['data'] = array(
        'date-format'         => $data['data']['date-format'],
        'use-acf-date-format' => $data['data']['use-acf-date-format'],
        'link-field'          => $data['data']['link-field'],
        'link-behaviour'      => $data['data']['link-behaviour'],
        'link-text'           => $data['data']['link-text'],
        'field-source'        => $section[ '_panels_design_cfield-' . $i . '-field-source' ],
    );


    if ( $data['data']['field-source'] == 'acf' ) {

      $acf_settings   = $data['meta']['acf_settings'];
      $fields         = $acf_settings['sub_fields'];
      $field['style'] = ! empty( $acf_settings['wrapper']['width'] ) ? 'style="width:' . $acf_settings['wrapper']['width'] . '%;"' : '';
      $field['class'] = ! empty( $acf_settings['wrapper']['class'] ) ? $acf_settings['wrapper']['class'] : '';
      $field['id']    = ! empty( $acf_settings['wrapper']['id'] ) ? 'id="' . $acf_settings['wrapper']['id'] . '"' : '';
      $content        .= '<div class="arc-cfield-group group-' . $data['data']['name'] . ' acf-layout-' . $acf_settings['layout'] . '">';

      $first_row = '';
      if ( $acf_settings['layout'] == 'table' ) {
        $content .= '<div ' . $field['id'] . ' class="group-header acf-layout-' . $acf_settings['layout'] . ' ' . $field['class'] . '" ' . $field['style'] . '>';
        foreach ( $acf_settings['sub_fields'] as $sub ) {
          $content .= '<div class="header-cell subfield-' . $sub['name'] . '">' . $sub['label'] . '</div>';
        }
        $content   .= '</div>';
        $first_row = ' first-row ';
      } elseif ( $acf_settings['layout'] == 'rows' ) {
        $first_row = ' first-row ';
      }
      // loop through the rows of data

      $content    .= '<div ' . $field['id'] . ' class="group-row group-row-' . $data['data']['name'] . ' ' . $field['class'] . ' ' . $first_row . '" ' . $field['style'] . '>';
      $first_cell = ( $acf_settings['layout'] == 'table' || $acf_settings['layout'] == 'rows' ) ? ' first-cell ' : '';

      foreach ( $fields as $field_value ) {
        $field_name = $field_value['name'];

        $field_data = $default;

        $subfield['style'] = ! empty( $field_value['wrapper']['width'] ) ? 'style="width:' . $field_value['wrapper']['width'] . '%;"' : '';
        $subfield['class'] = ! empty( $field_value['wrapper']['class'] ) ? $field_value['wrapper']['class'] : '';
        $subfield['id']    = ! empty( $field_value['wrapper']['id'] ) ? 'id="' . $field_value['wrapper']['id'] . '"' : '';
        $func              = str_replace( '-', '_', 'arc_cft_' . ArcFun::get_field_type( $field_value['type'] ) ) . '_get';

        // This wil return field type as group
        $field_data['data']['field-type'] = ArcFun::get_field_type( $field_value['type'] );
        $field_data['data']['name']       = $field_value['name'];
        $field_data['data']['value']      = $data['data']['value'][ $field_value['name'] ];

        $field_data['meta']['acf_settings'] = $field_value;
        // Gargh! We need to Render too!
        $field_data = $func( $i, $section, $post, $postmeta, $field_data );
        $content    .= '<div ' . $subfield['id'] . ' class="subfield subfield-' . $field_name . ' ' . $subfield['class'] . $first_cell . '" ' . $subfield['style'] . '>' . $field_data['data']['value'] . '</div>';
        $first_cell = '';
      }
      $content .= '</div>';
      $content .= '</div>';


    } else {

      if ( ! empty( $data['data']['value'] ) ) {
        if ( is_Array( maybe_unserialize( $data ['value'] ) ) ) {
          $data ['value'] = maybe_unserialize( $data ['value'] );

          $content      = '<table class="arc-group-table">';
          $headers_done = FALSE;

          foreach ( $data ['value'] as $key => $value ) {
            $inner_array = maybe_unserialize( $value );
            if ( is_array( $inner_array ) ) {
              if ( ! $headers_done ) {
                foreach ( $inner_array as $k => $v ) {
                  $content      .= '<th>' . ucwords( str_replace( '_', ' ', str_replace( 'ob_', '', $k ) ) ) . '</th>';
                  $headers_done = TRUE;
                }
              }
              $content .= '<tr>';
              foreach ( $inner_array as $k => $v ) {
                $content .= '<td>' . $v . '</td>';
              }
              $content .= '</tr>';
            } else {
              $content = '<td>' . $value . '</td>';
            }
          }
          $content .= '</table>';
        }

      }
    }
    $data['data']['value'] = $content;

    return $data;
  }



