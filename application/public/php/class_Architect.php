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

      require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Blueprint.php');
      $this->build = new arc_Blueprint($blueprint);
      if ($this->build->blueprint[ '_blueprints_content-source' ] == 'defaults' && $this->is_shortcode)
      {
        $this->build->blueprint[ 'err_msg' ] = 'Ooops! Need to specify a Contents Selection in your Blueprint to use a shortcode';
      }
      if (!empty($this->build->blueprint[ 'err_msg' ]))
      {
        echo $this->build->blueprint[ 'err_msg' ];

        return false;
      }

      // Good to go. Load all the classes
      require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Section.php');
      require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Navigator.php');
      require_once(PZARC_PLUGIN_PATH . '/public/php/class_arc_Pagination.php');
      require_once PZARC_PLUGIN_PATH . '/public/php/interface_arc_PanelDefinitions.php';


    }

    /**
     * @param $overrides
     */
    public function build($overrides)
    {
      do_action('arc_before_architect');
      echo '<div class="pzarchitect">';
      echo '<h1>START BUILD</h1>';
      $this->arc = array();
      $criteria  = array();

      $this->arc[ 'section' ][ 1 ] = arc_SectionFactory::create(1, $this->build->blueprint[ 'section' ][ 0 ],$this->build->blueprint['_blueprints_content-source']);

      $criteria[ 'panels_to_show' ] = $this->build->blueprint[ '_blueprints_section-0-panels-per-view' ];
//var_dump($this->build->blueprint);
      // hmm? if nopaging is true, it returns all records. Do we ever need that? Maybe we could manage pagination ourself then?!
      // $criteria[ 'nopaging' ] = ($this->build->blueprint['_blueprints_navigation']=='none');
      $criteria[ 'ignore_sticky_posts' ] = !$this->build->blueprint[ '_blueprints_sticky' ];
      $criteria[ 'offset' ] = $this->build->blueprint[ '_blueprints_skip' ];


      if ($this->build->blueprint[ 'section' ][ 1 ][ 'section-enable' ] && $this->build->blueprint[ '_blueprints_navigation' ] != 'navigator')
      {
        $this->arc[ 'section' ][ 2 ] = arc_SectionFactory::create(2, $this->build->blueprint[ 'section' ][ 1 ],$this->build->blueprint['_blueprints_content-source']);
        $criteria[ 'panels_to_show' ] .= $this->build->blueprint[ '_blueprints_section-1-panels-per-view' ];
      }

      if ($this->build->blueprint[ 'section' ][ 2 ][ 'section-enable' ] && $this->build->blueprint[ '_blueprints_navigation' ] != 'navigator')
      {
        $this->arc[ 'section' ][ 3 ] = arc_SectionFactory::create(3, $this->build->blueprint[ 'section' ][ 2 ],$this->build->blueprint['_blueprints_content-source']);
        $criteria[ 'panels_to_show' ] .= $this->build->blueprint[ '_blueprints_section-2-panels-per-view' ];
      }


      if ($this->build->blueprint[ '_blueprints_content-source' ] != 'defaults')
      {
        $prefix = '';
        switch ($this->build->blueprint[ '_blueprints_content-source' ])
        {
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
        foreach ($this->build->blueprint as $key => $value)
        {
          if (strpos($key, $prefix) === 0)
          {
            $criteria[ $key ] = $value;
          }
        }
        $this->query = self::query($this->build->blueprint[ '_blueprints_content-source' ], $criteria, $overrides);
      }
      else
      {
        global $wp_query;
        $this->query = $wp_query;
        // This may not do anything since the query may not update!
        // need to set nopaging too
        $this->query->query_vars['nopaging'] = $criteria['nopaging'];
        $this->query->query_vars['posts_per_page'] = $criteria['panels_to_show'];
      }


      // Loops
      self::loop(1);
      self::loop(2);
      self::loop(3);
      // End loops

      if ($this->build->blueprint[ '_blueprints_navigation' ] == 'navigator' && $this->build->blueprint[ '_blueprints_navigator-location' ] == 'outside')
      {
        $this->arc[ 'navigator' ] = new arc_Navigator();
      }
      elseif ($this->build->blueprint[ '_blueprints_navigation' ] == 'pagination')
      {
        $this->arc[ 'pagination' ] = new arc_Pagination('WP');
      }

      if (isset($this->arc[ 'navigator' ]))
      {
        $this->arc[ 'navigator' ]->render_navigator();
      }
      if (isset($this->arc[ 'pagination' ]))
      {
        $this->arc[ 'pagination' ]->render_pagination();
      }

      echo '</div> <!-- end architect -->';
      do_action('arc_after_architect');
      echo '<h1>END BUILD</h1>';

    }

    /**
     * @param $section
     */
    private function loop($section)
    {
      if (isset($this->arc[ 'section' ][ $section ]))
      {
        $class= 'arc_PanelDef_'.$this->build->blueprint[ '_blueprints_content-source' ];
        $panel_def = $class::panel_def();
        while ($this->query->have_posts())
        {
          $this->query->the_post();
          $this->arc[ 'section' ][ $section ]->render_panel($panel_def);
        }
      }
      unset($this->arc[ 'section' ][ $section ]);

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
      $query_options[ 'posts_per_page' ] = $criteria[ 'panels_to_show' ];
      $query_options[ 'ignore_sticky_posts' ] = $criteria[ 'ignore_sticky_posts' ];
      $query_options[ 'offset' ] = $criteria[ 'offset' ];

      //Lot of work!
      //     pzdebug($criteria);
      switch ($source)
      {

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
      if ($overrides)
      {
        $query_options[ 'post__in' ]       = explode(',', $overrides);
        $query_options[ 'posts_per_page' ] = count($query_options[ 'post__in' ]);
      }
      $query = new WP_Query($query_options);

      return $query;
    }

  }

  // EOC Architect

