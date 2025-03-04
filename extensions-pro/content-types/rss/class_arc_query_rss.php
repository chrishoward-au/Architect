<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_query_rss.php
   * User: chrishoward
   * Date: 20/10/14
   * Time: 9:13 PM
   */
  class arc_query_rss extends arc_query_generic {


    function get_custom_query( $overrides = NULL ) {
      //  add_filter( 'wp_feed_cache_transient_lifetime' , 'return_10' );
      $prefix = '_content_rss_';

      $settings = array(
          'rss-feed-url'          => ! empty( $this->build->blueprint[ $prefix . 'rss-feed-url' ] ) ? $this->build->blueprint[ $prefix . 'rss-feed-url' ] : '',
          'rss-feed-count'        => ! empty( $this->build->blueprint[ $prefix . 'rss-feed-count' ] ) ? $this->build->blueprint[ $prefix . 'rss-feed-count' ] : 10,
          'rss-feed-exclude-tags' => ! empty( $this->build->blueprint[ $prefix . 'rss-feed-exclude-tags' ] ) ? $this->build->blueprint[ $prefix . 'rss-feed-exclude-tags' ] : '',
          'rss-feed-hide-content' => ( empty( $this->build->blueprint[ $prefix . 'rss-feed-hide-content' ] ) || $this->build->blueprint[ $prefix . 'rss-feed-hide-content' ] == 'no' ) ? FALSE : TRUE,
          'rss-feed-hide-title'   => ( empty( $this->build->blueprint[ $prefix . 'rss-feed-hide-title' ] ) || $this->build->blueprint[ $prefix . 'rss-feed-hide-title' ] ) ? FALSE : TRUE,
          'rss-feed-date-format'  => ! empty( $this->build->blueprint[ $prefix . 'rss-feed-date-format' ] ) ? $this->build->blueprint[ $prefix . 'rss-feed-date-format' ] : 'j F Y | g:i a',
      );

      $settings['rss-feed-url']          = isset( $overrides['rssurl'] ) ? $overrides['rssurl'] : $settings['rss-feed-url'];
      $settings['rss-feed-exclude-tags'] = isset( $overrides['rssexclude'] ) ? $overrides['rssexclude'] : $settings['rss-feed-exclude-tags'];
      $settings['rss-feed-hide-title']   = isset( $overrides['rsshidetitle'] ) ? isset( $overrides['rsshidetitle'] ) : $settings['rss-feed-hide-title'];

      $rss_feed = fetch_feed( wp_specialchars_decode( $settings['rss-feed-url'] ) );
      //   remove_filter( 'wp_feed_cache_transient_lifetime' , 'return_10' );
      $rss_items = array();

      // Checks that the object is created correctly
      // Figure out how many total items there are, but limit it to 5.
      $maxitems  = 0;
      $rss_array = array();
      if ( ! is_wp_error( $rss_feed ) ) {
        $maxitems = $rss_feed->get_item_quantity( $settings['rss-feed-count'] );
        // Build an array of all the items, starting with element 0 (first element).
//        $rss_items = $rss_feed->get_items( 0, $maxitems );
        $exclude = explode( ',', $settings['rss-feed-exclude-tags'] );
        $inc     = 0;
        $i       = 1;
        foreach ( $rss_feed->get_items() as $aitem ) {

          $ok = TRUE;
          foreach ( $aitem->get_categories() as $ocat ) {
            if ( in_array( $ocat->term, $exclude ) ) {
              $ok = FALSE;
              break;
            }
          }
          if ( $ok ) {
            $thumb       = ( $enclosure = $aitem->get_enclosure() ) ? $enclosure->get_thumbnail() : '';
            $rss_array[] = array(
                'id'        => $inc ++,
                'title'     => $settings['rss-feed-hide-title'] ? '' : $aitem->get_title(),
                'permalink' => $aitem->get_permalink(),
                'date'      => $aitem->get_date( $settings['rss-feed-date-format'] ),
                'tags'      => $aitem->get_categories(),
                'content'   => $settings['rss-feed-hide-content'] ? '' : $aitem->get_content(),
                'image'     => $thumb,
            );
            if ( ++ $i > $maxitems ) {
              break;
            }
          }

        }
      }

      return $rss_array;
    }
  }