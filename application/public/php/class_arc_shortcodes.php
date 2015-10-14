<?php
  /**
   * Project pizazzwp-architect.
   * File: arc_shortcodes.php
   * User: chrishoward
   * Date: 8/10/15
   * Time: 3:02 PM
   *  Details: A collection of useful shortcodes
   */

  class arc_shortcodes
  {
    public $blueprint;

    function __construct(&$blueprint) {
      $this->blueprint = $blueprint->blueprint;
      add_shortcode('arcdummytext', array($this,'dummy_text'));
      add_shortcode('arcpagetitle', array($this,'display_page_title'));
    }

    function dummy_text()
    {
      return 'At processus velit quis placerat fiant. Ullamcorper consuetudium ex volutpat delenit processus. Demonstraverunt me demonstraverunt sit in dignissim. Qui per sequitur sit et quam. Quarta vel illum et mirum lius. Dolor lius suscipit esse consequat facilisis. Velit nunc praesent usus in ';
    }

    function display_page_title($atts)
    {
      global $_architect_options;
      $htag = !empty($atts['tag'])?$atts['tag']:'p';
      return pzarc_display_page_title($this->blueprint,$_architect_options,$htag);
    }
  }