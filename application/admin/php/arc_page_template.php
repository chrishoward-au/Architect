<?php
/**
 * Project pizazzwp-architect.
 * File: arc_page_template.php
 * User: chrishoward
 * Date: 19/03/15
 * Time: 6:45 PM
 */

  require_once( PZARC_PLUGIN_APP_PATH . 'public/php/class_arcBuilder.php' );
  $pzarc_builder = new arcBuilder;

  get_header();

?>

<div id="primary" class="content-area architect-builder">
  <main id="main" class="site-main" role="main">

    <?php
      $pzarc_builder->build(null);
//      do_action('arc_page_template');
    ?>
  </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>


