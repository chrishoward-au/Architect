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
   * @method: query($source, $this->criteria, $overrides)
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
    private $criteria = array();


    /**
     * @param $blueprint
     * @param $is_shortcode
     */
    public function __construct($blueprint, $is_shortcode)
    {
      $this->is_shortcode = $is_shortcode;

      pzarc_set_defaults();
      require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Blueprint.php');
      $this->build = new arc_Blueprint($blueprint);
      //var_dump(((array)$this->build->blueprint));

      if ($this->build->blueprint[ '_blueprints_content-source' ] == 'defaults' && $this->is_shortcode) {
        $this->build->blueprint[ 'err_msg' ] = '<p class="warning-msg">Ooops! Need to specify a <strong>Contents Selection</strong> in your Blueprint to use a shortcode. You cannot use Defaults.</p>';
      }
      if (!empty($this->build->blueprint[ 'err_msg' ])) {
        echo $this->build->blueprint[ 'err_msg' ];

        return false;
      }


      // Good to go. Load all the classes
      require_once(PZARC_PLUGIN_PATH . '/resources/architect/php/arc-functions.php');
      require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Section.php');
      require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Navigator.php');
      require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Pagination.php');
      require_once PZARC_PLUGIN_PATH . '/public/php/interface_arc_PanelDefinitions.php';
      if (!empty($this->build->blueprint[ 'blueprint-id' ])) {
        $filename = PZARC_CACHE_URL . '/pzarc-blueprints-layout-' . ($this->build->blueprint[ 'blueprint-id' ]) . '.css';
        wp_enqueue_style('blueprint-css-' . $this->build->blueprint[ 'blueprint-id' ], $filename);
      }

      return false;
    }

    /**
     * @param $overrides
     */
    public function build($overrides, $caller)
    {
      do_action('arc_before_architect');
      do_action('arc_navigation_top');
      do_action('arc_navigation_left');
      echo '<div class="pzarchitect pzarc-container pzarc-container-' . $this->build->blueprint[ '_blueprints_short-name' ].'">';
      /*******************************/
      $swiper = array();
      $swiper['class'] ='';
      $swiper['dataid'] = '';
      $swiper['dataopts'] = '';
      if ($this->build->blueprint[ '_blueprints_navigation' ] === 'navigator') {
        $swiper['class'] = ' swiper-container swiper-container-' . $this->build->blueprint[ '_blueprints_short-name' ];
        $swiper['dataid']    = ' data-swiperid="' . $this->build->blueprint[ '_blueprints_short-name' ] . '"';
        $swiper['dataopts'] = " data-swiperopts=\"pagination: '.pzarc-navigator-featured-posts-2x4',loop:true,mode:'horizontal',grabCursor: true,paginationClickable: true,slidesPerView:'1',useCSS3Transforms:false,speed:2000\"";
        echo '<a class="arrow-left" href="#"></a>';
        echo '<a class="arrow-right" href="#"></a>';
      }
      //TODO: Should the bp name be in the class or ID?
      echo '<div id= "pzarc-blueprint_' . $this->build->blueprint[ '_blueprints_short-name' ] . '" class="pzarc-blueprint_' . $this->build->blueprint[ '_blueprints_short-name' ] . ' pzarc-is_' . $caller . $swiper['class'] . '"' . $swiper['dataid'] . $swiper['dataopts'].'>';

      $this->arc      = array();
      $this->criteria = array();

      $this->criteria[ 'panels_to_show' ] = $this->build->blueprint[ '_blueprints_section-0-panels-per-view' ];

      // hmm? if nopaging is true, it returns all records. Do we ever need that? Maybe we could manage pagination ourself then?!
      // $this->criteria[ 'nopaging' ] = ($this->build->blueprint['_blueprints_navigation']=='none');

      $this->criteria[ 'ignore_sticky_posts' ] = !$this->build->blueprint[ '_blueprints_sticky' ];
      $this->criteria[ 'offset' ]              = $this->build->blueprint[ '_blueprints_skip' ];

      $do_section_2 = ($this->build->blueprint[ 'section' ][ 1 ][ 'section-enable' ] && $this->build->blueprint[ '_blueprints_navigation' ] != 'navigator');
      if ($do_section_2) {
        $this->criteria[ 'panels_to_show' ] .= $this->build->blueprint[ '_blueprints_section-1-panels-per-view' ];
      }

      $do_section_3 = ($this->build->blueprint[ 'section' ][ 2 ][ 'section-enable' ] && $this->build->blueprint[ '_blueprints_navigation' ] != 'navigator');
      if ($do_section_3) {
        $this->criteria[ 'panels_to_show' ] .= $this->build->blueprint[ '_blueprints_section-2-panels-per-view' ];
      }


      // TODO: Are all these 'self's too un-oop?
      if ($this->build->blueprint[ '_blueprints_content-source' ] != 'defaults') {
        self::build_new_query($$overrides);
      } else {
        self::use_default_query();
      }

      // Loops
      self::loop(1);
      if ($do_section_2) {
        self::loop(2);
      }
      if ($do_section_3) {
        self::loop(3);
      }
      // End loops

      echo '</div> <!-- end blueprint sections -->';

      // NAVIGATION
      if ($this->build->blueprint[ '_blueprints_navigation' ] == 'navigator' && $this->build->blueprint[ '_blueprints_navigator-location' ] == 'outside') {
//        $this->arc[ 'navigator' ] = new arc_Navigator();
        echo '<div class="pzarc-navigator pzarc-navigator-' . $this->build->blueprint[ '_blueprints_short-name' ] . '"></div>';

      } elseif ($this->build->blueprint[ '_blueprints_navigation' ] == 'pagination' && $this->build->blueprint[ '_blueprints_pager' ] != 'none') {
        $class                     = 'arc_Pagination_' . $this->build->blueprint[ '_blueprints_pager' ];
        $this->arc[ 'pagination' ] = new $class;
      }

      // TODO: Display navigator or pagination using add_action to appropriate spot
      // Display pagination
      if (isset($this->arc[ 'navigator' ])) {
        $this->arc[ 'navigator' ]->render();
      }
      if (isset($this->arc[ 'pagination' ])) {
        $this->arc[ 'pagination' ]->render();
      }
      echo '</div> <!-- end the whole lot-->';
      do_action('arc_navigation_right');
      do_action('arc_navigation_bottom');
      do_action('arc_after_architect');

    }

    /**
     * @param $section_no
     */
    private function loop($section_no)
    {

      $section[ $section_no ] = arc_SectionFactory::create($section_no, $this->build->blueprint[ 'section' ][ ($section_no - 1) ], $this->build->blueprint[ '_blueprints_content-source' ], $this->build->blueprint[ '_blueprints_navigation' ]);

      $class     = 'arc_PanelDef_' . $this->build->blueprint[ '_blueprints_content-source' ];
      $panel_def = self::build_meta_definition($class::panel_def(), $this->build->blueprint[ 'section' ][ ($section_no - 1) ][ 'section-panel-settings' ]);

      $i = 1;
      while ($this->query->have_posts()) {
        $this->query->the_post();
        $section[ $section_no ]->render_panel($panel_def, $i);
        if ($i++ >= $this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-panels-per-view' ]) {
          break;
        }
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
     * @param $panel_def
     * @param $section_panel_settings
     * @return mixed
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
     * @param $this ->criteria
     * @param $overrides
     * @return WP_Query
     */
    public function query($source, $overrides)
    {
      //build the new query
      $query_options = array();
      if ($this->build->blueprint[ '_blueprints_navigation' ] == 'pagination') {
        $query_options[ 'nopaging' ] = false;
      } else {
        $query[ 'nopaging' ] = $this->criteria[ 'nopaging' ];
      }

      $query_options[ 'posts_per_page' ]      = $this->criteria[ 'panels_to_show' ];
      $query_options[ 'ignore_sticky_posts' ] = $this->criteria[ 'ignore_sticky_posts' ];
      $query_options[ 'offset' ]              = $this->criteria[ 'offset' ];

      //Lot of work!
      //     pzdebug($this->criteria);
      switch ($source) {

        case 'post':
          $query_options[ 'post_type' ]    = 'post';
          $query_options[ 'category__in' ] = (!empty($this->criteria[ 'content_posts_inc-cats' ])
              ? $this->criteria[ 'content_posts_inc-cats' ] : null);
          break;
        // could we build this into the panel def or somewhere else so more closely tied to the content type stuff
        case 'gallery':
          $query_options[ 'post_type' ]           = 'attachment';
          $query_options[ 'post__in' ]            = (!empty($this->criteria[ 'content_galleries-specific-images' ])
              ? $this->criteria[ 'content_galleries-specific-images' ] : null);
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

//var_dump($query);
      return $query;
    }

    /**
     * @param $this ->criteria
     */
    private function use_default_query()
    {
      global $wp_query;
      $this->query = $wp_query;
      // This may not do anything since the query may not update!
      // need to set nopaging too
      var_dump($this->build->blueprint[ '_blueprints_navigation' ]);
      if ($this->build->blueprint[ '_blueprints_navigation' ] == 'pagination') {
        $this->query->query_vars[ 'nopaging' ] = false;
      } else {
        $this->query->query_vars[ 'nopaging' ] = $this->criteria[ 'nopaging' ];
      }
      $this->query->query_vars[ 'posts_per_page' ] = $this->criteria[ 'panels_to_show' ];
    }

    /**
     * @param $this ->criteria
     * @param $overrides
     */
    private function build_new_query($overrides)
    {
      $prefix = '';
      switch ($this->build->blueprint[ '_blueprints_content-source' ]) {
        case 'post':
          $prefix = '_content_posts_';
          break;
        case 'galleries':
          $prefix = '_content_galleries_';
          break;
        case 'page':
          $prefix = '_content_pages_';
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
          $this->criteria[ $key ] = $value;
        }
      }
      $this->query = self::query($this->build->blueprint[ '_blueprints_content-source' ], $overrides);

    }

  }

  // EOC Architect

