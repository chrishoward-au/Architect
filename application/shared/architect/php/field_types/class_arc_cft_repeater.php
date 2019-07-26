<?php
  /**
   * Name: class_arc_cft_repeater.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

  class arc_cft_repeater extends arc_custom_fields {

    function __construct( $i, $section, &$post, &$postmeta ) {
      parent::__construct( $i, $section, $post, $postmeta );
      self::get( $i, $section, $post, $postmeta );
    }

    function get( &$i, &$section, &$post, &$postmeta ) {

      if ( $this->data['field-source'] == 'acf' ) {

        $this->data['value'] = '';
        if ( have_rows( $this->data['name'] ) ):
          $fields              = get_field( $this->data['name'] );
          $object              = get_field_object( $this->data['name'] );
          $field['style']      = ! empty( $object['wrapper']['width'] ) ? 'style="width:' . $object['wrapper']['width'] . '%;"' : '';
          $field['class']      = ! empty( $object['wrapper']['class'] ) ? $object['wrapper']['class'] : '';
          $field['id']         = ! empty( $object['wrapper']['id'] ) ? 'id="' . $object['wrapper']['id'] . '"' : '';
          $this->data['value'] .= '<div class="arc-cfield-repeater repeater-' . $this->data['name'] . ' acf-layout-' . $object['layout'] . '">';
          var_Dump( $object );
          if ( $object['layout'] == 'table' ) {
            $this->data['value'] .= '<div ' . $field['id'] . ' class="repeater-header acf-layout-' . $object['layout'] . ' ' . $field['class'] . '" ' . $field['style'] . '>';
            foreach ( $object['sub_fields'] as $sub ) {
              $this->data['value'] .= '<div class="header-cell subfield-'.$sub['name'].'">' . $sub['label'] . '</div>';
            }
//            $this->data['value'] .= '<div ' . $subfield['id'] . ' class="subfield subfield-' . $k . ' ' . $subfield['class'] . '" ' . $subfield['style'] . '>' . $v . '</div>';
            $this->data['value'] .= '</div>';
          }
          // loop through the rows of data
          $first_row = ' first-row ';
          while ( have_rows( $this->data['name'] ) ) : the_row();
            $row                 = get_row_index() - 1;
            $odd_even            = get_row_index() % 2 === 0 ? 'even' : 'odd';
            $this->data['value'] .= '<div ' . $field['id'] . ' class="repeater-row repeater-row-' . get_row_index() . '-' . $this->data['name'] . ' ' . $field['class'] . ' ' . $odd_even . $first_row . '" ' . $field['style'] . '>';
            $first_row           = '';
            $first_cell          = ' first-cell ';
            foreach ( $fields[ $row ] as $k => $v ) {
              $row_object          = get_sub_field_object( $k );
              $subfield['style']   = ! empty( $row_object['wrapper']['width'] ) ? 'style="width:' . $row_object['wrapper']['width'] . '%;"' : '';
              $subfield['class']   = ! empty( $row_object['wrapper']['class'] ) ? $row_object['wrapper']['class'] : '';
              $subfield['id']      = ! empty( $row_object['wrapper']['id'] ) ? 'id="' . $row_object['wrapper']['id'] . '"' : '';
              $this->data['value'] .= '<div ' . $subfield['id'] . ' class="subfield subfield-' . $k . ' ' . $subfield['class'] . $first_cell . '" ' . $subfield['style'] . '>' . $v . '</div>';
              $first_cell          = '';
            }
            $this->data['value'] .= '</div>';
          endwhile;
          $this->data['value'] .= '</div>';

        else :
          $this->data['value'] = 'Currently only supports ACF<br>' . implode( ', ', $this->data['value'] );
          // no rows found

        endif;
//        $fields= get_field($this->data['name']);
//        $i=0;
//        $this->data['value'] = '';
//        foreach ($fields as $field){
//          $acf_field = $this->data['name'].'_'.$i.'_'.$field;
//          $this->data['value'].= $acf_field.'<div>'
//        }
      }

    }

    function render() {
      $content = '';
      if ( ! empty( $this->data['value'] ) ) {
        $content = $this->data['value'];
        var_dump( $this->data['value'] );

      }

      return $content;
    }


  }
