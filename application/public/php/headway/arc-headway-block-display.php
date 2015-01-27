<?php

  /* This class must be included in another file and included later so we don't get an error about HeadwayBlockAPI class not existing. */

  class HeadwayArchitectBlock extends HeadwayBlockAPI
  {

    public $id = 'architect-block';
    public $name = 'Architect';
    public $options_class = 'HeadwayArchitectBlockOptions';
    public $description = 'Layout your content any way you want';

    /**
     * Use this to enqueue styles or scripts for your block.  This method will be execute when the block type is on
     * the current page you are viewing.  Also, not only is it page-specific, the method will execute for every instance
     * of that block type on the current page.
     *
     * This method will be executed at the WordPress 'wp' hook
     * */
    static function enqueue_action($block_id, $block, $original_block = null)
    {

      if (method_exists('HeadwayBlocksData', 'get_legacy_id')) {
        $block[ 'id' ] = HeadwayBlocksData::get_legacy_id($block);
      }

      if (isset($block[ 'settings' ][ 'pzarc-blueprint' ])) {
        $blueprint = explode('##', $block[ 'settings' ][ 'pzarc-blueprint' ]);
      }

//		wp_enqueue_style('pzarc-plugin-styles');

      wp_enqueue_script('jquery');
      // if (!empty())
      //    $filename = PZARC_CACHE_URL . '/pzarc-blueprints-layout-' . ($blueprint[1]) . '.css';
      //    wp_enqueue_style('blueprint-css-' . $blueprint[ 1 ], $filename);
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
    static function js_content($block_id, $block, $original_block = null)
    {
      if (method_exists('HeadwayBlocksData', 'get_legacy_id')) {
        $block[ 'id' ] = HeadwayBlocksData::get_legacy_id($block);
      }

      $return_js = '';
      $settings  = HeadwayArchitectBlockOptions::get_settings($block);


      if (!isset($settings[ 'pzarc-sections' ])) {
        return null;
      }

      //$pzarc_sections = !empty($settings['pzarc-sections'])?$settings['pzarc-sections']:array('pzarc-cell-layout'=>'stock1','pzarc-cells-per-row'=>3,'pzarc-number-to-show'=>6);
      $pzarc_sections = (!isset($pzarc_sections[ 0 ])) ? array($pzarc_sections) : $pzarc_sections;
      //var_dump($pzarc_sections);

      $return_js = "jQuery(document).ready(function(){ ";

      foreach ($pzarc_sections as $key => $pzarc_section) {
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

      // BLUEPRINT
      $this->register_block_element(array(
                                        'id'         => 'pzarc-blueprint',
                                        'name'       => 'Blueprint container',
                                        'selector'   => '.use-hw-css.pzarc-blueprint',
                                        'states'     => array(
                                            'Links'   => '.use-hw-css.pzarc-blueprint a',
                                            'Hover'   => '.use-hw-css.pzarc-blueprint a:hover',
                                            'Clicked' => '.use-hw-css.pzarc-blueprint a:active',
                                            'Visited' => '.use-hw-css.pzarc-blueprint a:visited'
                                        ),
                                        'properties' => array('background',
                                                              'borders',
                                                              'padding',
                                                              'margins',
                                                              'rounded-corners',
                                                              'box-shadow',
                                                              'fonts'),
                                    ));
      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-page-title',
                                        'name'       => 'Page title',
                                        'selector'   => '.use-hw-css .pzarc-page-title',
                                        'properties' => array('background',
                                                              'borders',
                                                              'padding',
                                                              'margins',
                                                              'rounded-corners',
                                                              'box-shadow',
                                                              'fonts'),
                                    ));
      // SECTIONS
      $this->register_block_element(array(
                                        'id'         => 'pzarc-sections',
                                        'name'       => 'Sections',
                                        'selector'   => '.use-hw-css .pzarc-sections',
                                        'properties' => array('background',
                                                              'borders',
                                                              'padding',
                                                              'margins',
                                                              'rounded-corners',
                                                              'box-shadow',
                                                              'fonts'),
                                    ));
      $this->register_block_element(array(
                                        'id'         => 'pzarc-section1',
                                        'name'       => 'Section 1',
                                        'selector'   => '.use-hw-css .pzarc-section_1',
                                        'properties' => array('background',
                                                              'borders',
                                                              'padding',
                                                              'margins',
                                                              'rounded-corners',
                                                              'box-shadow',
                                                              'fonts'),
                                    ));
      $this->register_block_element(array(
                                        'id'         => 'pzarc-section2',
                                        'name'       => 'Section 2',
                                        'selector'   => '.use-hw-css .pzarc-section_2',
                                        'properties' => array('background',
                                                              'borders',
                                                              'padding',
                                                              'margins',
                                                              'rounded-corners',
                                                              'box-shadow',
                                                              'fonts'),
                                    ));
      $this->register_block_element(array(
                                        'id'         => 'pzarc-section3',
                                        'name'       => 'Section 3',
                                        'selector'   => '.use-hw-css .pzarc-section_3',
                                        'properties' => array('background',
                                                              'borders',
                                                              'padding',
                                                              'margins',
                                                              'rounded-corners',
                                                              'box-shadow',
                                                              'fonts'),
                                    ));
      // PANELS
      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel',
                                        'name'       => 'Panels',
                                        'selector'   => '.use-hw-css .pzarc-panel',
                                        //                                        'states'     => array(
                                        //                                            'Odd within Blueprint'  => '.use-hw-css .pzarc-panel.odd-blueprint-panel',
                                        //                                            'Even within Blueprint' => '.use-hw-css .pzarc-panel.even-blueprint-panel',
                                        //                                            'Odd within Section'    => '.use-hw-css .pzarc-panel.odd-section-panel',
                                        //                                            'Even within Section'   => '.use-hw-css .pzarc-panel.even-section-panel',
                                        //                                        ),
                                        // Don't allow margins on panels as it messes up layout. Margins must be set in the Blueprint settings
                                        'properties' => array('background',
                                                              'borders',
                                                              'padding',
                                                              'rounded-corners',
                                                              'box-shadow',
                                                              'fonts'),
                                    ));
      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-entry-title',
                                        'name'       => 'Entry title',
                                        'selector'   => '.use-hw-css .pzarc-panel .entry-title, .pzarc-panel .entry-title:before',
                                        'states'     => array(
                                            'Links'   => '.use-hw-css .pzarc-panel .entry-title a, .use-hw-css .pzarc-panel .entry-title:before a',
                                            'Hover'   => '.use-hw-css .pzarc-panel .entry-title a:hover, .use-hw-css .pzarc-panel .entry-title:before a:hover',
                                            'Clicked' => '.use-hw-css .pzarc-panel .entry-title a:active, .use-hw-css .pzarc-panel .entry-title:before a:active',
                                            'Visited' => '.use-hw-css .pzarc-panel .entry-title a:visited, .use-hw-css .pzarc-panel .entry-title:before a:visited'
                                        ),
                                        'properties' => array('background',
                                                              'borders',
                                                              'padding',
                                                              'margins',
                                                              'rounded-corners',
                                                              'box-shadow',
                                                              'fonts'),
                                    ));


      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-entry-content',
                                        'name'       => 'Entry content',
                                        'selector'   => '.use-hw-css .pzarc-panel .entry-content, .pzarc-panel .entry-excerpt',
                                        'states'     => array(
                                            'Links'   => '.use-hw-css .pzarc-panel .entry-content a, .use-hw-css .pzarc-panel .entry-excerpt a',
                                            'Hover'   => '.use-hw-css .pzarc-panel .entry-content a:hover, .use-hw-css .pzarc-panel .entry-excerpt a:hover',
                                            'Clicked' => '.use-hw-css .pzarc-panel .entry-content a:active, .use-hw-css .pzarc-panel .entry-excerpt a:active',
                                            'Visited' => '.use-hw-css .pzarc-panel .entry-content a:visited, .use-hw-css .pzarc-panel .entry-excerpt a:visited'
                                        ),
                                        'properties' => array('background',
                                                              'borders',
                                                              'padding',
                                                              'margins',
                                                              'rounded-corners',
                                                              'box-shadow',
                                                              'fonts'),
                                    ));

      // IMAGES
      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-figure',
                                        'name'       => 'Featured Image',
                                        'selector'   => '.use-hw-css .pzarc-panel figure.entry-thumbnail',
                                        'properties' => array('background',
                                                              'borders',
                                                              'padding',
                                                              'margins',
                                                              'rounded-corners',
                                                              'box-shadow'),
                                    ));
      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-figure-caption',
                                        'name'       => 'Featured image caption',
                                        'selector'   => '.use-hw-css .pzarc-panel figure.entry-thumbnail caption',
                                        'properties' => array('background',
                                                              'borders',
                                                              'padding',
                                                              'margins',
                                                              'rounded-corners',
                                                              'box-shadow',
                                                              'fonts'),
                                    ));
      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-backgroundimage',
                                        'name'       => 'Background image',
                                        'selector'   => '.use-hw-css .pzarc-panel figure.entry-thumbnail',
                                        'properties' => array('borders',
                                                              'padding',
                                                              'margins',
                                                              'rounded-corners',
                                                              'box-shadow'),
                                    ));
      //META
      for ($i = 1; $i <= 3; $i++) {
        $this->register_block_element(array(
                                          'id'         => 'pzarc-panel-meta' . $i,
                                          'name'       => 'Meta group' . $i,
                                          'selector'   => '.use-hw-css .pzarc-panel .entry-meta' . $i,
                                          'states'     => array(
                                              'Links'   => '.use-hw-css .pzarc-panel .entry-meta' . $i . ' a',
                                              'Hover'   => '.use-hw-css .pzarc-panel .entry-meta' . $i . ' a:hover',
                                              'Clicked' => '.use-hw-css .pzarc-panel .entry-meta' . $i . ' a:active',
                                              'Visited' => '.use-hw-css .pzarc-panel .entry-meta' . $i . ' a:visited'
                                          ),
                                          'properties' => array('background',
                                                                'borders',
                                                                'padding',
                                                                'margins',
                                                                'rounded-corners',
                                                                'box-shadow',
                                                                'fonts'),
                                      ));

      }

      // CUSTOM FIELDS
      for ($i = 1; $i <= 3; $i++) {
        $this->register_block_element(array(
                                          'id'         => 'pzarc-panel-custom' . $i,
                                          'name'       => 'Custom Field Group' . $i,
                                          'selector'   => '.use-hw-css .pzarc-panel .entry-customfieldgroup-' . $i,
                                          'states'     => array(
                                              'Links'   => '.use-hw-css .pzarc-panel .entry-customfieldgroup-' . $i . ' a',
                                              'Hover'   => '.use-hw-css .pzarc-panel .entry-customfieldgroup-' . $i . ' a:hover',
                                              'Clicked' => '.use-hw-css .pzarc-panel .entry-customfieldgroup-' . $i . ' a:active',
                                              'Visited' => '.use-hw-css .pzarc-panel .entry-customfieldgroup-' . $i . ' a:visited'
                                          ),
                                          'properties' => array('background',
                                                                'borders',
                                                                'padding',
                                                                'margins',
                                                                'rounded-corners',
                                                                'box-shadow',
                                                                'fonts'),
                                      ));

        $this->register_block_element(array(
                                          'id'         => 'pzarc-panel-meta-presuff-image' . $i,
                                          'name'       => 'CFG Prefix/Suffix image ' . $i,
                                          'selector'   => '.use-hw-css .pzarc-panel .entry-customfieldgroup-' . $i . ' .pzarc-presuff-image',
                                          'states'     => array(
                                              'Prefix' => '.use-hw-css .pzarc-panel .entry-customfieldgroup-' . $i . ' .pzarc-presuff-image.prefix-image',
                                              'Suffix' => '.use-hw-css .pzarc-panel .entry-customfieldgroup-' . $i . ' .pzarc-presuff-image.suffix-image',
                                          ),
                                          'properties' => array('background',
                                                                'borders',
                                                                'padding',
                                                                'margins',
                                                                'rounded-corners',
                                                                'box-shadow',
                                                                'fonts'),
                                      ));
      }
    }

    /**
     * Anything in here will be displayed when the block is being displayed.
     * */
    function content($block)
    {
      global $wp_query;
      $settings  = HeadwayArchitectBlockOptions::get_settings($block);
      $blueprint = explode('##', $settings[ 'pzarc-blueprint' ]);

      echo pzarc($blueprint[ 0 ], $settings[ 'pzarc-overrides-ids' ], 'headway-block', null, $settings);

      return;

    }
  }

  // End of class