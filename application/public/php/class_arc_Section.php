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
    public static function create($number, $section, $source,$navtype)
    {
      return new arc_Section($number, $section, $source, $navtype);
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

    /**
     * @param $number
     * @param $section
     * @param $source
     * @param $navtype
     */
    public function __construct($number, $section, $source, $navtype)
    {

      require_once PZARC_PLUGIN_PATH . '/public/php/class_arc_Panel.php';

      $this->section_number = $number;
      $this->section        = $section;
      $this->source         = $source;
      $this->navtype = $navtype;
      do_action("arc_before_section_{$this->section_number}");
      $this->slider = ($this->section_number===1 && $this->navtype==='navigator')?array('wrapper'=>' swiper-wrapper','slide'=>' swiper-slide'):array('wrapper'=>'','slide'=>'');
      echo '<section class="pzarc-section pzarc-section_' . $this->section_number . ' pzarc-section-using-panel_'.$this->section[ 'section-panel-settings' ]['_panels_settings_short-name'].$this->slider['wrapper'].'">';
    }


    /**
     * @param $panel_def
     */
    public function render_panel($panel_def,$panel_number)
    {

      $data = arc_Panel::set_data($this->section[ 'section-panel-settings' ]);

      $sequence = json_decode($this->section[ 'section-panel-settings' ][ '_panels_design_preview' ], true);
      // We do want to provide actions so want to use the sequence
      do_action('arc_before_panel_open');
      echo '<div class="pzarc-panel pzarc-panel_'.$this->section[ 'section-panel-settings' ]['_panels_settings_short-name'].' pzarc-panel-no_'.$panel_number.$this->slider['slide'].'">';
      // Although this loks back to front, this is determining flow compared to components
      if ($this->section[ 'section-panel-settings' ][ '_panels_design_background-position' ] != 'none' && ($this->section[ 'section-panel-settings' ][ '_panels_design_components-position' ] == 'bottom' || $this->section[ 'section-panel-settings' ][ '_panels_design_components-position' ] == 'right')) {
        echo $data['bgimage'];
      }
      // Render the components open html
      echo self::strip_unused_arctags(arc_Panel_Wrapper::render('components-open', $panel_def, '', $data,$this->section[ 'section-panel-settings' ]));
      foreach ($sequence as $component_type => $value) {
        if ($value[ 'show' ]) {
          // Send thru some data devs might find useful
          do_action("arc_before_{$component_type}",$component_type,$panel_number,$data['postid']);
          // Make the class name to call - strip numbers from metas and customs
          // We could do this in a concatenation first of all components' templates, and then replace the {{tags}}.... But then we couldn't do the filter on each component. Nor could we as easily make the components extensible
          $class = 'arc_Panel_' . str_replace(array('1', '2', '3'), '', $component_type);
          $line_out = $class::render($component_type, $panel_def, $this->source, $data,$this->section[ 'section-panel-settings' ]);
          echo apply_filters("arc_filter_{$component_type}", self::strip_unused_arctags($line_out),$data['postid']);
          do_action("arc_after_{$component_type}",$component_type,$panel_number,$data['postid']);
        }
      }
//        echo '<h1 class="entry-title">',get_the_title(),'</h1>';
//        echo '<div class="entry-content">',get_the_content(),'</div>';
      echo self::strip_unused_arctags(arc_Panel_Wrapper::render('components-close', $panel_def, '', $data,$this->section[ 'section-panel-settings' ]));
      if ($this->section[ 'section-panel-settings' ][ '_panels_design_background-position' ] != 'none' && ($this->section[ 'section-panel-settings' ][ '_panels_design_components-position' ] == 'top' || $this->section[ 'section-panel-settings' ][ '_panels_design_components-position' ] == 'left')) {
        echo $data['bgimage'];
      }
      echo '</div>';
      do_action('arc_after_panel_close');
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
      return $strip_from;

      return preg_replace('/{{([\w|\-]*)}}/s', '', $strip_from);
    }

    public function __destruct()
    {
      echo '</section><!-- End section ' . $this->section_number . ' -->';
      echo '<p>End section: ' . $this->section_number . '</p>';
      do_action("arc_after_section_{$this->section_number}");
    }

  }

  //EOC arc_Section

