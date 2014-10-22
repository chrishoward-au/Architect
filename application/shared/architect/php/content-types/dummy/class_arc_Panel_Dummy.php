<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 29/04/2014
   * Time: 12:16 PM
   */
  // TODO: These should also definethe content filtering menu in Blueprints options :/

  class arc_Panel_Dummy extends arc_Panel_Renderer
  {

    public $faker;
    public $generator;
    public $isfaker = true;
    public $toshow;
    public $data;
    public $section;

    /**
     *
     */
    public function __construct()
    {

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
        $this->faker = Faker\Factory::create();
      }
    }


    /**
     * @param $post
     * @param $toshow
     * @param $section
     */
    public function set_data(&$post, &$toshow, &$section)
    {
      $this->section = $section;
      $this->toshow  = $toshow;
      $this->get_title($post);
      $this->get_meta($post);
      $this->get_content($post);
      $this->get_excerpt($post);
      //TODO: Really must make these one!
      $this->get_image($post);
      $this->get_bgimage($post);
      // TODO: Should I do custom fields in Dummy?
//      $this->get_custom($post);
      $this->get_miscellanary($post);
    }

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
      if ($this->toshow[ 'title' ][ 'show' ]) {
        $this->data[ 'title' ][ 'title' ] = ($this->isfaker ? $this->faker->sentence() : ucfirst($this->generator->getContent(rand(3, 8), 'plain', false)));
      }
    }

    public function get_content(&$post)
    {
      /** CONTENT */
      if ($this->toshow[ 'content' ][ 'show' ]) {
        $this->data[ 'content' ] = apply_filters('the_content', ($this->isfaker ? $this->faker->realText() : ucfirst($this->generator->getContent(rand(400, 800), 'html', false))));
      }
    }

    public function get_excerpt(&$post)
    {
      /** Excerpt */
      if ($this->toshow[ 'excerpt' ][ 'show' ]) {
        $this->data[ 'excerpt' ] = apply_filters('the_excerpt', ($this->isfaker ? $this->faker->realText(250) : ucfirst($this->generator->getContent(rand(20, 50), 'text', false))) . '... <a href="#">[Read more]</a>');
      }
    }

    public function get_image(&$post)
    {
      /** Image */
      static $i = 0;
      if ($this->toshow[ 'image' ][ 'show' ] && $this->section[ '_panels_design_feature-location' ] !== 'fill') {
        $width  = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
        $height = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ]);

        // TODO: Add all the focal point stuff to all the post types images and bgimages
        // Easiest to do via a reusable function or all this stuff could be done once!!!!!!!!!
        // could pass $this->data thru a filter

        $cats = array('abstract',
                      'animals',
                      'business',
                      'cats',
                      'city',
                      'food',
                      'nightlife',
                      'fashion',
                      'people',
                      'nature',
                      'sports',
                      'technics',
                      'transport');

        $imageURL = ($this->isfaker ? $this->faker->imageURL($width, $height, $cats[ $i ]) : 'http://lorempixel.com/' . $width . '/' . $height . '/' . $cats[ $i ]);
//        $image = bfi_thumb($imageURL,array($width,$height));
        $this->data[ 'image' ][ 'image' ] = '<img src="' . $imageURL . '">';

        $this->data[ 'image' ][ 'original' ] = $imageURL;
        $this->data[ 'image' ][ 'caption' ]  = ($this->isfaker ? $this->faker->sentence() : 'caption');
        $i                                   = (++$i > count($cats) ? 0 : $i);
      }
    }

    public function get_bgimage(&$post)
    {
      /** Image */
      static $i = 0;
      if ($this->toshow[ 'image' ][ 'show' ]  && $this->section[ '_panels_design_feature-location' ] === 'fill') {
        $width  = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
        $height = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ]);

        // TODO: Add all the focal point stuff to all the post types images and bgimages
        // Easiest to do via a reusable function or all this stuff could be done once!!!!!!!!!
        // could pass $this->data thru a filter

        $cats = array('abstract',
                      'animals',
                      'business',
                      'cats',
                      'city',
                      'food',
                      'nightlife',
                      'fashion',
                      'people',
                      'nature',
                      'sports',
                      'technics',
                      'transport');

        $imageURL = ($this->isfaker ? $this->faker->imageURL($width, $height, $cats[ $i ]) : 'http://lorempixel.com/' . $width . '/' . $height . '/' . $cats[ $i ]);
//        $image = bfi_thumb($imageURL,array($width,$height));
        $this->data[ 'bgimage' ][ 'thumb' ] = '<img src="' . $imageURL . '">';

        $this->data[ 'bgimage' ][ 'original' ] = $imageURL;
        $this->data[ 'bgimage' ][ 'caption' ]  = ($this->isfaker ? $this->faker->sentence() : 'caption');
        $i                                   = (++$i > count($cats) ? 0 : $i);
      }
    }

    public function get_meta(&$post)
    {
      /** META */
      if ($this->toshow[ 'meta1' ][ 'show' ] ||
          $this->toshow[ 'meta2' ][ 'show' ] ||
          $this->toshow[ 'meta3' ][ 'show' ]
      ) {
        $thedate                                   = ($this->isfaker ? $this->faker->date() : time());
        $this->data[ 'meta' ][ 'datetime' ]        = $thedate;
        $this->data[ 'meta' ][ 'fdatetime' ]       = date_i18n($this->section[ '_panels_design_meta-date-format' ], strtotime($thedate));
        $this->data[ 'meta' ][ 'categorieslinks' ] = ($this->isfaker ? implode(', ', $this->faker->words(2)) : 'cat1,cat2');
        $this->data[ 'meta' ][ 'categories' ]      = '';
        $this->data[ 'meta' ][ 'tagslinks' ]       = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'tag1,tag2,tag3');
        $this->data[ 'meta' ][ 'tags' ]            = '';

        $this->data[ 'meta' ][ 'authorlink' ] = '#';
        $this->data[ 'meta' ][ 'authorname' ] = sanitize_text_field(($this->isfaker ? $this->faker->name() : 'Bill Bloggs'));
        $rawemail                             = sanitize_email(($this->isfaker ? $this->faker->email : 'billbloggs@somewhere.com'));
        $encodedmail                          = '';
        for ($i = 0; $i < strlen($rawemail); $i++) {
          $encodedmail .= "&#" . ord($rawemail[ $i ]) . ';';
        }
        $this->data[ 'meta' ][ 'authoremail' ]    = $encodedmail;
        $this->data[ 'meta' ][ 'comments-count' ] = rand(0, 10);

        $this->data[ 'meta' ][ 'custom' ][ 1 ] = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'c1tag1,c1tag2,c1tag3');
        $this->data[ 'meta' ][ 'custom' ][ 2 ] = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'c2tag1,c2tag2,c2tag3');
        $this->data[ 'meta' ][ 'custom' ][ 3 ] = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'c3tag1,c3tag2,c3tag3');
      }
    }


    /**
     * Default Loop
     */
    public function loop($section_no, &$architect, &$panel_class, $class)
    {
      static $j = 1;
      $this->build     = $architect->build;
      $this->arc_query = $architect->arc_query;

      $section[ $section_no ] = $this->build->blueprint[ 'section_object' ][ $section_no ];

      $panel_def = $panel_class->panel_def();

      // Setup meta tags
      $panel_def = $architect->build_meta_definition($panel_def, $this->build->blueprint[ 'section' ][ ($section_no - 1) ][ 'section-panel-settings' ]);

      //   var_dump(esc_html($panel_def));

      $i         = 1;
      $nav_items = array();

      // Does this work for non

      $section[ $section_no ]->open_section();
      while ($j <= $this->build->blueprint[ '_content_dummy_dummy-record-count' ]) {

        $j++;
        // TODO: This may need to be modified for other types that dont' use post_title
        // TODO: Make dumb so can be pluggable for other navs
        switch ($this->build->blueprint[ '_blueprints_navigator' ]) {

          case 'tabbed':
            $nav_items[ ] = '<span class="' . $this->build->blueprint[ '_blueprints_navigator' ] . '">' . self::get_title($j) . '</span>';
            break;

          case 'thumbs':

            $thumb        = '<img src="' . ($this->isfaker ? $this->faker->imageURL(50, 50) : 'http://lorempixel.com/50/50') . '" width="50" height="50">';
            $nav_items[ ] = '<span class="' . $this->build->blueprint[ '_blueprints_navigator' ] . '">' . $thumb . '</span>';
            break;

          case 'bullets':
          case 'numbers':
          case 'buttons':
            //No need for content on these
            $nav_items[ ] = '';
            break;

        }
        $section[ $section_no ]->render_panel($panel_def, $i, $class, $panel_class, $this->arc_query);

        if ($i++ >= $this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-panels-per-view' ] && !empty($this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-panels-limited' ])) {
          break;

        }

      }
      $section[ $section_no ]->close_section();

      // Unsetting causes it to run the destruct, which closes the div!
      unset($section[ $section_no ]);

      return $nav_items;
    }


  }
