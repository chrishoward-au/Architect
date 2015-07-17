<?php


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
    $edd_updater = new EDD_SL_Plugin_Updater( EDD_ARCHITECT_STORE_URL, PZARC_PLUGIN_PATH . 'architect.php', array(
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
    $pzarc_current_theme = wp_get_theme();
    $hw_opts=array();
    if ( ($pzarc_current_theme->get('Name') === 'Headway Base' || $pzarc_current_theme->get('Template')=='headway') ) {

      if ( is_multisite() ) {
        $hw_opts = get_blog_option( 1, 'headway_option_group_general' );
      } else {
        $hw_opts = get_option( 'headway_option_group_general' );
      }
    }
    if (  empty( $hw_opts[ 'license-status-architect' ] ) || $hw_opts[ 'license-status-architect' ] != 'valid' ) {

      add_submenu_page( 'pzarc', __( 'Licence', 'pzarchitect' ), '<span class="dashicons dashicons-admin-network size-small"></span>' . __( 'Licence', 'pzarchitect' ), 'manage_options', 'architect-licence', 'edd_architect_licence_page' );
    }
  }

  add_action( 'admin_menu', 'edd_architect_licence_menu' );


  function edd_architect_licence_page() {
    $license   = get_option( 'edd_architect_license_key' );
    $pzarc_status    = get_option( 'edd_architect_license_status' );
    $edd_state = get_option( 'edd_architect_license_state' );
    $edd_name = get_option( 'edd_architect_license_name' );
    $edd_activation = get_option( 'edd_architect_license_activation' );
    $edd_rem_activations = get_option( 'edd_architect_license_remaining_activations' );
    $edd_licence_limit = get_option( 'edd_architect_license_limit' );
    ?>
    <div class="wrap">
    <h2><?php _e( 'Architect Licence Options', 'pzarchitect' ); ?></h2>

    <p class="arc-important extra">Note: This page is for licences purchased from the <strong>PizazzWP shop</strong>. For licences purchased from the <strong>Headway Extend</strong>
      store, enter those in the <em>Headway</em> > <em>Options</em> screen</p>
    <?php
      if ( $pzarc_status === false || $pzarc_status !== 'valid' ) {
        echo ' <div class="arc-info-boxes">
                    <div class="arc-info col1">';
        echo '<h3 style="color:#0074A2">Architect Lite</h3>
        <p class="arc-important" style="font-weight:bold;">You are running Architect without activating a licence, therefore it is in Lite mode. Cool features you are missing out on are: Animations and access to all content types, including Galleries, Snippets, NextGen, Testimonials and custom post types</p>
        <p style="font-weight:bold;">If you need to purchase a licence, you can do so at the <a href="http://shop.pizazzwp.com/downloads/architect/" target="_blank">PizazzWP Shop</a>.</p>
        <p>Otherwise, enter and activate your licence from the Pizazz Shop licence below or on Headway > Options if you bought from Headway , </p>
</div></div>';
      }
    ?>
    <form method="post" action="options.php">

      <?php settings_fields( 'edd_architect_license' ); ?>

      <table class="form-table">
        <tbody>
        <tr valign="top">
          <th scope="row" valign="top">
            <?php _e( 'Licence Key' ); ?>
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
              <?php _e( 'Activate Licence' ); ?>
            </th>
            <td>
              <?php if ( $pzarc_status !== false && $pzarc_status == 'valid' ) { ?>
                <?php wp_nonce_field( 'edd_architect_nonce', 'edd_architect_nonce' ); ?>
                <input type="submit" class="button-secondary" name="edd_license_deactivate"
                       value="<?php _e( 'Deactivate Licence' ); ?>"/>

              <?php } else {
                wp_nonce_field( 'edd_architect_nonce', 'edd_architect_nonce' ); ?>
                <input type="submit" class="button-secondary" name="edd_license_activate"
                       value="<?php _e( 'Activate Licence' ); ?>"/>
              <?php }
                if  ( $pzarc_status !== false && $pzarc_status == 'valid' ) {
                  echo '<div style="background:white;border:1px solid #bbb;border-radius:3px;padding:5px 10px 10px 10px;width:250px;margin-top:20px;">';
                  echo '<p style="color:green;">'. __( 'Active','pzarchitect' ).'</p>';
                  echo "<p>" . $edd_name . "</p>";
                  echo "<p>" . $edd_state . "</p>";
                  echo '</div>';
                } elseif ($edd_activation==='Failed'){
                  echo '<p class="arc-important">Activation failed</p>';
                  echo '<p>(Did you click <em>Save Changes</em> before activating?)</p>';
                  echo "<p>Reason: " . $edd_state . "</p>";
                  if ($edd_state === 'Invalid licence key') {
                    echo '<p>If licence was purchased from Headway Extend, please enter it in <em>Headway</em> > <em>Options</em></p>';
                  }
                }
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
      $response = wp_remote_get( add_query_arg( $api_params, EDD_ARCHITECT_STORE_URL ), array(
          'timeout'   => 15,
          'sslverify' => false
      ) );

      // make sure the response came back okay
      if ( is_wp_error( $response ) ) {
        return false;
      }

      // decode the license data
      $license_data = json_decode( wp_remote_retrieve_body( $response ) );

//      object(stdClass)[426]
//  public 'success' => boolean false
//  public 'error' => string 'missing' (length=7)
//  public 'license_limit' => boolean false
//  public 'site_count' => int 0
//  public 'expires' => boolean false
//  public 'activations_left' => string 'unlimited' (length=9)
//  public 'license' => string 'invalid' (length=7)
//  public 'item_name' => string 'Architect' (length=9)
//  public 'payment_id' => boolean false
//  public 'customer_name' => string ' ' (length=1)
//  public 'customer_email' => null

//      public 'success' => boolean false
//  public 'error' => string 'no_activations_left' (length=19)
//  public 'max_sites' => int 1
//  public 'license_limit' => int 1
//  public 'site_count' => int 1
//  public 'expires' => string '2016-05-26 10:47:50' (length=19)
//  public 'activations_left' => int 0
//  public 'license' => string 'invalid' (length=7)
//  public 'item_name' => string 'Architect' (length=9)

      // $license_data->license will be either "valid" or "invalid"
//var_dump($license_data,$license);
//      die();
      update_option( 'edd_architect_license_status', $license_data->license );
      if ( $license_data->success ) {
        update_option( 'edd_architect_license_state', '<strong>'.__("Remaining activations: ",'pzarchitect').'</strong>' . $license_data->activations_left );
        update_option( 'edd_architect_license_name', '<strong>'.__('Licence owner: ','pzarchitect').'</strong>'.$license_data->customer_name );
        update_option( 'edd_architect_license_activation', 'Success' );
      } else {
        update_option( 'edd_architect_license_state', ($license_data->error=='missing' && !empty($license)?'Invalid licence key':ucfirst( str_replace( '_', ' ', $license_data->error ) ) ));
        update_option( 'edd_architect_license_name', '' );
        update_option( 'edd_architect_license_activation', 'Failed' );
      }
      update_option('edd_architect_license_remaining_activations',$license_data->activations_left);
      update_option('edd_architect_license_limit',$license_data->licence_limit);

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
      $response = wp_remote_get( add_query_arg( $api_params, EDD_ARCHITECT_STORE_URL ), array(
          'timeout'   => 15,
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
        delete_option( 'edd_architect_license_state' );
        delete_option( 'edd_architect_license_name' );
        delete_option( 'edd_architect_license_activation' );
        delete_option('edd_architect_license_remaining_activations');
        delete_option('edd_architect_license_limit');
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
    $response = wp_remote_get( add_query_arg( $api_params, EDD_ARCHITECT_STORE_URL ), array(
        'timeout'   => 15,
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
