  <?php

    /**
     * Created by PhpStorm.
     * User: chrishoward
     * Date: 15/12/2013
     * Time: 10:02 PM
     */
    /*
    * class for displaying content
    * this captures the content and returns it, so needs an echo statemnet to display in blueprints
    * this is necessary to work in a shortcode
    *
    */

    /**
     * Class Architect
     *
     * @method: __construct($blueprint, $is_shortcode)
     * @method: build($overrides)
     * @method: loop($section)
     * @method: query($source, $criteria, $overrides)
     *
     * @properties: $build, $panel_def, $arc, $query, $is_shortcode
     *
     */
    class Architect
    {

      public $build;
      private $panel_def;
      private $arc;
      private $query;
      private $is_shortcode;


      /**
       * @param $blueprint
       * @param $is_shortcode
       */
      public function __construct($blueprint, $is_shortcode)
      {
        $this->is_shortcode = $is_shortcode;

        self::set_defaults();
        require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Blueprint.php');
        $this->build = new arc_Blueprint($blueprint);
        if ($this->build->blueprint[ '_blueprints_content-source' ] == 'defaults' && $this->is_shortcode) {
          $this->build->blueprint[ 'err_msg' ] = 'Ooops! Need to specify a Contents Selection in your Blueprint to use a shortcode';
        }
        if (!empty($this->build->blueprint[ 'err_msg' ])) {
          echo $this->build->blueprint[ 'err_msg' ];

          return false;
        }


        // Good to go. Load all the classes
        require_once(PZARC_PLUGIN_PATH . '/resources/includes/php/arc-functions.php');
        require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Section.php');
        require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Navigator.php');
        require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Pagination.php');
        require_once PZARC_PLUGIN_PATH . '/public/php/interface_arc_PanelDefinitions.php';
        return false;
      }

      static function set_defaults()
      {
        // TODO: Remove this once Dovy fixes MB defaults
        require_once PZARC_PLUGIN_PATH . '/admin/php/class_arc_Panels_Layouts.php';
        require_once PZARC_PLUGIN_PATH . '/admin/php/class_arc_Blueprints_Layouts.php';
        global $pzarchitect;
        $pzarchitect[ 'defaults' ][ 'blueprints' ] = array();
        // Note: This is a cool trick but won't work in PHP < 5.4. So need Redux to get defaults working again or need to recode to longer form
        $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout_general' ]  = pzarc_blueprint_layout_general($pzarchitect[ 'defaults' ][ 'blueprints' ])[ 0 ][ 'sections' ];
        $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_content_general' ] = pzarc_blueprint_content_general($pzarchitect[ 'defaults' ][ 'blueprints' ])[ 0 ][ 'sections' ];
        $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout' ]          = pzarc_blueprint_layout($pzarchitect[ 'defaults' ][ 'blueprints' ])[ 0 ][ 'sections' ];
        $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_contents_metabox' ]          = pzarc_contents_metabox($pzarchitect[ 'defaults' ][ 'blueprints' ])[ 0 ][ 'sections' ];

        $pzarchitect[ 'defaults' ][ 'panels' ][ '_panel_general_settings' ] = pzarc_panel_general_settings($pzarchitect[ 'defaults' ][ 'panels' ])[ 0 ][ 'sections' ];
        $pzarchitect[ 'defaults' ][ 'panels' ][ '_panels_design' ]          = pzarc_panels_design($pzarchitect[ 'defaults' ][ 'panels' ])[ 0 ][ 'sections' ];
        $pzarchitect[ 'defaults' ][ 'panels' ][ '_panels_styling' ]         = pzarc_panels_styling($pzarchitect[ 'defaults' ][ 'panels' ])[ 0 ][ 'sections' ];

        foreach ($pzarchitect[ 'defaults' ][ 'blueprints' ] as $key1 => $value1) {
          foreach ($value1 as $key2 => $value2) {
            foreach ($value2 as $key3 => $fields) {
              if (is_array($fields)) {
                foreach ($fields as $key4 => $field) {
                  if (isset($field[ 'id' ])) {
                    $pzarchitect[ 'defaults' ][ '_blueprints' ][ $field[ 'id' ] ] = $field[ 'default' ];
                  }
                }
              }
            }
          }
        }

        unset($pzarchitect[ 'defaults' ][ 'blueprints' ]);

        foreach ($pzarchitect[ 'defaults' ][ 'panels' ] as $key1 => $value1) {
          foreach ($value1 as $key2 => $value2) {
            foreach ($value2 as $key3 => $fields) {
              if (is_array($fields)) {
                foreach ($fields as $key4 => $field) {
                  if (isset($field[ 'id' ])) {
                    $pzarchitect[ 'defaults' ][ '_panels' ][ $field[ 'id' ] ] = $field[ 'default' ];
                  }
                }
              }
            }
          }
        }
        unset($pzarchitect[ 'defaults' ][ 'panels' ]);

      }

      /**
       * @param $overrides
       */
      public function build($overrides)
      {
        do_action('arc_before_architect');
        echo '<div class="pzarchitect pzarc-blueprint_'.$this->build->blueprint['_blueprints_short-name'].'">';
        echo '<h1>START BUILD</h1>';

        $this->arc = array();
        $criteria  = array();

        $criteria[ 'panels_to_show' ] = $this->build->blueprint[ '_blueprints_section-0-panels-per-view' ];

        // hmm? if nopaging is true, it returns all records. Do we ever need that? Maybe we could manage pagination ourself then?!
        // $criteria[ 'nopaging' ] = ($this->build->blueprint['_blueprints_navigation']=='none');

        $criteria[ 'ignore_sticky_posts' ] = !$this->build->blueprint[ '_blueprints_sticky' ];
        $criteria[ 'offset' ]              = $this->build->blueprint[ '_blueprints_skip' ];

        $do_section_2 = ($this->build->blueprint[ 'section' ][ 1 ][ 'section-enable' ] && $this->build->blueprint[ '_blueprints_navigation' ] != 'navigator');
        if ($do_section_2) {
          $criteria[ 'panels_to_show' ] .= $this->build->blueprint[ '_blueprints_section-1-panels-per-view' ];
        }

        $do_section_3 = ($this->build->blueprint[ 'section' ][ 2 ][ 'section-enable' ] && $this->build->blueprint[ '_blueprints_navigation' ] != 'navigator');
        if ($do_section_3) {
          $criteria[ 'panels_to_show' ] .= $this->build->blueprint[ '_blueprints_section-2-panels-per-view' ];
        }


        if ($this->build->blueprint[ '_blueprints_content-source' ] != 'defaults') {
          $prefix = '';
          switch ($this->build->blueprint[ '_blueprints_content-source' ]) {
            case 'post':
              $prefix = '_content_posts_';
              break;
            case 'page':
              $prefix = '_content_pages_';
              break;
            case 'galleries':
              $prefix = '_content_galleries_';
              break;
            case 'slides':
              $prefix = '_content_slides_';
              break;
            case 'cpt':
              $prefix = '_content_cpt_';
              break;
          }
          foreach ($this->build->blueprint as $key => $value) {
            if (strpos($key, $prefix) === 0) {
              $criteria[ $key ] = $value;
            }
          }
          $this->query = self::query($this->build->blueprint[ '_blueprints_content-source' ], $criteria, $overrides);
        }
        else {
          global $wp_query;
          $this->query = $wp_query;
          // This may not do anything since the query may not update!
          // need to set nopaging too
          $this->query->query_vars[ 'nopaging' ]       = $criteria[ 'nopaging' ];
          $this->query->query_vars[ 'posts_per_page' ] = $criteria[ 'panels_to_show' ];
        }


        // Loops
        self::loop(1);
//        if ($do_section_2) {
//          self::loop(2);
//        }
//
//        if ($do_section_3) {
//          self::loop(3);
//        }
        // End loops

        if ($this->build->blueprint[ '_blueprints_navigation' ] == 'navigator' && $this->build->blueprint[ '_blueprints_navigator-location' ] == 'outside') {
          $this->arc[ 'navigator' ] = new arc_Navigator();
        }
        elseif ($this->build->blueprint[ '_blueprints_navigation' ] == 'pagination') {
          $this->arc[ 'pagination' ] = new arc_Pagination('WP');
        }

        if (isset($this->arc[ 'navigator' ])) {
          $this->arc[ 'navigator' ]->render();
        }
        if (isset($this->arc[ 'pagination' ])) {
          $this->arc[ 'pagination' ]->render();
        }

        echo '</div> <!-- end architect -->';
        do_action('arc_after_architect');
        echo '<h1>END BUILD</h1>';

      }

      /**
       * @param $section_no
       */
      private function loop($section_no)
      {
        $section[ $section_no ] = arc_SectionFactory::create($section_no, $this->build->blueprint[ 'section' ][ ($section_no - 1) ], $this->build->blueprint[ '_blueprints_content-source' ]);

        $class     = 'arc_PanelDef_' . $this->build->blueprint[ '_blueprints_content-source' ];
        $panel_def = self::build_meta_definition($class::panel_def(), $this->build->blueprint[ 'section' ][ ($section_no - 1) ][ 'section-panel-settings' ]);

        while ($this->query->have_posts()) {
          $this->query->the_post();
          global $pzinc;
          echo "<h1>$pzinc</h1>";
          $section[ $section_no ]->render_panel($panel_def);
        }

        // Unsetting causes it to run the destruct, which closes the div!
        unset($section[ $section_no ]);

      }

      /*************************************************
       *
       * Method: build_panel_definition
       *
       * Purpose: build meta defs
       *
       * Parameters:
       *
       * Returns:
       *
       *************************************************/
      function build_meta_definition($panel_def, $section_panel_settings)
      {

        $meta = array_pad(array(), 3, null);
        foreach ($meta as $key => $value) {
          $i                        = $key + 1;
          $meta[ $key ]             = preg_replace('/%(\\w*)%/u', '{{$1}}', (!empty($section_panel_settings[ '_panels_design_meta' . $i . '-config' ]) ? $section_panel_settings[ '_panels_design_meta' . $i . '-config' ] : null));
          $panel_def[ 'meta' . $i ] = str_replace('{{meta' . $i . 'innards}}', $meta[ $key ], $panel_def[ 'meta' . $i ]);
          $panel_def[ 'meta' . $i ] = str_replace('{{date}}', $panel_def[ 'datetime' ], $panel_def[ 'meta' . $i ]);
          $panel_def[ 'meta' . $i ] = str_replace('{{author}}', $panel_def[ 'author' ], $panel_def[ 'meta' . $i ]);
          $panel_def[ 'meta' . $i ] = str_replace('{{categories}}', $panel_def[ 'categories' ], $panel_def[ 'meta' . $i ]);
          $panel_def[ 'meta' . $i ] = str_replace('{{tags}}', $panel_def[ 'tags' ], $panel_def[ 'meta' . $i ]);
          $panel_def[ 'meta' . $i ] = str_replace('{{edit}}', $panel_def[ 'edit' ], $panel_def[ 'meta' . $i ]);
        }


// TODO: Need to work out what to do with the headerinnards!

//      $panel_layout = json_decode($section_panel_settings[ '_panels_design_preview' ], true);
//      // build up the blueprint for the panel, ordering from
//      // won't this be fun!!
//      // need to match panellayout slugs to paneldefs array index
//      $panel_definition = '';
//
//      foreach ((array)$panel_layout as $key => $value) {
//        if ($value[ 'show' ]) {
//          if ($key != 'title') {
//            $panel_definition .= $panel_def[ $key ];
//          }
//          else {
//            $panel_definition .= $panel_def[ 'header' ];
//          }
//        }
//
//      }
//      $panel_definition = str_replace('{{headerinnards}}', $panel_def[ 'title' ], $panel_definition);

        return $panel_def;
      }

      /**
       * @param $source
       * @param $criteria
       * @param $overrides
       * @return WP_Query
       */
      public function query($source, $criteria, $overrides)
      {
        //build the new query
        $query_options[ 'posts_per_page' ]      = $criteria[ 'panels_to_show' ];
        $query_options[ 'ignore_sticky_posts' ] = $criteria[ 'ignore_sticky_posts' ];
        $query_options[ 'offset' ]              = $criteria[ 'offset' ];

        //Lot of work!
        //     pzdebug($criteria);
        switch ($source) {

          case 'post':
            $query_options[ 'post_type' ]    = 'post';
            $query_options[ 'category__in' ] = (!empty($criteria[ 'content_posts_inc-cats' ])
                ? $criteria[ 'content_posts_inc-cats' ] : null);
            break;
          // could we build this into the panel def or somewhere else so more closely tied to the content type stuff
          case 'gallery':
            $query_options[ 'post_type' ]           = 'attachment';
            $query_options[ 'post__in' ]            = (!empty($criteria[ 'content_galleries-specific-images' ])
                ? $criteria[ 'content_galleries-specific-images' ] : null);
            $query_options[ 'post_status' ]         = array('publish', 'inherit', 'private');
            $query_options[ 'ignore_sticky_posts' ] = true;
            break;
        }
// currently this is the only bit that really does anything
        if ($overrides) {
          $query_options[ 'post__in' ]       = explode(',', $overrides);
          $query_options[ 'posts_per_page' ] = count($query_options[ 'post__in' ]);
        }
        $query = new WP_Query($query_options);

        return $query;
      }

    }

    // EOC Architect

