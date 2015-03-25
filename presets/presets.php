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
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-magazine-grid.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-magazine-grid.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-blog-excerpts-3x3.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-blog-excerpts-3x3.jpg',
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
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-features-slider-title-nav.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-features-slider-title-nav.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-features-slider-nav-inside.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-features-slider-nav-inside.jpg',
          'designer' => 'Chris Howard'
      );

    }

    function render()
    {
      self::get_presets();
      $render = array('html' => array('basic'     => '',
                                      'slider'    => '',
                                      'tabbed'    => '',
                                      'masonry'   => '',
                                      'accordion' => '',
                                      'tabular'   => ''),
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

        $render[ 'html' ][ $file_data[ 'bptype' ] ] .= '
          <span>
            <label class="arc-preset-item ' . $file_data[ 'bptype' ] . '" for="element_' . $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ] . '">
              <input id="element_' . $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ] . '" name="architect_preset_selector" class="element radio" type="radio" value="' . $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ] . '">
              <img class="jqlazy" src="'.$blank_gif.'" data-lazysrc="' . esc_attr($values[ 'image' ]) . '" style="min-height:250px;">
              <p class="arc-preset-title">' . ($file_data['title']) . '</p>
              <p class="arc-preset-designer">Designed by: ' . esc_html($values[ 'designer' ]) . '</p>
              <p class="arc-preset-description">'.$preset_meta['_blueprints_description'][0].'</p>
            </label>

              </span>
          ';
        $render[ 'data' ][ $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ] ] = $file_data;
      }

      return $render;
    }
  }