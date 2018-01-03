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

      $settings['settings'] = array(
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
                'menu_order' => 'Page order',
                'post__in'   => 'Specified',
                'rand'       => 'Random',
                'none'       => 'None',
                'custom'     => 'Custom field',
              ),
              'desc'     => __( 'Specified only works when the source is using the specific posts/page/etc option. Page order works only for page types - these will have a page order option', 'pzarchitect' ),
              'subtitle' => 'Some hosts disable random as it slows things down significantly on large sites. WPEngine does this. Look in WPE Dashboard, Advanced Configuration.',
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
              'default' => FALSE,
            ),
            //                  array(
            //                      'title'  => __('Pagination', 'pzarchitect'),
            //                      'id'     => $prefix . 'pagination-heading-start',
            //                      'type'   => 'section',
            //                      'indent' => false
            //                  ),
          ),
        ),
      );

      /** GENERAL  Filters*/
      $prefix = '_content_general_';

      $settings['filters'] = array(
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
              'indent' => TRUE,
            ),
            array(
              'title'   => __( 'Include categories', 'pzarchitect' ),
              'id'      => $prefix . 'inc-cats',
              'type'    => 'select',
              'select2' => array( 'allowClear' => TRUE ),
              //                'wpqv'    => 'category__in',
              'data'    => 'category',
              'multi'   => TRUE,
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
              'select2' => array( 'allowClear' => TRUE ),
              //                'wpqv'  => 'category__not_in',
              'data'    => 'category',
              'multi'   => TRUE,
            ),
            array(
              'title'    => __( 'Include sub-categories on archives', 'pzarchitect' ),
              'id'       => $prefix . 'sub-cats',
              'type'     => 'switch',
              'on'       => 'Yes',
              'off'      => 'No',
              'default'  => FALSE,
              'subtitle' => 'This requires a specified post type, not Defaults',
            ),
            array(
              'id'     => $prefix . 'categories-section-end',
              'type'   => 'section',
              'indent' => FALSE,
            ),
            array(
              'title'  => __( 'Tags', 'pzarchitect' ),
              'id'     => $prefix . 'tags-section-start',
              'type'   => 'section',
              'class'  => ' heading',
              'indent' => TRUE,
            ),
            array(
              'title' => __( 'Tags', 'pzarchitect' ),
              'id'    => $prefix . 'inc-tags',
              'type'  => 'select',
              //              'select2' => array('allowClear' => true),
              'data'  => 'tags',
              'multi' => TRUE,
            ),
            array(
              'title'   => __( 'Exclude tags', 'pzarchitect' ),
              'id'      => $prefix . 'exc-tags',
              'type'    => 'select',
              'select2' => array( 'allowClear' => TRUE ),
              'data'    => 'tags',
              'multi'   => TRUE,
            ),
            array(
              'id'     => $prefix . 'tags-section-end',
              'type'   => 'section',
              'indent' => FALSE,
            ),
            array(
              'title'  => __( 'Custom taxonomies', 'pzarchitect' ),
              'id'     => $prefix . 'custom-taxonomies-section-start',
              'type'   => 'section',
              'class'  => ' heading',
              'indent' => TRUE,
            ),
            // TODO: Add a loop to display all custom taxonomies
            // foreach($taxonomies as $taxonomy ){}
            array(
              'title'   => __( 'Other taxonomies', 'pzarchitect' ),
              'id'      => $prefix . 'other-tax',
              'type'    => 'select',
              'select2' => array( 'allowClear' => TRUE ),
              'data'    => 'taxonomies',
              'args'    => array( '_builtin' => FALSE ),
            ),
            array(
              'title'    => __( 'Other taxonomy terms', 'pzarchitect' ),
              'id'       => $prefix . 'other-tax-tags',
              'type'     => 'select',
              'select2'  => array( 'allowClear' => TRUE ),
              'data'     => 'callback',
              'multi'    => TRUE,
              'args'     => array( 'pzarc_get_tags' ),
              'subtitle' => __( 'Select terms to filter by in the chosen custom taxonomy', 'pzarchitect' ),
              'desc'     => __( 'To populate this dropdown, select the Custom Taxonomy above, then Publish or Update this Blueprint', 'pzarchitect' ),
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
              'indent' => FALSE,
            ),
            array(
              'title'  => __( 'Dates', 'pzarchitect' ),
              'id'     => $prefix . 'dates-section-start',
              'type'   => 'section',
              'class'  => ' heading',
              'indent' => TRUE,
            ),
            array(
              'title'   => __( 'Number of days to show', 'pzarchitect' ),
              'id'      => $prefix . 'days-to-show',
              'type'    => 'text',
              'default' => '',
              'desc'    => __( 'Limit number of days to show. Use only end or start date. Enter \'all\' to show all', 'pzarchitect' ),
            ),
            array(
              'title'   => __( 'Start date', 'pzarchitect' ),
              'id'      => $prefix . 'start-date',
              'type'    => 'text',
              'default' => '',
              'desc'    => __( 'Enter a date string. e.g. today, yesterday, 2017-10-24, 24-10-2017, 24 October 2017, 10/24/2017.', 'pzarchitect' ),
            ),
            array(
              'title'   => __( 'End date', 'pzarchitect' ),
              'id'      => $prefix . 'end-date',
              'type'    => 'text',
              'default' => '',
              'desc'    => __( 'Enter a valid date string. e.g. today, yesterday, 2017-10-24, 24-10-2017, 24 October 2017, 10/24/2017', 'pzarchitect' ),
            ),
            array(
              'title'   => __( 'Inclusive', 'pzarchitect' ),
              'id'      => $prefix . 'inclusive',
              'type'    => 'checkbox',
              'default' => FALSE,
              'desc'=> __('Include posts made on start and end dates','pzarchitect')
            ),
// Add this later if needed
//            array(
//              'title'   => __( 'Use timezone', 'pzarchitect' ),
//              'id'      => $prefix . 'use-timezone',
//              'type'    => 'checkbox',
//              'default' => FALSE,
//            ),
            array(
              'id'     => $prefix . 'dates-section-end',
              'type'   => 'section',
              'indent' => FALSE,
            ),
            array(
              'title'  => __( 'Authors', 'pzarchitect' ),
              'id'     => $prefix . 'authors-section-start',
              'type'   => 'section',
              'class'  => ' heading',
              'indent' => TRUE,
            ),
            array(
              'title'   => __( 'Authors', 'pzarchitect' ),
              'id'      => $prefix . 'authors',
              'type'    => 'select',
              'multi'   => TRUE,
              'data'    => 'callback',
              'args'    => array( 'pzarc_get_authors' ),
              'default' => 'all',
            ),
            array(
              'title'   => __( 'Exclude these authors', 'pzarchitect' ),
              'id'      => $prefix . 'exclude-authors',
              'type'    => 'checkbox',
              'default' => FALSE,
            ),
            array(
              'id'     => $prefix . 'authors-section-end',
              'type'   => 'section',
              'indent' => FALSE,
            ),
          ),
        ),
      );

      // This has to be post_type
      $registry->set( 'blueprint-content-common', $settings );
    }

  }

//  //todo:set this up as a proper singleton?
  $content_posts = arc_Blueprint_Data::getInstance( 'arc_Blueprint_Data' );



