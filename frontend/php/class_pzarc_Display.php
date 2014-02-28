<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 15/12/2013
   * Time: 10:02 PM
   */
  /*
  * class for displaying content
  * this captures the content and returns it, so needs an echo statemnet to display in blueprints
  * this is necessary to work in a shortcode
  *
  */

  class pzarc_Display
  {
    public $output = '';
    private $source_data = '';
    private $query_vars = '';
    private $panel_info = '';
    private $section_info = '';
    private $blueprint = '';
    private $nav_links = array();
//  private $css = '/* Architect CSS */';

    /*************************************************
     *
     * Method: __construct()
     *
     * Purpose: Initialize class
     *
     * Parameters: Nil
     *
     * Returns: Nil
     *
     *************************************************/
    function __construct($blueprint_name)
    {
      // TODO: CHECK THIS IS EVEN WORKING!
      add_filter('excerpt_length', array($this, 'custom_excerpt_length'), 999);
      add_filter('excerpt_more', array($this, 'new_excerpt_more'));

    }


    /*
     *
     * opens the arc container
     *
     */
    /*************************************************
     *
     * Method: blueprint_header()
     *
     * Purpose: Initiates the Architect container. Draw the header section of the blueprint. Includes pager mustaches
     *
     * Parameters: $blueprint_id
     *
     * Returns: Blueprint header HTML with some mustaches
     *
     *************************************************/
    function blueprint_header($blueprint_id)
    {
      $this->output .= '<div id="pzarc-container-' . $this->blueprint[ 'blueprints_short-name' ] . '" class="pzarc-container swiper-container pzarc-blueprint-' . $blueprint_id . '">';
      if ($this->blueprint[ 'blueprints_pager' ] != 'none' && ($this->blueprint[ 'blueprints_pager-location' ] == 'top' || $this->blueprint[ 'blueprints_pager-location' ] == 'both'))
      {
        $this->output .= '{{pager}}';
      }
    }

    /*
     * draw the footer section of the blueprint. includes navigation
     * closes the arc container
     */
    /*************************************************
     *
     * Method: blueprint_footer
     *
     * Purpose:
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function blueprint_footer()
    {
      if ($this->blueprint[ 'blueprints_pager' ] != 'none' && ($this->blueprint[ 'blueprints_pager-location' ] == 'bottom' || $this->blueprint[ 'blueprints_pager-location' ] == 'both'))
      {
        $this->output .= '{{pager}}';
      }
      $this->output .= '</div><!-- end pzarc_container-->';
    }

    /*************************************************
     *
     * Method: build_query
     *
     * Purpose:
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function build_query($the_criteria, $overrides = null)
    {
      if ($this->blueprint[ 'content_general_content-source' ] == 'default')
      {
        //don't change nuttin!
        // Do we need to check page type? Single etc?
        $this->query_vars = array();
      }
      else
      {
        //build the new query
        //Lot of work!
        //     var_dump($the_criteria);
        switch ($the_criteria[ 'content_general_content-source' ])
        {

          case 'post':
            $this->query_vars[ 'post_type' ]    = 'post';
            $this->query_vars[ 'category__in' ] = (!empty($the_criteria[ 'content_posts_inc-cats' ])
                ? $the_criteria[ 'content_posts_inc-cats' ] : null);
            break;
          // could we build this into the panel def or somewhere else so more closely tied to the content type stuff
          case 'gallery':
            $this->query_vars[ 'post_type' ]           = 'attachment';
            $this->query_vars[ 'post__in' ]            = (!empty($the_criteria[ 'content_galleries-specific-images' ])
                ? $the_criteria[ 'content_galleries-specific-images' ] : null);
            $this->query_vars[ 'post_status' ]         = array('publish', 'inherit', 'private');
            $this->query_vars[ 'ignore_sticky_posts' ] = true;
            break;
        }
      }
// currently this is the only bit that really does anything
      if ($overrides)
      {
        $this->query_vars[ 'post__in' ]       = explode(',', $overrides);
        $this->query_vars[ 'posts_per_page' ] = count($this->query_vars[ 'post__in' ]);
      }
    }


    /*************************************************
     *
     * Method: xrender
     *
     * Purpose:
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function xrender()
    {
      // Should we do it this way?
      // Or should it be filters?
      // And what would happen with multiple ? Would they all get mixed together?
      do_action('blueprint-header');
      do_action('blueprint-body');
      do_action('blueprint-footer');

      // Or should we doo it an OOP way? Which we are already semi doing.

    }

    /*************************************************
     *
     * Method: render
     *
     * Purpose: Mushes content into  something displayable. Called from the template tag
     *
     * Parameters:
     *
     * Returns: Formatted and sctructured content
     *
     *************************************************/
    function render($pzarc_blueprint, $overrides, $is_shortcode = false)
    {
      // THis is probably where we should preserve the wp_query
      global $wp_query;
      $original_query = $wp_query;
      $pzarc_query    = $wp_query;
      // Need to decide what query to use

      // THIS HAS TO ALL WORK NICELY WITH CONTENT IN CONTENT. I.E. NO RECURSIVE ISSUES.

      $this->blueprint = $pzarc_blueprint;
pzdebug($this->blueprint);
      // Get the criteria
      if ($this->blueprint[ 'content_general_content-source' ] == 'defaults' && !$is_shortcode)
      {
        // We never want tocome in here for a short code???? That doesn't sound right! :P

        // Use default page query
        // Determine the content type the post type so we canget the right panel def
        //eeek. Gotta work out what page content type is!

        // What if WE have changed the query?? What is our real current query?
        //  add_filter('parse_query', array($this,'modify_default_query'));
        $pzarc_query = $wp_query;
        // For defaults, determine the content type from the post type
        $content_type = (empty($pzarc_query->query_vars[ 'post_type' ]) ? 'post'
            : $pzarc_query->query_vars[ 'post_type' ]);
      }
      else
      {
        $this->get_source($this->blueprint, $overrides);

        // Determine the content type the post type so we canget the right panel def (this could be anything from posts, to images to NGG, to RSS

        $content_type = $this->blueprint[ 'content_general_content-source' ];

        $this->build_query($this->blueprint, $overrides);
        // no matter what happens, somewhere we've got to preserve the original query!
        if (!empty($this->query_vars))
        {
          $pzarc_query = new WP_Query($this->query_vars);
        }
      }



      // is filters a better way to do this? Altho how??
      //$this->output = apply_filters('pzarc_blueprint_header',blueprint_header());

      // The outer wrapper
      // use a method so we can swap it out in the future with different blueprint headers
      $this->blueprint_header($this->blueprint[ 'blueprint-id' ]);
      // panel defs will need to be a little more flexible to cater for for mixed sections of full and excerpts.

      $panel_def = pzarc_panel_def($content_type);
      /*********************************************************************
       **********************************************************************
       * THIS IS THE MAIN SECTIONS LOOP. THE WP LOOP IS CONTAINED WITHIN IT
       *
       * THE QUERY HAS ALREADY HAPPENED. NOW WE JUST NEED TO SHOW THE CONTENT, BROKEN DOWN INTO SECTIONS AND panelS
       **********************************************************************
       **********************************************************************/

      if (!empty($this->blueprint[ 'section' ]))
      {
        $key = 0;
        while (!empty($this->blueprint[ 'section' ][ $key ]))
        {
          $section_info = $this->blueprint[ 'section' ][ $key ];
          // load up the panel's css for this section
          $panelid = $this->blueprint[ 'blueprints_section-' . $key . '-panel-layout' ];
          //var_dump($section_info);
          if (!empty($this->blueprint[ 'blueprint-id' ]))
          {
            $upload_dir = wp_upload_dir();
            $filename = trailingslashit($upload_dir[ 'baseurl' ]) . '/cache/pizazzwp/arc/pzarc-panel-layout-' . $panelid . '.css';
            wp_enqueue_style('blueprint-css-' . $this->blueprint[ 'blueprint-id' ], $filename);
          }

          $this->section_info = $section_info;
          if ($pzarc_blueprint[ 'section' ][ $key ][ 'section-enable' ])
          {

            $this->output .= ($key == 0) ? '{{nav-top-outside}}' : null;
            $this->output .= ($key == 0) ? '{{nav-left-outside}}' : null;
            if ($pzarc_blueprint[ 'blueprints_section-' . $key . '-layout-mode' ] == 'basic')
            {
              $this->output .= '<div class="pzarc-section pzarc-section-' . ($key + 1) . ' swiper-wrapper">';
            }
            else
            {
              //        $this->output .= '<div class="js-isotope pzarc-section pzarc-section-' . $key . '" data-isotope-options=\'{ "layoutMode": "'.$pzarc_section_info['section-layout-mode'].'","itemSelector": ".pzarc-panel" }\'>';
              $this->output .= '<div class="pzarc-section pzarc-section-' . ($key + 1) . '">';
            }

            if ($pzarc_query->have_posts())
            {
              $this->output .= ($key == 0) ? '{{nav-top-inside}}' : null;
              //  var_dump($pzarc_query->found_posts);
              $i = 1;
              while ($pzarc_query->have_posts())
              {
                $pzarc_query->the_post();
                $this->build_panel($pzarc_query->post, $panel_def, $panelid, $section_info[ 'section-panel-settings' ], $key);

                //      $this->build_panel($pzarc_query->post, $panel_definition,$panel_def,$section_info['section-panel-layout']);

                $this->set_nav_link();

                // This needs to work out when to show! Maybe add it as a panel row???? Or at least an option??
                // if show comments {
                //          $this->output = apply_filters('pzarc_comments',$this->output);

                // Leave the while loop if up to the post count
                // blueprints_section-1-panels-per-view
                if ($pzarc_blueprint[ 'blueprints-section-' . $key . '-panels-per-view' ] != 0 && ++$i > $pzarc_blueprint[ 'blueprints-section-' . $key . '-panels-per-view' ])
                {

                  break;
                }

              } // End WP loop - tho we might use it some more
              $this->output .= ($key == 0) ? '{{nav-bottom-inside}}' : null;

            }
            $this->output .= '</div><!-- end pzarc-section-' . ($key + 1) . ' -->';
            $this->output .= ($key == 0) ? '{{nav-top-outside}}' : null;
            $this->output .= ($key == 0) ? '{{nav-left-outside}}' : null;
            // Add the section nav
            //      var_dump($pzarc_blueprint[ 'blueprints_navigation' ]);
            if ($pzarc_blueprint[ 'blueprints_navigation' ] != 'none')
            {
              $this->output = str_replace(
                  '{{nav-' . $pzarc_blueprint[ 'blueprints_navigator-position' ] . '-' . $pzarc_blueprint[ 'blueprints_navigator-location' ] . '}}',
                  $this->add_nav(),
                  $this->output
              );
              //var_dump($this->output);
            }
            // if we are out ofthe loop then we've run out of posts, so break out otherwise it just loops back tothe firstpost. go figure!
            if (!in_the_loop())
            {
              break;
            }
          }
          $key++;
        }

      }
      /*********************************************************************
       **********************************************************************
       * // End sections
       *********************************************************************
       **********************************************************************/

      // Close up the outer wrapper
      // use a method so we can swap it out in the future with different blueprint headers
      $this->blueprint_footer();

      // Process any left over {{}} variables

      $this->output = str_replace('{{pager}}', $this->add_pager(), $this->output);
      $this->output = $this->strip_unused_tags($this->output);
      // Reset to original query status.  Will this be conditional?!
      $wp_query = $original_query;
      wp_reset_postdata();

    }


    /*************************************************
     *
     * Method: build_panel
     *
     * Purpose:
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function build_panel($post_info, $panel_def, $panelid, $section_panel_settings, $key)
    {
      $featureat        = '';
      $this->panel_info = ($this->section_info[ 'section-panel-settings' ]);
      $panel_width     = 100 / $this->blueprint[ 'blueprints_section-'.$key.'-columns' ] - $this->blueprint[ 'blueprints_section-' . $key . '-panels-vert-margin' ];
      $panel_min_width = $this->blueprint[ 'blueprints_section-' . $key . '-min-panel-width' ];
      // this may need to be in its own method
      $panel_height = ($this->panel_info[ 'panels_settings_panel-height-type' ] == 'fixed')
          ? 'height:' . $this->panel_info[ 'panels_settings_panel-height' ] . 'px;' : null;

      // Open panel
      $this_panel = '<div class="pzarc-panel swiper-slide pzarc-{{classname}}" style="position:relative;width:' . $panel_width . '%;margin:' . ($this->blueprint[ 'blueprints_section-' . $key . '-panels-vert-margin' ] / 2) . '%;min-width:' . $panel_min_width . 'px;' . $panel_height . '">';


      // Returns false on failure.
      // ADD CHECK THERE IS AN IMAGE HERE

      // ang on... this isn't always whe thum. :/ need tofind thumb for different conent types :S
      $thumb_src   = '';
      $focal_point = false;
      if (has_post_thumbnail($post_info->ID))
      {
        $thumbnail   = wp_get_attachment_image_src(get_post_thumbnail_id($post_info->ID), 'full');
        $thumb_src   = $thumbnail[ 0 ];
        $focal_point = get_post_meta(get_post_thumbnail_id(), 'pzgp_focal_point', true);
      }
      elseif ($post_info->post_type == 'attachment')
      {
        $thumb_src   = $post_info->guid;
        $focal_point = get_post_meta($post_info->guid, 'pzgp_focal_point', true);
      }

      $params = array('uid'         => $panelid . '_' . $post_info->ID,
                      'width'       => $this->panel_info[ 'panels_design_image-max-width' ],
                      'height'      => $this->panel_info[ 'panels_design_image-max-height' ],
                      'type'        => $this->panel_info[ 'panels_design_image-resizing' ],
                      'backcolour'  => $this->panel_info[ 'panels_design_image-bgcolour' ],
                      'quality'     => $this->panel_info[ 'panels_design_image-quality' ],
                      'focal_point' => $focal_point);

      // BFI
      //$post_image = bfi_thumb($thumb_src, $params);

      //JOB

       $post_image = ($this->panel_info[ 'panels_design_thumb-position' ] != 'none')?job_resize($thumb_src, $params, PZARC_CACHE_PATH, PZARC_CACHE_URL):null;

      // if ($this->panel_info[ 'panels_settings_background-image' ] == 'fill')
      // {
      //   $this_panel .= '<div class="pzarc-bg-image"><img class="entry-image" src="' . $post_image . '"></div>';
      // }
      $layout = json_decode($this->panel_info[ 'panels_design_preview' ], true);
      // Get all the content data
      $the_title = get_the_title();
      switch ($this->panel_info[ 'panels_design_title-prefix' ])
      {
        case 'none':
          break;
        case 'bullet':
          $the_title = '&bull; ' . $the_title;
          break;
        case 'thumb':
          $the_title = '<img src="' . bfi_thumb($thumb_src, array('width' > 32, 'height' => 32)) . '"/>' . $the_title;
          break;
      }

      // These match with the panel definition mustaches
      // THIS NEEDS TO HAPPEN IN A SEPARATE CLASS SO WE CAN BE EXTENSIBLE
      $the_inputs[ 'title' ]      = apply_filters('the_title', $the_title);
      $the_inputs[ 'excerpt' ]    = apply_filters('the_excerpt', get_the_excerpt());
      $the_inputs[ 'content' ]    = apply_filters('the_content', get_the_content());
      $the_inputs[ 'image' ]      = $post_image; // This needs to be a url coz that's how it's shown
      $the_inputs[ 'caption' ]    = pzarc_get_image_caption(get_post_thumbnail_id($post_info->ID));
      $the_inputs[ 'date' ]       = apply_filters('the_date', get_the_date(empty($section_panel_settings[ 'panels_design_meta-date-format' ])
                                                                               ? 'l, F j, Y g:i a'
                                                                               : $section_panel_settings[ 'panels_design_meta-date-format' ]));
      $the_inputs[ 'categories' ] = apply_filters('the_category', 'Categories: ' . trim(get_the_category_list(', '), ', '));
      $the_inputs[ 'tags' ]       = apply_filters('the_tags', 'Tags: ' . trim(get_the_tag_list(', '), ', '));
      $the_inputs[ 'author' ]     = apply_filters('the_author', get_the_author());
      $the_inputs[ 'permalink' ]  = get_permalink();

      ob_start();
      comments_number();
      $comments_count = ob_get_contents();
      ob_end_clean();
      //TODO Work out what this filter is meant to be!
      $the_inputs[ 'commentcount' ] = apply_filters('the_', $comments_count);
      foreach ($the_inputs as $key => $value)
      {
        if (empty($value))
        {
          $layout[ $key ][ 'show' ] = false;
        }
      }

      // Is there a way to make this so it doesn't need to know the paneldef fields?
      if ($this->panel_info[ 'panels_design_thumb-position' ] == 'none' || empty($post_image))
      {
        $panel_def[ 'excerpt' ] = str_replace('{{image-in-content}}', '', $panel_def[ 'excerpt' ]);
        $panel_def[ 'content' ] = str_replace('{{image-in-content}}', '', $panel_def[ 'content' ]);
        if (empty($post_image))
        {
          $panel_def[ 'excerpt' ] = str_replace('{{nothumb}}', 'pznothumb', $panel_def[ 'excerpt' ]);
          $panel_def[ 'content' ] = str_replace('{{nothumb}}', 'pznothumb', $panel_def[ 'content' ]);
        }
      }
      else
      {
        $panel_def[ 'image' ]   = str_replace('{{incontent}}', ' incontent pzarc-align ' . $this->panel_info[ 'panels_design_thumb-position' ], $panel_def[ 'image' ]);
        $panel_def[ 'excerpt' ] = str_replace('{{image-in-content}}', $panel_def[ 'image' ], $panel_def[ 'excerpt' ]);
        $panel_def[ 'content' ] = str_replace('{{image-in-content}}', $panel_def[ 'image' ], $panel_def[ 'content' ]);

      }

      if (!empty($this->panel_info[ 'panels_design_link-titles' ]) && $this->panel_info[ 'panels_design_link-titles' ])
      {
        $panel_def[ 'title' ] = str_replace('{{postlink}}', $panel_def[ 'postlink' ], $panel_def[ 'title' ]);
        $panel_def[ 'title' ] = str_replace('{{closepostlink}}', '</a>', $panel_def[ 'title' ]);

      }
      // Build this panel's layout
      $panel_definition = $this->build_panel_definition($panel_def, $section_panel_settings, $layout);

      $panel_definition = (!empty($this->panel_info[ 'panels_design_image-captions' ]) && $this->panel_info[ 'panels_design_image-captions' ])
          ? str_replace('{{captioncode}}', $panel_def[ 'caption' ], $panel_definition) : $panel_definition;
      if ((!empty($this->panel_info[ 'panels_design_link-image' ]) && $this->panel_info[ 'panels_design_link-image' ] && $this->panel_info[ 'panels_design_link-image' ]) && $this->panel_info[ 'panels_settings_background-image' ] == 'none')
      {
        $panel_definition = str_replace('{{postlink}}', $panel_def[ 'postlink' ], $panel_definition);
        $panel_definition = str_replace('{{closepostlink}}', '</a>', $panel_definition);

      }


      // Create the content section

      $components_open  = '<div class="pzarc-components">';
      $components_close = '</div><!-- End components -->';
      $components       = $this->build_components($components_open, $the_inputs, $layout, $components_close, $panel_definition);
      $components       = str_replace('{{wrapperinnards}}', $components, $panel_def[ 'wrapper' ]);

      $this_panel .= $components . '</div><!-- end panel -->';

      // Add background image (or video!)
      if ($this->panel_info[ 'panels_settings_background-image' ] == 'align' && !empty($the_inputs[ 'image' ]))
      {
        if ($this->panel_info[ 'panels_settings_components-position' ] == 'bottom' || $this->panel_info[ 'panels_settings_components-position' ] == 'right')
        {
          $this_panel = str_replace('{{bgimagetl}}', '<div class="pzarc-bg-image"><img class="nofill" src="' . $the_inputs[ 'image' ] . '" /></div>', $this_panel);
        }
        elseif ($this->panel_info[ 'panels_settings_components-position' ] == 'top' || $this->panel_info[ 'panels_settings_components-position' ] == 'left')
        {
          $this_panel = str_replace('{{bgimagebr}}', '<div class="pzarc-bg-image"><img class="nofill" src="' . $the_inputs[ 'image' ] . '" /></div>', $this_panel);
          // need to use some absolute positioning to stop content being cut off
          $featureat = ' abs-content';
        }
      }
      if ($this->panel_info[ 'panels_settings_background-image' ] == 'fill' && !empty($the_inputs[ 'image' ]))
      {

        if ($this->panel_info[ 'panels_settings_components-position' ] == 'bottom' || $this->panel_info[ 'panels_settings_components-position' ] == 'right')
        {
          $this_panel = str_replace('{{bgimagetl}}', '<div class="pzarc-bg-image"><img class="fill ' . $this->panel_info[ 'panels_design_feature-scale' ] . '" src="' . $the_inputs[ 'image' ] . '" /></div>', $this_panel);
          $featureat  = ' abs-content';
        }
        elseif ($this->panel_info[ 'panels_settings_components-position' ] == 'top' || $this->panel_info[ 'panels_settings_components-position' ] == 'left')
        {
          $this_panel = str_replace('{{bgimagebr}}', '<div class="pzarc-bg-image"><img class="fill ' . $this->panel_info[ 'panels_design_feature-scale' ] . '" src="' . $the_inputs[ 'image' ] . '"/></div>', $this_panel);
          $featureat  = ' abs-content';
        }
      }
      $this->output .= $this_panel;
      // Add the image in the content and excerpt if requested

      // Replace wrapper tags

      $this->output = str_replace('{{postid}}', get_the_id(), $this->output);
      $this->output = str_replace('{{posttype}}', get_post_type(), $this->output);
      $this->output = str_replace('{{poststatus}}', get_post_status(), $this->output);
      $this->output = str_replace('{{postformat}}', get_post_format(), $this->output);
      $this->output = str_replace('{{classname}}', $panelid, $this->output);
      $this->output = str_replace('{{width}}', $this->panel_info[ 'panels_design_image-max-width' ], $this->output);
      $this->output = str_replace('{{pzclasses}}', $featureat, $this->output);


      $the_categories  = get_the_category();
      $categories_list = '';
      if (!empty($the_categories))
      {
        foreach ($the_categories as $key => $value)
        {
          $categories_list .= 'category-' . $value->slug . ' ';
        }
      }
      $this->output = str_replace('{{category-categories}}', $categories_list, $this->output);

      $the_tags  = get_the_tags();
      $tags_list = '';
      if (!empty($the_tags))
      {
        foreach ($the_tags as $key => $value)
        {
          $tags_list .= 'tag-' . $value->slug . ' ';
        }
      }
      $this->output = str_replace('{{tag-tags}}', $tags_list, $this->output);

    } //End of function build_panel

    /*************************************************
     *
     * Method: build_panel_definition
     *
     * Purpose: Wha?? Where is this being used??!!
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function build_panel_definition($panel_def, $section_panel_settings, $panel_layout)
    {
      $panel_meta1 = preg_replace('/%(\\w*)%/u', '{{$1}}', (!empty($section_panel_settings[ 'panels_design_meta1-config' ]) ? $section_panel_settings[ 'panels_design_meta1-config' ] : null));
      $panel_meta2 = preg_replace('/%(\\w*)%/u', '{{$1}}', (!empty($section_panel_settings[ 'panels_design_meta2-config' ]) ? $section_panel_settings[ 'panels_design_meta2-config' ] : null));
      $panel_meta3 = preg_replace('/%(\\w*)%/u', '{{$1}}', (!empty($section_panel_settings[ 'panels_design_meta3-config' ]) ? $section_panel_settings[ 'panels_design_meta3-config' ] : null));

      // build up the blueprint for the panel, ordering from
      // won't this be fun!!
      // need to match panellayout slugs to paneldefs array index
      $panel_definition = '';
      foreach ((array)$panel_layout as $key => $value)
      {
        if ($value[ 'show' ])
        {
          if ($key != 'title')
          {
            $panel_definition .= $panel_def[ $key ];
          }
          else
          {
            $panel_definition .= $panel_def[ 'header' ];
          }
        }

      }
      $panel_definition = str_replace('{{meta1innards}}', $panel_meta1, $panel_definition);
      $panel_definition = str_replace('{{meta2innards}}', $panel_meta2, $panel_definition);
      $panel_definition = str_replace('{{meta3innards}}', $panel_meta3, $panel_definition);
      $panel_definition = str_replace('{{headerinnards}}', $panel_def[ 'title' ], $panel_definition);

      return $panel_definition;
    }

    /*************************************************
     *
     * Method: build_components
     *
     * Purpose:
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function build_components($components_open, $the_inputs, $layout, $components_close, $panel_def)
    {
      // $layout = an arrayed version of the panel layout. Not sure why it's there.
      $return_str = $components_open;

      foreach ($the_inputs as $key => $the_input)
      {
        if (!empty($the_input))
        {
          $panel_def = str_replace('{{' . $key . '}}', $the_input, $panel_def);
        }
      }

      $return_str .= $panel_def . $components_close;
      return $return_str;

    }


    /*************************************************
     *
     * Method: add_pager
     *
     * Purpose:
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function add_pager()
    {

      $pager = '';

      $this->blueprint[ 'blueprints_pager' ]
          = ($this->blueprint[ 'blueprints_pager' ] == 'wppagenavi' && !function_exists('wp_pagenavi') ? 'prevnext'
          : $this->blueprint[ 'blueprints_pager' ]);

      switch ($this->blueprint[ 'blueprints_pager' ])
      {
        case 'names':
          $pager = '<div class="pzarc-pager nav-links">';
          //    $pager .=  get_next_posts_link( 'Older Entries', 999 );
          //    $pager .=  get_previous_posts_link( 'Newer Entries' );
          $prev_post = get_previous_post();
          $next_post = get_next_post();

          if (!empty($prev_post))
          {
            $pager .= '<span class="pzarc-pager-prev nav-previous"><a href="' . get_permalink($prev_post->ID) . '">&laquo; ' . $prev_post->post_title . '</a></span>';
          }
          if (!empty($next_post))
          {
            $pager .= '<span class="pzarc-pager-next nav-next"><a href="' . get_permalink($next_post->ID) . '">' . $next_post->post_title . ' &raquo;</a></span>';

          }
          $pager .= '</div>';
          break;

        case 'prevnext':
          $pager     = '<div class="pzarc-pager">';
          $prev_post = get_next_posts_link('Older Entries', 999);
          $next_post = get_previous_posts_link('Newer Entries');

          if (!empty($prev_post))
          {
            $pager .= '<span class="pzarc-pager-prev">' . $prev_post . '</span>';
          }
          if (!empty($next_post))
          {
            $pager .= '<span class="pzarc-pager-next">' . $next_post . '</span>';

          }
          $pager .= '</div>';
          break;

        case 'wppagenavi':
          if (function_exists('wp_pagenavi'))
          {
            $pager = '<div class="pzarc-pager">';
            ob_start();
            wp_pagenavi();
            $pager .= ob_get_contents();
            ob_end_clean();
            $pager .= '</div>';
          }
          break;
      }

      return $pager;

    }

    /*************************************************
     *
     * Method: add_nav
     *
     * Purpose: Builds and returns the navigator
     *
     * Parameters: None
     *
     * Returns: Built navigator as HTML
     *
     *************************************************/
    function add_nav()
    {
      $navigation = '<ul class="pzarc-navigation">';
      foreach ($this->nav_links as $key => $value)
      {
        $navigation .= '<li class="pzarc-nav-item"><a hef="' . $value[ 'link' ] . '" class="pzarc-nav-item-link">' . $value[ 'title' ] . '</a></li>';
      }
      $navigation .= '</ul>';

      return $navigation;
    }

    /*************************************************
     *
     * Method: set_nav_link
     *
     * Purpose:
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function set_nav_link()
    {

      $this->nav_links[ ] = array(
          'id'    => get_the_id(),
          'title' => get_the_title(),
          'link'  => get_permalink(),
      );
      // build up the nav links. Probably use an array that we can construct from later
    }

    /*************************************************
     *
     * Method: get_source
     *
     * Purpose:
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function get_source($criteria, $overrides = null)
    {
      switch ($criteria[ 'content_content-source' ])
      {
        case 'images' :
          $this->source_data = $criteria[ 'content_specific-images' ];
          break;
        case 'posts' :
          break;
      }

    }


    /*************************************************
     *
     * Method: strip_unused_tags
     *
     * Purpose: Remove any unused mustaches from the remaining HTML
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function strip_unused_tags($strip_from)
    {
      // removed while in development
      //  return $strip_from;

      return preg_replace('/{{([\w|\-]*)}}/s', '', $strip_from);
    }


    /*************************************************
     *
     * Method: modify_default_query
     *
     * Purpose:
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function modify_default_query($q)
    {
      if (!is_admin())
      {
        $q->set('posts_per_page', '10');
      }
      pzdebug((array)$q);

      return $q;

    }

    /*************************************************
     *
     * Method: custom_excerpt_length
     *
     * Purpose:
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function custom_excerpt_length($length)
    {
      if (empty($this->panel_info) || empty($this->panel_info[ 'panels_design_excerpts-word-count' ]))
      {
        return $length;
      }

      return $this->panel_info[ 'panels_design_excerpts-word-count' ];
    }

    /*************************************************
     *
     * Method: new_excerpt_more
     *
     * Purpose:
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    function new_excerpt_more($more)
    {
      if (empty($this->panel_info) || empty($this->panel_info[ 'panels_design_excerpts-linkmore' ]))
      {
        return $more;
      }

      if (!empty($this->panel_info[ 'panels_design_excerpts-linkmore' ]))
      {
        return esc_html($this->panel_info[ 'panels_design_excerpts-morestr' ]) . ' <a class="read-more" href="' . get_permalink(get_the_ID()) . '">' . esc_html($this->panel_info[ 'panels_design_excerpts-linkmore' ]) . '</a>';
      }
      else
      {
        return esc_html($this->panel_info[ 'panels_design_excerpts-morestr' ]);
      }
    }
  } //EOC

// Functions

  /*************************************************
   *
   * Function: pzarc_get_image_caption
   *
   * Purpose:
   *
   * Parameters:
   *
   * Returns:
   *
   *************************************************/
  function pzarc_get_image_caption($image_id)
  {
    $pzarc_image = get_post($image_id);

    return $pzarc_image->post_excerpt;
  }