<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 23/03/2014
   * Time: 6:27 PM
   */
  /*
   * Defines the slideshow
   */

  class architectDisplay
  {
    public $display = array(
        'content'  => '', // Filled later with recordset
        'type'     => 'grid', // grid,tabbed,slider,single
        'settings' => array(  // Maybe this should jsut be populated from metabox values
            'general' => array(),
            'grid'    => array(),
            'tabbed'  => array(),
            'single'  => array(),
            'slider'  => array(
                'transition' => '',
                'delay'      => '',
                'duration'   => '',
            ),
        ),
    );
  }

  /*
   * and object that holds all the records in the slideshow
   */

  class architectRecordset
  {

    public $recordset = array();
  }

  /*
   * A single record from the record set
   */

  class architectRecord
  {

    public $record = array(
        'title'        => '',
        'content'      => '',
        'excerpt'      => '',
        'meta1'        => '',
        'meta2'        => '',
        'meta3'        => '',
        'feature'      => '',
        'caption'      => '',
        'customfields' => array()
    );


    function set($field) { }

    function get($field) { }
  }

  /*
   * A single slide object built from the record and the definition
   */

  class architectPanel
  {

    public $id;

    public $record;

    function get_record($recordid) { }

    function set_record($recordid) { }
  }

