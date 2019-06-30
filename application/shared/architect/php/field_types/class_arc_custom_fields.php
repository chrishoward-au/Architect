<?php
  /**
   * Name: class_arc_custom_fields.php
   * Author: chrishoward
   * Date: 14/6/19
   * Purpose:
   */

  class arc_custom_fields {

    public $data = array('value'=>'');
    public $meta=array();

    function __construct( $i, $section, &$post, &$postmeta ) {
      self::get( $i, $section, $post, $postmeta );
    }

    public function get( &$i, &$section, &$post, &$postmeta ) {
      /** CUSTOM FIELDS **/
      $this->data ['group']        = $section[ '_panels_design_cfield-' . $i . '-group' ];
      $this->data ['name']         = $section[ '_panels_design_cfield-' . $i . '-name' ];
      $this->data ['field-type']   = $section[ '_panels_design_cfield-' . $i . '-field-type' ];
      $this->data ['field-source'] = $section[ '_panels_design_cfield-' . $i . '-field-source' ];
      $this->data ['wrapper-tag']  = $section[ '_panels_design_cfield-' . $i . '-wrapper-tag' ];
      $this->data ['class-name']   = isset( $section[ '_panels_design_cfield-' . $i . '-class-name' ] ) ? $section[ '_panels_design_cfield-' . $i . '-class-name' ] : '';

      // Link settings
      $this->data ['link-field']     = $section[ '_panels_design_cfield-' . $i . '-link-field' ]; // This will be populated with the actual value later
      $this->data ['link-behaviour'] = isset( $section[ '_panels_design_cfield-' . $i . '-link-behaviour' ] ) ? $section[ '_panels_design_cfield-' . $i . '-link-behaviour' ] : '_self';
      $this->data ['link-text']      = '<span class="pzarc-link-text">' . $section[ '_panels_design_cfield-' . $i . '-link-text' ] . '</span>';

      // Prefix/Suffix
      $params = array(
          'width'   => str_replace( $section[ '_panels_design_cfield-' . $i . '-ps-images-width' ]['units'], '', $section[ '_panels_design_cfield-' . $i . '-ps-images-width' ]['width'] ),
          'height'  => str_replace( $section[ '_panels_design_cfield-' . $i . '-ps-images-height' ]['units'], '', $section[ '_panels_design_cfield-' . $i . '-ps-images-height' ]['height'] ),
          'quality' => ( ! empty( $section['_panels_design_image-quality'] ) ? $section['_panels_design_image-quality'] : 82 ),
      );

      $this->data ['prefix-text']  = !empty($section[ '_panels_design_cfield-' . $i . '-prefix-text' ] )?'<span class="pzarc-prefix-text">' . $section[ '_panels_design_cfield-' . $i . '-prefix-text' ] . '</span>':'';
      $this->data ['prefix-image'] = function_exists( 'bfi_thumb' ) ? bfi_thumb( $section[ '_panels_design_cfield-' . $i . '-prefix-image' ]['url'], $params ) : $section[ '_panels_design_cfield-' . $i . '-prefix-image' ]['url'];
      $this->data ['suffix-text']  = !empty($section[ '_panels_design_cfield-' . $i . '-suffix-text' ])?'<span class="pzarc-suffix-text">' . $section[ '_panels_design_cfield-' . $i . '-suffix-text' ] . '</span>':'';
      $this->data ['suffix-image'] = function_exists( 'bfi_thumb' ) ? bfi_thumb( $section[ '_panels_design_cfield-' . $i . '-suffix-image' ]['url'], $params ) : $section[ '_panels_design_cfield-' . $i . '-prefix-image' ]['url'];

      // Get link field if set
      if ( ! empty( $section[ '_panels_design_cfield-' . $i . '-link-field' ] ) ) {
        $link_field = explode( '/', $section[ '_panels_design_cfield-' . $i . '-link-field' ] );
        if ( count( $link_field ) == 1 ) {
          $this->data ['link-field'] = ( ! empty( $postmeta[ $link_field[0] ] ) ? $postmeta[ $link_field[0] ][0] : NULL );
        } elseif ( count( $link_field ) == 2 ) {
          $this->data ['link-field'] = do_shortcode( '[arccf table="' . $link_field[0] . '" field="' . $link_field[1] . '"]' );
        }
      }

      // Get the content
      switch ( $section[ '_panels_design_cfield-' . $i . '-name' ] ) {
        case'post_title':
          $this->data ['value'] = $post->post_title;
          break;
        case'post_excerpt':
          $this->data ['value'] = $post->post_excerpt;
          break;
        case'post_content':
          $this->data ['value'] = $post->post_content;
          break;
        case'post_thumbnail':
          $this->data ['value'] = get_post_thumbnail_id(  $post->ID);
          break;
        case'post_date':
          $this->data ['value'] = $post->post_date;
          break;
        case'post_author':
          $this->data ['value'] = $post->post_author;
          break;
        case'use_empty':
          $this->data ['value'] = '{{empty}}';
          break;
        case'specific_code':
          $this->data ['value'] = strip_tags( $section[ '_panels_design_cfield-' . $i . '-code' ], '<br><p><a><strong><em><ul><ol><li><pre><code><blockquote><h1><h2><h3><h4><h5><h6><img>' );
          break;
//           case'tablefield':
//              $tablefield                          = explode( '___', $section[ '_panels_design_cfield-' . $i . '-name-table-field' ] );
//              $this->data ['value'] = do_shortcode( '[arccf table="' . $tablefield[0] . '" field="' . $tablefield[1] . '"]' );
//              break;
        default:
          $custom_field = explode( '/', $section[ '_panels_design_cfield-' . $i . '-name' ] );
          if ( count( $custom_field ) == 1 ) {
            $func                = 'arc_get_field_' . $this->data['field-source'];
            $this->data['value'] = $func( $custom_field[0], $postmeta );
          } elseif ( count( $custom_field ) == 2 ) {
            $this->data ['value'] = do_shortcode( '[arccf table="' . $custom_field[0] . '" field="' . $custom_field[1] . '"]' );
          }
      }



      // TODO : Add other attributes
    }

    public function render() {

    }
  }

  /****
   * functions
   */
  function arc_get_field_wp( $arc_field_name, &$postmeta ) {

    return ( ! empty( $postmeta[ $arc_field_name ] ) ? $postmeta[ $arc_field_name ][0] : NULL );
  }

  function arc_get_field_wooc( $arc_field_name, &$postmeta ) {
    return ( ! empty( $postmeta[ $arc_field_name ] ) ? $postmeta[ $arc_field_name ][0] : NULL );
  }

  function arc_get_field_acf( $arc_field_name, &$postmeta ) {
    $arc_field_val = '';
    if ( ! empty( $arc_field_name ) && function_exists( 'get_field' ) ) {
      $arc_field_val = get_field( $arc_field_name );
    }

    return $arc_field_val;

  }
