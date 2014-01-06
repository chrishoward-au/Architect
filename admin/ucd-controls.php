<?php

/**
 * Created by JetBrains PhpStorm.
 * User: chrishoward
 * Date: 25/09/13
 * Time: 7:11 PM
 * To change this template use File | Settings | File Templates.
 */

class pzucd_Controls extends pzucdForm
{
	function __construct()
	{
		add_action('init', array($this, 'create_ucdcontrols_post_type'));
	}

	public function create_ucdcontrols_post_type()
	{
		$labels = array(
			'name'               => _x('Controls', 'post type general name'),
			'singular_name'      => _x('Control', 'post type singular name'),
			'add_new'            => __('Add New control'),
			'add_new_item'       => __('Add New control'),
			'edit_item'          => __('Edit control'),
			'new_item'           => __('New control'),
			'view_item'          => __('View control'),
			'search_items'       => __('Search controls'),
			'not_found'          => __('No controls found'),
			'not_found_in_trash' => __('No controls found in Trash'),
			'parent_item_colon'  => '',
			'menu_name'          => _x('Controls', 'pzucd-controls-designer'),
		);

		$args = array(
			'labels'               => $labels,
			'description'          => __('Architect Controls are used to create reusable navigation controllers for use in your UCD blocks, widgets, shortcodes and WP template tags.'),
			'public'               => false,
			'publicly_queryable'   => false,
			'show_ui'              => true,
			'show_in_menu'         => 'pzucd',
			'show_in_control_menus'    => false,
			'query_var'            => true,
			'rewrite'              => true,
			'capability_type'      => 'post',
			'has_archive'          => false,
			'hierarchical'         => false,
			'menu_position'        => 30,
			'supports'             => array('title', 'revisions'),
			'exclude_from_search'  => true,
			'register_meta_box_cb' => array($this, 'controls_meta')
		);

		register_post_type('ucd-controls', $args);
	}
}

/* Control types
- Tabs (titles, bullets, numbers)
- Accordions
- HTML5 slider
- Arrows
- Pager
-
*/