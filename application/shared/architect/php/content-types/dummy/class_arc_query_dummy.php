<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_query_dummy.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 5:02 PM
   */
  class arc_query_dummy extends arc_query_generic
  {

    public $faker;
    public $generator;
    public $isfaker = true;


    // Set up a loop with each iteration adding a record to $arc_query->post with fields. Get loop count from show number
    // title,meta,content,feature

    public function __construct($build, $criteria)
    {
      $this->build    = $build;
      $this->criteria = $criteria;

      // Faker requires PHP 5.3.3
      if (!defined('PHP_VERSION_ID')) {
        $version = explode('.', PHP_VERSION);
        define('PHP_VERSION_ID', ($version[ 0 ] * 10000 + $version[ 1 ] * 100 + $version[ 2 ]));
      }

      // Ideally we want to use Faker, since it has so many more content field_types, but it needs PHP 5.3.3, so we'll use LoremIpsum class when less that 5.3.3
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

    public function get_custom_query($overrides)
    {

      $dummy_query = array();
      $cats        = array('abstract',
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
      $j           = rand(1,count($cats))-1;


//      $ch = curl_init('http://lorempixel.com');
//      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//      $cexec=curl_exec($ch);
//      $cinfo = curl_getinfo($ch);
//      $is_offline = ($cexec == false || $cinfo['http_code']==302);
//      curl_close($ch);
      $is_offline = is_wp_error(wp_remote_head('https://loremflickr.com')); // v1.9.2 // v1.15.0 changed to loremflickr as google.com was giving an error.
 //     var_dump($this->build->blueprint);
   //   var_dump(maybe_unserialize(get_option('_architect_defaults')));
      $trim_length = $this->build->blueprint[ 'section_object' ][ 1 ]->section[ 'section-panel-settings' ][ '_panels_design_excerpts-word-count' ];
      $max_paragraphs = !empty($this->build->blueprint[ '_content_dummy_max-paragraphs' ])?$this->build->blueprint[ '_content_dummy_max-paragraphs' ]:6;
      $min_paragraphs = !empty($this->build->blueprint[ '_content_dummy_min-paragraphs' ])?$this->build->blueprint[ '_content_dummy_min-paragraphs' ]:1;
      $image_source = empty($this->build->blueprint[ '_content_dummy_image-source' ])?'lorempixel':$this->build->blueprint[ '_content_dummy_image-source' ];
      $readmore = $this->build->blueprint[ 'section_object' ][ 1 ]->section[ 'section-panel-settings' ][ '_panels_design_readmore-truncation-indicator' ].' <a href="#" class="moretag readmore">'.$this->build->blueprint[ 'section_object' ][ 1 ]->section[ 'section-panel-settings' ][ '_panels_design_readmore-text' ].'</a>';
      $no_per_page = ($this->query_options[ 'posts_per_page' ] < 1 ? $this->build->blueprint[ '_content_dummy_dummy-record-count' ] : $this->query_options[ 'posts_per_page' ]);
      for ($i = 0; $i < $no_per_page; $i++) {
        $picno = rand(1, 9999);

        $dummy_query[ $i ][ 'title' ][ 'title' ] = ($this->isfaker ? $this->faker->sentence() : ucfirst($this->generator->getContent(rand(3, 8), 'plain', false)));
        $pcount = rand($min_paragraphs,$max_paragraphs);
        $paragraphs = '';
        if ($this->isfaker) {
          for ($x=0;$x<$pcount;$x++) {
            $paragraphs .= '<p>'.$this->faker->realText(rand(200, 500)).'</p>';
          }
        }
        $dummy_query[ $i ][ 'content' ]          = ($this->isfaker ? $paragraphs : ucfirst($this->generator->getContent(rand(400, 800), 'html', false)));
        $dummy_query[ $i ][ 'excerpt' ]          = ($this->isfaker ? $this->faker->realText(max(10,5 * $trim_length)) : ucfirst($this->generator->getContent(rand(10, $trim_length), 'text', false))) . $readmore;

        // We could cache these but writes are expensive and time consuming. So we'll just use fat thumbs instead.
        // Maybe get Faker to add specific pic number then we could cut a smaller thumb
        // What if we only returned the cat and pic no?!


        if (in_array($image_source,$cats)) {
          $dummy_query[ $i ][ 'image' ][ 'original' ] = (!$is_offline)?$image_source . '/?random=' . $picno:'offline';

        } else {
          $dummy_query[ $i ][ 'image' ][ 'original' ] = (!$is_offline)?$cats[ $j ] . '/?random=' . $picno:'offline';

        }
        $dummy_query[ $i ][ 'image' ][ 'caption' ]        = ($this->isfaker ? $this->faker->sentence() : 'caption');
        $j                                                = (++$j > (count($cats)-1) ? 0 : $j);
        $thedate                                          = ($this->isfaker ? $this->faker->date() : time());
        $dummy_query[ $i ][ 'meta' ][ 'datetime' ]        = $thedate;
        $dummy_query[ $i ][ 'meta' ][ 'fdatetime' ]       = date_i18n(get_option('date_format'), str_replace(',',' ',strtotime($thedate)));
        $dummy_query[ $i ][ 'meta' ][ 'categorieslinks' ] = ($this->isfaker ? implode(', ', $this->faker->words(2)) : 'cat1,cat2');
        $dummy_query[ $i ][ 'meta' ][ 'categories' ]      = '';
        $dummy_query[ $i ][ 'meta' ][ 'tagslinks' ]       = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'tag1,tag2,tag3');
        $dummy_query[ $i ][ 'meta' ][ 'tags' ]            = '';

        $dummy_query[ $i ][ 'meta' ][ 'authorlink' ]     = '#';
        $dummy_query[ $i ][ 'meta' ][ 'authorname' ]     = sanitize_text_field(($this->isfaker ? $this->faker->name() : 'Bill Bloggs'));
        $rawemail                                        = sanitize_email(($this->isfaker ? $this->faker->email : 'billbloggs@somewhere.com'));
        $dummy_query[ $i ][ 'meta' ][ 'authoremail' ]    = $rawemail;
        $dummy_query[ $i ][ 'meta' ][ 'comments-count' ] = rand(0, 10);

        $dummy_query[ $i ][ 'meta' ][ 'custom' ][ 1 ] = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'c1tag1,c1tag2,c1tag3');
        $dummy_query[ $i ][ 'meta' ][ 'custom' ][ 2 ] = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'c2tag1,c2tag2,c2tag3');
        $dummy_query[ $i ][ 'meta' ][ 'custom' ][ 3 ] = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'c3tag1,c3tag2,c3tag3');


      }

      return $dummy_query;
    }
  }
