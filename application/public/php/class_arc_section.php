<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 25/04/2014
   * Time: 9:11 PM
   */
  /* Factory pattern */

  class arc_SectionFactory
  {
    public static function create($number, $section, $source, $navtype, $layout_mode, $slider_type = null, $table_titles = null, $panel_name = 'legacy-panel', &$blueprint)
    {
      //    var_dump($number, $section, $source, $navtype, $layout_mode, $slider_type, $section_title, $table_titles, $panel_name);
      return new arc_Section($number, $section, $source, $navtype, $layout_mode, $slider_type, $table_titles, $panel_name, $blueprint);
    }

  }

  class arc_Section
  {
    private $section_number;
    public $section;
    private $source;
    private $data;
    private $slider;
    private $navtype;
    private $layout_mode;
    private $blueprint;
    private $section_title;
    private $slider_type;
    private $rsid;
    private $table_accordion_titles;
    public $panel_number;

    /**
     * @param      $number
     * @param      $section_panel
     * @param      $content_source
     * @param      $navtype
     * @param      $layout_mode
     * @param null $slider_type
     * @param null $section_title
     * @param      $table_accordion_titles
     * @internal param $blueprint
     */
    public function __construct($number, $section_panel, $content_source, $navtype, $layout_mode, $slider_type = null, $table_accordion_titles = array(), $panel_name = null, &$blueprint)
    {
      $this->section_number         = $number;
      $this->section                = $section_panel;
      $this->panel_name             = $panel_name;
      $this->source                 = $content_source;
      $this->navtype                = $navtype;
      $this->layout_mode            = $layout_mode;
      $this->slider_type            = $slider_type;
      $this->rsid                   = 'rsid' . (rand(1, 9999) * rand(10000, 99999));
      $this->table_accordion_titles = $table_accordion_titles;
      $this->blueprint              = $blueprint;
      pzdb('construct section');
      //     wp_enqueue_style('pzarc_css_panel_' . $this->panel_name);

      if ('table' === $this->layout_mode) {
        $this->table_accordion_titles = $table_accordion_titles;
      }
      add_action('wp_print_footer_scripts', array($this, 'extra_scripts'));

      // If global is off, then no retina
      // If global is on, then obey local setting
      // That is, they both must be on

      global $_architect_options;
      if (!empty($_architect_options[ 'architect_enable-retina-images' ]) && !empty($this->section[ 'section-panel-settings' ][ '_panels_settings_use-retina-images' ])) {
        wp_enqueue_script('js-retinajs');
      }

      add_shortcode('arcshare', array($this, 'arcshare_shortcode'));


    }


    function extra_scripts()
    {
      // This is a much nicer way than creating files!!! Wonder where else I can use it?

      // Tabular scripts
      if ('table' === $this->layout_mode) {
        print('<script>jQuery(document).ready(function(){ jQuery("#' . $this->rsid . '").DataTable(); });</script>');
      }
    }

    function open_section()
    {
      pzdb('open_section');
      // Add the excerpt filters
      if (!empty($this->section[ 'section-panel-settings' ][ '_panels_design_excerpts-word-count' ])) {
        add_filter('excerpt_length', array(&$this, 'set_excerpt_length'), 999);
      }
      if (!empty($this->section[ 'section-panel-settings' ][ '_panels_design_readmore-truncation-indicator' ])) {
        add_filter('excerpt_more', array(&$this, 'set_excerpt_more'), 999);
      }


      do_action("arc_before_section_{$this->section_number}");

      $this->slider = ($this->section_number === 1 && ($this->navtype === 'navigator'))
          ? array('wrapper' => ' arc-slider-wrapper',
                  'slide'   => ' arc-slider-slide')
          : array('wrapper' => '',
                  'slide'   => '');


      wp_enqueue_script('js-front');

      $isotope      = '';
      $accordion    = '';
      $layout_class = 'tiles';
      switch ($this->layout_mode) {

        case 'masonry':
//          $isotope = 'data-isotope-options=\'{ "layoutMode": "masonry","itemSelector": ".pzarc-panel","masonry":{"columnWidth":50,"gutter":20}}\'';

          // Do we load up the MAsonry here?
          wp_enqueue_script('js-imagesloaded');
          wp_enqueue_script('js-isotope-v2');
          wp_enqueue_script('js-isotope-packery');
          wp_enqueue_script('js-front-isotope');
//          add_action('init',array($this,'init_scripts'));
//          $isotope      = 'data-isotope-options=\'{ "layoutMode": "masonry","itemSelector": ".pzarc-panel","masonry":{"columnWidth":".grid-sizer","gutter":".gutter-sizer"}}\'';
          $isotope      = 'data-uid="' . $this->rsid . '"';
          $layout_class = 'js-isotope';
          break;

        case 'accordion':
          $layout_class = 'accordion';
          $accordion    = ' data-collapse="accordion"';
          wp_enqueue_script('js-jquery-collapse');
          break;

        case 'table':
          wp_enqueue_script('js-datatables');
          wp_enqueue_style('css-datatables');
          $layout_class = 'datatables';
          break;

      }

      // TODO: Might need to change js-isotope to masonry - chekc impact tho
      // TODO Accordion

      $slider            = array();
      $slider[ 'class' ] = '';
      $slider[ 'data' ]  = '';

      if (($this->layout_mode === 'slider' || $this->layout_mode === 'tabbed')) {

        $slider = apply_filters('arc-set-slider-data', $slider, $this->blueprint);

      }
      echo '<' . ('table' !== $this->layout_mode ? 'div' : 'table') . ' id="' . $this->rsid . '"
       class="' . $layout_class . ' pzarc-section pzarc-section_' . $this->section_number . ' pzarc-section-using-' . $this->panel_name . ' ' . $slider[ 'class' ] . '"' . $isotope . $accordion . ' ' . $slider[ 'data' ] . '>';

      // Table heading stuff
      if ('table' === $this->layout_mode) {

        $settings = $this->section[ 'section-panel-settings' ];
        $toshow   = json_decode($settings[ '_panels_design_preview' ], true);
        $widths   = array();

        // Set the column widths to the widths set for the components
        foreach ($toshow as $k => $v) {
          if ($v[ 'show' ]) {
            echo '<col style="width:' . $v[ 'width' ] . '%;">';
            $widths[] = $v[ 'width' ];
          }
        }
        echo '<thead><tr>';

        $i = 0;
        // If any titles are missing, the remainder are blanked
        $this->table_accordion_titles = (is_array($this->table_accordion_titles) ? $this->table_accordion_titles : array($this->table_accordion_titles));
        $this->table_accordion_titles = array_pad($this->table_accordion_titles, count($widths), '');

        $i = 0;
        foreach ($this->table_accordion_titles as $title) {
          echo '<th style="width:' . $widths[ $i++ ] . '%;">' . $title . '</th>';
        }
        echo '</tr></thead>';
      }

      // Masonry stuff
      if ('masonry' === $this->layout_mode) {
        echo '<div class="grid-sizer"></div><div class="gutter-sizer"></div>';

      }

    }

    /**
     * set_excerpt_length
     * @param $excerpt_length
     * @return mixed
     */
    function set_excerpt_length($excerpt_length)
    {
      return $this->section[ 'section-panel-settings' ][ '_panels_design_excerpts-word-count' ];
    }

    function set_excerpt_more($excerpt_more)
    {
//      $new_more = $this->section[ 'section-panel-settings' ][ '_panels_design_readmore-truncation-indicator' ];
//      $new_more .= ($this->section[ 'section-panel-settings' ][ '_panels_design_readmore-text' ] ? '<a href="' . get_the_permalink() . '" class="readmore moretag">' . $this->section[ 'section-panel-settings' ][ '_panels_design_readmore-text' ] . '</a>' : null);

      return pzarc_make_excerpt_more($this->section[ 'section-panel-settings' ]);

    }

    /**
     * close_section
     */
    function close_section()
    {
      $derf = '</' . ('table' !== $this->layout_mode ? 'div' : 'table') . '><!-- End section ' . $this->section_number . ' -->';
      //    var_Dump($derf);
      echo $derf;
      do_action("arc_after_section_{$this->section_number}");

      remove_filter('excerpt_length', array(&$this, 'set_excerpt_length'), 999);
      remove_filter('excerpt_more', array(&$this, 'set_excerpt_more'), 999);
      pzdb('close_section');

    }

    /**
     * @param $panel_def
     */
    public function render_panel($panel_def, $panel_number, $class, $panel_class, &$arc_query)
    {
//      var_dump($panel_number, $class, $panel_class);
      pzdb('top of render panel ' . get_the_id());
      $this->panel_number = $panel_number;
      if (!empty($arc_query->post)) {
        $post   = $arc_query->post;
        $postid = $arc_query->post->ID;
      } else {
        // Then it is an array

        // This happens on non-post types, like dummy content type
        // TODO: Make these something more useful!
        $post   = $arc_query[ $this->panel_number - 1 ];
        $postid = 'NoID';

      }
//      var_dump($arc_query->post);
      $postid   = (empty($postid) ? 'NoID' : $postid);
      $settings = $this->section[ 'section-panel-settings' ];
      $toshow   = json_decode($settings[ '_panels_design_preview' ], true);
      pzdb('json decode ' . get_the_id());
      $panel_class->set_data($post, $toshow, $settings,$this->panel_number);
      pzdb('set data ' . get_the_id());
//      $elements = array();
//
//      // Massage toshow to be more usable here
//      foreach ($toshow as $k => $v) {
//        if ($v[ 'show' ]) {
//          $elements[ ] = array_merge(array('key' => $k), $v);
//        }
//      }

      $line_out = $panel_def;

      // We do want to provide actions so want to use the sequence
      do_action('arc_before_panel_open_action');
      $nav_item[ $this->panel_number ] = null;
      // This is for adding nav items to each panel. Used by navs like accordions
      // Although we could do it already, we want to kepp it in the nav class for extensibility
      echo apply_filters('arc_before_panel_open_filter', $nav_item[ $this->panel_number ]);

      // TODO: What's this??
      //       echo '<div class="js-isotope pzarc-section pzarc-section-' . $key . '" data-isotope-options=\'{ "layoutMode": "'.$pzarc_section_info['section-layout-mode'].'","itemSelector": ".pzarc-panel" }\'>';


      $image_in_bg = (!empty($toshow[ 'image' ][ 'show' ]) && $settings[ '_panels_design_feature-location' ] === 'fill') ? ' using-bgimages' : '';

//      switch ($settings[ '_panels_design_background-position' ]) {
//
//        case 'fill':
//          $image_in_bg = ' using-bgimages';
//          break;
//
//        case 'align':
//          $image_in_bg = ' using-aligned-bgimages';
//          break;
//
//      }
      // TODO: Added an extra div here to pre-empt the structure needed for accordions. Tho, needs some work as it breaks layout. Maybe conditional
      // TODO: Probably use jQuery Collapse for accordions

      /** ACCORDION TITLES */
      if ('accordion' === $this->layout_mode) {
        //This is a Dummy content specific hack fix
        if (is_array($post)) {
          $accordion_title = (isset($this->table_accordion_titles[ $this->panel_number - 1 ])) ? $this->table_accordion_titles[ $this->panel_number - 1 ] : $post[ 'title' ][ 'title' ];
        } else {
          $accordion_title = (isset($this->table_accordion_titles[ $this->panel_number - 1 ])) ? $this->table_accordion_titles[ $this->panel_number - 1 ] : $post->post_title;
        }
//        $accordion_title = isset($post['title']['title'])?$post['title']['title']:$post->post_title;
//        if (isset($this->table_accordion_titles) && !empty($this->table_accordion_titles) && isset($this->table_accordion_titles[ $panel_def ])) {
//          $accordion_title = do_shortcode($this->table_accordion_titles[ $this->panel_number ]);
//        }
        //'_blueprint_section-' . $this->section_number . '-accordion-titles'
        echo '<div class="pzarc-accordion title ' . (($this->panel_number === 1 && !empty($this->blueprint[ '_blueprints_accordion-closed' ])) ? 'open' : 'close') . '">' . $accordion_title . '</div>';
      }


      static $panel_count = 1;
      $odds_evens_bp = ($panel_count++ % 2 ? ' odd-panel' : ' even-panel');


      // Add standard identifying WP classes to the whole panel
      // TODO: WHY??? Is that what WP does? Aren't they in the article anyways? Temporarily don't do it.
      $postmeta_classes = ' ' . $panel_class->data[ 'posttype' ] . ' type-' . $panel_class->data[ 'posttype' ] . ' status-' . $panel_class->data[ 'poststatus' ] . ' format-' . $panel_class->data[ 'postformat' ] . ' ';
//      echo '<' . ('table' !== $this->layout_mode ? 'div' : 'tr') . ' class="pzarc-panel pzarc-panel_' . $settings[ '_panels_settings_short-name' ] . ' pzarc-panel-no_' . $this->panel_number . $this->slider[ 'slide' ] . $image_in_bg . $odds_evens_bp . $odds_evens_section . $postmeta_classes . '" >';
      $classes = 'pzarc-panel pzarc-panel_' . $this->panel_name . ' pzarc-panel-no_' . $this->panel_number . $this->slider[ 'slide' ] . $image_in_bg . $odds_evens_bp.' arc-layout-'.$this->layout_mode;

      /**********************************************************************************************************************************
       *
       * Open the Panel div
       *
       **********************************************************************************************************************************/

      $moreclick_class = (!empty($settings[ '_panels_design_more-click-action' ]) && $settings[ '_panels_design_more-click-action' ] !== 'none')?' pzarc-'.$settings[ '_panels_design_more-click-action' ].' ':'';

      echo '<' . ('table' !== $this->layout_mode ? 'div' : 'tr') . ' id="bp' . $this->blueprint[ 'blueprint-id' ] . '-' . $this->panel_number . '" class="'. apply_filters('arc-extend-panel-classes', $classes, $this->blueprint) . ' ' . apply_filters('arc-extend-panel-classes_' . $this->panel_name, '', $this->blueprint) .$moreclick_class . '" ' . apply_filters('arc-extend-panel-data', '', $this->blueprint) . '>';

      //
      if (!empty($settings[ '_panels_design_link-panel' ])) {
        echo '<a href="' . apply_filters('arc-overlay-permalink', get_the_permalink()) . '" class="pzarc-panel-overlay"></a>';
      }
      if ($moreclick_class) {
        echo '<div class="pzarc-front">';
      }

      //TODO: Check this works for all scenarios
      switch ($settings[ '_panels_design_feature-location' ]) {

        /** Background image */
        case 'fill':
          $line_out = $panel_class->render_bgimage('bgimage', $this->source, $panel_def, $this->rsid);
          echo apply_filters("arc_filter_bgimage", self::strip_unused_arctags($line_out), $postid);

          break;

        /** Image outside and before components */
        case 'float' :

          if ($settings[ '_panels_design_components-position' ] != 'top') {
            $line_out = $panel_class->render_image('image', $this->source, $panel_def, $this->rsid);

            if ($toshow[ 'image' ][ 'width' ] === 100) {

              $line_out = str_replace('{{nofloat}}', 'nofloat', $line_out);

            }

            echo apply_filters("arc_filter_outer_image", self::strip_unused_arctags($line_out), $postid);
          }
          break;
      }
      pzdb('after feature before ' . get_the_id());

      $has_components = false;
      foreach ($toshow as $k => $v) {
        switch ($k) {
          case 'title' :
          case 'content' :
          case 'excerpt' :
          case 'meta1' :
          case 'meta2' :
          case 'meta3' :
          case 'custom1' :
          case 'custom2' :
          case 'custom3' :
            $has_components = !empty($v[ 'show' ]);
            break;
          case 'feature':
            if (!empty($v[ 'show' ]) && $settings[ '_panels_design_feature-location' ] === 'components') {
              $has_components = true;
            }
            break;
        }
        if ($has_components) {
          break;
        }
      }

      /** Open components wrapper */
      if ('table' !== $this->layout_mode && $has_components) {
        echo self::strip_unused_arctags($panel_class->render_wrapper('components-open', $this->source, $panel_def, $this->rsid));
      }

//var_dump($panel_def);
      /** Components */

      pzdb('top of components ' . get_the_id());
      foreach ($toshow as $component_type => $value) {
        if ($component_type === 'image' && $settings[ '_panels_design_feature-location' ] !== 'components') {
          $value[ 'show' ] = false;
        }
        if ($value[ 'show' ]) {

          // Send thru some data devs might find useful
          do_action("arc_before_{$component_type}", $component_type, $this->panel_number, $postid);

          // Make the class name to call - strip numbers from metas and customs
          // We could do this in a concatenation first of all components' templates, and then replace the {{tags}}.... But then we couldn't do the filter on each component. Nor could we as easily make the components extensible
          $method_to_do = strtolower('render_' . str_replace(array('1', '2', '3'), '', ucfirst($component_type)));
          $line_out     = $panel_class->$method_to_do($component_type, $this->source, $panel_def, $this->rsid, $this->layout_mode);
          echo apply_filters("arc_filter_{$component_type}", self::strip_unused_arctags($line_out), $postid);
          do_action("arc_after_{$component_type}", $component_type, $this->panel_number, $postid);

        }

      }
      pzdb('bottom of components ' . get_the_id());


      /** Close components wrapper */
      if ('table' !== $this->layout_mode && $has_components) {
        echo self::strip_unused_arctags($panel_class->render_wrapper('components-close', $this->source, $panel_def, $this->rsid));
      }
      /** Image outside and after components */

      $show_image_after_components = (!empty($toshow[ 'image' ][ 'show' ]) && $settings[ '_panels_design_feature-location' ] === 'float' && ($settings[ '_panels_design_components-position' ] == 'top'));
      if ($show_image_after_components) {

        $line_out = $panel_class->render_image('image', $this->source, $panel_def, $this->rsid);
        echo apply_filters("arc_filter_outer_image", self::strip_unused_arctags($line_out), $postid);

      }

      // Close panel
      if ($moreclick_class) {
        echo '</div><div class="pzarc-back">';
        global $post;
        $panel_class->get_content($post);
        echo apply_filters('the_title',self::strip_unused_arctags($panel_class->render_title('title', $this->source, $panel_def, $this->rsid)));
        echo apply_filters('the_content',self::strip_unused_arctags($panel_class->render_content('content', $this->source, $panel_def, $this->rsid)));
        echo '<p class="pzarc-close-back"><a href="#">Close</a></p>';
        echo '</div>';
      }

      echo '</' . ('table' !== $this->layout_mode ? 'div' : 'tr') . '>'; //close panel
      do_action('arc_after_panel_close');

      //  echo '</div>'; // close panel wrapper
    }

    /*************************************************
     *
     * Method: strip_unused_tags
     *
     * Purpose: Remove any unused mustaches from the remaining HTML
     *
     * Parameters:
     *
     * Returns:
     *
     *************************************************/
    private function strip_unused_arctags($strip_from)
    {
      // removed while in development
      //   return $strip_from;

      return preg_replace('/{{([\w|\-|\:]*)}}/s', '', $strip_from);

    }

    public function __destruct()
    {

    }

    function arcshare_shortcode($atts, $content = null)
    {
      $share_url  = 'bp' . $this->blueprint[ 'blueprint-id' ] . '-' . $this->panel_number;
      $share_link = '';
      $styles     = '';
      $message    = (!empty($atts[ 'message' ]) ? $atts[ 'message' ] : __('Check this out: ', 'pzarchitect'));
      $styles .= !empty($atts[ 'colour' ]) ? 'color:' . $atts[ 'colour' ] . ';' : '';
      $styles .= !empty($atts[ 'color' ]) ? 'color:' . $atts[ 'color' ] . ';' : '';
      $styles .= !empty($atts[ 'size' ]) ? 'height:' . $atts[ 'size' ] . 'px;width:' . $atts[ 'size' ] . 'px;font-size:' . $atts[ 'size' ] . 'px;' : '';
      $styles = $styles ? 'style="' . $styles . '"' : '';
      foreach ($atts as $v) {
        switch ($v) {
          case 'twitter':
            wp_enqueue_style('dashicons');
            $share_link .= '<a class="arc-twitter-share-button" href="https://twitter.com/intent/tweet?text=' . $message . $this->blueprint[ 'parent-page-url' ] . '%23' . $share_url . '" target=_blank title="' . __('Share on Twitter', 'pzarchitect') . '"><span class="dashicons dashicons-twitter" ' . $styles . '></span></a> ';
            break;
          //https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.bordermail.com.au%2Fstory%2F2981852%2F2015-hot-100-vote-for-your-favourite%2F%23slide%3D1
          case'facebook':
            wp_enqueue_style('dashicons');
            $share_link .= '<a class="arc-facebook-share-button" href="https://www.facebook.com/sharer/sharer.php?u=' . $message . $this->blueprint[ 'parent-page-url' ] . '%23' . $share_url . '" target=_blank title="' . __('Share on Facebook', 'pzarchitect') . '"><span class="dashicons dashicons-facebook" ' . $styles . '></span></a> ';
            break;
          case 'link':
            wp_enqueue_style('dashicons');
            $share_link .= '<a class="arc-link-share-button" href="' . $this->blueprint[ 'parent-page-url' ] . '#' . $share_url . '" title="' . __('Direct link', 'pzarchitect') . '"><span class="dashicons dashicons-admin-links" ' . $styles . '></span></a> ';
            break;
          case'email':
            wp_enqueue_style('dashicons');
            $share_link .= '<a class="arc-email-share-button" href="mailto:?subject=' . str_replace(':', '', $message) . '&body=' . $message . $this->blueprint[ 'parent-page-url' ] . '%23' . $share_url . '" title="' . __('Share by email', 'pzarchitect') . '"><span class="dashicons dashicons-email" ' . $styles . '></span></a> ';
            break;
        }
      }

      return $share_link;
    }
  }

  //EOC arc_Section

