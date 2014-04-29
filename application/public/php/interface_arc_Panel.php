<?php
/**
 * Created by PhpStorm.
 * User: chrishoward
 * Date: 29/04/2014
 * Time: 12:16 PM
 */

  interface arc_Panel
  {
    static function render($type, $template, $source, $data);
  }

  class arc_Panel_Wrapper implements arc_Panel
  {
    static function render($type, $template, $source, $data)
    {
      foreach ($data as $key => $value)
      {
        $template = str_replace('{{' . $key . '}}', $value, $template);
      }

      // '{{bgimagetl}}<article id="post-{{postid}}" class="block-type-content post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{category-categories}} {{tag-tags}} {{pzclasses}}">{{wrapperinnards}}';
      // '</article>{{bgimagebr}}';

      return $template;
    }
  }


  class arc_Panel_Title implements arc_Panel
  {
    static function render($type, $template, $source, $data)
    {
      switch ($source)
      {
        case 'post':
        case 'page':
          $template = str_replace('{{title}}', get_the_title(), $template);
          if (true)
          {
            $template = str_replace('{{postlink}}', '<a href="' . get_the_permalink() . '">', $template);
            $template = str_replace('{{closepostlink}}', '</a>', $template);
          }
      };

      // this only works for posts! need different rules for different types! :S
      return $template;
    }
  }


  class arc_Panel_Meta implements arc_Panel
  {
    static function render($type, $template, $source, $data)
    {
      // this only works for posts! need different rules for different types! :S
      // so maybe use a switch
      return $template;
    }

  }


  class arc_Panel_Image implements arc_Panel
  {
    static function render($type, $template, $source, $data)
    {
      if (strpos($data['image'],'<img')===0) {
        preg_match_all("/width=\"(\\d)*\"/uiUm", $data['image'],$widthm);
        preg_match_all("/src=\"(.)*\"/uiUm", $data['image'],$srcm);
        preg_match_all("/alt=\"(.)*\"/uiUm", $data['image'],$altm);
        $data['width'] = str_replace(array('width=','"'),'',$widthm[0][0]);
        $data['image'] = str_replace(array('src=','"'),'',$srcm[0][0]);
        $data['alttext'] = str_replace(array('alt=','"'),'',$altm[0][0]);
      }
      // this only works for posts! need different rules for different types! :S
      foreach ($data as $key => $value)
      {
        $template = str_replace('{{' . $key . '}}', $value, $template);
      }

      return $template;
    }

  }


  class arc_Panel_Content implements arc_Panel
  {
    static function render($type, $template, $source, $data)
    {
      // this only works for posts! need different rules for different types! :S
      return $template;
    }

  }


  class arc_Panel_Excerpt implements arc_Panel
  {
    static function render($type, $template, $source, $data)
    {
      // this only works for posts! need different rules for different types! :S
      return $template;
    }

  }


  class arc_Panel_Custom implements arc_Panel
  {
    static function render($type, $template, $source, $data)
    {
      // this only works for posts! need different rules for different types! :S
      return $template;
    }

  }


