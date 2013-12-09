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

  // meed to return a structure for the cells, the content source, the navgation info

  $the_post = get_post_meta(1893, null, true);

  foreach ($the_post as $key => $value)
  {
    $pzucd_template_field_set[ $key ] = $value[ 0 ];
  }
  $pzucd_template = array(
    'template-short-name' => $pzucd_template_field_set[ '_pzucd_template-short-name' ],
    'template-criteria'   => $pzucd_template_field_set [ '_pzucd_template-criteria' ],
    'template-pager'      => $pzucd_template_field_set[ '_pzucd_template-pager' ],
    'template-controls'   => $pzucd_template_field_set[ '_pzucd_template-controls' ],
    'template-nav-pos'    => $pzucd_template_field_set[ '_pzucd_template-nav-pos' ],
    'template-nav-loc'    => $pzucd_template_field_set[ '_pzucd_template-nav-loc' ]
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
  $pzucd_out = new $caller($pzucd_template);
  // Get the criteria
  $the_criteria = get_post_meta($pzucd_template[ 'template-criteria' ], null, true);

  $pzucd_out->get_source($the_criteria, $overrides);

  $pzucd_out->build_query();
  $pzucd_query = new WP_Query($pzucd_out->query_vars);

//  pzdebug((array) $pzucd_query);
  $pzucd_out->template_header();
  foreach ($pzucd_template[ 'section' ] as $key => $pzucd_section_info)
  {
    $pzucd_out->section_info = $pzucd_section_info;
    if ($pzucd_template[ 'section' ][ $key ][ 'section-enable' ])
    {
      $pzucd_out->output .= '<div class="pzucd-section pzucd-section-' . $key . '">';
      if ($pzucd_query->have_posts())
      {
        while ($pzucd_query->have_posts())
        {
          $pzucd_query->the_post();
          $pzucd_out->build_cell($pzucd_query->post);
          $pzucd_out->set_nav_link();
        }
      }
      $pzucd_out->output .= '</div><!-- end pzucd-section-' . $key . ' -->';
      $pzucd_out->add_nav();
    }
  }
  $pzucd_out->template_footer();
  $pzucd_out->add_pager();

  return $pzucd_out->output;
}

function pzucd_build_components($components_open, $the_inputs, $layout, $components_close, $cell_info)
{
  $return_str = $components_open;
  if ($cell_info[ '_pzucd_layout-background-image' ] == 'align')
  {
    $return_str .= '<div class="pzucd_bg_image">' . $the_inputs[ 'image' ] . '</div>';
  }
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
          $return_str .= '<div class="entry-excerpt"style="'.$cell_info['_pzucd_layout-format-entry-content'].'">' . esc_html($the_inputs[ 'excerpt' ]) . '</div>';
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

  function __construct($pzucd_template)
  {
    $this->template = $pzucd_template;
  }

  function template_header()
  {
    $this->output .= '<div id="pzucd=container-'.$this->template['template-short-name'].'" class="pzucd-container">';
//    switch ($this->tempate['template-nav-pos']) {
//      case 'top':
//      case 'left':
//        break;
//      case 'bottom':
//      case 'right' :
//        break;
//    }
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

  }


  function build_cell($post_info)
  {
    // this is the one that should be inthe specific class!
    echo "Ma nama na! Oops! You need a cell builder";

  }

  function add_pager() { }

  function add_nav() { }

  function set_nav_link() { }

  function get_source($criteria, $overrides = null)
  {
    switch ($criteria[ '_pzucd_criteria-content-source' ][ 0 ])
    {
      case 'images' :
        $this->source_data = $criteria[ '_pzucd_criteria-specific-images' ];
        break;
    }

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