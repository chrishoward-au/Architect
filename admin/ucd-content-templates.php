<?php

class pzucd_Content_templates extends pzucdForm
{

  private $mb_fields;

  /**
   * [__construct description]
   */
  function __construct()
  {
    add_action('init', array($this, 'create_templates_post_type'));
    // This overrides the one in the parent class

    if (is_admin())
    {

      if (!class_exists('CMB_Meta_Box'))
      {
        require_once PZUCD_PLUGIN_PATH . 'external/HM-Custom-Meta-Boxes/custom-meta-boxes.php';
      }

      //	add_action('admin_init', 'pzucd_preview_meta');
      add_action('add_meta_boxes', array($this, 'templates_meta'));
      add_action('admin_head', array($this, 'content_templates_admin_head'));
      add_action('admin_enqueue_scripts', array($this, 'content_templates_admin_enqueue'));
//			add_filter('manage_ucd-templates_posts_columns', array($this, 'add_template_columns'));
//			add_action('manage_ucd-templates_posts_custom_column', array($this, 'add_template_column_content'), 10, 2);

      // check screen ucd-templates. ugh. doesn't work for save and edit
//			if ( $_REQUEST[ 'post_type' ] == 'ucd-templates' )
//			{
//			}
    }

  }

  /**
   * [content_templates_admin_enqueue description]
   * @param  [type] $hook [description]
   * @return [type]       [description]
   */
  public function content_templates_admin_enqueue($hook)
  {
    $screen = get_current_screen();
    if ('ucd-templates' == $screen->id)
    {


      wp_enqueue_style('pzucd-admin-templates-css', PZUCD_PLUGIN_URL . 'admin/css/ucd-admin-templates.css');

      wp_enqueue_script('jquery-pzucd-metaboxes-templates', PZUCD_PLUGIN_URL . 'admin/js/ucd-metaboxes-templates.js', array('jquery'));
      wp_enqueue_script('jquery-isotope', PZUCD_PLUGIN_URL . 'external/jquery.isotope.min.js', array('jquery'));
      wp_enqueue_script('jquery-isotope', PZUCD_PLUGIN_URL . 'external/masonry.pkgd.min.js', array('jquery'));
    }
  }

  /**
   * [content_templates_admin_head description]
   * @return [type] [description]
   */
  public function content_templates_admin_head()
  {

  }

  /**
   * [add_template_columns description]
   * @param [type] $columns [description]
   */
  public function add_template_columns($columns)
  {
    unset($columns[ 'thumbnail' ]);
    $pzucd_front  = array_slice($columns, 0, 2);
    $pzucd_back   = array_slice($columns, 2);
    $pzucd_insert =
            array
            (
              'pzucd_template_short_name' => __('Template short name', 'pzsp'),
            );

    return array_merge($pzucd_front, $pzucd_insert, $pzucd_back);
  }

  /**
   * [add_template_column_content description]
   * @param [type] $column  [description]
   * @param [type] $post_id [description]
   */
  public function add_template_column_content($column, $post_id)
  {
    switch ($column)
    {
      case 'pzucd_short_name':
        echo get_post_meta($post_id, 'pzucd_template_short-name', true);
        break;
    }
  }

  /**
   * [create_templates_post_type description]
   * @return [type] [description]
   */
  public function create_templates_post_type()
  {
    $labels = array(
      'name'               => _x('Templates', 'post type general name'),
      'singular_name'      => _x('Template', 'post type singular name'),
      'add_new'            => __('Add New Template'),
      'add_new_item'       => __('Add New Template'),
      'edit_item'          => __('Edit Template'),
      'new_item'           => __('New Template'),
      'view_item'          => __('View Template'),
      'search_items'       => __('Search Templates'),
      'not_found'          => __('No Templates found'),
      'not_found_in_trash' => __('No Templates found in Trash'),
      'parent_item_colon'  => '',
      'menu_name'          => _x('Templates', 'pzucd-template-designer'),
    );

    $args = array(
      'labels'              => $labels,
      'description'         => __('Ultimate Content Display templates are used to create reusable layout templates for use in your UCD blocks, widgets, shortcodes and WP template tags. These are made up of cells, sections, criteria and navigation'),
      'public'              => false,
      'publicly_queryable'  => false,
      'show_ui'             => true,
      'show_in_menu'        => 'pzucd',
      'show_in_nav_menus'   => false,
      'query_var'           => true,
      'rewrite'             => true,
      'capability_type'     => 'post',
      'has_archive'         => false,
      'hierarchical'        => false,
      'menu_position'       => 30,
      'supports'            => array('title', 'revisions'),
      'exclude_from_search' => true,
    );

    register_post_type('ucd-templates', $args);
  }


// End templates_meta
  // cpt = custom post type
  /**
   * [populate_template_options description]
   * @return [type] [description]
   */
//	private function _populate_template_options()
//	{
//		//		global $this->meta_box_template;
//
//		$prefix = 'pzucd_';
//		/*
//		 *
//		 * Setup templates extra fields
//		 *
//		 */
//		$i                 = 0;
//		$meta_box_template = array(
//			'id'          => 'pzucd-templates-id',
//			'title'       => 'Template Designer',
//			'page'        => 'ucd-templates',
//			'context'     => 'normal',
//			'priority'    => 'high',
//			'orientation' => 'horizontal',
//			'tabs'        => array(
//				$i++ => array(
//					'icon'  => 'entypo-window',
//					'label' => __('General', 'pzucd'),
//					'id'    => $prefix . 'tab_templates_general',
//					'type'  => 'tab',
//				),
//				$i++ => array(
//					'icon'  => 'fontawesome-forward',
//					'label' => __('Navigation', 'pzucd'),
//					'id'    => $prefix . 'tab_templates_controls',
//					'type'  => 'tab',
//				),
//				$i++ => array(
//					'icon'  => 'fontawesome-forward',
//					'label' => __('Pager', 'pzucd'),
//					'id'    => $prefix . 'tab_templates_controls',
//					'type'  => 'tab',
//				),
//			)
//		);
//
//		$i = 0;
//
//		/*		 * **************
//		 * RESPONSIVE
//		 * *************** */
//		$meta_box_template[ 'tabs' ][ $i++ ][ 'fields' ] = array(
//			array(
//				'name' => __('General', 'pzucd'),
//				'id'      => $prefix . 'template-general',
//				'type'    => 'heading',
//				'default' => '',
//				'desc'    => __('', 'pzucd')
//			),
//			array(
//				'name' => __('Template set name', 'pzucd'),
//				'id'      => $prefix . 'template-set-name',
//				'type'    => 'text',
//				'default' => '',
//				'desc'    => __('.', 'pzucd'),
//				'help'    => __('Create sets of templates with each template in a set for different screen dimensions')
//			),
//			array(
//				'name' => __('Template short name ', 'pzucd'),
//				'id'      => $prefix . 'template-short-name',
//				'type'    => 'text',
//				'default' => '',
//				'desc'    => __('.', 'pzucd')
//			),
//			array(
//				'name' => __('Display', 'pzucd'),
//				'id'      => $prefix . 'template-display',
//				'type'    => 'select',
//				'default' => 'desktop',
//				'options' => array(
//					'desktop' => 'Desktop, Tablet landscape - greater than 960 pixels'),
//					'tablet-portrait' => 'Tablet Portrait - 641 to 960 pixels'),
//					'smartphone' => 'Smartphone - 0 to 640 pixels'),
//				),
//				'desc'    => __('Primary device you expect this template to show on.', 'pzucd'),
//			),
////				array(
////						'label'		 => __( 'Minimum display width (px)', 'pzucd' ),
////						'id'			 => $prefix . 'template-min-display-width',
////						'type'		 => 'text',
////						'default'	 => '0',
////						'desc'		 => __( '.', 'pzucd' ),
////				),
////				array(
////						'label'		 => __( 'Maximum display width (px)', 'pzucd' ),
////						'id'			 => $prefix . 'template-max-display-width',
////						'type'		 => 'text',
////						'default'	 => '320',
////						'desc'		 => __( '.', 'pzucd' ),
////				),
//			array(
//				'name' => __('Structure', 'pzucd'),
//				'id'      => $prefix . 'template-structure',
//				'type'    => 'heading',
//				'default' => '',
//				'desc'    => __('', 'pzucd')
//			),
//			array(
//				'name' => __('Cells per view', 'pzucd'),
//				'id'      => $prefix . 'template-cells-per-view',
//				'type'    => 'spinner',
//				'default' => '9',
//				'suffix'  => '',
//				'desc'    => __('.', 'pzucd'),
//				'help'    => __('')
//			),
//			array(
//				'name' => __('Number of cells across', 'pzucd'),
//				'id'      => $prefix . 'template-cells-across',
//				'type'    => 'spinner',
//				'default' => '3',
//				'suffix'  => '',
//				'desc'    => __('.', 'pzucd'),
//				'help'    => __('')
//			),
//			array(
//				'name' => __('Gutter width (%)', 'pzucd'),
//				'id'      => $prefix . 'layout-gutter-width',
//				'type'    => 'spinner',
//				'alt'     => 'gutter',
//				'default' => '1',
//				'min'     => '1',
//				'max'     => '100',
//				'step'    => '1',
//				'suffix'  => '%',
//				'desc'    => __('Set the gutter width as a percentage of the cell width. The gutter is the gap between adjoining elements', 'pzucd')
//			),
//			array(
//				'name' => __('Layout mode', 'pzucd'),
//				'id'      => $prefix . 'layout-mode',
//				'type'    => 'select',
//				'default' => 'masonary',
//				'options' => array(
//					'masonary' => 'Masonary'),
//					'masonaryVertical' => 'Masonary Vertical'),
//					'fitRows' => 'Fit rows'),
//					'fitColumns' => 'Fit columns'),
//					'cellsByRow' => 'Cells by row'),
//					'cellsByColumn' => 'Cells by column'),
//					'straightDown' => 'Straight down'),
//					'straightAcross' => 'Straight across'),
//				),
//				'desc'    => __('Choose how you want the cells to display. Please visit <a href="http://isotope.metafizzy.co/demos/layout-modes.html" target=_blank>Isotope Layout Modes</a> for demonstrations of these layouts', 'pzucd')
//			),
//		);
//
//		/// Navigation
//		$meta_box_template[ 'tabs' ][ $i++ ][ 'fields' ] = array(
//			array(
//				'name' => __('Navigation', 'pzucd'),
//				'id'      => $prefix . 'template-controls-heading',
//				'type'    => 'heading',
//				'default' => '',
//				'desc'    => __('Navigation controls single cells. Used, for example, in sliders and tabs.', 'pzucd')
//			),
//			array(
//				'name' => __('Navigation positions', 'pzucd'),
//				'id'      => $prefix . 'control-positions',
//				'type'    => 'multicheck',
//				'default' => array('bottom'),
//				'options' => array(
//					'top' => 'Top'),
//					'bottom' => 'Bottom'),
//					'left' => 'Left'),
//					'right' => 'Right'),
//				),
//				'desc'    => __('Choose where to display controls', 'pzucd')
//			),
//
//			array(
//				'name' => __('Top', 'pzucd'),
//				'id'      => $prefix . 'template-controls--heading-top',
//				'type'    => 'heading',
//				'default' => '',
//				'desc'    => __('', 'pzucd')
//			),
//			array(
//				'name' => __('Type', 'pzucd'),
//				'id'      => $prefix . 'template-top-control-type',
//				'type'    => 'text',
//				'default' => '',
//				'desc'    => __('.', 'pzucd'),
//				'help'    => __('Top control type: navigation, thumbs, titles, bullets')
//			),
//			array(
//				'name' => __('Position', 'pzucd'),
//				'id'      => $prefix . 'template-top-control-position',
//				'type'    => 'text',
//				'default' => '',
//				'desc'    => __('.', 'pzucd'),
//				'help'    => __('Top control position: Inside, Outside')
//			),
//		);
//		$meta_box_template[ 'tabs' ][ $i++ ][ 'fields' ] = array(
//			array(
//				'name' => __('Pager', 'pzucd'),
//				'id'      => $prefix . 'template-pager-heading',
//				'type'    => 'heading',
//				'default' => '',
//				'desc'    => __('Pagers control navigation between pages of content. Sometimes these may be a single cell, and sometimes a grid of cells.', 'pzucd')
//			),
//			array(
//				'name' => __('Pager positions', 'pzucd'),
//				'id'      => $prefix . 'pager-positions',
//				'type'    => 'multicheck',
//				'default' => array('bottom'),
//				'options' => array(
//					'top' => 'Top'),
//					'bottom' => 'Bottom'),
//					'left' => 'Left'),
//					'right' => 'Right'),
//				),
//				'desc'    => __('Choose where to display controls', 'pzucd')
//			),
//
//			array(
//				'name' => __('Top', 'pzucd'),
//				'id'      => $prefix . 'template-pager--heading-top',
//				'type'    => 'heading',
//				'default' => '',
//				'desc'    => __('', 'pzucd')
//			),
//			array(
//				'name' => __('Type', 'pzucd'),
//				'id'      => $prefix . 'template-top-pager-type',
//				'type'    => 'text',
//				'default' => '',
//				'desc'    => __('.', 'pzucd'),
//				'help'    => __('Top control type: Pagination, pager')
//			),
//			array(
//				'name' => __('Position', 'pzucd'),
//				'id'      => $prefix . 'template-top-pager-position',
//				'type'    => 'text',
//				'default' => '',
//				'desc'    => __('.', 'pzucd'),
//				'help'    => __('Top control position: Inside, Outside')
//			),
//
//
//		);
//
//		return $meta_box_template;
//	}


}

// EOC

add_filter('cmb_meta_boxes', 'pzucd_sections_preview_meta');
function pzucd_sections_preview_meta($meta_boxes = array())
{
  $prefix        = '_pzucd_';
  for ($i = 0; $i < 3; $i++)
  {
    $fields = array(
//    array(
//      'name'       => __('Short name', 'pzucd'),
//      'id'         => $prefix . $i.'-template-short-name',
//      'type'       => 'text',
//      'default'    => '',
//      'cols'       => 12,
//      'tooltip'    => __('', 'pzucd'),
//      'validation' => 'data-validation-engine="validate[required]"'
//    ),
//  );
//  $meta_boxes[ ] = array(
//    'title'   => 'Template sections preview',
//    'pages'   => 'ucd-templates',
//    'fields'  => $fields,
//    'context' => 'normal',
//  );
//
//  return $meta_boxes;
//}
//
//
//add_filter('cmb_meta_boxes', 'pzucd_sections_settings_metabox');
//function pzucd_sections_settings_metabox($meta_boxes = array())
//{
//  $prefix = '_pzucd_';
//
/////  make this agroup up to 3.
//
//  $fields        = array(
      array(
        'name'    => __('Cells per section', 'pzucd'),
        'id'      => $prefix . $i . '-template-cells-per-view',
        'type'    => 'pzspinner',
        'default' => '9',
        'min'     => 1,
        'max'     => 999,
        'cols'    => 2,
      ),
      array(
        'name'    => __('Cells across', 'pzucd'),
        'id'      => $prefix . $i . '-template-cells-across',
        'type'    => 'pzspinner',
        'default' => '3',
        'min'     => 1,
        'cols'    => 2,
        'max'     => 999,
      ),
      array(
        'name'    => __('Minimum cell width', 'pzucd'),
        'id'      => $prefix . $i . '-template-min-cell-width',
        'type'    => 'pzspinner',
        'alt'     => 'mincellw',
        'default' => 0,
        'min'     => '0',
        'max'     => 9999,
        'cols'    => 2,
        'step'    => '1',
        'suffix'  => 'px',
//      'desc'    => __('Set the minimum width for sells in this section. This helps with responsive layout', 'pzucd')
      ),

      array(
        'name'    => __('Cells vertical margin', 'pzucd'),
        'id'      => $prefix . $i . '-template-cells-vert-margin',
        'type'    => 'pzspinner',
        'alt'     => 'gutterv',
        'default' => '1',
        'min'     => '0',
        'max'     => '100',
        'cols'    => 3,
        'step'    => '1',
        'suffix'  => '%',
        //    'desc'    => __('Set the vertical gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzucd')
      ),
      array(
        'name'    => __('Cells horizontal margin', 'pzucd'),
        'id'      => $prefix . $i . '-template-cells-horiz-margin',
        'type'    => 'pzspinner',
        'alt'     => 'gutterh',
        'default' => '1',
        'min'     => '0',
        'max'     => '100',
        'cols'    => 3,
        'step'    => '1',
        'suffix'  => '%',
//      'desc'    => __('Set the horizontal gutter width as a percentage of the section width. The gutter is the gap between adjoining elements', 'pzucd')
      ),
      array(
        'id'       => $prefix . $i . '-sections-preview',
        'cols'     => 12,
        'type'     => 'pzlayout',
        'readonly' => false, // Readonly fields can't be written to by code! Weird
        'code'     => draw_sections_preview($i),
        'default'  => 'May not need anything',
        'desc'     => __('', 'pzucd')
      ),
      array(
        'id'      => $prefix . $i . '-template-section-cell-layout',
        'name'    => __('Cells layout', 'pzucd'),
        'type'    => 'text',
        'cols'    => 6,
        'default' => ''
      ),
      array(
        'name'    => __('Layout mode', 'pzucd'),
        'id'      => $prefix . $i . '-template-layout-mode',
        'type'    => 'pzselect',
        'default' => 'fitRows',
        'cols'    => 6,
        'options' => array(
          'fitRows'         => 'Fit rows',
          'fitColumns'      => 'Fit columns',
          'masonry'         => 'Masonry (Pinterest-like)',
          'masonryVertical' => 'Masonry Vertical',
          'cellsByRow'      => 'Cells by row',
          'cellsByColumn'   => 'Cells by column',
          'straightDown'    => 'Straight down',
          'straightAcross'  => 'Straight across',
        ),
        'desc'    => __('Choose how you want the cells to display. With evenly sized cells, you\'ll see little difference. Please visit <a href="http://isotope.metafizzy.co/demos/layout-modes.html" target=_blank>Isotope Layout Modes</a> for demonstrations of these layouts', 'pzucd')
      ),
    );
    $meta_boxes[ ] = array(
      'title'    => 'Section '.($i+1),
      'pages'    => 'ucd-templates',
      'context'  => 'normal',
      'priority' => 'high',
      'fields'         => $fields // An array of fields.
    );
  }

  return $meta_boxes;
}


add_filter('cmb_meta_boxes', 'pzucd_template_settings_metabox');
function pzucd_template_settings_metabox($meta_boxes = array())
{
  $prefix        = '_pzucd_';
  $fields        = array(
    array(
      'id'      => $prefix . 'template-criteria',
      'name'    => __('Criteria', 'pzucd'),
      'type'    => 'text',
      'cols'    => 12,
      'default' => ''
    ),
    array(
      'id'      => $prefix . 'template-controls',
      'name'    => __('Navigation', 'pzucd'),
      'type'    => 'pzselect',
      'cols'    => 12,
      'default' => 'pagination',
      'options' => array(
        'wpagination' => 'WP pagination',
        'pagenavi'    => 'PageNavi',
        'player'      => 'Media Player',
        'titles'      => 'Titles',
        'bullets'     => 'Bullets',
        'numbers'     => 'Numbers',
        'thumbs'      => 'Thumbnails'
      )
    ),
//    array(
//      'id' => $prefix.'template-other-thing',
//      'name' => __('Other thing','pzucd'),
//      'type' => 'text',
//      'cols' => 12,
//      'default' => ''
//    ),
    array(
      'id'      => $prefix . 'layout-set-save',
      'type'    => 'pzsubmit',
      'default' => 'Save'
    ),
  );
  $meta_boxes[ ] = array(
    'title'    => 'Template settings',
    'pages'    => 'ucd-templates',
    'fields'   => $fields,
    'context'  => 'side',
    'priority' => 'high'

  );

  return $meta_boxes;
}


function draw_sections_preview($section_number)
{
  $return_html = '';

  // Put in a hidden field with the plugin url for use in js
  $return_html = '
  <div id="pzucd-sections-preview" class="pzucd-sections pzucd-section-".$section_number>';
  $return_html .= '</div>
	  <p class="howto pzcentred"><strong style="color:#f00;">This is an example only and thus only a <span style="border-bottom: 3px double;">general guide</span> to how the cells will look.</strong></p>
	</div>
	';

  return $return_html;
}
