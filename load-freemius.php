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
				'id'                  => '1305',
				'slug'                => 'pzarchitect',
				'type'                => 'plugin',
				'public_key'          => 'pk_5500130c6efcbdbb28d9754141319',
				'is_premium'          => true,
				// If your plugin is a serviceware, set this option to false.
				'has_premium_version' => true,
				'has_addons'          => false,
				'has_paid_plans'      => true,
				'trial'               => array(
					'days'               => 14,
					'is_require_payment' => true,
				),
				'menu'                => array(
					'slug'           => 'pzarc',
					'first-path'     => 'admin.php?page=pzarc_support',
					'support'        => false,
				),
				// Set the SDK to work in a sandbox mode (for development & testing).
				// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
				'secret_key'          => 'sk_xLrX{Z;#wd}Z1eys=:ZU;t;DDO)qc',
			) );
		}

		return $arc_fs;
	}


// Init Freemius.
	arc_fs();
// Signal that SDK was initiated.
	do_action( 'arc_fs_loaded' );

	require_once dirname( __FILE__ ) . '/freemius/client-migration/edd.php';
