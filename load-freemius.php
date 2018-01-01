<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 25/8/17
   * Time: 1:50 PM
   */

// Create a helper function for easy SDK access.
  function arc_fs() {
    global $arc_fs;

    if ( ! isset( $arc_fs ) ) {
      // Include Freemius SDK.
      require_once dirname(__FILE__) . '/freemius/start.php';

      $arc_fs = fs_dynamic_init( array(
        'id'                  => '1417',
        'slug'                => 'architect',
        'type'                => 'plugin',
        'public_key'          => 'pk_68de4ec8e507df992b179ccb796a9',
        'is_premium'          => true,
        'has_addons'          => false,
        'has_paid_plans'      => true,
        'is_org_compliant'    => false,
        'has_affiliation'     => 'all',
        'menu'                => array(
          'slug'           => 'pzarc',
          'first-path'     => 'admin.php?page=pzarc_support',
          'support'        => false,
        ),
        // Set the SDK to work in a sandbox mode (for development & testing).
        // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
        'secret_key'          => 'sk_=(R[948G%n?nHVC$S~6UPvJ;FE[b&',
      ) );
    }

    return $arc_fs;
  }

// Init Freemius.
  arc_fs();
// Signal that SDK was initiated.
  do_action( 'arc_fs_loaded' );

  require_once dirname( __FILE__ ) . '/freemius/client-migration/edd.php';
