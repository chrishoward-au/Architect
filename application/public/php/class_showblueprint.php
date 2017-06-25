<?php
  /**
   * Project pizazzwp-architect.
   * File: class_showblueprint.php
   * User: chrishoward
   * Date: 24/01/15
   * Time: 7:58 PM
   */

  /**
   * Class showBlueprint
   *
   * Provides an easy method for users to inject blueprints at any action hook
   *
   * Usage:Add: new showBlueprint(’action’, ’blueprint’, ’pageids’); to your functions.php
   * action = Action hook to hook into
   * blueprint = Blueprint short name to display
   * pageids = overrides
   */
  class showBlueprint
  {
    public $blueprint;
    public $action;
    public $overrides;
    public $caller;
    public $pageid;
    public $specificids;
    public $customtax;

    // Pass $data var to class
    function __construct($k, $v, $overrides = null, $caller = 'template_tag')
    {
      $this->blueprint   = $v[ 'architect_actions_' . $k . '_blueprint' ]; // Get data in var
      $this->action      = trim($v[ 'architect_actions_' . $k . '_action-name' ]);
      $this->overrides   = $overrides; // TODO: not used yet
      $this->caller      = $caller;
      $this->pageid      = isset($v[ 'architect_actions_' . $k . '_pageids' ]) ? $v[ 'architect_actions_' . $k . '_pageids' ] : array('all');
      $this->specificids = isset($v[ 'architect_actions_' . $k . '_specificids' ]) ? explode(',', $v[ 'architect_actions_' . $k . '_specificids' ]) : '';
      $this->customtax   = isset($v[ 'architect_actions_' . $k . '_tax-slugs' ]) ? $v[ 'architect_actions_' . $k . '_tax-slugs' ] : '';

      add_action($this->action, array(&$this, 'render'));
    }

    public function render()
    {
      global $post,$wp_query;
      //TODO: Get the page conditionals working
      switch (true) {
        case (in_array('all', $this->pageid)) :
        case (in_array('home', $this->pageid) && (is_home() || is_front_page())) :
        case (in_array('specific', $this->pageid) && !empty($this->specificids) && in_array(get_the_id(), $this->specificids)) :
        case (in_array('single-post', $this->pageid) && (is_single())) :
        case (in_array('single-page', $this->pageid) && (is_page())) :
        case (in_array('blog', $this->pageid) && (is_home())) :
        case (in_array('categories', $this->pageid) && (is_category())) :
        case (in_array('tags', $this->pageid) && (is_tag())) :
        case (in_array('tax', $this->pageid) && !empty($this->customtax) && (is_tax($this->customtax))) :
        case (in_array('dates', $this->pageid) && (is_date())) :
        case (in_array('authors', $this->pageid) && (is_author())) :
        case (in_array('search', $this->pageid) && (is_search())) :
        case (in_array('404', $this->pageid) && (is_404())) :

          $tablet_bp = null;
          $phone_bp = null;
          pzarchitect($this->blueprint, $this->overrides, $tablet_bp,$phone_bp); // Overrides and tablet/phone bp not used yet

          break;

      }
    }
  } // EOC showBlueprint
