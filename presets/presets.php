<?php


  class arcPresetsLoader
  {
    private $presets;

    function _construct()
    {

    }

    function get_presets()
    {

      // Masonry
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-pinterest.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-pinterest.jpg',
          'designer' => 'Chris Howard'
      );

      // Grids
      $this->presets[ ] = array(
        'data'     => PZARC_PLUGIN_URL . 'presets/preset-grid-glossy-magazine.txt',
        'image'    => PZARC_PLUGIN_URL . 'presets/preset-grid-glossy-magazine.jpg',
        'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-magazine-grid.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-magazine-grid.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-blog-excerpts-3x3.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-blog-excerpts-3x3.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-grid-single-page.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-grid-single-page.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
        'data'     => PZARC_PLUGIN_URL . 'presets/preset-grid-testimonials.txt',
        'image'    => PZARC_PLUGIN_URL . 'presets/preset-grid-testimonials.jpg',
        'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-grid-thumb-gallery.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-grid-thumb-gallery.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
        'data'     => PZARC_PLUGIN_URL . 'presets/preset-grid-recent-posts-small-excerpts.txt',
        'image'    => PZARC_PLUGIN_URL . 'presets/preset-grid-recent-posts-small-excerpts.jpg',
        'designer' => 'Chris Howard'
      );

      // Sliders
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-featured-posts-vertical-content.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-featured-posts-vertical-content.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-featured-posts-horizontal-content.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-featured-posts-horizontal-content.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
        'data'     => PZARC_PLUGIN_URL . 'presets/preset-slider-full-width.txt',
        'image'    => PZARC_PLUGIN_URL . 'presets/preset-slider-full-width.jpg',
        'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-features-slider-title-nav.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-features-slider-title-nav.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-features-slider-nav-inside.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-features-slider-nav-inside.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-slider-image-slideshow.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-slider-image-slideshow.jpg',
          'designer' => 'Chris Howard'
      );

      // tabbed
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-tabbed-horizontal-top.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-tabbed-horizontal-top.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-tabbed-vertical-left.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-tabbed-vertical-left.jpg',
          'designer' => 'Chris Howard'
      );

      // tabular
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-tabular.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-tabular.jpg',
          'designer' => 'Chris Howard'
      );

      // accordion
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-accordion.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-accordion.jpg',
          'designer' => 'Chris Howard'
      );

      // TODO: Add a bit here to look in uploads/architect/presets

      // check folder exists

      // look for presets files... data,screenie in folders of designer name!
      /// all should have identical names except to prefix arc-preset-data,screen and appropriate suffix
      //eg. arc-preset-
    }

    function render()
    {
      $this->presets = apply_filters( 'pzarc-add-presets', $this->presets );
      $render = array('html' => array('basic'     => '',
                                      'slider'    => '',
                                      'tabbed'    => '',
                                      'masonry'   => '',
                                      'accordion' => '',
                                      'table'   => ''),
                      'data' => array());
      $blank_gif = PZARC_PLUGIN_APP_URL.'admin/assets/images/blank.gif';
      foreach ($this->presets as $index => $values) {

//        $file_contents = file_get_contents($values[ 'data' ]);

        $ch = curl_init($values[ 'data' ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $file_contents = curl_exec($ch);
        curl_close($ch);


        $file_data   = json_decode($file_contents, true);
        $preset_post = json_decode($file_data[ 'post' ], true);
        $preset_meta = json_decode($file_data['meta'],true);
        $description = empty($preset_meta[ '_blueprints_description' ][ 0 ])?'':$preset_meta[ '_blueprints_description' ][ 0 ];
        $render[ 'html' ][ $file_data[ 'bptype' ] ] .= '
          <span>
            <label class="arc-preset-item ' . $file_data[ 'bptype' ] . '" for="element_' . $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ] . '">
              <input id="element_' . $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ] . '" name="architect_preset_selector" class="element radio" type="radio" value="' . $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ] . '">
              <img src="' . esc_attr($values[ 'image' ]) . '">
              <p class="arc-preset-title">' . ($file_data['title']) . '</p>
              <p class="arc-preset-designer">Designed by: ' . esc_html($values[ 'designer' ]) . '</p>
              <p class="arc-preset-description">'.$description.'</p>
            </label>

              </span>
          ';
        $render[ 'data' ][ $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ] ] = $file_data;
      }
      return $render;
    }
  }

  add_filter('pzarc-add-presets','pzarc_load_presets',10,1);

  function pzarc_load_presets($presets){
    // Masonry
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-pinterest.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-pinterest.jpg',
      'designer' => 'Chris Howard'
    );

    // Grids
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-grid-glossy-magazine.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-grid-glossy-magazine.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-magazine-grid.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-magazine-grid.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-blog-excerpts-3x3.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-blog-excerpts-3x3.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-grid-single-page.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-grid-single-page.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-grid-testimonials.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-grid-testimonials.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-grid-thumb-gallery.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-grid-thumb-gallery.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-grid-recent-posts-small-excerpts.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-grid-recent-posts-small-excerpts.jpg',
      'designer' => 'Chris Howard'
    );

    // Sliders
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-featured-posts-vertical-content.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-featured-posts-vertical-content.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-featured-posts-horizontal-content.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-featured-posts-horizontal-content.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-slider-full-width.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-slider-full-width.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-features-slider-title-nav.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-features-slider-title-nav.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-features-slider-nav-inside.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-features-slider-nav-inside.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-slider-image-slideshow.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-slider-image-slideshow.jpg',
      'designer' => 'Chris Howard'
    );

    // tabbed
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-tabbed-horizontal-top.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-tabbed-horizontal-top.jpg',
      'designer' => 'Chris Howard'
    );
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-tabbed-vertical-left.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-tabbed-vertical-left.jpg',
      'designer' => 'Chris Howard'
    );

    // tabular
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-tabular.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-tabular.jpg',
      'designer' => 'Chris Howard'
    );

    // accordion
    $presets[ ] = array(
      'data'     => PZARC_PLUGIN_URL . 'presets/preset-accordion.txt',
      'image'    => PZARC_PLUGIN_URL . 'presets/preset-accordion.jpg',
      'designer' => 'Chris Howard'
    );
    return $presets;
  }