<?php

/* This class must be included in another file and included later so we don't get an error about HeadwayBlockAPI class not existing. */

class HeadwayUltimateContentDisplayBlock extends HeadwayBlockAPI {

	public $id										 = 'ultimatecontentdisplay-block';
	public $name									 = 'Ultimate Content Display';
	public $options_class				 = 'HeadwayUltimateContentDisplayBlockOptions';
	public $description					 = 'Display your content any way you want';
	public $pzucd_cells_template	 = '
				<div class="pzucd_cell" style="width:%cells-per-row%;">
				    <div class="pzucd_cell_header">
				        <div class="pzucd_cell_header_left">%header-left%</div>
				        <div class="pzucd_cell_header_right">%header-right%</div>
				    </div>
				    <div class="pzucd_clearfloat"></div>
				    <div class="pzucd_cell_columns">
				        <div class= "pzucd_cell_left_col">%column-left%</div>
				        <div class= "pzucd_cell_centre_col">%column-centre%</div>
				        <div class="pzucd_cell_right_col">%column-right%</div>
				    </div>
				    <div class="pzucd_clearfloat"></div>
				    <div class="pzucd_cell_footer">
				        <div class="pzucd_cell_footer_left">%footer-left%</div>
				        <div class="pzucd_cell_footer_right">%footer-right%</div>
				    </div>
				    <div class="pzucd_clearfloat"></div>
				</div>
		';
	public $pzucd_cells					 = '';

	/**
	 * Use this to enqueue styles or scripts for your block.  This method will be execute when the block type is on 
	 * the current page you are viewing.  Also, not only is it page-specific, the method will execute for every instance
	 * of that block type on the current page.
	 * 
	 * This method will be executed at the WordPress 'wp' hook
	 * */
	function enqueue_action($block_id, $layout) {

		$block = HeadwayBlocksData::get_block($block_id);

		//If it's a mirrored block, enqueue the scripts for that block instead
		if (headway_get('mirror-block', $block['settings'], '') !== '')
		{
			$block = HeadwayBlocksData::get_block($block['settings']['mirror-block']);
		}

		wp_enqueue_style('pzucd-block-css', PZUCD_PLUGIN_URL . '/includes/frontend/css/ucd-front.css');

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-isotope', PZUCD_PLUGIN_URL . '/includes/external/js/jquery.isotope.min.js');
//		wp_enqueue_script('jquery-masonry');

		return;
	}

	/**
	 * Use this method to register sidebars, menus, or anything to that nature.  This method executes for every single block that
	 * has this method defined.
	 * 
	 * The method will execute for every single block on every single layout.
	 * */
	function init_action($block_id, $layout) {

		return;
	}

	/**
	 * Use this to insert dynamic JS into the page needed.  This is perfect for initializing instances of jQuery Cycle, jQuery Tabs, etc.
	 * */
	function js_content($block_id, $layout) {
		$block = HeadwayBlocksData::get_block($block_id);
		if (headway_get('mirror-block', $block['settings'], '') !== '')
		{
			$block = HeadwayBlocksData::get_block($block['settings']['mirror-block']);
		}

		$return_js = '';
		$settings	 = HeadwayUltimateContentDisplayBlockOptions::get_settings($block);




		if (!isset($settings['pzucd-sections']))
		{
			return null;
		}

		//$pzucd_sections = !empty($settings['pzucd-sections'])?$settings['pzucd-sections']:array('pzucd-cell-layout'=>'stock1','pzucd-cells-per-row'=>3,'pzucd-number-to-show'=>6);
		$pzucd_sections = (!isset($pzucd_sections[0])) ? array($pzucd_sections) : $pzucd_sections;
		var_dump($pzucd_sections);

		$return_js = "jQuery(document).ready(function(){ ";

		foreach ($pzucd_sections as $key => $pzucd_section)
		{
			// $return_js .= "
			// 	var section = jQuery('#block-".$block_id." .pzucd_section_".$key."');
			// 	var cell = jQuery('#block-".$block_id." .pzucd_section_".$key." .pzucd_cell');
			// 	var gutterWidth = 10;
			// 	section.masonry({
			// 		itemSelector: '.pzucd_cell',
			// 			gutter : gutterWidth,
			// 			columnWidth: function( containerWidth ) {
			// 					if (jQuery(window).width() < ".$settings['pzucd-width-tablet-lower']." ) {
			// 						var columns = ".$pzucd_section['pzucd-cells-per-row-phone'].";
			// 					} else if (jQuery(window).width() < ".$settings['pzucd-width-tablet-upper']." ) {
			// 						var columns = ".$pzucd_section['pzucd-cells-per-row-tablet'].";
			// 					} else {
			// 						var columns = ".$pzucd_section['pzucd-cells-per-row-desktop'].";
			// 					}
			// 					var newWidth = Math.floor((containerWidth - (gutterWidth*(columns-1))) / columns); 
			// 			    return newWidth;
			// 		  }
			//  			});
			// 	";
			$pzucd_col_width = HeadwayBlocksData::get_block_width($block['id']) / $pzucd_section['pzucd-cells-per-row-desktop'];

			// if (jQuery(window).width() < ".$settings['pzucd-width-tablet-lower']." ) {
			// 	var columns = ".$pzucd_section['pzucd-cells-per-row-phone'].";
			// } else if (jQuery(window).width() < ".$settings['pzucd-width-tablet-upper']." ) {
			// 	var columns = ".$pzucd_section['pzucd-cells-per-row-tablet'].";
			// } else {
			// 	var columns = ".$pzucd_section['pzucd-cells-per-row-desktop'].";
			// }
			// jQuery(window).smartresize(function() {
			// 		if (jQuery(window).width() < ".$settings['pzucd-width-tablet-lower']." ) {
			// 			var columns = ".$pzucd_section['pzucd-cells-per-row-phone'].";
			// 		} else if (jQuery(window).width() < ".$settings['pzucd-width-tablet-upper']." ) {
			// 			var columns = ".$pzucd_section['pzucd-cells-per-row-tablet'].";
			// 		} else {
			// 			var columns = ".$pzucd_section['pzucd-cells-per-row-desktop'].";
			// 		}
			// 		var newWidth = section.width()/columns;
			// 		cell.css({width: newWidth});
			// 		console.log(newWidth);
			// 		console.log(cell.width(),section.width());
			// 	});

			$return_js .= "
				var section = jQuery('#block-" . $block_id . " .pzucd_section_" . $key . "');
				var cell = jQuery('#block-" . $block_id . " .pzucd_section_" . $key . " .pzucd_cell');
				section.isotope({
				  // options
				  itemSelector : '.pzucd_cell',
				  layoutMode : 'masonry',
				  masonry : {
//				  	columnWidth : section.width()/columns,
				  	columnWidth : " . $pzucd_col_width . ",
					  gutterWidth : 10
				  }
				});
			";
		}

		$return_js .= "});";

		return $return_js;
	}

	function setup_elements() {
		$this->register_block_element(array(
			'id'							 => 'pzucd-cells',
			'name'						 => 'Cells',
			'selector'				 => '.pzucd_cell',
			'properties'			 => array('background', 'borders', 'padding', 'rounded-corners', 'box-shadow', 'fonts'),
			'inherit-location' => 'text'
		));
		$this->register_block_element(array(
			'id'							 => 'pzucd-entry-title',
			'name'						 => 'Entry title',
			'selector'				 => '.pzucd_entry_title, .pzucd_entry_title a',
			'properties'			 => array('background', 'borders', 'padding', 'rounded-corners', 'box-shadow', 'fonts'),
			'inherit-location' => 'text'
		));
	}

	/**
	 * Anything in here will be displayed when the block is being displayed.
	 * */
	function content($block) {
		global $wp_query;
		//The third argument in the following function is the default that will be returned if the setting is not present in the database
		$settings = HeadwayUltimateContentDisplayBlockOptions::get_settings($block);
		pzdebug($settings);
		// Either going to have to pre-grab content, or setup a loop here. :/

		pzucd_display($settings['pzucd-sections'][0]['pzucd-cell-layout'], $settings['pzucd-sections'][0]['pzucd-content'], $settings['pzucd-sections'][0]['pzucd-template']);
		return;

		if (!isset($settings['pzucd-content-type']))
		{
			$pzucd_content_types = 'any';
		}
		elseif (is_array($settings['pzucd-content-type']))
		{
			$pzucd_content_types = implode(',', $settings['pzucd-content-type']);
		}
		else
		{
			$pzucd_content_types = $settings['pzucd-content-type'];
		}

		$pzucd_query_str = 'posts_per_page=-1&post_type=' . $pzucd_content_types;

		$pzucd_query			 = new WP_Query($pzucd_query_str);
		$pzucd_cell_margin = 2;

		if (!isset($settings['pzucd-sections']))
		{
			echo 'UCD: Nothing to display';
			return null;
		}

		$pzucd_sections	 = !empty($settings['pzucd-sections']) ? $settings['pzucd-sections'] : array('pzucd-cell-layout'		 => 'stock1', 'pzucd-cells-per-row'	 => 3, 'pzucd-number-to-show' => 6);
		$pzucd_sections	 = (!isset($pzucd_sections[0])) ? array($pzucd_sections) : $pzucd_sections;

		$pzucd_layouts = self::get_all_layouts();


		foreach ($pzucd_sections as $key => $pzucd_section)
		{

			$pzucd_column_count = 1;

			$this->pzucd_cells_template = str_replace('%cells-per-row%', ((HeadwayBlocksData::get_block_width($block['id']) - 10) / $pzucd_section['pzucd-cells-per-row-desktop']) . 'px', $this->pzucd_cells_template);
//			$this->pzucd_cells_template = str_replace('%cells-per-row%',(100/$pzucd_section['pzucd-cells-per-row-desktop']-$pzucd_cell_margin).'%',$this->pzucd_cells_template);

			if ($pzucd_query->have_posts())
			{
				$pzucd_location									 = array();
				$pzucd_layout										 = $pzucd_layouts[$pzucd_section['pzucd-cell-layout']];
				$pzucd_location['header-left']	 = (isset($pzucd_layout['ucd_layout-header-left'])) ? $pzucd_layout['ucd_layout-header-left'] : '';
				$pzucd_location['header-right']	 = (isset($pzucd_layout['ucd_layout-header-right'])) ? $pzucd_layout['ucd_layout-header-right'] : '';
				$pzucd_location['column-left']	 = (isset($pzucd_layout['ucd_layout-column-left'])) ? $pzucd_layout['ucd_layout-column-left'] : '';
				$pzucd_location['column-centre'] = (isset($pzucd_layout['ucd_layout-column-centre'])) ? $pzucd_layout['ucd_layout-column-centre'] : '';
				$pzucd_location['column-right']	 = (isset($pzucd_layout['ucd_layout-column-right'])) ? $pzucd_layout['ucd_layout-column-right'] : '';
				$pzucd_location['footer-left']	 = (isset($pzucd_layout['ucd_layout-footer-left'])) ? $pzucd_layout['ucd_layout-footer-left'] : '';
				$pzucd_location['footer-right']	 = (isset($pzucd_layout['ucd_layout-footer-right'])) ? $pzucd_layout['ucd_layout-footer-right'] : '';

				echo '<div class="pzucd_section_' . $key . ' pzucd_section">';
				while ($pzucd_query->have_posts())
				{
					$pzucd_query->the_post();
					self::display_cell($settings, $block, $pzucd_section, $pzucd_query, $pzucd_location);
					echo $this->pzucd_cells;
				}
				echo '</div>'; // End of section
			}
		}
	}

	function display_cell($settings, $block, $pzucd_section, $pzucd_query, $pzucd_locations) {

		$pzucd['title']		 = '<h2 class="pzucd_entry_title">' . get_the_title() . '</h2>';
		$pzucd['excerpt']	 = '<div class="pzucd_entry_excerpt pzucd_entry_body">' . get_the_excerpt() . '</div>';
		$pzucd['content']	 = '<div class="pzucd_entry_content pzucd_entry_body">' . get_the_content() . '</div>';
		$pzucd['image']		 = '<div class="pzucd_entry_image">' . get_the_post_thumbnail(get_the_ID(), array(48, 48)) . '</div>';
		$pzucd['meta1']		 = '<div class="pzucd_entry_meta1 pzucd_entry_meta">' . 'meta1' . '</div>';
		$pzucd['meta2']		 = '<div class="pzucd_entry_meta2 pzucd_entry_meta">' . 'meta2' . '</div>';
		$pzucd['meta3']		 = '<div class="pzucd_entry_meta3 pzucd_entry_meta">' . 'meta3' . '</div>';

		$pzucd_cell = $this->pzucd_cells_template;

		// Work out what goes where
		foreach ($pzucd_locations as $key => $value)
		{
			$pzucd_locations[$key] = str_replace('%title%', $pzucd['title'], $pzucd_locations[$key]);
			$pzucd_locations[$key] = str_replace('%excerpt%', $pzucd['excerpt'], $pzucd_locations[$key]);
			$pzucd_locations[$key] = str_replace('%content%', $pzucd['content'], $pzucd_locations[$key]);
			$pzucd_locations[$key] = str_replace('%image%', $pzucd['image'], $pzucd_locations[$key]);
			$pzucd_locations[$key] = str_replace('%meta1%', $pzucd['meta1'], $pzucd_locations[$key]);
			$pzucd_locations[$key] = str_replace('%meta2%', $pzucd['meta2'], $pzucd_locations[$key]);
			$pzucd_locations[$key] = str_replace('%meta3%', $pzucd['meta3'], $pzucd_locations[$key]);
		}

		$pzucd_cell	 = str_replace('%header-left%', $pzucd_locations['header-left'], $pzucd_cell);
		$pzucd_cell	 = str_replace('%header-right%', $pzucd_locations['header-right'], $pzucd_cell);
		$pzucd_cell	 = str_replace('%column-left%', $pzucd_locations['column-left'], $pzucd_cell);
		$pzucd_cell	 = str_replace('%column-centre%', $pzucd_locations['column-centre'], $pzucd_cell);
		$pzucd_cell	 = str_replace('%column-right%', $pzucd_locations['column-right'], $pzucd_cell);
		$pzucd_cell	 = str_replace('%footer-left%', $pzucd_locations['footer-left'], $pzucd_cell);
		$pzucd_cell	 = str_replace('%footer-right%', $pzucd_locations['footer-right'], $pzucd_cell);

		$this->pzucd_cells = $pzucd_cell;
	}

	private function get_data() {
		
	}

	private function get_content() {
		
	}

	function get_all_layouts() {
		global $wp_query;
		$query_options = array(
			'post_type'	 => 'contentplus-layouts',
			'meta_key'	 => 'contentplus_layout-short-name',
		);

		$layouts_query = new WP_Query($query_options);
		$pzucd_return	 = array();
		$pzucd_common	 = array();
		$pzucd_meta		 = array();
		while ($layouts_query->have_posts())
		{
			$layouts_query->the_post();
			$pzucd_settings				 = get_post_custom();
			$pzucd_common['id']		 = get_the_ID();
			$pzucd_common['title'] = get_the_title();
			foreach ($pzucd_settings as $key => $value)
			{
				if ($key != '_edit_last' && $key != '_edit_lock')
				{
					$pzucd_meta[$key] = maybe_unserialize($value[0]);
				}
			}
			$pzucd_return[$pzucd_meta['ucd_layout-short-name']] = array_merge($pzucd_common, $pzucd_meta);
		};

		return $pzucd_return;
	}

}

// End of class