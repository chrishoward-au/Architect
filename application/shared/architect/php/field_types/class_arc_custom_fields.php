<?php
  /**
   * Name: class_arc_custom_fields.php
   * Author: chrishoward
   * Date: 14/6/19
   * Purpose:
   */

  class arc_custom_fields {

    public $data = array();
    protected $content = '';
    protected $section;
    protected $post;
    protected $i;
    protected $postmeta;

    function __construct( $i, $section, &$post, &$postmeta ) {
      $this->section  = $section;
      $this->post     = $post;
      $this->i        = $i;
      $this->postmeta = $postmeta;
      self::get();
    }

    public function get() {
      /** CUSTOM FIELDS **/
      $this->data ['group']        = $this->section[ '_panels_design_cfield-' . $this->i . '-group' ];
      $this->data ['name']         = $this->section[ '_panels_design_cfield-' . $this->i . '-name' ];
      $this->data ['field-type']   = $this->section[ '_panels_design_cfield-' . $this->i . '-field-type' ];
      $this->data ['field-source'] = $this->section[ '_panels_design_cfield-' . $this->i . '-field-source' ];
      $this->data ['wrapper-tag']  = $this->section[ '_panels_design_cfield-' . $this->i . '-wrapper-tag' ];
      $this->data ['class-name']   = isset( $this->section[ '_panels_design_cfield-' . $this->i . '-class-name' ] ) ? $this->section[ '_panels_design_cfield-' . $this->i . '-class-name' ] : '';

      // Link settings
      $this->data ['link-field']     = $this->section[ '_panels_design_cfield-' . $this->i . '-link-field' ]; // This will be populated with the actual value later
      $this->data ['link-behaviour'] = isset( $this->section[ '_panels_design_cfield-' . $this->i . '-link-behaviour' ] ) ? $this->section[ '_panels_design_cfield-' . $this->i . '-link-behaviour' ] : '_self';
      $this->data ['link-text']      = '<span class="pzarc-link-text">' . $this->section[ '_panels_design_cfield-' . $this->i . '-link-text' ] . '</span>';

      // Numeric settings
      $this->data ['decimals']      = $this->section[ '_panels_design_cfield-' . $this->i . '-number-decimals' ];
      $this->data ['decimal-char']  = $this->section[ '_panels_design_cfield-' . $this->i . '-number-decimal-char' ];
      $this->data ['thousands-sep'] = $this->section[ '_panels_design_cfield-' . $this->i . '-number-thousands-separator' ];


      // Prefix/Suffix
      $params = array(
          'width'   => str_replace( $this->section[ '_panels_design_cfield-' . $this->i . '-ps-images-width' ]['units'], '', $this->section[ '_panels_design_cfield-' . $this->i . '-ps-images-width' ]['width'] ),
          'height'  => str_replace( $this->section[ '_panels_design_cfield-' . $this->i . '-ps-images-height' ]['units'], '', $this->section[ '_panels_design_cfield-' . $this->i . '-ps-images-height' ]['height'] ),
          'quality' => ( ! empty( $this->section['_panels_design_image-quality'] ) ? $this->section['_panels_design_image-quality'] : 82 ),
      );

      $this->data ['prefix-text']  = '<span class="pzarc-prefix-text">' . $this->section[ '_panels_design_cfield-' . $this->i . '-prefix-text' ] . '</span>';
      $this->data ['prefix-image'] = function_exists( 'bfi_thumb' ) ? bfi_thumb( $this->section[ '_panels_design_cfield-' . $this->i . '-prefix-image' ]['url'], $params ) : $this->section[ '_panels_design_cfield-' . $this->i . '-prefix-image' ]['url'];
      $this->data ['suffix-text']  = '<span class="pzarc-suffix-text">' . $this->section[ '_panels_design_cfield-' . $this->i . '-suffix-text' ] . '</span>';
      $this->data ['suffix-image'] = function_exists( 'bfi_thumb' ) ? bfi_thumb( $this->section[ '_panels_design_cfield-' . $this->i . '-suffix-image' ]['url'], $params ) : $this->section[ '_panels_design_cfield-' . $this->i . '-prefix-image' ]['url'];


      // The content itself comes from post meta or post title

      switch ( $this->section[ '_panels_design_cfield-' . $this->i . '-name' ] ) {
        case'post_title':
          $this->data ['value'] = $this->post->post_title;
          break;
        case'use_empty':
          $this->data ['value'] = '{{empty}}';
          break;
        case'specific_code':
          $this->data ['value'] = strip_tags( $this->section[ '_panels_design_cfield-' . $this->i . '-code' ], '<br><p><a><strong><em><ul><ol><li><pre><code><blockquote><h1><h2><h3><h4><h5><h6><img>' );
          break;
//           case'tablefield':
//              $tablefield                          = explode( '___', $this->section[ '_panels_design_cfield-' . $this->i . '-name-table-field' ] );
//              $this->data ['value'] = do_shortcode( '[arccf table="' . $tablefield[0] . '" field="' . $tablefield[1] . '"]' );
//              break;
        default:
          $custom_field = explode( '/', $this->section[ '_panels_design_cfield-' . $this->i . '-name' ] );
          if ( count( $custom_field ) == 1 ) {
            $this->data ['value'] = ( ! empty( $this->postmeta[ $custom_field[0] ] ) ? $this->postmeta[ $custom_field[0] ][0] : NULL );
          } elseif ( count( $custom_field ) == 2 ) {
            $this->data ['value'] = do_shortcode( '[arccf table="' . $custom_field[0] . '" field="' . $custom_field[1] . '"]' );
          }
//              $this->data ['value'] = ( ! empty( $this->postmeta[ $this->section[ '_panels_design_cfield-' . $this->i . '-name' ] ] ) ? $this->postmeta[ $this->section[ '_panels_design_cfield-' . $this->i . '-name' ] ][0] : NULL );
      }

      // Process field groups
      if ( is_Array( maybe_unserialize( $this->data ['value'] ) ) || $this->section[ '_panels_design_cfield-' . $this->i . '-field-type' ] === 'group' ) {
        $this->data ['value'] = maybe_unserialize( $this->data ['value'] );

        $build_layout = '<table class="arc-group-table">';
        $headers_done = FALSE;

        foreach ( $this->data ['value'] as $key => $value ) {
          $inner_array = maybe_unserialize( $value );
          if ( is_array( $inner_array ) ) {
            if ( ! $headers_done ) {
              foreach ( $inner_array as $k => $v ) {
                $build_layout .= '<th>' . ucwords( str_replace( '_', ' ', str_replace( 'ob_', '', $k ) ) ) . '</th>';
                $headers_done = TRUE;
              }
            }
            $build_layout .= '<tr>';
            foreach ( $inner_array as $k => $v ) {
              $build_layout .= '<td>' . $v . '</td>';
            }
            $build_layout .= '</tr>';
          } else {
            $build_layout = '<td>' . $value . '</td>';
          }
        }
        $build_layout         .= '</table>';
        $this->data ['value'] = $build_layout;
      }

      // TODO:Bet this doesn't work!
      if ( ! empty( $this->section[ '_panels_design_cfield-' . $this->i . '-link-field' ] ) ) {
        $link_field = explode( '/', $this->section[ '_panels_design_cfield-' . $this->i . '-link-field' ] );
        if ( count( $link_field ) == 1 ) {
          $this->data ['link-field'] = ( ! empty( $this->postmeta[ $link_field[0] ] ) ? $this->postmeta[ $link_field[0] ][0] : NULL );
        } elseif ( count( $link_field ) == 2 ) {
          $this->data ['link-field'] = do_shortcode( '[arccf table="' . $link_field[0] . '" field="' . $link_field[1] . '"]' );
        }
      }


      if ( $this->section[ '_panels_design_cfield-' . $this->i . '-field-type' ] === 'number' ) {
        $cfnumeric           = @number_format( $this->data ['value'], $this->data ['decimals'], '', '' );
        $cfnumeric           = @number_format( $this->data ['value'], $this->data ['decimals'], '', '' );
        $cfnumeric           = empty( $cfnumeric ) ? '0000' : $cfnumeric;
        $this->data ['data'] = "data-sort-numeric='{$cfnumeric}'";
      }
      // TODO : Add other attributes
    }

    public function render() {

    }
  }
