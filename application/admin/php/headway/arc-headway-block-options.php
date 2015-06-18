<?php

  /* This class must be included in another file and included later so we don't get an error about HeadwayBlockOptionsAPI class not existing. */

  class HeadwayArchitectBlockOptions extends HeadwayBlockOptionsAPI
  {

    public $tabs = array();
    public $inputs = array();
    public $tab_notices = array();
    public $open_js_callback = '';

    function modify_arguments($args = false)
    {
      $block = $args[ 'block' ];
      $this->tabs
             = array(
          'build'  => 'Blueprint',
          'custom' => 'Overrides',
          //          'info'   => 'Info'
      );

      // Setup the tab options
      $this->inputs
          = array(
          'build' => self::pzarc_build($block, false),
                    'custom' => self::pzarc_custom($block, false),
          //          'info'   => null
          //					'info'	=> self::sab_info($block),
      );
      // Setup any optional messages you want displayed on each tabs' panel
//							View <a href="https://s3.amazonaws.com/341public/LATEST/versioninfo/pzarc-changelog.html" target=_blank>Change Log</a><br/>
//							Visit <a href="http://guides.pizazzwp.com/swiss-army-block/about-contentsplus/" target=_blank>Architect User Guide</a></br/>
      $this->tab_notices
          = array(
//          'info'   => '<strong>Support:</strong> Please support ret <a href="http://pizazzwp.zendesk.com" target=_blank>pizazzwp.zendesk.com</a> and log your question there.',
//         'custom' => 'If you set the Blueprint to custom, you can build you own here. You will still need pre-made Cells and Content Selections.',
      );
    }

    static function get_settings($block)
    {
      // use this function to retrieve block settings to an array to use in the content area of your block
      //
      // usage: $settings = HeadwayExampleBlockOptions::get_settings($block)
      //  or    $settings = HeadwayExampleBlockOptions::get_settings($block['id'])
      //
      // The $settings array will then contain all your block options
      // eg $settings['dob'], $settings['height'] etc

      // TODO: What if new style block ID?
      if (is_integer($block)) {
        $block = HeadwayBlocksData::get_block($block);
      }
      $settings = array();
      $options  = array_merge(
          self::pzarc_build($block, true),
          self::pzarc_custom($block, true)
      );
      foreach ($options as $option) {
        $settings[ $option[ 'name' ] ] = HeadwayBlockAPI::get_setting($block, $option[ 'name' ], $option[ 'default' ]);
      }

      return $settings;
    }

    static function pzarc_build($block, $just_defaults)
    {
      if (!$just_defaults) {
        global $pzarc_blueprints_list;
        if (empty($pzarc_blueprints_list)) {
          $pzarc_blueprints_list   = pzarc_get_posts_in_post_type('arc-blueprints',true);


        }

        $pzarc_blueprints_list = pzarc_get_blueprints(true);
        $pzarc_blueprints = array_merge(array('none' => 'Select blueprint'), $pzarc_blueprints_list,array('show-none' => 'DO NOT SHOW ANY BLUEPRINT'));
      } else {
        $pzarc_blueprints = array();

      }
      $settings = array(
          'pzarc-blueprint' => array(
              'type'    => 'select',
              'name'    => 'pzarc-blueprint',
              'label'   => __('Blueprint (required)', 'pzpzarc'),
              'default' => 'none',
              'options' => $pzarc_blueprints,
              'tooltip' => __('Choose a Blueprint to display on all devices (except when overridden by the phone or tablet Blueprint)', 'pzarchitect')
          ),
          'pzarc-blueprint-tablet' => array(
            'type'    => 'select',
            'name'    => 'pzarc-blueprint-tablet',
            'label'   => __('Blueprint Tablet (optional)', 'pzpzarc'),
            'default' => 'none',
            'options' => $pzarc_blueprints,
            'tooltip' => __('Choose a Blueprint to display on tablets', 'pzarchitect')
          ),
          'pzarc-blueprint-phone' => array(
            'type'    => 'select',
            'name'    => 'pzarc-blueprint-phone',
            'label'   => __('Blueprint Phone (optional)', 'pzpzarc'),
            'default' => 'none',
            'options' => $pzarc_blueprints,
            'tooltip' => __('Choose a Blueprint to display on phones', 'pzarchitect')
          ),


      );

      return $settings;
    }

    static function pzarc_custom($block, $just_defaults)
    {
      $taxonomy_list = array();
      if (!$just_defaults) {
        $taxonomy_list  = get_taxonomies( array(
                                              'public'   => true,
                                              '_builtin' => false

                                          ) );
        foreach ( $taxonomy_list as $k => $v ) {
          $tax_obj             = get_taxonomy( $k );
          $taxonomy_list[ $k ] = $tax_obj->labels->name;
        }
        $extras        = array( 0 => '', 'category' => 'Categories', 'post_tag' => 'Tags' );
        $taxonomy_list = $extras + $taxonomy_list;
      }
      $settings = array(
          'pzarc-overrides-ids' => array(
              'type'    => 'text',
              'name'    => 'pzarc-overrides-ids',
              'label'   => __('IDs', 'pzpzarc'),
              'default' => '',
              'tooltip' => __('Enter  comma separated list of IDs of content to display instead of the Blueprint\'s preset', 'pzarchitect')
          ),
          'pzarc-overrides-taxonomy' => array(
              'type'    => 'select',
              'name'    => 'pzarc-overrides-taxonomy',
              'label'   => __('Taxonomy', 'pzpzarc'),
              'default' => '',
              'tooltip' => __('', 'pzarchitect'),
              'options'=>$taxonomy_list
          ),
          'pzarc-overrides-terms' => array(
              'type'    => 'text',
              'name'    => 'pzarc-overrides-terms',
              'label'   => __('Terms', 'pzpzarc'),
              'default' => '',
              'tooltip' => __('Enter taxonomy terms as a comma separated list. This is the WP slug name of the term.', 'pzarchitect')
          ),
          'pzarc-overrides-page-title' => array(
              'type'    => 'checkbox',
              'name'    => 'pzarc-overrides-page-title',
              'label'   => __('Show page title', 'pzpzarc'),
              'default' => '',
              'tooltip' => __('', 'pzarchitect')
          ),

      );

      return $settings;
    }


    static function get_layouts($pzarc_inc_width)
    {
      // TODO: Offer alternative panels in overrides
      global $wp_query;
      $query_options = array(
          'post_type'      => 'arc-panels',
          'meta_key'       => '_architect',
          'posts_per_page' => '-1'
      );
      $layouts_query = new WP_Query($query_options);
      $pzarc_return  = array();
      while ($layouts_query->have_posts()) {
        $layouts_query->the_post();
        $the_panel_meta                                                        = get_post_meta($layouts_query->posts->ID);
        $pzarc_return[ $the_panel_meta[ '_panels_settings_short-name' ][ 0 ] ] = get_the_title($layouts_query->post->ID);
      };
      asort($pzarc_return);

      return $pzarc_return;
    }



  }

