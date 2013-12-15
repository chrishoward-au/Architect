<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chrishoward
 * Date: 13/08/13
 * Time: 8:32 PM
 * To change this template use File | Settings | File Templates.
 */

// require(PZUCD_PLUGIN_PATH .'/frontend/class_pzucdDisplay.php');
//require(PZUCD_PLUGIN_PATH .'/includes/class_pzucdQuery.php');
require PZUCD_PLUGIN_PATH . '/frontend/ucdGallery.php';
require PZUCD_PLUGIN_PATH . '/frontend/ucdCellDefinitions.php';
require_once(PZUCD_PLUGIN_PATH .'external/bfi_thumb/BFI_Thumb.php');

//add_shortcode('ucdgallery', 'pzucd_gallery_shortcode');
//
//// Need to work out how to replace wp gallery with ours
////add_filter('post_gallery', 'pzucd_gallery');
//
//function pzucd_gallery_shortcode($atts, $content = null, $tag)
//{
//
//
//  $pzucd_the_template = pzucd_get_the_template($atts[ 'template' ]);
//  $overrides          = $atts[ 'ids' ];
//  $output             = pzucd_render($pzucd_the_template, $overrides);
//
//  return $output;
//}
//
//// Overrides WP Gallery
//function pzucd_gallery()
//{
//  global $post;
//  if (has_shortcode($post->post_content, 'gallery'))
//  {
//    return ' ';
//  }
//}

function pzucd_get_the_template($template)
{
  wp_enqueue_script('jquery-isotope');

  // meed to return a structure for the cells, the content source, the navgation info


  global $wp_query;
  $original_query = $wp_query;
  $template_info = new WP_Query('post_type=ucd-templates&meta_key=_pzucd_template-short-name&meta_value='.$template);
  if (!isset($template_info->posts[0])) { echo '<p class="pzucd-oops">Template '.$template.' not found</p>';return null;}
  $the_template_meta = get_post_meta($template_info->posts[0]->ID, null, true);
//  $wp_query = $original_query;
 // wp_reset_postdata();

  // VERY risky- fine on single pages, but will cause horror on multi post pages
 // rewind_posts();

  foreach ($the_template_meta as $key => $value)
  {
    $pzucd_template_field_set[ $key ] = $value[ 0 ];
  }
  $pzucd_template = array(
    'template-short-name' => $pzucd_template_field_set[ '_pzucd_template-short-name' ],
    'template-criteria'   => $pzucd_template_field_set [ '_pzucd_template-criteria' ],
    'template-pager'      => $pzucd_template_field_set[ '_pzucd_template-pager' ],
  );
  for ($i = 0; $i < 3; $i++)
  {
    $pzucd_template[ 'section' ][ $i ] = !empty($pzucd_template_field_set[ '_pzucd_' . $i . '-template-section-enable' ]) ?
            array(
              'section-enable'             => !empty($pzucd_template_field_set[ '_pzucd_' . $i . '-template-section-enable' ]),
              'section-cells-per-view'     => $pzucd_template_field_set[ '_pzucd_' . $i . '-template-cells-per-view' ],
              'section-cells-across'       => $pzucd_template_field_set[ '_pzucd_' . $i . '-template-cells-across' ],
              'section-min-cell-width'     => $pzucd_template_field_set[ '_pzucd_' . $i . '-template-min-cell-width' ],
              'section-cells-vert-margin'  => $pzucd_template_field_set[ '_pzucd_' . $i . '-template-cells-vert-margin' ],
              'section-cells-horiz-margin' => $pzucd_template_field_set[ '_pzucd_' . $i . '-template-cells-horiz-margin' ],
              'section-cell-layout'        => $pzucd_template_field_set[ '_pzucd_' . $i . '-template-section-cell-layout' ],
              'section-layout-mode'        => $pzucd_template_field_set[ '_pzucd_' . $i . '-template-layout-mode' ],
              'section-navigation'         => $pzucd_template_field_set[ '_pzucd_' . $i . '-template-section-navigation' ],
              'section-nav-pos'            => $pzucd_template_field_set[ '_pzucd_' . $i . '-template-section-nav-pos' ],
              'section-nav-loc'            => $pzucd_template_field_set[ '_pzucd_' . $i . '-template-section-nav-loc' ],
              'section-cell-settings'      => get_post_meta($pzucd_template_field_set[ '_pzucd_' . $i . '-template-section-cell-layout' ]),

            ) : null;
  }

  return $pzucd_template;
}

function pzucd_get_cell_design($pzucd_cell_layout_id)
{

  $pzucd_cell_design = get_post_meta($pzucd_cell_layout_id, '_pzucd_layout-cell-preview', true);

  return $pzucd_cell_design;

}


function pzucd_render($pzucd_template, $overrides, $caller)
{
  if (empty($pzucd_template)) {return null;}

 // pzdebug($pzucd_template);
  $pzucd_out = new $caller($pzucd_template);
 // pzdebug((array)$pzucd_out);
  // Get the criteria
  $the_criteria = get_post_meta($pzucd_template[ 'template-criteria' ], null, true);

//  pzdebug($pzucd_template[ 'template-criteria' ]);
  if ($pzucd_template[ 'template-criteria' ]!='default') {
    $pzucd_out->get_source($the_criteria, $overrides);

    $pzucd_out->build_query();

    if ($pzucd_out->query_vars) {
      $pzucd_query = new WP_Query($pzucd_out->query_vars);
    }
  } else {
    global $wp_query;
    $original_query = $wp_query;
    $pzucd_query = $wp_query;
  }

 // pzdebug($pzucd_query->found_posts);
 // pzdebug(is_main_query());
//  pzdebug((array) $pzucd_query);

  // is filters a better way to do this? Altho how??
  //$pzucd_out->output = apply_filters('pzucd_template_header',template_header());

  $pzucd_out->template_header();

  foreach ($pzucd_template[ 'section' ] as $key => $pzucd_section_info)
  {
//    $pzucd_out->section_info = $pzucd_section_info;
    if ($pzucd_template[ 'section' ][ $key ][ 'section-enable' ])
    {
      $pzucd_out->output .= '%nav-top-outside%';
      $pzucd_out->output .= '%nav-left-outside%';
      if ($pzucd_section_info['section-layout-mode']=='basic') {
        $pzucd_out->output .= '<div class="pzucd-section pzucd-section-' . $key . '">';
      }else {
//        $pzucd_out->output .= '<div class="js-isotope pzucd-section pzucd-section-' . $key . '" data-isotope-options=\'{ "layoutMode": "'.$pzucd_section_info['section-layout-mode'].'","itemSelector": ".pzucd-cell" }\'>';
        $pzucd_out->output .= '<div class="pzucd-section pzucd-section-' . $key . '">';
      }

      if ($pzucd_query->have_posts())
       $pzucd_out->output .= '%nav-top-inside%';

      {
        while ($pzucd_query->have_posts())
        {
          $pzucd_query->the_post();

          $pzucd_out->build_cell($pzucd_query->post);

          $pzucd_out->set_nav_link();

// This needs to work out when to show! Maybe add it as a cell row???? Or at least an option??
          // if show comments {
//          $pzucd_out->output = apply_filters('pzucd_comments',$pzucd_out->output);

        }
      $pzucd_out->output .= '%nav-bottom-inside%';
      }
      $pzucd_out->output .= '</div><!-- end pzucd-section-' . $key . ' -->';
      $pzucd_out->output .= '%nav-top-outside%';
      $pzucd_out->output .= '%nav-left-outside%';
      if ($pzucd_section_info['section-navigation'] != 'none') {
        $pzucd_out->output = str_replace('%nav-'.$pzucd_section_info['section-nav-pos'].'-'.$pzucd_section_info['section-nav-loc'].'%',$pzucd_out->add_nav(),$pzucd_out->output);
      }
    }
  }
  $pzucd_out->template_footer();

  $pzucd_out->add_pager();

  // This isn't necessary yet
  if (!empty($original_query)){$wp_query=$original_query;}

  // Rememebr to strip out unused tags

  $pzucd_out->strip_unused_tags();
  return $pzucd_out->output;
}

function pzucd_build_components($components_open, $the_inputs, $layout, $components_close, $cell_info,$celldef)
{
  $return_str = $components_open;
  if ($cell_info[ '_pzucd_layout-background-image' ] == 'align')
  {
    $return_str .= '<div class="pzucd_bg_image">' . $the_inputs[ 'image' ] . '</div>';
  }


  // Now this is where we need to use our cells defs!!!
  // how do we define what goes into the innards tho? maybe need an array option for each innard.
  // time for bed!
  var_dump($layout);
  foreach ($layout as $key => $value)
  {
    if ($value[ 'show' ])
    {
      switch ($key)
      {
        case 'title' :
          $return_str .= '<h3 class="entry-title" style="'.$cell_info['_pzucd_layout-format-entry-title'].'">' . $the_inputs[ 'title' ] . '</h3>';
          break;
        case 'excerpt' :
          $return_str .= '<div class="entry-excerpt" style="'.$cell_info['_pzucd_layout-format-entry-content'].'">' . esc_html($the_inputs[ 'excerpt' ]) . '</div>';
          break;
        case 'content' :
          $return_str .= '<div class="entry-content" style="'.$cell_info['_pzucd_layout-format-entry-content'].'">' . $the_inputs[ 'content' ] . '</div>';
          break;
        case 'image' :
          $return_str .= '<div class="pzucd_image">' . $the_inputs[ 'image' ] . '</div>';
          break;

      }
    }
  }
  $return_str .= $components_close;

  return $return_str;

}


class pzucd_Display
{
  public $output = '';
  public $source_data = '';
  public $query_vars = '';
  public $cell_info = '';
  public $template = '';
  public $nav_links = array();
  public $celldefs = array();

  function __construct($pzucd_template)
  {
    $this->template = $pzucd_template;
    $this->celldefs[ 'singlepost' ] = array(
      array('wrapper' => '<article id="post-%postid%" class="pzucd-singlepost post-%postid% post type-%posttype% status-%poststatus% format-%postformat% hentry %category-categories% %tag-tags%">%wrapperinnards%</article>'),
      array('header' => '<header class="entry-header">%headerinnards%</header><!-- .entry-header -->'),
      array('meta1' => '<div class="entry-meta entry-meta-1">%meta1innards%</div><!-- .entry-meta 1 -->'),
      array('meta2' => '<div class="entry-meta entry-meta-2">%meta2innards%</div><!-- .entry-meta 2 -->'),
      array('meta3' => '<div class="entry-meta entry-meta-3">%meta3innards%</div><!-- .entry-meta 3 -->'),
      array('datetime' => '<span class="date"><a href="http://localhost/wp-mba.dev/hello-world/" title="Permalink to Hello world!" rel="bookmark"><time class="entry-date" datetime="2013-10-08T15:04:20+00:00">October 8, 2013</time></a></span>'),
      array('categories' => '<span class="categories-links">%categories%</span>'),
      array('categorylinks' => '<a href="%categorylink%" title="View all posts in %categorynam%" rel="category tag">%categoryname%</a>'),
      array('tags' => '<span class="tags-links">%tags%</span>'),
      array('taglinks' => '<a href="%taglink%" rel="tag">%tag%</a>'),
      array('author' => '<span class="author vcard"><a class="url fn n" href="%authorlink%" title="View all posts by %authorname%" rel="author">%authorname%</a></span>'),
      array('edit' => '<span class="edit-link"><a class="post-edit-link" href="http://localhost/wp-mba.dev/wp-admin/post.php?post=%postid%&amp;action=edit">Edit</a></span>'),
      array('featuredimage' => '<div class="entry-thumbnail"><img width="%width%" height="%height%" src="%imgsrc%" class="attachment-post-thumbnail wp-post-image" alt="%alttext%"></div>'),
      array('title' => '<h1 class="entry-title">%title%</h1>'),
      array('body' => ' <div class="entry-content">%content%</div><!-- .entry-content -->'),
      array('custom1' => '<div class="entry-customfield entry-customfield-1">%custom1innards%</div><!-- .entry-custom 1 -->'),
      array('custom2' => '<div class="entry-customfield entry-customfield-2">custom2innards%</div><!-- .entry-custom 2 -->'),
      array('custom3' => '<div class="entry-customfield entry-customfield-3">%custom3innards%</div><!-- .entry-custom 3 -->'),
      array('footer' => '<footer class="entry-meta">%footerinnards%</footer><!-- .entry-meta -->'),
    );


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


  function render(){
  // Should we do it this way?
    // Or should it be filters?
    // And what would happen with multiple ? Would they all get mixed together?
    do_action('template-header');
    do_action('template-body');
    do_action('template-pooter');

    // Or should we doo it an OOP way? Which we are already semi doing.

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
    $components_open         = '<div class="pzucd-components" style="'.$cell_info['_pzucd_layout-format-components-group'].';position:' . $position . ';' . $cell_info[ '_pzucd_layout-sections-position' ] . ':'.$cell_info[ '_pzucd_layout-nudge-section-y' ].'%;width:' . $cell_info[ '_pzucd_layout-sections-widths' ] . '%;">';
    $components_close        = '</div><!-- End components -->';
    $components              = pzucd_build_components($components_open, $the_inputs, $layout, $components_close, $cell_info, $this->celldefs['singlepost']);
    $this->output .= $components . '</div><!-- end cell -->';
  }

  function add_pager() {
    $this->output .=  get_next_posts_link( 'Older Entries', 999 );
    $this->output .=  get_previous_posts_link( 'Newer Entries' );
    $next_post = get_next_post();
    $this->output .= '<a href="'.get_permalink( $next_post->ID ).'">'.$next_post->post_title.'</a>';


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

function pzucd_flatten_wpinfo($array_in)
{
  $array_out = array();
  foreach ($array_in as $key => $value)
  {
    if (is_array($value))
    {
      $array_out[ $key ] = $value;
    }
    $array_out[ $key ] = $value[ 0 ];
  }

  return $array_out;
}

add_shortcode('pzucd','pzucd_shortcode');
function pzucd_shortcode($atts,$content=null,$tag) {
  return pzucd_render(pzucd_get_the_template($atts[ 0]), (!empty($atts[ 'ids' ]) ? $atts[ 'ids' ] : null), 'pzucd_Display');
;
}

/* Template tag */
/* Overrides is a list of ids */
function pzucd($template=null,$overrides=null){
  return pzucd_render(pzucd_get_the_template($template), $overrides, 'pzucd_Display');
}

// Capture and append the comments display
add_filter('pzucd_comments','pzucd_get_comments');
function pzucd_get_comments($pzucd_content) {
//  pzdebug(get_the_id());
  ob_start();
  comments_template(null,null);
  $pzucd_comments = ob_get_contents();
  ob_end_flush();
  return $pzucd_content.$pzucd_comments;
}

































/*

// Template tag
function pzucd_display()
{
  $pzucd_criteria = null;
  $pzucd_sections = null;
  $pzucd_title    = null;
  $args           = func_get_args();
  echo '<h1>HELLOOOO!</H1>';
  echo 'You placed a shortcode with the args:';
  pzdebug($args);
  // Load global variables. PAGINATION RELATED
  global $paged;
  global $wp_query;
  global $authordata;
  global $post;
  // get the settings first then pass them
  // will ahve to consider defaults but for now, return if not all three.
  if (empty($pzucd_sections))
  {
    return false;
  }

  // It's ok for criteria tobe null as then we just use the default
  // There will be other scenarios where we use the default - see E+.
  // Only allowed one query per set - this is so pagination doesn't get screwed and we get consistency between sections
  if (!isset($pzucd_criteria))
  {
    $pzucd_query = $wp_query;
  }
  else
  {
    // Do the query
    // Get criteria options
    $query_options          = array(
      'post_type'  => 'ucd-criterias',
      'meta_key'   => 'pzucd_criteria-name',
      'meta_value' => $pzucd_criteria
    );
    $criteria_array         = get_posts($query_options);
    $pzucd_criteria_options = get_post_custom($criteria_array[ 0 ]->ID);
    $pzucd_query            = new pzucdQuery($pzucd_criteria_options);
    $pzucd_criteria_options = pz_squish($pzucd_criteria_options);
  }

  // TEMPORARY FOR TESTING
  $pzucd_query = $wp_query;
//	pzdebug((array) $pzucd_query);


  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.
  // Need to replace as much as possible with actions and filters. Then do the class method calls there.


  // Navs go in this action or after one
  do_action('pzucd_before_section');

  // Loop for each section (max 3!)
  var_dump(count($pzucd_sections));
  foreach ($pzucd_sections as $pzucd_section)
  {
    // Need to setup a routine for the defaults... probably pulled from the bit that makes the meta fields or save the lot :/
    // Get cell options
//pzdebug($pzucd_section);
    // replace this with function call
    $query_options      = array(
      'post_type'  => 'ucd-layouts',
      'meta_key'   => 'pzucd_layout-set-name',
      'meta_value' => $pzucd_section[ 'cells' ]
    );
    $cell_array         = get_posts($query_options);
    $pzucd_cell_options = get_post_custom($cell_array[ 0 ]->ID);

    // Get template options
    $query_options          = array(
      'post_type'  => 'ucd-templates',
      'meta_key'   => 'pzucd_template-set-name',
      'meta_value' => $pzucd_section[ 'template' ]
    );
    $template_array         = get_posts($query_options);
    $pzucd_template_options = get_post_custom($template_array[ 0 ]->ID);

    // Squish arrays as values are currently in $x['name'][0]. This makes them $x['name']
    $pzucd_cell_options     = pz_squish($pzucd_cell_options);
    $pzucd_template_options = pz_squish($pzucd_template_options);

//		pzdebug($pzucd_cell_options);
//		pzdebug($pzucd_criteria_options);
//		pzdebug($pzucd_template_options);

    // Should each be a separate class?? i.e. layout,criteria, template? or child classes. hmmm
    // Not child coz childs are for adding or changing existing functionailty
    // Separates may be overkill at this stage since there'd only be one method per class
    /// So we'll stick to the single class


    // Pass a generic cells template - don't know why yet!
    $pzucd = new ucdDisplay($pzucd_cell_options, $pzucd_template_options);

//		// Get the content
//		// should this return a query
//		$pzucd_query = $pzucd->select($pzucd_section['criteria_options']);

    // Generate the cell template
    $pzucd_cells_layout = $pzucd->cell_layout();

    // Generate the full layout
    $pzucd_template_layout = $pzucd->layout_template();

    $pzucd->layout_header($pzucd_template_layout); // Outer wrapper and nav top and left
    var_dump($pzucd_query->found_posts);
    if ($pzucd_query->have_posts())
    {
      $row_break = false;
      $i         = 0;
      // These sections are rows - but may not be necessary - although may be useful for advanced formatting
      $pzucd->section_header($pzucd_template_layout);
      while ($pzucd_query->have_posts() && !$row_break)
        // Do we need row breaks? Can % width deal with them automatically? And then do we need sections?
        // Can "dumb" thml with smart css format it?
        // Will need {clear:left} at firs titem in  each row, will need smart css to do that.
      {
        $pzucd_query->the_post();
        var_dump(get_the_id());
        // $i will be used to identify the cell so $i % cells_per_row will enabled us to determine the row beginning
        //	echo $pzucd->display_cell($pzucd_cells_layout,$i++);
      } //Endwhile
      $pzucd->section_footer($pzucd_template_layout); // Outer wrapper close and nav right and bottom
    } //endif
    // Should this be in the loop?
    // And finally, display it
  } //end foreach
  var_dump($pzucd_sections);
  do_action('pzucd_after_sections');
  $pzucd->layout_footer($pzucd_template_layout);
  wp_reset_postdata();
  rewind_posts();

} // EOF

function pzucd_shortcode($atts, $title = null)
{
//	pzdebug($atts);
//	var_dump($title);
  $pzucd_sections = array();
// var_dump($atts);
  if (isset($atts[ 'section1' ]))
  {
    list($pzucd_sections[ 'section1' ][ 'cells' ], $pzucd_sections[ 'section1' ][ 'template' ]) = explode(',', $atts[ 'section1' ]);
  }
  if (isset($atts[ 'section2' ]))
  {
    list($pzucd_sections[ 'section2' ][ 'cells' ], $pzucd_sections[ 'section2' ][ 'template' ]) = explode(',', $atts[ 'section2' ]);
  }
  if (isset($atts[ 'section3' ]))
  {
    list($pzucd_sections[ 'section3' ][ 'cells' ], $pzucd_sections[ 'section3' ][ 'template' ]) = explode(',', $atts[ 'section3' ]);
  }

//	pzdebug($pzucd_sections);

  // Id there goingto be a problem putting thisin a post???? Doe sit cause an infinite loop where it just keeps calling itself? Do we need to ensure if it's a post that the current post isn't displayed?
  $return_html = pzucd_display($atts, $pzucd_sections, $title);

  return $return_html;
}

add_shortcode('pzucd', 'pzucd_shortcode');
*/

