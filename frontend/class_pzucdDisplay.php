<?php

/* * ***************************
 * Class Ucd_Display
 * **************************** */

class ucdDisplay
{

	public $pzucd_cells_layout = '';
	public $pzucd_cell_options = '';
	public $pzucd_criteria_options = '';
	public $pzucd_template_options = '';

	function __construct($cell_options,$template_options)
	{
    require PZUCD_PLUGIN_PATH.'/frontend/ucdGallery.php';

    //$tag, $function_to_add, $priority, $accepted_args
		// add apply_filters so devs can add use their own stuff like navs.
//		$this->pzucd_cells_layout     = $cells_template;
		$this->pzucd_cell_options     = $cell_options;
//		$this->pzucd_criteria_options = $criteria_options;
		$this->pzucd_template_options = $template_options;

		//$pzucd_navigation = apply_filters();
	}

// EOF __construct
	public function cells_template() {
		// This should be passed into. eg. $ucd = new ucdDisplay();$ucd->layout = that;$output = $ucd->getOutput();echo $output or whatever you want to do with it
		// Default
    // this will bedifferent for each layout
		$pzucd_cells_layout = '
				<article class="pzucd_cell %cell-no%" style="width:%cells-per-row%;">
					<div class="pzucd_cell_header">
						<div class="pzucd_cell_header_left">%header-left%</div>
						<div class="pzucd_cell_header_right">%header-right%</div>
					</div>
					<div class="pzucd_clearfloat"></div>
					<div class="pzucd_cell_columns">
						<div class= "pzucd_cell_left_col">%column-left%</div>
						<div class= "pzucd_cell_centre_col">%column-centre%</div>
						<div class="pzucd_cell_right_col">%column-right%</div>
					</div>
					<div class="pzucd_clearfloat"></div>
					<div class="pzucd_cell_footer">
						<div class="pzucd_cell_footer_left">%footer-left%</div>
						<div class="pzucd_cell_footer_right">%footer-right%</div>
					</div>
					<div class="pzucd_clearfloat"></div>
				</article>
		';

	}


	public function cell_layout() {
		// This is the bit tha works it all out - make sure it's called outside the loop! Don't want to call it every time
	}

	public function layout_template() {
		// this bit works out the template layout. Only call once per section!
	}

	public function layout_header($layout_template)
	{
		echo '<h3>Layout Header</h3>';
	}

	public function section_header($layout_template)
	{
		echo '<h3>Section Header</h3><ul></ul>';
	}

	public function layout_footer($layout_template)
	{
		echo '<h3>Layout footer</h3>';

	}

	public function section_footer($layout_template)
	{
		echo '</ul><h3>Section footer</h3>';

	}
	public function display_cell($cell_layout,$cell_no)
	{
		// THIS WILL NEED TO BE CHANGED SO ACCOMMODATES ANY CONTENT TYPE
		$the_title = '<h2 class= "entry-title">'.get_the_title.'</h2>';
		$the_content = '<div class="entry-content">'.get_the_excerpt().'</div>';
		return '<div class="hentry">'.$the_title.$the_content.' - Cell '.$cell_no.'</div>';
	}


	function __destruct()
	{

	}

// EOF __destruct
}

// EOC ucdDisplay

function pzucd_layout($cells, $criteria, $templates, $nav)
{
	var_dump($cells, $criteria, $templates, $nav);
}

