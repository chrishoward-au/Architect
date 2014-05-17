<?php

  if (!function_exists('pzdebug')) {

    //---------------------------------------------------------------------------------------------------
    // Debug
    //---------------------------------------------------------------------------------------------------
    /**
     * [pzdebug description]
     * @param  string $value ='' [description]
     * @return [type]           [description]
     */
    function pzdebug($value = '')
    {
      $btr  = debug_backtrace();
      $line = $btr[ 0 ][ 'line' ];
      $file = basename($btr[ 0 ][ 'file' ]);
      print"<pre>$file:$line</pre>\n";
      if (is_array($value)) {
        print"<pre>";
        print_r($value);
        print"</pre>\n";
      } elseif (is_object($value)) {
        var_dump($value);
      } else {
        print("<p>&gt;${value}&lt;</p>");
      }
    }

  }

  function arc_tax_string_list($tax, $prefix, $suffix, $separator)
  {
    $list  = '';
    $count = count($tax);
    $i     = 1;
    if (is_array($tax)) {
      foreach ($tax as $key => $value) {
        $list .= $prefix . $value->slug . $suffix . ($i++ == $count ? '' : $separator);
      }
    }

    return $list;
  }

  function pzarc_squish($array)
  {
    $return_array = array();
    foreach ($array as $key => $value) {
      $return_array[ $key ] = $array[ $key ][ 0 ];
    }

    return $return_array;
  }


  function pzarc_get_string($start, $end, $source)
  {

    preg_match("/(?<=" . $start . ").+?(?=" . $end . ")/uim", $source, $result);

    return $result[ 0 ];

  }

  function pzarc_redux_font($id, $selectors, $defaults = null)
  {

    return array(
        'title'           => __('Font', 'pzarc'),
        'id'              => $id,
        'output'          => $selectors,
        'type'            => 'typography',
        'text-decoration' => true,
        'font-variant'    => true,
        'text-transform'  => true,
        'font-family'     => true,
        'font-size'       => true,
        'font-weight'     => true,
        'font-style'      => true,
        'font-backup'     => false,
        'subsets'         => false,
        'custom_fonts'    => false,
        'text-align'      => true,
        //'text-shadow'       => false, // false
        'color'           => true,
        'preview'         => true,
        'line-height'     => true,
        'word-spacing'    => true,
        'letter-spacing'  => true,
        'google'          => true,
        'default'         => $defaults,
    );
  }


  function pzarc_redux_bg($id, $selectors, $defaults = null)
  {
    return array(
        'title'                 => __('Background', 'pzarc'),
        'id'                    => $id,
        'output'                => $selectors,
        'compiler'              => $selectors,
        'type'                  => 'color_rgba',
        'mode'                  => 'backgound-color',
        'background-image'      => false,
        'background-repeat'     => false,
        'background-size'       => false,
        'background-attachment' => false,
        'background-position'   => false,
        'preview'               => false,
        'default'               => $defaults,
    );
  }

  function pzarc_redux_padding($id, $selectors, $defaults = null)
  {
    return array(
        'title'   => __('Padding', 'pzarc'),
        'id'      => $id,
        'output'  => $selectors,
        'mode'    => 'padding',
        'type'    => 'spacing',
        'units'   => array('px', '%'),
        'default' => $defaults,
    );

  }

  function pzarc_redux_margin($id, $selectors, $defaults = null)
  {
    return array(
        'title'   => __('Margins', 'pzarc'),
        'id'      => $id,
        'output'  => $selectors,
        'mode'    => 'margin',
        'type'    => 'spacing',
        'units'   => array('px', '%'),
        'default' => $defaults,
    );

  }

  function pzarc_redux_links($id, $selectors, $defaults = null)
  {
    return
        array(
            array(
                'title'   => __('Links', 'pzarc'),
                'id'      => $id,
                'type'    => 'link_color',
                'output'  => $selectors,
                'default' => $defaults,
                'regular' => true,
                'hover'   => true,
                'active'  => true,
                'visited' => true
            ),
            // PREBETA: Add some js so can't select both! Or improve it
            array(
                'id'    => $id . '-underline-heading',
                'title' => 'Links underlining',
                'type'  => 'section',
            ),
            //            array(
            //                'title'   => __('Regular', 'pzarc'),
            //                'id'      => $id . '-regular-deco',
            //                'type'    => 'button_set',
            //                'output'  => $selectors,
            //                'default' => $defaults,
            //                'options' => array(
            //                    'inherit'   => 'Default',
            //                    'none'      => '<span class="smaller bold">Abc</span>',
            //                    'underline' => '<span class="smaller bold underline">Abc</span>',
            //                    'overline' => '<span class="smaller bold overline">Abc</span>',
            //                    'line-through' => '<span class="smaller bold line-through">Abc</span>',
            //                    'underover' => '<span class="smaller bold underline-overline">Abc</span>'
            //                )
            //            ),
            //            array(
            //                'title'   => __('Hover', 'pzarc'),
            //                'id'      => $id . '-hover-deco',
            //                'type'    => 'button_set',
            //                'output'  => $selectors,
            //                'default' => $defaults,
            //                'options' => array(
            //                    'inherit'   => 'Default',
            //                    'none'      => '<span class="smaller bold">Abc</span>',
            //                    'underline' => '<span class="smaller bold underline">Abc</span>',
            //                    'overline' => '<span class="smaller bold overline">Abc</span>',
            //                    'line-through' => '<span class="smaller bold line-through">Abc</span>',
            //                    'underover' => '<span class="smaller bold underline-overline">Abc</span>'
            //                )
            //            ),
            //            array(
            //                'title'   => __('Active', 'pzarc'),
            //                'id'      => $id . '-active-deco',
            //                'type'    => 'button_set',
            //                'output'  => $selectors,
            //                'default' => $defaults,
            //                'options' => array(
            //                    'inherit'   => 'Default',
            //                    'none'      => '<span class="smaller bold">Abc</span>',
            //                    'underline' => '<span class="smaller bold underline">Abc</span>',
            //                    'overline' => '<span class="smaller bold overline">Abc</span>',
            //                    'line-through' => '<span class="smaller bold line-through">Abc</span>',
            //                    'underover' => '<span class="smaller bold underline-overline">Abc</span>'
            //                )
            //            ),
            //            array(
            //                'title'   => __('Visited', 'pzarc'),
            //                'id'      => $id . '-visited-deco',
            //                'type'    => 'button_set',
            //                'output'  => $selectors,
            //                'default' => $defaults,
            //                'options' => array(
            //                    'inherit'   => 'Default',
            //                    'none'      => '<span class="smaller bold">Abc</span>',
            //                    'underline' => '<span class="smaller bold underline">Abc</span>',
            //                    'overline' => '<span class="smaller bold overline">Abc</span>',
            //                    'line-through' => '<span class="smaller bold line-through">Abc</span>',
            //                    'underover' => '<span class="smaller bold underline-overline">Abc</span>'
            //                )
            //            ),
            array(
                'title'   => __('Links', 'pzarc'),
                'id'      => $id . '-links-test',
                'type'    => 'links',
                'multi'   => true,
                'output'  => $selectors,
                'default' => $defaults,
            )
        );

  }

//  function pzarc_redux_links_dec($id, $selectors, $defaults = null)
//  {
//    return array(
//        'title'   => __('Links underline', 'pzarc'),
//        'id'      => $id,
//        'type'    => 'button_set',
//        'multi'   => true,
//        'output'  => $selectors,
//        'default' => $defaults,
//        'options' => array(
//            'regular' => 'Regular',
//            'hover'   => 'Hover',
//            'active'  => 'Active',
//            'visited' => 'Visited'
//        )
//    );
//
//  }

  function pzarc_redux_borders($id, $selectors, $defaults = null)
  {

    return array(
        'title'   => __('Border', 'pzarc'),
        'id'      => $id,
        'type'    => 'border',
        'all'     => false,
        'output'  => $selectors,
        'default' => $defaults
    );
  }

  function pzarc_set_defaults()
  {
    // TODO: Remove this once Dovy fixes MB defaults... or maybe not...
    require_once PZARC_PLUGIN_PATH . '/admin/php/class_arc_Panels_Layouts.php';
    require_once PZARC_PLUGIN_PATH . '/admin/php/class_arc_Blueprints_Layouts.php';
    global $pzarchitect;
    $pzarchitect[ 'defaults' ][ 'blueprints' ]                                 = array();
    $blueprint_layout_general                                                  = pzarc_blueprint_layout_general($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarc_blueprint_content_general                                           = pzarc_blueprint_content_general($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarc_blueprint_layout                                                    = pzarc_blueprint_layout($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarc_contents_metabox                                                    = pzarc_contents_metabox($pzarchitect[ 'defaults' ][ 'blueprints' ]);
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout_general' ]  = $blueprint_layout_general[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_content_general' ] = $pzarc_blueprint_content_general[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_blueprint_layout' ]          = $pzarc_blueprint_layout[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'blueprints' ][ '_contents_metabox' ]          = $pzarc_contents_metabox[ 0 ][ 'sections' ];
    $pzarc_panel_general_settings                                              = pzarc_panel_general_settings($pzarchitect[ 'defaults' ][ 'panels' ]);
    $pzarc_panels_design                                                       = pzarc_panels_design($pzarchitect[ 'defaults' ][ 'panels' ]);
    $pzarc_panels_styling                                                      = pzarc_panels_styling($pzarchitect[ 'defaults' ][ 'panels' ]);
    $pzarchitect[ 'defaults' ][ 'panels' ][ '_panel_general_settings' ]        = $pzarc_panel_general_settings[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'panels' ][ '_panels_design' ]                 = $pzarc_panels_design[ 0 ][ 'sections' ];
    $pzarchitect[ 'defaults' ][ 'panels' ][ '_panels_styling' ]                = $pzarc_panels_styling[ 0 ][ 'sections' ];

    foreach ($pzarchitect[ 'defaults' ][ 'blueprints' ] as $key1 => $value1) {
      foreach ($value1 as $key2 => $value2) {
        foreach ($value2 as $key3 => $fields) {
          if (is_array($fields)) {
            foreach ($fields as $key4 => $field) {
              if (isset($field[ 'id' ])) {
                $pzarchitect[ 'defaults' ][ '_blueprints' ][ $field[ 'id' ] ] = $field[ 'default' ];
              }
            }
          }
        }
      }
    }

    unset($pzarchitect[ 'defaults' ][ 'blueprints' ]);

    foreach ($pzarchitect[ 'defaults' ][ 'panels' ] as $key1 => $value1) {
      foreach ($value1 as $key2 => $value2) {
        foreach ($value2 as $key3 => $fields) {
          if (is_array($fields)) {
            foreach ($fields as $key4 => $field) {
              if (isset($field[ 'id' ])) {
                $pzarchitect[ 'defaults' ][ '_panels' ][ $field[ 'id' ] ] = $field[ 'default' ];
              }
            }
          }
        }
      }
    }
    unset($pzarchitect[ 'defaults' ][ 'panels' ]);

  }

  function pzarc_merge_defaults($defaultvs, $setvals)
  {

    foreach ($defaultvs as $key => $value) {
      if (!isset($setvals[ $key ])) {
        $setvals[ $key ] = $value;
      }
    }

    return $setvals;
  }

  // For testing actions
//add_action('arc_after_title','pzarc_action_test',10,3);
  function pzarc_action_test($component, $panelno, $postid)
  {
    echo '<h2>Action run:' . current_action() . '</h2>';
    var_dump($component, $panelno, $postid);
  }

  // For testing filters
//add_filter('arc_filter_excerpt','pzarc_filter_test',10,2);
  function pzarc_filter_test($stuff, $postid)
  {
    return $stuff . '--more stuff added by filter--' . $postid;
  }

  add_filter('arc_filter_shortcode', 'pzarc_scf_test', 10, 3);
  function pzarc_scf_test($content, $blueprint, $overrides)
  {
    return '<div class="pzarc-shortcode-debug" style="background:#fff4f4;border:solid 1px #c99;box-sizing: border-box;"><h3>Start shortcode blueprint ' . $blueprint . ' with ' . count($overrides) . ' overrides</h3>' . $content . '<h3>End blueprint ' . $blueprint . '</h3>';
  }

  /**
   * Convert any variable to an array
   *
   * @param $delimiter
   * @param $var
   * @return array
   */
  function pzarc_to_array($delimiter, $var)
  {
    return (is_array($var) ? $var : explode($delimiter, (string)$var));
  }

  function pzarc_fields()
  {
    $arg_list = func_get_args();
    $returna  = array();
    foreach ($arg_list as $k => $v) {
      if (isset($v[ 0 ])) {
        foreach ($v as $k2 => $v2) {
          $returna[ ] = $v2;
        }
      } else {
        $returna[ ] = $v;
      }
    }

    return $returna;
  }

  add_action('xloop_start', 'pztopofloop', 10, 1);
  function pztopofloop(&$the_query)
  {

    if (is_main_query()) {
      echo '<h1 style="font-size:24px;font-weight:bold;color:red;">Loop starts here</h1>';
//      var_dump($the_query);
    }
  }

  add_action('xloop_end', 'pzbottomofloop');
  function pzbottomofloop()
  {
    if (is_main_query()) {
      echo '<h1 style="font-size:24px;font-weight:bold;color:red;">Loop ends here</h1>';
    }
  }


  /**
   * Class showBlueprint
   *
   * Provides an easy method for users to inject blueprints at any action hook
   */
  class showBlueprint
  {

    // Pass $data var to class
    function __construct($action, $blueprint, $pageid='home', $overrides=null, $caller='template_tag')
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
      if ('home' === $this->pageid && (is_home() || is_front_page())) {
        pzarchitect($this->blueprint, $this->overrides, $this->caller);
      }
    }
  }
