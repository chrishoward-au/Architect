<?php
  $hw_opts = get_option('headway_option_group_general');
  var_dump($hw_opts['license-status-architect']);


// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
  define( 'EDD_ARCHITECT_STORE_URL', 'http://shop.pizazzwp.com' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in EDD exactly
  define( 'EDD_ARCHITECT_ITEM_NAME', 'Architect' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
  if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    // load our custom updater
    include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
  }

  function edd_sl_architect_plugin_updater() {

    // retrieve our license key from the DB
    $license_key = trim( get_option( 'edd_architect_license_key' ) );
//  global $_architect_options;
//  // Just incase that didn't work... A problem from days of past
//  if ( ! isset( $GLOBALS[ '_architect_options' ] ) ) {
//    $GLOBALS[ '_architect_options' ] = get_option( '_architect_options', array() );
//  }
//  if ( ! empty( $_architect_options[ 'architect_licence_key' ] ) ) {}
    // setup the updater
    $edd_updater = new EDD_SL_Plugin_Updater( EDD_ARCHITECT_STORE_URL, PZARC_PLUGIN_PATH.'architect.php', array(
                                                                       'version'   => PZARC_VERSION,
                                                                       // current version number
                                                                       'license'   => $license_key,
                                                                       // license key (used get_option above to retrieve from DB)
                                                                       'item_name' => EDD_ARCHITECT_ITEM_NAME,
                                                                       // name of this plugin
                                                                       'author'    => 'Chris Howard'
                                                                       // author of this plugin
                                                                     )
    );
  }

  add_action( 'admin_init', 'edd_sl_architect_plugin_updater', 0 );


  /************************************
   * the code below is just a standard
   * options page. Substitute with
   * your own.
   *************************************/

  function edd_architect_licence_menu() {
    $hw_opts = get_option('headway_option_group_general');
var_dump($hw_opts['license-status-architect']);
    die();
    if ( $hw_opts['license-status-architect']!='valid' ) {
      add_submenu_page( 'pzarc', __( 'Licence', 'pzarchitect' ), '<span class="dashicons dashicons-admin-network size-small"></span>' . __( 'Licence', 'pzarchitect' ), 'manage_options', 'architect-licence', 'edd_architect_licence_page' );
    }
  }
  add_action( 'admin_menu', 'edd_architect_licence_menu' );


  function edd_architect_licence_page() {
    $license = get_option( 'edd_architect_license_key' );
    $status  = get_option( 'edd_architect_license_status' );
    $edd_state = get_option('edd_architect_license_state');
    ?>
    <div class="wrap">
    <h2><?php _e( 'Architect Licence Options', 'pzarchitect' ); ?></h2>
    <p>Note: This page is for licences purchases from the PizazzWP shop. For licences purchased from the Headway Extend store, enter those in the <em>Headway</em> > <em>Options</em> screen</p>
<?php
    if ( $status !== false && $status == 'valid' ) {
      echo 'Until you activate a valid licence, Architect will be the Lite version only. This is limited to just the Default, Dummy and Post content types and Animations are not available.';
    }
?>
    <form method="post" action="options.php">

      <?php settings_fields( 'edd_architect_license' ); ?>

      <table class="form-table">
        <tbody>
        <tr valign="top">
          <th scope="row" valign="top">
            <?php _e( 'License Key' ); ?>
          </th>
          <td>
            <input id="edd_architect_license_key" name="edd_architect_license_key" type="text" class="regular-text"
                   value="<?php esc_attr_e( $license ); ?>"/>
            <label class="description" for="edd_architect_license_key"><?php _e( 'Enter your license key' ); ?></label>
          </td>
        </tr>
        <?php if ( false !== $license ) { ?>
          <tr valign="top">
            <th scope="row" valign="top">
              <?php _e( 'Activate License' ); ?>
            </th>
            <td>
              <?php if ( $status !== false && $status == 'valid' ) { ?>
                <?php wp_nonce_field( 'edd_architect_nonce', 'edd_architect_nonce' ); ?>
                <input type="submit" class="button-secondary" name="edd_license_deactivate"
                       value="<?php _e( 'Deactivate License' ); ?>"/>
                <span style="color:green;">&nbsp;<?php _e( 'Active' ); ?></span>
              <?php } else {
                wp_nonce_field( 'edd_architect_nonce', 'edd_architect_nonce' ); ?>
                <input type="submit" class="button-secondary" name="edd_license_activate"
                       value="<?php _e( 'Activate License' ); ?>"/>
              <?php }
                echo "<p>".$edd_state."</p>";
              ?>

            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
      <?php submit_button(); ?>

    </form>
  <?php
  }

  function edd_architect_register_option() {
    // creates our settings in the options table
    register_setting( 'edd_architect_license', 'edd_architect_license_key', 'edd_sanitize_license' );
  }

  add_action( 'admin_init', 'edd_architect_register_option' );

  function edd_sanitize_license( $new ) {
    $old = get_option( 'edd_architect_license_key' );
    if ( $old && $old != $new ) {
      delete_option( 'edd_architect_license_status' ); // new license has been entered, so must reactivate
    }

    return $new;
  }


  /************************************
   * this illustrates how to activate
   * a license key
   *************************************/

  function edd_architect_activate_license() {

    // listen for our activate button to be clicked
    if ( isset( $_POST[ 'edd_license_activate' ] ) ) {

      // run a quick security check
      if ( ! check_admin_referer( 'edd_architect_nonce', 'edd_architect_nonce' ) ) {
        return;
      } // get out if we didn't click the Activate button

      // retrieve the license from the database
      $license = trim( get_option( 'edd_architect_license_key' ) );


      // data to send in our API request
      $api_params = array(
        'edd_action' => 'activate_license',
        'license'    => $license,
        'item_name'  => urlencode( EDD_ARCHITECT_ITEM_NAME ), // the name of our product in EDD
        'url'        => home_url()
      );

      // Call the custom API.
      $response = wp_remote_get( add_query_arg( $api_params, EDD_ARCHITECT_STORE_URL ), array( 'timeout'   => 15,
                                                                                               'sslverify' => false
      ) );

      // make sure the response came back okay
      if ( is_wp_error( $response ) ) {
        return false;
      }

      // decode the license data
      $license_data = json_decode( wp_remote_retrieve_body( $response ) );

      // $license_data->license will be either "valid" or "invalid"

      update_option( 'edd_architect_license_status', $license_data->license );

      if ($license_data->success) {
        update_option('edd_architect_license_state', "Remaining activations: ".$license_data->activations_left);
      } else {
        update_option('edd_architect_license_state',"Activation failed with message: ".ucfirst(str_replace('_', ' ', $license_data->error)).'. Licence limit: '.$license_data->license_limit);
      }

    }
  }

  add_action( 'admin_init', 'edd_architect_activate_license' );


  /***********************************************
   * Illustrates how to deactivate a license key.
   * This will decrease the site count
   ***********************************************/

  function edd_architect_deactivate_license() {

    // listen for our activate button to be clicked
    if ( isset( $_POST[ 'edd_license_deactivate' ] ) ) {

      // run a quick security check
      if ( ! check_admin_referer( 'edd_architect_nonce', 'edd_architect_nonce' ) ) {
        return;
      } // get out if we didn't click the Activate button

      // retrieve the license from the database
      $license = trim( get_option( 'edd_architect_license_key' ) );


      // data to send in our API request
      $api_params = array(
        'edd_action' => 'deactivate_license',
        'license'    => $license,
        'item_name'  => urlencode( EDD_ARCHITECT_ITEM_NAME ), // the name of our product in EDD
        'url'        => home_url()
      );

      // Call the custom API.
      $response = wp_remote_get( add_query_arg( $api_params, EDD_ARCHITECT_STORE_URL ), array( 'timeout'   => 15,
                                                                                               'sslverify' => false
      ) );

      // make sure the response came back okay
      if ( is_wp_error( $response ) ) {
        return false;
      }

      // decode the license data
      $license_data = json_decode( wp_remote_retrieve_body( $response ) );

      // $license_data->license will be either "deactivated" or "failed"
      if ( $license_data->license == 'deactivated' ) {
        delete_option( 'edd_architect_license_status' );
      }

    }
  }

  add_action( 'admin_init', 'edd_architect_deactivate_license' );


  /************************************
   * this illustrates how to check if
   * a license key is still valid
   * the updater does this for you,
   * so this is only needed if you
   * want to do something custom
   *************************************/

  function edd_architect_check_license() {

    global $wp_version;

    $license = trim( get_option( 'edd_architect_license_key' ) );

    $api_params = array(
      'edd_action' => 'check_license',
      'license'    => $license,
      'item_name'  => urlencode( EDD_ARCHITECT_ITEM_NAME ),
      'url'        => home_url()
    );

    // Call the custom API.
    $response = wp_remote_get( add_query_arg( $api_params, EDD_ARCHITECT_STORE_URL ), array( 'timeout'   => 15,
                                                                                             'sslverify' => false
    ) );

    if ( is_wp_error( $response ) ) {
      return false;
    }

    $license_data = json_decode( wp_remote_retrieve_body( $response ) );

    if ( $license_data->license == 'valid' ) {
      echo 'valid';
      exit;
      // this license is still valid
    } else {
      echo 'invalid';
      exit;
      // this license is no longer valid
    }
  }
