<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arcBuilderAdmin.php
   * User: chrishoward
   * Date: 20/03/15
   * Time: 8:25 PM
   */
  class arcBuilder {
    public $redux_opt_name = '_architect';
    public $build = array();

    function __construct() {

//      add_action('arc_page_template', array( $this, 'build' ), 99 );
      add_action( 'wp_head', array( $this, 'css' ), 10 );
    }


    /**
     * Display Page Builder
     */
    function build( $query_object ) {
      $this->build = array(
        'blueprints_any'     => get_post_meta( get_the_id(), '_pzarc_pagebuilder_any', true ),
        'blueprints_phones'  => get_post_meta( get_the_id(), '_pzarc_pagebuilder_phones', true ),
        'blueprints_tablets' => get_post_meta( get_the_id(), '_pzarc_pagebuilder_tablets', true ),
        'titles'             => get_post_meta( get_the_id(), '_pzarc_pagebuilder_titles', true ),
        'titles_tag'         => get_post_meta( get_the_id(), '_pzarc_pagebuilder_titles_tag', true ),
      );

      if (!class_exists('Mobile_Detect')) {
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/Mobile-Detect/Mobile_Detect.php');
      }
      $detect = new Mobile_Detect;

      switch ( true ) {
        case ( $detect->isMobile() && ! $detect->isTablet() && !empty($this->build[ 'blueprints_phones' ]) ):
          // Phone
          $blueprints =  $this->build[ 'blueprints_phones' ];
          break;
        case ( $detect->isTablet() && ! empty( $this->build[ 'blueprints_tablets' ] ) ):
          // Tablet
          $blueprints = $this->build[ 'blueprints_tablets' ] ;
          break;
        default:
          // Desktop or other weird thing
          $blueprints = ! empty( $this->build[ 'blueprints_any' ] ) ? $this->build[ 'blueprints_any' ] : array();
          break;
      }

      $this->build[ 'titles_tag' ] = empty( $this->build[ 'titles_tag' ] ) ? 'h3' : $this->build[ 'titles_tag' ];

      if ( ! empty( $blueprints ) ) {
        echo '<div class="pzarc-builder">';
        $i = 0;
        foreach ( $blueprints as $bp_shortname ) {
          echo '<section class="arc-builder-section arc-builder-section-' . $bp_shortname . '">';
          if ( ! empty( $this->build[ 'titles' ][ $i ] ) ) {
            echo '<' . $this->build[ 'titles_tag' ] . ' class="arc-builder-section-title">' . sanitize_title( $this->build[ 'titles' ][ $i ++ ] ) . '</' . $this->build[ 'titles_tag' ] . '>';
          }
          switch ( $bp_shortname ) {
            case 'comments':
              if ( comments_open() || get_comments_number() ) :
                comments_template();
              endif;
              break;
            default:
              pzarc_pagebuilder( $bp_shortname, null );
          }
          echo '</section>';
        }
        echo '</div>';
      }

  }

  function css() {

//    pzdebug();
//    die();
    // Only want to do this on the Architect Builder template.
    if ( 'arc_page_template.php' !== get_page_template_slug() && 'arc_page_template_no_sidebars.php' !== get_page_template_slug()) {
      return;
    }
//    pzdebug();
//die();
    $this->build = array(
      'wrapperwidth'   => get_post_meta( get_the_id(), '_pzarc_pagebuilder_wrapper-width', true ),
      'wrapperfloat'   => get_post_meta( get_the_id(), '_pzarc_pagebuilder_wrapper-float', true ),
      'wrappermargins' => get_post_meta( get_the_id(), '_pzarc_pagebuilder_wrapper-margins', true ),
      'width'          => get_post_meta( get_the_id(), '_pzarc_pagebuilder_width', true ),
      'float'          => get_post_meta( get_the_id(), '_pzarc_pagebuilder_float', true ),
      'margins'        => get_post_meta( get_the_id(), '_pzarc_pagebuilder_margins', true ),
      'padding'        => get_post_meta( get_the_id(), '_pzarc_pagebuilder_padding', true ),
      'background'     => get_post_meta( get_the_id(), '_pzarc_pagebuilder_background', true ),
      'titles_typo'    => get_post_meta( get_the_id(), '_pzarc_pagebuilder_titles_typography', true )
    );
    $wrapper_css = '.architect-builder {';
    //WRAPPER
    $wrapper_css .= ! empty( $this->build[ 'wrapperwidth' ][ 'width' ] ) ? 'width:' . $this->build[ 'wrapperwidth' ][ 'width' ] . ';' : null;
    $wrapper_css .= ! empty( $this->build[ 'wrapperfloat' ] ) ? 'float:' . $this->build[ 'wrapperfloat' ] . ';' : null;
    $wrapper_css .= ! empty( $this->build[ 'wrappermargins' ][ 'margin-top' ] ) ? 'margin-top:' . $this->build[ 'wrappermargins' ][ 'margin-top' ] . ';' : null;
    $wrapper_css .= ! empty( $this->build[ 'wrappermargins' ][ 'margin-right' ] ) ? 'margin-right:' . $this->build[ 'wrappermargins' ][ 'margin-right' ] . ';' : null;
    $wrapper_css .= ! empty( $this->build[ 'wrappermargins' ][ 'margin-bottom' ] ) ? 'margin-bottom:' . $this->build[ 'wrappermargins' ][ 'margin-bottom' ] . ';' : null;
    $wrapper_css .= ! empty( $this->build[ 'wrappermargins' ][ 'margin-left' ] ) ? 'margin-left:' . $this->build[ 'wrappermargins' ][ 'margin-left' ] . ';' : null;

    $wrapper_css .= '}';

    $css = '.pzarc-builder {';


    //WIDTH
    $css .= ! empty( $this->build[ 'width' ][ 'width' ] ) ? 'width:' . $this->build[ 'width' ][ 'width' ] . ';' : null;

    // Float
    if ( ! empty( $this->build[ 'float' ] ) && $this->build[ 'float' ] === 'centre' ) {
      $this->build[ 'margins' ][ 'margin-left' ]  = 'auto';
      $this->build[ 'margins' ][ 'margin-right' ] = 'auto';
    } else {
      $css .= ! empty( $this->build[ 'float' ] ) ? 'float:' . $this->build[ 'float' ] . ';' : null;
    }

    // Margins
    $css .= ! empty( $this->build[ 'margins' ][ 'margin-top' ] ) ? 'margin-top:' . $this->build[ 'margins' ][ 'margin-top' ] . ';' : null;
    $css .= ! empty( $this->build[ 'margins' ][ 'margin-right' ] ) ? 'margin-right:' . $this->build[ 'margins' ][ 'margin-right' ] . ';' : null;
    $css .= ! empty( $this->build[ 'margins' ][ 'margin-bottom' ] ) ? 'margin-bottom:' . $this->build[ 'margins' ][ 'margin-bottom' ] . ';' : null;
    $css .= ! empty( $this->build[ 'margins' ][ 'margin-left' ] ) ? 'margin-left:' . $this->build[ 'margins' ][ 'margin-left' ] . ';' : null;

    // PAdding
    $css .= ! empty( $this->build[ 'padding' ][ 'padding-top' ] ) ? 'padding-top:' . $this->build[ 'padding' ][ 'padding-top' ] . ';' : null;
    $css .= ! empty( $this->build[ 'padding' ][ 'padding-right' ] ) ? 'padding-right:' . $this->build[ 'padding' ][ 'padding-right' ] . ';' : null;
    $css .= ! empty( $this->build[ 'padding' ][ 'padding-bottom' ] ) ? 'padding-bottom:' . $this->build[ 'padding' ][ 'padding-bottom' ] . ';' : null;
    $css .= ! empty( $this->build[ 'padding' ][ 'padding-left' ] ) ? 'padding-left:' . $this->build[ 'padding' ][ 'padding-left' ] . ';' : null;

    // Background
    $css .= ! empty( $this->build[ 'background' ][ 'background-color' ] ) ? 'background-color:' . $this->build[ 'background' ][ 'background-color' ] . ';' : null;

    $titles = pzarc_style_font( '.arc-builder-section-title', $this->build[ 'titles_typo' ] );

    $css .= '}';
//      if ($css !== '.pzarc_builder {}') {
    echo '<style id="architect-builder" type="text/css">' . $wrapper_css . $css . $titles . '</style>';
//      }
  }

  }


