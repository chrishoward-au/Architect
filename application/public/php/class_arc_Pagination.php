<?php

  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 25/04/2014
   * Time: 9:11 PM
   */
  class arc_Pagination
  {

    function __construct()
    {
    }

    /**
     * @param $the_query
     * @param $location_class
     */
    function render($the_query, $location_class) { }
  }

  class arc_Pagination_names extends arc_Pagination
  {

    function render($the_query, $location_class)
    {
      if (is_home()|| is_archive()) {
        if ($the_query->max_num_pages > 1) : ?>
          <nav id="<?php echo $location_class; ?>" class="navigation page-nav clearfix" role="navigation">
            <div
                class="nav-previous alignleft"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'pzarchitect')); ?></div>
            <div
                class="nav-next alignright"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'pzarchitect')); ?></div>
          </nav>
        <?php
        endif;
      } elseif (is_single()) {
        ?>
        <nav id="<?php echo $location_class; ?>" class="navigation nav-single clearfix">
          <span
              class="nav-previous alignleft"><?php previous_post_link('%link', '<span class="meta-nav">' . _x('&larr;', 'Previous post link', 'pzarchitect') . '</span> %title'); ?></span>
          <span
              class="nav-next alignright"><?php next_post_link('%link', '%title <span class="meta-nav">' . _x('&rarr;', 'Next post link', 'pzarchitect') . '</span>'); ?></span>
        </nav><!-- .nav-single -->
      <?php
      }

    }


  }

  class arc_Pagination_prevnext extends arc_Pagination
  {

    function render($the_query, $location_class)
    {

      if (is_home() || is_archive()) {
        if ($the_query->max_num_pages > 1) : ?>
          <nav id="<?php echo $location_class; ?>" class="navigation page-nav clearfix" role="navigation">
            <div
                class="nav-previous alignleft"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'pzarchitect')); ?></div>
            <div
                class="nav-next alignright"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'pzarchitect')); ?></div>
          </nav>
        <?php
        endif;
      } elseif (is_single()) {
        ?>
        <nav id="<?php echo $location_class; ?>" class="navigation nav-single clearfix">
          <span
              class="nav-previous alignleft"><?php previous_post_link('%link', '<span class="meta-nav">' . _x('&larr;', 'Previous post link', 'pzarchitect') . '</span> Previous post'); ?></span>
          <span
              class="nav-next alignright"><?php next_post_link('%link', 'Next post <span class="meta-nav">' . _x('&rarr;', 'Next post link', 'pzarchitect') . '</span>'); ?></span>
        </nav><!-- .nav-single -->
      <?php
      }

    }


  }

  class arc_Pagination_pagenavi extends arc_Pagination
  {
    function render($the_query, $location_class)
    {
      //     var_dump(is_main_query(),have_posts());
      if (is_home() || is_archive()) {
        if ($the_query->max_num_pages > 1) : ?>
          <nav id="<?php echo $location_class; ?>" class="navigation page-nav clearfix" role="navigation">
          
          <?php
          if (function_exists('wp_pagenavi')) {
            echo '<div id="'.$location_class.'" class="navigation clearfix">';
            wp_pagenavi(array('query' => $the_query));
          } else {
            echo 'PageNavi not present';
          }
          echo '</div><!-- end nav-below navigation  -->';
          ?>

          </nav>
        <?php
        endif;
      } elseif (is_single()) {
        ?>
        <nav id="<?php echo $location_class; ?>" class="navigation nav-single clearfix">
          <?php
          if (function_exists('wp_pagenavi')) {
            echo '<div id="'.$location_class.'" class="navigation clearfix">';
            wp_pagenavi(array('query' => $the_query));
          } else {
            echo 'PageNavi not present';
          }
          echo '</div><!-- end nav-below navigation  -->';
          ?>
        </nav><!-- .nav-single -->
      <?php
      }

    }

  }