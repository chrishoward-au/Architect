<?php

/**
 * Created by PhpStorm.
 * User: chrishoward
 * Date: 15/12/2013
 * Time: 10:02 PM
 */
class pzucd_Display
{
  public $output = '';
  public $source_data = '';
  public $query_vars = '';
  public $cell_info = '';
  public $template = '';
  public $nav_links = array();
  public $css ='/* UCD CSS */';

  function __construct()
  {
  }

  function template_header()
  {
    $this->output .= '<div id="pzucd=container-' . $this->template[ 'template-short-name' ] . '" class="pzucd-container">';
    if ($this->template[ 'template-pager' ] == 'hover')
    {
      $this->output .= '{{pager}}';
    }
  }

  function template_footer()
  {
    if ($this->template[ 'template-pager' ] == 'wordpress' || $this->template[ 'template-pager' ] == 'wppagenavi')
    {
      $this->output .= '{{pager}}';
    }
    $this->output .= '</div><!-- end pzucd_container-->';
  }

  function build_query($the_criteria, $overrides=null)
  {
    if ($this->template[ 'template-criteria' ] == 'default')
    {
      //don't change nuttin!
      // Do we need to check page type? Single etc?
      $this->query_vars = array();
    }
    else
    {
      //build the new query
      //Lot of work!
      switch ($the_criteria[ '_pzucd_criteria-content-source' ][ 0 ])
      {
        case 'images':
          $this->query_vars[ 'post_type' ]   = 'attachment';
          $this->query_vars[ 'post__in' ]    = $the_criteria[ '_pzucd_criteria-specific-images' ];
          $this->query_vars[ 'post_status' ] = array('publish', 'inherit', 'private');
          break;
      }
    }

     if ($overrides) {
       $this->query_vars[ 'post__in' ]= explode(',',$overrides);
       $this->query_vars['posts_per_page'] = count($this->query_vars[ 'post__in' ]);
     }
  }


  function xrender()
  {
    // Should we do it this way?
    // Or should it be filters?
    // And what would happen with multiple ? Would they all get mixed together?
    do_action('template-header');
    do_action('template-body');
    do_action('template-footer');

    // Or should we doo it an OOP way? Which we are already semi doing.

  }

  function render($pzucd_template, $overrides)
  {
    // THis is probably where we should preserve the wp_query
    global $wp_query;
    $original_query = $wp_query;
    $pzucd_query    = $wp_query;
    // Need to decide what query to use

    // THIS HAS TO ALL WORK NICELY WITH CONTENT IN CONTENT. I.E. NO RECURSIVE ISSUES.

    $this->template = $pzucd_template;


    // pzdebug($pzucd_template);
    // pzdebug((array)$pzucd_out);
    // Get the criteria
    $the_criteria = get_post_meta($pzucd_template[ 'template-criteria' ], null, true);

    if ($this->template[ 'template-criteria' ] != 'default')
    {

      self::get_source($the_criteria, $overrides);

      // Determine the content type the post type so we canget the right cell def (this could be anything from posts, to images to NGG, to RSS

      $content_type = $the_criteria[ '_pzucd_criteria-content-source' ][ 0 ];

      self::build_query($the_criteria, $overrides);
// no matter what happens, somewhere we've got to preserve the original query!
//      pzdebug($this->query_vars);
      if ($this->query_vars)
      {
        $pzucd_query = new WP_Query($this->query_vars);
        //     pzdebug((array)$pzucd_query);
      }
    }
    else
    {
      // Determine the content type the post type so we canget the right cell def
      //eeek. Gotta work out what page content type is!

      // What if WE have changed the query?? What is our real current query?

      $pzucd_query = $wp_query;

      // For defaults, determine the content type from the post type
      $content_type = (empty($pzucd_query->query_vars[ 'post_type' ]) ? 'post' : $pzucd_query->query_vars[ 'post_type' ]);
     // var_dump($content_type);
    }


    //  pzdebug((array)$pzucd_query);
    // pzdebug($pzucd_query->found_posts);
    // pzdebug(is_main_query());
//  pzdebug((array) $pzucd_query);


    // is filters a better way to do this? Altho how??
    //$this->output = apply_filters('pzucd_template_header',template_header());

    // The outer wrapper
    // use a method so we can swap it out in the future with different template headers
    self::template_header();
    // Cell defs will need to be a little more flexible to cater for for mixed sections of full and excerpts.

    $celldef = pzucd_celldef($content_type);

    foreach ($this->template[ 'section' ] as $key => $section_info)
    {
      // Build out the celldefinition here so dont' do it every single cell.
      $celldefinition     = self::build_cell_definition($celldef, $section_info[ 'section-cell-settings' ],$celldef);
      $this->section_info = $section_info;
      if ($pzucd_template[ 'section' ][ $key ][ 'section-enable' ])
      {

        $this->output .= '{{nav-top-outside}}';
        $this->output .= '{{nav-left-outside}}';
        if ($section_info[ 'section-layout-mode' ] == 'basic')
        {
          $this->output .= '<div class="pzucd-section pzucd-section-' . $key . '">';
        }
        else
        {
//        $this->output .= '<div class="js-isotope pzucd-section pzucd-section-' . $key . '" data-isotope-options=\'{ "layoutMode": "'.$pzucd_section_info['section-layout-mode'].'","itemSelector": ".pzucd-cell" }\'>';
          $this->output .= '<div class="pzucd-section pzucd-section-' . $key . '">';
        }

        if ($pzucd_query->have_posts())
        {
          $this->output .= '{{nav-top-inside}}';
        //  var_dump($pzucd_query->found_posts);
          while ($pzucd_query->have_posts())
          {
            $pzucd_query->the_post();

            self::build_cell($pzucd_query->post, $celldefinition,$celldef);

            self::set_nav_link();

// This needs to work out when to show! Maybe add it as a cell row???? Or at least an option??
            // if show comments {
//          $this->output = apply_filters('pzucd_comments',$this->output);

          } // End loop
          $this->output .= '{{nav-bottom-inside}}';
        }
        $this->output .= '</div><!-- end pzucd-section-' . $key . ' -->';
        $this->output .= '{{nav-top-outside}}';
        $this->output .= '{{nav-left-outside}}';

        // Add the section nav
        if ($section_info[ 'section-navigation' ] != 'none')
        {
          $this->output = str_replace('{{nav-' . $section_info[ 'section-nav-pos' ] . '-' . $section_info[ 'section-nav-loc' ] . '}}', self::add_nav(), $this->output);
        }
      }
    } // End sections
    // Close up the outer wrapper
    // use a method so we can swap it out in the future with different template headers
    self::template_footer();

    // Process any left over {{}} variables
    self::add_pager();
    self::strip_unused_tags();
    // Reset to original query status.  Will this be conditional?!
    $wp_query = $original_query;
    wp_reset_postdata();

  }


  function build_cell_definition($celldef, $section_cell_settings)
  {
//    pzdebug((array)$this);
    $cell_layout = json_decode($section_cell_settings[ '_pzucd_layout-cell-preview' ][ 0 ], true);
//    pzdebug($section_cell_settings);
    $meta1_confg = unserialize($section_cell_settings[ '_pzucd_cell-settings-meta1-config' ][ 0 ]);
    $meta2_confg = unserialize($section_cell_settings[ '_pzucd_cell-settings-meta2-config' ][ 0 ]);
    $meta3_confg = unserialize($section_cell_settings[ '_pzucd_cell-settings-meta3-config' ][ 0 ]);
    $cell_meta1  = (!empty($meta1_confg) ? '{{' . implode('}}, {{', $meta1_confg) . '}}' : null);
    $cell_meta2  = (!empty($meta2_confg) ? '{{' . implode('}}, {{', $meta2_confg) . '}}' : null);
    $cell_meta3  = (!empty($meta3_confg) ? '{{' . implode('}}, {{', $meta3_confg) . '}}' : null);
    // build up the template for the cell, ordering from
    // won't this be fun!!
    // need to match celllayout slugs to celldefs array index
    $cell_definition = '';
    foreach ((array)$cell_layout as $key => $value)
    {
      if ($value[ 'show' ])
      {
        if ($key != 'title') {
          $cell_definition .= $celldef[ $key ];
        } else {
          $cell_definition .= $celldef[ 'header' ];
        }
      }

    }
    //cell-settings-meta1-config  _pzucd_cell-settings-meta1-config
    $cell_definition = str_replace('{{meta1innards}}', $cell_meta1, $cell_definition);
    $cell_definition = str_replace('{{meta2innards}}', $cell_meta2, $cell_definition);
    $cell_definition = str_replace('{{meta3innards}}', $cell_meta3, $cell_definition);
    $cell_definition = str_replace('{{headerinnards}}', $celldef['title'], $cell_definition);
//    var_dump(esc_html($cell_definition));

  // And finally, stuff the whole lot into the wrapper


    return $cell_definition;
  }

  function build_cell($post_info, $cell_definition,$celldef)
  {
    //pzdebug($post_info);
    $cell_info = pzucd_flatten_wpinfo($this->section_info[ 'section-cell-settings' ]);


    // ALL THIS STYLING CRAP WILL BE MOVED TO A SEPARATE ROUTINE THAT CREATES A CACHED CSS
    //pzdebug($cell_info);

    $cell_width     = 100 / $this->section_info[ 'section-cells-across' ] - $this->section_info[ 'section-cells-vert-margin' ];
    $cell_min_width = $this->section_info[ 'section-min-cell-width' ];
    // this may need to be in its own method
    $cell_height = ($cell_info[ '_pzucd_layout-cell-height-type' ] == 'fixed') ? 'height:' . $cell_info[ '_pzucd_layout-cell-height' ] . 'px;' : null;

    // Open cell
    $this_cell = '<div class="pzucd-cell" style="position:relative;width:' . $cell_width . '%;margin:' . ($this->section_info[ 'section-cells-vert-margin' ] / 2) . '%;min-width:' . $cell_min_width . 'px;' . $cell_info[ '_pzucd_layout-format-cells' ] . $cell_height . '">';

      $position = 'static';
      $params   = array('width' => 300);
      // Returns false on failure.
      $post_image = bfi_thumb($post_info->guid, $params);
      $post_image = ($post_image ? $post_image : $post_info->guid);
      if ($cell_info[ '_pzucd_layout-background-image' ] == 'fill')
      {
        $this_cell .= '<div class="pzucd-bg-image"><img class="entry-image" src="' . $post_image . '"></div>';
        $position = 'absolute';
      }
      $layout                     = json_decode($cell_info[ '_pzucd_layout-cell-preview' ], true);
      $the_inputs[ 'title' ]      = apply_filters('the_title', get_the_title());
      $the_inputs[ 'excerpt' ]    = apply_filters('the_excerpt', get_the_excerpt());
      $the_inputs[ 'content' ]    = apply_filters('the_content', get_the_content());
      $the_inputs[ 'image' ]      = $post_image;
      $the_inputs[ 'date' ]       = apply_filters('the_date', get_the_date());
      $the_inputs[ 'categories' ] = apply_filters('the_category', 'Categories: ' . trim(get_the_category_list(', '), ', '));
      $the_inputs[ 'tags' ]       = apply_filters('the_tags', 'Tags: ' . trim(get_the_tag_list(', '), ', '));
      $the_inputs[ 'author' ]     = apply_filters('the_author', get_the_author());
      ob_start();
      comments_number();
      $comments_count = ob_get_contents();
      ob_end_clean();
      $the_inputs[ 'commentcount' ] = apply_filters('the_', $comments_count);
  //    $the_inputs[ '' ] = apply_filters('the_', get_the_());
  //    $the_inputs[ '' ] = apply_filters('the_', get_the_());
  //    $the_inputs[ '' ] = apply_filters('the_', get_the_());
  //    $the_inputs[ '' ] = apply_filters('the_', get_the_());
  //    $the_inputs[ '' ] = apply_filters('the_', get_the_());
  //


      // and heaps more!
      // this needs its ownmethod
      //   pzdebug($cell_info);
      // Need a better way to pullup the styling which will populate all fields.

  //    $components_open         = '<div class="pzucd-components" style="'.$cell_info['_pzucd_layout-format-components-group'].';position:' . $position . ';' . $cell_info[ '_pzucd_layout-sections-position' ] . ':'.$cell_info[ '_pzucd_layout-nudge-section-y' ].'%;width:' . $cell_info[ '_pzucd_layout-sections-widths' ] . '%;">';
      $components_open  = '<div class="pzucd-components" style="position:' . $position . ';' . $cell_info[ '_pzucd_layout-sections-position' ] . ':' . $cell_info[ '_pzucd_layout-nudge-section-y' ] . '%;width:' . $cell_info[ '_pzucd_layout-sections-widths' ] . '%;">';
      $components_close = '</div><!-- End components -->';
      $components       = self::build_components($components_open, $the_inputs, $layout, $components_close, $cell_info, $cell_definition);
    $components = str_replace('{{wrapperinnards}}',$components,$celldef['wrapper']);
    $this_cell .= $components . '</div><!-- end cell -->';

    $this->output .= $this_cell;

    // Replace wrapper tags
    //  id="post-{{postid}}"
    //  class="pzucd-{{classname}} post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{category-categories}} {{tag-tags}}"

    $this->output = str_replace('{{postid}}',get_the_id(),$this->output);
    $this->output = str_replace('{{posttype}}',get_post_type(),$this->output);
    $this->output = str_replace('{{poststatus}}',get_post_status(),$this->output);
    $this->output = str_replace('{{postformat}}',get_post_format(),$this->output);

    $the_categories = get_the_category();
    $categories_list = '';
    if (!empty($the_categories)) {
      foreach ($the_categories as $key => $value){
        $categories_list .= 'category-'.$value->slug.' ';
      }
    }
    $this->output = str_replace('{{category-categories}}',$categories_list,$this->output);

    $the_tags = get_the_tags();
    $tags_list = '';
    if (!empty($the_tags)) {
      foreach ($the_tags as $key => $value){
        $tags_list .= 'tag-'.$value->slug.' ';
      }
    }
    $this->output = str_replace('{{tag-tags}}',$tags_list,$this->output);

  }


  function add_pager()
  {
    // var_dump(get_the_id(),get_the_title());
    $pager = '<div class="pzucd-pager">';
//    $pager .=  get_next_posts_link( 'Older Entries', 999 );
//    $pager .=  get_previous_posts_link( 'Newer Entries' );
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    if (!empty($prev_post))
    {
      $pager .= '<a class="pzucd-pager-prev" href="' . get_permalink($prev_post->ID) . '">&laquo; ' . $prev_post->post_title . '</a>';
    }
    if (!empty($next_post))
    {
      $pager .= '<a class="pzucd-pager-next" href="' . get_permalink($next_post->ID) . '">' . $next_post->post_title . ' &raquo;</a>';

    }
    $pager .= '</div>';
    //  var_dump(esc_html($pager));
    $this->output = str_replace('{{pager}}', $pager, $this->output);

  }


  function add_nav()
  {
    $navigation = '<ul class="pzucd-navigation">';
    foreach ($this->nav_links as $key => $value)
    {
      $navigation .= '<li class="pzucd-nav-item"><a hef="' . $value[ 'link' ] . '" class="pzucd-nav-item-link">' . $value[ 'title' ] . '</a></li>';
    }
    $navigation .= '</ul>';

    return $navigation;
  }

  function set_nav_link()
  {

    $this->nav_links[ ] = array(
            'id'    => get_the_id(),
            'title' => get_the_title(),
            'link'  => get_permalink(),
    );
    // build up the nav links. Probably use an array that we can construct from later
  }

  function get_source($criteria, $overrides = null)
  {
    switch ($criteria[ '_pzucd_criteria-content-source' ][ 0 ])
    {
      case 'images' :
        $this->source_data = $criteria[ '_pzucd_criteria-specific-images' ];
        break;
      case 'posts' :
        break;
    }

  }

  function strip_unused_tags()
  {
    $this->output = str_replace(array('{{pager}}', '{{nav-top-outside}}', '{{nav-top-inside}}', '{{nav-left-outside}}', '{{nav-right-outside}}', '{{nav-bottom-inside}}', '{{nav-bottom-outside}}'), '', $this->output);
  }

  function build_components($components_open, $the_inputs, $layout, $components_close, $cell_info, $celldef)
  {
    $return_str = $components_open;
    if ($cell_info[ '_pzucd_layout-background-image' ] == 'align')
    {
      $return_str .= '<div class="pzucd-bg-image">' . $the_inputs[ 'image' ] . '</div>';
    }


    foreach ($the_inputs as $key => $the_input)
    {
      $celldef = str_replace('{{' . $key . '}}', $the_input, $celldef);
    }
    // Now this is where we need to use our cells defs!!!
    // how do we define what goes into the innards tho? maybe need an array option for each innard.
    // time for bed!
//    var_dump($layout);
//    foreach ($layout as $key => $value)
//    {
//      if ($value[ 'show' ])
//      {
//        switch ($key)
//        {
//          case 'title' :
//            $return_str .= '<h3 class="entry-title" style="'.$cell_info['_pzucd_layout-format-entry-title'].'">' . $the_inputs[ 'title' ] . '</h3>';
//            break;
//          case 'excerpt' :
//            $return_str .= '<div class="entry-excerpt" style="'.$cell_info['_pzucd_layout-format-entry-content'].'">' . esc_html($the_inputs[ 'excerpt' ]) . '</div>';
//            break;
//          case 'content' :
//            $return_str .= '<div class="entry-content" style="'.$cell_info['_pzucd_layout-format-entry-content'].'">' . $the_inputs[ 'content' ] . '</div>';
//            break;
//          case 'image' :
//            $return_str .= '<div class="pzucd_image">' . $the_inputs[ 'image' ] . '</div>';
//            break;
//
//        }
//      }
//    }
    $return_str .= $celldef . $components_close;
    return $return_str;

  }

}
