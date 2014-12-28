<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 29/04/2014
   * Time: 12:16 PM
   */

  // TODO: These should also definethe content filtering menu in Blueprints options... :/ Or is this meant to be in the class_arc_content_xxxx

  class arc_Panel_nextgen extends arc_Panel_Generic
  {


    function get_title(&$post)
    {

    }

    function get_image(&$post)
    {

    }

    public function get_content(&$post)
    {
      /** CONTENT */
      $this->data[ 'content' ] = '';
    }

    public function get_excerpt(&$post)
    {
      $this->data[ 'excerpt' ] = '';
    }


  }

