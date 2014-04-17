<?php

/* This class must be included in another file and included later so we don't get an error about HeadwayBlockAPI class not existing. */

class HeadwayArchitectBlock extends HeadwayBlockAPI
{

	public $id = 'architect-block';
	public $name = 'Architect';
	public $options_class = 'HeadwayArchitectBlockOptions';
	public $description = 'Display your content any way you want';

	/**
	 * Use this to enqueue styles or scripts for your block.  This method will be execute when the block type is on
	 * the current page you are viewing.  Also, not only is it page-specific, the method will execute for every instance
	 * of that block type on the current page.
	 *
	 * This method will be executed at the WordPress 'wp' hook
	 * */
	static function enqueue_action($block_id, $layout)
	{

		$block = HeadwayBlocksData::get_block($block_id);

		//If it's a mirrored block, enqueue the scripts for that block instead
		if (headway_get('mirror-block', $block[ 'settings' ], '') !== '')
		{
			$block = HeadwayBlocksData::get_block($block[ 'settings' ][ 'mirror-block' ]);
		}

//		wp_enqueue_style('pzarc-plugin-styles');

		wp_enqueue_script('jquery');
		wp_enqueue_script('js-isotope-v2');

//		wp_enqueue_script('jquery-masonry');

		return;
	}

	/**
	 * Use this method to register sidebars, menus, or anything to that nature.  This method executes for every single block that
	 * has this method defined.
	 *
	 * The method will execute for every single block on every single layout.
	 * */
	static function init_action($block_id, $layout)
	{

		return;
	}

	/**
	 * Use this to insert dynamic JS into the page needed.  This is perfect for initializing instances of jQuery Cycle, jQuery Tabs, etc.
	 * */
	static function js_content($block_id, $layout)
	{
		$block = HeadwayBlocksData::get_block($block_id);
		if (headway_get('mirror-block', $block[ 'settings' ], '') !== '')
		{
			$block = HeadwayBlocksData::get_block($block[ 'settings' ][ 'mirror-block' ]);
		}

		$return_js = '';
		$settings  = HeadwayArchitectBlockOptions::get_settings($block);


		if (!isset($settings[ 'pzarc-sections' ]))
		{
			return null;
		}

		//$pzarc_sections = !empty($settings['pzarc-sections'])?$settings['pzarc-sections']:array('pzarc-cell-layout'=>'stock1','pzarc-cells-per-row'=>3,'pzarc-number-to-show'=>6);
		$pzarc_sections = (!isset($pzarc_sections[ 0 ])) ? array($pzarc_sections) : $pzarc_sections;
		//var_dump($pzarc_sections);

		$return_js = "jQuery(document).ready(function(){ ";

		foreach ($pzarc_sections as $key => $pzarc_section)
		{
			// $return_js .= "
			// 	var section = jQuery('#block-".$block_id." .pzarc_section_".$key."');
			// 	var cell = jQuery('#block-".$block_id." .pzarc_section_".$key." .pzarc_cell');
			// 	var gutterWidth = 10;
			// 	section.masonry({
			// 		itemSelector: '.pzarc_cell',
			// 			gutter : gutterWidth,
			// 			columnWidth: function( containerWidth ) {
			// 					if (jQuery(window).width() < ".$settings['pzarc-width-tablet-lower']." ) {
			// 						var columns = ".$pzarc_section['pzarc-cells-per-row-phone'].";
			// 					} else if (jQuery(window).width() < ".$settings['pzarc-width-tablet-upper']." ) {
			// 						var columns = ".$pzarc_section['pzarc-cells-per-row-tablet'].";
			// 					} else {
			// 						var columns = ".$pzarc_section['pzarc-cells-per-row-desktop'].";
			// 					}
			// 					var newWidth = Math.floor((containerWidth - (gutterWidth*(columns-1))) / columns); 
			// 			    return newWidth;
			// 		  }
			//  			});
			// 	";
			$pzarc_col_width = HeadwayBlocksData::get_block_width($block[ 'id' ]) / $pzarc_section[ 'pzarc-cells-per-row-desktop' ];

			// if (jQuery(window).width() < ".$settings['pzarc-width-tablet-lower']." ) {
			// 	var columns = ".$pzarc_section['pzarc-cells-per-row-phone'].";
			// } else if (jQuery(window).width() < ".$settings['pzarc-width-tablet-upper']." ) {
			// 	var columns = ".$pzarc_section['pzarc-cells-per-row-tablet'].";
			// } else {
			// 	var columns = ".$pzarc_section['pzarc-cells-per-row-desktop'].";
			// }
			// jQuery(window).smartresize(function() {
			// 		if (jQuery(window).width() < ".$settings['pzarc-width-tablet-lower']." ) {
			// 			var columns = ".$pzarc_section['pzarc-cells-per-row-phone'].";
			// 		} else if (jQuery(window).width() < ".$settings['pzarc-width-tablet-upper']." ) {
			// 			var columns = ".$pzarc_section['pzarc-cells-per-row-tablet'].";
			// 		} else {
			// 			var columns = ".$pzarc_section['pzarc-cells-per-row-desktop'].";
			// 		}
			// 		var newWidth = section.width()/columns;
			// 		cell.css({width: newWidth});
			// 		console.log(newWidth);
			// 		console.log(cell.width(),section.width());
			// 	});

			$return_js .= "
				var section = jQuery('#block-" . $block_id . " .pzarc_section_" . $key . "');
				var cell = jQuery('#block-" . $block_id . " .pzarc_section_" . $key . " .pzarc_cell');
				section.isotope({
				  // options
				  itemSelector : '.pzarc_cell',
				  layoutMode : 'masonry',
				  masonry : {
//				  	columnWidth : section.width()/columns,
				  	columnWidth : " . $pzarc_col_width . ",
					  gutterWidth : 10
				  }
				});
			";
		}

		$return_js .= "});";

		return $return_js;
	}

	function setup_elements()
	{
		$this->register_block_element(array(
										   'id'               => 'pzarc-cells',
										   'name'             => 'Cells',
										   'selector'         => '.pzarc_cell',
										   'properties'       => array('background', 'borders', 'padding', 'rounded-corners', 'box-shadow', 'fonts'),
										   'inherit-location' => 'text'
									  ));
		$this->register_block_element(array(
										   'id'               => 'pzarc-entry-title',
										   'name'             => 'Entry title',
										   'selector'         => '.pzarc_entry_title, .pzarc_entry_title a',
										   'properties'       => array('background', 'borders', 'padding', 'rounded-corners', 'box-shadow', 'fonts'),
										   'inherit-location' => 'text'
									  ));
	}

	/**
	 * Anything in here will be displayed when the block is being displayed.
	 * */
	function content($block)
	{
		global $wp_query;
		$settings = HeadwayArchitectBlockOptions::get_settings($block);

		echo pzarc($settings[ 'pzarc-blueprint' ]);

		return;

  }
}

// End of class