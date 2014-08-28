<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 29/04/2014
   * Time: 12:16 PM
   */
  // TODO: These should also definethe content filtering menu in Blueprints options :/

  class arc_Panel_Dummy extends arc_Panel_Renderer
  {


    public function set_data(&$post, &$toshow, &$section)
    {
      $this->section = $section;
      $this->toshow  = $toshow;
      $this->get_title($post);
//      $this->get_meta($post);
      $this->get_content($post);
      $this->get_excerpt($post);
//      $this->get_image($post);
//      $this->get_bgimage($post);
//      $this->get_custom($post);
//      $this->get_miscellanary($post);
    }

    public function get_title(&$post)
    {
      if ($this->toshow[ 'title' ][ 'show' ]) {
        $this->data[ 'title' ][ 'title' ] = 'This is a title';
      }
    }
    public function get_content(&$post)
    {
      /** CONTENT */
      if ($this->toshow[ 'content' ][ 'show' ]) {
        $this->data[ 'content' ] = apply_filters('the_content', 'This is some contwnt');
      }
    }
    public function get_excerpt(&$post)
    {
      /** Excerpt */
      if ($this->toshow[ 'excerpt' ][ 'show' ]) {
        $this->data[ 'excerpt' ] = apply_filters('the_excerpt', 'This is some excerpt');
      }
    }

  }
