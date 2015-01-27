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

    // Pass $data var to class
    function __construct($action, $blueprint, $pageid = 'home', $overrides = null, $caller = 'template_tag')
    {
      $this->blueprint = $blueprint; // Get data in var
      $this->action    = $action;
      $this->overrides = $overrides;
      $this->caller    = $caller;
      $this->pageid    = $pageid;
      add_action($action, array(&$this, 'render'));
    }

    public function render()
    {
      //TODO: Get the page conditionals working
      //     switch (true) {
//        case ('home' === $this->pageid && (is_home() || is_front_page())) :
      pzarchitect($this->blueprint, $this->overrides, $this->caller);
      //        break;
      //   }
    }
  } // EOC showBlueprint
