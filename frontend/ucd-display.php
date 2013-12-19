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

// should this be handled in defs??
//require PZUCD_PLUGIN_PATH . '/frontend/ucdGallery.php';

require_once PZUCD_PLUGIN_PATH. 'frontend/class_pzucd_Display.php';
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
    'template-type'      => $pzucd_template_field_set[ '_pzucd_template-type' ],
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
  $pzucd_template_arr =  pzucd_get_the_template($atts[ 0]);
  var_dump($pzucd_template_arr);
//  $pzucd = new pzucd_Display();
    return pzucd_render($pzucd_template_arr, (!empty($atts[ 'ids' ]) ? $atts[ 'ids' ] : null), 'pzucd_Display');
;
}


/* Template tag */
/* Overrides is a list of ids */
function pzucd($pzucd_template=null,$pzucd_overrides=null){
  if (empty($pzucd_template)) {return 'You need to set a template';}
  $pzucd_template_arr =  pzucd_get_the_template($pzucd_template);
  pzdebug($pzucd_template_arr);

  // THis is probably where we should preserve the wp_query
  global $wp_query;
  $original_query = $wp_query;

  $pzucd_stuff = new pzucd_Display();
  $pzucd_stuff->render($pzucd_template_arr,$pzucd_overrides);

  // Reset to original query status.  Will this be conditional?!
  $wp_query = $original_query;

  return $pzucd_stuff->output;

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

