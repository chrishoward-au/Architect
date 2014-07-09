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
          'custom' => 'Custom',
//          'info'   => 'Info'
      );

      // Setup the tab options
      $this->inputs
          = array(
          'build'  => self::pzarc_build($block, false),
//          'custom' => self::pzarc_custom($block, false),
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
      $pzarc_layouts = array();
      if (!$just_defaults) {
//      $pzarc_layouts    = self::get_layouts(true);
//
////      $pzarc_layouts    = array_merge(array('none' => 'None selected'), $pzarc_layouts);
        $pzarc_blueprints = self::get_blueprints(true);
//        $pzarc_blueprints = array_merge(array('custom' => 'Custom blueprint'), $pzarc_blueprints);
        $pzarc_blueprints = array_merge(array('none' => 'Select blueprint'), $pzarc_blueprints);
      } else {
        $pzarc_layouts    = array();
        $pzarc_blueprints = array();
        $pzarc_content    = array();

      }
      $settings = array(
//			'pzarc-sections' => array(
//				'type'		 => 'repeater',
//				'name'		 => 'pzarc-sections',
//				'label'		 => 'Sections',
//				'tooltip'	 => 'You can create sections in your Architect block. For example, you might create two sections, one to show the first post in full, then a 3x3 grid of older posts, and then maybe a bulleted list of the next 10 posts. Maximum 3 sections',
//				'default'	 => null,
//				'inputs'	 => array(
//					'pzarc-cell-layout'	 => array(
//						'type'		 => 'select',
//						'name'		 => 'pzarc-cell-layout',
//						'label'		 => __('Cell layout set', 'pzpzarc'),
//						'default'	 => 'none',
//						'options'	 => $pzarc_layouts,
//						'tooltip'	 => __('Choose a set of layouts for the cells in this section. Layouts are created in WP admin in the PizazzWP > UltimateContentDisplay Layouts menu', 'pzpzarc')
//					),
//					'pzarc-content'			 => array(
//						'type'		 => 'select',
//						'name'		 => 'pzarc-content',
//						'label'		 => __('Content', 'pzpzarc'),
//						'default'	 => 'none',
//						'options'	 => $pzarc_content,
//						'tooltip'	 => __('Choose a set of layouts for the cells in this section. Layouts are created in WP admin in the PizazzWP > UltimateContentDisplay Layouts menu', 'pzpzarc')
//					),
//					'pzarc-blueprint'		 => array(
//						'type'		 => 'select',
//						'name'		 => 'pzarc-blueprint',
//						'label'		 => __('Blueprint', 'pzpzarc'),
//						'default'	 => 'none',
//						'options'	 => $pzarc_blueprints,
//						'tooltip'	 => __('Choose a set of layouts for the cells in this section. Layouts are created in WP admin in the PizazzWP > UltimateContentDisplay Layouts menu', 'pzpzarc')
//					),
//				),
//				'sortable' => true,
//				'limit'		 => 2
//			),
'pzarc-blueprint' => array(
    'type'    => 'select',
    'name'    => 'pzarc-blueprint',
    'label'   => __('Blueprint', 'pzpzarc'),
    'default' => 'none',
    'options' => $pzarc_blueprints,
    'tooltip' => __('Choose a set of layouts for the cells in this section. Layouts are created in WP admin in the PizazzWP > Architect Layouts menu', 'pzpzarc')
),


      );

      return $settings;
    }

    static function pzarc_custom($block, $just_defaults)
    {
      if (!$just_defaults) {
      }
      $settings = array();

      return $settings;
    }

    /*
      function pzarc_layout($block) {
      $settings = array(
      'pzarc-layout-method' => array(
      'type'		 => 'slect',
      'name'		 => 'pzarc-layout-method',
      'label'		 => __('Layout method', 'pzpzarc'),
      'default'	 => 'grid',
      'options'	 => array(
      'grid'	 => 'Grid',
      'tabs'	 => 'Tabbbed',
      'slider' => 'Slider'
      ),
      'tooltip'	 => __('Select the method for laying out this block\'s content.', 'pzpzarc')
      ),
      'pzarc-content-source' => array(
      'type'		 => 'select',
      'name'		 => 'pzarc-content-source',
      'label'		 => __('Content source', 'pzpzarc'),
      'default'	 => 'page',
      'options'	 => array(
      'posts'				 => 'Posts',
      'pages'				 => 'Pages',
      'gallery'			 => 'Gallery',
      'widgets'			 => 'Widgets',
      'blocks'			 => 'Blocks',
      'custom-code'	 => 'Custom code',
      'shortcode'		 => 'Shortcodes'
      ),
      'tooltip'	 => __('THIS IS JUST A FURPHY TO REMIND ME TO THINK THIS WAY.', 'pzpzarc')
      ),
      'pzarc-paginate'				 => array(
      'type'		 => 'checkbox',
      'name'		 => 'pzarc-paginate',
      'label'		 => __('Paginate', 'pzpzarc'),
      'default'	 => false,
      'tooltip'	 => __('Enable pagination.', 'pzpzarc')
      ),
      'pzarc-align-rows'			 => array(
      'type'		 => 'checkbox',
      'name'		 => 'pzarc-align-rows',
      'label'		 => __('Align rows', 'pzpzarc'),
      'default'	 => false,
      'tooltip'	 => __('Enabling this makes each cell in a row the same vertical position.', 'pzpzarc')
      ),
      'pzarc-default-display'	 => array(
      'type'		 => 'checkbox',
      'name'		 => 'pzarc-default-display',
      'label'		 => __('Use page defaults', 'pzpzarc'),
      'default'	 => true,
      'tooltip'	 => __('Use the page\'s default content.', 'pzpzarc'),
      'callback' => ''
      ),
      );
      return $settings;
      }

      function pzarc_content($block, $just_defaults) {
      $all_post_types = array('post' => 'Posts', 'page' => 'Pages');

      if ($just_defaults == 'no')
      {
      $all_post_types	 = array('post' => 'Posts', 'page' => 'Pages');
      $args						 = array(
      'public'	 => true,
      '_builtin' => false
      );
      $output					 = 'objects'; // names or objects
      $operator				 = 'and'; // 'and' or 'or'
      $post_types			 = get_post_types($args, $output, $operator);
      foreach ($post_types as $post_type)
      {
      $all_post_types[$post_type->name] = $post_type->label;
      }
      // Check for update
      }
      $settings = array(
      'pzarc-content-type' => array(
      'type'		 => 'multi-select',
      'options'	 => $all_post_types,
      'label'		 => 'Content type',
      'tooltip'	 => 'Choose the content type you want to display: Posts, pages or custom post types (if available).',
      'default'	 => 'post',
      'name'		 => 'pzarc-content-type',
      ),
      );
      return $settings;
      }
     */

    static function get_layouts($pzarc_inc_width)
    {
      global $wp_query;
      $query_options = array(
          'post_type' => 'arc-panels',
          'meta_key'  => '_architect',
          'posts_per_page'=>'-1'
      );
      $layouts_query = new WP_Query($query_options);
      $pzarc_return  = array();
      while ($layouts_query->have_posts()) {
        $layouts_query->the_post();
        $the_panel_meta                                                   = get_post_meta($layouts_query->posts->ID);
        $pzarc_return[ $the_panel_meta[ '_panels_settings_short-name' ][0] ] = get_the_title($layouts_query->post->ID);
      };
       asort($pzarc_return);

      return $pzarc_return;
    }

    static function get_blueprints($pzarc_inc_width)
    {
      global $wp_query;
      $query_options    = array(
          'post_type' => 'arc-blueprints',
          'meta_key'  => '_architect',
          'posts_per_page'=>'-1'
      );
      $blueprints_query = new WP_Query($query_options);
      $pzarc_return     = array();
      while ($blueprints_query->have_posts()) {
        $blueprints_query->the_post();
        $the_panel_meta = get_post_meta($blueprints_query->post->ID);

        $pzarc_return[ $the_panel_meta[ '_blueprints_short-name' ][0] . '##' . $blueprints_query->post->ID ] = get_the_title($blueprints_query->post->ID);
      };
       asort($pzarc_return);

      return $pzarc_return;
    }


  }

