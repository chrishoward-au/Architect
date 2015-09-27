<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_masonry.php
   * User: chrishoward
   * Date: 12/09/15
   * Time: 12:43 PM
   */
  class arc_masonry
  {
    public $blueprint;

    public function __construct(&$blueprint)
    {
      $this->blueprint = $blueprint;
      //var_dump($blueprint);

      add_action('arc_masonry_controls_' . $this->blueprint[ '_blueprints_short-name' ], array($this, 'sorting'));
      add_action('arc_masonry_controls_' . $this->blueprint[ '_blueprints_short-name' ], array($this, 'filtering'));
      add_action('arc-extend-panel-classes_' . $this->blueprint[ '_blueprints_short-name' ], array($this,
                                                                                                   'filtering_classes'), 10, 2);
      echo '</div>';
      add_action('wp_print_footer_scripts', array($this, 'js'));
    }

    function sorting()
    {

      echo '<div class="arc-filtering-and-sorting">';
      if (!empty($this->blueprint[ '_blueprints_masonry-sort-fields' ])) {

        // TODO: Setupo registry for each so developer can define
        echo '<div class="arc-sort-buttons"><div class="arc-masonry-buttons button-group sort-by-button-group">
            <label>' . __('Sort by', 'pzarchitect') . ': </label>
            <button class="selected" data-sort-by="original-order">' . __('Default', 'pzarchitect') . '</button>';
        foreach ($this->blueprint[ '_blueprints_masonry-sort-fields' ] as $k => $v) {
          $sv = str_replace(array('[', ']', '.'), '', $v);
          $sk = str_replace(array('-', '_', ' '), '', $sv);
          $sv = str_replace(array('-', '_'), ' ', $sv);
          $sv = ($v == '[data-order]') ? 'Date' : $sv;
          echo '<button data-sort-by="' . $sk . '">' . ucwords($sv) . '</button>';
        }
        echo '</div>';
        echo '<div class="arc-masonry-buttons button-group sort-order-button-group">
            <label>' . __('Order', 'pzarchitect') . ': </label>
            <button class="selected" data-sort-order="true">' . __('Ascending', 'pzarchitect') . '</button>
            <button data-sort-order="false">' . __('Descending', 'pzarchitect') . '</button>';

        echo '</div></div>';
      }
    }

    function filtering()
    {
      // Need to do this for each taxonomy
      if (!empty($this->blueprint[ '_blueprints_masonry-filtering' ])) {
        $i = 1;
        echo '<div class="arc-filter-buttons">';
        foreach ($this->blueprint[ '_blueprints_masonry-filtering' ] as $tax) {
          if (!empty($this->blueprint[ '_blueprints_masonry-filtering-limit-' . $tax ])) {
            switch ($this->blueprint[ '_blueprints_masonry-filtering-limit-' . $tax ]) {
              case 'include':
                $terms = pzarc_get_terms($tax, array('hide_empty' => true,
                                                     'include'    => $this->blueprint[ '_blueprints_masonry-filtering-incexc-' . $tax ]));
                break;
              case 'exclude':
                $terms = pzarc_get_terms($tax, array('hide_empty' => true,
                                                     'exclude'    => $this->blueprint[ '_blueprints_masonry-filtering-incexc-' . $tax ]));
                break;
              default:
              case 'none':
                $terms = pzarc_get_terms($tax, array('hide_empty' => true));
                break;
            }
            echo '<div class="arc-masonry-buttons button-group filter-button-group">';
            if (!empty($terms)) {
              echo '<label>' . __('Filter by ', 'pzarchitect') . ucwords(str_replace(array('_',
                                                                                           '-'), ' ', $tax)) . ': </label>';
              foreach ($terms as $class => $name) {
                echo '<button data-filter=".' . $tax . '-' . $class . '">' . $name . '</button>';
              }
            }
            if ((empty($this->blueprint[ '_blueprints_masonry-filtering-allow-multiple' ]) || $this->blueprint[ '_blueprints_masonry-filtering-allow-multiple' ] === 'multiple') && $i++ === count($this->blueprint[ '_blueprints_masonry-filtering' ])) {
              echo '<p><button data-filter="*" class="showall" data-filter-group="all">' . __('Clear all', 'pzarchitect') . '</button></p>';
            }
            echo '</div>';
          }
        }
        echo '</div>';
      }
      echo '</div>'; //close filtering and sorting
    }

    function filtering_classes($classes, $blueprint)
    {
      if (!empty($this->blueprint[ '_blueprints_masonry-filtering' ])) {
        $classes .= ' ';
        foreach ($this->blueprint[ '_blueprints_masonry-filtering' ] as $tax) {
          $terms = get_the_terms(get_the_id(), $tax);
          if (!empty($terms)) {
            foreach ($terms as $term_obj) {
              $classes .= $tax . '-' . $term_obj->slug . ' ';
            }
          }
        }
      }

      return $classes;
    }

    function js()
    {
      $blueprint = $this->blueprint[ '_blueprints_short-name' ];
      $sort_data = '';
      if (!empty($this->blueprint[ '_blueprints_masonry-sort-fields' ])) {

        foreach ($this->blueprint[ '_blueprints_masonry-sort-fields' ] as $k => $v) {
          if ($k !== 'random') {
            $s = str_replace(array('.', '-', '_', '[', ']', ' '), '', $v);
            switch ($v) {
              case  '[data-order]':
                $sort_data .= "{$s}:function(itemElem){
              return jQuery(itemElem).find('.entry-meta').attr('data-order');
            },";
                break;
              case
              'random':
                break;
              default:

                if (!empty($this->blueprint[ '_blueprints_masonry-sort-fields-numeric' ]) && in_array($v, $this->blueprint[ '_blueprints_masonry-sort-fields-numeric' ])) {
//                  $v = '[data-sort-numeric]';
                  $v = "function (elem) {return parseFloat(jQuery(elem).find('.{$v}').attr('data-sort-numeric'));}";
                  $sort_data .= "{$s}:{$v},";
                } elseif (!empty($this->blueprint[ '_blueprints_masonry-sort-fields-date' ]) && in_array($v, $this->blueprint[ '_blueprints_masonry-sort-fields-date' ])) {
                  //              $v = '[data-sort-date]';
                  $v = "function (elem) {return parseInt(jQuery(elem).find('.{$v}').attr('data-sort-date'));}";
                  $sort_data .= "{$s}:{$v},";
                } else {
                  $v = strpos($v, '.') !== 0 && strpos($v, '#') !== 0 ? '.' . $v : $v;
                  $sort_data .= "{$s}:'{$v}',";
                }
            }
          }
        }
      }
      $sort_data = $sort_data ? substr($sort_data, 0, -1) : $sort_data;
      $script    = "<script type='text/javascript' id='masonry-{$blueprint}'>
        // init Isotope
        (function($){";
      $script .= "var allowMultiple=" . (empty($this->blueprint[ '_blueprints_masonry-filtering-allow-multiple' ]) || $this->blueprint[ '_blueprints_masonry-filtering-allow-multiple' ] === 'multiple' ? 'true' : 'false') . ";";
      $script .= "    var container = jQuery( '.pzarc-section-using-{$blueprint}' );
               var arcIsotopeID = jQuery( container ).attr( 'data-uid' );
              jQuery(container).imagesLoaded( function ()
              {
                container.isotope( {
                  // options
                  layoutMode: 'masonry',
                  itemSelector: '.' + arcIsotopeID + ' .pzarc-panel',
                  masonry: {
                    columnWidth: '.grid-sizer',
                    gutter: '.gutter-sizer'
                  },
                  getSortData: {
                    {$sort_data}
                  }
                } );
                container.isotope('updateSortData').isotope();
              } );

        // sort items on button click
        jQuery('.sort-by-button-group').on( 'click', 'button', function() {
          jQuery('.sort-by-button-group').find('.selected').removeClass('selected');
          jQuery(this).addClass('selected');
          var sortByValue = $(this).attr('data-sort-by');
          var sortOrderValue = ($('#pzarc-blueprint_{$blueprint} .sort-order-button-group .selected').attr('data-sort-order')=='true');
          container.isotope({ sortBy: sortByValue, sortAscending: sortOrderValue });
        });

        jQuery('.sort-order-button-group').on( 'click', 'button', function() {
          jQuery('.sort-order-button-group').find('.selected').removeClass('selected');
          jQuery(this).addClass('selected');
          var sortOrderValue = ($(this).attr('data-sort-order')=='true');
          var sortByValue = $('#pzarc-blueprint_{$blueprint} .sort-by-button-group .selected').attr('data-sort-by');
          container.isotope({ sortByValue: sortByValue, sortAscending: sortOrderValue });
        });";

      $script .= "jQuery('.filter-button-group').on( 'click', 'button', function() {
          if (jQuery(this).hasClass('showall')) {
            jQuery('.filter-button-group .selected').removeClass('selected');
          } else {
            jQuery('.filter-button-group .showall').removeClass('selected');
          }

          if (jQuery(this).hasClass('selected')) {
            jQuery(this).removeClass('selected');
          } else {
            if (!allowMultiple) {
              jQuery('.filter-button-group .selected').removeClass('selected');
            }
            jQuery(this).addClass('selected');
          }

          var t = jQuery('.filter-button-group .selected');
          var filterValue = concatValues(t);
          container.isotope({ filter: filterValue });
      });

      function concatValues( t ) {
         var value = '';
         jQuery(t).each(function(){
         var dataFilter = jQuery(this).attr('data-filter');
          if ('*'=== dataFilter) {
            value = '*';
          } else {
            if (allowMultiple) {
              value += dataFilter;
            } else {
              value=dataFilter;
            }
          console.log(value,allowMultiple);
          }
         });
        return value;
        };
      ";

      $script .= "})(jQuery);
      </script>";

      echo $script;

    }

  }
