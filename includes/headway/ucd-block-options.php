<?php

/* This class must be included in another file and included later so we don't get an error about HeadwayBlockOptionsAPI class not existing. */

class HeadwayUltimateContentDisplayBlockOptions extends HeadwayBlockOptionsAPI {

	public $tabs							 = array();
	public $inputs						 = array();
	public $tab_notices			 = array();
	public $open_js_callback	 = '';

	function modify_arguments($args) {
		$block			 = $args['block'];
		$this->tabs	 =
				array(
					'sections' => 'Sections',
					'info'		 => 'Info'
		);

		// Setup the tab options		
		$this->inputs			 =
				array(
					'sections' => self::pzucd_sections($block, false),
					'info'		 => null
				//					'info'	=> self::sab_info($block),
		);
		// Setup any optional messages you want displayed on each tabs' panel			
		$this->tab_notices =
				array(
					'info' => '
							View <a href="https://s3.amazonaws.com/341public/LATEST/versioninfo/pzucd-changelog.html" target=_blank>Change Log</a><br/>
							Visit <a href="http://guides.pizazzwp.com/swiss-army-block/about-contentsplus/" target=_blank>UltimateContentDisplay User Guide</a></br/>
							<strong>Support:</strong> Please go to PizazzWP support at <a href="http://pizazzwp.zendesk.com" target=_blank>pizazzwp.zendesk.com</a> and log your question there.
				',
		);
	}

	function get_settings($block) {
		// use this function to retrieve block settings to an array to use in the content area of your block
		//
		// usage: $settings = HeadwayExampleBlockOptions::get_settings($block)
		//  or    $settings = HeadwayExampleBlockOptions::get_settings($block['id'])
		//
		// The $settings array will then contain all your block options 
		// eg $settings['dob'], $settings['height'] etc

		if (is_integer($block))
		{
			$block = HeadwayBlocksData::get_block($block);
		}
		$settings	 = array();
		$options	 = array_merge(
				self::pzucd_sections($block, true)
		);
		foreach ($options as $option)
		{
			$settings[$option['name']] = HeadwayBlockAPI::get_setting($block, $option['name'], $option['default']);
		}

		return $settings;
	}

	function pzucd_sections($block, $just_defaults) {
		$pzucd_layouts = array();
		if (!$just_defaults)
		{
			$pzucd_layouts	 = self::pzucd_get_layouts(true);
			$pzucd_layouts	 = array_merge(array('none' => 'None selected'), $pzucd_layouts);
			$pzucd_templates = self::pzucd_get_templates(true);
			$pzucd_templates = array_merge(array('none' => 'None selected'), $pzucd_templates);
			$pzucd_content	 = self::pzucd_get_content(true);
			$pzucd_content	 = array_merge(array('none' => 'None selected'), $pzucd_content);
		}
		$settings = array(
			'pzucd-sections' => array(
				'type'		 => 'repeater',
				'name'		 => 'pzucd-sections',
				'label'		 => 'Sections',
				'tooltip'	 => 'You can create sections in your Ultimate ContentDisplay block. For example, you might create two sections, one to show the first post in full, then a 3x3 grid of older posts, and then maybe a bulleted list of the next 10 posts. Maximum 3 sections',
				'default'	 => null,
				'inputs'	 => array(
					'pzucd-cell-layout'	 => array(
						'type'		 => 'select',
						'name'		 => 'pzucd-cell-layout',
						'label'		 => __('Cell layout set', 'pzpzucd'),
						'default'	 => 'none',
						'options'	 => $pzucd_layouts,
						'tooltip'	 => __('Choose a set of layouts for the cells in this section. Layouts are created in WP admin in the PizazzWP > UltimateContentDisplay Layouts menu', 'pzpzucd')
					),
					'pzucd-content'			 => array(
						'type'		 => 'select',
						'name'		 => 'pzucd-content',
						'label'		 => __('Content', 'pzpzucd'),
						'default'	 => 'none',
						'options'	 => $pzucd_content,
						'tooltip'	 => __('Choose a set of layouts for the cells in this section. Layouts are created in WP admin in the PizazzWP > UltimateContentDisplay Layouts menu', 'pzpzucd')
					),
					'pzucd-template'		 => array(
						'type'		 => 'select',
						'name'		 => 'pzucd-template',
						'label'		 => __('Template', 'pzpzucd'),
						'default'	 => 'none',
						'options'	 => $pzucd_templates,
						'tooltip'	 => __('Choose a set of layouts for the cells in this section. Layouts are created in WP admin in the PizazzWP > UltimateContentDisplay Layouts menu', 'pzpzucd')
					),
				),
				'sortable' => true,
				'limit'		 => 2
			),
		);
		return $settings;
	}

	/*
	  function pzucd_layout($block) {
	  $settings = array(
	  'pzucd-layout-method' => array(
	  'type'		 => 'slect',
	  'name'		 => 'pzucd-layout-method',
	  'label'		 => __('Layout method', 'pzpzucd'),
	  'default'	 => 'grid',
	  'options'	 => array(
	  'grid'	 => 'Grid',
	  'tabs'	 => 'Tabbbed',
	  'slider' => 'Slider'
	  ),
	  'tooltip'	 => __('Select the method for laying out this block\'s content.', 'pzpzucd')
	  ),
	  'pzucd-content-source' => array(
	  'type'		 => 'select',
	  'name'		 => 'pzucd-content-source',
	  'label'		 => __('Content source', 'pzpzucd'),
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
	  'tooltip'	 => __('THIS IS JUST A FURPHY TO REMIND ME TO THINK THIS WAY.', 'pzpzucd')
	  ),
	  'pzucd-paginate'				 => array(
	  'type'		 => 'checkbox',
	  'name'		 => 'pzucd-paginate',
	  'label'		 => __('Paginate', 'pzpzucd'),
	  'default'	 => false,
	  'tooltip'	 => __('Enable pagination.', 'pzpzucd')
	  ),
	  'pzucd-align-rows'			 => array(
	  'type'		 => 'checkbox',
	  'name'		 => 'pzucd-align-rows',
	  'label'		 => __('Align rows', 'pzpzucd'),
	  'default'	 => false,
	  'tooltip'	 => __('Enabling this makes each cell in a row the same vertical position.', 'pzpzucd')
	  ),
	  'pzucd-default-display'	 => array(
	  'type'		 => 'checkbox',
	  'name'		 => 'pzucd-default-display',
	  'label'		 => __('Use page defaults', 'pzpzucd'),
	  'default'	 => true,
	  'tooltip'	 => __('Use the page\'s default content.', 'pzpzucd'),
	  'callback' => ''
	  ),
	  );
	  return $settings;
	  }

	  function pzucd_content($block, $just_defaults) {
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
	  'pzucd-content-type' => array(
	  'type'		 => 'multi-select',
	  'options'	 => $all_post_types,
	  'label'		 => 'Content type',
	  'tooltip'	 => 'Choose the content type you want to display: Posts, pages or custom post types (if available).',
	  'default'	 => 'post',
	  'name'		 => 'pzucd-content-type',
	  ),
	  );
	  return $settings;
	  }
	 */

	function pzucd_get_layouts($pzucd_inc_width) {
		global $wp_query;
		$query_options = array(
			'post_type'	 => 'ucd-layouts',
			'meta_key'	 => 'pzucd_layout-short-name',
		);
		$layouts_query = new WP_Query($query_options);
		$pzucd_return	 = array();
		while ($layouts_query->have_posts())
		{
			$layouts_query->the_post();
			$pzucd_settings = get_post_custom();

			//				if (!array_key_exists($pzucd_settings['pzucd_layout-set-name'][0],$pzucd_return)) {
			$pzucd_return[$pzucd_settings['pzucd_layout-set-name'][0]] = $pzucd_settings['pzucd_layout-set-name'][0];
			//				} else {
			//					preg_match("/(\\d)*(?=\\))/u", $pzucd_return[$pzucd_settings['pzucd_layout-set-name'][0]],$matches);
			//					$pzucd_return[$pzucd_settings['pzucd_layout-set-name'][0]] = $pzucd_settings['pzucd_layout-set-name'][0].' ('.($matches[0]+1).')';
			//				}
		};


		return $pzucd_return;
	}

	function pzucd_get_templates($pzucd_inc_width) {
		global $wp_query;
		$query_options	 = array(
			'post_type'	 => 'ucd-templates',
			'meta_key'	 => 'pzucd_template-short-name',
		);
		$templates_query = new WP_Query($query_options);
		$pzucd_return		 = array();
		while ($templates_query->have_posts())
		{
			$templates_query->the_post();
			$pzucd_settings = get_post_custom();

			//				if (!array_key_exists($pzucd_settings['pzucd_layout-set-name'][0],$pzucd_return)) {
			$pzucd_return[$pzucd_settings['pzucd_template-set-name'][0]] = $pzucd_settings['pzucd_template-set-name'][0];
			//				} else {
			//					preg_match("/(\\d)*(?=\\))/u", $pzucd_return[$pzucd_settings['pzucd_layout-set-name'][0]],$matches);
			//					$pzucd_return[$pzucd_settings['pzucd_layout-set-name'][0]] = $pzucd_settings['pzucd_layout-set-name'][0].' ('.($matches[0]+1).')';
			//				}
		};


		return $pzucd_return;
	}

	function pzucd_get_content($pzucd_inc_width) {
		global $wp_query;
		$query_options = array(
			'post_type'	 => 'ucd-criterias',
			'meta_key'	 => 'pzucd_criteria-name',
		);
		$content_query = new WP_Query($query_options);
		$pzucd_return	 = array();
		while ($content_query->have_posts())
		{
			$content_query->the_post();
			$pzucd_settings = get_post_custom();

			//				if (!array_key_exists($pzucd_settings['pzucd_layout-set-name'][0],$pzucd_return)) {
			$pzucd_return[$pzucd_settings['pzucd_criteria-name'][0]] = $pzucd_settings['pzucd_criteria-name'][0];
			//				} else {
			//					preg_match("/(\\d)*(?=\\))/u", $pzucd_return[$pzucd_settings['pzucd_layout-set-name'][0]],$matches);
			//					$pzucd_return[$pzucd_settings['pzucd_layout-set-name'][0]] = $pzucd_settings['pzucd_layout-set-name'][0].' ('.($matches[0]+1).')';
			//				}
		};


		return $pzucd_return;
	}

}

