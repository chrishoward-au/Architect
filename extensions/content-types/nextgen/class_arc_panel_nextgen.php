<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 29/04/2014
   * Time: 12:16 PM
   */

  // TODO: These should also definethe content filtering menu in Blueprints options... :/ Or is this meant to be in the class_arc_content_xxxx

  class arc_Panel_nextgen extends arc_Panel_Generic
  {


    function get_title(&$post)
    {
      $this->data['title']['title'] = $post['title'];
    }

    function get_image(&$post)
    {
      $width  = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
      $height = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ]);

      $this->data[ 'image' ]['thumb'] = '<img src="'.bfi_thumb($post['image_url'],array('width' => $width, 'height' => $height )).'">';
      $this->data[ 'image' ]['original'][0] = $post['image_url'];
      $this->data[ 'image' ]['caption'] = $post['excerpt'];

    }

    function get_bgimage(&$post)
    {
      $width  = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
      $height = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ]);

      $this->data[ 'bgimage' ]['thumb'] = '<img src="'.bfi_thumb($post['image_url'],array('width' => $width, 'height' => $height )).'">';
      $this->data[ 'image' ]['original'][0] = $post['image_url'];
//      var_dump($this->data['bgimage'],array('width' => $width, 'height' => $height ));
    }

    public function get_content(&$post)
    {
      /** CONTENT */
      $this->data[ 'content' ] = $post['content'];
    }

    public function get_excerpt(&$post)
    {
      $this->data[ 'excerpt' ] = $post['excerpt'];
    }

    /**
     * Custom loop for Dummy data
     */
    public function loop($section_no, &$architect, &$panel_class, $class)
    {
      static $j = 1;
      $this->build     = $architect->build;
      $this->arc_query = $architect->arc_query;

      $section[ $section_no ] = $this->build->blueprint[ 'section_object' ][ $section_no ];

      $panel_def = $panel_class->panel_def();

      // Setup meta tags
      $panel_def = self::build_meta_header_footer_groups($panel_def, $section[ $section_no ]->section['section-panel-settings' ]);

      //   var_dump(esc_html($panel_def));

      $i = 1;

      // Does this work for non
      $section[ $section_no ]->open_section();
      for ($j = 0; $j < count($this->arc_query); $j++) {

        $section[ $section_no ]->render_panel($panel_def, $i, $class, $panel_class, $this->arc_query);

        if ($i++ >= $this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-panels-per-view' ] && !empty($this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-panels-limited' ])) {
          break;

        }

      }
      $section[ $section_no ]->close_section();

      // Unsetting causes it to run the destruct, which closes the div!
      unset($section[ $section_no ]);

    }

    public function get_nav_items($blueprints_navigator,&$arc_query)
    {
      $nav_items = array();
      for ($j = 0; $j < count($arc_query); $j++){
        switch ($blueprints_navigator) {

          case 'tabbed':
            $post_title = $arc_query[ $j ][ 'title' ][ 'title' ];
            if (!empty($nav_title_len) && strlen($post_title)>$nav_title_len) {
              $post_title = trim(substr($post_title,0,($nav_title_len-1))).'&hellip;';
            }

            $nav_items[ ] = '<span class="' . $blueprints_navigator . '">' . $post_title . '</span>';
            break;

          case 'thumbs':

            $thumb        = '<img src="'.$arc_query[$j]['thumb_url'] . '" width="' . parent::get_thumbsize('w') . '" height="' . parent::get_thumbsize('h') . '">';
            $nav_items[ ] = '<span class="' . $blueprints_navigator . '">' . $thumb . '</span>';
            break;

          case 'bullets':
          case 'numbers':
          case 'buttons':
            //No need for content on these
            $nav_items[ ] = '';
            break;

        }
      }

      return $nav_items;

    }

  }

