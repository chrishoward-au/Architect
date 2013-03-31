<?php


class pzucdAdmin 
{

	function __construct() 
	{
		/* 
		* Create the layouts custom post type 
		*/
	

		if (is_admin())
		{
		//	add_action('admin_init', 'pzucd_preview_meta');
			add_action('admin_head', array($this, 'admin_head'));
			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));
		}

		require PZUCD_PLUGIN_PATH.'/admin/ucd-cell-layouts.php';
    new pzucd_Cell_Layouts;

	}

	function admin_enqueue($hook) {
		$screen = get_current_screen();
		if ( 'ucd-layouts' == $screen->id ) 
		{

		}

	}
	function admin_head() {
	}


	// add_action('admin_head','pzucd_layouts_add_help_tab');
	// function pzucd_layouts_add_help_tab () {
	//     $screen = get_current_screen();
	// 		$prefix = 'pzucd_';
	// 		switch ($screen->id) {
	// 			case 'edit-ucd-layouts':
	// 				$screen->add_help_tab( array(
	// 						'id'	=> $prefix.'view_help_about',
	// 						'title'	=> __('About ContentPlus Layouts'),
	// 						'content'	=> '<h3>About</h3><p>' . __( 'ContentPlus layouts are used in the ContentPlus Block' ) . '</p>'
	// 				) );
	// 				$screen->add_help_tab( array(
	//             'title' => __('Support','pzucd'),
	//             'id' => $prefix . 'view_help_support',
	//             'content' => '<h3>Support</h3><p>'.__('Headway users can get support for ContentPlus on the <a href="http://support.headwaythemes.com/" target=_blank>Headway forums</a>').'</p>'
	//         )	);

					
	// 				break;

	// 			default:
	// 				return;
	// 				break;
	// 		}
	// }
	// Make this only load once - probably loads all the time at the moment

	function layout_defaults() {
		global $pzucd_cpt_layouts_meta_boxes;
//pzdebug($pzucd_cpt_layouts_meta_boxes);
		$pzucd_layout_defaults = array();
		$this->populate_layout_options();
		foreach ($pzucd_cpt_layouts_meta_boxes['tabs'] as $pzucd_meta_box) {
//		pzdebug($pzucd_meta_box);
			foreach ($pzucd_meta_box['fields'] as $pzucd_field) {
					if (!isset($pzucd_field['id'])) {
						$pzucd_layout_defaults[$pzucd_field['id']] = (isset($pzucd_field['default'])?$pzucd_field['default']:null);
					}
				}
			}
//pzdebug($pzucd_layout_defaults);			
		return $pzucd_layout_defaults;
	}	



} // end pzucdAdmin

new pzucdAdmin();

class pzucdPreview
{

} 
