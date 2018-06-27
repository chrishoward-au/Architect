<?php
  /**
   * Server-side rendering of the `core/latest-posts` block.
   *
   * @package gutenberg
   */

  /**
   * Renders the `core/latest-posts` block on server.
   *
   * @param array $attributes The block attributes.
   *
   * @return string Returns the post content with latest posts added.
   */
  function pizazz_render_block_architect( $attributes ) {
    $meta_query_args = ArcFun::get_blueprint_query_args($attributes['blueprint_default']);
//var_dump(get_post_meta($meta_query_args));
    // can we enqueue the scripts and styles here

    ob_start();

//TODO: Need to check blueprint is not the same one running

//pzarc( $blueprint = NULL, $overrides = NULL, $caller = NULL, $tag = NULL, $additional_overrides = NULL, $tablet_bp = NULL, $phone_bp = NULL ) ;
    if ( function_exists( 'pzarc' ) ) {
   //   var_dump( $attributes );

      pzarc( $attributes['blueprint_default'], array(
          'override_ids'      => $attributes['override_ids'],
          'override_taxonomy' => $attributes['override_taxonomy'],
          'override_terms'    => $attributes['override_terms'],
      ), 'gutenberg', NULL, NULL, $attributes['blueprint_tablet'], $attributes['blueprint_phone'] );
    } else {
      echo '<p>Architect Gutenberg block requires Architect</p>';
    }

    // echo do_shortcode('[architect blueprint="'.$attributes['blueprint_default'].'"]');


    return ob_get_clean();
  }

  register_block_type( 'cgb/block-pizazzwp-arc-guten', array(
      'attributes'      => pizazzwp_arc_guten_cgb_fields(),
      'render_callback' => 'pizazz_render_block_architect',
  ) );

