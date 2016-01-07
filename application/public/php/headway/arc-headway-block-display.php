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
      return null;
      // TODO: Don't need this
      if (method_exists('HeadwayBlocksData', 'get_legacy_id')) {
        $block[ 'id' ] = HeadwayBlocksData::get_legacy_id($block);
      }

      $return_js = '';
      $settings  = HeadwayArchitectBlockOptions::get_settings($block);


      if (!isset($settings[ 'pzarc-sections' ])) {
        return null;
      }

      $pzarc_sections = (!isset($pzarc_sections[ 0 ])) ? array($pzarc_sections) : $pzarc_sections;

      $return_js = "jQuery(document).ready(function(){ ";

      foreach ($pzarc_sections as $key => $pzarc_section) {
        // var_dump($key,$pzarc_section);
        $pzarc_col_width = HeadwayBlocksData::get_block_width($block[ 'id' ]) / $pzarc_section[ 'pzarc-cells-per-row-desktop' ];


        $return_js .= "
				var section = jQuery('#block-" . $block_id . " .pzarc-section_" . $key . "');
				var cell = jQuery('#block-" . $block_id . " .pzarc-section_" . $key . " .pzarc-panel');
				section.isotope({
				  // options
				  itemSelector : '.pzarc-panel',
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
//better add the hw stuff!
      // BLUEPRINT
      $this->register_block_element(array(
                                        'id'         => 'pzarc-blueprint',
                                        'name'       => 'Blueprint container',
                                        'selector'   => '.use-hw-css.pzarc-blueprint',
                                        //                                        'states'     => array(
                                        //                                            'Links'   => '.use-hw-css.pzarc-blueprint a',
                                        //                                            'Hover'   => '.use-hw-css.pzarc-blueprint a:hover',
                                        //                                            'Clicked' => '.use-hw-css.pzarc-blueprint a:active',
                                        //                                            'Visited' => '.use-hw-css.pzarc-blueprint a:visited'
                                        //                                        ),
                                        'properties' => array(
                                            'background',
                                            'borders',
                                            'padding',
                                            'margins',
                                            'rounded-corners',
                                            'box-shadow',
                                            'fonts'
                                        ),
                                    ));
      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-page-title',
                                        'name'       => 'Page title',
                                        'selector'   => '.use-hw-css .pzarc-page-title',
                                        'properties' => array(
                                            'background',
                                            'borders',
                                            'padding',
                                            'margins',
                                            'rounded-corners',
                                            'box-shadow',
                                            'fonts'
                                        ),
                                    ));
      $this->register_block_element(array(
                                        'id'         => 'pzarc-blueprint-title',
                                        'name'       => 'Blueprint title',
                                        'selector'   => '.use-hw-css h2.pzarc-blueprint-title',
                                        'properties' => array(
                                            'background',
                                            'borders',
                                            'padding',
                                            'margins',
                                            'rounded-corners',
                                            'box-shadow',
                                            'fonts'
                                        ),
                                    ));
      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-page-navi',
                                        'name'       => 'Page navigation',
                                        'selector'   => '.use-hw-css .nav-previous a, .use-hw-css .nav-next a',
                                        'properties' => array(
                                            'background',
                                            'borders',
                                            'padding',
                                            'margins',
                                            'rounded-corners',
                                            'box-shadow',
                                            'fonts'
                                        ),
                                    ));
      // SECTIONS
      $this->register_block_element(array(
                                        'id'         => 'pzarc-sections',
                                        'name'       => 'Panels wrapper',
                                        'selector'   => '.use-hw-css .pzarc-sections',
                                        'properties' => array(
                                            'background',
                                            'borders',
                                            'padding',
                                            'margins',
                                            'rounded-corners',
                                            'box-shadow',
                                            'fonts'
                                        ),
                                    ));
//      $this->register_block_element(array(
//                                        'id'         => 'pzarc-section1',
//                                        'name'       => 'Section 1',
//                                        'selector'   => '.use-hw-css .pzarc-section_1',
//                                        'properties' => array('background',
//                                                              'borders',
//                                                              'padding',
//                                                              'margins',
//                                                              'rounded-corners',
//                                                              'box-shadow',
//                                                              'fonts'),
//                                    ));
//      $this->register_block_element(array(
//                                        'id'         => 'pzarc-section2',
//                                        'name'       => 'Section 2',
//                                        'selector'   => '.use-hw-css .pzarc-section_2',
//                                        'properties' => array('background',
//                                                              'borders',
//                                                              'padding',
//                                                              'margins',
//                                                              'rounded-corners',
//                                                              'box-shadow',
//                                                              'fonts'),
//                                    ));
//      $this->register_block_element(array(
//                                        'id'         => 'pzarc-section3',
//                                        'name'       => 'Section 3',
//                                        'selector'   => '.use-hw-css .pzarc-section_3',
//                                        'properties' => array('background',
//                                                              'borders',
//                                                              'padding',
//                                                              'margins',
//                                                              'rounded-corners',
//                                                              'box-shadow',
//                                                              'fonts'),
//                                    ));
      // PANELS
      // Don't allow margins on panels as it messes up layout. Margins must be set in the Blueprint settings


      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel',
                                        'name'       => 'Panels',
                                        'selector'   => '.use-hw-css .pzarc-panel',
                                        'states'     => array(
                                            'Odd'  => '.use-hw-css .pzarc-panel.odd-panel',
                                            'Even' => '.use-hw-css .pzarc-panel.even-panel',
                                        ),
                                        'properties' => array(
                                            'background',
                                            'borders',
                                            'padding',
                                            'rounded-corners',
                                            'box-shadow',
                                            'fonts'
                                        ),
                                    ));
      // Don't allow margins on panels as it messes up layout. Margins must be set in the Blueprint settings
      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-components',
                                        'name'       => 'Components group',
                                        'selector'   => '.use-hw-css .pzarc-panel .pzarc-components, .use-hw-css .pzarc-panel .hentry',
                                        'properties' => array(
                                            'background',
                                            'borders',
                                            'padding',
                                            'rounded-corners',
                                            'box-shadow',
                                            'fonts'
                                        ),
                                    ));


      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-entry-titlefuck',
                                        'name'       => 'Entry title',
                                        'selector'   => '.use-hw-css .pzarc-panel .entry-title, .use-hw-css .pzarc-panel .entry-title:before',
                                        'states'     => array(
                                            'Links'   => '.use-hw-css .pzarc-panel .entry-title a',
                                            'Hover'   => '.use-hw-css .pzarc-panel .entry-title a:hover',
                                            'Clicked' => '.use-hw-css .pzarc-panel .entry-title a:active',
                                            'Visited' => '.use-hw-css .pzarc-panel .entry-title a:visited'
                                        ),
                                        'properties' => array(
                                            'background',
                                            'borders',
                                            'padding',
                                            'margins',
                                            'rounded-corners',
                                            'box-shadow',
                                            'fonts'
                                        ),
                                    ));


      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-entry-content',
                                        'name'       => 'Entry content',
                                        'selector'   => '.use-hw-css .pzarc-panel .entry-content, .use-hw-css .pzarc-panel .entry-excerpt',
                                        'states'     => array(
                                            'Links'   => '.use-hw-css .pzarc-panel .entry-content a, .use-hw-css .pzarc-panel .entry-excerpt a',
                                            'Hover'   => '.use-hw-css .pzarc-panel .entry-content a:hover, .use-hw-css .pzarc-panel .entry-excerpt a:hover',
                                            'Clicked' => '.use-hw-css .pzarc-panel .entry-content a:active, .use-hw-css .pzarc-panel .entry-excerpt a:active',
                                            'Visited' => '.use-hw-css .pzarc-panel .entry-content a:visited, .use-hw-css .pzarc-panel .entry-excerpt a:visited'
                                        ),
                                        'properties' => array(
                                            'background',
                                            'borders',
                                            'padding',
                                            'margins',
                                            'rounded-corners',
                                            'box-shadow',
                                            'fonts'
                                        ),
                                    ));

      // IMAGES
      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-figure',
                                        'name'       => 'Featured Image',
                                        'selector'   => '.use-hw-css .pzarc-panel figure.entry-thumbnail img',
                                        'properties' => array(
                                            'background',
                                            'borders',
                                            'padding',
                                            'margins',
                                            'rounded-corners',
                                            'box-shadow'
                                        ),
                                    ));
      $this->register_block_element(array(
                                        'id'         => 'pzarc-panel-figure-caption',
                                        'name'       => 'Featured image caption',
                                        'selector'   => '.use-hw-css .pzarc-panel figure.entry-thumbnail caption',
                                        'properties' => array(
                                            'background',
                                            'borders',
                                            'padding',
                                            'margins',
                                            'rounded-corners',
                                            'box-shadow',
                                            'fonts'
                                        ),
                                    ));
//      $this->register_block_element(array(
//                                        'id'         => 'pzarc-panel-backgroundimage',
//                                        'name'       => 'Background image',
//                                        'selector'   => '.use-hw-css .pzarc-panel figure.entry-thumbnail',
//                                        'properties' => array('borders',
//                                                              'padding',
//                                                              'margins',
//                                                              'rounded-corners',
//                                                              'box-shadow'),
//                                    ));
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
                                          'properties' => array(
                                              'background',
                                              'borders',
                                              'padding',
                                              'margins',
                                              'rounded-corners',
                                              'box-shadow',
                                              'fonts'
                                          ),
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
                                          'properties' => array(
                                              'background',
                                              'borders',
                                              'padding',
                                              'margins',
                                              'rounded-corners',
                                              'box-shadow',
                                              'fonts'
                                          ),
                                      ));

        $this->register_block_element(array(
                                          'id'         => 'pzarc-panel-meta-presuff-image' . $i,
                                          'name'       => 'CFG Prefix/Suffix image ' . $i,
                                          'selector'   => '.use-hw-css .pzarc-panel .entry-customfieldgroup-' . $i . ' .pzarc-presuff-image',
                                          'states'     => array(
                                              'Prefix' => '.use-hw-css .pzarc-panel .entry-customfieldgroup-' . $i . ' .pzarc-presuff-image.prefix-image',
                                              'Suffix' => '.use-hw-css .pzarc-panel .entry-customfieldgroup-' . $i . ' .pzarc-presuff-image.suffix-image',
                                          ),
                                          'properties' => array(
                                              'background',
                                              'borders',
                                              'padding',
                                              'margins',
                                              'rounded-corners',
                                              'box-shadow',
                                              'fonts'
                                          ),
                                      ));

        // Nav
        $this->register_block_element(array(
                                          'id'         => 'pzarc-nav-items',
                                          'name'       => 'Navigator items',
                                          'selector'   => '.use-hw-css .arc-slider-slide-nav-item',
                                          'states'     => array(
                                              'Active' => '.use-hw-css .arc-slider-slide-nav-item.active, .use-hw-css .arc-slider-slide-nav-item.current',
                                              'Hover'  => '.use-hw-css .arc-slider-slide-nav-item:hover',
                                          ),
                                          'properties' => array(
                                              'background',
                                              'borders',
                                              'padding',
                                              'rounded-corners',
                                              'box-shadow',
                                              'fonts'
                                          ),
                                      ));
        $this->register_block_element(array(
                                          'id'         => 'pzarc-accordion',
                                          'name'       => 'Accordion titles',
                                          'selector'   => '.use-hw-css .pzarc-accordion.title',
                                          'states'     => array(
                                              'Close' => '.use-hw-css .pzarc-accordion.title.close',
                                              'Open'  => '.use-hw-css .pzarc-accordion.title.open',
                                              'Hover' => '.use-hw-css .pzarc-accordion.title:hover',
                                          ),
                                          'properties' => array(
                                              'background',
                                              'borders',
                                              'padding',
                                              'rounded-corners',
                                              'box-shadow',
                                              'fonts'
                                          ),
                                      ));
        $this->register_block_element(array(
                                          'id'         => 'pzarc-masonry-fs-group',
                                          'name'       => 'Masonry filtering and sorting section',
                                          'selector'   => '.use-hw-css .arc-filtering-and-sorting',
                                          'properties' => array(
                                              'background',
                                              'borders',
                                              'padding',
                                              'rounded-corners',
                                              'box-shadow',
                                              'fonts'
                                          ),
                                      ));
        $this->register_block_element(array(
                                          'id'         => 'pzarc-masonry-buttons',
                                          'name'       => 'Masonry  filtering and sorting buttons',
                                          'selector'   => '.use-hw-css .arc-masonry-buttons button',
                                          'states'     => array(
                                              'Selected' => '.use-hw-css .arc-masonry-buttons button.selected',
                                              'Hover'    => '.use-hw-css .arc-masonry-buttons button:hover',
                                              'Clear'    => '.use-hw-css .arc-masonry-buttons button.showall',
                                          ),
                                          'properties' => array(
                                              'background',
                                              'borders',
                                              'padding',
                                              'rounded-corners',
                                              'box-shadow',
                                              'fonts'
                                          ),
                                      ));

      }
    }

    /**
     * Anything in here will be displayed when the block is being displayed.
     * */
    function content($block)
    {
      global $wp_query;
      $settings         = HeadwayArchitectBlockOptions::get_settings($block);
      $blueprint        = explode('##', $settings[ 'pzarc-blueprint' ]);
      $blueprint_tablet = explode('##', $settings[ 'pzarc-blueprint-tablet' ]);
      $blueprint_phone  = explode('##', $settings[ 'pzarc-blueprint-phone' ]);

      // We don't want tablet and phone to use 'none'
      $blueprint_tablet[ 0 ] = $blueprint_tablet[ 0 ] === 'none' ? null : $blueprint_tablet[ 0 ];
      $blueprint_phone[ 0 ]  = $blueprint_phone[ 0 ] === 'none' ? null : $blueprint_phone[ 0 ];

      $pzarc_overrides = array('ids'   => $settings[ 'pzarc-overrides-ids' ],
                               'tax'   => $settings[ 'pzarc-overrides-taxonomy' ],
                               'terms' => $settings[ 'pzarc-overrides-terms' ]);

      if (function_exists('pzarc')) {
        echo pzarc($blueprint[ 0 ], $pzarc_overrides, 'headway-block', null, $settings, $blueprint_tablet[ 0 ], $blueprint_phone[ 0 ]);

      }

      return;

    }
  }

  // End of class