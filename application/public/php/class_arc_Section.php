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
    public static function create($number, $section, $source, $navtype, $layout_mode, $slider_type = null, $section_title = null, $table_titles = null)
    {
      return new arc_Section($number, $section, $source, $navtype, $layout_mode, $slider_type, $section_title, $table_titles);
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
    public function __construct($number, $section_panel, $content_source, $navtype, $layout_mode, $slider_type = null, $section_title = null, $table_accordion_titles = array())
    {


      $this->section_number = $number;
      $this->section        = $section_panel;
      $this->source         = $content_source;
      $this->navtype        = $navtype;
      $this->layout_mode    = $layout_mode;
      $this->section_title  = $section_title;
      $this->slider_type    = $slider_type;
      $this->rsid           = 'rsid' . (rand(1, 9999) * rand(10000, 99999));
      $this->table_accordion_titles = $table_accordion_titles;

      if ('table' === $this->layout_mode) {
        $this->table_accordion_titles = $table_accordion_titles;
      }
      add_action('wp_print_footer_scripts',array($this,'extra_scripts'));
    }


    function extra_scripts(){
      // This is a much nicer way than creating files!!! Wonder where else I can use it?

      // Tabular scripts
      if ('table' === $this->layout_mode) {
        print('<script>jQuery(document).ready(function(){ jQuery("#' . $this->rsid . '").DataTable(); });</script>');
      }
    }

    function open_section()
    {
      if (!empty($this->section[ 'section-panel-settings' ][ '_panels_design_excerpts-word-count' ])) {
        add_filter('excerpt_length', array(&$this, 'set_excerpt_length'), 999);
      }
      if (!empty($this->section[ 'section-panel-settings' ][ '_panels_design_readmore-truncation-indicator' ])) {
        add_filter('excerpt_more', array(&$this, 'set_excerpt_more'), 999);
      }


      do_action("arc_before_section_{$this->section_number}");

      $this->slider = ($this->section_number === 1 && $this->navtype === 'navigator')
          ? array('wrapper' => ' arc-slider-wrapper',
                  'slide'   => ' arc-slider-slide')
          : array('wrapper' => '',
                  'slide'   => '');

      if (!empty($this->section_title)) {

        // TODO: Neeed to process %% tags in title.
        echo '<div class="pzarc-section-title pzarc-section-title-' . $this->section_number . '"><h3>' . $this->section_title . '</h3></div>';

      }

      $isotope      = '';
      $accordion = '';
      $layout_class = 'tiles';
      switch ($this->layout_mode) {

        case 'masonry':
//          $isotope = 'data-isotope-options=\'{ "layoutMode": "masonry","itemSelector": ".pzarc-panel","masonry":{"columnWidth":50,"gutter":20}}\'';

          // Do we load up the MAsonry here?
          wp_enqueue_script('js-isotope-v2');
          $isotope      = 'data-isotope-options=\'{ "layoutMode": "masonry","itemSelector": ".pzarc-panel","masonry":{"columnWidth":".grid-sizer","gutter":".gutter-sizer"}}\'';
          $layout_class = 'js-isotope';
          break;

        case 'accordion':
          $layout_class = 'accordion';
          $accordion = ' data-collapse="accordion"';
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

      echo '<' . ('table' !== $this->layout_mode ? 'div' : 'table') . ' id="' . $this->rsid . '" class="' . $layout_class . ' pzarc-section pzarc-section_' . $this->section_number . ' pzarc-section-using-panel_' . $this->section[ 'section-panel-settings' ][ '_panels_settings_short-name' ] . $this->slider[ 'wrapper' ] . '"' . $isotope . $accordion.'>';

      // Table heading stuff
      if ('table' === $this->layout_mode) {

        $settings = $this->section[ 'section-panel-settings' ];
        $toshow   = json_decode($settings[ '_panels_design_preview' ], true);
        $widths   = array();
        foreach ($toshow as $k => $v) {
          if ($v[ 'show' ]) {
            echo '<col style="width:' . $v[ 'width' ] . '%;">';
            $widths[ ] = $v[ 'width' ];
          }
        }
        echo '<thead><tr>';

        $i = 0;
        // If any titles are missing, the remainder are blanked
        $this->table_accordion_titles   = (is_array($this->table_accordion_titles)?$this->table_accordion_titles:array($this->table_accordion_titles));
        $this->table_accordion_titles = array_pad($this->table_accordion_titles,count($widths),'');

        foreach ($this->table_accordion_titles as $title) {
          echo '<th>' . $title . '</th>';
        }
        echo '</tr></thead>';
      }

      // Masonry stuff
      if ('masonry'===$this->layout_mode) {
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
      $new_more = $this->section[ 'section-panel-settings' ][ '_panels_design_readmore-truncation-indicator' ];
      $new_more .= ($this->section[ 'section-panel-settings' ][ '_panels_design_readmore-text' ] ? '<a href="' . get_the_permalink() . '" class="readmore moretag">' . $this->section[ 'section-panel-settings' ][ '_panels_design_readmore-text' ] . '</a>' : null);

      return $new_more;

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

    }

    /**
     * @param $panel_def
     */
    public function render_panel($panel_def, $panel_number, $class, $panel_class, &$arc_query)
    {
      if (!empty($arc_query->post)) {
        $post   = $arc_query->post;
        $postid = $arc_query->post->ID;
      } else {
        // Then it is an array

        // This happens on non-post types, like dummy content type
        // TODO: Make these something more useful!
        $post   = $arc_query[ $panel_number - 1 ];
        $postid = 'NoID';

      }
      $postid   = (empty($postid) ? 'NoID' : $postid);
      $settings = $this->section[ 'section-panel-settings' ];
      $toshow   = json_decode($settings[ '_panels_design_preview' ], true);
      $panel_class->set_data($post, $toshow, $settings);
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
      do_action('arc_before_panel_open_a');
      $nav_item[ $panel_number ] = null;
      // This is for adding nav items to each panel. Used by navs like accordions
      // Although we could do it already, we want to kepp it in the nav class for extensibility
      echo apply_filters('arc_before_panel_open_f', $nav_item[ $panel_number ]);

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
      if ('accordion'===$this->layout_mode) {
        $accordion_title = $post->post_title;
        if (!empty($this->table_accordion_titles) && isset($this->table_accordion_titles[$panel_def])) {
          $accordion_title = do_shortcode($this->table_accordion_titles[$panel_number]);
        }
        //'_blueprint_section-' . $this->section_number . '-accordion-titles'
        echo '<div class="pzarc-accordion title '.($panel_number===1?'open':'close').'">'.$accordion_title.'</div>';
      }


      $odds_evens_section = ($panel_number % 2 ? ' odd-section-panel' : ' even-section-panel');
      static $panel_count = 1;
      $odds_evens_bp = ($panel_count++ % 2 ? ' odd-blueprint-panel' : ' even-blueprint-panel');


      // Add standard identifying WP classes to the whole panel
      $postmeta_classes = ' ' . $panel_class->data[ 'posttype' ] . ' type-' . $panel_class->data[ 'posttype' ] . ' status-' . $panel_class->data[ 'poststatus' ] . ' format-' . $panel_class->data[ 'postformat' ] . ' ';

      echo '<' . ('table' !== $this->layout_mode ? 'div' : 'tr') . ' class="pzarc-panel pzarc-panel_' . $settings[ '_panels_settings_short-name' ] . ' pzarc-panel-no_' . $panel_number . $this->slider[ 'slide' ] . $image_in_bg . $odds_evens_bp . $odds_evens_section . $postmeta_classes . '" >';

      // Although this loks back to front, this is determining flow compared to components

      //    var_dump(!empty($toshow[ 'image' ][ 'show' ]) && ($settings[ '_panels_design_components-position' ] == 'bottom' || $settings[ '_panels_design_components-position' ] == 'right'));
//       $show_image_before_components = (!empty($toshow[ 'image' ][ 'show' ]) && ($settings[ '_panels_design_feature-location' ] !== 'float' && $settings[ '_panels_design_components-position' ] == 'top'));

//       if ($show_image_before_components) {

//         /** Background image */
//         if ($settings[ '_panels_design_feature-location' ] === 'fill') {
//           $line_out = $panel_class->render_bgimage('bgimage', $this->source, $panel_def, $this->rsid);
// //        echo apply_filters("arc_filter_bgimage", self::strip_unused_arctags($line_out), $data[ 'postid' ]);
//           echo apply_filters("arc_filter_bgimage", self::strip_unused_arctags($line_out), $arc_query->post->ID);

//         }
//         /** Image outside and before components */
//         if ($settings[ '_panels_design_feature-location' ] === 'float') {

//           $line_out = $panel_class->render_image('image', $this->source, $panel_def, $this->rsid);

//           if ($toshow[ 'image' ][ 'width' ] === 100) {

//             $line_out = str_replace('{{nofloat}}', 'nofloat', $line_out);

//           }

//           echo apply_filters("arc_filter_outer_image", self::strip_unused_arctags($line_out), $arc_query->post->ID);


//         }
//       }

      //TODO: Check this works for all scenarios
      switch ($settings[ '_panels_design_feature-location' ]) {

        /** Background image */
        case 'fill':
          $line_out = $panel_class->render_bgimage('bgimage', $this->source, $panel_def, $this->rsid);
//        echo apply_filters("arc_filter_bgimage", self::strip_unused_arctags($line_out), $data[ 'postid' ]);
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


      /** Open components wrapper */
      if ('table' !== $this->layout_mode) {

        echo self::strip_unused_arctags($panel_class->render_wrapper('components-open', $this->source, $panel_def, $this->rsid));
      }
//var_dump($panel_def);
      /** Components */
      foreach ($toshow as $component_type => $value) {
        if ($component_type === 'image' && $settings[ '_panels_design_feature-location' ] !== 'components') {
          $value[ 'show' ] = false;
        }
        if ($value[ 'show' ]) {

          // Send thru some data devs might find useful
          do_action("arc_before_{$component_type}", $component_type, $panel_number, $postid);

          // Make the class name to call - strip numbers from metas and customs
          // We could do this in a concatenation first of all components' templates, and then replace the {{tags}}.... But then we couldn't do the filter on each component. Nor could we as easily make the components extensible
          $method_to_do = strtolower('render_' . str_replace(array('1', '2', '3'), '', ucfirst($component_type)));
          $line_out     = $panel_class->$method_to_do($component_type, $this->source, $panel_def, $this->rsid, $this->layout_mode);
          echo apply_filters("arc_filter_{$component_type}", self::strip_unused_arctags($line_out), $postid);
          do_action("arc_after_{$component_type}", $component_type, $panel_number, $postid);

        }

      }


      /** Close components wrapper */
      if ('table' !== $this->layout_mode) {
        echo self::strip_unused_arctags($panel_class->render_wrapper('components-close', $this->source, $panel_def, $this->rsid));
      }
      /** Image outside and after components */

      $show_image_after_components = (!empty($toshow[ 'image' ][ 'show' ]) && $settings[ '_panels_design_feature-location' ] === 'float' && ($settings[ '_panels_design_components-position' ] == 'top'));
      if ($show_image_after_components) {

        $line_out = $panel_class->render_image('image', $this->source, $panel_def, $this->rsid);
        echo apply_filters("arc_filter_outer_image", self::strip_unused_arctags($line_out), $postid);

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

  }

  //EOC arc_Section

