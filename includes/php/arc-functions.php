<?php

  if (!function_exists('pzdebug'))
  {

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
      if (is_array($value))
      {
        print"<pre>";
        print_r($value);
        print"</pre>\n";
      }
      elseif (is_object($value))
      {
        var_dump($value);
      }
      else
      {
        print("<p>&gt;${value}&lt;</p>");
      }
    }

  }

  function pz_squish($array)
  {
    $return_array = array();
    foreach ($array as $key => $value)
    {
      $return_array[ $key ] = $array[ $key ][ 0 ];
    }

    return $return_array;
  }

  function pzarc_create_css($postid, $type = null)
  {

    return;

    $defaults = get_option('architect-defaults_settings');
    $prefix   = 'architect-defaults_architect_class_defaults_';
    // var_dump($defaults);

    // Need to create the file contents
    // For each field in stylings, create css
    switch ($type)
    {
      case 'cells':
        $pzarc_cells = get_post_meta($postid);

        $pzarc_contents = "/* This is the css for cell $postid */\n";
//		    var_dump($pzarc_cells);
        // step thru each field loking for ones to format


        foreach ($pzarc_cells as $key => $value)
        {
          switch (true)
          {
            case ($key == '_pzarc_cell-settings-hide-content' && !empty($pzarc_cells[ '_pzarc_cell-settings-hide-content' ][ 0 ])):
              $pzarc_contents .= '@media (max-width: ' . $pzarc_cells[ '_pzarc_cell-settings-hide-content' ][ 0 ] . 'px) { .pzarchitect .pzarc-' . $postid . ' entry-content, .pzarchitect .pzarc-' . $postid . ' .entry-excerpt {display:none;}}' . "\n";

              break;

            case ($key == '_pzarc_layout-cell-preview'):
              $pzarc_left_margin  = (!empty($pzarc_cells[ '_pzarc_cell-settings-image-margin-left' ][ 0 ]) ? $pzarc_cells[ '_pzarc_cell-settings-image-margin-left' ][ 0 ] : 0);
              $pzarc_right_margin = (!empty($pzarc_cells[ '_pzarc_cell-settings-image-margin-right' ][ 0 ]) ? $pzarc_cells[ '_pzarc_cell-settings-image-margin-right' ][ 0 ] : 0);
              $pzarc_layout       = json_decode($value[ 0 ], true);
              $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-title {width:' . $pzarc_layout[ 'title' ][ 'width' ] . '%;}' . "\n";
              // Don't give thumbnail div a width if it's in the content
              if ($pzarc_cells[ '_pzarc_layout-excerpt-thumb' ][ 0 ] == 'none')
              {
                $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-thumbnail {width:' . ($pzarc_layout[ 'image' ][ 'width' ] - $pzarc_left_margin - $pzarc_right_margin) . '%;}' . "\n";
              }
              else
              {
                $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-thumbnail {width:' . $pzarc_cells[ '_pzarc_cell-settings-image-max-width' ][ 0 ] . 'px;}' . "\n";
              }
              $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-content {width:' . $pzarc_layout[ 'content' ][ 'width' ] . '%;}' . "\n";
              $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-excerpt {width:' . $pzarc_layout[ 'excerpt' ][ 'width' ] . '%;}' . "\n";
              $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-meta1 {width:' . $pzarc_layout[ 'meta1' ][ 'width' ] . '%;}' . "\n";
              $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-meta2 {width:' . $pzarc_layout[ 'meta2' ][ 'width' ] . '%;}' . "\n";
              $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-meta3 {width:' . $pzarc_layout[ 'meta3' ][ 'width' ] . '%;}' . "\n";
              break;

            case ($key == '_pzarc_cell-settings-image-margin-left' && ($value[ 0 ] === 0 || $value[ 0 ] > 0)):
              $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-thumbnail {margin-left: ' . $value[ 0 ] . '%;}' . "\n";
              break;

            case ($key == '_pzarc_cell-settings-image-margin-right' && ($value[ 0 ] === 0 || $value[ 0 ] > 0)):
              $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-thumbnail {margin-right: ' . $value[ 0 ] . '%;}' . "\n";
              break;

            case ($key == '_pzarc_cell-settings-image-margin-top' && ($value[ 0 ] === 0 || $value[ 0 ] > 0)):
              $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-thumbnail {margin-top: ' . $value[ 0 ] . '%;}' . "\n";
              break;

            case ($key == '_pzarc_cell-settings-image-margin-bottom' && ($value[ 0 ] === 0 || $value[ 0 ] > 0)):
              $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .entry-thumbnail {margin-bottom: ' . $value[ 0 ] . '%;}' . "\n";
              break;

            // Setup all the custom CSS
            case (strpos($key, 'layout-format-') && !empty($value[ 0 ])):
              $fieldname = str_replace('_pzarc_layout-format-', '', $key);
//            var_dump($fieldname,$key,$prefix.$fieldname.'-class',$defaults[ $prefix.$fieldname.'-class'],$value[ 0 ]);
              $pzarc_classes = '.pzarchitect .pzarc-' . $postid . ' ' . str_replace(',', ', .pzarchitect .pzarc-' . $postid . ' ', $defaults[ $prefix . $fieldname . '-class' ]);
              $pzarc_contents .= $pzarc_classes . ' {' . $value[ 0 ] . '}' . "\n";
              break;
          }


          //     var_dump($key,strpos($key, '-format-') , !empty($value[ 0 ]) , !empty($pzarc_cells[ $key . '-classes' ][ 0 ]));
        }
        $pzarc_sections_postion = (!empty($pzarc_cells[ '_pzarc_layout-sections-position' ]) ? $pzarc_cells[ '_pzarc_layout-sections-position' ][ 0 ] : 'top');
        $pzarc_sections_nudge_x = (!empty($pzarc_cells[ '_pzarc_layout-sections-nudge-x' ]) ? $pzarc_cells[ '_pzarc_layout-sections-nudge-x' ][ 0 ] : 0);
        $pzarc_sections_nudge_y = (!empty($pzarc_cells[ '_pzarc_layout-sections-nudge-y' ]) ? $pzarc_cells[ '_pzarc_layout-sections-nudge-y' ][ 0 ] : 0);
        $pzarc_sections_width   = (!empty($pzarc_cells[ '_pzarc_layout-sections-widths' ]) ? $pzarc_cells[ '_pzarc_layout-sections-widths' ][ 0 ] : 100);
        $pzarc_tb               = ($pzarc_sections_postion == 'left' || $pzarc_sections_postion == 'right' ? 'top' : $pzarc_sections_postion);
        $pzarc_lr               = ($pzarc_sections_postion == 'top' || $pzarc_sections_postion == 'bottom' ? 'left' : $pzarc_sections_postion);
        $pzarc_contents .= '.pzarchitect .pzarc-' . $postid . ' .abs-content {' . $pzarc_tb . ':' . $pzarc_sections_nudge_y . '%;' . $pzarc_lr . ':' . $pzarc_sections_nudge_x . '%;width:' . $pzarc_sections_width . '%;}';
        break;
    }

//die();
    return $pzarc_contents;
  }

  function pzarc_get_string($start, $end, $source)
  {

    preg_match("/(?<=" . $start . ").+?(?=" . $end . ")/uim", $source, $result);

    return $result[ 0 ];

  }

  function pzarc_redux_font($id, $selectors)
  {

    return array(
        'title'  => __('Font', 'pzarc'),
        'id'     => $id,
        'output' => $selectors,
        'type'   => 'typography',
        'custom_fonts'=>true
        //      'default' => $defaults[ $optprefix . 'content_defaults_entry-readmore-hover-defaults' ],
    );
  }

  function pzarc_redux_bg($id, $selectors)
  {
    return array(
        'title'                 => __('Background', 'pzarc'),
        'id'                    => $id,
        'output'                => $selectors,
        'type'                  => 'background',
        'background-image'      => false,
        'background-repeat'     => false,
        'background-size'       => false,
        'background-attachment' => false,
        'background-position'   => false,
        'preview'               => false,
        //      'default' => $defaults[ $optprefix . 'content_defaults_entry-readmore-hover-defaults' ],
    );
  }

  function pzarc_redux_padding($id, $selectors)
  {
    return array(
        'title'  => __('Padding', 'pzarc'),
        'id'     => $id,
        'output' => $selectors,
        'mode'   => 'padding',
        'type'   => 'spacing',
        'units'  => array('px', '%')
        //      'default' => $defaults[ $optprefix . 'content_defaults_entry-readmore-hover-defaults' ],
    );

  }

  function pzarc_redux_links($id, $selectors)
  {
    return array(
        'title'  => __('Links', 'pzarc'),
        'id'     => $id,
        'type'   => 'link_color',
        'output' => $selectors,
    );

  }

  function pzarc_redux_borders($id, $selectors)
  {

    return array(
        'title'  => __('Border', 'pzarc'),
        'id'     => $id,
        'type'   => 'border',
        'all'    => false,
        'output' => $selectors,
    );
  }