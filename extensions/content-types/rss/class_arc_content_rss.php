<?php
/**
 * Project pizazzwp-architect.
 * File: class_arc_content_rss.php
 * User: chrishoward
 * Date: 19/10/14
 * Time: 11:04 PM
 */

class arc_content_rss extends arc_set_data{

  protected function __construct() {

    $registry                        = arc_Registry::getInstance();
    $prefix                          = '_content_rss_';
    $settings[ 'blueprint-content' ] = array(
        'type'        => 'rss',
        'name'        => 'RSS feed',
        'panel_class' => 'arc_Panel_RSS',
        'prefix'      => $prefix,
        // These are the sections to display on the admin metabox. We also use them to get default values.
        'sections'    => array(
            'title'      => 'RSS',
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-rss',
            'fields'     => array(
                array(
                    'title'    => __( 'RSS feed URL', 'pzarchitect' ),
                    'id'       => $prefix . 'rss-feed-url',
                    'type'     => 'text',
                    'default'  => ''
                ),
                array(
                    'title'    => __( 'Maximum items', 'pzarchitect' ),
                    'id'       => $prefix . 'rss-feed-count',
                    'type' => 'spinner',
                    'min' => 0,
                    'max' => 999,
                    'step' => 1,
                    'default'  => 10
                ),
                array(
                    'title'    => __( 'Exclude tags', 'pzarchitect' ),
                    'id'       => $prefix . 'rss-feed-exclude-tags',
                    'type'     => 'text',
                    'default'  => '',
                    'desc'=>__('Enter a comma separated list of tags to exclude using the tag slug name as defined on the source website','pzarchitect')
                ),
                array(
                    'title'    => __( 'Hide content', 'pzarchitect' ),
                    'id'       => $prefix . 'rss-feed-hide-content',
                    'type'     => 'button_list',
                    'default'  => 'no',
                    'options'=>array(
                        'no'=>__('No','pzarchitect'),
                        'yes'=>__('Yes','pzarchitect')
                    )
                ),
                array(
                    'title'    => __( 'Date format', 'pzarchitect' ),
                    'id'       => $prefix . 'rss-feed-date-format',
                    'type'     => 'text',
                    'default'  => 'j F Y | g:i a'
                ),
            )
        )
    );

    // This has to be post_type
    $registry->set( 'post_types', $settings );
    $registry->set( 'content_source', array( 'rss' => plugin_dir_path( __FILE__ ) ) );
  }

  private function __clone() {
  }

  private function __wakeup() {
  }
}

//  //todo:set this up as a proper singleton?

  $content_posts = arc_content_rss::getInstance( 'arc_content_rss' );
