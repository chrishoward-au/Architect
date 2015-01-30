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
    public function get_custom_query()
    {

      $j = 0;
      $dummy_query = array();
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

      $picno = rand(1,10);
      $no_per_page = ($this->query_options[ 'posts_per_page' ] < 1?$this->build->blueprint['_content_dummy_dummy-record-count']:$this->query_options[ 'posts_per_page' ]);
      for ($i = 0; $i < $no_per_page; $i++) {
        $dummy_query[$i][ 'title' ][ 'title' ] = ($this->isfaker ? $this->faker->sentence() : ucfirst($this->generator->getContent(rand(3, 8), 'plain', false)));
        $dummy_query[$i][ 'content' ] = apply_filters('the_content', ($this->isfaker ? $this->faker->realText() : ucfirst($this->generator->getContent(rand(400, 800), 'html', false))));
        $dummy_query[$i][ 'excerpt' ] = apply_filters('the_excerpt', ($this->isfaker ? $this->faker->realText(250) : ucfirst($this->generator->getContent(rand(20, 50), 'text', false))) . '... <a href="#">[Read more]</a>');

        // We could cache these but writes are expensive and time consuming. So we'll just use fat thumbs instead.
        // Maybe get Faker to add specific pic number then we could cut a smaller thumb
        // What if we only returned the cat and pic no?!


        $dummy_query[$i][ 'image' ][ 'original' ] =  $cats[ $j ].'/'.$picno;

        $dummy_query[$i][ 'image' ][ 'caption' ]  = ($this->isfaker ? $this->faker->sentence() : 'caption');
        $j                                   = (++$j > count($cats) ? 0 : $j);
        $thedate                                   = ($this->isfaker ? $this->faker->date() : time());
        $dummy_query[$i][ 'meta' ][ 'datetime' ]        = $thedate;
        $dummy_query[$i][ 'meta' ][ 'fdatetime' ]       = date_i18n(get_option( 'date_format' ), strtotime($thedate));
        $dummy_query[$i][ 'meta' ][ 'categorieslinks' ] = ($this->isfaker ? implode(', ', $this->faker->words(2)) : 'cat1,cat2');
        $dummy_query[$i][ 'meta' ][ 'categories' ]      = '';
        $dummy_query[$i][ 'meta' ][ 'tagslinks' ]       = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'tag1,tag2,tag3');
        $dummy_query[$i][ 'meta' ][ 'tags' ]            = '';

        $dummy_query[$i][ 'meta' ][ 'authorlink' ] = '#';
        $dummy_query[$i][ 'meta' ][ 'authorname' ] = sanitize_text_field(($this->isfaker ? $this->faker->name() : 'Bill Bloggs'));
        $rawemail                             = sanitize_email(($this->isfaker ? $this->faker->email : 'billbloggs@somewhere.com'));
        $dummy_query[$i][ 'meta' ][ 'authoremail' ]    = $rawemail;
        $dummy_query[$i][ 'meta' ][ 'comments-count' ] = rand(0, 10);

        $dummy_query[$i][ 'meta' ][ 'custom' ][ 1 ] = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'c1tag1,c1tag2,c1tag3');
        $dummy_query[$i][ 'meta' ][ 'custom' ][ 2 ] = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'c2tag1,c2tag2,c2tag3');
        $dummy_query[$i][ 'meta' ][ 'custom' ][ 3 ] = ($this->isfaker ? implode(', ', $this->faker->words(3)) : 'c3tag1,c3tag2,c3tag3');


      }
      return $dummy_query;
    }
  }