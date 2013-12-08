<?php
/**
 * Created by PhpStorm.
 * User: chrishoward
 * Date: 8/12/2013
 * Time: 12:51 PM
 */

class ucdGallery extends pzucd_Display
{

  function __construct($pzucd_template)
  {
    parent::__construct($pzucd_template);
  }

  // Display gallery layouts when called from shortcode

  // Overrides WP Gallery
  function pzucd_gallery()
  {

    return 'Substituted';
  }

  function build_cell($post_info)
  {
    $cell_info = pzucd_flatten_wpinfo($this->section_info[ 'section-cell-settings' ]);
    //pzdebug($cell_info);
    $cell_width     = 100 / $this->section_info[ 'section-cells-across' ] - $this->section_info[ 'section-cells-vert-margin' ];
    $cell_min_width = $this->section_info[ 'section-min-cell-width' ];
    // thuis may need to be in its own method
    $this->output .= '<div class="pzucd-cell" style="position:relative;width:' . $cell_width . '%;margin:' . ($this->section_info[ 'section-cells-vert-margin' ] / 2) . '%;min-width:' . $cell_min_width . 'px">';
    $position = 'relative';
    if ($cell_info[ '_pzucd_layout-background-image' ] == 'fill')
    {
      $this->output .= '<div class="pzucd_bg_image"><img class="entry-image" src="' . $post_info->guid . '"></div>';
      $position = 'absolute';
    }
    $layout                  = json_decode($cell_info[ '_pzucd_layout-cell-preview' ], true);
    $the_inputs[ 'title' ]   = $post_info->post_title;
    $the_inputs[ 'excerpt' ] = $post_info->post_excerpt;
    $the_inputs[ 'image' ]   = '<img class="entry-image" src="' . $post_info->guid . '">';
    // this needs its ownmethod
    $components_open         = '<div class="pzucd-components" style="position:' . $position . ';' . $cell_info[ '_pzucd_layout-sections-position' ] . ':'.$cell_info[ '_pzucd_layout-nudge-section-y' ].'%;width:' . $cell_info[ '_pzucd_layout-sections-widths' ] . '%;background:rgba(255,255,255,0.8);">';
    $components_close        = '</div><!-- End components -->';
    $components              = pzucd_build_components($components_open, $the_inputs, $layout, $components_close, $cell_info);
    $this->output .= $components . '</div><!-- end cell -->';
  }

  function get_source($criteria, $overrides = null)
  {
    if (empty($overrides))
    {
      parent::get_source($criteria, $overrides);
    }
    else
    {
      $this->source_data = explode(',', $overrides);
    }
  }

  function build_query()
  {
    if ($this->source_data)
    {
      $this->query_vars[ 'post__in' ] = $this->source_data;
    }
    $this->query_vars[ 'post_status' ]         = array('publish', 'inherit');
    $this->query_vars[ 'post_type' ]           = 'attachment';
    $this->query_vars[ 'ignore_sticky_posts' ] = 1;

  }
}

function pzucd_gallery_shortcode($atts, $content = null, $tag)
{


  //add_filter('post_gallery', array($this,'pzucd_gallery'));
  return pzucd_render(pzucd_get_the_template($atts[ 'template' ]), (!empty($atts[ 'ids' ]) ? $atts[ 'ids' ] : null), 'ucdGallery');


}

add_shortcode('ucdgallery', 'pzucd_gallery_shortcode');
