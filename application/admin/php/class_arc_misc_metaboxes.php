<?php

  /**
   * Project pizazzwp-architect.
   * File: class-arc_Misc_Metaboxes.php
   * User: chrishoward
   * Date: 29/09/14
   * Time: 4:23 PM
   */
  class arc_Misc_metaboxes
  {

    public $redux_opt_name = '_architect';


    /**
     * [__construct description]
     */
    function __construct()
    {
      add_action('admin_init',array($this,'init'));
      add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this, "pzarc_add_fvid_metaboxes"), 10, 1);
    }

    function init() {
      wp_enqueue_script( 'jquery-pzarc-metaboxes', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes.js', array( 'jquery' ), true );

    }
    /** Featured Video */

    function pzarc_add_fvid_metaboxes($metaboxes)
    {
      // Declare your sections
      global $_architect_options;
      $pzarc_vids_on = array();
      if ($_architect_options[ 'architect_mod-video-fields' ][ 'post' ] == 1) {
        $pzarc_vids_on[ ] = 'post';
      }
      if ($_architect_options[ 'architect_mod-video-fields' ][ 'page' ] == 1) {
        $pzarc_vids_on[ ] = 'page';
      }
      if ($_architect_options[ 'architect_mod-video-fields' ][ 'pz_snippets' ] == 1) {
        $pzarc_vids_on[ ] = 'pz_snippets';
      }
      $boxSections    = array();
      $boxSections[ ] = array(
        //'title'         => __('General Settings', 'pzarchitect'),
        //'icon'          => 'el-icon-home', // Only used with metabox position normal or advanced
        'fields' => array(
            array(
                'id'    => 'pzarc_features-video',
                'title' => __('Code', 'pzarchitect'),
                'desc'  => __('Enter the URL, embed code or a video shortcode.', 'pzarchitect'),
                'type'  => 'textarea',
                'rows'  => 2
            ),
        ),
      );

      // Declare your metaboxes
      $metaboxes[ ] = array(
          'id'         => 'pzarc_mb-featured-video',
          'title'      => __('Featured video', 'pzarchitect'),
          'post_types' => $pzarc_vids_on,
          'position'   => 'normal', // normal, advanced, side
          'priority'   => 'default', // high, core, default, low - Priorities of placement
          'sections'   => $boxSections,
          'sidebar'    => false
      );

      return $metaboxes;
    }




  }

