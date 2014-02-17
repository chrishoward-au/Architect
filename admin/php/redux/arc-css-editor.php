<?php
/**
 * Created by PhpStorm.
 * User: chrishoward
 * Date: 15/02/2014
 * Time: 1:03 PM
 */

$redux_opt_name = '_architect';



add_action("redux/metaboxes/{$redux_opt_name}/boxes", 'pzarc_css_editor');
function pzarc_css_editor($meta_boxes = array())
{
  $prefix      = 'css_editor_';
  $sections    = array();
  $sections[ ] = array(
          'title'      => __('Typography', 'pzarc'),
          'show_title' => true,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-fontsize',
          'fields'     => array(
                  array(
                          'id'             => $prefix . 'typography',
                          'type'           => 'typography',
                          'title'          => __('Typography', 'redux-framework-demo'),
                          //'compiler'=>true, // Use if you want to hook in your own CSS compiler
                          'google'         => true, // Disable google fonts. Won't work if you haven't defined your google api key
                          'font-backup'    => true, // Select a backup non-google font in addition to a google font
                          'font-style'     => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                          'subsets'        => false, // Only appears if google is true and subsets not set to false
                          'font-size'      => true,
                          'line-height'    => true,
                          'word-spacing'   => true, // Defaults to false
                          'letter-spacing' => true, // Defaults to false
                          'color'          => true,
                          //'preview'=>false, // Disable the previewer
                          'all_styles'     => true, // Enable all Google Font style/weight variations to be added to the page
                          'output'         => array('h2.site-description'), // An array of CSS selectors to apply this font style to dynamically
                          'compiler'       => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                          'units'          => 'px', // Defaults to px
                          'subtitle'       => __('Typography option with each property can be called individually.', 'redux-framework-demo'),
                          'default'        => array(
                                  'color'       => "#333",
                                  'font-style'  => '700',
                                  'font-family' => 'Abel',
                                  'google'      => true,
                                  'font-size'   => '33px',
                                  'line-height' => '40px'),
                  ),
          )
  );
  $sections[ ] = array(
          'title'      => __('Box', 'pzarc'),
          'show_title' => true,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-th-large',
          'fields'     => array(
                  array(
                          'id'       => $prefix . 'background',
                          'type'     => 'background',
                          'title'    => __('Body Background', 'redux-framework-demo'),
                          'subtitle' => __('Body background with image, color, etc.', 'redux-framework-demo'),
                          'desc'     => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                          'default'  => array(
                                  'background-color' => '#1e73be',
                          )
                  ),
                  array(
                          'id'       => $prefix . 'border',
                          'type'     => 'border',
                          'title'    => __('Border Option', 'redux-framework-demo'),
                          'subtitle' => __('Only color validation can be done on this field type', 'redux-framework-demo'),
                          'output'   => array('.site-header'),
                          'desc'     => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                          'default'  => array(
                                  'border-color'  => '#1e73be',
                                  'border-style'  => 'solid',
                                  'border-top'    => '3px',
                                  'border-right'  => '3px',
                                  'border-bottom' => '3px',
                                  'border-left'   => '3px'
                          )

                  )

          )
  );
  $sections[ ] = array(
          'title'      => __('Layout', 'pzarc'),
          'show_title' => true,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-website',
          'fields'     => array(
                  array(
                          'id'       => 'opt_dimensions',
                          'type'     => 'dimensions',
                          'units'    => array('em', 'px', '%'),
                          'title'    => __('Dimensions (Width/Height) Option', 'redux-framework-demo'),
                          'subtitle' => __('Allow your users to choose width, height, and/or unit.', 'redux-framework-demo'),
                          'desc'     => __('You can enable or disable any piece of this field. Width, Height, or Units.', 'redux-framework-demo'),
                          'default'  => array(
                                  'Width'  => '200',
                                  'Height' => '100'
                          ),
                  ),
          )
  );
  $sections[ ] = array(
          'title'      => 'Help',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-info-sign',
          'fields'     => array(

                  array(
                          'title' => __('CSS Editor', 'pzarc'),
                          'id'    => $prefix . 'css-editor-help',
                          'type'  => 'info',
                          'desc'  => '<p>
                              Fiant nulla claritatem processus vulputate quarta. Anteposuerit eodem habent parum id et. Notare mutationem facilisi nulla ut facer.
                              </p>

                              <p>
                              Nam minim quis est typi nostrud. Et nunc in legere dignissim decima. Feugiat facilisi nulla lectores quod esse.
                              </p>

                              <p>
                              Nostrud ipsum usus nam ut magna. Zzril nobis qui est nonummy in. Nonummy seacula dolore amet ipsum decima.
                              </p>

                              <p>
                              Nibh cum lorem iriure laoreet ut. Nihil in vel diam sit iusto. Eorum tempor ea zzril dynamicus consuetudium.
                              </p>

                              <p>
                              Ut at consectetuer blandit nibh in.
                              </p>'

                  )
          )
  );


  $meta_boxes[ ] = array(
          'id'         => 'arc-css-editor',
          'title'      => 'CSS Editor',
          'post_types' => array('arc-layouts'),
          'sections'   => $sections,
          'position'   => 'normal',
          'priority'   => 'high', // high, core, default, low - Priorities of placement
          'sidebar'    => false

  );


  return $meta_boxes;

}
