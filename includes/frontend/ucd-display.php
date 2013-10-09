<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chrishoward
 * Date: 13/08/13
 * Time: 8:32 PM
 * To change this template use File | Settings | File Templates.
 */

require(PZUCD_PLUGIN_PATH .'/includes/frontend/class_pzucdDisplay.php');
require(PZUCD_PLUGIN_PATH .'/includes/class_pzucdQuery.php');

// Template tag
function pzucd_display($pzucd_criteria=null ,$pzucd_sections = null, $pzucd_title = null)
{
	echo '<h1>HELLOOOO!</H1>';
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
	if (!isset($pzucd_criteria)) {
		$pzucd_query = $wp_query;
	} else {
		// Do the query
		// Get criteria options
		$query_options          = array(
			'post_type'  => 'ucd-criterias',
			'meta_key'   => 'pzucd_criteria-name',
			'meta_value' => $pzucd_criteria
		);
		$criteria_array         = get_posts($query_options);
		$pzucd_criteria_options = get_post_custom($criteria_array[ 0 ]->ID);
		$pzucd_query = new pzucdQuery($pzucd_criteria_options);
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
	foreach ($pzucd_sections as $pzucd_section) {
		// Need to setup a routine for the defaults... probably pulled from the bit that makes the meta fields or save the lot :/
		// Get cell options
//pzdebug($pzucd_section);
		// replace this with function call
		$query_options      = array(
			'post_type'  => 'ucd-layouts',
			'meta_key'   => 'pzucd_layout-set-name',
			'meta_value' => $pzucd_section['cells']
		);
		$cell_array         = get_posts($query_options);
		$pzucd_cell_options = get_post_custom($cell_array[ 0 ]->ID);

		// Get template options
		$query_options          = array(
			'post_type'  => 'ucd-templates',
			'meta_key'   => 'pzucd_template-set-name',
			'meta_value' => $pzucd_section['template']
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
		$pzucd = new ucdDisplay($pzucd_cell_options,$pzucd_template_options);

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
			$i = 0;
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
	if (isset($atts['section1'])) {list($pzucd_sections['section1']['cells'],$pzucd_sections['section1']['template']) = explode(',',$atts['section1']);}
	if (isset($atts['section2'])) {list($pzucd_sections['section2']['cells'],$pzucd_sections['section2']['template']) = explode(',',$atts['section2']);}
	if (isset($atts['section3'])) {list($pzucd_sections['section3']['cells'],$pzucd_sections['section3']['template']) = explode(',',$atts['section3']);}

//	pzdebug($pzucd_sections);

	// Id there goingto be a problem putting thisin a post???? Doe sit cause an infinite loop where it just keeps calling itself? Do we need to ensure if it's a post that the current post isn't displayed?
	$return_html = pzucd_display($atts['criteria'],$pzucd_sections,$title);
	return $return_html;
}

add_shortcode('pzucd', 'pzucd_shortcode');
