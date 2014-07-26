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
    private $nav_items = array();

    /**
     * @param $blueprint
     * @param $is_shortcode
     */
    public function __construct($blueprint, $is_shortcode)
    {
      $this->is_shortcode = $is_shortcode;

      pzarc_set_defaults();

      require_once(PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_Section.php');
      require_once(PZARC_PLUGIN_APP_PATH . '/public/php/class_arc_Blueprint.php');

      $this->build = new arc_Blueprint($blueprint);

//      pzdebug(((array)$this->build->blueprint));

      if ($this->build->blueprint[ '_blueprints_content-source' ] == 'defaults' && $this->is_shortcode) {

        $this->build->blueprint[ 'err_msg' ] = '<p class="message-warning">Ooops! Need to specify a <strong>Contents Selection</strong> in your Blueprint to use a shortcode. You cannot use Defaults.</p>';

      }

      if (!empty($this->build->blueprint[ 'err_msg' ])) {

        echo $this->build->blueprint[ 'err_msg' ];

        return false;

      }

      // Good to go. Load all the classes

      require_once(PZARC_PLUGIN_APP_PATH . '/shared/architect/php/arc-functions.php');
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
      $bp_shortname = $this->build->blueprint[ '_blueprints_short-name' ];
      $bp_nav_type  = $this->build->blueprint[ '_blueprints_navigation' ];
      $bp_nav_pos   = $this->build->blueprint[ '_blueprints_navigator-position' ];
      $bp_transtype = $this->build->blueprint[ '_blueprints_transitions-type' ];

      $this->arc = array();

      self::set_generic_criteria();

      // Set vars to identify if we need to display sections 2 and 3.
      $do_section_2 = ($this->build->blueprint[ '_blueprints_section-1-enable' ] && $bp_nav_type != 'navigator');
      $do_section_3 = ($this->build->blueprint[ '_blueprints_section-2-enable' ] && $bp_nav_type != 'navigator');

      // TODO: Are all these 'self's too un-oop?
      // Get pagination
      // Need to do this before we touch the query!?
      if ($bp_nav_type == 'pagination') {

        switch (true) {

          case is_home():
            $class                     = 'arc_Pagination_' . (!$this->build->blueprint[ '_blueprints_pager' ] ? 'prevnext' : $this->build->blueprint[ '_blueprints_pager' ]);
            $this->arc[ 'pagination' ] = new $class;
            break;

          case (is_singular()):
            $class                     = 'arc_Pagination_' . (!$this->build->blueprint[ '_blueprints_pager-single' ] ? 'prevnext' : $this->build->blueprint[ '_blueprints_pager-single' ]);
            $this->arc[ 'pagination' ] = new $class;
            break;

          case is_archive():
            $class                     = 'arc_Pagination_' . (!$this->build->blueprint[ '_blueprints_pager-archives' ] ? 'prevnext' : $this->build->blueprint[ '_blueprints_pager-archives' ]);
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


      // at tis point we have the necessary info to populate the navigator. So let's do it!
      $this->nav_items = self::get_nav_items($this->build->blueprint[ '_blueprints_navigator' ]);

      // TODO: Show or hide blueprint if no content

      do_action('arc_before_architect');


      /** BLUEPRINT */
      echo '<div class="pzarchitect pzarc-blueprint pzarc-blueprint_' . $this->build->blueprint[ '_blueprints_short-name' ] . ' nav-' . $bp_nav_type . ' icomoon">';

      /** NAVIGATOR TOP*/

      if ($bp_nav_type === 'navigator' && ('top' === $bp_nav_pos || 'left' === $bp_nav_pos)) {
        self::display_navigation('tl');
      }


      self::display_page_title($this->build->blueprint[ '_blueprints_page-title' ]);

      echo self::get_sections_opener($bp_shortname, $bp_nav_type, $caller, $bp_transtype);

      do_action('arcNavBeforeSection-{$bpshortname}');


      /** Display pagination above */
      if (isset($this->arc[ 'pagination' ])) {

        do_action('arcBeforePaginationAbove');

        // TODO: Make this replace via an action or filter
        $this->arc[ 'pagination' ]->render($this->arc_query, 'nav-above');

        do_action('arcAfterPaginationAbove');

      }

      /** LOOPS */
      $this->nav_items = self::loop(1);

      if ($do_section_2) {

        $notused = self::loop(2);

      }

      if ($do_section_3) {

        $notused = self::loop(3);

      }

      // End loops
      echo '</div> <!-- end blueprint sections -->';


      // Don't allow pagination on pages it doesn't work on!
      //   Todo : setup pagination for single or blog index

      /** PAGINATION BELOW  */
      if (isset($this->arc[ 'pagination' ])) {

        do_action('arcBeforePaginationBelow');

        $this->arc[ 'pagination' ]->render($this->arc_query, 'nav-below');

        do_action('arcAfterPaginationBelow');

      }

      /** NAVIGATION BELOW */
      if ($bp_nav_type === 'navigator' && ('bottom' === $bp_nav_pos || 'right' === $bp_nav_pos)) {
        self::display_navigation('br');
      }


      echo '</div> <!-- end pzarchitect blueprint ' . $this->build->blueprint[ '_blueprints_short-name' ] . '-->';

      do_action('arc_after_architect');


      // Set our original query back in case we had pagination.
      wp_reset_postdata(); // Pretty sure this goes here... Not after the query reassignment
      $wp_query = null;
      $wp_query = $original_query;

    }

    private function display_navigation($location)
    {


      $class                    = 'arc_Navigator_' . $this->build->blueprint[ '_blueprints_navigator' ];
      $this->arc[ 'navigator' ] = new $class($this->build->blueprint, $this->nav_items);

      if ('tl' === $location) {

        // TODO: How do we make thi sgo into the action?WP-Navi does it!
        if (isset($this->arc[ 'navigator' ])) {


          do_action('arcBeforeNavigationAbove');

          $this->arc[ 'navigator' ]->render();

          do_action('arcAfterNavigationAbove');

        }

        // Nav left or top
        do_action('arc_navigation_top');
        do_action('arc_navigation_left');

      }

      if ('br' === $location) {

        // TODO: How do we make thi sgo into the action?WP-Navi does it!
        if (isset($this->arc[ 'navigator' ])) {


          do_action('arcBeforeNavigationBelow');

          $this->arc[ 'navigator' ]->render();

          do_action('arcAfterNavigationBelow');

        }

        // Nav right or bottom
        do_action('arc_navigation_right');
        do_action('arc_navigation_bottom');

      }

      unset($this->arc[ 'navigator' ]);


    }

    /**
     * get_nav_items
     */
    private function get_nav_items($blueprints_navigator)
    {

      $nav_items = array();

//      var_dump($blueprints_navigator, $this->arc_query->posts);

      foreach ($this->arc_query->posts as $the_post) {

        switch ($blueprints_navigator) {

          case 'tabbed':
            $nav_items[ ] = '<span class="' . $blueprints_navigator . '">' . $the_post->post_title . '</span>';
            break;

          case 'thumbs':

            if ('attachment' === $the_post->post_type) {

              // TODO: Will need to change this to use the thumb dimensions set in the blueprint viasmall , medium, large
              $thumb = wp_get_attachment_image($the_post->ID, array(50, 50));

            } else {

              $thumb = get_the_post_thumbnail($the_post->ID, array(50, 50));

            }

            $thumb = (empty($thumb) ? '<img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/missing-image.png" width="50" height="50">' : $thumb);

            $nav_items[ ] = '<span class="' . $blueprints_navigator . '">' . $thumb . '</span>';
            break;

          case 'bullets':
          case 'numbers':
          case 'buttons':
            //No need for content on these
            $nav_items[ ] = '';
            break;

        }

      }

      return $nav_items;
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
      $limited = ((int)$this->build->blueprint[ '_blueprints_section-1-enable' ] * (int)$this->build->blueprint[ '_blueprints_section-1-panels-limited' ])
          || ((int)$this->build->blueprint[ '_blueprints_section-2-enable' ] * (int)$this->build->blueprint[ '_blueprints_section-2-panels-limited' ])
          || (int)$this->build->blueprint[ '_blueprints_section-0-panels-limited' ];

      if (!$limited) {

        $this->criteria[ 'panels_to_show' ] = -1;
        $this->criteria[ 'nopaging' ]       = true;

      } else {

        $this->criteria[ 'panels_to_show' ] =
            $this->build->blueprint[ '_blueprints_section-0-panels-per-view' ] +
            ((int)$this->build->blueprint[ '_blueprints_section-1-enable' ] * $this->build->blueprint[ '_blueprints_section-1-panels-per-view' ]) +
            ((int)$this->build->blueprint[ '_blueprints_section-2-enable' ] * $this->build->blueprint[ '_blueprints_section-2-panels-per-view' ]);
        $this->criteria[ 'nopaging' ]       = false;

      }

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
     * display_page_title
     */
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
          case is_singular() :
            $title = single_post_title(null, false);
            break;
        }
        if ($title) {
          echo '<div class="pzarc-page-title"><h2>' . $title . '</h2></div>';
        }
      }
    }

    /**
     *
     * get_sections_opener
     *
     * @param $bp_shortname
     * @param $bp_nav_type (not necessary but makes calling code more informative)
     * @param $caller
     * @param $bp_transtype
     * @return string
     */
    private function get_sections_opener($bp_shortname, $bp_nav_type, $caller, $bp_transtype)
    {
      $return_val = '';
      if ($bp_nav_type === 'navigator') {
        $slider                = array();
        $slider[ 'class' ]     = '';
        $slider[ 'dataid' ]    = '';
        $slider[ 'datatype' ]  = '';
        $slider[ 'class' ]     = ' swiper-container slider swiper-container-' . $bp_shortname;
        $slider[ 'dataid' ]    = ' data-swiperid="' . $bp_shortname . '"';
        $slider[ 'datatype' ]  = ' data-navtype="' . $bp_nav_type . '"';
        $slider[ 'datatrans' ] = ' data-transtype="' . $bp_transtype . '"';


        $duration             = $this->build->blueprint[ '_blueprints_transitions-duration' ] * 1000;
        $interval             = $this->build->blueprint[ '_blueprints_transitions-interval' ] * 1000;
        $skip_thumbs          = $this->build->blueprint[ '_blueprints_navigator-skip-thumbs' ];
        $slider[ 'dataopts' ] = 'data-opts="{#tduration#:' . $duration . ',#tinterval#:' . $interval . ',#tskip#:' . $skip_thumbs . '}"';

        $return_val .= '<button type="button" class="pager arrow-left icon-arrow-left4"></button>';
        $return_val .= '<button type="button" class="pager arrow-right icon-uniE60D"></button>';
//          //TODO: Should the bp name be in the class or ID?
        $return_val .= '<div class="pzarc-sections_' . $bp_shortname . ' pzarc-is_' . $caller . $slider[ 'class' ] . '"' . $slider[ 'dataid' ] . $slider[ 'datatype' ] . $slider[ 'dataopts' ] . $slider[ 'datatrans' ] . '>';
      } else {
        $return_val .= '<div class="pzarc-sections_' . $bp_shortname . ' pzarc-is_' . $caller . '">';
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
    private function build_meta_definition($panel_def, $section_panel_settings)
    {
      //replace meta1innards etc
      $meta = array_pad(array(), 3, null);
      foreach ($meta as $key => $value) {
        $i = $key + 1;
//        $meta[ $key ]             = preg_replace('/%(\\w*)%/u', '{{$1}}', (!empty($section_panel_settings[ '_panels_design_meta' . $i . '-config' ]) ? $section_panel_settings[ '_panels_design_meta' . $i . '-config' ] : null));
        $first_pass               = preg_replace('/%(\\w|[\\:\\-])*%/uiUmx', '{{$0}}', (!empty($section_panel_settings[ '_panels_design_meta' . $i . '-config' ]) ? $section_panel_settings[ '_panels_design_meta' . $i . '-config' ] : null));
        $meta[ $key ]             = preg_replace("/%(.*)%/uiUmx", "$1", $first_pass);
        $panel_def[ 'meta' . $i ] = str_replace('{{meta' . $i . 'innards}}', $meta[ $key ], $panel_def[ 'meta' . $i ]);
        $panel_def[ 'meta' . $i ] = str_replace('{{date}}', $panel_def[ 'datetime' ], $panel_def[ 'meta' . $i ]);
        $panel_def[ 'meta' . $i ] = str_replace('{{author}}', $panel_def[ 'author' ], $panel_def[ 'meta' . $i ]);
        $panel_def[ 'meta' . $i ] = str_replace('{{email}}', $panel_def[ 'email' ], $panel_def[ 'meta' . $i ]);
        $panel_def[ 'meta' . $i ] = str_replace('{{categories}}', $panel_def[ 'categories' ], $panel_def[ 'meta' . $i ]);
        $panel_def[ 'meta' . $i ] = str_replace('{{tags}}', $panel_def[ 'tags' ], $panel_def[ 'meta' . $i ]);
// TODO: This maybe meant to be editlink
//        $panel_def[ 'meta' . $i ] = str_replace('{{edit}}', $panel_def[ 'edit' ], $panel_def[ 'meta' . $i ]);
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
        case 'posts':
          $prefix = '_content_posts_';
          break;
        case 'galleries':
        case 'gallery':
          $prefix = '_content_galleries_';
          break;
        case 'page':
        case 'pages':
          $prefix = '_content_pages_';
          break;
        case 'slides':
        case 'slide':
          $prefix = '_content_slides_';
          break;
        case 'snippets':
        case 'snippet':
          $prefix = '_content_snippets_';
          break;
        case 'cpt':
          $prefix = '_content_cpt_';
          break;

        //TODO: We don't really want to do thsi, do we?
        default:
          $prefix = '_content_posts_';
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
      // TODO: Leave this // until id'ed why $prefix is empty. And prob set up a better response
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

      $this->arc_query->query_vars[ 'orderby' ] = $this->criteria[ 'orderby' ];
      $this->arc_query->query_vars[ 'order' ]   = $this->criteria[ 'order' ];

    }

    /**
     * @param $source
     * @param $this ->criteria
     * @param $overrides
     * @return WP_Query
     */
    private function build_custom_query($overrides)
    {

      //build the new query
      $source = $this->build->blueprint[ '_blueprints_content-source' ];

      $query_options = array();

      global $paged;

      //Paging parameters
      if ($this->build->blueprint[ '_blueprints_navigation' ] == 'pagination') {

        // This is meant ot be the magic tonic to make pagination work on static front page. Bah!! Didnt' for me - ever
        // Ah! It only doesn't work with Headway!
        if (get_query_var('paged')) {

          $paged = get_query_var('paged');

        } elseif (get_query_var('page')) {

          $paged = get_query_var('page');

        } else {

          $paged = 1;

        }

// TODO: WTF IS THIS?! Surely just some debugging left behind!
//        query_posts('posts_per_page=3&paged=' . $paged);

//        $query_options[ 'nopaging' ]       = false;
        $query_options[ 'posts_per_page' ] = $this->criteria[ 'panels_to_show' ];
        $query_options[ 'pagination' ]     = true;
        $query_options[ 'paged' ]          = $paged;

      } else {

        $query[ 'nopaging' ]               = $this->criteria[ 'nopaging' ];
        $query_options[ 'posts_per_page' ] = $this->criteria[ 'panels_to_show' ];

        $query_options[ 'offset' ] = $this->criteria[ 'offset' ];
      }

      // these two break paging yes?
      $query_options[ 'ignore_sticky_posts' ] = $this->criteria[ 'ignore_sticky_posts' ];

      //Lot of work!
      //     pzdebug($this->criteria);

      // TODO: We're going to have to make this pluggable too! :P Probably with a loop?

      /** General content filters */
      $cat_ids = $this->criteria[ 'category__in' ];
//      var_dump(get_the_category(),is_category());

      // TODO: This doesn't work right yet
//      if ($this->build->blueprint[ '_content_general_sub-cats' ]  && is_category()) {
//        $current_cat = get_the_category();
//        $archive_cat = $current_cat->cat_ID;
//        $cat_kids = get_categories(array('child_of' => $archive_cat));
//
//        foreach ($cat_kids as $kid) {
//          $cat_ids[] = $kid->cat_ID;
//        }
//
//      }

      $query_options[ 'category__in' ]     = (!empty($this->criteria[ 'category__in' ]) ? $cat_ids : null);
      $query_options[ 'category__not_in' ] = (!empty($this->criteria[ 'category__in' ]) ? $this->criteria[ 'category__not_in' ] : null);

      $query_options[ 'tag__in' ]     = (!empty($this->criteria[ 'tag__in' ]) ? $this->criteria[ 'tag__in' ] : null);
      $query_options[ 'tag__not_in' ] = (!empty($this->criteria[ 'tag__in' ]) ? $this->criteria[ 'tag__not_in' ] : null);


      /** Custom taxonomies  */
      if (!empty($this->build->blueprint[ '_content_general_other-tax-tags' ])) {
        $query_options[ 'tax_query' ] = array(
            array(
                'taxonomy' => $this->build->blueprint[ '_content_general_other-tax' ],
                'field'    => 'slug',
                'terms'    => explode(',', $this->build->blueprint[ '_content_general_other-tax-tags' ]),
                'operator' => $this->build->blueprint[ '_content_general_tax-op' ]
            ),
        );
      }
      /** Specific content filters */
      switch ($source) {

        case 'post':
          $query_options[ 'post_type' ] = 'post';
          break;

        case 'cpt':
          $query_options[ 'post_type' ] = $this->build->blueprint[ '_content_cpt_custom-post-type' ];
          break;

        // TODO: could we build this into the panel def or somewhere else so more closely tied to the content type stuff

        case 'gallery':

          $prefix         = '_content_galleries_';
          $gallery_source = !empty($overrides) ? 'ids' : $this->build->blueprint[ $prefix . 'gallery-source' ];

          switch ($gallery_source) {

            case 'galleryplus':
              $gallery_post = get_post($this->build->blueprint[ $prefix . 'galleryplus' ]);
              preg_match_all('/' . get_shortcode_regex() . '/s', $gallery_post->post_content, $matches);
              $ids = '';
              foreach ($matches[ 0 ] as $match) {
                if (strpos($match, '[gallery ids=') === 0) {
                  $ids                                    = str_replace('[gallery ids="', '', $match);
                  $ids                                    = str_replace('"]', '', $ids);
                  $query_options[ 'post_type' ]           = 'attachment';
                  $query_options[ 'post__in' ]            = explode(',', $ids);
                  $query_options[ 'post_status' ]         = array('publish', 'inherit', 'private');
                  $query_options[ 'ignore_sticky_posts' ] = true;
                  // Only do first one
                  break;
                }
              }
              break;

            case 'images':
            case 'ids':

              $ids = !empty($overrides) ? $overrides : $this->criteria[ $prefix . 'specific-images' ];

              $query_options[ 'post_type' ]           = 'attachment';
              $query_options[ 'post__in' ]            = (!empty($ids) ? explode(',', $ids) : null);
              $query_options[ 'post_status' ]         = array('publish', 'inherit', 'private');
              $query_options[ 'ignore_sticky_posts' ] = true;
              break;
          }
          break;

        case 'snippets':
          $query_options[ 'post_type' ] = 'pz_snippets';
          break;

        case 'slides':
          $query_options[ 'post_type' ] = 'pzsp-slides';
          break;

      }

      // Order. This is always set
      $query_options[ 'orderby' ] = $this->criteria[ 'orderby' ];
      $query_options[ 'order' ]   = $this->criteria[ 'order' ];


      // OVERRIDES
      // currently this is the only bit that really does anything
      if ($overrides) {

        $query_options[ 'post__in' ]       = explode(',', $overrides);
        $query_options[ 'posts_per_page' ] = count($query_options[ 'post__in' ]);
      }

      //    var_dump($query_options);

      $this->arc_query = new WP_Query($query_options);

    }


    /**
     * @param $section_no
     */
    private function loop($section_no)
    {

      $section[ $section_no ] = $this->build->blueprint[ 'section_object' ][ $section_no ];
      // oops! Need to get default content type when defaults chosen.
      $post_type = (empty($this->build->blueprint[ '_blueprints_content-source' ]) || 'defaults' === $this->build->blueprint[ '_blueprints_content-source' ] ?
          (empty($this->arc_query->queried_object->post_type) ? 'post' : $this->arc_query->queried_object->post_type) :
          $this->build->blueprint[ '_blueprints_content-source' ]);

      if (empty($post_type)) {

        // Should never get this message now.

        pzarc_msg('No post type specified', 'error');

        return null;

      }

      $class = 'arc_Panel_' . $post_type;

      // TODO: Should we fall back to Post post type if unknown??
      // Use an include incase it doesn't exist!
      @include_once PZARC_PLUGIN_APP_PATH . '/public/php/post_types/class_arc_Panel_' . ucfirst($post_type) . '.php';

      // TODO: Add an option to throw an error if post type doesn't exist, instead of falling back on Post
      // Although.. this makes handling custom types slightly easier
      if (!class_exists($class)) {

//        pzarc_msg(__('Post type ', 'pzarchitect') . '<strong>' . $post_type . '</strong>' . __(' has no panel definition and cannot be displayed.', 'pzarchitect'), 'error');

        $class = 'arc_Panel_post';

        include_once PZARC_PLUGIN_APP_PATH . '/public/php/post_types/class_arc_Panel_Post.php';

//        return null;

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

      $section[ $section_no ]->open_section();

      while ($this->arc_query->have_posts()) {

        $this->arc_query->the_post();

        // TODO: This may need to be modified for other types that dont' use post_title
        // TODO: Make dumb so can be pluggable for other navs
        switch ($this->build->blueprint[ '_blueprints_navigator' ]) {

          case 'tabbed':
            $nav_items[ ] = '<span class="' . $this->build->blueprint[ '_blueprints_navigator' ] . '">' . $this->arc_query->post->post_title . '</span>';
            break;

          case 'thumbs':

            if ('attachment' === $this->arc_query->post->post_type) {

              // TODO: Will need to change this to use the thumb dimensions set in the blueprint viasmall , medium, large
              $thumb = wp_get_attachment_image($this->arc_query->post->ID, array(50, 50));

            } else {

              $thumb = get_the_post_thumbnail($this->arc_query->post->ID, array(50, 50));

            }

            $thumb = (empty($thumb) ? '<img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/missing-image.png" width="50" height="50">' : $thumb);

            $nav_items[ ] = '<span class="' . $this->build->blueprint[ '_blueprints_navigator' ] . '">' . $thumb . '</span>';
            break;

          case 'bullets':
          case 'numbers':
          case 'buttons':
            //No need for content on these
            $nav_items[ ] = '';
            break;

        }
        $section[ $section_no ]->render_panel($panel_def, $i, $class, $panel_class, $this->arc_query);

        if ($i++ >= $this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-panels-per-view' ] && !empty($this->build->blueprint[ '_blueprints_section-' . ($section_no - 1) . '-panels-limited' ])) {
          break;

        }

      }
      $section[ $section_no ]->close_section();

      // Unsetting causes it to run the destruct, which closes the div!
      unset($section[ $section_no ]);

      return $nav_items;
    }
  }

  // EOC Architect

