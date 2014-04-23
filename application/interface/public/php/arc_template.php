<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 22/04/2014
   * Time: 1:57 PM
   */


// section template that goes into the theme where content is displayed

  echo '<div class="pzarc-post-grid">';
  while (have_posts())
  {
    do_action('pzarc_before_panel');
    do_action('pzarc_panel','pzarc_display_panel');
    do_action('pzarc_after_panel');
  }
  echo '</div>';

  if ($nav_type == 'pagination')
  {
    if (function_exists(('wp_pagenavi')))
    {
      do_action('pzarc_navigator', 'wp_pagenavi');
    }
    else
    {
      do_action('pzarc_navigator', 'pzarc_navigator');
    }
  }
  if ($nav_type == 'pagination')
  {
    do_action('pzarc_pagination');

  }

// The comments template will be handled by the theme at this stage.