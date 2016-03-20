<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_class_generic.php
   * User: chrishoward
   * Date: 11/10/14
   * Time: 9:39 PM
   */

  /* THIS IS NEVER USED. IT'S JUST HERE AS AN EXAMPLE */

  class arc_content_generic extends arc_set_data {

    protected function __construct() {

      $registry = arc_Registry::getInstance();
      $registry->set( 'content_source', array( 'generic' => plugin_dir_path( __FILE__ ) ) );
      $registry->set( 'content_tabs',
                      array(
                        'generic' => array(
                          'options' => array(
                            'titles'       => '<span class="pzarc-tab-title">' . __( 'Title', 'pzarchitect' ) . '</span>',
                            'meta'         => '<span class="pzarc-tab-title">' . __( 'Meta', 'pzarchitect' ) . '</span>',
                            'features'     => '<span class="pzarc-tab-title">' . __( 'Feature', 'pzarchitect' ) . '</span>',
                            'body'         => '<span class="pzarc-tab-title">' . __( 'Body/Excerpt', 'pzarchitect' ) . '</span>',
                            'customfields' => '<span class="pzarc-tab-title">' . __( 'Custom Fields', 'pzarchitect' ) . '</span>',
                          ),
                          'targets' =>
                            array(
                              'titles'       => array( 'titles-settings' ),
                              'meta'         => array( 'meta-settings' ),
                              'features'     => array( 'features-settings' ),
                              'body'         => array( 'body-settings' ),
                              'customfields' => array( 'customfields-settings' ),
                            )
                        )
                      )
      );

    }
  }

