<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 29/04/2014
   * Time: 12:16 PM
   */
  // TODO: These should also definethe content filtering menu in Blueprints options :/

  class arc_Panel_Dummy extends arc_Panel_Generic {

    public $faker;
    public $generator;
    public $isfaker = TRUE;
    public $toshow;
    public $data;
    public $section;
    public $arc_query;

    /**
     *
     */
    public function __construct(&$build) {

      parent::__construct($build);
      parent::initialise_data();

      // Faker requires PHP 5.3.3
      if (!defined('PHP_VERSION_ID')) {
        $version = explode('.', PHP_VERSION);
        define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
      }

      // Ideally we want to use Faker, since it has so many more content field_types, but it needs PHP 5.3.3, so we'll use LoremIpsum class when less that 5.3.3
      if (PHP_VERSION_ID < 50303) {
        $this->isfaker = FALSE;
        require_once(PZARC_PLUGIN_APP_PATH . '/shared/thirdparty/php/LoremIpsum.class/LoremIpsum.class.php');
        $this->generator = new LoremIpsumGenerator;
      }
      else {
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
    public function set_data(&$post, &$toshow, &$section, $panel_number)
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

    public function get_miscellanary(&$post) {
      global $_architect_options;
      $this->data['inherit-hw-block-type'] = (!empty($_architect_options['architect_hw-content-class']) ? 'block-type-content ' : '');

      $this->data['postid']      = '999';
      $this->data['poststatus']  = 'published';
      $this->data['posttype']    = 'post';
      $this->data['permalink']   = '#';
      $post_format               = 'standard';
      $this->data ['postformat'] = (empty($post_format) ? 'standard' : $post_format);
    }

    public function get_title(&$post) {
//      var_Dump($post);
      if ($this->toshow['title']['show']) {
        $this->data['title']['title'] = $post['title']['title'];
      }
    }

    public function get_content(&$post) {
      /** CONTENT */
      if ($this->toshow['content']['show']) {
        //$this->data[ 'content' ] = apply_filters('the_content', $post[ 'content' ]);
        $this->data['content'] = $post['content'];
      }
    }

    public function get_excerpt(&$post) {
      /** Excerpt */
      if ($this->toshow['excerpt']['show']) {
        //$this->data[ 'excerpt' ] = apply_filters('the_excerpt', $post[ 'excerpt' ]);
        $this->data['excerpt'] = $post['excerpt'];
      }
    }

    public function get_image(&$post) {
      /** Image */
      if ($this->toshow['image']['show'] && $this->section['_panels_design_feature-location'] !== 'fill') {
        $width  = (int)str_replace('px', '', $this->section['_panels_design_image-max-dimensions']['width']);
        $height = (int)str_replace('px', '', $this->section['_panels_design_image-max-dimensions']['height']);

        $image_source    = empty($this->build->blueprint['_content_dummy_image-source']) ? 'lorempixel' : $this->build->blueprint['_content_dummy_image-source'];
        $image_grey      = empty($this->build->blueprint['_content_dummy_image-grey']) ? '' : 'g/';
        $text_colour     = empty($this->build->blueprint['_content_dummy_text-colour']) ? 'fff' : str_replace('#', '', $this->build->blueprint['_content_dummy_text-colour']);
        $bg_colour       = empty($this->build->blueprint['_content_dummy_bg-colour']) ? 'bbb' : str_replace('#', '', $this->build->blueprint['_content_dummy_bg-colour']);
        $specific_images = empty($this->build->blueprint['_content_dummy_use-dummy-image-source-specific']) ? array() : explode(',', $this->build->blueprint['_content_dummy_use-dummy-image-source-specific']);

        switch (TRUE) {
          case ('specific' === $image_source && $specific_images) :
            $counter       = ($this->panel_number % count($specific_images)) - 1;
            $counter       = $counter < 0 ? count($specific_images) - 1 : $counter;
            $attachment_id = $specific_images[$counter];
            $focal_point   = get_post_meta($attachment_id, 'pzgp_focal_point', TRUE);
            if (empty($focal_point)) {
              $focal_point = get_post_meta(get_the_id(), 'pzgp_focal_point', TRUE);
            }
            $focal_point = (empty($focal_point) ? explode(',', pzarc_get_option('architect_focal_point_default','50,10')) : explode(',', $focal_point));

            $imageSRC                     = wp_get_attachment_image_src($attachment_id, array(
                $width,
                $height,
                'bfi_thumb' => TRUE,
                'crop'      => (int)$focal_point[0] . 'x' . (int)$focal_point[1] . 'x' . $this->section['_panels_settings_image-focal-point'],

            ));
            $imageURL                     = $imageSRC[0];
            $origimageURL                 = $imageSRC[0];
            $this->data['image']['image'] = '<img src="' . $imageURL . '">';
            break;
          case (empty($post['image']['original']) || 'offline' === $post['image']['original']):
            $imageURL                     = PZARC_PLUGIN_APP_URL . 'public/assets/blank-sky.jpg';
            $origimageURL                 = PZARC_PLUGIN_APP_URL . 'public/assets/blank-sky.jpg';
            $this->data['image']['image'] = '<img src="' . $imageURL . '" width=' . $width . ' height=' . $height . ' style="width:' . $width . 'px;height:' . $height . 'px;">';
            break;
          case ('dummyimage' === $image_source) :
            $imageURL                     = 'http://dummyimage.com/' . $width . 'x' . $height . '/' . $bg_colour . '/' . $text_colour;
            $origimageURL                 = 'http://dummyimage.com/' . (3 * $width) . 'x' . (3 * $height) . '/' . $bg_colour . '/' . $text_colour;
            $this->data['image']['image'] = '<img src="' . $imageURL . '">';
            break;
          default:
            $imageURL                     = 'https://loremflickr.com/' . $image_grey . $width . '/' . $height . '/' . $post['image']['original'];
            $origimageURL                 = 'https://loremflickr.com/' . $image_grey . (3 * $width) . '/' . (3 * $height) . '/' . $post['image']['original'];
            $this->data['image']['image'] = '<img src="' . $imageURL . '">';
        }

        $this->data['image']['original'][0] = $origimageURL;
        $this->data['image']['caption']     = $post['image']['caption'];

      }
    }

    public function get_bgimage(&$post) {
      /** Image */
      if ($this->toshow['image']['show'] && $this->section['_panels_design_feature-location'] === 'fill') {
        $width  = (int)str_replace('px', '', $this->section['_panels_design_image-max-dimensions']['width']);
        $height = (int)str_replace('px', '', $this->section['_panels_design_image-max-dimensions']['height']);

        $image_source    = empty($this->build->blueprint['_content_dummy_image-source']) ? 'lorempixel' : $this->build->blueprint['_content_dummy_image-source'];
        $image_grey      = empty($this->build->blueprint['_content_dummy_image-grey']) ? '' : 'g/';
        $text_colour     = empty($this->build->blueprint['_content_dummy_text-colour']) ? 'fff' : str_replace('#', '', $this->build->blueprint['_content_dummy_text-colour']);
        $bg_colour       = empty($this->build->blueprint['_content_dummy_bg-colour']) ? 'bbb' : str_replace('#', '', $this->build->blueprint['_content_dummy_bg-colour']);
        $specific_images = empty($this->build->blueprint['_content_dummy_use-dummy-image-source-specific']) ? array() : explode(',', $this->build->blueprint['_content_dummy_use-dummy-image-source-specific']);


        switch (TRUE) {
          case ('specific' === $image_source && $specific_images) :
            $counter       = ($this->panel_number % count($specific_images)) - 1;
            $counter       = $counter < 0 ? count($specific_images) - 1 : $counter;
            $attachment_id = $specific_images[$counter];
            $focal_point   = get_post_meta($attachment_id, 'pzgp_focal_point', TRUE);
            if (empty($focal_point)) {
              $focal_point = get_post_meta(get_the_id(), 'pzgp_focal_point', TRUE);
            }
            $focal_point = (empty($focal_point) ? explode(',', pzarc_get_option('architect_focal_point_default','50,10')) : explode(',', $focal_point));

            $imageSRC                       = wp_get_attachment_image_src($attachment_id, array(
                $width,
                $height,
                'bfi_thumb' => TRUE,
                'crop'      => (int)$focal_point[0] . 'x' . (int)$focal_point[1] . 'x' . $this->section['_panels_settings_image-focal-point'],

            ));
            $imageURL                       = $imageSRC[0];
            $this->data['bgimage']['thumb'] = '<img src="' . $imageURL . '">';
            break;
          case (empty($post['image']['original']) || 'offline' === $post['image']['original']):
            $imageURL                       = PZARC_PLUGIN_APP_URL . 'public/assets/blank-sky.jpg';
            $this->data['bgimage']['thumb'] = '<img src="' . $imageURL . '" width=' . $width . ' height=' . $height . ' style="width:' . $width . 'px;height:' . $height . 'px;">';
            break;
          case ('dummyimage' === $image_source) :
            $imageURL                       = 'https://dummyimage.com/' . $width . 'x' . $height . '/' . $bg_colour . '/' . $text_colour;
            $this->data['bgimage']['thumb'] = '<img src="' . $imageURL . '">';
            break;
          default:
            $imageURL                       = 'https://loremflickr.com/' . $image_grey . $width . '/' . $height . '/' . $post['image']['original'];
            $this->data['bgimage']['thumb'] = '<img src="' . $imageURL . '">';
        }
        $this->data['bgimage']['original'] = $imageURL;
        $this->data['bgimage']['caption']  = $post['image']['caption'];
      }
    }

    public function get_meta(&$post) {
      /** META */
      if ($this->toshow['meta1']['show'] || $this->toshow['meta2']['show'] || $this->toshow['meta3']['show']) {
        $this->data['meta']['datetime']        = $post['meta']['datetime'];
        // $this->data['meta']['fdatetime']       = date_i18n(strip_tags($this->section['_panels_design_meta-date-format']), str_replace(',', ' ', strtotime($post['meta']['fdatetime'])));
        $this->data['meta']['fdatetime']       = wp_date(strip_tags($this->section['_panels_design_meta-date-format']), str_replace(',', ' ', strtotime($post['meta']['fdatetime']))); //v11.3
        $this->data['meta']['categorieslinks'] = $post['meta']['categorieslinks'];
        $this->data['meta']['categories']      = $post['meta']['categories'];
        $this->data['meta']['tagslinks']       = $post['meta']['tagslinks'];
        $this->data['meta']['tags']            = $post['meta']['tags'];

        $this->data['meta']['authorlink']     = $post['meta']['authorlink'];
        $this->data['meta']['authorname']     = $post['meta']['authorname'];
        $this->data['meta']['authoremail']    = $post['meta']['authoremail'];
        $this->data['meta']['comments-count'] = $post['meta']['comments-count'];

        $this->data['meta']['custom'][1] = $post['meta']['custom'][1];
        $this->data['meta']['custom'][2] = $post['meta']['custom'][2];
        $this->data['meta']['custom'][3] = $post['meta']['custom'][3];
      }
    }

    function loop($section_no, &$architect, &$panel_class, $class) {
      parent::loop_from_array($section_no, $architect, $panel_class, $class); // TODO: Change the autogenerated stub
    }

    function get_nav_items($blueprints_navigator, &$arc_query, $nav_labels, $nav_title_len = 0) {
      return parent::get_nav_items_from_array($blueprints_navigator, $arc_query, $nav_labels, $nav_title_len); // TODO: Change the autogenerated stub
    }

  }
