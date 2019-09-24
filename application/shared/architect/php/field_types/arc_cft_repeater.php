<?php
  /**
   * Name: class_arc_cft_repeater.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */


  function arc_cft_repeater_get( &$i, &$section, &$post, &$postmeta, $data ) {

    $content = '';

    $default['data'] = array(
        'date-format'         => $data['data']['date-format'],
        'use-acf-date-format' => $data['data']['use-acf-date-format'],
        'link-field'          => $data['data']['link-field'],
        'link-behaviour'      => $data['data']['link-behaviour'],
        'link-text'           => $data['data']['link-text'],
        'field-source'        => $section[ '_panels_design_cfield-' . $i . '-field-source' ],
    );

    if ( $data['data']['field-source'] == 'acf' ) {

      if ( have_rows( $data['data']['name'] ) ):
        $fields = get_field( $data['data']['name'] );

        $object         = get_field_object( $data['data']['name'] );
        $field['style'] = ! empty( $object['wrapper']['width'] ) ? 'style="width:' . $object['wrapper']['width'] . '%;"' : '';
        $field['class'] = ! empty( $object['wrapper']['class'] ) ? $object['wrapper']['class'] : '';
        $field['id']    = ! empty( $object['wrapper']['id'] ) ? 'id="' . $object['wrapper']['id'] . '"' : '';
        $content        .= '<div class="arc-cfield-repeater repeater-' . $data['data']['name'] . ' acf-layout-' . $object['layout'] . '">';

        $first_row = '';
        if ( $object['layout'] == 'table' ) {
          $content .= '<div ' . $field['id'] . ' class="repeater-header acf-layout-' . $object['layout'] . ' ' . $field['class'] . '" ' . $field['style'] . '>';
          foreach ( $object['sub_fields'] as $sub ) {
            $content .= '<div class="header-cell subfield-' . $sub['name'] . '">' . $sub['label'] . '</div>';
          }
          $content   .= '</div>';
          $first_row = ' first-row ';
        } elseif ( $object['layout'] == 'rows' ) {
          $first_row = ' first-row ';
        }
        // loop through the rows of data
        while ( have_rows( $data['data']['name'] ) ) : the_row();
          $row        = get_row_index() - 1;
          $odd_even   = get_row_index() % 2 === 0 ? 'even' : 'odd';
          $content    .= '<div ' . $field['id'] . ' class="repeater-row repeater-row-' . get_row_index() . '-' . $data['data']['name'] . ' ' . $field['class'] . ' ' . $odd_even . $first_row . '" ' . $field['style'] . '>';
          $first_row  = '';
          $first_cell = ( $object['layout'] == 'table' || $object['layout'] == 'rows' ) ? ' first-cell ' : '';
          foreach ( $fields[ $row ] as $field_name => $field_value ) {
            $field_data = $default;

            $row_object        = get_sub_field_object( $field_name );
            $subfield['style'] = ! empty( $row_object['wrapper']['width'] ) ? 'style="width:' . $row_object['wrapper']['width'] . '%;"' : '';
            $subfield['class'] = ! empty( $row_object['wrapper']['class'] ) ? $row_object['wrapper']['class'] : '';
            $subfield['id']    = ! empty( $row_object['wrapper']['id'] ) ? 'id="' . $row_object['wrapper']['id'] . '"' : '';
            $func              = str_replace( '-', '_', 'arc_cft_' . ArcFun::get_field_type( $row_object['type'] ) ) . '_get';

            // This wil return field type as repeater
            $field_data['data']['field-type'] = ArcFun::get_field_type( $row_object['type'] );
            $field_data['data']['name']       = ArcFun::get_field_type( $row_object['name'] );
            $field_data['data']['value']      = ArcFun::get_field_type( $row_object['value'] );

            $field_data = arc_get_field_acf( ArcFun::get_field_type( $field_name ), $postmeta, $field_data, TRUE );
            // Gargh! We need to Render too!
            $field_data = $func( $i, $section, $post, $postmeta, $field_data );
            $content    .= '<div ' . $subfield['id'] . ' class="subfield subfield-' . $field_name . ' ' . $subfield['class'] . $first_cell . '" ' . $subfield['style'] . '>' . $field_data['data']['value'] . '</div>';
            $first_cell = '';
          }
          $content .= '</div>';
        endwhile;
        $content .= '</div>';

      else :
        $content = 'Currently only supports ACF<br>';
        // no rows found

      endif;
    }
    $data['data']['value'] = $content;

    return $data;
  }
