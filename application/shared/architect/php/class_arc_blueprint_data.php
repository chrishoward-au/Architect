<?php
  /**
   * Project pizazzwp-architect.
   * File: class_arc_blueprint_data.php
   * User: chrishoward
   * Date: 13/10/14
   * Time: 5:37 PM
   */
  // Add content info to the registry
  class arc_Blueprint_Data extends arc_set_data {


    protected function __construct() {
      $registry = arc_Registry::getInstance();

      $prefix = '_content_general_';

      $settings[ 'settings' ] = array(
        'prefix'   => $prefix,
        'sections' => array(
          'title'      => 'Settings & sorting',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-adjust-alt',
          'fields'     => array(
            array(
              'title'    => __( 'Sort by', 'pzarchitect' ),
              'id'       => $prefix . 'orderby',
              'type'     => 'button_set',
              'default'  => 'date',
              'cols'     => 6,
              'options'  => array(
                'date'       => 'Date',
                'title'      => 'Title',
                'menu_order' => 'Page order (pages only)',
                'post__in'   => 'Specified',
                'rand'       => 'Random',
                'none'       => 'None',
                'custom'     => 'Custom field'
              ),
              'desc'=>__('Specified only works when the if the source is using the specific posts/page/etc option.','pzarchitect'),
              'subtitle' => 'Some hosts disable random as it slows things down significantly on large sites. WPEngine does this. Look in WPE Dashboard, Advanced Configuration.'
            ),
            array(
              'title'   => __( 'Sort direction', 'pzarchitect' ),
              'id'      => $prefix . 'orderdir',
              'type'    => 'button_set',
              'default' => 'DESC',
              'options' => array(
                'ASC'  => 'Ascending',
                'DESC' => 'Descending',
              ),
            ),

            array(
              'title'   => __( 'Skip N posts', 'pzarchitect' ),
              'id'      => $prefix . 'skip',
              'type'    => 'spinner',
              'min'     => 0,
              'max'     => 9999,
              'step'    => 1,
              'default' => 0,
              'desc'    => '<strong style="color:tomato;">' . __( 'Note: Skipping breaks pagination. This is a known WordPress issue. Also, skipping does not work if no post limit set. Again a WP limitation. Use a high number of posts to show as a workaround', 'pzarchitect' ) . '</strong>',
              //                      'required'=>array(
              //                          array('_blueprints_pagination-per-page','=',false),
              ////                          array('_blueprints_section-0-panels-limited','=',false),
              //                      )
            ),
            array(
              'title'   => __( 'Sticky posts first', 'pzarchitect' ),
              'id'      => $prefix . 'sticky',
              'type'    => 'switch',
              'on'      => 'Yes',
              'off'     => 'No',
              'default' => false,
            ),
            //                  array(
            //                      'title'  => __('Pagination', 'pzarchitect'),
            //                      'id'     => $prefix . 'pagination-heading-start',
            //                      'type'   => 'section',
            //                      'indent' => false
            //                  ),
          )
        )
      );

      /** GENERAL  Filters*/
      $prefix = '_content_general_';

      $settings[ 'filters' ] = array(
        'prefix'   => $prefix,
        'sections' => array(
          'title'      => __( 'Filters', 'pzarchitect' ),
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-filter',
          'fields'     => array(
            array(
              'title'  => __( 'Categories', 'pzarchitect' ),
              'id'     => $prefix . 'categories-heading-start',
              'type'   => 'section',
              'class'  => ' heading',
              'indent' => true
            ),
            array(
              'title'   => __( 'Include categories', 'pzarchitect' ),
              'id'      => $prefix . 'inc-cats',
              'type'    => 'select',
              'select2' => array( 'allowClear' => true ),
              //                'wpqv'    => 'category__in',
              'data'    => 'category',
              'multi'   => true
            ),
            array(
              'title'   => __( 'In ANY or ALL categories', 'pzarchitect' ),
              'id'      => $prefix . 'all-cats',
              'type'    => 'button_set',
              'options' => array( 'any' => 'Any', 'all' => 'All' ),
              //               'wpqv'    => 'category__and',
              'default' => 'any',
            ),
            array(
              'title'   => __( 'Exclude categories', 'pzarchitect' ),
              'id'      => $prefix . 'exc-cats',
              'type'    => 'select',
              'select2' => array( 'allowClear' => true ),
              //                'wpqv'  => 'category__not_in',
              'data'    => 'category',
              'multi'   => true
            ),
            array(
              'title'    => __( 'Include sub-categories on archives', 'pzarchitect' ),
              'id'       => $prefix . 'sub-cats',
              'type'     => 'switch',
              'on'       => 'Yes',
              'off'      => 'No',
              'default'  => false,
              'subtitle' => 'This requires a specified post type, not Defaults'
            ),
            array(
              'id'     => $prefix . 'categories-section-end',
              'type'   => 'section',
              'indent' => false
            ),
            array(
              'title'  => __( 'Tags', 'pzarchitect' ),
              'id'     => $prefix . 'tags-section-start',
              'type'   => 'section',
              'class'  => ' heading',
              'indent' => true
            ),
            array(
              'title' => __( 'Tags', 'pzarchitect' ),
              'id'    => $prefix . 'inc-tags',
              'type'  => 'select',
              //              'select2' => array('allowClear' => true),
              'data'  => 'tags',
              'multi' => true
            ),
            array(
              'title'   => __( 'Exclude tags', 'pzarchitect' ),
              'id'      => $prefix . 'exc-tags',
              'type'    => 'select',
              'select2' => array( 'allowClear' => true ),
              'data'    => 'tags',
              'multi'   => true
            ),
            array(
              'id'     => $prefix . 'tags-section-end',
              'type'   => 'section',
              'indent' => false
            ),
            array(
              'title'  => __( 'Custom taxonomies', 'pzarchitect' ),
              'id'     => $prefix . 'custom-taxonomies-section-start',
              'type'   => 'section',
              'class'  => ' heading',
              'indent' => true
            ),
            // TODO: Add a loop to display all custom taxonomies
            // foreach($taxonomies as $taxonomy ){}
            array(
              'title'   => __( 'Other taxonomies', 'pzarchitect' ),
              'id'      => $prefix . 'other-tax',
              'type'    => 'select',
              'select2' => array( 'allowClear' => true ),
              'data'    => 'taxonomies',
              'args'    => array( '_builtin' => false )
            ),
            array(
              'title'    => __( 'Other taxonomy terms', 'pzarchitect' ),
              'id'       => $prefix . 'other-tax-tags',
              'type'     => 'select',
              'select2'  => array( 'allowClear' => true ),
              'data'     => 'callback',
              'multi'    => true,
              'args'     => array( 'pzarc_get_tags' ),
              'subtitle' => __( 'Select terms to filter by in the chosen custom taxonomy', 'pzarchitect' ),
              'desc'     => __( 'To populate this dropdown, select the Custom Taxonomy above, then Publish or Update this Blueprint', 'pzarchitect' )
            ),
            array(
              'title'   => __( 'Taxonomies operator', 'pzarchitect' ),
              'id'      => $prefix . 'tax-op',
              'type'    => 'button_set',
              'options' => array( 'AND' => 'All', 'IN' => 'Any', 'NOT IN' => 'None' ),
              'default' => 'IN',
              'hint'    => array( 'content' => __( 'Display posts containing all, any or none of the taxonomies', 'pzarchitect' ) ),
            ),
            //TODO: Add taxomonies to exclude
            //    array(
            //      'title' => __('Days to show', 'pzarchitect'),
            //      'id' => $prefix . 'days',
            //      'type' => 'text',
            //      'cols'=>6,
            //              //      'default' => 'All',
            //    ),
            //    array(
            //      'title' => __('Days to show until', 'pzarchitect'),
            //      'id' => $prefix . 'days-until',
            //      'type' => 'text',
            //      'cols'=>6,
            //
            //    ),
            array(
              'id'     => $prefix . 'custom-taxonomies-section-end',
              'type'   => 'section',
              'indent' => false
            ),
            array(
              'title'  => __( 'Others', 'pzarchitect' ),
              'id'     => $prefix . 'other-section-start',
              'type'   => 'section',
              'class'  => ' heading',
              'indent' => true
            ),
            array(
              'title'   => __( 'Authors', 'pzarchitect' ),
              'id'      => $prefix . 'authors',
              'type'    => 'select',
              'data'    => 'callback',
              'args'    => array( 'pzarc_get_authors' ),
              'default' => 'all',
            ),
            array(
              'id'     => $prefix . 'other-section-end',
              'type'   => 'section',
              'indent' => false
            ),
          )
        )
      );

      // This has to be post_type
      $registry->set( 'blueprint-content-common', $settings );
    }

  }

//  //todo:set this up as a proper singleton?
  $content_posts = arc_Blueprint_Data::getInstance( 'arc_Blueprint_Data' );



