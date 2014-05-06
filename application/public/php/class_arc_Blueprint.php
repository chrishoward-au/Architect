<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 25/04/2014
   * Time: 9:11 PM
   */

  /**
   * Class pzarc_Blueprint
   * Purpose: Creates the blueprint object
   */
  class arc_Blueprint
  {

    public $name;
    public $blueprint;

    function __construct($name)
    {
      // strip out string text containment characters incase user enters them
      $this->name = str_replace(array('\'', '\"'), '', $name);
      self::get_blueprint();
    }

    function get_blueprint()
    {

      // meed to return a structure for the panels, the content source, the navgation info

      global $wp_query;
      $original_query = $wp_query;
//  $blueprint_info = new WP_Query('post_type=arc-blueprints&meta_key=_architect-blueprints_short-name&meta_value=' . $blueprint);

      $meta_query_args = array(
          'post_type'    => 'arc-blueprints',
          'meta_key'     => '_architect',
          'meta_value'   => '"' . $this->name . '"',
          'meta_compare' => 'LIKE'
      );
      $blueprint_info  = new WP_Query($meta_query_args);

      if (!isset($blueprint_info->posts[ 0 ]))
      {
        $this->blueprint = array('err_msg' => '<p class="pzarc-oops">Architect Blueprint <strong>' . $this->name . '</strong> not found</p>');
        return $this->blueprint;
      }
      $this->blueprint = get_post_meta($blueprint_info->posts[ 0 ]->ID, '_architect', true);

      // Need to add in default values for blueprints
      global $pzarchitect;
      foreach ($pzarchitect['defaults']['_blueprints'] as $key => $value) {
        if ((strpos($key,'_blueprints_')===0 || strpos($key,'_content_')===0 ) && !isset($this->blueprint[$key])) {
          $this->blueprint[$key] = $value;
        }
      }
      $this->blueprint[ 'blueprint-id' ] = $blueprint_info->posts[ 0 ]->ID;

      /************************************/
      // Add panel settings for Section 1
      $panel = get_post_meta($this->blueprint[ '_blueprints_section-0-panel-layout' ], '_architect', true);

      // Add default values for panels
      foreach ($pzarchitect['defaults']['_panels'] as $key => $value) {
        if (strpos($key,'_panels_')===0  && !isset($panel[$key])) {
           $panel[$key] = $value;
        }
      }
      foreach ($panel as $key => $value) {
        if (strpos($key, '_panels_styling') === 0) {
  //        unset($panel[ $key ]);
        }
      }

      $this->blueprint[ 'section' ][ 0 ]
          = array(
          'section-enable'         => true,
          'section-panel-settings' => $panel,
      );

      if (!isset($this->blueprint[ 'section' ][ 0 ][ 'section-panel-settings' ]))
      {
        $this->blueprint = array('err_msg' => '<p class="pzarc-oops">No Panel Layout assigned.</p>');
        return $this->blueprint;
      }

      // TODO:Setup check for navigator to save little time
      /************************************/
      // Add panel settings for Section 2
      $panel = get_post_meta($this->blueprint[ '_blueprints_section-1-panel-layout' ], '_architect', true);
      foreach ($pzarchitect as $key => $value) {
        if (strpos($key,'_panels_')===0  && !isset($panel[$key])) {
          $panel[$key] = $value;
        }
      }
      $this->blueprint[ 'section' ][ 1 ]
          = array(
          'section-enable'         => !empty($this->blueprint[ '_blueprints_section-1-enable' ]),
          'section-panel-settings' => (!empty($this->blueprint[ '_blueprints_section-1-enable' ]) ? $panel : null),
      );

      /************************************/
      // Add panel settings for Section 3
      $panel = get_post_meta($this->blueprint[ '_blueprints_section-2-panel-layout' ], '_architect', true);
      foreach ($pzarchitect as $key => $value) {
        if (strpos($key,'_panels_')===0  && !isset($panel[$key])) {
          $panel[$key] = $value;
        }
      }
      $this->blueprint[ 'section' ][ 2 ]
          = array(
          'section-enable'         => !empty($this->blueprint[ '_blueprints_section-2-enable' ]),
          'section-panel-settings' => (!empty($this->blueprint[ '_blueprints_section-2-enable' ]) ? $panel : null),
      );

     // return $this->blueprint;
      return true;
    }

    //TODO: Don't think we need either of these methods
    /***********************
     *
     * Get panel design
     *
     ***********************/
    function get_panel_design($panel_layout_id)
    {

      $panel_design = get_post_meta($panel_layout_id, '_architect-panels_preview', true);

      return $panel_design;

    }

    /***********************
     *
     * Flatten wp arrays if necessary
     *
     ***********************/

    static function flatten_wpinfo($array_in)
    {
      $array_out = array();
      foreach ($array_in as $key => $value)
      {
        if ($key == '_edit_lock' || $key == '_edit_last')
        {
          continue;
        }
        if (is_array($value))
        {
          $array_out[ $key ] = $value;
        }
        $array_out[ $key ] = $value[ 0 ];
      }

      return $array_out;
    }

  }


