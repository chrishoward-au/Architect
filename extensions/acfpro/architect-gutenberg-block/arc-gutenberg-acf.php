<?php
  /**
   * Name: arc-gutenberg-acf.php
   * Author: chrishoward
   * Date: 2/12/19
   * Purpose:
   */


  add_action( 'acf/init', 'pzarc_acf_init' );
  function pzarc_acf_init() {

    // Load the Architect Gutenberg block fields
    include_once 'arc-acf-fields.php';

    // check function exists
    if ( function_exists( 'acf_register_block' ) ) {

      // register a Architect block
      acf_register_block( array(
          'name'            => 'pzarchitect',
          'title'           => 'Architect',
          'description'     => 'Architect Gutenberg' . __( 'block', 'pzarchitect' ),
          //          'render_callback' => 'pzarc_acf_block_render_callback',
          'category'        => 'layout',
          'supports'        => array( 'align' => FALSE, 'html' => FALSE, 'edit' => TRUE ),
          'render_template' => trailingslashit( plugin_dir_path( __FILE__ ) ) . "/template-parts/content-architect.php",
          'icon'            => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32" enable-background="new 0 0 32 32" xml:space="preserve">  <image id="image0" width="32" height="32" x="0" y="0"
    href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAABGdBTUEAALGPC/xhBQAAACBjSFJN
AAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAACwVBMVEX////W1tbExMTGyMjF
x8fKysr9/f1TU1MDAwMCAgECAgIgICD19fVQUFAAAAABAgMcHBxRUVEdHR0BAQIAAQEBAAABAQEB
AwRPTkxNSEQBAwMAAAEcHBsZExFQT0319PTz7u3a2trIyMjIx8fQ09QuVl0pTVRWprVRnKsfO0Fn
xdcaMTZLj51GhZIWKy9mwtVkv9ESIiZIiJZCfoozXWU3ZW42Y2w3Zm8xWmIJGiIHJiwGIScGIigJ
HSIDBgk4cas4ma0ECw0XLkcbR1EzYpg3kaQCBQYQHzBCgMYWO0MrU4AwXY8zh5g0iZsJEh1Ae705
bqoEBw0JGBo+ordDsMcSMTcjQ2k/erwNGigVOD9DsMcvfY0ECA07ca9DgsgWLEMiWmZCrcQPJy0b
NFEiQWUvfIwrcoE1Zp4sVoQGEBI6ma1Aqr8LHSETJTlDgsg2aKEECA0QKjBBq8ImZXIuWYk9d7cK
FB4cS1Q/pboIFBcMFiNBf8NCgMYUJjspbXwiWmYnS3MeOVkDCAk1jZ88nbEEDA0AAgdBfsEqUn4L
HCA+pboeT1oTM1Y3a6QCBAcWO0NFts45lqoDBwdUV1skVIkKFB4kXmoZQ0xofZUIGCwzhpc4lKda
YmsHExU/pbsQMzsUNTwsfpAcHBsYTVgwUVgAAQI1W2P29/f+//9ox9poxtlkvtBkv9Fv1OhpyNxv
1ekvlrgml6wolq0nlawtnrZFg8s9f7wnjqclkKUmj6Unkac8pbtGts1DgsdCe8E4grclj6Q4obdC
q8FCr8ZAfcBAfL9Be8A0irg0ordCq8BAqb9EsspEg8pEgspDsshDsMdBfsFDgshCrsVAqsBEhcxC
f8RBq8FDsslBfcFEtcxEhMxEs8tAqb5CgcZDr8ZBqsFDgslBrMJEtMtFhs9BrMNEtMxIjNhEtc1G
uNBIv9j///+nLATKAAAAqXRSTlMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
V07Rwzz9MKyeKPn0HaKTZ3Z1dmEiMi4uIwTS1wZJVrbJAS37RZaisbkX78wFE+H+NXXpIz36qAfb
+0Ru+SdUbaOWwZgM0fIbN/7ABivwg6LgGVfpER729jiMcYReBL7dCQ30kBzlXnHCAUL+0gMNpBp2
TjUys9AKEd9IPKkCXjkCMAIBCyY7BgAAAAFiS0dEAIgFHUgAAAAHdElNRQfjDAERJw921i9bAAAB
nklEQVQ4y2NgYGBgZIIDZhYmJMDKxgAG7BwwwKmqxsWBANw8EAW8fDDAr67BhwQEoAoE4SKaK7W0
kRQIYSjQWbVSF58CPf3VawwMcSsQNlq7bt16YxGcCjhNTM3MzC1EcSqwtLK2sbG2ssWlwM5+w8ZN
mzZvcXDEocBp67btO3bu2r3HGbsCEZe9+/YfOHDw0GFXN6wK3D2OHD12/MTJU6dOe2JV4HXm6NGz
3j7nTp067yuGRYGf/4WjFwMCg4IvnbocEopFQdiVo1fDI/j4IqOunboejakgJvbqjbh4kGjCzVOX
E5MwFCTfOnorBRLgqbfv3E5DVyCefvdKBtTtmVn37mfnoCnIzXuQXwALn8Kih4+K0RSUPC4tQ0Rz
+e0nFZUoCqqqa2qR01rd00f1KAoanjUiy/M1NT9vaUUokGhr7+BDBZ1dL7oRCiR7evvQFPD1T5g4
Ca5g8pSpfBhg2svpcAUzZmLK80nNmi0tA1Uwhw8bmDtvvixUgRxWBXwLFi6SR896qGDxEgX0nIUK
li5bDlGgqIQdKKusYGAAAEoXnnaCFAskAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE5LTEyLTAyVDAw
OjM5OjE1LTA3OjAw8oq8jQAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOS0xMi0wMlQwMDozOToxNS0w
NzowMIPXBDEAAAAZdEVYdFNvZnR3YXJlAEFkb2JlIEltYWdlUmVhZHlxyWU8AAAAAElFTkSuQmCC" />
</svg>',
          'keywords'        => array( 'architect' ),
      ) );
    }
  }

  function pzarc_acf_block_render_callback( $block ) {

    // convert name ("acf/testimonial") into path friendly slug ("testimonial")
    $slug = str_replace( 'acf/', '', $block['name'] );
    // include a template part from within the "template-parts/block" folder
    if ( file_exists( trailingslashit( plugin_dir_path( __FILE__ ) ) . "/template-parts/content-{$slug}.php" ) ) {
      include( trailingslashit( plugin_dir_path( __FILE__ ) ) . "/template-parts/content-{$slug}.php" );
    }

  }

  // }

  function pzarc_render_block( $block ) {

    $arc_default_raw                       = get_post_meta( get_field( 'default_blueprint' ) );
    $arc_blueprint['default']['shortname'] = $arc_default_raw['_blueprints_short-name'][0];
    $arc_blueprint['default']['type']      = empty( $arc_default_raw['_blueprints_section-0-layout-mode'][0] ) || $arc_default_raw['_blueprints_section-0-layout-mode'][0] == 'basic' ? 'basic' : $arc_default_raw['_blueprints_section-0-layout-mode'][0];
    $arc_blueprint['default']['source']    = empty( $arc_default_raw['_blueprints_content-source'][0] ) || $arc_default_raw['_blueprints_content-source'][0] == 'default' ? 'Default' : $arc_default_raw['_blueprints_content-source'][0];
    $arc_blueprint['default']['source']    = $arc_blueprint['default']['source'] == 'cpt' ? $arc_default_raw['_content_cpt_custom-post-type'][0] : $arc_blueprint['default']['source'];
    $arc_blueprint['default']['title']     = ! empty( get_field( 'default_blueprint' ) ) ? get_the_title( get_field( 'default_blueprint' ) ) : '';

    if ( ! empty( get_field( 'tablet_blueprint' ) ) ) {
      $arc_tablet_raw                       = get_post_meta( get_field( 'tablet_blueprint' ) );
      $arc_blueprint['tablet']['shortname'] = $arc_tablet_raw['_blueprints_short-name'][0];
      $arc_blueprint['tablet']['type']      = empty( $arc_tablet_raw['_blueprints_section-0-layout-mode'][0] ) || $arc_tablet_raw['_blueprints_section-0-layout-mode'][0] == 'basic' ? 'basic' : $arc_tablet_raw['_blueprints_section-0-layout-mode'][0];
      $arc_blueprint['tablet']['source']    = empty( $arc_tablet_raw['_blueprints_content-source'][0] ) || $arc_tablet_raw['_blueprints_content-source'][0] == 'default' ? 'Default' : $arc_tablet_raw['_blueprints_content-source'][0];
      $arc_blueprint['tablet']['source']    = $arc_blueprint['tablet']['source'] == 'cpt' ? $arc_tablet_raw['_content_cpt_custom-post-type'][0] : $arc_blueprint['tablet']['source'];
      $arc_blueprint['tablet']['title']     = ! empty( get_field( 'tablet_blueprint' ) ) ? get_the_title( get_field( 'tablet_blueprint' ) ) : '';
    }

    if ( ! empty( get_field( 'phone_blueprint' ) ) ) {
      $arc_phone_raw                       = get_post_meta( get_field( 'phone_blueprint' ) );
      $arc_blueprint['phone']['shortname'] = $arc_phone_raw['_blueprints_short-name'][0];
      $arc_blueprint['phone']['type']      = empty( $arc_phone_raw['_blueprints_section-0-layout-mode'][0] ) || $arc_phone_raw['_blueprints_section-0-layout-mode'][0] == 'basic' ? 'basic' : $arc_phone_raw['_blueprints_section-0-layout-mode'][0];
      $arc_blueprint['phone']['source']    = empty( $arc_phone_raw['_blueprints_content-source'][0] ) || $arc_phone_raw['_blueprints_content-source'][0] == 'default' ? 'Default' : $arc_phone_raw['_blueprints_content-source'][0];
      $arc_blueprint['phone']['source']    = $arc_blueprint['phone']['source'] == 'cpt' ? $arc_phone_raw['_content_cpt_custom-post-type'][0] : $arc_blueprint['phone']['source'];
      $arc_blueprint['phone']['title']     = ! empty( get_field( 'phone_blueprint' ) ) ? get_the_title( get_field( 'phone_blueprint' ) ) : '';
    }

    // We don't want tablet and phone to use 'none'
    $arc_blueprint['tablet'] = empty( $arc_blueprint['tablet'] ) || $arc_blueprint['tablet'] === 'none' ? NULL : $arc_blueprint['tablet'];
    $arc_blueprint['phone']  = empty( $arc_blueprint['phone'] ) || $arc_blueprint['phone'] === 'none' ? NULL : $arc_blueprint['phone'];

    $arc_blueprint['extra_overrides']['_blueprints_blueprint-title'] = ! empty( get_field( 'custom_blueprint_title' ) ) ? get_field( 'custom_blueprint_title' ) : NULL;

    $arc_blueprint['overrides']['ids']              = get_field( 'specific_ids' );
    $arc_blueprint['overrides']['tax']              = get_field( 'taxonomy' );
    $arc_blueprint['overrides']['terms']            = get_field( 'terms' );
    $arc_blueprint['overrides']['post__in']         = get_field( 'specific_ids' );
    $arc_blueprint['overrides']['category__in']     = get_field( 'category_any_all' ) === 'any' ? get_field( 'category__in' ) : NULL;
    $arc_blueprint['overrides']['category__and']    = get_field( 'category_any_all' ) === 'all' ? get_field( 'category__in' ) : NULL;
    $arc_blueprint['overrides']['category__not_in'] = get_field( 'category__not_in' );
    $arc_blueprint['overrides']['tag__in']          = get_field( 'tag_any_all' ) === 'any' ? get_field( 'tag__in' ) : NULL;
    $arc_blueprint['overrides']['tag__and']         = get_field( 'tag_any_all' ) === 'all' ? get_field( 'tag__in' ) : NULL;
    $arc_blueprint['overrides']['tag__not_in']      = get_field( 'tag__not_in' );

    $arc_blueprint['overrides']['panels_per_view'] = ( empty( get_field( 'number_of_posts_to_show' ) ) ? FALSE : get_field( 'number_of_posts_to_show' ) );

    foreach ( $arc_blueprint['overrides'] as $k => $v ) {
      if ( empty( $v ) ) {
        unset( $arc_blueprint['overrides'][ $k ] );
      }
    }
    if ( get_field( 'custom_overrides' ) ) {
      $custom_overrides = explode( "\n", str_replace( "\r", "", get_field( 'custom_overrides' ) ) );
      foreach ( $custom_overrides as $override ) {
        $exploded                                   = explode( '=', $override );
        $arc_blueprint['overrides'][ $exploded[0] ] = str_replace( '"', '', $exploded[1] );
      }
    }
    unset( $arc_default_raw, $arc_tablet_raw, $arc_phone_raw );

    return $arc_blueprint;

  }


  function pzarc_post_object_result( $title, $post, $field, $post_id ) {
//    var_Dump(maybe_unserialize(get_the_excerpt(get_field('default_blueprint'))));
    // ArcFun::save_debug('excerpt_data', maybe_unserialize( get_the_excerpt( $field['value'] ) ) );

    switch ( $field['_name'] ) {
      case 'default_blueprint':
      case 'tablet_blueprint':
      case 'phone_blueprint':
        // Get the Blueprint's info
        $pzarc_data                = maybe_unserialize( $post->post_excerpt );
        $pzarc_data['layout_type'] = ! isset( $pzarc_data['layout_type'] ) || $pzarc_data['layout_type'] == 'basic' ? 'grid' : $pzarc_data['layout_type'];;
        if ( $pzarc_data['source'] == 'cpt' ) {
          $pzarc_data['source'] = get_post_meta( $post->ID, '_content_cpt_custom-post-type', TRUE );
          $pzarc_data['source'] = empty( $pzarc_data['source'] ) ? 'No CPT selected' : $pzarc_data['source'];
        }
        $title .= ' (' . ucfirst( $pzarc_data['source'] ) . ' : ' . ucfirst( $pzarc_data['layout_type'] ) . ')';
        break;
      case 'specific_ids':
        $title = strtoupper( $post->post_type ) . ' : ' . $title;
        break;
    }

    return $title;

  }

  add_filter( 'acf/fields/post_object/result/name=default_blueprint', 'pzarc_post_object_result', 10, 4 );
  add_filter( 'acf/fields/post_object/result/name=tablet_blueprint', 'pzarc_post_object_result', 10, 4 );
  add_filter( 'acf/fields/post_object/result/name=phone_blueprint', 'pzarc_post_object_result', 10, 4 );
  add_filter( 'acf/fields/post_object/result/name=specific_ids', 'pzarc_post_object_result', 10, 4 );

  function pzarc_post_object_query( $args, $field, $post_id ) {


    switch ( $field['_name'] ) {
      case 'default_blueprint':
      case 'tablet_blueprint':
      case 'phone_blueprint':
        // only show published Blueprints
        $args['post_status'] = 'publish';
        break;
      case 'specific_ids':
        $arc_blueprint_data1 =  get_field( $field['value'] ) ;
        $arc_blueprint_data2 =  get_field( 'default_blueprint' ) ;
        $arc_blueprint_data3 = get_the_excerpt( $post_id ) ;

        ArcFun::save_debug( '$arc_blueprint_data1', $arc_blueprint_data1 );
        ArcFun::save_debug( '$arc_blueprint_data2', $arc_blueprint_data2 );
        ArcFun::save_debug( '$arc_blueprint_data3', $arc_blueprint_data3 );
        //doesn't work!! :(
        // Of course not! Specific IDs are all posts, so each one is not going to have the necessary data
        //$args['post_type'] = $arc_blueprint_data['source'];

        break;
    }

    // return
    return $args;

  }

  add_filter( 'acf/fields/post_object/query/name=default_blueprint', 'pzarc_post_object_query', 10, 3 );
  add_filter( 'acf/fields/post_object/query/name=tablet_blueprint', 'pzarc_post_object_query', 10, 3 );
  add_filter( 'acf/fields/post_object/query/name=phone_blueprint', 'pzarc_post_object_query', 10, 3 );
  add_filter( 'acf/fields/post_object/query/name=specific_ids', 'pzarc_post_object_query', 10, 3 );

  function my_taxonomy_query( $args, $field, $post_id ) {
    $args['taxonomy'] = get_field( 'taxonomies' );
    ArcFun::save_debug( '$args', $args);
    ArcFun::save_debug( '$field', $field );
    ArcFun::save_debug( '$post_id', $post_id );

    return $args;
  }

  add_filter( 'acf/fields/taxonomy/query/name=terms_select', 'my_taxonomy_query', 10, 3 );
