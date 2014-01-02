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
class pzucd_Display
{
  public $output = '';
  public $source_data = '';
  public $query_vars = '';
  public $cell_info = '';
  public $blueprint = '';
  public $nav_links = array();
  public $css ='/* UCD CSS */';

  function __construct()
  {
  }


/*
 * draw the header section of the blueprint. includes navigation
 * opens the ucd container
 *
 */
 function blueprint_header($blueprint_id)
  {
    $this->output .= '<div id="pzucd=container-' . $this->blueprint[ 'blueprint-short-name' ] . '" class="pzucd-container pzucd-blueprint-'.$blueprint_id.'">';
    if ($this->blueprint[ 'blueprint-pager' ] == 'hover')
    {
      $this->output .= '{{pager}}';
    }
  }

/*
 * draw the footer section of the blueprint. includes navigation
 * closes the ucd container
 */
  function blueprint_footer()
  {
    if ($this->blueprint[ 'blueprint-pager' ] == 'wordpress' || $this->blueprint[ 'blueprint-pager' ] == 'wppagenavi')
    {
      $this->output .= '{{pager}}';
    }
    $this->output .= '</div><!-- end pzucd_container-->';
  }

  function build_query($the_criteria, $overrides=null)
  {
    if ($this->blueprint[ 'blueprint-criteria' ] == 'default')
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
      // could we build this into the cell def or somewhere else so more closely tied to the content type stuff
        case 'images':
          $this->query_vars[ 'post_type' ]   = 'attachment';
          $this->query_vars[ 'post__in' ]    = $the_criteria[ '_pzucd_criteria-specific-images' ];
          $this->query_vars[ 'post_status' ] = array('publish', 'inherit', 'private');
          break;
      }
    }
// currently this is the only bit that really does anything
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
    do_action('blueprint-header');
    do_action('blueprint-body');
    do_action('blueprint-footer');

    // Or should we doo it an OOP way? Which we are already semi doing.

  }

  function render($pzucd_blueprint, $overrides)
  {
    // THis is probably where we should preserve the wp_query
    global $wp_query;
    $original_query = $wp_query;
    $pzucd_query    = $wp_query;
    // Need to decide what query to use

    // THIS HAS TO ALL WORK NICELY WITH CONTENT IN CONTENT. I.E. NO RECURSIVE ISSUES.

    $this->blueprint = $pzucd_blueprint;


    // pzdebug($pzucd_blueprint);
    // pzdebug((array)$pzucd_out);
    // Get the criteria
    $the_criteria = get_post_meta($pzucd_blueprint[ 'blueprint-criteria' ], null, true);

    if ($this->blueprint[ 'blueprint-criteria' ] != 'default')
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
    //  add_filter('parse_query', array($this,'modify_default_query'));
      $pzucd_query = $wp_query;
      $pzucd_query->set( 'posts_per_page', $this->blueprint['blueprint-posts-per-page'] );
      $pzucd_query->get_posts();
 //pzdebug((array)$pzucd_query->request);
      // For defaults, determine the content type from the post type
      $content_type = (empty($pzucd_query->query_vars[ 'post_type' ]) ? 'post' : $pzucd_query->query_vars[ 'post_type' ]);
     // var_dump($content_type);
    }


    //  pzdebug((array)$pzucd_query);
    // pzdebug($pzucd_query->found_posts);
    // pzdebug(is_main_query());
//  pzdebug((array) $pzucd_query);


    // is filters a better way to do this? Altho how??
    //$this->output = apply_filters('pzucd_blueprint_header',blueprint_header());

    // The outer wrapper
    // use a method so we can swap it out in the future with different blueprint headers
    self::blueprint_header($this->blueprint['blueprint-id']);
    // Cell defs will need to be a little more flexible to cater for for mixed sections of full and excerpts.

    $celldef = pzucd_celldef($content_type);
    
    /*********************************************************************
    **********************************************************************
    * THIS IS THE MAIN SECTIONS LOOP. THE WP LOOP IS CONTAINED WITHIN IT
    *
    * THE QUERY HAS ALREADY HAPPENED. NOW WE JUST NEED TO SHOW THE CONTENT, BROKEN DOWN INTO SECTIONS AND CELLS
    **********************************************************************
    **********************************************************************/
    foreach ($this->blueprint[ 'section' ] as $key => $section_info)
    {

      // load up the cell's css for this section
      $cellid =$section_info['section-cell-layout'];
      if (!empty($this->blueprint['blueprint-id'])) {
        $upload_dir = wp_upload_dir();
        $filename   = trailingslashit($upload_dir[ 'baseurl' ]) . '/cache/pizazzwp/ucd/pzucd-blueprint-' . $this->blueprint['blueprint-id'] . '.css';
        wp_enqueue_style('blueprint-css'.$this->blueprint['blueprint-id'],$filename);
      }

      $this->section_info = $section_info;
      if ($pzucd_blueprint[ 'section' ][ $key ][ 'section-enable' ])
      {

        $this->output .= '{{nav-top-outside}}';
        $this->output .= '{{nav-left-outside}}';
        if ($section_info[ 'section-layout-mode' ] == 'basic')
        {
          $this->output .= '<div class="pzucd-section pzucd-section-' . ($key+1) . '">';
        }
        else
        {
//        $this->output .= '<div class="js-isotope pzucd-section pzucd-section-' . $key . '" data-isotope-options=\'{ "layoutMode": "'.$pzucd_section_info['section-layout-mode'].'","itemSelector": ".pzucd-cell" }\'>';
          $this->output .= '<div class="pzucd-section pzucd-section-' . ($key+1) . '">';
        }

        if ($pzucd_query->have_posts())
        {
          $this->output .= '{{nav-top-inside}}';
        //  var_dump($pzucd_query->found_posts);
          $i = 1;
          while ($pzucd_query->have_posts())
          {
            $pzucd_query->the_post();
            self::build_cell($pzucd_query->post, $celldef,$section_info['section-cell-layout'],$section_info[ 'section-cell-settings' ]);

      //      self::build_cell($pzucd_query->post, $celldefinition,$celldef,$section_info['section-cell-layout']);

            self::set_nav_link();

// This needs to work out when to show! Maybe add it as a cell row???? Or at least an option??
            // if show comments {
//          $this->output = apply_filters('pzucd_comments',$this->output);

            // Leave the while loop if up to the post count
            if ($section_info['section-cells-per-view'] != 0 && ++$i > $section_info['section-cells-per-view']){break;}
            
          } // End WP loop - tho we might use it some more
          $this->output .= '{{nav-bottom-inside}}';
        }
        $this->output .= '</div><!-- end pzucd-section-' . ($key+1) . ' -->';
        $this->output .= '{{nav-top-outside}}';
        $this->output .= '{{nav-left-outside}}';

        // Add the section nav
        if ($section_info[ 'section-navigation' ] != 'none')
        {
          $this->output = str_replace('{{nav-' . $section_info[ 'section-nav-pos' ] . '-' . $section_info[ 'section-nav-loc' ] . '}}', self::add_nav(), $this->output);
        }
        // if we are out ofthe loop then we've run out of posts, so break out otherwise it just loops back tothe firstpost. go figure!
        if (!in_the_loop()){break;}
      }
    } 
    /*********************************************************************
    **********************************************************************
    // End sections
    *********************************************************************
    **********************************************************************/
    
    // Close up the outer wrapper
    // use a method so we can swap it out in the future with different blueprint headers
    self::blueprint_footer();

    // Process any left over {{}} variables
    self::add_pager();
    $this->output = self::strip_unused_tags($this->output);
    // Reset to original query status.  Will this be conditional?!
    $wp_query = $original_query;
    wp_reset_postdata();

  }


  function build_cell_definition($celldef, $section_cell_settings,$cell_layout)
  {
  
//    pzdebug((array)$this);
 //   $cell_layout = json_decode($section_cell_settings[ '_pzucd_layout-cell-preview' ][ 0 ], true);
//    pzdebug($section_cell_settings);
    $meta1_confg = unserialize($section_cell_settings[ '_pzucd_cell-settings-meta1-config' ][ 0 ]);
    $meta2_confg = unserialize($section_cell_settings[ '_pzucd_cell-settings-meta2-config' ][ 0 ]);
    $meta3_confg = unserialize($section_cell_settings[ '_pzucd_cell-settings-meta3-config' ][ 0 ]);
    $cell_meta1  = (!empty($meta1_confg) ? '{{' . implode('}}, {{', $meta1_confg) . '}}' : null);
    $cell_meta2  = (!empty($meta2_confg) ? '{{' . implode('}}, {{', $meta2_confg) . '}}' : null);
    $cell_meta3  = (!empty($meta3_confg) ? '{{' . implode('}}, {{', $meta3_confg) . '}}' : null);
    // build up the blueprint for the cell, ordering from
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


    // load up the css
//    $upload_dir = wp_upload_dir();
//    $filename   = trailingslashit($upload_dir[ 'baseurl' ]) . '/cache/pizazzwp/ucd/pzucd-cell-layout-' . $cellid . '.css';
//    if (!empty($cellid)) {
//      wp_enqueue_style('cells-css'.$cellid,$filename);
//    }
    return $cell_definition;
  }



  function build_cell($post_info, $celldef,$cellid,$section_cell_settings)
  {
    $cell_info = pzucd_flatten_wpinfo($this->section_info[ 'section-cell-settings' ]);


    // ALL THIS STYLING CRAP WILL BE MOVED TO A SEPARATE ROUTINE THAT CREATES A CACHED CSS
    //pzdebug($cell_info);

    $cell_width     = 100 / $this->section_info[ 'section-cells-across' ] - $this->section_info[ 'section-cells-vert-margin' ];
    $cell_min_width = $this->section_info[ 'section-min-cell-width' ];
    // this may need to be in its own method
    $cell_height = ($cell_info[ '_pzucd_layout-cell-height-type' ] == 'fixed') ? 'height:' . $cell_info[ '_pzucd_layout-cell-height' ] . 'px;' : null;

    // Open cell
    $this_cell = '<div class="pzucd-cell pzucd-{{classname}}" style="position:relative;width:' . $cell_width . '%;margin:' . ($this->section_info[ 'section-cells-vert-margin' ] / 2) . '%;min-width:' . $cell_min_width . 'px;' . $cell_height . '">';

      $position = 'static';
      $params   = array('width' => $cell_info['_pzucd_cell-settings-image-max-width'],'height' => $cell_info['_pzucd_cell-settings-image-max-height']);

      // Returns false on failure.
      // ADD CHECK THERE IS AN IMAGE HERE
      
      // ang on... this isn't always whe thum. :/ need tofind thumb for different conent types :S
      $thumb_src = '';
      if (has_post_thumbnail($post_info->ID)) {
    //  var_dump($post_info->ID ,get_post_thumbnail_id($post_info->ID ));
        $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_info->ID ),'full');
        $thumb_src = $thumbnail[0];
      } elseif ($post_info->post_type=='attachment'){
        $thumb_src = $post_info->guid;
      }
      $post_image = bfi_thumb($thumb_src, $params);
//var_dump($post_info->ID,$thumb_src,$post_image);
// need to find out when this is required!!
//      $post_image = ($post_image ? $post_image : $post_info->guid);


    if ($cell_info[ '_pzucd_layout-background-image' ] == 'fill')
    {
      $this_cell .= '<div class="pzucd-bg-image"><img class="entry-image" src="' . $post_image . '"></div>';
      $position = 'absolute';
    }

    $layout                     = json_decode($cell_info[ '_pzucd_layout-cell-preview' ], true);

    // Get all the content data
    $the_title = get_the_title();
    switch ($cell_info['_pzucd_cell-settings-title-prefix']) {
      case 'none':
      break;
      case 'bullet':
        $the_title = '&bull; '.$the_title;
      break;
      case 'thumb':
        $the_title = '<img src="'.bfi_thumb($thumb_src,array('width'>32,'height'=>32)).'"/>'.$the_title;
      break;
    }
    $the_inputs[ 'title' ]      = apply_filters('the_title', $the_title);
    $the_inputs[ 'excerpt' ]    = apply_filters('the_excerpt', get_the_excerpt());
    $the_inputs[ 'content' ]    = apply_filters('the_content', get_the_content());
    // This needs to be a url coz that's how it's shown
    $the_inputs[ 'image' ]      = $post_image;
    $the_inputs[ 'date' ]       = apply_filters('the_date', get_the_date());
    $the_inputs[ 'categories' ] = apply_filters('the_category', 'Categories: ' . trim(get_the_category_list(', '), ', '));
    $the_inputs[ 'tags' ]       = apply_filters('the_tags', 'Tags: ' . trim(get_the_tag_list(', '), ', '));
    $the_inputs[ 'author' ]     = apply_filters('the_author', get_the_author());
//var_dump($layout,$the_inputs);
    ob_start();
    comments_number();
    $comments_count = ob_get_contents();
    ob_end_clean();
    $the_inputs[ 'commentcount' ] = apply_filters('the_', $comments_count);
    foreach($the_inputs as $key => $value) {
      if (empty($value)) {
        $layout[$key]['show']=false;
      }
    }
    // Build this cell's layout
    $cell_definition = self::build_cell_definition($celldef, $section_cell_settings,$layout);
 

    // Create the content section
    $components_open  = '<div class="pzucd-components" style="position:' . $position . ';">';
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
    $this->output = str_replace('{{classname}}',$cellid,$this->output);
    //'.$postid.'-'.$pzucd_cells['_pzucd_layout-short-name']

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
//    var_dump();
    if ($this->blueprint['blueprint-pager']=='wordpress'){
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
    } elseif ($this->blueprint['blueprint-pager']=='wppagenavi') {
      if(function_exists('wp_pagenavi')) { 
       $pager = '<div class="pzucd-pager">';
        ob_start();
        wp_pagenavi(); }
        $pager .= ob_get_contents();
        ob_end_clean();
      $pager .= '</div>';
        
    } elseif ($this->blueprint['blueprint-pager']=='hover') {
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


  function strip_unused_tags($strip_from)
  {
  return preg_replace('/{{([\w|\-]*)}}/s', '', $strip_from);
  }

  function build_components($components_open, $the_inputs, $layout, $components_close, $cell_info, $celldef)
  {
    $return_str = $components_open;
    if ($cell_info[ '_pzucd_layout-background-image' ] == 'align' && !empty($the_inputs['image']))
    {
      $return_str .= '<div class="pzucd-bg-image entry-content"><img src="' . $the_inputs[ 'image' ] . '" /></div>';
    }


    foreach ($the_inputs as $key => $the_input)
    {
      $celldef = str_replace('{{' . $key . '}}', $the_input, $celldef);
    }
    $return_str .= $celldef . $components_close;
    return $return_str;

  }

  function modify_default_query($q) {
   if (!is_admin()) {
     $q->set( 'posts_per_page', '10' );
   }
pzdebug((array)$q);
   return $q;

  }
} //EOC