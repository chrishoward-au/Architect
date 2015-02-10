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
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/includes/php/LoremIpsum.class/LoremIpsum.class.php');
        $this->generator = new LoremIpsumGenerator;
      } else {
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/includes/php/Faker/src/autoload.php');
        require_once(trailingslashit(plugin_dir_path(__FILE__)).'faker_53.php');
        $this->faker =pzarc_faker_53();
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
        $this->data[ 'content' ] = apply_filters('the_content', $post[ 'content' ]);
      }
    }

    public function get_excerpt(&$post)
    {
      /** Excerpt */
      if ($this->toshow[ 'excerpt' ][ 'show' ]) {
        $this->data[ 'excerpt' ] = apply_filters('the_excerpt', $post[ 'excerpt' ]);
      }
    }

    public function get_image(&$post)
    {
      /** Image */
      if ($this->toshow[ 'image' ][ 'show' ] && $this->section[ '_panels_design_feature-location' ] !== 'fill') {
        $width                               = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
        $height                              = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ]);
        $imageURL                            = 'http://lorempixel.com/' . $width . '/' . $height . '/' . $post[ 'image' ][ 'original' ];
        $this->data[ 'image' ][ 'image' ]    = '<img src="' . $imageURL . '">';
        $this->data[ 'image' ][ 'original' ] = $imageURL;
        $this->data[ 'image' ][ 'caption' ]  = $post[ 'image' ][ 'caption' ];
      }
    }

    public function get_bgimage(&$post)
    {
      /** Image */
      if ($this->toshow[ 'image' ][ 'show' ] && $this->section[ '_panels_design_feature-location' ] === 'fill') {
        $width                                 = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
        $height                                = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ]);
        $imageURL                              = 'http://lorempixel.com/' . $width . '/' . $height . '/' . $post[ 'image' ][ 'original' ];
        $this->data[ 'bgimage' ][ 'thumb' ]    = '<img src="' . $imageURL . '">';
        $this->data[ 'bgimage' ][ 'original' ] = $imageURL;
        $this->data[ 'bgimage' ][ 'caption' ]  = $post[ 'image' ][ 'caption' ];
      }
    }

    public function get_meta(&$post)
    {
      /** META */
      if ($this->toshow[ 'meta1' ][ 'show' ] ||
          $this->toshow[ 'meta2' ][ 'show' ] ||
          $this->toshow[ 'meta3' ][ 'show' ]
      ) {
        $this->data[ 'meta' ][ 'datetime' ]        = $post[ 'meta' ][ 'datetime' ];
        $this->data[ 'meta' ][ 'fdatetime' ]       = $post[ 'meta' ][ 'fdatetime' ];
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
    public function loop($section_no, &$architect, &$panel_class, $class)
    {
      static $j = 1;
      $this->build     = $architect->build;
      $this->arc_query = $architect->arc_query;

      $section[ $section_no ] = $this->build->blueprint[ 'section_object' ][ $section_no ];

      $panel_def = $panel_class->panel_def();

      // Setup meta tags
      $panel_def = self::build_header_footer_meta_groups($panel_def, $this->build->blueprint[ 'section' ][ ($section_no - 1) ][ 'section-panel-settings' ]);

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

    public function get_nav_items($blueprints_navigator, &$arc_query, $nav_labels)

    {
      $nav_items = array();
      for ($j = 0; $j < count($arc_query); $j++){
        switch ($blueprints_navigator) {

          case 'tabbed':
            $nav_items[ ] = '<span class="' . $blueprints_navigator . '">' . $arc_query[ $j ][ 'title' ][ 'title' ] . '</span>';
            break;

          case 'thumbs':

            $thumb        = '<img src="http://lorempixel.com/' . parent::get_thumbsize('w') . '/' . parent::get_thumbsize('h') . '/' . $arc_query[ $j ][ 'image' ][ 'original' ] . '" class="arc-nav-thumb" width="' . parent::get_thumbsize('w') . '" height="' . parent::get_thumbsize('h') . '">';
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
