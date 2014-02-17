<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chrishoward
 * Date: 13/08/13
 * Time: 8:32 PM
 * To change this blueprint use File | Settings | File Blueprints.
 */


require_once PZARC_PLUGIN_PATH . '/frontend/php/class_pzarc_Display.php';
require PZARC_PLUGIN_PATH . '/frontend/php/arcPanelDefinitions.php';
require_once(PZARC_PLUGIN_PATH . '/external/php/jo-image-resizer/jo_image_resizer.php');


add_action('init','pzarc_display_init');
function pzarc_display_init() {
  wp_register_script('js-arc-frontjs',PZARC_PLUGIN_URL.'/frontend/js/arc-front.js', array('jquery'));
  wp_register_script('js-swiperjs',PZARC_PLUGIN_URL.'/external/js/swiper/idangerous.swiper.js');
  wp_register_style('css-swiperjs',PZARC_PLUGIN_URL.'/external/js/swiper/idangerous.swiper.css');

  wp_enqueue_script('js-arc-frontjs');
  wp_enqueue_script('js-swiperjs');
  wp_enqueue_style('css-swiperjs');
}


function pzarc_get_the_blueprint($blueprint)
{
//  wp_enqueue_script('jquery-isotope');

  // meed to return a structure for the panels, the content source, the navgation info


  global $wp_query;
  $original_query = $wp_query;
  $blueprint_info = new WP_Query('post_type=arc-blueprints&meta_key=_architect-blueprints_short-name&meta_value=' . $blueprint);
  var_dump($blueprint_info);
  if (!isset($blueprint_info->posts[ 0 ]))
  {
    echo '<p class="pzarc-oops">Architect Blueprint <strong>' . $blueprint . '</strong> not found</p>';

    return null;
  }
  $the_blueprint_meta = get_post_meta($blueprint_info->posts[ 0 ]->ID, null, true);

  $pzarc_blueprint = pzarc_flatten_wpinfo($the_blueprint_meta);
  //
  $pzarc_blueprint['blueprint-id']          = $blueprint_info->posts[ 0 ]->ID;

  $pzarc_blueprint[ 'section' ][ 0 ] =
          array(
                  'section-enable'             => true,
                  'section-panel-settings'      => pzarc_flatten_wpinfo(get_post_meta($pzarc_blueprint[ '_architect-blueprints_section-0-panel-layout' ])),
          );
  $pzarc_blueprint[ 'section' ][ 1 ] =
          array(
                  'section-enable'             => !empty($pzarc_blueprint[ '_architect-blueprints_section-1-enable' ]),
                  'section-panel-settings'      => (!empty($pzarc_blueprint[ '_architect-blueprints_section-1-enable' ])?pzarc_flatten_wpinfo(get_post_meta($pzarc_blueprint[ '_architect-blueprints_section-1-panel-layout' ])):null),
          );
  $pzarc_blueprint[ 'section' ][ 2 ] =
          array(
                  'section-enable'             => !empty($pzarc_blueprint[ '_architect-blueprints_section-2-enable' ]),
                  'section-panel-settings'      => (!empty($pzarc_blueprint[ '_architect-blueprints_section-2-enable' ])?pzarc_flatten_wpinfo(get_post_meta($pzarc_blueprint[ '_architect-blueprints_section-2-panel-layout' ])):null),
          );


  return $pzarc_blueprint;
}

function pzarc_get_panel_design($pzarc_panel_layout_id)
{

  $pzarc_panel_design = get_post_meta($pzarc_panel_layout_id, '_architect-panels_preview', true);

  return $pzarc_panel_design;

}


function pzarc_flatten_wpinfo($array_in)
{
  $array_out = array();
  foreach ($array_in as $key => $value)
  {
    if ($key=='_edit_lock' || $key=='_edit_last') {continue;}
    if (is_array($value))
    {
      $array_out[ $key ] = $value;
    }
    $array_out[ $key ] = $value[ 0 ];
  }

  return $array_out;
}

/***********************
 * Shortcode
 ***********************/
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

  return pzarc($pzarc_blueprint, (!empty($atts[ 'ids' ]) ? $atts[ 'ids' ] : null), true);

  //  return pzarc_render($pzarc_blueprint_arr, (!empty($atts[ 'ids' ]) ? $atts[ 'ids' ] : null), 'pzarc_Display');

}

/***********************
 * Template tag
 ***********************/
function pzarchitect($pzarc_blueprint = null, $pzarc_overrides = null){
  pzarc($pzarc_blueprint, $pzarc_overrides, false);
}

/***********************
/* Blueprint main display function */
/* Overrides is a list of ids */
function pzarc($pzarc_blueprint = null, $pzarc_overrides = null, $is_shortcode = false)
{

  if (empty($pzarc_blueprint))
  {
    // make this use a set of defaults. prob an excerpt grid
    return 'You need to set a blueprint';
  }
  $pzarc_blueprint_arr = pzarc_get_the_blueprint($pzarc_blueprint);
  $pzarc               = new pzarc_Display($pzarc_blueprint);
  if ($pzarc_blueprint_arr[ '_architect-content_general_content-source' ] == 'default' && $is_shortcode)
  {
    return 'Ooops! Need to specify a Contents Selection in your Blueprint to use a shortcode';
  }
  else
  {
    $pzarc->render($pzarc_blueprint_arr, $pzarc_overrides, $is_shortcode);

    return $pzarc->output;
  }
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

// Add body class by filter
add_filter('body_class','add_pzarc_class');
function add_pzarc_class($classes) {
  // add 'class-name' to the $classes array
  if (!in_array('pzarchitect', $classes)) {
    $classes[] = 'pzarchitect';
  }
  // return the $classes array
  return $classes;
}
