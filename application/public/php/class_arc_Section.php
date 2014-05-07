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
    public static function create($number, $section, $source)
    {
      return new arc_Section($number, $section, $source);
    }

  }

  class arc_Section
  {
    private $section_number;
    private $section;
    private $source;
    private $data;

    /**
     * @param $number
     * @param $section
     * @param $source
     */
    public function __construct($number, $section, $source)
    {

      require_once PZARC_PLUGIN_PATH . '/public/php/class_arc_Panel.php';

      $this->section_number = $number;
      $this->section        = $section;
      $this->source         = $source;
      do_action("arc_before_section_{$this->section_number}");
      echo '<div class="pzarc-section pzarc-section_' . $this->section_number . ' pzarc-section-using-panel_'.$this->section[ 'section-panel-settings' ]['_panels_settings_short-name'].'">';
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
      echo '<div class="pzarc-panel pzarc-panel_'.$this->section[ 'section-panel-settings' ]['_panels_settings_short-name'].' pzarc-panel-no-'.$panel_number.'">';
      echo self::strip_unused_arctags(arc_Panel_Wrapper::render('panel-open', $panel_def, '', $data,$this->section[ 'section-panel-settings' ]));
      foreach ($sequence as $component_type => $value) {
        if ($value[ 'show' ]) {
          // Send thru some data devs might find useful
          do_action("arc_before_{$component_type}",$component_type,$panel_number,$data['postid']);
          // Make the class name to call - strip numbers from metas and customs
          $class = 'arc_Panel_' . str_replace(array('1', '2', '3'), '', $component_type);
          $line_out = $class::render($component_type, $panel_def, $this->source, $data,$this->section[ 'section-panel-settings' ]);
          echo apply_filters("arc_filter_{$component_type}", self::strip_unused_arctags($line_out),$data['postid']);
          do_action("arc_after_{$component_type}",$component_type,$panel_number,$data['postid']);
        }
      }
//        echo '<h1 class="entry-title">',get_the_title(),'</h1>';
//        echo '<div class="entry-content">',get_the_content(),'</div>';
      echo self::strip_unused_arctags(arc_Panel_Wrapper::render('panel-close', $panel_def, '', $data,$this->section[ 'section-panel-settings' ]));
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
      echo '</div><!-- End section ' . $this->section_number . ' -->';
      echo '<p>End section: ' . $this->section_number . '</p>';
      do_action("arc_after_section_{$this->section_number}");
    }

  }

  //EOC arc_Section

