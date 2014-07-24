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
    public static function create($number, $section, $source, $navtype, $layout_mode, $slider_type = null, $section_title = null)
    {
      return new arc_Section($number, $section, $source, $navtype, $layout_mode, $slider_type, $section_title);
    }

  }

  class arc_Section
  {
    private $section_number;
    private $section;
    private $source;
    private $data;
    private $slider;
    private $navtype;
    private $layout_mode;
    private $blueprint;
    private $section_title;
    private $slider_type;
    private $rsid;

    /**
     * @param      $number
     * @param      $section_panel
     * @param      $content_source
     * @param      $navtype
     * @param      $layout_mode
     * @param null $slider_type
     * @param null $section_title
     * @internal param $blueprint
     */
    public function __construct($number, $section_panel, $content_source, $navtype, $layout_mode, $slider_type = null, $section_title = null)
    {


      $this->section_number = $number;
      $this->section        = $section_panel;
      $this->source         = $content_source;
      $this->navtype        = $navtype;
      $this->layout_mode    = $layout_mode;
      $this->section_title  = $section_title;
      $this->slider_type    = $slider_type;
      $this->rsid           = 'rsid' . (rand(1,9999)*rand(10000,99999));

    }

    function open_section()
    {
      if (!empty($this->section[ 'section-panel-settings' ][ '_panels_design_excerpts-word-count' ])) {
        add_filter('excerpt_length', array(&$this, 'set_excerpt_length'), 999);
      }
      if (!empty($this->section[ 'section-panel-settings' ][ '_panels_design_readmore-truncation-indicator' ])) {
        add_filter('excerpt_more', array(&$this, 'set_excerpt_more'), 999);
      }

      // Do we load up the MAsonry here?
      wp_enqueue_script('js-isotope-v2');

      do_action("arc_before_section_{$this->section_number}");

      $this->slider = ($this->section_number === 1 && $this->navtype === 'navigator')
          ? array('wrapper' => ' swiper-wrapper',
                  'slide'   => ' swiper-slide')
          : array('wrapper' => '',
                  'slide'   => '');

      if (!empty($this->section_title)) {

        // TODO: Neeed to process %% tags in title.
        echo '<div class="pzarc-section-title pzarc-section-title-' . $this->section_number . '"><h3>' . $this->section_title . '</h3></div>';

      }

      echo '<section id="'.$this->rsid.'" class="' . ($this->layout_mode !== 'basic' ? 'js-isotope ' : '') . 'pzarc-section pzarc-section_' . $this->section_number . ' pzarc-section-using-panel_' . $this->section[ 'section-panel-settings' ][ '_panels_settings_short-name' ] . $this->slider[ 'wrapper' ] . '">';

    }

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

    function close_section()
    {
      echo '</section><!-- End section ' . $this->section_number . ' -->';
      do_action("arc_after_section_{$this->section_number}");

      remove_filter('excerpt_length', array(&$this, 'set_excerpt_length'), 999);
      remove_filter('excerpt_more', array(&$this, 'set_excerpt_more'), 999);

    }

    /**
     * @param $panel_def
     */
    public function render_panel($panel_def, $panel_number, $class, $panel_class, &$arc_query)
    {

      $data = $panel_class->set_data($this->section[ 'section-panel-settings' ], $arc_query->post);

      $sequence = json_decode($this->section[ 'section-panel-settings' ][ '_panels_design_preview' ], true);
      // We do want to provide actions so want to use the sequence
      do_action('arc_before_panel_open_a');
      $nav_item[ $panel_number ] = null;
      // This is for adding nav items to each panel. Used by navs like accordions
      // Although we could do it already, we want to kepp it in the nav class for extensibility
      echo apply_filters('arc_before_panel_open_f', $nav_item[ $panel_number ]);

      // TODO: What's this??
      //       echo '<div class="js-isotope pzarc-section pzarc-section-' . $key . '" data-isotope-options=\'{ "layoutMode": "'.$pzarc_section_info['section-layout-mode'].'","itemSelector": ".pzarc-panel" }\'>';

      switch ($this->layout_mode) {

        case 'masonry':
//          $isotope = 'data-isotope-options=\'{ "layoutMode": "masonry","itemSelector": ".pzarc-panel","masonry":{"columnWidth":50,"gutter":20}}\'';
          $isotope = 'data-isotope-options=\'{ "layoutMode": "masonry","itemSelector": ".pzarc-panel"}\'';
          break;

        default:
          $isotope = '';

      }

      $image_in_bg = '';

      switch ($this->section[ 'section-panel-settings' ][ '_panels_design_background-position' ]) {

        case 'fill':
          $image_in_bg = ' using-bgimages';
          break;

        case 'align':
          $image_in_bg = ' using-aligned-bgimages';
          break;

      }
      // TODO: Added an extra div here to pre-empt the structure needed for accordions. Tho, needs some work as it breaks layout. Maybe conditional
      // TODO: Probably use jQuery Collapse for accordions

//      echo '<div class="arc-panel-wrapper" style="margin:0;padding:0">';
//      echo '<div class="arc-panel-title"></div>'; // Use this for future accordion layout

      $odds_evens_section = ($panel_number % 2 ? ' odd-section-panel' : ' even-section-panel');
      static $panel_count = 1;
      $odds_evens_bp = ($panel_count++ % 2 ? ' odd-blueprint-panel' : ' even-blueprint-panel');


      echo '<div class="pzarc-panel pzarc-panel_' . $this->section[ 'section-panel-settings' ][ '_panels_settings_short-name' ] . ' pzarc-panel-no_' . $panel_number . $this->slider[ 'slide' ] . $image_in_bg . $odds_evens_bp . $odds_evens_section . '" ' . $isotope . '>';
      // Although this loks back to front, this is determining flow compared to components

      if ($this->section[ 'section-panel-settings' ][ '_panels_design_background-position' ] != 'none' && ($this->section[ 'section-panel-settings' ][ '_panels_design_components-position' ] == 'bottom' || $this->section[ 'section-panel-settings' ][ '_panels_design_components-position' ] == 'right')) {

        $class_bgimage = $class . '_bgimage';
        $bgimage_class = new $class_bgimage;
        $line_out      = $bgimage_class->render('bgimage', $panel_def, $this->source, $data, $this->section[ 'section-panel-settings' ],$this->rsid);
        echo apply_filters("arc_filter_bgimage", self::strip_unused_arctags($line_out), $data[ 'postid' ]);
//        echo $data['bgimage'];
//        var_dump(esc_html( $data['bgimage']));

        unset($class_bgimage);
      }

      // Render the components open html
      $class_wrapper = $class . '_Wrapper';
      $wrapper_class = new $class_wrapper;
      echo self::strip_unused_arctags($wrapper_class->render('components-open', $panel_def, '', $data, $this->section[ 'section-panel-settings' ],$this->rsid));
      foreach ($sequence as $component_type => $value) {

        if ($value[ 'show' ]) {

          // Send thru some data devs might find useful
          do_action("arc_before_{$component_type}", $component_type, $panel_number, $data[ 'postid' ]);

          // Make the class name to call - strip numbers from metas and customs
          // We could do this in a concatenation first of all components' templates, and then replace the {{tags}}.... But then we couldn't do the filter on each component. Nor could we as easily make the components extensible
          $class_component = $class . '_' . str_replace(array('1', '2', '3'), '', ucfirst($component_type));
          $component_class = new $class_component;
          $line_out        = $component_class->render($component_type, $panel_def, $this->source, $data, $this->section[ 'section-panel-settings' ],$this->rsid);


          echo apply_filters("arc_filter_{$component_type}", self::strip_unused_arctags($line_out), $data[ 'postid' ]);
          do_action("arc_after_{$component_type}", $component_type, $panel_number, $data[ 'postid' ]);

        }

      }
//        echo '<h1 class="entry-title">',get_the_title(),'</h1>';
//        echo '<div class="entry-content">',get_the_content(),'</div>';
      echo self::strip_unused_arctags($wrapper_class->render('components-close', $panel_def, '', $data, $this->section[ 'section-panel-settings' ],$this->rsid));

      if ($this->section[ 'section-panel-settings' ][ '_panels_design_background-position' ] != 'none' && ($this->section[ 'section-panel-settings' ][ '_panels_design_components-position' ] == 'top' || $this->section[ 'section-panel-settings' ][ '_panels_design_components-position' ] == 'left')) {

        $class_bgimage = $class . '_bgimage';
        $bgimage_class = new $class_bgimage;
        $line_out      = $bgimage_class->render('bgimage', $panel_def, $this->source, $data, $this->section[ 'section-panel-settings' ],$this->rsid);
        echo apply_filters("arc_filter_bgimage", self::strip_unused_arctags($line_out), $data[ 'postid' ]);
//        echo $data['bgimage'];
//        var_dump(esc_html( $data['bgimage']));

        unset($class_bgimage);

      }

      echo '</div>'; //close panel

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
      //  return $strip_from;

      return preg_replace('/{{([\w|\-|\:]*)}}/s', '', $strip_from);

    }

    public function __destruct()
    {

    }

  }

  //EOC arc_Section

