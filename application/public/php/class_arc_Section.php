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

    public function __construct($number, $section, $source)
    {
      require_once PZARC_PLUGIN_PATH.'/public/php/interface_arc_Panel.php';

      $this->section_number = $number;
      $this->section        = $section;
      $this->source         = $source;

      do_action('arc_before_section_' . $this->section_number);
      echo '<div class="pzarc-section-' . $this->section_number . '">';
    }

    private function set_data()
    {
      $this->data[ 'wrapper_open' ][ 'postid' ]     = get_the_ID();
      $this->data[ 'wrapper_open' ][ 'poststatus' ] = get_post_status();
      // setto value coz PHP < 5.5 doesn'tsupport it inline in empty
      $post_format                                   = get_post_format();
      $this->data [ 'wrapper_open' ][ 'postformat' ] = (empty($post_format) ? 'standard' : $post_format);
      $this->data[ 'image' ][ 'image' ]              = get_the_post_thumbnail(null, 'thumbnail');
    }

    public function render_panel($panel_def)
    {

      self::set_data();

      //var_dump($this->section);
      $sequence = json_decode($this->section[ 'section-panel-settings' ][ '_panels_design_preview' ], true);
      // We do want to provide actions so want to use the sequence
      do_action('arc_before_panel_open');
      echo '<div class="pzarc-panel">';
      echo self::strip_unused_arctags(arc_Panel_Wrapper::render('open', $panel_def[ 'panel-open' ], '', $this->data[ 'wrapper_open' ]));
      foreach ($sequence as $key => $value)
      {
        if ($value[ 'show' ])
        {
          do_action('arc_before_' . $key);
          // Make the class name to call - strip numbers from metas and customs
          $class = 'arc_Panel_' . str_replace(array('1', '2', '3'), '', $key);
          echo apply_filters('arc_filter_' . $key, self::strip_unused_arctags($class::render($key, $panel_def[ $key ], $this->source, $this->data[ $key ])));
          do_action('arc_after_' . $key);
        }
        else
        {
          // Because they are in sequence with all the to shows first, we can break once we hit the first not to show
          break;
        }
      }
//        echo '<h1 class="entry-title">',get_the_title(),'</h1>';
//        echo '<div class="entry-content">',get_the_content(),'</div>';
      echo self::strip_unused_arctags(arc_Panel_Wrapper::render('close', $panel_def[ 'panel-close' ], '', $this->data[ 'wrapper_close' ]));
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

      // return preg_replace('/{{([\w|\-]*)}}/s', '', $strip_from);
    }

    public function __destruct()
    {
      echo '</div><!-- End section ' . $this->section_number . ' -->';
      echo '<p>End: ' . $this->section_number . '</p>';
      do_action('arc_after_section_' . $this->section_number);
    }

  }

  //EOC arc_Section

