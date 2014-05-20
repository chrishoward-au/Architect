<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 20/05/2014
   * Time: 10:46 AM
   */

  /**
   * Class arc_Adapter
   *
   * Standardize data into easy to use objects
   *
   * Doubles up as a data dictionary
   *
   */
  class arc_Adapter
  {


  }

  class arc_BlueprintData extends arc_Adapter
  {
    public $b =
        array(
            'short_name'                  => '',
            'id'                          => '',
            'navigation'                  => '',
            'content_source'              => '',
            'posts_per_page'              => '',
            'section_1_enable'            => '',
            'section_2_enable'            => '',
            'orderby'                     => '',
            'orderdir'                    => '',
            'skip'                        => '',
            'sticky'                      => '',
            'section'                     => array(
                0 => array(
                    'title'               => '',
                    'panel_layout'        => '',
                    'panels_unlimited'    => '',
                    'panels_per_view'     => '',
                    'columns'             => '',
                    'layout_mode'         => '',
                    'min_panel_width'     => '',
                    'panels_vert_margin'  => '',
                    'panels_horiz_margin' => '',
                ),
                1 => array(

                    'title'               => '',
                    'panel_layout'        => '',
                    'layout_mode'         => '',
                    'panels_unlimited'    => '',
                    'panels_per_view'     => '',
                    'columns'             => '',
                    'min_panel_width'     => '',
                    'panels_vert_margin'  => '',
                    'panels_horiz_margin' => '',
                ),
                2 => array(
                    'title'               => '',
                    'panel_layout'        => '',
                    'layout_mode'         => '',
                    'panels_unlimited'    => '',
                    'panels_per_view'     => '',
                    'columns'             => '',
                    'min_panel_width'     => '',
                    'panels_vert_margin'  => '',
                    'panels_horiz_margin' => '',
                )
            ),
            'pager'                       => '',
            'pager_location'              => '',
            'navigator'                   => '',
            'navigator_position'          => '',
            'navigator_location'          => '',
            'navigator_pager'             => '',
            'navigator_items_visible'     => '',
            'section_transitions_heading' => '',
            'transitions_type'            => '',
            'transitions_duration'        => '',
            'transitions_interval'        => '',
            'transitions_autostart'       => '',
            'transitions_pause_on_hover'  => '',
            'sections_preview'            => '',
            'help_layout'                 => '',
            'content'                     => array(
                'defaults'  => array(),
                'posts'     => array(
                    'posts_section_start_posts'             => '',
                    'posts_categories_heading_start'        => '',
                    'posts_all_cats'                        => '',
                    'posts_exc_cats'                        => '',
                    'posts_sub_cat_archives'                => '',
                    'posts_categories_section_end'          => '',
                    'posts_tags_section_end'                => '',
                    'posts_inc_tags'                        => '',
                    'posts_exc_tags'                        => '',
                    'posts_custom_taxonomies_section_start' => '',
                    'posts_other_tax'                       => '',
                    'posts_tax_op'                          => '',
                    'posts_custom_taxonomies_section_end'   => '',
                    'posts_other_section_start'             => '',
                    'posts_other_section_end'               => '',
                    'posts_inc_cats'                        => '',
                    'posts_other_tax_tags'                  => '',
                    'posts_authors'                         => '',
                ),
                'pages'     => array(),
                'galleries' => array(
                    'galleries_gallery_source'  => '',
                    'galleries_galleryplus'     => '',
                    'galleries_nggallery'       => '',
                    'galleries_specific_images' => '',
                    'galleries_specific_ids'    => '',
                    'galleries_wp_post_gallery' => '',
                    'galleries_wp_post_images'  => '',
                ),
                'slides'    => array(),
                'cpt'       => array()

            ),
            'css'                         => array(
                'blueprint' => array(
                    'background' => '',
                    'padding'    => '',
                    'margins'    => '',
                    'borders'    => '',
                    'custom_css' => '',
                ),
                'section'   => array(
                    0 => array(
                        'background' => '',
                        'padding'    => '',
                        'margins'    => '',
                        'borders'    => '',
                    ),
                    1 => array(
                        'background' => '',
                        'padding'    => '',
                        'margins'    => '',
                        'borders'    => '',
                    ),
                    2 => array(
                        'background' => '',
                        'padding'    => '',
                        'margins'    => '',
                        'borders'    => '',
                    ),
                ),

            )
        );

    public function set(&$source) { }

    public function get()
    {
      return $this->b;
    }

  }

  class arc_PanelsData extends arc_Adapter
  {

    public $p = array(
        'short_name'        => '',
        'panel_height_type' => '',
        'd'                 => array(
            'components_to_show'  => '',
            'preview'             => '',
            'components_position' => '',
            'components_nudge_y'  => '',
            'background_position' => '',
            'image_spacing'       => '',

        ),
        'css'               => array(
            'panels_background'                   => '',
            'components_background'               => '',
            'components_padding'                  => '',
            'components_borders'                  => '',
            'hentry_background'                  => '',
            'hentry_padding'                      => '',
            'hentry_margin'                       => '',
            'hentry_borders'                      => '',
            'entry_title_font'                    => '',
            'entry_title_font_background'         => '',
            'entry_title_font_padding'            => '',
            'entry_title_font_margin'             => '',
            'entry_title_font_links'              => '',
            'entry_title_font_links_dec'          => '',
            'text_other_css'                      => '',
            'entry_meta_font'                     => '',
            'entry_meta_font_background'          => '',
            'entry_meta_font_padding'             => '',
            'entry_meta_font_links'               => '',
            'entry_content_font'                  => '',
            'entry_content_font_background'       => '',
            'entry_content_font_padding'          => '',
            'entry_content_font_links'            => '',
            'entry_readmore_font'                 => '',
            'entry_readmore_font_background'      => '',
            'entry_readmore_font_padding'         => '',
            'entry_readmore_font_links'           => '',
            'entry_image_background'              => '',
            'entry_image_padding'                 => '',
            'entry_mage_caption_font'             => '',
            'entry_mage_caption_font_background'  => '',
            'custom_css'                          => '',
            'panel_height'                        => '',
            'components_height'                   => '',
            'components_widths'                   => '',
            'components_nudge_x'                  => '',
            'background_image_width'              => '',
            'thumb_position'                      => '',
            'thumb_width'                         => '',
            'title_prefix'                        => '',
            'title_thumb_width'                   => '',
            'title_bullet_separator'              => '',
            'link_titles'                         => '',
            'meta1_config'                        => '',
            'meta2_config'                        => '',
            'meta3_config'                        => '',
            'meta_date_format'                    => '',
            'content_responsive_heading'          => '',
            'responsive_hide_content'             => '',
            'excerpt_heading'                     => '',
            'excerpts_word_count'                 => '',
            'readmore_truncation_indicator'       => '',
            'readmore_text'                       => '',
            'image_responsive_heading'            => '',
            'background_image_resize'             => '',
            'featured_image_heading'              => '',
            'maximize_content'                    => '',
            'link_image'                          => '',
            'image_captions'                      => '',
            'image_sizing_heading'                => '',
            'image_to_jpeg'                       => '',
            'image_resizing'                      => '',
            'image_max_width'                     => '',
            'image_max_height'                    => '',
            'image_bgcolour'                      => '',
            'image_quality'                       => '',
            'settings_custom1'                    => '',
            'settings_custom2'                    => '',
            'settings_custom3'                    => '',
            'panels_help_design'                  => '',
            'panels_section'                      => '',
            'panels_padding'                      => '',
            'panels_borders'                      => '',
            'components_section'                  => '',
            'hentry_section'                      => '',
            'hentry_background'                   => '',
            'entry_title_font_links_nodec'        => '',
            'entry_meta_font_links_dec'           => '',
            'entry_meta_font_links_nodec'         => '',
            'entry_content_font_links_dec'        => '',
            'entry_content_font_links_nodec'      => '',
            'entry_readmore'                      => '',
            'entry_readmore_font_links_dec'       => '',
            'entry_readmore_font_links_nodec'     => '',
            'entry_image'                         => '',
            'entry_image_borders'                 => '',
            'entry_image_caption'                 => '',
            'entry_image_caption_font'            => '',
            'entry_image_caption_font_background' => '',
            'entry_image_caption_font_margin'     => '',
        )

    );

    public function set(&$source)
    {

    }

    public function get()
    {
      return $this->p;
    }
  }