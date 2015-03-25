<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arcBuilderAdmin.php
   * User: chrishoward
   * Date: 20/03/15
   * Time: 8:25 PM
   */
  class arcBuilder
  {
    public $redux_opt_name = '_architect';
    public $build = array();

    function __construct()
    {
      add_action('pzarc_page_template', array($this, 'build'), 99);
      add_action('wp_head', array($this, 'css'), 10);
    }


    /**
     * Display Page Builder before the post
     */
    function build($query_object)
    {
      $this->build                 = array(
          'blueprints' => get_post_meta(get_the_id(), '_pzarc_pagebuilder', true),
          'titles'     => get_post_meta(get_the_id(), '_pzarc_pagebuilder_titles', true),
          'titles_tag' => get_post_meta(get_the_id(), '_pzarc_pagebuilder_titles_tag', true),
      );
      $this->build[ 'titles_tag' ] = empty($this->build[ 'titles_tag' ]) ? 'h3' : $this->build[ 'titles_tag' ];
      if (isset($this->build[ 'blueprints' ][ 'enabled' ])) {
        echo '<div class="pzarc_builder">';
        $i = 0;
        foreach ($this->build[ 'blueprints' ][ 'enabled' ] as $bp_shortname => $bp_title) {
          if ($bp_shortname !== 'placebo') {
            echo '<section class="arc-builder-section arc-builder-section-' . $bp_shortname . '">';
            if (!empty($this->build[ 'titles' ][ $i ])) {
              echo '<' . $this->build[ 'titles_tag' ] . ' class="arc-builder-section-title">' . sanitize_title($this->build[ 'titles' ][ $i++ ]) . '</' . $this->build[ 'titles_tag' ] . '>';
            }
            switch ($bp_shortname) {
              case 'comments':
                if (comments_open() || get_comments_number()) :
                  comments_template();
                endif;
                break;
              default:
                pzarc_pagebuilder($bp_shortname);
            }
            echo '</section>';
          }
        }
        echo '</div>';
      }

    }

    function css()
    {
      $this->build = array(
          'width'       => get_post_meta(get_the_id(), '_pzarc_pagebuilder_width', true),
          'float'       => get_post_meta(get_the_id(), '_pzarc_pagebuilder_float', true),
          'margins'     => get_post_meta(get_the_id(), '_pzarc_pagebuilder_margins', true),
          'padding'     => get_post_meta(get_the_id(), '_pzarc_pagebuilder_padding', true),
          'background'  => get_post_meta(get_the_id(), '_pzarc_pagebuilder_background', true),
          'titles_typo' => get_post_meta(get_the_id(), '_pzarc_pagebuilder_titles_typography', true)
      );
      $css         = '.pzarc_builder {';

      //WIDTH
      $css .= $this->build[ 'width' ][ 'width' ] ? 'width:' . $this->build[ 'width' ][ 'width' ] . ';' : null;

      // Float
      if ($this->build[ 'float' ] === 'centre') {
        $this->build[ 'margins' ][ 'margin-left' ]  = 'auto';
        $this->build[ 'margins' ][ 'margin-right' ] = 'auto';
      } else {
        $css .= $this->build[ 'float' ] ? 'float:' . $this->build[ 'float' ] . ';' : null;
      }

      // Margins
      $css .= $this->build[ 'margins' ][ 'margin-top' ] ? 'margin-top:' . $this->build[ 'margins' ][ 'margin-top' ] . ';' : null;
      $css .= $this->build[ 'margins' ][ 'margin-right' ] ? 'margin-right:' . $this->build[ 'margins' ][ 'margin-right' ] . ';' : null;
      $css .= $this->build[ 'margins' ][ 'margin-bottom' ] ? 'margin-bottom:' . $this->build[ 'margins' ][ 'margin-bottom' ] . ';' : null;
      $css .= $this->build[ 'margins' ][ 'margin-left' ] ? 'margin-left:' . $this->build[ 'margins' ][ 'margin-left' ] . ';' : null;

      // PAdding
      $css .= $this->build[ 'padding' ][ 'padding-top' ] ? 'padding-top:' . $this->build[ 'padding' ][ 'padding-top' ] . ';' : null;
      $css .= $this->build[ 'padding' ][ 'padding-right' ] ? 'padding-right:' . $this->build[ 'padding' ][ 'padding-right' ] . ';' : null;
      $css .= $this->build[ 'padding' ][ 'padding-bottom' ] ? 'padding-bottom:' . $this->build[ 'padding' ][ 'padding-bottom' ] . ';' : null;
      $css .= $this->build[ 'padding' ][ 'padding-left' ] ? 'padding-left:' . $this->build[ 'padding' ][ 'padding-left' ] . ';' : null;

      // Background
      $css .= $this->build[ 'background' ][ 'background-color' ] ? 'background-color:' . $this->build[ 'background' ][ 'background-color' ] . ';' : null;

      $titles = pzarc_style_font('.arc-builder-section-title', $this->build[ 'titles_typo' ]);

      $css .= '}';
//      if ($css !== '.pzarc_builder {}') {
      echo '<style id="architect-builder" type="text/css">' . $css . $titles . '</style>';
//      }
    }

  }


