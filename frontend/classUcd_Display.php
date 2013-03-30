<?php
/*****************************
* Class Ucd_Display
******************************/

class ucdDisplay {

	public $pzucd_cells_template = '';

	function __construct() {

		// add apply_filters so devs can add use their own stuff like navs.
		 $this->pzucd_cells_template = '
					<div class="pzucd_cell" style="width:%cells-per-row%;">
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
					</div>
			';

			$pzucd_navigation = apply_filters();

	} // EOF __construct

	function __destruct(){

	} // EOF __destruct

} // EOC ucdDisplay