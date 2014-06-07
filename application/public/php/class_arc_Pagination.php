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

    function render()    {    }
  }

  class arc_Pagination_names extends arc_Pagination
  {

    function render()
    {
      //TODO: Make this names
      // Arg!! have_posts() is the evil screwing the pointer!
//      var_dump(is_main_query(),have_posts());
      ?>
      <div class="nav-previous alignleft">Name 1<?php next_posts_link('Older posts'); ?></div>
      <div class="nav-next alignright"><?php previous_posts_link('Newer posts'); ?>Name 2</div>

    <?php
    }


  }

  class arc_Pagination_prevnext extends arc_Pagination
  {

    function render()
    {
      global $wp_query;


      if ( $wp_query->max_num_pages > 1 ) : ?>
        <nav id="<?php echo 'page-nav nav-below'; ?>" class="navigation" role="navigation">
          <h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
          <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve' ) ); ?></div>
          <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div>
        </nav>
      <?php endif;

      // Arg!! have_posts() is the evil screwing the pointer!
//      var_dump(is_main_query(),have_posts());
      ?>
      <div class="nav-previous alignleft"><<<?php next_posts_link('Older posts'); ?></div>
      <div class="nav-next alignright"><?php previous_posts_link('Newer posts'); ?>>></div>

    <?php
    }


  }

  class arc_Pagination_pagenavi extends arc_Pagination
  {
    function render()
    {
 //     var_dump(is_main_query(),have_posts());
      if (function_exists('wp_pagenavi'))
      {

        echo '<div class="nav-below navigation">';
        wp_pagenavi();
        echo '</div><!-- end nav-below navigation  -->';
      }
    }

  }
