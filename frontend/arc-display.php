<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chrishoward
 * Date: 13/08/13
 * Time: 8:32 PM
 * To change this blueprint use File | Settings | File Blueprints.
 */

// require(PZARC_PLUGIN_PATH .'/frontend/class_pzarcDisplay.php');
//require(PZARC_PLUGIN_PATH .'/includes/class_pzarcQuery.php');

// should this be handled in defs??
//require PZARC_PLUGIN_PATH . '/frontend/arcGallery.php';

require_once PZARC_PLUGIN_PATH . 'frontend/class_pzarc_Display.php';
require PZARC_PLUGIN_PATH . '/frontend/arcCellDefinitions.php';
require_once(PZARC_PLUGIN_PATH . 'external/bfi_thumb/BFI_Thumb.php');

//add_shortcode('arcgallery', 'pzarc_gallery_shortcode');
//
//// Need to work out how to replace wp gallery with ours
////add_filter('post_gallery', 'pzarc_gallery');
//
//function pzarc_gallery_shortcode($atts, $content = null, $tag)
//{
//
//
//  $pzarc_the_blueprint = pzarc_get_the_blueprint($atts[ 'blueprint' ]);
//  $overrides          = $atts[ 'ids' ];
//  $output             = pzarc_render($pzarc_the_blueprint, $overrides);
//
//  return $output;
//}
//
//// Overrides WP Gallery
//function pzarc_gallery()
//{
//  global $post;
//  if (has_shortcode($post->post_content, 'gallery'))
//  {
//    return ' ';
//  }
//}

function pzarc_get_the_blueprint($blueprint)
{
//  wp_enqueue_script('jquery-isotope');

  // meed to return a structure for the cells, the content source, the navgation info


  global $wp_query;
  $original_query = $wp_query;
  $blueprint_info  = new WP_Query('post_type=arc-blueprints&meta_key=_pzarc_blueprint-short-name&meta_value=' . $blueprint);
 // var_dump($blueprint_info->request);
  if (!isset($blueprint_info->posts[ 0 ]))
  {
    echo '<p class="pzarc-oops">Blueprint ' . $blueprint . ' not found</p>';

    return null;
  }
  $the_blueprint_meta = get_post_meta($blueprint_info->posts[ 0 ]->ID, null, true);
//  $wp_query = $original_query;
  // wp_reset_postdata();

  // VERY risky- fine on single pages, but will cause horror on multi post pages
  // rewind_posts();

  foreach ($the_blueprint_meta as $key => $value)
  {
    $pzarc_blueprint_field_set[ $key ] = $value[ 0 ];
  }
  $pzarc_blueprint = array(
           'blueprint-id' => $blueprint_info->posts[ 0 ]->ID,
          'blueprint-short-name' => (!empty($pzarc_blueprint_field_set[ '_pzarc_blueprint-short-name' ]) ? $pzarc_blueprint_field_set[ '_pzarc_blueprint-short-name' ] : null),
          'blueprint-criteria'   => (!empty($pzarc_blueprint_field_set [ '_pzarc_blueprint-criteria' ]) ? $pzarc_blueprint_field_set [ '_pzarc_blueprint-criteria' ] : 'default'),
          'blueprint-pager'      => (!empty($pzarc_blueprint_field_set[ '_pzarc_blueprint-pager' ]) ? $pzarc_blueprint_field_set[ '_pzarc_blueprint-pager' ] : null),
          'blueprint-posts-per-page'      => (!empty($pzarc_blueprint_field_set[ '_pzarc_blueprint-posts-per-page' ]) ? $pzarc_blueprint_field_set[ '_pzarc_blueprint-posts-per-page' ] : get_option('posts_per_page')),
          'blueprint-type'       => (!empty($pzarc_blueprint_field_set[ '_pzarc_blueprint-type' ]) ? $pzarc_blueprint_field_set[ '_pzarc_blueprint-type' ] : null),
  );
  $pzarc_blueprint[ 'section' ][ 0 ] = 
          array(
                  'section-enable'             => true,
                  'section-cells-per-view'     => (!empty($pzarc_blueprint_field_set[ '_pzarc_0-blueprint-cells-per-view' ]) ? $pzarc_blueprint_field_set[ '_pzarc_0-blueprint-cells-per-view' ] : null),
                  'section-cells-across'       => (!empty($pzarc_blueprint_field_set[ '_pzarc_0-blueprint-cells-across' ]) ? $pzarc_blueprint_field_set[ '_pzarc_0-blueprint-cells-across' ] : null),
                  'section-min-cell-width'     => (!empty($pzarc_blueprint_field_set[ '_pzarc_0-blueprint-min-cell-width' ]) ? $pzarc_blueprint_field_set[ '_pzarc_0-blueprint-min-cell-width' ] : null),
                  'section-cells-vert-margin'  => (!empty($pzarc_blueprint_field_set[ '_pzarc_0-blueprint-cells-vert-margin' ]) ? $pzarc_blueprint_field_set[ '_pzarc_0-blueprint-cells-vert-margin' ] : null),
                  'section-cells-horiz-margin' => (!empty($pzarc_blueprint_field_set[ '_pzarc_0-blueprint-cells-horiz-margin' ]) ? $pzarc_blueprint_field_set[ '_pzarc_0-blueprint-cells-horiz-margin' ] : null),
                  'section-cell-layout'        => (!empty($pzarc_blueprint_field_set[ '_pzarc_0-blueprint-section-cell-layout' ]) ? $pzarc_blueprint_field_set[ '_pzarc_0-blueprint-section-cell-layout' ] : null),
                  'section-layout-mode'        => (!empty($pzarc_blueprint_field_set[ '_pzarc_0-blueprint-layout-mode' ]) ? $pzarc_blueprint_field_set[ '_pzarc_0-blueprint-layout-mode' ] : null),
                  'section-navigation'         => (!empty($pzarc_blueprint_field_set[ '_pzarc_0-blueprint-section-navigation' ]) ? $pzarc_blueprint_field_set[ '_pzarc_0-blueprint-section-navigation' ] : null),
                  'section-nav-pos'            => (!empty($pzarc_blueprint_field_set[ '_pzarc_0-blueprint-section-nav-pos' ]) ? $pzarc_blueprint_field_set[ '_pzarc_0-blueprint-section-nav-pos' ] : null),
                  'section-nav-loc'            => (!empty($pzarc_blueprint_field_set[ '_pzarc_0-blueprint-section-nav-loc' ]) ? $pzarc_blueprint_field_set[ '_pzarc_0-blueprint-section-nav-loc' ] : null),
                  'section-cell-settings'      => get_post_meta($pzarc_blueprint_field_set[ '_pzarc_0-blueprint-section-cell-layout' ]),

          ) ;
  for ($i = 1; $i < 3; $i++)
  {
    $pzarc_blueprint[ 'section' ][ $i ] = !empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-section-enable' ]) ?
            array(
                    'section-enable'             => !empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-section-enable' ]),
                    'section-cells-per-view'     => (!empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-cells-per-view' ]) ? $pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-cells-per-view' ] : null),
                    'section-cells-across'       => (!empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-cells-across' ]) ? $pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-cells-across' ] : null),
                    'section-min-cell-width'     => (!empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-min-cell-width' ]) ? $pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-min-cell-width' ] : null),
                    'section-cells-vert-margin'  => (!empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-cells-vert-margin' ]) ? $pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-cells-vert-margin' ] : null),
                    'section-cells-horiz-margin' => (!empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-cells-horiz-margin' ]) ? $pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-cells-horiz-margin' ] : null),
                    'section-cell-layout'        => (!empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-section-cell-layout' ]) ? $pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-section-cell-layout' ] : null),
                    'section-layout-mode'        => (!empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-layout-mode' ]) ? $pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-layout-mode' ] : null),
                    'section-navigation'         => (!empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-section-navigation' ]) ? $pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-section-navigation' ] : null),
                    'section-nav-pos'            => (!empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-section-nav-pos' ]) ? $pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-section-nav-pos' ] : null),
                    'section-nav-loc'            => (!empty($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-section-nav-loc' ]) ? $pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-section-nav-loc' ] : null),
                    'section-cell-settings'      => get_post_meta($pzarc_blueprint_field_set[ '_pzarc_' . $i . '-blueprint-section-cell-layout' ]),

            ) : null;
  }

  return $pzarc_blueprint;
}

function pzarc_get_cell_design($pzarc_cell_layout_id)
{

  $pzarc_cell_design = get_post_meta($pzarc_cell_layout_id, '_pzarc_layout-cell-preview', true);

  return $pzarc_cell_design;

}


function pzarc_flatten_wpinfo($array_in)
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

add_shortcode('pzarc', 'pzarc_shortcode');
function pzarc_shortcode($atts, $content = null, $tag)
{

//  $pzarc_blueprint_arr = pzarc_get_the_blueprint($atts[ 0 ]);
//  var_dump($pzarc_blueprint_arr);
//  var_dump($atts);
//  $pzarc = new pzarc_Display();
  $pzarc_blueprint = '';
  if (!empty($atts[ 'blueprint' ]))
  {
    $pzarc_blueprint = $atts[ 'blueprint' ];
  }
  elseif (!empty($atts[ 0 ]))
  {
    $pzarc_blueprint = $atts[ 0 ];
  }

  return pzarc($pzarc_blueprint, (!empty($atts[ 'ids' ]) ? $atts[ 'ids' ] : null));

  //  return pzarc_render($pzarc_blueprint_arr, (!empty($atts[ 'ids' ]) ? $atts[ 'ids' ] : null), 'pzarc_Display');

}


/* Blueprint tag */
/* Overrides is a list of ids */
function pzarc($pzarc_blueprint = null, $pzarc_overrides = null)
{
  if (empty($pzarc_blueprint))
  {
  // make this use a set of defaults. prob an excerpt grid
    return 'You need to set a blueprint';
  }
  $pzarc_blueprint_arr = pzarc_get_the_blueprint($pzarc_blueprint);

  $pzarc_stuff = new pzarc_Display();
  $pzarc_stuff->render($pzarc_blueprint_arr, $pzarc_overrides);

  return $pzarc_stuff->output;

}

// Capture and append the comments display
add_filter('pzarc_comments', 'pzarc_get_comments');
function pzarc_get_comments($pzarc_content)
{
//  pzdebug(get_the_id());
  ob_start();
  comments_template(null, null);
  $pzarc_comments = ob_get_contents();
  ob_end_flush();

  return $pzarc_content . $pzarc_comments;
}

































/*

// Blueprint tag
function pzarc_display()
{
  $pzarc_criteria = null;
  $pzarc_sections = null;
  $pzarc_title    = null;
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
  if (empty($pzarc_sections))
  {
    return false;
  }

  // It's ok for criteria tobe null as then we just use the default
  // There will be other scenarios where we use the default - see E+.
  // Only allowed one query per set - this is so pagination doesn't get screwed and we get consistency between sections
  if (!isset($pzarc_criteria))
  {
    $pzarc_query = $wp_query;
  }
  else
  {
    // Do the query
    // Get criteria options
    $query_options          = array(
      'post_type'  => 'arc-criterias',
      'meta_key'   => 'pzarc_criteria-name',
      'meta_value' => $pzarc_criteria
    );
    $criteria_array         = get_posts($query_options);
    $pzarc_criteria_options = get_post_custom($criteria_array[ 0 ]->ID);
    $pzarc_query            = new pzarcQuery($pzarc_criteria_options);
    $pzarc_criteria_options = pz_squish($pzarc_criteria_options);
  }

  // TEMPORARY FOR TESTING
  $pzarc_query = $wp_query;
//	pzdebug((array) $pzarc_query);


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
  do_action('pzarc_before_section');

  // Loop for each section (max 3!)
  var_dump(count($pzarc_sections));
  foreach ($pzarc_sections as $pzarc_section)
  {
    // Need to setup a routine for the defaults... probably pulled from the bit that makes the meta fields or save the lot :/
    // Get cell options
//pzdebug($pzarc_section);
    // replace this with function call
    $query_options      = array(
      'post_type'  => 'arc-layouts',
      'meta_key'   => 'pzarc_layout-set-name',
      'meta_value' => $pzarc_section[ 'cells' ]
    );
    $cell_array         = get_posts($query_options);
    $pzarc_cell_options = get_post_custom($cell_array[ 0 ]->ID);

    // Get blueprint options
    $query_options          = array(
      'post_type'  => 'arc-blueprints',
      'meta_key'   => 'pzarc_blueprint-set-name',
      'meta_value' => $pzarc_section[ 'blueprint' ]
    );
    $blueprint_array         = get_posts($query_options);
    $pzarc_blueprint_options = get_post_custom($blueprint_array[ 0 ]->ID);

    // Squish arrays as values are currently in $x['name'][0]. This makes them $x['name']
    $pzarc_cell_options     = pz_squish($pzarc_cell_options);
    $pzarc_blueprint_options = pz_squish($pzarc_blueprint_options);

//		pzdebug($pzarc_cell_options);
//		pzdebug($pzarc_criteria_options);
//		pzdebug($pzarc_blueprint_options);

    // Should each be a separate class?? i.e. layout,criteria, blueprint? or child classes. hmmm
    // Not child coz childs are for adding or changing existing functionailty
    // Separates may be overkill at this stage since there'd only be one method per class
    /// So we'll stick to the single class


    // Pass a generic cells blueprint - don't know why yet!
    $pzarc = new arcDisplay($pzarc_cell_options, $pzarc_blueprint_options);

//		// Get the content
//		// should this return a query
//		$pzarc_query = $pzarc->select($pzarc_section['criteria_options']);

    // Generate the cell blueprint
    $pzarc_cells_layout = $pzarc->cell_layout();

    // Generate the full layout
    $pzarc_blueprint_layout = $pzarc->layout_blueprint();

    $pzarc->layout_header($pzarc_blueprint_layout); // Outer wrapper and nav top and left
    var_dump($pzarc_query->found_posts);
    if ($pzarc_query->have_posts())
    {
      $row_break = false;
      $i         = 0;
      // These sections are rows - but may not be necessary - although may be useful for advanced formatting
      $pzarc->section_header($pzarc_blueprint_layout);
      while ($pzarc_query->have_posts() && !$row_break)
        // Do we need row breaks? Can % width deal with them automatically? And then do we need sections?
        // Can "dumb" thml with smart css format it?
        // Will need {clear:left} at firs titem in  each row, will need smart css to do that.
      {
        $pzarc_query->the_post();
        var_dump(get_the_id());
        // $i will be used to identify the cell so $i % cells_per_row will enabled us to determine the row beginning
        //	echo $pzarc->display_cell($pzarc_cells_layout,$i++);
      } //Endwhile
      $pzarc->section_footer($pzarc_blueprint_layout); // Outer wrapper close and nav right and bottom
    } //endif
    // Should this be in the loop?
    // And finally, display it
  } //end foreach
  var_dump($pzarc_sections);
  do_action('pzarc_after_sections');
  $pzarc->layout_footer($pzarc_blueprint_layout);
  wp_reset_postdata();
  rewind_posts();

} // EOF

function pzarc_shortcode($atts, $title = null)
{
//	pzdebug($atts);
//	var_dump($title);
  $pzarc_sections = array();
// var_dump($atts);
  if (isset($atts[ 'section1' ]))
  {
    list($pzarc_sections[ 'section1' ][ 'cells' ], $pzarc_sections[ 'section1' ][ 'blueprint' ]) = explode(',', $atts[ 'section1' ]);
  }
  if (isset($atts[ 'section2' ]))
  {
    list($pzarc_sections[ 'section2' ][ 'cells' ], $pzarc_sections[ 'section2' ][ 'blueprint' ]) = explode(',', $atts[ 'section2' ]);
  }
  if (isset($atts[ 'section3' ]))
  {
    list($pzarc_sections[ 'section3' ][ 'cells' ], $pzarc_sections[ 'section3' ][ 'blueprint' ]) = explode(',', $atts[ 'section3' ]);
  }

//	pzdebug($pzarc_sections);

  // Id there goingto be a problem putting thisin a post???? Doe sit cause an infinite loop where it just keeps calling itself? Do we need to ensure if it's a post that the current post isn't displayed?
  $return_html = pzarc_display($atts, $pzarc_sections, $title);

  return $return_html;
}

add_shortcode('pzarc', 'pzarc_shortcode');
*/

