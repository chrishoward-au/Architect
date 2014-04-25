<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 22/04/2014
   * Time: 1:57 PM
   */


// section template that goes into the theme where content is displayed

  // do for each section too!

  // maybe...
    echo '<div class="pzarc-layout">';
    do_action('pzarc_section_1');
    do_action('pzarc_navigator');
    do_action('pzarc_section_2');
    do_action('pzarc_section_3');
    do_action('pzarc_pagination');
    echo '</div>';

    if (!$is_section2) {remove_action('pzarc_section_2');}
    if (!$is_section3) {remove_action('pzarc_section_3');}

  echo '<div class="pzarc-post-grid">';
  while (have_posts())
  {
    do_action('pzarc_before_panel');
    do_action('pzarc_panel','pzarc_display_panel');
    do_action('pzarc_after_panel');
  }
  echo '</div>';

  // Navigtion goes at the foot of the first section
    if ($nav_type == 'navigation')
    {
      do_action('pzarc_pagination');

    }

  // PAgination goes at the foot of all sections
  if ($nav_type == 'pagination')
  {
    if (function_exists(('wp_pagenavi')))
    {
      do_action('pzarc_pagination', 'wp_pagenavi');
    }
    else
    {
      do_action('pzarc_pagination', 'others');
    }
  }


// The comments template will be handled by the theme at this stage.