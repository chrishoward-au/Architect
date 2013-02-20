<?php
/* This class must be included in another file and included later so we don't get an error about HeadwayBlockAPI class not existing. */

class HeadwayContentPlusBlock extends HeadwayBlockAPI {
	
	
	public $id = 'contentplus-block';
	
	public $name = 'ContentPlus';
	
	public $options_class = 'HeadwayContentPlusBlockOptions';

	public $description = 'Display your content any way you want';

	public $cplus_cells_template = '
				<div class="cplus_cell" style="width:%cells-per-row%;">
				    <div class="cplus_cell_header">
				        <div class="cplus_cell_header_left">%header-left%</div>
				        <div class="cplus_cell_header_right">%header-right%</div>
				    </div>
				    <div class="cplus_clearfloat"></div>
				    <div class="cplus_cell_columns">
				        <div class= "cplus_cell_left_col">%column-left%</div>
				        <div class= "cplus_cell_centre_col">%column-centre%</div>
				        <div class="cplus_cell_right_col">%column-right%</div>
				    </div>
				    <div class="cplus_clearfloat"></div>
				    <div class="cplus_cell_footer">
				        <div class="cplus_cell_footer_left">%footer-left%</div>
				        <div class="cplus_cell_footer_right">%footer-right%</div>
				    </div>
				    <div class="cplus_clearfloat"></div>
				</div>
		';

	public $cplus_cells = '';	

	/**
	 * Use this to enqueue styles or scripts for your block.  This method will be execute when the block type is on 
	 * the current page you are viewing.  Also, not only is it page-specific, the method will execute for every instance
	 * of that block type on the current page.
	 * 
	 * This method will be executed at the WordPress 'wp' hook
	 **/ 
	function enqueue_action($block_id, $layout) {
								
		$block = HeadwayBlocksData::get_block($block_id);

		//If it's a mirrored block, enqueue the scripts for that block instead
		if ( headway_get('mirror-block', $block['settings'], '') !== '' ) {
			$block = HeadwayBlocksData::get_block($block['settings']['mirror-block']);
		}

		wp_enqueue_style('contentplus-block-css', CPLUS_PLUGIN_URL.'/css/contentplus-front.css');

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-isotope',CPLUS_PLUGIN_URL.'/js/jquery.isotope.min.js');
//		wp_enqueue_script('jquery-masonry');
		
		return;
				
	}
	
	
	/**
	 * Use this method to register sidebars, menus, or anything to that nature.  This method executes for every single block that
	 * has this method defined.
	 * 
	 * The method will execute for every single block on every single layout.
	 **/
	function init_action($block_id, $layout) {
		
		return;
		
	}
	
	
	/**
	 * Use this to insert dynamic JS into the page needed.  This is perfect for initializing instances of jQuery Cycle, jQuery Tabs, etc.
	 **/
	function js_content($block_id, $layout) {
		$block = HeadwayBlocksData::get_block( $block_id );
		if ( headway_get( 'mirror-block', $block['settings'], '' ) !== '' ) {
			$block = HeadwayBlocksData::get_block( $block['settings']['mirror-block'] );
		}

		$return = '';
		$settings = HeadwayContentPlusBlockOptions::get_settings( $block );


		$return_js = "jQuery(document).ready(function(){ ";



		$cplus_sections = !empty($settings['cplus-sections'])?$settings['cplus-sections']:array('cplus-cell-layout'=>'stock1','cplus-cells-per-row'=>3,'cplus-number-to-show'=>6);
		$cplus_sections = (!isset($cplus_sections[0]))?array($cplus_sections):$cplus_sections;

		foreach ($cplus_sections as $key => $cplus_section) {
			// $return_js .= "
			// 	var section = jQuery('#block-".$block_id." .cplus_section_".$key."');
			// 	var cell = jQuery('#block-".$block_id." .cplus_section_".$key." .cplus_cell');
			// 	var gutterWidth = 10;

			// 	section.masonry({
			// 		itemSelector: '.cplus_cell',
			// 			gutter : gutterWidth,
			// 			columnWidth: function( containerWidth ) {
			// 					if (jQuery(window).width() < ".$settings['cplus-width-tablet-lower']." ) {
			// 						var columns = ".$cplus_section['cplus-cells-per-row-phone'].";
			// 					} else if (jQuery(window).width() < ".$settings['cplus-width-tablet-upper']." ) {
			// 						var columns = ".$cplus_section['cplus-cells-per-row-tablet'].";
			// 					} else {
			// 						var columns = ".$cplus_section['cplus-cells-per-row-desktop'].";
			// 					}
			// 					var newWidth = Math.floor((containerWidth - (gutterWidth*(columns-1))) / columns); 
			// 			    return newWidth;
			// 		  }
	  //  			});

			// 	";

		$cplus_col_width = HeadwayBlocksData::get_block_width($block['id'])/$cplus_section['cplus-cells-per-row-desktop'];

				// if (jQuery(window).width() < ".$settings['cplus-width-tablet-lower']." ) {
				// 	var columns = ".$cplus_section['cplus-cells-per-row-phone'].";
				// } else if (jQuery(window).width() < ".$settings['cplus-width-tablet-upper']." ) {
				// 	var columns = ".$cplus_section['cplus-cells-per-row-tablet'].";
				// } else {
				// 	var columns = ".$cplus_section['cplus-cells-per-row-desktop'].";
				// }
				// jQuery(window).smartresize(function() {
				// 		if (jQuery(window).width() < ".$settings['cplus-width-tablet-lower']." ) {
				// 			var columns = ".$cplus_section['cplus-cells-per-row-phone'].";
				// 		} else if (jQuery(window).width() < ".$settings['cplus-width-tablet-upper']." ) {
				// 			var columns = ".$cplus_section['cplus-cells-per-row-tablet'].";
				// 		} else {
				// 			var columns = ".$cplus_section['cplus-cells-per-row-desktop'].";
				// 		}
				// 		var newWidth = section.width()/columns;
				// 		cell.css({width: newWidth});
				// 		console.log(newWidth);
				// 		console.log(cell.width(),section.width());
				// 	});

			$return_js .= "
				var section = jQuery('#block-".$block_id." .cplus_section_".$key."');
				var cell = jQuery('#block-".$block_id." .cplus_section_".$key." .cplus_cell');
				section.isotope({
				  // options
				  itemSelector : '.cplus_cell',
				  layoutMode : 'masonry',
				  masonry : {
//				  	columnWidth : section.width()/columns,
				  	columnWidth : ".$cplus_col_width.",
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
			'id' => 'cplus-cells',
			'name' => 'Cells',
			'selector' => '.cplus_cell',
			'properties' => array('background', 'borders', 'padding', 'rounded-corners', 'box-shadow','fonts'),
			'inherit-location' => 'text'

		));
		$this->register_block_element(array(
			'id' => 'cplus-entry-title',
			'name' => 'Entry title',
			'selector' => '.cplus_entry_title, .cplus_entry_title a',
			'properties' => array('background', 'borders', 'padding', 'rounded-corners', 'box-shadow','fonts'),
			'inherit-location' => 'text'

		));

	}	

	/** 
	 * Anything in here will be displayed when the block is being displayed.
	 **/
	function content($block) {
		global $wp_query;		
		//The third argument in the following function is the default that will be returned if the setting is not present in the database
		$settings = HeadwayContentPlusBlockOptions::get_settings($block); 
		pzdebug($settings);
		// Either going to have to pre-grab content, or setup a loop here. :/

		if (!isset($settings['cplus-content-type'])) {
			$cplus_content_types = 'any';
		} elseif (is_array($settings['cplus-content-type'])) {
			$cplus_content_types = implode(',',$settings['cplus-content-type']);
		} else {
			$cplus_content_types = $settings['cplus-content-type'];
		}

		$cplus_query_str = 'posts_per_page=-1&post_type='.$cplus_content_types;

		$cplus_query = new WP_Query($cplus_query_str);
		$cplus_cell_margin = 2;
		$cplus_sections = !empty($settings['cplus-sections'])?$settings['cplus-sections']:array('cplus-cell-layout'=>'stock1','cplus-cells-per-row'=>3,'cplus-number-to-show'=>6);
		$cplus_sections = (!isset($cplus_sections[0]))?array($cplus_sections):$cplus_sections;

		$cplus_layouts = self::get_all_layouts();


		foreach ($cplus_sections as $key => $cplus_section) {

			$cplus_column_count = 1;

			$this->cplus_cells_template = str_replace('%cells-per-row%',((HeadwayBlocksData::get_block_width($block['id'])-10)/$cplus_section['cplus-cells-per-row-desktop']).'px',$this->cplus_cells_template);
//			$this->cplus_cells_template = str_replace('%cells-per-row%',(100/$cplus_section['cplus-cells-per-row-desktop']-$cplus_cell_margin).'%',$this->cplus_cells_template);

			if ($cplus_query->have_posts()) {
				$cplus_location = array();
				$cplus_layout = $cplus_layouts[$cplus_section['cplus-cell-layout']];
				$cplus_location['header-left']   = (isset($cplus_layout['contentplus_layout-header-left']))   ? $cplus_layout['contentplus_layout-header-left']   : '';
				$cplus_location['header-right']  = (isset($cplus_layout['contentplus_layout-header-right']))  ? $cplus_layout['contentplus_layout-header-right']  : '';
				$cplus_location['column-left']   = (isset($cplus_layout['contentplus_layout-column-left']))   ? $cplus_layout['contentplus_layout-column-left']   : '';
				$cplus_location['column-centre'] = (isset($cplus_layout['contentplus_layout-column-centre'])) ? $cplus_layout['contentplus_layout-column-centre'] : '';
				$cplus_location['column-right']  = (isset($cplus_layout['contentplus_layout-column-right']))  ? $cplus_layout['contentplus_layout-column-right']  : '';
				$cplus_location['footer-left']   = (isset($cplus_layout['contentplus_layout-footer-left']))   ? $cplus_layout['contentplus_layout-footer-left']   : '';
				$cplus_location['footer-right']  = (isset($cplus_layout['contentplus_layout-footer-right']))  ? $cplus_layout['contentplus_layout-footer-right']  : '';

				echo '<div class="cplus_section_'.$key.' cplus_section">';
					while ($cplus_query->have_posts()) {
						$cplus_query->the_post();
						self::display_cell($settings,$block,$cplus_section,$cplus_query,$cplus_location);
						echo $this->cplus_cells;
					}
				echo '</div>'; // End of section

			}
		}
	}
	
	function display_cell($settings,$block,$cplus_section,$cplus_query,$cplus_locations) {

			$cplus['title'] = '<h2 class="cplus_entry_title">'.get_the_title().'</h2>';
			$cplus['excerpt'] = '<div class="cplus_entry_excerpt cplus_entry_body">'.get_the_excerpt().'</div>';
			$cplus['content'] = '<div class="cplus_entry_content cplus_entry_body">'.get_the_content().'</div>';
			$cplus['image'] = '<div class="cplus_entry_image">'.get_the_post_thumbnail(get_the_ID(),array(48,48)).'</div>';
			$cplus['meta1'] = '<div class="cplus_entry_meta1 cplus_entry_meta">'.'meta1'.'</div>';
			$cplus['meta2'] = '<div class="cplus_entry_meta2 cplus_entry_meta">'.'meta2'.'</div>';
			$cplus['meta3'] = '<div class="cplus_entry_meta3 cplus_entry_meta">'.'meta3'.'</div>';

			$cplus_cell = $this->cplus_cells_template;

			// Work out what goes where
			foreach ($cplus_locations as $key => $value) {
				$cplus_locations[$key] = str_replace('%title%'   , $cplus['title']   , $cplus_locations[$key]);
				$cplus_locations[$key] = str_replace('%excerpt%' , $cplus['excerpt'] , $cplus_locations[$key]);
				$cplus_locations[$key] = str_replace('%content%' , $cplus['content'] , $cplus_locations[$key]);
				$cplus_locations[$key] = str_replace('%image%'   , $cplus['image']   , $cplus_locations[$key]);
				$cplus_locations[$key] = str_replace('%meta1%'   , $cplus['meta1']   , $cplus_locations[$key]);
				$cplus_locations[$key] = str_replace('%meta2%'   , $cplus['meta2']   , $cplus_locations[$key]);
				$cplus_locations[$key] = str_replace('%meta3%'   , $cplus['meta3']   , $cplus_locations[$key]);
			}

			$cplus_cell = str_replace('%header-left%'   , $cplus_locations['header-left']   , $cplus_cell);
			$cplus_cell = str_replace('%header-right%'  , $cplus_locations['header-right']  , $cplus_cell);
			$cplus_cell = str_replace('%column-left%'   , $cplus_locations['column-left']   , $cplus_cell);
			$cplus_cell = str_replace('%column-centre%' , $cplus_locations['column-centre'] , $cplus_cell);
			$cplus_cell = str_replace('%column-right%'  , $cplus_locations['column-right']  , $cplus_cell);
			$cplus_cell = str_replace('%footer-left%'   , $cplus_locations['footer-left']   , $cplus_cell);
			$cplus_cell = str_replace('%footer-right%'  , $cplus_locations['footer-right']  , $cplus_cell);

			$this->cplus_cells = $cplus_cell;

	}

	private function get_data() {


	}

	private function get_content() {

	}

	function get_all_layouts() {
		global $wp_query;
			$query_options = array(
				'post_type' => 'contentplus-layouts',
				'meta_key' => 'contentplus_layout-short-name',
			);

			$layouts_query = new WP_Query($query_options);
			$cplus_return = array();
			$cplus_common = array();
			$cplus_meta = array();
			while ($layouts_query->have_posts()) {
				$layouts_query->the_post();
					$cplus_settings = get_post_custom();
					$cplus_common['id'] = get_the_ID();
					$cplus_common['title'] = get_the_title();
					foreach ($cplus_settings as $key => $value) {
						if ($key != '_edit_last' && $key != '_edit_lock') {
							$cplus_meta[$key] = maybe_unserialize($value[0]);
						}
					}
					$cplus_return[$cplus_meta['contentplus_layout-short-name']] = array_merge($cplus_common,$cplus_meta);
			};

	return $cplus_return;

	}
} // End of class