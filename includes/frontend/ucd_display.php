<?php


class ucd_display extends UCDDisplay {

	function __construct($parameters) {

//

// Load the config

// 1 x content/data
// N x Templates
// Each template has 1 cells def

$config = apply_filters('get_config',get_config($parameters));

$data_pars = apply_filters('get_data_parameters',$config['data']);

// Load query
global $wp_query;

// Loop
// Each section can have a different layout but the data query remains the same. Thus can do a full post followed by a grid followed by titles list.


$templates =  $config['sections'];

do_action('above',$config['navs']);
do_action('left',$config['pagers']);
	while ($wp_query->has_posts()) {
		// Get layout for each section
		foreach ($templates as $template) {
			// get next post;
			$wp_query->post();
			do_action_ref_array('display_cell',$config['cells']);
		}
	}
do_action('right',$config['pagers']);
do_action('below',$config['navs']);
	}
}