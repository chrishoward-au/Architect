<?php
/* This class must be included in another file and included later so we don't get an error about HeadwayBlockOptionsAPI class not existing. */

class HeadwayContentPlusBlockOptions extends HeadwayBlockOptionsAPI {
	
	
	public $tabs = array();
	public $inputs = array();
	public $tab_notices = array();
	public $open_js_callback = '';

	function modify_arguments($args) {
		$block = $args['block'];
				$this->tabs =
					array(
						'sections'	=> 'Sections',
						'layout'	=> 'Layout',
						'content' => 'Content',
						'info' => 'Info'
					);

				// Setup the tab options		
				$this->inputs =
					array(
						'sections'	=> self::cplus_sections($block,false),
						'layout'	=> self::cplus_layout($block,false),
						'content'	=> self::cplus_content($block,false),
						'info'=>null
	//					'info'	=> self::sab_info($block),
					);
			// Setup any optional messages you want displayed on each tabs' panel			
		$this->tab_notices	=	
			array(
				'info' => '
							View <a href="https://s3.amazonaws.com/341public/LATEST/versioninfo/cplus-changelog.html" target=_blank>Change Log</a><br/>
							Visit <a href="http://guides.pizazzwp.com/swiss-army-block/about-contentsplus/" target=_blank>ContentPlus User Guide</a></br/>
							<strong>Support:</strong> Please go to the ContentPlus forum at <a href="http://support.headwaythemes.com" target=_blank>support.headwaythemes.com</a> and log your question there.
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

		if (is_integer($block)) {
			$block = HeadwayBlocksData::get_block($block);	
		} 
		$settings = array();
		$options = array_merge(	
					self::cplus_sections($block,true),
					self::cplus_layout($block,true),
					self::cplus_content($block,true)
					);
		foreach ($options as $option) {
			$settings[$option['name']] = HeadwayBlockAPI::get_setting($block,$option['name'],$option['default']);				
		}

		return $settings;
	
	}
	function cplus_sections($block,$just_defaults) {
		$contentplus_layouts = array();
		if (!$just_defaults) {
			$contentplus_layouts = self::contentplus_get_layouts(true);
			$contentplus_layouts = array_merge(array('none'=>'None selected'),$contentplus_layouts);
		}
		$settings = array(		
			'cplus-sections' => array(
				'type' => 'repeater',
				'name' => 'cplus-sections',
				'label' => 'Sections',
				'tooltip' => 'You can create sections in your ContentPlus block. For example, you might create two sections, one to show the first post in full, then a 3x3 grid of older posts, and then maybe a bulleted list of the next 10 posts. Maximum 3 sections',
				'default' => null,
				'inputs' => array(
					'cplus-cell-layout' => array(
						'type' => 'select',
						'name' => 'cplus-cell-layout',
						'label' => __('Cell layout','pzcplus'),
						'default' => 'pizazz1',
						'options' => $contentplus_layouts,
						'tooltip' => __('Choose a layout for the cells in this section. Layouts are created in WP admin in the PizazzWP > ContentPlus Layouts menu','pzcplus')
					),
					'cplus-cells-per-row-desktop' => array(
						'type' => 'integer',
						'name' => 'cplus-cells-per-row-desktop',
						'label' => __('Desktop cols','pzcplus'),
						'default' => 3,
						'tooltip' => __('Set the number of cells per row for this section when displayed on a desktop. Device dimensions can be defined in PizazzWP > Options menu.','pzcplus')
					),
					'cplus-cells-per-row-tablet' => array(
						'type' => 'integer',
						'name' => 'cplus-cells-per-row-tablet',
						'label' => __('Tablet cols','pzcplus'),
						'default' => 2,
						'tooltip' => __('Set the number of cells per row for this section when displayed on a tablet. Device dimensions can be defined in PizazzWP > Options menu.','pzcplus')
					),
					'cplus-cells-per-row-phone' => array(
						'type' => 'integer',
						'name' => 'cplus-cells-per-row-phone',
						'label' => __('Phone cols','pzcplus'),
						'default' => 1,
						'tooltip' => __('Set the number of cells per row for this section when displayed on a phone. Device dimensions can be defined in PizazzWP > Options menu.','pzcplus')
					),
					'cplus-number-to-show' => array(
						'type' => 'integer',
						'name' => 'cplus-number-to-show',
						'label' => __('Number to show','pzcplus'),
						'default' => 6,
						'tooltip' => __('Set the number of posts to show in this section. Each section will continue from the last.','pzcplus')
					),
				),
				'sortable' => true,
				'limit' => 2
			),
		);
		return $settings;
	}

	function cplus_layout($block) {
		$settings = array(		
			'cplus-paginate' => array(
				'type' => 'checkbox',
				'name' => 'cplus-paginate',
				'label' => __('Paginate','pzcplus'),
				'default' => false,
				'tooltip' => __('Enable pagination.','pzcplus')
			),
			'cplus-align-rows' => array(
				'type' => 'checkbox',
				'name' => 'cplus-align-rows',
				'label' => __('Align rows','pzcplus'),
				'default' => false,
				'tooltip' => __('Enabling this makes each cell in a row the same vertical position.','pzcplus')
			),
			'cplus-default-display' => array(
				'type' => 'checkbox',
				'name' => 'cplus-default-display',
				'label' => __('Use page defaults','pzcplus'),
				'default' => true,
				'tooltip' => __('Use the page\'s default content.','pzcplus'),
				'callback' => ''
			),
		);
		return $settings;
	}

	function cplus_content($block,$just_defaults) {
		$all_post_types = array('post'=>'Posts', 'page'=>'Pages');

		if ($just_defaults=='no') {
			$all_post_types = array('post'=>'Posts', 'page'=>'Pages');
			$args=array(
				'public'   => true,
				'_builtin' => false
			);
			$output = 'objects'; // names or objects
			$operator = 'and'; // 'and' or 'or'
			$post_types=get_post_types($args, $output, $operator);
			foreach ($post_types  as $post_type ) {
				$all_post_types[$post_type->name] = $post_type->label;
			}
			// Check for update
		}
		$settings = array(
			'cplus-content-type' => array(
				'type' => 'multi-select',
				'options' => $all_post_types,
				'label' => 'Content type',
				'tooltip' => 'Choose the content type you want to display: Posts, pages or custom post types (if available).',
				'default' => 'post',
				'name' => 'cplus-content-type',
			),
		);
		return $settings;
	}
	
	function contentplus_get_layouts($cplus_inc_width) {
			global $wp_query;
			$query_options = array(
				'post_type' => 'contentplus-layouts',
				'meta_key' => 'contentplus_layout-short-name',
			);

			$layouts_query = new WP_Query($query_options);
			$cplus_return = array();
			while ($layouts_query->have_posts()) {
				$layouts_query->the_post();
					$cplus_settings = get_post_custom();
					$cplus_return[$cplus_settings['contentplus_layout-short-name'][0]] = get_the_title();
			};


			return $cplus_return;
	}
	
}