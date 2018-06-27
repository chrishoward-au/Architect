<?php

  /**
   * This file should be used to render each module instance.
   * You have access to two variables in this file:
   *
   * $module An instance of your module class.
   * $settings The module's settings.
   *
   * Example:
   */

?>
<div class="fl-example-text">
  <?php
    //var_dump($settings);
    $additional_overrides = pzarc_generate_additional_overrides( 'beaver', $settings );
   //var_dump( $settings );
    // change this to use WordPress keywords. Change all of architect like that!
    $pzarc_overrides = $settings->override_filters === 'yes' ? array(
        'post__in'         => $settings->post__in,
        'category__in'     => $settings->cat_all_any === 'any' ? $settings->category__in : NULL,
        'category__and'    => $settings->cat_all_any === 'all' ? $settings->category__in : NULL,
        'category__not_in' => $settings->category__not_in,
        'tag__in'          => $settings->tag__in,
        'tag__not_in'      => $settings->tag__not_in,
        'taxonomy'         => $settings->taxonomy,
        'terms'            => $settings->terms,
    ) : NULL;

    $pzarc_overrides['panels_per_view']=  ( empty( $settings->panels_per_view ) ? FALSE : $settings->panels_per_view );

    if ( $settings->custom_overrides ) {
      $custom_overrides = explode( "\n", str_replace( "\r", "", $settings->custom_overrides ) );
      foreach ( $custom_overrides as $override ) {
        $exploded                        = explode( '=', $override );
        $pzarc_overrides[ $exploded[0] ] = str_replace( '"', '', $exploded[1] );
      }
    }

    // pzarchitect($pzarc_blueprint = null, $pzarc_overrides = null, $tablet_bp = null, $phone_bp = null)
    pzarc( $settings->blueprint_default, $pzarc_overrides, 'beaver_module_basic', null,$additional_overrides,$settings->blueprint_tablet, $settings->blueprint_phone );
  ?>
</div>