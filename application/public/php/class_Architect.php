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
    private $arc_query;
    private $is_shortcode;
    private $criteria = array();
    private $backup_wp_query;

    /**
     * @param $blueprint
     * @param $is_shortcode
     */
    public function __construct($blueprint, $is_shortcode)
    {
      $this->is_shortcode = $is_shortcode;

      pzarc_set_defaults();

      require_once(PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_Blueprint.php');

      $this->build = new arc_Blueprint($blueprint);
      //var_dump(((array)$this->build->blueprint));

      if ($this->build->blueprint[ '_blueprints_content-source' ] == 'defaults' && $this->is_shortcode) {

        $this->build->blueprint[ 'err_msg' ] = '<p class="message-warning">Ooops! Need to specify a <strong>Contents Selection</strong> in your Blueprint to use a shortcode. You cannot use Defaults.</p>';

      }

      if (!empty($this->build->blueprint[ 'err_msg' ])) {

        echo $this->build->blueprint[ 'err_msg' ];

        return false;

      }

      // Good to go. Load all the classes

      require_once(PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-functions.php');
      require_once(PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_Section.php');
      require_once(PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_Navigator.php');
      require_once(PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_Pagination.php');

      if (!empty($this->build->blueprint[ 'blueprint-id' ])) {

        $filename = 'pzarc-blueprints-layout-' . ($this->build->blueprint[ 'blueprint-id' ]) . '-' . $this->build->blueprint[ '_blueprints_short-name' ] . '.css';

        if (file_exists(PZARC_CACHE_PATH . $filename)) {

          wp_enqueue_style('blueprint-css-' . $this->build->blueprint[ 'blueprint-id' ], PZARC_CACHE_URL . $filename);

        } else {

          echo '<p class="message-warning">Oops! Could not find css cache file: ' . $filename . '</p>';

        }

      }

      return false;
    }

    /**
     * @param $overrides
     */
    public function build_blueprint($overrides, $caller)
    {
      // If we use pagination, we'll have to mod $wp_query
      global $wp_query;
      $original_query = $wp_query;

      // Shorthand some vars
      $bpshortname = $this->build->blueprint[ '_blueprints_short-name' ];
      $bpnav_type  = $this->build->blueprint[ '_blueprints_navigation' ];
      $bptranstype = $this->build->blueprint[ '_blueprints_transitions-type' ];

      do_action('arc_before_architect');
      do_action('arc_navigation_top');
      do_action('arc_navigation_left');

      echo '<div class="pzarchitect pzarc-blueprint pzarc-blueprint_' . $this->build->blueprint[ '_blueprints_short-name' ] . ' nav-' . $bpnav_type . '">';

      self::display_page_title($this->build->blueprint[ '_blueprints_page-title' ]);

      echo self::get_sections_opener($bpshortname, $bpnav_type, $caller, $bptranstype);

      do_action('arcNavBeforeSection-{$bpshortname}');

      $this->arc      = array();
      $this->criteria = array();

      // Setup some generic criteria
      $this->criteria[ 'panels_to_show' ]      = $this->build->blueprint[ '_blueprints_section-0-panels-per-view' ] +
          ((int)$this->build->blueprint[ '_blueprints_section-1-enable' ] * $this->build->blueprint[ '_blueprints_section-1-panels-per-view' ]) +
          ((int)$this->build->blueprint[ '_blueprints_section-2-enable' ] * $this->build->blueprint[ '_blueprints_section-2-panels-per-view' ]);
      $this->criteria[ 'ignore_sticky_posts' ] = !$this->build->blueprint[ '_blueprints_sticky' ];
      $this->criteria[ 'offset' ]              = $this->build->blueprint[ '_blueprints_skip' ];

      // Set vars to identify if we need to display sections 2 and 3.
      $do_section_2 = ($this->build->blueprint[ '_blueprints_section-1-enable' ] && $bpnav_type != 'navigator');
      $do_section_3 = ($this->build->blueprint[ '_blueprints_section-2-enable' ] && $bpnav_type != 'navigator');

      // TODO: Are all these 'self's too un-oop?
      // Get pagination
      // Need to do this before we touch the query!?
      if ($bpnav_type == 'pagination') {

        switch (true) {

          case is_home():
            $class                     = 'arc_Pagination_' . $this->build->blueprint[ '_blueprints_pager' ];
            $this->arc[ 'pagination' ] = new $class;
            break;

          case is_archive():
            $class                     = 'arc_Pagination_' . $this->build->blueprint[ '_blueprints_pager-archives' ];
            $this->arc[ 'pagination' ] = new $class;
            break;

          case (is_singular() && !is_front_page()):
            $class                     = 'arc_Pagination_' . $this->build->blueprint[ '_blueprints_pager-single' ];
            $this->arc[ 'pagination' ] = new $class;
            break;

        }
      }

      // Build the query
      if ($this->build->blueprint[ '_blueprints_content-source' ] != 'defaults') {

        self::use_custom_query($overrides);

      } else {

        self::use_default_query();

      }

      // Display pagination above
      if (isset($this->arc[ 'pagination' ])) {

        do_action('arcBeforePaginationAbove');

        $this->arc[ 'pagination' ]->render($this->arc_query, 'nav-above');

        do_action('arcAfterPaginationAbove');

      }

      // Loops
      $nav_items = self::loop(1);

      if ($do_section_2) {

        $notused = self::loop(2);

      }

      if ($do_section_3) {

        $notused = self::loop(3);

      }

      // End loops
      echo '</div> <!-- end blueprint sections -->';

      //TODO: Is it possible toshow the nav based on an action?
      do_action('arcNavAfterSections-{$bpshortname}');

      // NAVIGATION
//      if ($this->build->blueprint[ '_blueprints_navigation' ] === 'navigator' && $this->build->blueprint[ '_blueprints_navigator-location' ] === 'outside') {
      if ($bpnav_type === 'navigator') {

        $class                    = 'arc_Navigator_' . $this->build->blueprint[ '_blueprints_navigator' ];
        $this->arc[ 'navigator' ] = new $class($this->build->blueprint, $nav_items);

      }

      // TODO: Display navigator or pagination using add_action to appropriate spot
      // Display pagination
      if (isset($this->arc[ 'navigator' ])) {

        do_action('arcBeforeNavigationBelow');

        $this->arc[ 'navigator' ]->render();

        do_action('arcAfterNavigationBelow');

      }

      // Don't allow pagination on pages it doesn't work on!
      //   Todo : setup pagination for single or blog index
      if (isset($this->arc[ 'pagination' ])) {

        do_action('arcBeforePaginationBelow');

        $this->arc[ 'pagination' ]->render($this->arc_query, 'nav-below');

        do_action('arcAfterPaginationBelow');

      }

      echo '</div> <!-- end the whole lot-->';
      // TODO:: Hmmm how we planning to use these?

      do_action('arc_navigation_right');

      do_action('arc_navigation_bottom');

      do_action('arc_after_architect');


      // Set our original query back in case we had pagination.
      wp_reset_postdata(); // Pretty sure this goes here... Not after the query reassignment
      $wp_query = null;
      $wp_query = $original_query;

    }

    private function display_page_title($display_title)
    {
      if (!empty($display_title)) {
        $title = '';
        switch (true) {
          case is_category() :
            $title = single_cat_title(__('Showing category: ', 'pzarchitect'), false);
            break;
          case is_tag() :
            $title = single_tag_title(__('Showing tag: ', 'pzarchitect'), false);
            break;
          case is_month() :
            $title = single_month_title(__('Showing month: ', 'pzarchitect'), false);
            break;
          case is_single() :
            $title = single_post_title(null, false);
            break;
        }
        if ($title) {
          echo '<div class="pzarc-page-title"><h2>' . $title . '</h2></div>';
        }
      }
    }

    /**
     * @param $bpshortname
     * @param $bpnav_type (not necessary but makes calling code more informative)
     * @param $caller
     * @param $bptranstype
     * @return string
     */
    private function get_sections_opener($bpshortname, $bpnav_type, $caller, $bptranstype)
    {
      $return_val = '';
      if ($bpnav_type === 'navigator') {
        $swiper                = array();
        $swiper[ 'class' ]     = '';
        $swiper[ 'dataid' ]    = '';
        $swiper[ 'datatype' ]  = '';
        $swiper[ 'class' ]     = ' swiper-container slider swiper-container-' . $bpshortname;
        $swiper[ 'dataid' ]    = ' data-swiperid="' . $bpshortname . '"';
        $swiper[ 'datatype' ]  = 'data-navtype="' . $bpnav_type . '"';
        $swiper[ 'datatrans' ] = ' data-transtype="' . $bptranstype . '"';

        $duration             = $this->build->blueprint[ '_blueprints_transitions-duration' ] * 1000;
        $interval             = $this->build->blueprint[ '_blueprints_transitions-interval' ] * 1000;
        $skip_thumbs          = $this->build->blueprint[ '_blueprints_navigator-skip-thumbs' ];
        $swiper[ 'dataopts' ] = 'data-opts="{#tduration#:' . $duration . ',#tinterval#:' . $interval . ',#tskip#:' . $skip_thumbs . '}"';

        $return_val .= '<a class="pager arrow-left icon-btn-style" href="#"></a>';
        $return_val .= '<a class="pager arrow-right icon-btn-style" href="#"></a>';
//          //TODO: Should the bp name be in the class or ID?
        $return_val .= '<div class="pzarc-sections_' . $bpshortname . ' pzarc-is_' . $caller . $swiper[ 'class' ] . '"' . $swiper[ 'dataid' ] . $swiper[ 'datatype' ] . $swiper[ 'dataopts' ] . $swiper[ 'datatrans' ] . '>';
      } else {
        $return_val .= '<div class="pzarc-sections_' . $bpshortname . ' pzarc-is_' . $caller . '">';
      }

      return $return_val;
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
      //replace meta1innards etc

      $meta = array_pad(array(), 3, null);
      foreach ($meta as $key => $value) {
        $i                        = $key + 1;
        $meta[ $key ]             = preg_replace('/%(\\w*)%/u', '{{$1}}', (!empty($section_panel_settings[ '_panels_design_meta' . $i . '-config' ]) ? $section_panel_settings[ '_panels_design_meta' . $i . '-config' ] : null));
        $panel_def[ 'meta' . $i ] = str_replace('{{meta' . $i . 'innards}}', $meta[ $key ], $panel_def[ 'meta' . $i ]);
        $panel_def[ 'meta' . $i ] = str_replace('{{date}}', $panel_def[ 'datetime' ], $panel_def[ 'meta' . $i ]);
        $panel_def[ 'meta' . $i ] = str_replace('{{author}}', $panel_def[ 'author' ], $panel_def[ 'meta' . $i ]);
        $panel_def[ 'meta' . $i ] = str_replace('{{email}}', $panel_def[ 'email' ], $panel_def[ 'meta' . $i ]);
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
     * @param $this ->criteria
     * @param $overrides
     */
    private function use_custom_query($overrides)
    {
      // Is this a better way to code?
      self::set_criteria_prefix(self::set_prefix());
      self::build_custom_query($overrides);
      self::replace_wp_query();
    }

    /**
     * @return string
     */
    private function set_prefix()
    {
      // TODO: This is not dumb either so no ggod for plugable
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
        case 'snippets':
          $prefix = '_content_snippets_';
          break;
        case 'cpt':
          $prefix = '_content_cpt_';
          break;
      }

      return $prefix;
    }

    /**
     *
     */
    private function replace_wp_query()
    {
      // WordPress uses the main query for pagination. Need to get our query in there. http://wordpress.stackexchange.com/questions/120407/how-to-fix-pagination-for-custom-loops
      // We'll still use our query, but paging will be picked up from $wp_query. We'll reset $wp_query back after
      // followed every tute under the sun to get it to work otherwise, but nothin!

      if ($this->build->blueprint[ '_blueprints_navigation' ] === 'pagination') {
        global $wp_query;
        $wp_query = $this->arc_query;
        wp_reset_postdata();
      }

    }

    /**
     * @param $prefix
     */
    private function set_criteria_prefix($prefix)
    {
//      if (empty($prefix)){return;}
      foreach ($this->build->blueprint as $key => $value) {
        if (strpos($key, $prefix) === 0) {
          $this->criteria[ $key ] = $value;
        }
      }

    }

    /**
     * @param $this ->criteria
     */
    private function use_default_query()
    {
      global $wp_query;
      $this->arc_query = $wp_query;
      // This may not do anything since the query may not update!
      // need to set nopaging too
      if ($this->build->blueprint[ '_blueprints_navigation' ] == 'pagination') {
        $this->arc_query->query_vars[ 'nopaging' ] = false;
      } else {
        $this->arc_query->query_vars[ 'nopaging' ] = $this->criteria[ 'nopaging' ];
      }
      $this->arc_query->query_vars[ 'posts_per_page' ] = $this->criteria[ 'panels_to_show' ];
    }

    /**
     * @param $source
     * @param $this ->criteria
     * @param $overrides
     * @return WP_Query
     */
    public function build_custom_query($overrides)
    {

      //build the new query
      $source        = $this->build->blueprint[ '_blueprints_content-source' ];
      $query_options = array();

      //Paging parameters
      if ($this->build->blueprint[ '_blueprints_navigation' ] == 'pagination') {

        if (get_query_var('paged')) {

          $paged = get_query_var('paged');

        } elseif (get_query_var('page')) {

          $paged = get_query_var('page');

        } else {

          $paged = 1;

        }

//        $query_options[ 'nopaging' ]       = false;
        $query_options[ 'posts_per_page' ] = $this->criteria[ 'panels_to_show' ];
        $query_options[ 'pagination' ]     = true;
        $query_options[ 'paged' ]          = $paged;

      } else {

        $query[ 'nopaging' ]               = $this->criteria[ 'nopaging' ];
        $query_options[ 'posts_per_page' ] = $this->criteria[ 'panels_to_show' ];

        $query_options[ 'offset' ]         = $this->criteria[ 'offset' ];
      }

      // these two break paging yes?
      $query_options[ 'ignore_sticky_posts' ] = $this->criteria[ 'ignore_sticky_posts' ];

      //Lot of work!
      //     pzdebug($this->criteria);
      // TODO: We're going to have to make this pluggable too! :P
      switch ($source) {

        case 'post':
//          var_dump($this->criteria[ '_content_posts_inc-cats']);
          $query_options[ 'post_type' ]    = 'post';
          $query_options[ 'category__in' ] = (!empty($this->criteria[ '_content_posts_inc-cats' ])
              ? $this->criteria[ '_content_posts_inc-cats' ] : null);
          break;

        // TODO: could we build this into the panel def or somewhere else so more closely tied to the content type stuff

        case 'gallery':

          //TODO: What about G+ galleries??
          $query_options[ 'post_type' ]           = 'attachment';
          $query_options[ 'post__in' ]            = (!empty($this->criteria[ 'content_galleries-specific-images' ])
              ? $this->criteria[ 'content_galleries-specific-images' ] : null);
          $query_options[ 'post_status' ]         = array('publish', 'inherit', 'private');
          $query_options[ 'ignore_sticky_posts' ] = true;

          break;

        case 'snippets':
          $query_options[ 'post_type' ] = 'pz_snippets';
          break;

        case 'slides':
          $query_options[ 'post_type' ] = 'pzsp-slides';
          break;

      }
      // OVERRIDES
      // currently this is the only bit that really does anything
      if ($overrides) {

        $query_options[ 'post__in' ]       = explode(',', $overrides);
        $query_options[ 'posts_per_page' ] = count($query_options[ 'post__in' ]);
      }

      $this->arc_query = new WP_Query($query_options);
//      var_dump($this->arc_query->request);
//      var_dump($this->arc_query->found_posts);
    }


    /**
     * @param $section_no
     */
    private function loop($section_no)
    {
      $section[ $section_no ] = arc_SectionFactory::create($section_no,
                                                           $this->build->blueprint[ 'section' ][ ($section_no - 1) ],
                                                           $this->build->blueprint[ '_blueprints_content-source' ],
                                                           $this->build->blueprint[ '_blueprints_navigation' ],
                                                           $this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-layout-mode' ],
                                                           $this->build->blueprint[ '_blueprints_navigator-slider-engine' ],
                                                           $this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-title' ]
      );

      // oops! Need to get default content type when defaults chosen.
      $post_type = (empty($this->build->blueprint[ '_blueprints_content-source' ]) || 'defaults' === $this->build->blueprint[ '_blueprints_content-source' ] ?
          $this->arc_query->queried_object->post_type :
          $this->build->blueprint[ '_blueprints_content-source' ]);

      if (empty($post_type)) {

        pzarc_msg('No post type specified', 'error');

        return null;

      }

      $class = 'arc_Panel_' . $post_type;

      // TODO: Should we fall back to Post post type if unknown??
      // Use an include incase it doesn't exist!
      include_once PZARC_PLUGIN_APP_PATH . '/public/php/post_types/class_arc_Panel_' . ucfirst($post_type) . '.php';

      if (!class_exists($class)) {

        pzarc_msg(__('Post type ', 'pzarchitect') . '<strong>' . $post_type . '</strong>' . __(' has no panel definition and cannot be displayed.', 'pzarchitect'), 'error');

        return null;

      }

      // We setup the Paneldef here so we're not doing it every iteration of the Loop!
      // TODO: Some sites get a T_PAAMAYIM_NEKUDOTAYIM error! ugh!

      $panel_class = new $class;

      $panel_def = $panel_class->panel_def();

      // Setup meta tags
      $panel_def = self::build_meta_definition($panel_def, $this->build->blueprint[ 'section' ][ ($section_no - 1) ][ 'section-panel-settings' ]);

      //   var_dump(esc_html($panel_def));

      $i         = 1;
      $nav_items = array();

      // Does this work for non
      while ($this->arc_query->have_posts()) {

        $this->arc_query->the_post();

        // TODO: This may need to be modified for other types that dont' use post_title
        // TODO: Make dumb so can be pluggable for other navs
        switch ($this->build->blueprint[ '_blueprints_navigator' ]) {

          case 'tabbed':
            $nav_items[ ] = '<span class="' . $this->build->blueprint[ '_blueprints_navigator' ] . '">' . $this->arc_query->post->post_title . '</span>';
            break;

          case 'thumbs':

            if ('attachment'===$this->arc_query->post->post_type) {

              // TODO: Will need to change this to use the thumb dimensions set in the blueprint viasmall , medium, large
              $thumb        = wp_get_attachment_image($this->arc_query->post->ID, array(50, 50));

            } else {

              $thumb        = get_the_post_thumbnail($this->arc_query->post->ID, array(50, 50));

            }

            $thumb        = (empty($thumb) ? '<img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/missing-image.png" width="50" height="50">' : $thumb);

            $nav_items[ ] = '<span class="' . $this->build->blueprint[ '_blueprints_navigator' ] . '">' . $thumb . '</span>';
            break;

          case 'bullets':
          case 'numbers':
          case 'buttons':
            //No need for content on these
            $nav_items[ ] = '';
            break;

        }
        $section[ $section_no ]->render_panel($panel_def, $i, $class, $panel_class);

        if ($i++ >= $this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-panels-per-view' ] && !empty($this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-panels-limited' ])) {
          break;

        }

      }

      // Unsetting causes it to run the destruct, which closes the div!
      unset($section[ $section_no ]);

      return $nav_items;
    }
  }

  // EOC Architect

