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

  function __construct()
  {
  }

  function template_header()
  {
    $this->output .= '<div id="pzucd=container-'.$this->template['template-short-name'].'" class="pzucd-container">';
    if ($this->template['template-pager']=='hover'){
      $this->output .= '%pager%';
    }
  }

  function template_footer()
  {
    if ($this->template['template-pager']=='wordpress' || $this->template['template-pager']== 'wppagenavi'){
      $this->output .= '%pager%';
    }
    $this->output .= '</div><!-- end pzucd_container-->';
  }

  function build_query()
  {
    if ($this->template['template-criteria']=='default') {
      //don't change nuttin!
      // Do we need to check page type? Single etc?
      $this->query_vars = '';
    }  else {
      //build the new query
      //Lot of work!
      //
      $this->query_vars='post_type=post';
    }

  }


  function xrender(){
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

    // THIS HAS TO ALL WORK NICELY WITH CONTENT IN CONTENT. I.E. NO RECURSIVE ISSUES.

    $this->template = $pzucd_template;


    // pzdebug($pzucd_template);
    // pzdebug((array)$pzucd_out);
    // Get the criteria
    $the_criteria = get_post_meta($pzucd_template[ 'template-criteria' ], null, true);

    if ($this->template[ 'template-criteria' ]!='default') {

      self::get_source($the_criteria, $overrides);

      // Determine the content type the post type so we canget the right cell def (this could be anything from posts, to images to NGG, to RSS

      $content_type = $the_criteria[ '_pzucd_criteria-content-source' ][ 0 ];

      self::build_query();
// no matter what happens, somewhere we've got to preserve the original query!
      if ($this->query_vars) {
        $pzucd_query = new WP_Query($this->query_vars);
      }
    } else {
      // Determine the content type the post type so we canget the right cell def
      //eeek. Gotta work out what page content type is!

      // What if WE have changed the query?? What is our real current query?

      global $wp_query;
      $original_query = $wp_query;
      $pzucd_query = $wp_query;

      // For defaults, determine the content type from the post type
      $content_type = (empty($pzucd_query->query_vars['post_type'])?'post':$pzucd_query->query_vars['post_type']);

    }


    pzdebug((array)$pzucd_query);
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

    foreach ($this->template[ 'section' ] as $key => $pzucd_section_info)
    {
      $this->section_info = $pzucd_section_info;
      if ($pzucd_template[ 'section' ][ $key ][ 'section-enable' ])
      {

        $this->output .= '%nav-top-outside%';
        $this->output .= '%nav-left-outside%';
        if ($pzucd_section_info['section-layout-mode']=='basic') {
          $this->output .= '<div class="pzucd-section pzucd-section-' . $key . '">';
        }else {
//        $this->output .= '<div class="js-isotope pzucd-section pzucd-section-' . $key . '" data-isotope-options=\'{ "layoutMode": "'.$pzucd_section_info['section-layout-mode'].'","itemSelector": ".pzucd-cell" }\'>';
          $this->output .= '<div class="pzucd-section pzucd-section-' . $key . '">';
        }

        if ($pzucd_query->have_posts())
          $this->output .= '%nav-top-inside%';

        {
          while ($pzucd_query->have_posts())
          {
            $pzucd_query->the_post();

            self::build_cell($pzucd_query->post,$celldef);

            self::set_nav_link();

// This needs to work out when to show! Maybe add it as a cell row???? Or at least an option??
            // if show comments {
//          $this->output = apply_filters('pzucd_comments',$this->output);

          }
          $this->output .= '%nav-bottom-inside%';
        }
        $this->output .= '</div><!-- end pzucd-section-' . $key . ' -->';
        $this->output .= '%nav-top-outside%';
        $this->output .= '%nav-left-outside%';

        // Add the section nav
        if ($pzucd_section_info['section-navigation'] != 'none') {
          $this->output = str_replace('%nav-'.$pzucd_section_info['section-nav-pos'].'-'.$pzucd_section_info['section-nav-loc'].'%',self::add_nav(),$this->output);
        }
      }
    }
    // Close up the outer wrapper
    // use a method so we can swap it out in the future with different template headers
    self::template_footer();

    // Process any left over %% variables
    self::add_pager();
    self::strip_unused_tags();

  }




  function build_cell($post_info)
  {
    //pzdebug($post_info);
    $cell_info = pzucd_flatten_wpinfo($this->section_info[ 'section-cell-settings' ]);


    // ALL THIS STYLING CRAP WILL BE MOVED TO A SEPARATE ROUTINE THAT CREATES A CACHED CSS
    //pzdebug($cell_info);

    $cell_width     = 100 / $this->section_info[ 'section-cells-across' ] - $this->section_info[ 'section-cells-vert-margin' ];
    $cell_min_width = $this->section_info[ 'section-min-cell-width' ];
    // this may need to be in its own method
    $cell_height = ($cell_info['_pzucd_layout-cell-height-type'] == 'fixed')?'height:'.$cell_info['_pzucd_layout-cell-height'].'px;':null;
    $this->output .= '<div class="pzucd-cell" style="position:relative;width:' . $cell_width . '%;margin:' . ($this->section_info[ 'section-cells-vert-margin' ] / 2) . '%;min-width:' . $cell_min_width . 'px;'.$cell_info['_pzucd_layout-format-cells'].$cell_height.'">';
    $position = 'static';
    $params = array( 'width' => 300 );
    // Returns false on failure.
    $post_image = bfi_thumb( $post_info->guid, $params );
    $post_image = ($post_image?$post_image:$post_info->guid);
    if ($cell_info[ '_pzucd_layout-background-image' ] == 'fill')
    {
      $this->output .= '<div class="pzucd_bg_image"><img class="entry-image" src="' . $post_image . '"></div>';
      $position = 'absolute';
    }
    $layout                  = json_decode($cell_info[ '_pzucd_layout-cell-preview' ], true);
    $the_inputs[ 'title' ]   = get_the_title();
    $the_inputs[ 'excerpt' ] = get_the_excerpt();
    $the_inputs[ 'content' ] = apply_filters('the_content', get_the_content());
    $the_inputs[ 'image' ]   = '<img class="entry-image" src="' . $post_image . '">';
    // this needs its ownmethod
    pzdebug($cell_info);
    // Need a better way to pullup the styling which will populate all fields.
    
    $components_open         = '<div class="pzucd-components" style="'.$cell_info['_pzucd_layout-format-components-group'].';position:' . $position . ';' . $cell_info[ '_pzucd_layout-sections-position' ] . ':'.$cell_info[ '_pzucd_layout-nudge-section-y' ].'%;width:' . $cell_info[ '_pzucd_layout-sections-widths' ] . '%;">';
    $components_close        = '</div><!-- End components -->';
    $components              = pzucd_build_components($components_open, $the_inputs, $layout, $components_close, $cell_info, $this->celldefs['singlepost']);
    $this->output .= $components . '</div><!-- end cell -->';
  }


  function add_pager() {
    var_dump(get_the_id(),get_the_title());
    $pager = '<div class="pzucd-pager">';
    $pager .=  get_next_posts_link( 'Older Entries', 999 );
    $pager .=  get_previous_posts_link( 'Newer Entries' );
    $next_post = get_next_post();
    // var_dump($next_post);
    $prev_post = get_previous_post();
    //  var_dump($next_post);
    $pager .= '<a class="pzucd-pager-prev" href="'.get_permalink( $prev_post->ID ).'">&laquo; '.$prev_post->post_title.'</a>';
    $pager .= '<a class="pzucd-pager-next" href="'.get_permalink( $next_post->ID ).'">'.$next_post->post_title.' &raquo;</a>';
    $pager .= '</div>';
    //  var_dump(esc_html($pager));
    $this->output = str_replace('%pager%', $pager,$this->output);

  }



  function add_nav() {
    $navigation = '<ul class="pzucd-navigation">';
    foreach($this->nav_links as $key => $value) {
      $navigation .= '<li class="pzucd-nav-item"><a hef="'.$value['link'].'" class="pzucd-nav-item-link">'.$value['title'].'</a></li>';
    }
    $navigation .= '</ul>';
    return $navigation;
  }

  function set_nav_link() {

    $this->nav_links[] = array(
      'id' => get_the_id(),
      'title' => get_the_title(),
      'link' => get_permalink(),
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

  function strip_unused_tags() {
    $this->output = str_replace(array('%pager%','%nav-top-outside%','%nav-top-inside%','%nav-left-outside%','%nav-right-outside%','%nav-bottom-inside%','%nav-bottom-outside%'),'',$this->output);
  }
}
