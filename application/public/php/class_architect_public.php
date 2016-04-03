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
  class ArchitectPublic
  {

    public $build;
    private $arc_pagination;
    public $arc_query;
    private $is_shortcode;
    private $criteria = array();
    private $nav_items = array();
    private $content_source = null;

    /**
     * @param $blueprint_shortname
     * @param $is_shortcode
     */
    public function __construct($blueprint_shortname, $is_shortcode)
    {
      // Might use this for transients to check last DB change
      //      global $wpdb;
      //      $newest = $wpdb->get_row("select post_modified from $wpdb->posts
      //where post_type in ('page', 'post') and post_status = 'publish' order by post_modified desc");
      //      var_dump(mysql2date('m/d/Y h:m:s',$newest->post_modified));

      $this->is_shortcode = $is_shortcode;

      require_once PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_shortcodes.php';
      require_once(PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_section.php');
      require_once(PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_blueprint.php');

      // Load generics
      require_once(PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/generic/class_arc_panel_generic.php');
      require_once(PZARC_PLUGIN_APP_PATH . '/shared/architect/php/content-types/generic/class_arc_query_generic.php');

      /** This is the first point we have all the Blueprint data */
      $this->build = new arc_Blueprint( $blueprint_shortname,false);

      new arc_shortcodes($this->build);


      if (isset($this->build->blueprint[ '_blueprints_content-source' ]) && $this->build->blueprint[ '_blueprints_content-source' ] == 'defaults' && $this->is_shortcode) {

        $this->build->blueprint[ 'err_msg' ] = '<p class="message-warning">Ooops! Need to specify a <strong>Contents Selection</strong> in your Blueprint to use a shortcode. You cannot use Defaults.</p>';

      }

      if (!empty($this->build->blueprint[ 'err_msg' ])) {

        echo $this->build->blueprint[ 'err_msg' ];

        return false;

      }

      // Good to go. Load all the classes

      require_once(PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-functions.php');
      require_once(PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_navigator.php');
      require_once(PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_pagination.php');

      if ('masonry' === $this->build->blueprint[ '_blueprints_section-0-layout-mode' ]) {
        require_once(PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_masonry.php');
        new arc_masonry($this->build->blueprint);
      }

      /** If it's a slider, create its stuff */
      if ($this->build->blueprint) {
        // load slider
        // Later we'll get this from the registry
//        require_once(PZARC_PLUGIN_PATH. '/extensions-inc/sliders/slick/arc-slick-public.php');
      }


      /** Add navigation.*/
      $nav_pos = ('thumbs' === $this->build->blueprint[ '_blueprints_navigator' ] && 'top' === $this->build->blueprint[ '_blueprints_navigator-thumbs-position' ]) ? 'tl' : '';
      $nav_pos = (!in_array($this->build->blueprint[ '_blueprints_navigator' ], array(
              'thumbs',
              'none'
          )) && ('top' === $this->build->blueprint[ '_blueprints_navigator-position' ] || 'left' === $this->build->blueprint[ '_blueprints_navigator-position' ])) ? 'tl' : $nav_pos;

      //  Putting it in an action allows devs to write their own
      if (($this->build->blueprint[ '_blueprints_section-0-layout-mode' ] === 'tabbed' || $this->build->blueprint[ '_blueprints_section-0-layout-mode' ] === 'slider')
          && $nav_pos === 'tl'
      ) {

        add_action('arc_top_left_navigation_' . $this->build->blueprint[ '_blueprints_short-name' ], array(
            &$this,
            'add_navigation'
        ), 10, 1);
      }

      $nav_pos = ('thumbs' === $this->build->blueprint[ '_blueprints_navigator' ] && 'bottom' === $this->build->blueprint[ '_blueprints_navigator-thumbs-position' ]) ? 'br' : '';
      $nav_pos = (!in_array($this->build->blueprint[ '_blueprints_navigator' ], array(
              'thumbs',
              'none'
          )) && ('bottom' === $this->build->blueprint[ '_blueprints_navigator-position' ] || 'right' === $this->build->blueprint[ '_blueprints_navigator-position' ])) ? 'br' : $nav_pos;

      // Do the 'none' here to ensure we still load the js even tho no nav bar
      if (($this->build->blueprint[ '_blueprints_section-0-layout-mode' ] === 'tabbed' || $this->build->blueprint[ '_blueprints_section-0-layout-mode' ] === 'slider')
          && $nav_pos === 'br' || $this->build->blueprint[ '_blueprints_navigator' ] === 'none'
      ) {

        add_action('arc_bottom_right_navigation_' . $this->build->blueprint[ '_blueprints_short-name' ], array(
            &$this,
            'add_navigation'
        ), 10, 1);
      }

      return false;
    }


    /**
     * @param $overrides
     * @param $caller
     * @param $additional_overrides
     */
    public function build_blueprint($overrides, $caller, &$additional_overrides)
    {
      // If we use pagination, we'll have to mod $wp_query. Actually... we have to regardless
      global $wp_query;
      $original_query = $wp_query;

      $this->build->blueprint[ 'additional_overrides' ] = $additional_overrides;

      // Shorthand some vars
      $bp_shortname = $this->build->blueprint[ '_blueprints_short-name' ];
      $bp_nav_type  = 'none';
      $bp_type      = $this->build->blueprint[ '_blueprints_section-0-layout-mode' ];
      switch (true) {
        case $bp_type === 'slider':
        case  $bp_type === 'tabbed':
          $bp_nav_type = 'navigator';
          break;
        case !empty($this->build->blueprint[ '_blueprints_pagination' ]):
          $bp_nav_type = 'pagination';
          break;
      }
      $bp_nav_pos           = ($this->build->blueprint[ '_blueprints_navigator' ] === 'thumbs' ? $this->build->blueprint[ '_blueprints_navigator-thumbs-position' ] : $this->build->blueprint[ '_blueprints_navigator-position' ]);
      $bp_transtype         = !empty($this->build->blueprint[ '_blueprints_transitions-type' ]) ?$this->build->blueprint[ '_blueprints_transitions-type' ]:'slide';
      $this->arc_pagination = array();

      self::set_generic_criteria();


      // TODO: Are all these 'self's too un-oop?
      // Get pagination
      // Need to do this before we touch the query!?
      if (!empty($this->build->blueprint[ '_blueprints_pagination' ])) {

        switch (true) {

          case is_home():
            $content_class                        = 'arc_Pagination_' . (!$this->build->blueprint[ '_blueprints_pager' ] ? 'prevnext' : $this->build->blueprint[ '_blueprints_pager' ]);
            $this->arc_pagination[ 'pagination' ] = new $content_class;
            break;

          case (is_singular()):
            $content_class                        = 'arc_Pagination_' . (!$this->build->blueprint[ '_blueprints_pager-single' ] ? 'prevnext' : $this->build->blueprint[ '_blueprints_pager-single' ]);
            $this->arc_pagination[ 'pagination' ] = new $content_class;
            break;

          case is_archive():
            $content_class                        = 'arc_Pagination_' . (!$this->build->blueprint[ '_blueprints_pager-archives' ] ? 'prevnext' : $this->build->blueprint[ '_blueprints_pager-archives' ]);
            $this->arc_pagination[ 'pagination' ] = new $content_class;
            break;


        }
      }

      /** Build the query */
      $registry       = arc_Registry::getInstance();
      $content_source = $registry->get('content_source');

      // Setup original query vars that can then be used by custom query when Defaults
      $this->build->blueprint[ 'original_content-source' ] = $this->build->blueprint[ '_blueprints_content-source' ];
      if ($this->build->blueprint[ '_blueprints_content-source' ] === 'defaults') {
//      if ( $this->build->blueprint[ '_blueprints_content-source' ] === 'defaults' && ! empty( $this->build->blueprint[ '_content_defaults_defaults-override' ] ) ) {
        global $wp_query;
        $this->build->blueprint[ 'original_query_vars' ] = $wp_query->query_vars;
        if (empty($wp_query->query_vars[ 'post_type' ]) && ($this->build->blueprint[ '_blueprints_content-source' ] !== 'defaults')) {
          if (!empty($wp_query->queried_object->post_type)) {
            $this->build->blueprint[ 'original_query_vars' ][ 'post_type' ] = $wp_query->queried_object->post_type;
          } else {
            $this->build->blueprint[ 'original_query_vars' ][ 'post_type' ] = get_post_type();
          }
        }
//        foreach ($this->build->blueprint[ 'original_query_vars' ] as $kq => $vq) {
//          if (empty($vq)) {
//            unset($this->build->blueprint[ 'original_query_vars' ][ $kq ]);
//          }
//        }
      }

      if (array_key_exists($this->build->blueprint[ '_blueprints_content-source' ], $content_source)) {

        $source_query_class = 'arc_query_' . $this->build->blueprint[ '_blueprints_content-source' ];
        require_once($content_source[ $this->build->blueprint[ '_blueprints_content-source' ] ] . '/class_' . $source_query_class . '.php');

        global $wp_query;

        if ($this->build->blueprint[ '_blueprints_content-source' ] === 'defaults') {
          self::use_default_query();
          if (!empty($this->build->blueprint[ '_content_defaults_defaults-override' ])) {
            self::use_custom_query($overrides, $source_query_class);
          }
        } else {
          self::use_custom_query($overrides, $source_query_class);
        }

      } else {

        self::use_default_query();

      }

      /** at this point we have the necessary info to populate the navigator. So let's do it! */
      $content_class = self::get_blueprint_content_class();
      if (class_exists($content_class)) {
        $panel_class = new $content_class($this->build); // This gets the settings for the panels of this content type.
        if ($bp_nav_type === 'navigator') {
          $this->nav_items = $panel_class->get_nav_items($this->build->blueprint[ '_blueprints_navigator' ], $this->arc_query, $this->build->blueprint[ '_blueprints_navigator-labels' ], $this->build->blueprint[ '_blueprints_navtabs-maxlen' ]);
        }
        /** RENDER THE BLUEPRINT */
        pzdb('pre render');
        self::render_this_architect_blueprint($bp_nav_type, $bp_nav_pos, $bp_shortname, $caller, $bp_transtype, $panel_class, $content_class, $bp_type);
        pzdb('post render');
      }
      if (isset($_GET['debug'])) {
        d($this->build);
        d($wp_query->query_vars);
// Don't use this in public as it reveals table names

        $result = preg_replace("/(\\w)*\\./uiUs", "****$1.", $wp_query->request);
        $result =str_replace("****s",'xxx',$result);
        $result =str_replace("****a",'yyy',$result);
        $result = preg_replace("/FROM(.)*INNER/uiUs", "FROM xxx INNER", $result);
        $result = preg_replace("/JOIN(.)*ON/uiUs", "JOIN yyy ON", $result);

        d($result);
      }

      /** Set our original query back. */
      wp_reset_postdata(); // Pretty sure this goes here... Not after the query reassignment
      $wp_query = null;
      $wp_query = $original_query;

    }

    /**
     * @param $bp_nav_type
     * @param $bp_nav_pos
     * @param $bp_shortname
     * @param $caller
     * @param $bp_transtype
     * @param $panel_class
     * @param $content_class
     * @param $do_section_2
     * @param $do_section_3
     */
    private function render_this_architect_blueprint($bp_nav_type, $bp_nav_pos, $bp_shortname, $caller, $bp_transtype, $panel_class, $content_class, $blueprint_type)
    {
      // TODO: Show or hide blueprint if no content
      do_action('arc_before_architect');
      do_action("arc_before_architect_{$bp_shortname}");

      global $_architect_options;
      $use_hw_css = (!empty($_architect_options[ 'architect_use-hw-css' ]) ? 'use-hw-css' : null);
      /** BLUEPRINT */
      /**
       *
       *
       * OPEN THE HTML
       *
       * THIS IS WHERE IT BEGINS
       *
       *
       *
       *
       */

      pzdb('top blueprint html');
      echo '<!-- Blueprint '.$this->build->blueprint[ '_blueprints_short-name' ].' -->';
      echo '<div id="pzarc-blueprint_' . $this->build->blueprint[ '_blueprints_short-name' ] . '" class="' . $this->build->blueprint[ 'uid' ] . ' pzarchitect layout-' . $blueprint_type . ' ' . $use_hw_css . ' pzarc-blueprint pzarc-blueprint_' . $this->build->blueprint[ '_blueprints_short-name' ] . ' nav-' . $bp_nav_type . ' icomoon ' . ($bp_nav_type === 'navigator' ? 'navpos-' . $bp_nav_pos : '') . (is_rtl() ? ' rtl' : ' ltr') . '">';
      /** Page title */
      pzdb('after blueprint open');
      echo apply_filters('arc_page_title', pzarc_display_page_title($this->build->blueprint,$_architect_options));

      if (!empty($this->build->blueprint[ '_blueprints_blueprint-title' ])) {
        $bptitle_open_tag  = '<h2 class="pzarc-blueprint-title">';
        $bptitle_close_tag = '</h2>';
        echo apply_filters('arc-blueprint-title', $bptitle_open_tag . esc_html($this->build->blueprint[ '_blueprints_blueprint-title' ]) . $bptitle_close_tag);
      }
      pzdb();
      /** NAVIGATION TOP/LEFT */
      // These are the slider and tabbed controls
      do_action('arc_before_navigation_top_left');
      do_action("arc_before_navigation_top_left_{$bp_shortname}");

      // Devs can hook in with their own navigation
      do_action_ref_array("arc_top_left_navigation_{$bp_shortname}", array(&$this));

      do_action('arc_after_navigation_top_left');
      do_action("arc_after_navigation_top_left_{$bp_shortname}");

      pzdb();
      /** Display pagination above */
      // As pagination is WP core, devs can modify pagination in the same way PageNavi hooks in
      if (!empty($this->arc_pagination[ 'pagination' ]) && ($this->build->blueprint[ '_blueprints_pager-location' ] === 'top' || $this->build->blueprint[ '_blueprints_pager-location' ] === 'both')) {

        do_action('arc_before_pagination_above');
        do_action("arc_before_pagination_above_{$bp_shortname}");

        $this->arc_pagination[ 'pagination' ]->render($this->arc_query, 'nav-above', $this->build->blueprint);

        do_action('arc_after_pagination_above');
        do_action("arc_after_pagination_above_{$bp_shortname}");

      }

      do_action("arc_masonry_controls_{$bp_shortname}");

      do_action('arc_before_panels_wrapper');
      do_action("arc_before_panels_wrapper_{$bp_shortname}");
      pzdb();

      /** Sections opening HTML*/
      echo self::get_sections_opener($bp_shortname, $bp_nav_type, $caller, $bp_transtype);

      pzdb();

      /** LOOPS */
      // First loop always executes
      $panel_class->loop(1, $this, $panel_class, $content_class);

      pzdb();

      // End loop
      echo '</div> <!-- end blueprint sections -->';

      do_action('arc_after_panels_wrapper');
      do_action("arc_after_panels_wrapper_{$bp_shortname}");

      // Don't allow pagination on pages it doesn't work on!
      //   Todo : setup pagination for single or blog index

      /** PAGINATION BELOW  */
      if (!empty($this->arc_pagination[ 'pagination' ]) && ($this->build->blueprint[ '_blueprints_pager-location' ] === 'bottom' || $this->build->blueprint[ '_blueprints_pager-location' ] === 'both')) {

        do_action('arc_before_pagination_below');
        do_action("arc_before_pagination_below_{$bp_shortname}");

        $this->arc_pagination[ 'pagination' ]->render($this->arc_query, 'nav-below', $this->build->blueprint);

        do_action('arc_after_pagination_below');
        do_action("arc_after_pagination_below_{$bp_shortname}");

      }
      pzdb();

      /** NAVIGATION BOTTOM OR RIGHT */
      // These are the slider and tabbed controls
      do_action('arc_after_navigation');
      do_action("arc_after_navigation_{$bp_shortname}");
      pzdb();

      // Devs can hook in with their own navigation
      do_action_ref_array("arc_bottom_right_navigation_{$bp_shortname}", array(&$this));

      do_action('arc_after_navigation');
      do_action("arc_after_navigation_{$bp_shortname}");

      do_action('arc_before_blueprint_footer');
      if (!empty($this->build->blueprint['_blueprints_footer-text-link'])) {
        echo '<div class="pzarc-blueprint-footer">';
        echo do_shortcode(strip_tags($this->build->blueprint['_blueprints_footer-text-link'],'<br><p><a><strong><em><ul><ol><li><pre><code><blockquote>'));
        echo '</div>';
      }
      do_action('arc_after_blueprint_footer');
      echo '</div> <!-- end pzarchitect blueprint ' . $this->build->blueprint[ '_blueprints_short-name' ] . ' v' . PZARC_VERSION . PZARC_SHOP.' -->';
      pzdb('end blueprint html');

      do_action('arc_after_architect');
      do_action("arc_after_architect_{$bp_shortname}");

    }

    /**
     * set_generic_criteria
     */
    private function set_generic_criteria()
    {
      $this->criteria = array();

      /** Setup some generic criteria */

      // Technically we don't need to do this, but it just makes things neater and easier to read.

      // Set posts to show
      $limited = (int)$this->build->blueprint[ '_blueprints_section-0-panels-limited' ];

      if (!$limited) {

        $this->criteria[ 'panels_to_show' ] = -1;
        $this->criteria[ 'nopaging' ]       = true;

      } else {

        $this->criteria[ 'panels_to_show' ] = $this->build->blueprint[ '_blueprints_section-0-panels-per-view' ];
        $this->criteria[ 'nopaging' ]       = false;

      }

      $this->criteria[ 'per_page' ] = $this->build->blueprint[ '_blueprints_pagination-per-page' ];

      // Sticky posts
      $this->criteria[ 'ignore_sticky_posts' ] = !$this->build->blueprint[ '_content_general_sticky' ];

      // Offset
      $this->criteria[ 'offset' ] = $this->build->blueprint[ '_content_general_skip' ];

      // Order
      $this->criteria[ 'orderby' ] = $this->build->blueprint[ '_content_general_orderby' ];
      $this->criteria[ 'order' ]   = $this->build->blueprint[ '_content_general_orderdir' ];

      // Categories
      $this->criteria[ 'category__in' ]     = $this->build->blueprint[ '_content_general_inc-cats' ];
      $this->criteria[ 'category__not_in' ] = $this->build->blueprint[ '_content_general_exc-cats' ];

      // Tags
      $this->criteria[ 'tag__in' ]     = $this->build->blueprint[ '_content_general_inc-tags' ];
      $this->criteria[ 'tag__not_in' ] = $this->build->blueprint[ '_content_general_exc-tags' ];

    }


    /**
     *
     * get_sections_opener
     *
     * @param $bp_shortname
     * @param $bp_nav_type (not necessary but makes calling code more informative)
     * @param $caller
     * @param $bp_transtype
     *
     * @return string
     */
    private function get_sections_opener($bp_shortname, $bp_nav_type, $caller, $bp_transtype)
    {
      $return_val = '';
      if ($bp_nav_type === 'navigator') {
        $slider            = array();
        $slider[ 'class' ] = '';
        $slider[ 'data' ]  = '';


        if ('hover' === $this->build->blueprint[ '_blueprints_navigator-pager' ] && 'slider' === $this->build->blueprint[ '_blueprints_section-0-layout-mode' ]) {

          $return_val = apply_filters('arc-add-hover-buttons', $return_val, $this->build->blueprint);

        }
//          //TODO: Should the bp name be in the class or ID?
        $return_val .= '<div class="pzarc-sections pzarc-sections_' . $bp_shortname . ' pzarc-is_' . $caller . $slider[ 'class' ] . '"' . $slider[ 'data' ] . '>';
      } else {
        $return_val .= '<div class="pzarc-sections pzarc-sections_' . $bp_shortname . ' pzarc-is_' . $caller . '">';
      }

      return $return_val;
    }


    /**
     * @param $this ->criteria
     * @param $overrides
     */
    private function use_custom_query($overrides, $source_query_class)
    {
      // Is this a better way to code?
      self::load_criteria();

      $arc_query_source = new $source_query_class($this->build, $this->criteria);
      //   var_Dump($source_query_class);
      $arc_query_source->build_custom_query_options($overrides);

      $this->arc_query = $arc_query_source->get_custom_query($overrides);
      self::replace_wp_query(); // NOTE: This is only activated on pagination. So should only be used by legitimate post types
    }

    /**
     * @return string
     */
    private function load_criteria()
    {
      $registry = arc_Registry::getInstance();

      $content_post_types = $registry->get('post_types');
      $content_types      = array();
      foreach ($content_post_types as $key => $value) {
        if (isset($value[ 'blueprint-content' ])) {
          $content_types[ $value[ 'blueprint-content' ][ 'type' ] ] = $value[ 'blueprint-content' ][ 'prefix' ];
        }
      }
      $prefix = $content_types[ $this->build->blueprint[ '_blueprints_content-source' ] ];
      // Get values to use in criteria
      foreach ($this->build->blueprint as $key => $value) {
        if (strpos($key, $prefix) === 0) {
          $this->criteria[ $key ] = $value;
        }
      }
    }

    /**
     *
     */
    private function replace_wp_query()
    {
      // WordPress uses the main query for pagination. Need to get our query in there. http://wordpress.stackexchange.com/questions/120407/how-to-fix-pagination-for-custom-loops
      // We'll still use our query, but paging will be picked up from $wp_query. We'll reset $wp_query back after
      // followed every tute under the sun to get it to work otherwise, but nothin!
      if (!empty($this->build->blueprint[ '_blueprints_pagination' ])) {

        // Some things like dummy content don't make this an object
        if (is_object($this->arc_query)) {
          global $wp_query;
          $wp_query = $this->arc_query;
          wp_reset_postdata();
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
//
//      // This may not do anything since the query may not update!
//      // need to set nopaging too
//      if (!empty($this->build->blueprint[ '_blueprints_pagination' ])) {
//        $this->arc_query->query_vars[ 'nopaging' ] = false;
//      } else {
//        $this->arc_query->query_vars[ 'nopaging' ] = $this->criteria[ 'nopaging' ];
//      }
//      $this->arc_query->query_vars[ 'posts_per_page' ] = $this->criteria[ 'per_page' ];
//      $this->arc_query->query_vars[ 'ignore_sticky_posts' ] = $this->criteria[ 'ignore_sticky_posts' ];
//      $this->arc_query->query_vars[ 'offset' ] = $this->criteria[ 'offset' ];
//      $this->arc_query->query_vars[ 'orderby' ] = $this->criteria[ 'orderby' ];
//      $this->arc_query->query_vars[ 'order' ]   = $this->criteria[ 'order' ];
//
//      // TODO Try to get thiks working.
//      $this->arc_query = new WP_Query($this->arc_query->query_vars);
//      self::replace_wp_query(); // NOTE: This is only activated on pagination. So should only be used by legitimate post types
    }

    // My function to modify the main query object

// Hook my above function to the pre_get_posts action


    private function get_blueprint_content_class()
    {

      //TODO: oops! Need to get default content type when defaults chosen.
      $post_type = (empty($this->build->blueprint[ '_blueprints_content-source' ]) ? (empty($this->arc_query->queried_object->post_type) ? 'post' : $this->arc_query->queried_object->post_type) : $this->build->blueprint[ '_blueprints_content-source' ]);
      $registry  = arc_Registry::getInstance();

      // Build the query
      $content_source = $registry->get('content_source');
      $class          = 'arc_Panel_' . ('defaults' === $this->build->blueprint[ '_blueprints_content-source' ] ? 'defaults' : $post_type);
//      $class          = 'arc_Panel_' . ( 'defaults' === $this->build->blueprint[ '_blueprints_content-source' ]|| $this->content_source === 'defaults' ? 'defaults' : $post_type );
      pzdb($post_type);
      if (array_key_exists($this->build->blueprint[ '_blueprints_content-source' ], $content_source)) {
        require_once $content_source[ $this->build->blueprint[ '_blueprints_content-source' ] ] . '/class_arc_panel_' . strtolower($post_type) . '.php';
      }

      return $class;
    }

    // $t is used so third party navs can be written
    function add_navigation(&$t)
    {

      $class = 'arc_Navigator_' . $t->build->blueprint[ '_blueprints_navigator' ];

      $class = apply_filters('arc-navigator-class', $class, $t->build->blueprint);

      if (class_exists($class)) {
        $navigator = new $class($t->build->blueprint, $t->nav_items);
        if (isset($navigator)) {

          $navigator->render();

        }

        unset($navigator);

      }
    }

  }

  // EOC Architect

