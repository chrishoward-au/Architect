<?php

/* This class must be included in another file and included later so we don't get an error about HeadwayBlockAPI class not existing. */

class HeadwayUltimateContentDisplayBlock extends HeadwayBlockAPI
{

	public $id = 'ultimatecontentdisplay-block';
	public $name = 'Ultimate Content Display';
	public $options_class = 'HeadwayUltimateContentDisplayBlockOptions';
	public $description = 'Display your content any way you want';
	public $pzucd_cells_template = '
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
	public $pzucd_cells = '';

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

		wp_enqueue_style('pzucd-block-css', PZUCD_PLUGIN_URL . '/frontend/css/ucd-front.css');

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
		$settings  = HeadwayUltimateContentDisplayBlockOptions::get_settings($block);


		if (!isset($settings[ 'pzucd-sections' ]))
		{
			return null;
		}

		//$pzucd_sections = !empty($settings['pzucd-sections'])?$settings['pzucd-sections']:array('pzucd-cell-layout'=>'stock1','pzucd-cells-per-row'=>3,'pzucd-number-to-show'=>6);
		$pzucd_sections = (!isset($pzucd_sections[ 0 ])) ? array($pzucd_sections) : $pzucd_sections;
		//var_dump($pzucd_sections);

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
			$pzucd_col_width = HeadwayBlocksData::get_block_width($block[ 'id' ]) / $pzucd_section[ 'pzucd-cells-per-row-desktop' ];

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

	function setup_elements()
	{
		$this->register_block_element(array(
										   'id'               => 'pzucd-cells',
										   'name'             => 'Cells',
										   'selector'         => '.pzucd_cell',
										   'properties'       => array('background', 'borders', 'padding', 'rounded-corners', 'box-shadow', 'fonts'),
										   'inherit-location' => 'text'
									  ));
		$this->register_block_element(array(
										   'id'               => 'pzucd-entry-title',
										   'name'             => 'Entry title',
										   'selector'         => '.pzucd_entry_title, .pzucd_entry_title a',
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
		//The third argument in the following function is the default that will be returned if the setting is not present in the database
		$settings = HeadwayUltimateContentDisplayBlockOptions::get_settings($block);

		echo pzucd($settings[ 'pzucd-template' ]);

		return;

  }
}

// End of class