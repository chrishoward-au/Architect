<?php
/**
 * Project pizazzwp-architect.
 * File: arc_page_template.php
 * User: chrishoward
 * Date: 19/03/15
 * Time: 6:45 PM
 */


  get_header(); ?>

<div id="primary" class="content-area architect-builder">
  <main id="main" class="site-main" role="main">

    <?php
      do_action('pzarc_page_template');
    ?>
  </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>


