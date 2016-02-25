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
      $this->blueprint['parent-page-url']=get_the_permalink();

      if (empty($this->blueprint[ 'err_msg' ])) {
//        for ($i = 1; $i <= 3; $i++) {
        $i = 1;
        switch ($this->blueprint[ '_blueprints_section-' . ($i - 1) . '-layout-mode' ]) {
          case 'table':
            $titles = $this->blueprint[ '_blueprints_section-' . ($i - 1) . '-table-column-titles' ];
            break;
          case 'accordion':
            $titles = $this->blueprint[ '_blueprints_section-' . ($i - 1) . '-accordion-titles' ];
            break;
          default:
            $titles = '';
        }
//          if (!empty($this->blueprint[ '_blueprints_section-' . ($i - 1) . '-panel-layout' ])) {
        $this->blueprint[ 'section_object' ][ $i ] =
            arc_SectionFactory::create($i,
                                       $this->blueprint[ 'section' ][ ($i - 1) ],
                                       $this->blueprint[ '_blueprints_content-source' ],
                                       $this->blueprint[ '_blueprints_navigator' ],
                                       $this->blueprint[ '_blueprints_section-' . ($i - 1) . '-layout-mode' ],
                                       (empty($this->blueprint[ '_blueprints_slider-engine' ])|| $this->blueprint[ '_blueprints_slider-engine' ]==='slick15' ? 'slick':$this->blueprint[ '_blueprints_slider-engine' ]), // Possible Future use
//                                       $this->blueprint[ '_blueprints_section-' . ($i - 1) . '-title' ],
                                       $titles,
                                       $this->blueprint[ '_blueprints_short-name' ],
                                       $this->blueprint

            );
        unset($this->blueprint[ 'section' ]);
//            var_dump($i,            $this->blueprint[ 'section_object' ][ $i ]);
      }

      //      }
      //}

      // Let others add ther own defaults
      $this->blueprint = apply_filters('arc-load-blueprint', $this->blueprint);
      pzdb('bottom of blueprint construct');
    }


    /**
     * get_blueprint()
     *
     * @return bool
     */
    function get_blueprint()
    {
      pzdb('top get blueprint');
      // meed to return a structure for the panels, the content source, the navgation info
      // This is added to support Shortcake which returns an ID rather than the shortname
      if (is_numeric($this->name)) {
        $meta_query_args = array(
            'post_type'    => 'arc-blueprints',
            'include'   => $this->name,
        );
      } else {
        $meta_query_args = array(
            'post_type'    => 'arc-blueprints',
            'meta_key'     => '_blueprints_short-name',
            'meta_value'   => $this->name,
            'meta_compare' => '='
        );
      }
      $bp              = get_posts($meta_query_args);
      if (isset($bp[ 0 ])) {
        $this->bp = pzarc_flatten_wpinfo(get_post_meta($bp[ 0 ]->ID));
        // Do we need this still? Yes! Because Redux doesn't store defaults
        // True excludes styling
      // V1.8 Started storing defaults in an option
     //   pzarc_get_defaults(true);

        global $_architect_options, $_architect;
        $pzarc_architect_defaults = maybe_unserialize( get_option( '_architect_defaults' ) );
        if (empty($pzarc_architect_defaults)) {
          pzarc_set_defaults();
          $pzarc_architect_defaults = maybe_unserialize( get_option( '_architect_defaults' ) );
        }
        //var_dump($pzarc_architect_defaults);
        $this->blueprint          = array_replace_recursive($pzarc_architect_defaults, $this->bp);
        $this->blueprint[ 'uid' ] = 'uid' . time() . rand(1000, 9999);

//        if ( ! empty( $_architect_options[ 'architect_enable_query_cache' ] ) && ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'edit_others_pages' ) ) && false === ( $blueprint_query = get_transient( 'pzarc_blueprint_query_' . $this->name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
        $blueprint_query = new WP_Query($meta_query_args);
//          set_transient( 'pzarc_blueprint_query_' . $this->name, $blueprint_query, PZARC_TRANSIENTS_KEEP );
//        } elseif ( current_user_can( 'edit_others_pages' ) || empty( $_architect_options[ 'architect_enable_query_cache' ] ) ) {
//          $blueprint_query = new WP_Query( $meta_query_args );
//        } else {
//          // we need to put something here!
//
//        }


      }

      if (!isset($bp[ 0 ]) || !isset($blueprint_query->posts[ 0 ]->ID)) {

        $this->blueprint = array('err_msg' => '<p class="message-error">Architect Blueprint <strong>' . $this->name . '</strong> not found</p>');

        return $this->blueprint;

      }

      $this->blueprint[ 'blueprint-id' ] = $blueprint_query->posts[ 0 ]->ID;
      global $arc_blueprint_id;
      $arc_blueprint_id = $this->blueprint[ 'blueprint-id' ];
      $blueprint_info = get_post_meta($blueprint_query->posts[ 0 ]->ID);
      foreach ($blueprint_info as $key => $value) {

        if ('_edit_lock' !== $key && '_edit_last' !== $key && strpos($key, '_blueprints_styling_') !== 0) {
          $this->blueprint[ $key ] = maybe_unserialize($value[ 0 ]);
        }

      }

      /** Add the default values except for the styling ones **/
      foreach ($pzarc_architect_defaults as $key => $value) {
        if ((strpos($key, '_blueprints_') === 0 || strpos($key, '_content_') === 0) && !isset($this->blueprint[ $key ])) {
          $this->blueprint[ 'panels' ][ $key ] = maybe_unserialize($value);
        };

      }

//      /** Add panel settings for Section 1
//      *************************************/
// TODO: START HERE. WITH PANELS IN BLUEPRINTS, THERE CAN BE NO SECTIONS.
// YET ONE OF THE BEAUTIIES OF SECTIONS IS WHEN YOU NEED TO CONTINUE. WITHOUT IT YOU'LL NEED TO USE SKIP POSTS
      $panel[ 1 ] = array();
      foreach ($this->blueprint as $key => $value) {

        if (strpos($key, '_panel') === 0 && !isset($panel[ 1 ][ $key ]) && strpos($key, '_panels_styling_') !== 0) {
          $panel[ 1 ][ $key ] = maybe_unserialize($value);
          unset($this->blueprint[ $key ]);
        };

      }

      $this->blueprint[ 'section' ][ 0 ]
          = array(
          'section-enable'         => true,
          'section-panel-settings' => $panel[ 1 ],
          'section-rsid'           => 'rsid' . time() . rand(1000, 9999),
          'section-panel-slug'     => 'bp-panel',
      );
      unset($panel);
      unset($this->bp);

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


