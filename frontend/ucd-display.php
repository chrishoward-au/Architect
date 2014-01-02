<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chrishoward
 * Date: 13/08/13
 * Time: 8:32 PM
 * To change this blueprint use File | Settings | File Blueprints.
 */

// require(PZUCD_PLUGIN_PATH .'/frontend/class_pzucdDisplay.php');
//require(PZUCD_PLUGIN_PATH .'/includes/class_pzucdQuery.php');

// should this be handled in defs??
//require PZUCD_PLUGIN_PATH . '/frontend/ucdGallery.php';

require_once PZUCD_PLUGIN_PATH . 'frontend/class_pzucd_Display.php';
require PZUCD_PLUGIN_PATH . '/frontend/ucdCellDefinitions.php';
require_once(PZUCD_PLUGIN_PATH . 'external/bfi_thumb/BFI_Thumb.php');

//add_shortcode('ucdgallery', 'pzucd_gallery_shortcode');
//
//// Need to work out how to replace wp gallery with ours
////add_filter('post_gallery', 'pzucd_gallery');
//
//function pzucd_gallery_shortcode($atts, $content = null, $tag)
//{
//
//
//  $pzucd_the_blueprint = pzucd_get_the_blueprint($atts[ 'blueprint' ]);
//  $overrides          = $atts[ 'ids' ];
//  $output             = pzucd_render($pzucd_the_blueprint, $overrides);
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

function pzucd_get_the_blueprint($blueprint)
{
//  wp_enqueue_script('jquery-isotope');

  // meed to return a structure for the cells, the content source, the navgation info


  global $wp_query;
  $original_query = $wp_query;
  $blueprint_info  = new WP_Query('post_type=ucd-blueprints&meta_key=_pzucd_blueprint-short-name&meta_value=' . $blueprint);
  var_dump($blueprint_info);
  if (!isset($blueprint_info->posts[ 0 ]))
  {
    echo '<p class="pzucd-oops">Blueprint ' . $blueprint . ' not found</p>';

    return null;
  }
  $the_blueprint_meta = get_post_meta($blueprint_info->posts[ 0 ]->ID, null, true);
//  $wp_query = $original_query;
  // wp_reset_postdata();

  // VERY risky- fine on single pages, but will cause horror on multi post pages
  // rewind_posts();

  foreach ($the_blueprint_meta as $key => $value)
  {
    $pzucd_blueprint_field_set[ $key ] = $value[ 0 ];
  }
  $pzucd_blueprint = array(
           'blueprint-id' => $blueprint_info->posts[ 0 ]->ID,
          'blueprint-short-name' => (!empty($pzucd_blueprint_field_set[ '_pzucd_blueprint-short-name' ]) ? $pzucd_blueprint_field_set[ '_pzucd_blueprint-short-name' ] : null),
          'blueprint-criteria'   => (!empty($pzucd_blueprint_field_set [ '_pzucd_blueprint-criteria' ]) ? $pzucd_blueprint_field_set [ '_pzucd_blueprint-criteria' ] : 'default'),
          'blueprint-pager'      => (!empty($pzucd_blueprint_field_set[ '_pzucd_blueprint-pager' ]) ? $pzucd_blueprint_field_set[ '_pzucd_blueprint-pager' ] : null),
          'blueprint-posts-per-page'      => (!empty($pzucd_blueprint_field_set[ '_pzucd_blueprint-posts-per-page' ]) ? $pzucd_blueprint_field_set[ '_pzucd_blueprint-posts-per-page' ] : get_option('posts_per_page')),
          'blueprint-type'       => (!empty($pzucd_blueprint_field_set[ '_pzucd_blueprint-type' ]) ? $pzucd_blueprint_field_set[ '_pzucd_blueprint-type' ] : null),
  );
  for ($i = 0; $i < 3; $i++)
  {
    $pzucd_blueprint[ 'section' ][ $i ] = !empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-section-enable' ]) ?
            array(
                    'section-enable'             => !empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-section-enable' ]),
                    'section-cells-per-view'     => (!empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-cells-per-view' ]) ? $pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-cells-per-view' ] : null),
                    'section-cells-across'       => (!empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-cells-across' ]) ? $pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-cells-across' ] : null),
                    'section-min-cell-width'     => (!empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-min-cell-width' ]) ? $pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-min-cell-width' ] : null),
                    'section-cells-vert-margin'  => (!empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-cells-vert-margin' ]) ? $pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-cells-vert-margin' ] : null),
                    'section-cells-horiz-margin' => (!empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-cells-horiz-margin' ]) ? $pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-cells-horiz-margin' ] : null),
                    'section-cell-layout'        => (!empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-section-cell-layout' ]) ? $pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-section-cell-layout' ] : null),
                    'section-layout-mode'        => (!empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-layout-mode' ]) ? $pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-layout-mode' ] : null),
                    'section-navigation'         => (!empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-section-navigation' ]) ? $pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-section-navigation' ] : null),
                    'section-nav-pos'            => (!empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-section-nav-pos' ]) ? $pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-section-nav-pos' ] : null),
                    'section-nav-loc'            => (!empty($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-section-nav-loc' ]) ? $pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-section-nav-loc' ] : null),
                    'section-cell-settings'      => get_post_meta($pzucd_blueprint_field_set[ '_pzucd_' . $i . '-blueprint-section-cell-layout' ]),

            ) : null;
  }

  return $pzucd_blueprint;
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

add_shortcode('pzucd', 'pzucd_shortcode');
function pzucd_shortcode($atts, $content = null, $tag)
{

//  $pzucd_blueprint_arr = pzucd_get_the_blueprint($atts[ 0 ]);
//  var_dump($pzucd_blueprint_arr);
//  var_dump($atts);
//  $pzucd = new pzucd_Display();
  $pzucd_blueprint = '';
  if (!empty($atts[ 'blueprint' ]))
  {
    $pzucd_blueprint = $atts[ 'blueprint' ];
  }
  elseif (!empty($atts[ 0 ]))
  {
    $pzucd_blueprint = $atts[ 0 ];
  }

  return pzucd($pzucd_blueprint, (!empty($atts[ 'ids' ]) ? $atts[ 'ids' ] : null));

  //  return pzucd_render($pzucd_blueprint_arr, (!empty($atts[ 'ids' ]) ? $atts[ 'ids' ] : null), 'pzucd_Display');

}


/* Blueprint tag */
/* Overrides is a list of ids */
function pzucd($pzucd_blueprint = null, $pzucd_overrides = null)
{
  if (empty($pzucd_blueprint))
  {
  // make this use a set of defaults. prob an excerpt grid
    return 'You need to set a blueprint';
  }
  $pzucd_blueprint_arr = pzucd_get_the_blueprint($pzucd_blueprint);

  $pzucd_stuff = new pzucd_Display();
  $pzucd_stuff->render($pzucd_blueprint_arr, $pzucd_overrides);

  return $pzucd_stuff->output;

}

// Capture and append the comments display
add_filter('pzucd_comments', 'pzucd_get_comments');
function pzucd_get_comments($pzucd_content)
{
//  pzdebug(get_the_id());
  ob_start();
  comments_template(null, null);
  $pzucd_comments = ob_get_contents();
  ob_end_flush();

  return $pzucd_content . $pzucd_comments;
}

































/*

// Blueprint tag
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

    // Get blueprint options
    $query_options          = array(
      'post_type'  => 'ucd-blueprints',
      'meta_key'   => 'pzucd_blueprint-set-name',
      'meta_value' => $pzucd_section[ 'blueprint' ]
    );
    $blueprint_array         = get_posts($query_options);
    $pzucd_blueprint_options = get_post_custom($blueprint_array[ 0 ]->ID);

    // Squish arrays as values are currently in $x['name'][0]. This makes them $x['name']
    $pzucd_cell_options     = pz_squish($pzucd_cell_options);
    $pzucd_blueprint_options = pz_squish($pzucd_blueprint_options);

//		pzdebug($pzucd_cell_options);
//		pzdebug($pzucd_criteria_options);
//		pzdebug($pzucd_blueprint_options);

    // Should each be a separate class?? i.e. layout,criteria, blueprint? or child classes. hmmm
    // Not child coz childs are for adding or changing existing functionailty
    // Separates may be overkill at this stage since there'd only be one method per class
    /// So we'll stick to the single class


    // Pass a generic cells blueprint - don't know why yet!
    $pzucd = new ucdDisplay($pzucd_cell_options, $pzucd_blueprint_options);

//		// Get the content
//		// should this return a query
//		$pzucd_query = $pzucd->select($pzucd_section['criteria_options']);

    // Generate the cell blueprint
    $pzucd_cells_layout = $pzucd->cell_layout();

    // Generate the full layout
    $pzucd_blueprint_layout = $pzucd->layout_blueprint();

    $pzucd->layout_header($pzucd_blueprint_layout); // Outer wrapper and nav top and left
    var_dump($pzucd_query->found_posts);
    if ($pzucd_query->have_posts())
    {
      $row_break = false;
      $i         = 0;
      // These sections are rows - but may not be necessary - although may be useful for advanced formatting
      $pzucd->section_header($pzucd_blueprint_layout);
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
      $pzucd->section_footer($pzucd_blueprint_layout); // Outer wrapper close and nav right and bottom
    } //endif
    // Should this be in the loop?
    // And finally, display it
  } //end foreach
  var_dump($pzucd_sections);
  do_action('pzucd_after_sections');
  $pzucd->layout_footer($pzucd_blueprint_layout);
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
    list($pzucd_sections[ 'section1' ][ 'cells' ], $pzucd_sections[ 'section1' ][ 'blueprint' ]) = explode(',', $atts[ 'section1' ]);
  }
  if (isset($atts[ 'section2' ]))
  {
    list($pzucd_sections[ 'section2' ][ 'cells' ], $pzucd_sections[ 'section2' ][ 'blueprint' ]) = explode(',', $atts[ 'section2' ]);
  }
  if (isset($atts[ 'section3' ]))
  {
    list($pzucd_sections[ 'section3' ][ 'cells' ], $pzucd_sections[ 'section3' ][ 'blueprint' ]) = explode(',', $atts[ 'section3' ]);
  }

//	pzdebug($pzucd_sections);

  // Id there goingto be a problem putting thisin a post???? Doe sit cause an infinite loop where it just keeps calling itself? Do we need to ensure if it's a post that the current post isn't displayed?
  $return_html = pzucd_display($atts, $pzucd_sections, $title);

  return $return_html;
}

add_shortcode('pzucd', 'pzucd_shortcode');
*/

