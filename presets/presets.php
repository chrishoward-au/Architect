<?php


  class arcPresetsLoader
  {
    private $presets;

    function _construct()
    {

    }

    function get_presets()
    {
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-architect-pinterest.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-architect-pinterest.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-architect-magazine-grid.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-architect-magazine-grid.jpg',
          'designer' => 'Chris Howard'
      );
      $this->presets[ ] = array(
          'data'     => PZARC_PLUGIN_URL . 'presets/preset-featured-posts-type-1.txt',
          'image'    => PZARC_PLUGIN_URL . 'presets/preset-featured-posts-type-1.jpg',
          'designer' => 'Chris Howard'
      );

    }

    function render()
    {
      self::get_presets();
      $render = array('html' => array('basic'     => '',
                                      'slider'   => '',
                                      'tabbed'    => '',
                                      'masonry'   => '',
                                      'accordion' => '',
                                      'tabular'   => ''),
                      'data' => array());
      foreach ($this->presets as $index => $values) {
        $file_data   = json_decode(file_get_contents($values[ 'data' ]), true);
        $preset_post = json_decode($file_data[ 'post' ], true);
        $render[ 'html' ][ $file_data[ 'bptype' ] ] .= '
        <span>

              <label class="arc-preset-item ' . $file_data[ 'bptype' ] . '" for="element_' . $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ] . '">
                <input id="element_' . $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ] . '" name="element_' . $preset_post[ 'post_name' ] . '" class="element radio" type="radio" value="' . $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ] .'">
                <img src="' . $values[ 'image' ] . '">
                <p>' . $file_data[ 'title' ] . '
                <span style="font-size:x-small;display:block;">Designed by: '.$values['designer'].'</span></p>
              </label>

            </span>
        ';
        $render[ 'data' ][ $file_data[ 'bptype' ] . '_' . $preset_post[ 'post_name' ]  ] = $file_data;
      }

      return $render;
    }
  }