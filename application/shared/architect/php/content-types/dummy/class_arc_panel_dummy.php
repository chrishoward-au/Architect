<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 29/04/2014
   * Time: 12:16 PM
   */
  // TODO: These should also definethe content filtering menu in Blueprints options :/

  class arc_Panel_Dummy extends arc_Panel_Generic
  {

    public $faker;
    public $generator;
    public $isfaker = true;
    public $toshow;
    public $data;
    public $section;
    public $arc_query;

    /**
     *
     */
    public function __construct()
    {

      parent::initialise_data();

      // Faker requires PHP 5.3.3
      if (!defined('PHP_VERSION_ID')) {
        $version = explode('.', PHP_VERSION);
        define('PHP_VERSION_ID', ($version[ 0 ] * 10000 + $version[ 1 ] * 100 + $version[ 2 ]));
      }

      // Ideally we want to use Faker, since it has so many more content types, but it needs PHP 5.3.3, so we'll use LoremIpsum class when less that 5.3.3
      if (PHP_VERSION_ID < 50303) {
        $this->isfaker = false;
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/LoremIpsum.class/LoremIpsum.class.php');
        $this->generator = new LoremIpsumGenerator;
      } else {
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/Faker/src/autoload.php');
        require_once(trailingslashit(plugin_dir_path(__FILE__)) . 'faker_53.php');
        $this->faker = pzarc_faker_53();
      }
    }


    /**
     * @param $post
     * @param $toshow
     * @param $section
     */
//    public function set_data(&$post, &$toshow, &$section)
//    {
//      $this->section = $section;
//      $this->toshow  = $toshow;
//      $this->get_title($post);
//      $this->get_meta($post);
//      $this->get_content($post);
//      $this->get_excerpt($post);
//      //TODO: Really must make these one!
//      $this->get_image($post);
//      $this->get_bgimage($post);
//      // TODO: Should I do custom fields in Dummy?
////      $this->get_custom($post);
//      $this->get_miscellanary($post);
//    }

    public function get_miscellanary(&$post)
    {
      global $_architect_options;
      $this->data[ 'inherit-hw-block-type' ] = (!empty($_architect_options[ 'architect_hw-content-class' ]) ? 'block-type-content ' : '');

      $this->data[ 'postid' ]      = '999';
      $this->data[ 'poststatus' ]  = 'published';
      $this->data[ 'posttype' ]    = 'post';
      $this->data[ 'permalink' ]   = '#';
      $post_format                 = 'standard';
      $this->data [ 'postformat' ] = (empty($post_format) ? 'standard' : $post_format);

    }

    public function get_title(&$post)
    {
//      var_Dump($post);
      if ($this->toshow[ 'title' ][ 'show' ]) {
        $this->data[ 'title' ][ 'title' ] = $post[ 'title' ][ 'title' ];
      }
    }

    public function get_content(&$post)
    {
      /** CONTENT */
      if ($this->toshow[ 'content' ][ 'show' ]) {
        //$this->data[ 'content' ] = apply_filters('the_content', $post[ 'content' ]);
        $this->data[ 'content' ] = $post[ 'content' ];
      }
    }

    public function get_excerpt(&$post)
    {
      /** Excerpt */
      if ($this->toshow[ 'excerpt' ][ 'show' ]) {
        //$this->data[ 'excerpt' ] = apply_filters('the_excerpt', $post[ 'excerpt' ]);
        $this->data[ 'excerpt' ] = $post[ 'excerpt' ];
      }
    }

    public function get_image(&$post)
    {
      /** Image */
      if ($this->toshow[ 'image' ][ 'show' ] && $this->section[ '_panels_design_feature-location' ] !== 'fill') {
        $width  = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
        $height = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ]);

        $image_source    = empty($this->build->blueprint[ '_content_dummy_image-source' ]) ? 'lorempixel' : $this->build->blueprint[ '_content_dummy_image-source' ];
        $image_grey      = empty($this->build->blueprint[ '_content_dummy_image-grey' ]) ? '' : 'g/';
        $text_colour     = empty($this->build->blueprint[ '_content_dummy_text-colour' ]) ? 'fff' : str_replace('#', '', $this->build->blueprint[ '_content_dummy_text-colour' ]);
        $bg_colour       = empty($this->build->blueprint[ '_content_dummy_bg-colour' ]) ? 'bbb' : str_replace('#', '', $this->build->blueprint[ '_content_dummy_bg-colour' ]);
        $specific_images = empty($this->build->blueprint[ '_content_dummy_use-dummy-image-source-specific' ]) ? array() : explode(',', $this->build->blueprint[ '_content_dummy_use-dummy-image-source-specific' ]);

        switch (true) {
          case ('specific' === $image_source && $specific_images) :
            $counter       = ($this->panel_number % count($specific_images)) - 1;
            $counter       = $counter < 0 ? count($specific_images) - 1 : $counter;
            $attachment_id = $specific_images[ $counter ];
            $focal_point   = get_post_meta($attachment_id, 'pzgp_focal_point', true);
            if (empty($focal_point)) {
              $focal_point = get_post_meta(get_the_id(), 'pzgp_focal_point', true);
            }
            $focal_point = (empty($focal_point) ? array(50, 50) : explode(',', $focal_point));

            $imageSRC                         = wp_get_attachment_image_src($attachment_id, array(
                $width,
                $height,
                'bfi_thumb' => true,
                'crop'      => (int)$focal_point[ 0 ] . 'x' . (int)$focal_point[ 1 ] . 'x' . $this->section[ '_panels_settings_image-focal-point' ]

            ));
            $imageURL                         = $imageSRC[ 0 ];
            $origimageURL                     = $imageSRC[ 0 ];
            $this->data[ 'image' ][ 'image' ] = '<img src="' . $imageURL . '">';
            break;
          case (empty($post[ 'image' ][ 'original' ]) || 'offline' === $post[ 'image' ][ 'original' ]):
            $imageURL                         = PZARC_PLUGIN_APP_URL . 'public/assets/blank-sky.jpg';
            $origimageURL                     = PZARC_PLUGIN_APP_URL . 'public/assets/blank-sky.jpg';
            $this->data[ 'image' ][ 'image' ] = '<img src="' . $imageURL . '" width=' . $width . ' height=' . $height . ' style="width:' . $width . 'px;height:' . $height . 'px;">';
            break;
          case ('dummyimage' === $image_source) :
            $imageURL                         = 'http://dummyimage.com/' . $width . 'x' . $height . '/' . $bg_colour . '/' . $text_colour;
            $origimageURL                     = 'http://dummyimage.com/' . (3 * $width) . 'x' . (3 * $height) . '/' . $bg_colour . '/' . $text_colour;
            $this->data[ 'image' ][ 'image' ] = '<img src="' . $imageURL . '">';
            break;
          default:
            $imageURL                         = 'http://lorempixel.com/' . $image_grey . $width . '/' . $height . '/' . $post[ 'image' ][ 'original' ];
            $origimageURL                     = 'http://lorempixel.com/' . $image_grey . (3 * $width) . '/' . (3 * $height) . '/' . $post[ 'image' ][ 'original' ];
            $this->data[ 'image' ][ 'image' ] = '<img src="' . $imageURL . '">';
        }

        $this->data[ 'image' ][ 'original' ][ 0 ] = $origimageURL;
        $this->data[ 'image' ][ 'caption' ]       = $post[ 'image' ][ 'caption' ];
      }
    }

    public function get_bgimage(&$post)
    {
      /** Image */
      if ($this->toshow[ 'image' ][ 'show' ] && $this->section[ '_panels_design_feature-location' ] === 'fill') {
        $width  = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
        $height = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ]);

        $image_source    = empty($this->build->blueprint[ '_content_dummy_image-source' ]) ? 'lorempixel' : $this->build->blueprint[ '_content_dummy_image-source' ];
        $image_grey      = empty($this->build->blueprint[ '_content_dummy_image-grey' ]) ? '' : 'g/';
        $text_colour     = empty($this->build->blueprint[ '_content_dummy_text-colour' ]) ? 'fff' : str_replace('#', '', $this->build->blueprint[ '_content_dummy_text-colour' ]);
        $bg_colour       = empty($this->build->blueprint[ '_content_dummy_bg-colour' ]) ? 'bbb' : str_replace('#', '', $this->build->blueprint[ '_content_dummy_bg-colour' ]);
        $specific_images = empty($this->build->blueprint[ '_content_dummy_use-dummy-image-source-specific' ]) ? array() : explode(',', $this->build->blueprint[ '_content_dummy_use-dummy-image-source-specific' ]);


        switch (true) {
          case ('specific' === $image_source && $specific_images) :
            $counter       = ($this->panel_number % count($specific_images)) - 1;
            $counter       = $counter < 0 ? count($specific_images) - 1 : $counter;
            $attachment_id = $specific_images[ $counter ];
            $focal_point   = get_post_meta($attachment_id, 'pzgp_focal_point', true);
            if (empty($focal_point)) {
              $focal_point = get_post_meta(get_the_id(), 'pzgp_focal_point', true);
            }
            $focal_point = (empty($focal_point) ? array(50, 50) : explode(',', $focal_point));

            $imageSRC                           = wp_get_attachment_image_src($attachment_id, array(
                $width,
                $height,
                'bfi_thumb' => true,
                'crop'      => (int)$focal_point[ 0 ] . 'x' . (int)$focal_point[ 1 ] . 'x' . $this->section[ '_panels_settings_image-focal-point' ]

            ));
            $imageURL                           = $imageSRC[ 0 ];
            $this->data[ 'bgimage' ][ 'thumb' ] = '<img src="' . $imageURL . '">';
            break;
          case (empty($post[ 'image' ][ 'original' ]) || 'offline' === $post[ 'image' ][ 'original' ]):
            $imageURL                           = PZARC_PLUGIN_APP_URL . 'public/assets/blank-sky.jpg';
            $this->data[ 'bgimage' ][ 'thumb' ] = '<img src="' . $imageURL . '" width=' . $width . ' height=' . $height . ' style="width:' . $width . 'px;height:' . $height . 'px;">';
            break;
          case ('dummyimage' === $image_source) :
            $imageURL                           = 'http://dummyimage.com/' . $width . 'x' . $height . '/' . $bg_colour . '/' . $text_colour;
            $this->data[ 'bgimage' ][ 'thumb' ] = '<img src="' . $imageURL . '">';
            break;
          default:
            $imageURL                           = 'http://lorempixel.com/' . $image_grey . $width . '/' . $height . '/' . $post[ 'image' ][ 'original' ];
            $this->data[ 'bgimage' ][ 'thumb' ] = '<img src="' . $imageURL . '">';
        }
        $this->data[ 'bgimage' ][ 'original' ] = $imageURL;
        $this->data[ 'bgimage' ][ 'caption' ]  = $post[ 'image' ][ 'caption' ];
      }
    }

    public function get_meta(
        &$post
    ) {
      /** META */
      if ($this->toshow[ 'meta1' ][ 'show' ] ||
          $this->toshow[ 'meta2' ][ 'show' ] ||
          $this->toshow[ 'meta3' ][ 'show' ]
      ) {
        $this->data[ 'meta' ][ 'datetime' ]        = $post[ 'meta' ][ 'datetime' ];
        $this->data[ 'meta' ][ 'fdatetime' ]       = date_i18n(strip_tags($this->section[ '_panels_design_meta-date-format' ]), strtotime($post[ 'meta' ][ 'fdatetime' ]));
        $this->data[ 'meta' ][ 'categorieslinks' ] = $post[ 'meta' ][ 'categorieslinks' ];
        $this->data[ 'meta' ][ 'categories' ]      = $post[ 'meta' ][ 'categories' ];
        $this->data[ 'meta' ][ 'tagslinks' ]       = $post[ 'meta' ][ 'tagslinks' ];
        $this->data[ 'meta' ][ 'tags' ]            = $post[ 'meta' ][ 'tags' ];

        $this->data[ 'meta' ][ 'authorlink' ]     = $post[ 'meta' ][ 'authorlink' ];
        $this->data[ 'meta' ][ 'authorname' ]     = $post[ 'meta' ][ 'authorname' ];
        $this->data[ 'meta' ][ 'authoremail' ]    = $post[ 'meta' ][ 'authoremail' ];
        $this->data[ 'meta' ][ 'comments-count' ] = $post[ 'meta' ][ 'comments-count' ];

        $this->data[ 'meta' ][ 'custom' ][ 1 ] = $post[ 'meta' ][ 'custom' ][ 1 ];
        $this->data[ 'meta' ][ 'custom' ][ 2 ] = $post[ 'meta' ][ 'custom' ][ 2 ];
        $this->data[ 'meta' ][ 'custom' ][ 3 ] = $post[ 'meta' ][ 'custom' ][ 3 ];
      }
    }


    /**
     * Custom loop for Dummy data
     */
    public
    function loop(
        $section_no, &$architect, &$panel_class, $class
    ) {
      static $j = 1;
      $this->build     = $architect->build;
      $this->arc_query = $architect->arc_query;

      $section[ $section_no ] = $this->build->blueprint[ 'section_object' ][ $section_no ];

      $panel_def = $panel_class->panel_def();

      // Setup meta tags
      $panel_def = self::build_meta_header_footer_groups($panel_def, $section[ $section_no ]->section[ 'section-panel-settings' ]);

      //   var_dump(esc_html($panel_def));

      $i = 1;

      // Does this work for non
      $section[ $section_no ]->open_section();
      $post_count = (defined('PZARC_PRO') ? count($this->arc_query) : 15);
      for ($j = 0; $j < $post_count; $j++) {

        $section[ $section_no ]->render_panel($panel_def, $i, $class, $panel_class, $this->arc_query);

        if ($i++ >= $this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-panels-per-view' ] && !empty($this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-panels-limited' ])) {
          break;

        }

      }
      $section[ $section_no ]->close_section();

      // Unsetting causes it to run the destruct, which closes the div!
      unset($section[ $section_no ]);

    }

    public function get_nav_items($blueprints_navigator, &$arc_query, $nav_labels, $nav_title_len = 0) {
      $nav_items = array();
      for ($j = 0; $j < count($arc_query); $j++) {
        switch ($blueprints_navigator) {

          case 'tabbed':
            $post_title = $arc_query[ $j ][ 'title' ][ 'title' ];
            if (!empty($nav_title_len) && strlen($post_title) > $nav_title_len) {
              $post_title = trim(substr($post_title, 0, ($nav_title_len - 1))) . '&hellip;';
            }

            $nav_items[] = '<span class="' . $blueprints_navigator . '">' . $post_title . '</span>';
            break;

          case 'thumbs':

            $thumb       = '<img src="http://lorempixel.com/' . parent::get_thumbsize('w') . '/' . parent::get_thumbsize('h') . '/' . $arc_query[ $j ][ 'image' ][ 'original' ] . '" class="arc-nav-thumb" width="' . parent::get_thumbsize('w') . '" height="' . parent::get_thumbsize('h') . '">';
            $nav_items[] = '<span class="' . $blueprints_navigator . '" title="'.$arc_query[ $j ][ 'title' ][ 'title' ].'">' . $thumb . '</span>';
            break;

          case 'bullets':
          case 'numbers':
          case 'buttons':
            //No need for content on these
            $nav_items[] = '';
            break;

        }
      }

      return $nav_items;

    }
  }
