<?php

  /* This class must be included in another file and included later so we don't get an error about BloxBlockOptionsAPI class not existing. */

  class BloxArchitectBlockOptions extends BloxBlockOptionsAPI
  {

    public $tabs = array();
    public $inputs = array();
    public $tab_notices = array();
    public $open_js_callback = '';

    function modify_arguments($args)
    {
      $block = $args[ 'block' ];
      $this->tabs
             = array(
          'build'  => 'Blueprint',
          'custom' => 'Filters',
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
      // usage: $settings = BloxExampleBlockOptions::get_settings($block)
      //  or    $settings = BloxExampleBlockOptions::get_settings($block['id'])
      //
      // The $settings array will then contain all your block options
      // eg $settings['dob'], $settings['height'] etc

      // TODO: What if new style block ID?
      if (is_integer($block)) {
        $block = BloxBlocksData::get_block($block);
      }
      $settings = array();
      $options  = array_merge(
          self::pzarc_build($block, true),
          self::pzarc_custom($block, true)
      );
      foreach ($options as $option) {
        if ($option['type'] !== 'heading') {
          $settings[ $option['name'] ] = BloxBlockAPI::get_setting( $block, $option['name'], $option['default'] );
        }
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
          'pzarc-overrides-heading'    => array(
              'name'  => 'overrides-heading',
              'type'  => 'heading',
              'label' => 'Overrides'
          ),
          'pzarc-overrides-ids' => array(
              'type'    => 'text',
              'name'    => 'pzarc-overrides-ids',
              'label'   => __('Override IDs', 'pzpzarc'),
              'default' => '',
              'tooltip' => __('Enter comma separated list of IDs of content to display instead of the Blueprint\'s preset. Note: This should be the same content type as the Blueprint was created.', 'pzarchitect')
          ),
          'pzarc-panels-per-view'   => array(
              'name'=>'pzarc-panels-per-view',
              'type'    => 'text',
              'label'   => __('Override number of posts to show', 'pzarchitect'),
              'default' => '',
              'tooltip'=>__('Note: If the selection contains sticky posts, these will affect this limit.','pzarchitect')
          ),
          'pzarc-blueprint-title'   => array(
              'name'=>'pzarc-blueprint-title',
              'type'    => 'text',
              'label'   => __('Override Blueprint display title', 'pzarchitect'),
              'default' => '',
          ),
          'pzarc-overrides-page-title' => array(
              'type'    => 'checkbox',
              'name'    => 'pzarc-overrides-page-title',
              'label'   => __('Show page title', 'pzpzarc'),
              'default' => '',
              'tooltip' => __('', 'pzarchitect')
          ),
          'pzarc-custom-overrides'   => array(
              'name'=>'pzarc-custom-overrides',
              'type'    => 'textarea',
              'label'   => __('Custom overrides', 'pzarchitect'),
              'default' => '',
              'tooltip'=>__('Enter shortcode parameter style. e.g rssurl="http://myurl.com/feed" count="5"','pzarchitect')
          ),


      );

      return $settings;
    }

    static function pzarc_custom($block, $just_defaults)
    {
      $taxonomy_list = array();
      $catlist = array();
      $taglist=array();
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
        $catlist = pzarc_get_terms( 'category', array(
            'hide_empty' => false,
        ),true,true );
        $taglist = pzarc_get_terms( 'post_tag', array(
            'hide_empty' => false,
        ),true,true );
      }
      $settings = array(
          'pzarc-categories-heading'    => array(
              'name'  => 'categories-heading',
              'type'  => 'heading',
              'label' => 'Categories'
          ),
          'pzarc-category__in'     => array(
              'name'=>'pzarc-category__in',
              'type'   => 'multi-select',
              'options'=>$catlist,
              'label'  => __('Include Categories', 'pzarchitect'),
              'default' => '',
          ),
          'pzarc-cat_all_any'      => array(
              'name'=>'pzarc-cat_all_any',
              'type'    => 'select',
              'label'   => __('In ANY or ALL categories', 'pzarchitect'),
              'default' => 'any',
              'options' => array(
                  'any' => __('Any', 'pzarchitect'),
                  'all' => __('All', 'pzarchitect'),
              ),
          ),
          'pzarc-category__not_in' => array(
              'name'=>'pzarc-category__not_in',
              'type'   => 'multi-select',
              'options'=>$catlist,
              'label'  => __('Exclude Categories', 'pzarchitect'),
              'default' => '',
          ),
          'pzarc-tags-heading'    => array(
              'name'  => 'tags-heading',
              'type'  => 'heading',
              'label' => 'Tags'
          ),
          'pzarc-tag__in'          => array(
              'name'=>'pzarc-tag__in',
              'type'   => 'multi-select',
              'options'=>$taglist,
              'label'  => __('Include Tags', 'pzarchitect'),
              'default' => '',
          ),
          'pzarc-tag_all_any'      => array(
              'name'=>'pzarc-tag_all_any',
              'type'    => 'select',
              'label'   => __('In ANY or ALL categories', 'pzarchitect'),
              'default' => 'any',
              'options' => array(
                  'any' => __('Any', 'pzarchitect'),
                  'all' => __('All', 'pzarchitect'),
              ),
          ),
          'pzarc-tag__not_in'      => array(
              'name'=>'pzarc-tag__not_in',
              'type'   => 'multi-select',
              'options'=>$taglist,
              'label'  => __('Exclude Tags', 'pzarchitect'),
              'default' => '',
          ),
          'pzarc-custom-heading'    => array(
              'name'  => 'custom-heading',
              'type'  => 'heading',
              'label' => 'Custom'
          ),
          'pzarc-overrides-taxonomy' => array(
              'type'    => 'select',
              'name'    => 'pzarc-overrides-taxonomy',
              'label'   => __('Other Taxonomy', 'pzpzarc'),
              'default' => '',
              'tooltip' => __('', 'pzarchitect'),
              'options'=>$taxonomy_list
          ),
          'pzarc-overrides-terms' => array(
              'type'    => 'text',
              'name'    => 'pzarc-overrides-terms',
              'label'   => __('Taxonomy Terms', 'pzpzarc'),
              'default' => '',
              'tooltip' => __('Enter taxonomy terms as a comma separated list. This is the WP slug name of the term.', 'pzarchitect')
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

