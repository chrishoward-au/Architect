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
      add_action('wp_print_footer_scripts', array($this, 'js'));
    }

    function sorting()
    {

      // TODO: Setupo registry for each so developer can define
      echo '<div class="arc-sort-buttons"><div class="button-group sort-by-button-group">
            <label>'.__('Sort by','pzarchitect').': </label>
            <button class="current" data-sort-by="original-order">'.__('Default','pzarchitect').'</button>';
      foreach ($this->blueprint[ '_blueprints_masonry-sort-fields' ] as $k => $v) {
        $sv = str_replace(array('[', ']', '.'), '', $v);
        $sk = str_replace(array('-', '_', ' '), '', $sv);
        $sv = str_replace(array('-', '_'), ' ', $sv);
        $sv = ($v == '[data-order]') ? 'Date' : $sv;
        echo '<button data-sort-by="' . $sk . '">' . ucwords($sv) . '</button>';
      }

      echo '</div>';
      echo '<div class="button-group sort-order-button-group">
            <label>'.__('Order','pzarchitect').': </label>
            <button class="current" data-sort-order="true">'.__('Ascending','pzarchitect').'</button>
            <button data-sort-order="false">'.__('Descending','pzarchitect').'</button>';

      echo '</div></div>';

    }

    function filtering() {
      // Need to do this for each taxonomy
      $terms = pzarc_get_terms('category',array('hide_empty'=>true));

      var_Dump($terms);
     echo '
     <div class="button-group filter-button-group">
     <label>'.__('Filters','pzarchitect').': </label>
  <button data-filter="*" class="current">'.__('Show all','pzarchitect').'</button>
  <button data-filter=".metal">metal</button>
  <button data-filter=".transition">transition</button>
  <button data-filter=".alkali, .alkaline-earth">alkali & alkaline-earth</button>
  <button data-filter=":not(.transition)">not transition</button>
  <button data-filter=".metal:not(.transition)">metal but not transition</button>
</div>
     ';
    }

    function js()
    {
      $blueprint = $this->blueprint[ '_blueprints_short-name' ];
      $sort_data = '';
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
              $sort_data .= "{$s}:'{$v}',";
          }
        }
      }
      $sort_data = $sort_data ? substr($sort_data, 0, -1) : $sort_data;

      echo "<script type='text/javascript' id='masonry-{$blueprint}'>
        // init Isotope
        (function($){
            var container = jQuery( '.pzarc-section-using-{$blueprint}' );
               var arcIsotopeID = jQuery( container ).attr( 'data-uid' );
              jQuery(container).imagesLoaded( function ()
              {
          //      console.log( arcIsotopeID );
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
          jQuery('.sort-by-button-group').find('.current').removeClass('current');
          jQuery(this).addClass('current');
          var sortByValue = $(this).attr('data-sort-by');
          var sortOrderValue = ($('#pzarc-blueprint_{$blueprint} .sort-order-button-group .current').attr('data-sort-order')=='true');
          console.log(sortOrderValue, sortByValue);
          container.isotope({ sortBy: sortByValue, sortAscending: sortOrderValue });
        });

        jQuery('.sort-order-button-group').on( 'click', 'button', function() {
          jQuery('.sort-order-button-group').find('.current').removeClass('current');
          jQuery(this).addClass('current');
          var sortOrderValue = ($(this).attr('data-sort-order')=='true');
          var sortByValue = $('#pzarc-blueprint_{$blueprint} .sort-by-button-group .current').attr('data-sort-by');
          console.log(sortOrderValue, sortByValue);
          container.isotope({ sortByValue: sortByValue, sortAscending: sortOrderValue });
        });

      })(jQuery);
      </script>";


    }
  }
