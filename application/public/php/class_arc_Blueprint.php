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
      // name is slug, need to get short name

      // strip out string text containment characters incase user enters them
      $this->name = str_replace(array('\'', '\"'), '', $name);

      self::get_blueprint();
      if (empty($this->blueprint['err_msg'])) {
        for ($i = 1; $i <= 3; $i++) {
          $this->blueprint[ 'section_object' ][ $i ] =
              arc_SectionFactory::create($i,
                                         $this->blueprint[ 'section' ][ ($i - 1) ],
                                         $this->blueprint[ '_blueprints_content-source' ],
                                         $this->blueprint[ '_blueprints_navigation' ],
                                         $this->blueprint[ '_blueprints_section-' . ($i - 1) . '-layout-mode' ],
                                         'slick', // Possible Future use
                                         $this->blueprint[ '_blueprints_section-' . ($i - 1) . '-title' ]
              );


        }
      }

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
          'meta_compare' => '='
      );
      global $_architect;
      // Get any existing copy of our transient data
      if ( !current_user_can( 'manage_options' ) && false === ( $blueprint_query = get_transient( 'pzarc_blueprint_query_'.$this->name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
        $blueprint_query = new WP_Query($meta_query_args);
        set_transient( 'pzarc_blueprint_query_'.$this->name, $blueprint_query, PZARC_TRANSIENTS_KEEP );
      } elseif (current_user_can( 'manage_options' )) {
        $blueprint_query = new WP_Query($meta_query_args);
      }


      if (!isset($blueprint_query->posts[ 0 ]->ID)) {

        $this->blueprint = array('err_msg' => '<p class="message-error">Architect Blueprint <strong>' . $this->name . '</strong> not found</p>');

        return $this->blueprint;

      }

      $this->blueprint[ 'blueprint-id' ] = $blueprint_query->posts[ 0 ]->ID;

      $blueprint_info = get_post_meta($blueprint_query->posts[ 0 ]->ID);
      foreach ($blueprint_info as $key => $value) {

        if ('_edit_lock' !== $key && '_edit_last' !== $key) {

          $this->blueprint[ $key ] = maybe_unserialize($blueprint_info[ $key ][ 0 ]);

        }

      }

      /** Add the default values except for the styling ones **/
      foreach ($_architect[ 'defaults' ][ '_blueprints' ] as $key => $value) {
        if ((strpos($key, '_blueprints_') === 0 || strpos($key, '_content_') === 0) && !isset($this->blueprint[ $key ])) {
          $this->blueprint[ $key ] = maybe_unserialize($value);
        };

      }

      /** Add panel settings for Section 1
      *************************************/
      $panel_id   = pzarc_convert_name_to_id($this->blueprint[ '_blueprints_section-0-panel-layout' ]);
      $panel[ 0 ] = get_post_meta($panel_id);

      $panel[ 1 ] = !$panel[ 0 ] ? array() : pzarc_flatten_wpinfo($panel[ 0 ]);

      if (!empty($panel[ 0 ])) {
        foreach ($_architect[ 'defaults' ][ '_panels' ] as $key => $value) {

          if (strpos($key, '_panel') === 0 && !isset($panel[ 1 ][ $key ])) {
            $panel[ 1 ][ $key ] = maybe_unserialize($value);
          };

        }
      }
      $this->blueprint[ 'section' ][ 0 ]
          = array(
          'section-enable'         => !empty($panel[ 0 ]),
          'section-panel-settings' => $panel[ 1 ],
          'section-rsid'           => 'rsid' . rand(1000, 9999),
          'section-panel-slug'     => $this->blueprint[ '_blueprints_section-0-panel-layout' ],
      );

      if (!$panel[ 0 ]) {

        $this->blueprint = array('err_msg' => '<p class="message-error">No Panel Layout assigned.</p>');

        return $this->blueprint;

      }

      /** Add panel settings for Section 2
      *************************************/
      if(!empty($this->blueprint[ '_blueprints_section-1-panel-layout' ])) {
        $panel_id   = pzarc_convert_name_to_id($this->blueprint[ '_blueprints_section-1-panel-layout' ]);
        $panel[ 0 ] = get_post_meta($panel_id);

        $panel[ 2 ] = !$panel[ 0 ] ? array() : pzarc_flatten_wpinfo($panel[ 0 ]);

        if (!empty($panel[ 0 ])) {
          foreach ($_architect[ 'defaults' ][ '_panels' ] as $key => $value) {

            if (strpos($key, '_panel') === 0 && !isset($panel[ 2 ][ $key ])) {
              $panel[ 2 ][ $key ] = maybe_unserialize($value);
            };

          }
        }
      } else {
        $panel[2] = '';
      }
      $this->blueprint[ 'section' ][ 1 ]
          = array(
          'section-enable'         => !empty($panel[ 0 ]),
          'section-panel-settings' => $panel[ 2 ],
          'section-rsid'           => 'rsid' . rand(1000, 9999),
          'section-panel-slug'     => $this->blueprint[ '_blueprints_section-1-panel-layout' ],

      );

      /** Add panel settings for Section 3
       *************************************/
      if ($this->blueprint[ '_blueprints_section-2-panel-layout' ]) {
      $panel_id   = pzarc_convert_name_to_id($this->blueprint[ '_blueprints_section-2-panel-layout' ]);
      $panel[ 0 ] = get_post_meta($panel_id);

      $panel[ 3 ] = !$panel[ 0 ] ? array() : pzarc_flatten_wpinfo($panel[ 0 ]);

      if (!empty($panel[ 0 ])) {
        foreach ($_architect[ 'defaults' ][ '_panels' ] as $key => $value) {

          if (strpos($key, '_panel') === 0 && !isset($panel[ 3 ][ $key ])) {
            $panel[ 3 ][ $key ] = maybe_unserialize($value);
          };

        }
      }
      } else {
        $panel[3] = '';
      }
      $this->blueprint[ 'section' ][ 2 ]
          = array(
          'section-enable'         => !empty($panel[ 0 ]),
          'section-panel-settings' => $panel[ 3 ],
          'section-rsid'           => 'rsid' . rand(1000, 9999),
          'section-panel-slug'     => $this->blueprint[ '_blueprints_section-2-panel-layout' ],

      );

      unset($panel);

      return true;
    }

    /***********************
     *
     * Get panel design
     *
     ***********************/
//    function get_panel_design($panel_layout_id)
//    {
//
//      $panel_design = get_post_meta($panel_layout_id, '_architect-panels_preview', true);
//
//      return $panel_design;
//
//    }

    function __destruct()
    {
    }

  }


