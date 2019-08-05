<?php
  /**
   * Name: class_arc_cft_repeater.php
   * Author: chrishoward
   * Date: 15/6/19
   * Purpose:
   */

//  class arc_cft_repeater extends arc_cft {
  class arc_cft_repeater extends arc_cft {
//    public $data = array();

    function __construct( $i, $section, &$post, &$postmeta ) {
//      parent::__construct( $i, $section, $post, $postmeta );
//      self::get( $i, $section, $post, $postmeta );
      self::process( $i, $section, $post, $postmeta );
    }

    function process( &$i, &$section, &$post, &$postmeta ) {


      $this->data['value']         = '';
      $this->data ['name']         = $section[ '_panels_design_cfield-' . $i . '-name' ];
      $this->data ['field-type']   = $section[ '_panels_design_cfield-' . $i . '-field-type' ];
      $this->data ['field-source'] = $section[ '_panels_design_cfield-' . $i . '-field-source' ];

      if ( $this->data['field-source'] == 'acf' ) {

        if ( have_rows( $this->data['name'] ) ):
          $fields              = get_field( $this->data['name'] );
          $object              = get_field_object( $this->data['name'] );
          $field['style']      = ! empty( $object['wrapper']['width'] ) ? 'style="width:' . $object['wrapper']['width'] . '%;"' : '';
          $field['class']      = ! empty( $object['wrapper']['class'] ) ? $object['wrapper']['class'] : '';
          $field['id']         = ! empty( $object['wrapper']['id'] ) ? 'id="' . $object['wrapper']['id'] . '"' : '';
          $this->data['value'] .= '<div class="arc-cfield-repeater repeater-' . $this->data['name'] . ' acf-layout-' . $object['layout'] . '">';
//          var_Dump( $object );

          $first_row = '';
          if ( $object['layout'] == 'table' ) {
            $this->data['value'] .= '<div ' . $field['id'] . ' class="repeater-header acf-layout-' . $object['layout'] . ' ' . $field['class'] . '" ' . $field['style'] . '>';
            foreach ( $object['sub_fields'] as $sub ) {
              $this->data['value'] .= '<div class="header-cell subfield-' . $sub['name'] . '">' . $sub['label'] . '</div>';
            }
//            $this->data['value'] .= '<div ' . $subfield['id'] . ' class="subfield subfield-' . $k . ' ' . $subfield['class'] . '" ' . $subfield['style'] . '>' . $v . '</div>';
            $this->data['value'] .= '</div>';
            $first_row           = ' first-row ';
          } elseif ( $object['layout'] == 'rows' ) {
            $first_row = ' first-row ';
          }
          // loop through the rows of data
          while ( have_rows( $this->data['name'] ) ) : the_row();
            $row                 = get_row_index() - 1;
            $odd_even            = get_row_index() % 2 === 0 ? 'even' : 'odd';
            $this->data['value'] .= '<div ' . $field['id'] . ' class="repeater-row repeater-row-' . get_row_index() . '-' . $this->data['name'] . ' ' . $field['class'] . ' ' . $odd_even . $first_row . '" ' . $field['style'] . '>';
            $first_row           = '';
            $first_cell          = ( $object['layout'] == 'table' || $object['layout'] == 'rows' ) ? ' first-cell ' : '';
            foreach ( $fields[ $row ] as $k => $v ) {
              $row_object        = get_sub_field_object( $k );
              $subfield['style'] = ! empty( $row_object['wrapper']['width'] ) ? 'style="width:' . $row_object['wrapper']['width'] . '%;"' : '';
              $subfield['class'] = ! empty( $row_object['wrapper']['class'] ) ? $row_object['wrapper']['class'] : '';
              $subfield['id']    = ! empty( $row_object['wrapper']['id'] ) ? 'id="' . $row_object['wrapper']['id'] . '"' : '';
              $type = str_replace( '-', '_', 'arc_cft_' . ArcFun::get_field_type($row_object['type'] ));
              $xxx  = new $type( $i, $section, $post, $postmeta );
              var_dump($xxx);
              $v    = $xxx->render();
              var_Dump($v);
              die();
//              switch ( $row_object['type'] ) {
//                case 'number':
//                  $v = ArcFun::get_number_field( $i, $section, array( 'name' => $k, 'field-source' => $this->data['field-source'], 'value' => $v ) );
//                  break;
//                case 'image':
////                  var_dump( $i, $section );
////                  var_dump( $v );
//
//                  $v = '<img src="' . $v['url'] . '">';
//              }
              $this->data['value'] .= '<div ' . $subfield['id'] . ' class="subfield subfield-' . $k . ' ' . $subfield['class'] . $first_cell . '" ' . $subfield['style'] . '>' . $v . '</div>';
              $first_cell          = '';
            }
            $this->data['value'] .= '</div>';
          endwhile;
          $this->data['value'] .= '</div>';

        else :
          $this->data['value'] = 'Currently only supports ACF<br>';
          // no rows found

        endif;
      }
    }

    function render() {
      return $this->data['value'];
    }
  }
