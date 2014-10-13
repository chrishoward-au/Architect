<?php
  /**
   * Project pizazzwp-architect.
   * File: class_arc_Blueprint_Data.php
   * User: chrishoward
   * Date: 13/10/14
   * Time: 5:37 PM
   */
  // Add content info to the registry
  class arc_Blueprint_Data extends arc_set_data
  {


    protected function __construct()
    {
      $registry = Registry::getInstance();

      $prefix = '_content_general_';

      $settings[ 'general' ] = array(
          'prefix'   => $prefix,
          'sections' => array(
              'title'      => 'Settings',
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-adjust-alt',
              'fields'     => array(
                  array(
                      'title'    => __('Order by', 'pzarchitect'),
                      'id'       => $prefix . 'orderby',
                      'type'     => 'button_set',
                      'default'  => 'date',
                      'cols'     => 6,
                      'options'  => array(
                          'date'       => 'Date',
                          'title'      => 'Title',
                          'menu_order' => 'Page order (if set)',
                          'rand'       => 'Random',
                      ),
                      'subtitle' => 'Some hosts disable random as it slows things down significantly on large sites. WPEngine does this. Look in WPE Dashboard, Advanced Configuration.'
                  ),
                  array(
                      'title'   => __('Order direction', 'pzarchitect'),
                      'id'      => $prefix . 'orderdir',
                      'type'    => 'button_set',
                      'default' => 'DESC',
                      'options' => array(
                          'ASC'  => 'Ascending',
                          'DESC' => 'Descending',
                      ),
                  ),
                  array(
                      'title'   => __('Skip N posts', 'pzarchitect'),
                      'id'      => $prefix . 'skip',
                      'type'    => 'spinner',
                      'min'     => 0,
                      'max'     => 9999,
                      'step'    => 1,
                      'default' => 0,
                      'desc'    => __('Note: Skipping breaks pagination. This is a known WordPress issue.', 'pzarchitect'),
                  ),
                  array(
                      'title'   => __('Sticky posts first', 'pzarchitect'),
                      'id'      => $prefix . 'sticky',
                      'type'    => 'switch',
                      'on'      => 'Yes',
                      'off'     => 'No',
                      'default' => false,
                  ),
              )
          )
      );

      /** GENERAL  Filters*/
      $prefix = '_content_general_';

      $settings[ 'filters' ] = array(
          'prefix'   => $prefix,
          'sections' => array(
              'title'      => __('Filters', 'pzarchitect'),
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-filter',
              'fields'     => array(
                  array(
                      'title'  => __('Categories', 'pzarchitect'),
                      'id'     => $prefix . 'categories-heading-start',
                      'type'   => 'section',
                      'class'  => ' heading',
                      'indent' => true
                  ),
                  array(
                      'title'   => __('Include categories', 'pzarchitect'),
                      'id'      => $prefix . 'inc-cats',
                      'type'    => 'select',
                      'select2' => array('allowClear' => true),
                      //                'wpqv'    => 'category__in',
                      'data'    => 'category',
                      'multi'   => true
                  ),
                  array(
                      'title'   => __('In ANY or ALL categories', 'pzarchitect'),
                      'id'      => $prefix . 'all-cats',
                      'type'    => 'button_set',
                      'options' => array('any' => 'Any', 'all' => 'All'),
                      //               'wpqv'    => 'category__and',
                      'default' => 'any',
                  ),
                  array(
                      'title'   => __('Exclude categories', 'pzarchitect'),
                      'id'      => $prefix . 'exc-cats',
                      'type'    => 'select',
                      'select2' => array('allowClear' => true),
                      //                'wpqv'  => 'category__not_in',
                      'data'    => 'category',
                      'multi'   => true
                  ),
                  array(
                      'title'    => __('Include sub-categories on archives', 'pzarchitect'),
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
                      'title'  => __('Tags', 'pzarchitect'),
                      'id'     => $prefix . 'tags-section-start',
                      'type'   => 'section',
                      'class'  => ' heading',
                      'indent' => true
                  ),
                  array(
                      'title' => __('Tags', 'pzarchitect'),
                      'id'    => $prefix . 'inc-tags',
                      'type'  => 'select',
                      //              'select2' => array('allowClear' => true),
                      'data'  => 'tags',
                      'multi' => true
                  ),
                  array(
                      'title'   => __('Exclude tags', 'pzarchitect'),
                      'id'      => $prefix . 'exc-tags',
                      'type'    => 'select',
                      'select2' => array('allowClear' => true),
                      'data'    => 'tags',
                      'multi'   => true
                  ),
                  array(
                      'id'     => $prefix . 'tags-section-end',
                      'type'   => 'section',
                      'indent' => false
                  ),
                  array(
                      'title'  => __('Custom taxonomies', 'pzarchitect'),
                      'id'     => $prefix . 'custom-taxonomies-section-start',
                      'type'   => 'section',
                      'class'  => ' heading',
                      'indent' => true
                  ),
                  // TODO: Add a loop to display all custom taxonomies
                  // foreach($taxonomies as $taxonomy ){}
                  array(
                      'title' => __('Other taxonomies', 'pzarchitect'),
                      'id'    => $prefix . 'other-tax',
                      'type'  => 'select',
                      'data'  => 'taxonomies',
                      'args'  => array('_builtin' => false)
                  ),
                  array(
                      'title'    => __('Other taxonomy tags', 'pzarchitect'),
                      'id'       => $prefix . 'other-tax-tags',
                      'type'     => 'text',
                      'subtitle' => 'Enter a comma separated list of tags to filter by in the chosen custom taxonomy'
                  ),
                  array(
                      'title'   => __('Taxonomies operator', 'pzarchitect'),
                      'id'      => $prefix . 'tax-op',
                      'type'    => 'button_set',
                      'options' => array('AND' => 'All', 'IN' => 'Any', 'NOT IN' => 'None'),
                      'default' => 'IN',
                      'hint'    => array('content' => __('Display posts containing all, any or none of the taxonomies', 'pzarchitect')),
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
                      'title'  => __('Others', 'pzarchitect'),
                      'id'     => $prefix . 'other-section-start',
                      'type'   => 'section',
                      'class'  => ' heading',
                      'indent' => true
                  ),
                  array(
                      'title'   => __('Authors', 'pzarchitect'),
                      'id'      => $prefix . 'authors',
                      'type'    => 'select',
                      'data'    => 'callback',
                      'args'    => array('pzarc_get_authors'),
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
      $registry->set('blueprint-content-common', $settings);
    }

  }

//  //todo:set this up as a proper singleton?
  $content_posts = arc_Blueprint_Data::getInstance();



