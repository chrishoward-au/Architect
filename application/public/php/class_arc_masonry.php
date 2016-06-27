<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_masonry.php
   * User: chrishoward
   * Date: 12/09/15
   * Time: 12:43 PM
   */
  class arc_masonry {
    public $blueprint;
    public $layout_mode = 'masonry';

    public function __construct( &$blueprint ) {
      $this->blueprint = $blueprint;
      //var_dump($blueprint);

      $this->layout_mode = ( ! empty( $this->blueprint[ '_blueprints_masonry-features' ] ) && in_array( 'bidirectional', $this->blueprint[ '_blueprints_masonry-features' ] ) ? 'packery' : 'masonry' );
      add_action( 'arc_masonry_controls_' . $this->blueprint[ '_blueprints_short-name' ], array(
        $this,
        'sorting'
      ) );
      add_action( 'arc_masonry_controls_' . $this->blueprint[ '_blueprints_short-name' ], array(
        $this,
        'filtering'
      ) );
      add_action( 'arc-extend-panel-classes_' . $this->blueprint[ '_blueprints_short-name' ], array(
        $this,
        'filtering_classes'
      ), 10, 2 );
      //   echo '</div>';
      add_action( 'wp_print_footer_scripts', array( $this, 'js' ) );
    }

    function sorting() {

      if ( ! empty( $this->blueprint[ '_blueprints_masonry-sort-fields' ] ) && ! empty( $this->blueprint[ '_blueprints_masonry-features' ] ) && in_array( 'sorting', $this->blueprint[ '_blueprints_masonry-features' ] ) ) {

        global $_architect_options;
        $sortby_label = ! empty( $_architect_options[ 'architect_language-sorting-label' ] ) ? $_architect_options[ 'architect_language-sorting-label' ] : __( 'Sort by: ', 'pzarchitect' );
        $order_label  = ! empty( $_architect_options[ 'architect_language-sorting-order-label' ] ) ? $_architect_options[ 'architect_language-sorting-order-label' ] : __( 'Order: ', 'pzarchitect' );
        // TODO: Setupo registry for each so developer can define
        echo '<div class="arc-sort-buttons"><div class="arc-masonry-buttons button-group sort-by-button-group">
            <label>' . $sortby_label . ' </label>
            <button class="selected" data-sort-by="original-order">' . __( 'Default', 'pzarchitect' ) . '</button>';
        foreach ( $this->blueprint[ '_blueprints_masonry-sort-fields' ] as $k => $v ) {
          $sv = str_replace( array( '[', ']', '.' ), '', $v );
          $sk = str_replace( array( '-', '_', ' ' ), '', $sv );
          $sv = str_replace( array( '-', '_' ), ' ', $sv );
          $sv = ( $v == '[data-order]' ) ? 'Date' : $sv;
          echo '<button data-sort-by="' . $sk . '">' . ucwords( $sv ) . '</button>';
        }
        echo '</div>';
        echo '<div class="arc-masonry-buttons button-group sort-order-button-group">
            <label>' . $order_label . ' </label>
            <button class="selected" data-sort-order="true">' . __( 'Ascending', 'pzarchitect' ) . '</button>
            <button data-sort-order="false">' . __( 'Descending', 'pzarchitect' ) . '</button>';

        echo '</div></div>';
      }
    }

    function filtering() {
      // Need to do this for each taxonomy
      if ( ! empty( $this->blueprint[ '_blueprints_masonry-filtering' ] ) && ! empty( $this->blueprint[ '_blueprints_masonry-features' ] ) && in_array( 'filtering', $this->blueprint[ '_blueprints_masonry-features' ] ) ) {
        global $_architect_options;
        $i             = 1;
        $term_defaults = array();
        echo '<div class="arc-filter-buttons">';
        foreach ( $this->blueprint[ '_blueprints_masonry-filtering' ] as $tax ) {
          if ( ! empty( $this->blueprint[ '_blueprints_masonry-filtering-limit-' . $tax ] ) ) {
            switch ( $this->blueprint[ '_blueprints_masonry-filtering-limit-' . $tax ] ) {
              case 'include':
                $terms = pzarc_get_terms( $tax, array(
                  'hide_empty' => true,
                  'include'    => $this->blueprint[ '_blueprints_masonry-filtering-incexc-' . $tax ]
                ) );
                break;
              case 'exclude':
                $terms = pzarc_get_terms( $tax, array(
                  'hide_empty' => true,
                  'exclude'    => $this->blueprint[ '_blueprints_masonry-filtering-incexc-' . $tax ]
                ) );
                break;
              default:
              case 'none':
                $terms = pzarc_get_terms( $tax, array( 'hide_empty' => true ) );
                break;
            }
            $defaults = array();
            if ( $this->blueprint[ '_blueprints_masonry-filtering-set-defaults-' . $tax ] === 'yes' && ! empty( $this->blueprint[ '_blueprints_masonry-filtering-default-terms-' . $tax ] ) ) {
              $defaults              = pzarc_get_terms( $tax, array(
                'hide_empty' => true,
                'include'    => $this->blueprint[ '_blueprints_masonry-filtering-default-terms-' . $tax ]
              ) );
              $term_defaults[ $tax ] = $defaults;
            }
            echo '<div class="arc-masonry-buttons button-group filter-button-group">';
            if ( ! empty( $terms ) ) {
              $filterby_label = ! empty( $_architect_options[ 'architect_language-filtering-tax-label' ] ) ? $_architect_options[ 'architect_language-filtering-tax-label' ] : __( 'Filter %tax% by: ', 'pzarchitect' );

              echo '<label>' . str_replace( '%tax%', ucwords( str_replace( array(
                                                                             'pz_',
                                                                             '_',
                                                                             '-'
                                                                           ), ' ', $tax ) ), $filterby_label ) . '</label>';
              foreach ( $terms as $class => $name ) {
                $pre_selected = '';
                if ( array_key_exists( $class, $defaults ) ) {
                  $pre_selected = ' class="selected" ';
                }
                echo '<button data-filter=".' . $tax . '-' . $class . '" ' . $pre_selected . '>' . $name . '</button>';
              }
            }
            if ( $i ++ === count( $this->blueprint[ '_blueprints_masonry-filtering' ] ) ) {
              echo '<p>';
              if ( ( empty( $this->blueprint[ '_blueprints_masonry-filtering-allow-multiple' ] ) || $this->blueprint[ '_blueprints_masonry-filtering-allow-multiple' ] === 'multiple' ) ) {
                $clearall = ! empty( $_architect_options[ 'architect_language-clearall-button' ] ) ? $_architect_options[ 'architect_language-clearall-button' ] : __( 'Clear all', 'pzarchitect' );
                echo '<button data-filter="*" class="showall" data-filter-group="all">' . $clearall . '</button>';
              }
              if ( ! empty( $term_defaults ) ) {
                $term_defaults_set = array();
                foreach ( $term_defaults as $key => $value ) {
                  foreach ( $value as $k => $v ) {
                    $term_defaults_set[] = $key . '-' . $k;
                  }
                }
                $defaultstext = ! empty( $_architect_options[ 'architect_language-defaults-button' ] ) ? $_architect_options[ 'architect_language-defaults-button' ] : __( 'Defaults', 'pzarchitect' );
                echo '<button class="reset-to-defaults" data-defaults="' . implode( '.', $term_defaults_set ) . '">' . $defaultstext . '</button>';
              }
              echo '</p>';
              echo '</div>';
            }
          }
        }
        echo '</div>';
      }
    }

    function filtering_classes( $classes, $blueprint ) {
      if ( ! empty( $this->blueprint[ '_blueprints_masonry-filtering' ] ) ) {
        $classes .= ' ';
        foreach ( $this->blueprint[ '_blueprints_masonry-filtering' ] as $tax ) {
          $tax_exists = get_taxonomy( $tax );
          if ( ! empty( $tax_exists ) ) {
            $terms = get_the_terms( get_the_ID(), $tax );
            if ( ! empty( $terms ) && ! isset( $terms->errors ) ) {
              foreach ( $terms as $term_obj ) {
                $classes .= $tax . '-' . $term_obj->slug . ' ';
              }
            }
          }
        }
      }

      return $classes;
    }

    function js() {
      $blueprint = $this->blueprint[ '_blueprints_short-name' ];
      $sort_data = '';
      if ( ! empty( $this->blueprint[ '_blueprints_masonry-sort-fields' ] ) ) {

        foreach ( $this->blueprint[ '_blueprints_masonry-sort-fields' ] as $k => $v ) {
          if ( $k !== 'random' ) {
            $s = str_replace( array( '.', '-', '_', '[', ']', ' ' ), '', $v );
            switch ( $v ) {
              case  '[data-order]':
                $sort_data .= "{$s}:function(itemElem){
              return jQuery(itemElem).find('.entry-meta').attr('data-order');
            },";
                break;
              case
              'random':
                break;
              default:

                if ( ! empty( $this->blueprint[ '_blueprints_masonry-sort-fields-numeric' ] ) && in_array( $v, $this->blueprint[ '_blueprints_masonry-sort-fields-numeric' ] ) ) {
//                  $v = '[data-sort-numeric]';
                  $v = "function (elem) {return parseFloat(jQuery(elem).find('.{$v}').attr('data-sort-numeric'));}";
                  $sort_data .= "{$s}:{$v},";
                } elseif ( ! empty( $this->blueprint[ '_blueprints_masonry-sort-fields-date' ] ) && in_array( $v, $this->blueprint[ '_blueprints_masonry-sort-fields-date' ] ) ) {
                  //              $v = '[data-sort-date]';
                  $v = "function (elem) {return parseInt(jQuery(elem).find('.{$v}').attr('data-sort-date'));}";
                  $sort_data .= "{$s}:{$v},";
                } else {
                  $v = strpos( $v, '.' ) !== 0 && strpos( $v, '#' ) !== 0 ? '.' . $v : $v;
                  $sort_data .= "{$s}:'{$v}',";
                }
            }
          }
        }
      }
      $sort_data = $sort_data ? substr( $sort_data, 0, - 1 ) : $sort_data;

      $blueprint_id = '#pzarc-blueprint_' . $blueprint;

      if ( $this->layout_mode == 'packery' ) {
        $mode_options = "packery: {
                          gutter: '{$blueprint_id} .gutter-sizer'
                        }
                    ";
      } else {
        $mode_options = "masonry: {
                          columnWidth: '{$blueprint_id} .grid-sizer',
                          gutter: '{$blueprint_id} .gutter-sizer'
                        }
                    ";
      }


      $transition_duration = ! empty( $this->blueprint[ '_blueprints_masonry-animation-duration' ] ) ? $this->blueprint[ '_blueprints_masonry-animation-duration' ] : 300;
      $stagger_duration    = ! empty( $this->blueprint[ '_blueprints_masonry-animation-stagger' ] ) ? $this->blueprint[ '_blueprints_masonry-animation-stagger' ] : 30;
      $allow_multiple      = empty( $this->blueprint[ '_blueprints_masonry-filtering-allow-multiple' ] ) || $this->blueprint[ '_blueprints_masonry-filtering-allow-multiple' ] === 'multiple' ? 'true' : 'false';

      echo "
      <script type='text/javascript' id='masonry-{$blueprint}'>
       (function($){
        var bpsection = jQuery( '.pzarc-section-using-{$blueprint}' );
        jQuery(bpsection).imagesLoaded( function () {
          var allowMultiple={$allow_multiple};
          var arcIsotopeID = jQuery( bpsection ).attr( 'data-uid' );
          var defaultsButton = jQuery('{$blueprint_id} .reset-to-defaults');
          var container = jQuery( bpsection ).isotope( {
            // options
            layoutMode: '{$this->layout_mode}',
            itemSelector: '.' + arcIsotopeID + ' .pzarc-panel',
            transitionDuration: {$transition_duration},
            stagger: {$stagger_duration},
            getSortData: {
              {$sort_data}
            },
            {$mode_options}
          } );
          setDefaults(false,defaultsButton);
          setGutterGridOn();
          container.isotope('updateSortData').isotope('layout');


          /*
           * sort items on button click
           */
          jQuery('{$blueprint_id} .sort-by-button-group').on( 'click', 'button', function() {
            jQuery('{$blueprint_id} .sort-by-button-group').find('.selected').removeClass('selected');
            jQuery(this).addClass('selected');
            var sortByValue = $(this).attr('data-sort-by');
            var sortOrderValue = ($('{$blueprint_id} .sort-order-button-group .selected').attr('data-sort-order')=='true');
            setGutterGridOn();
            container.isotope({ sortBy: sortByValue, sortAscending: sortOrderValue});
          });
      
          /*
          *
          */
          jQuery('{$blueprint_id} .sort-order-button-group').on( 'click', 'button', function() {
            jQuery('{$blueprint_id} .sort-order-button-group').find('.selected').removeClass('selected');
            jQuery(this).addClass('selected');
            var sortOrderValue = ($(this).attr('data-sort-order')=='true');
            var sortByValue = $('{$blueprint_id} .sort-by-button-group .selected').attr('data-sort-by');
            setGutterGridOn();
            container.isotope({ sortByValue: sortByValue, sortAscending: sortOrderValue});
          });
  
        /*
         * Filter term click
         */
        jQuery('{$blueprint_id} .filter-button-group').on( 'click', 'button', function(e) {
          setSelected(this,false)
        });
  
     
          /*
          *  Defaults click
          */
        defaultsButton.on( 'click', function(e) {
         setDefaults(this,false);
        });
  
        /*
        * Set default selection
        */
        function setDefaults(defBtn,onLoad){
          if (!defBtn && !onLoad.length) {
           return;
          }
          
          if (onLoad) {
            defBtn = onLoad;
          } else {
            defBtn = jQuery(defBtn);
          }
          jQuery('{$blueprint_id} .filter-button-group .selected').removeClass('selected');
          filterValue = jQuery(defBtn).attr('data-defaults');
            setGutterGridOn();
          container.isotope({ filter: '.' + filterValue });
          defaultsSet = filterValue.split('.');
          for (let taxterm of defaultsSet) {
            jQuery( '{$blueprint_id} .filter-button-group [data-filter=\".' + taxterm + '\"]').addClass('selected') ;
          }
        }
        
          /*
          * setSelected
          */
        function setSelected(t) {
          if (jQuery(t).hasClass('selected')) {
            jQuery(t).removeClass('selected');
          } else {
            if (!allowMultiple) {
              jQuery('{$blueprint_id} .filter-button-group .selected').removeClass('selected');
            }
            jQuery(t).addClass('selected');
          }
  
          if (jQuery(t).hasClass('showall')) {
            // Removes selected from all selected buttons
            jQuery('{$blueprint_id} .filter-button-group .selected').removeClass('selected');
            jQuery(t).removeClass('selected');
          } else {
            jQuery('{$blueprint_id} .filter-button-group .showall').removeClass('selected');
          }
  
          if (jQuery(t).hasClass('reset-to-defaults')) {
            jQuery(t).removeClass('selected');
          } else {
            var selectedTerms = jQuery('{$blueprint_id} .filter-button-group .selected');
            var filterValue = concatValues(selectedTerms);
            setGutterGridOn();
            container.isotope({ filter: filterValue });
         }
        }
        
        
          /*
          *
          */
        function concatValues( t ) {
         var value = '';
         jQuery(t).each(function(){
           var dataFilter = jQuery(this).attr('data-filter');
            if ( '*' === dataFilter ) {
              value = '*';
            } else {
              if (allowMultiple) {
                value += dataFilter;
              } else {
                value = dataFilter;
              }
            }
         });
         return value;
       }
       
       /*
       * Masonry keep setting grid size to display:none, and then that makes gutter zero.
        */
       function setGutterGridOn() {
        jQuery('{$blueprint_id} .grid-sizer').css('display','block');
        jQuery('{$blueprint_id} .gutter-sizer').css('display','block');
       }
       
     });  // images loaded
    })(jQuery)
  </script>";


    }

  }
