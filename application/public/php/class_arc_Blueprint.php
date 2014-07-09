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

    /**
     * get_blueprint()
     *
     * @return bool
     */
    function get_blueprint()
    {

      // meed to return a structure for the panels, the content source, the navgation info


      $meta_query_args = array(
          'post_type'    => 'arc-blueprints',
          'meta_key'     => '_blueprints_short-name',
          'meta_value'   => $this->name,
          'meta_compare' => 'LIKE'
      );

      $blueprint_id    = new WP_Query($meta_query_args);

      if (!isset($blueprint_id->posts[ 0 ]->ID)) {

        $this->blueprint = array('err_msg' => '<p class="message-error">Architect Blueprint <strong>' . $this->name . '</strong> not found</p>');

        return $this->blueprint;

      }

      $this->blueprint[ 'blueprint-id' ] = $blueprint_id->posts[ 0 ]->ID;

      $blueprint_info = get_post_meta($blueprint_id->posts[ 0 ]->ID, null, true);

      foreach ($blueprint_info as $key => $value) {

        if ('_edit_lock' !== $key && '_edit_last' !== $key) {

          $this->blueprint[ $key ] = maybe_unserialize($blueprint_info[ $key ][ 0 ]);

        }

      }

      /** Add panel settings for Section 1 */
      $panel[ 0 ]  = get_post_meta($this->blueprint[ '_blueprints_section-0-panel-layout' ]);

      $panel[ 1 ] = !$panel[ 0 ]?array():self::flatten_wpinfo($panel[0]);

      $this->blueprint[ 'section' ][ 0 ]
          = array(
          'section-enable'         => !empty($panel[0]),
          'section-panel-settings' => $panel[ 1 ],
      );

      if (!$panel[0]) {

        $this->blueprint = array('err_msg' => '<p class="message-error">No Panel Layout assigned.</p>');

        return $this->blueprint;

      }

      /** Add panel settings for Section 2 */
      $panel[ 0 ]  = get_post_meta($this->blueprint[ '_blueprints_section-1-panel-layout' ]);

      $panel[ 2 ] = !$panel[ 0 ]?array():self::flatten_wpinfo($panel[0]);

      $this->blueprint[ 'section' ][ 0 ]
          = array(
          'section-enable'         => !empty($panel[0]),
          'section-panel-settings' => $panel[ 2 ],
      );

      /** Add panel settings for Section 3 */
      $panel[ 0 ]  = get_post_meta($this->blueprint[ '_blueprints_section-2-panel-layout' ]);

      $panel[ 3 ] = !$panel[ 0 ]?array():self::flatten_wpinfo($panel[0]);

      $this->blueprint[ 'section' ][ 0 ]
          = array(
          'section-enable'         => !empty($panel[0]),
          'section-panel-settings' => $panel[ 3 ],
      );

      unset($panel);

      return true;
    }

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
      foreach ($array_in as $key => $value) {
        if ($key == '_edit_lock' || $key == '_edit_last') {
          continue;
        }
        if (is_array($value)) {
          $array_out[ $key ] = $value;
        }
        $array_out[ $key ] = maybe_unserialize($value[ 0 ]);
      }

      return $array_out;
    }

  }


