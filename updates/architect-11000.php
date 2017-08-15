<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-11000.php
   * User: chrishoward
   * Date: 26/06/17
   * Time: 8:55 PM
   */


  add_action('admin_notices', 'pzarc_admin_notice_11000');
  function pzarc_admin_notice_11000()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v11000')) {
        echo '<div class="message updated highlight arc-updates"><p>';
        printf(__('<h2>Architect v1.10.0</h2>
        <h3>Architect 1.10 more power and more Beaver</h3>
        <p>Welcome to Architect 1.10.0 release. There are a number of significant updates in this version that I hope you will like, some of the key highlights include:</p>
        <p><strong>Also, if you haven\'t already, do remember to checkout my <a href="http://pizazzwp.com/codex/about-codex-documentation-system/" target="_blank">Codex plugin</a>. Codex is for making beautiful step-based documentation.</strong></p>

                        <div class="pzarc-help-section">
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a>&nbsp;
                        <a class="pzarc-button-help" href="mailto:support@pizazzwp.com?subject=Architect upgrade" target="_blank">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Tech support</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/checkout/customer-dashboard/" target="_blank">
                        <span class="dashicons dashicons-admin-users"></span>
                        Customer dashboard</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/affiliate-area/" target="_blank">
                        <span class="dashicons" style="font-size:1.3em">$</span>
                        Affiliates</a>
                        </div>
        <p><strong><a href="https://s3.amazonaws.com/341public/LATEST/Architect/architect-changelog.html" target="_blank">View full change log</a> <a href="%1$s" style="float:right">Hide Notice</a></strong></p>','pzarchitect'), '?pzarc_nag_ignore_v11000=0');

        echo "
        </div>";
      }
    }
  }


  add_action('admin_init', 'pzarc_nag_ignore_11000');

  function pzarc_nag_ignore_11000()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v11000' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v11000' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v11000', 'true', true);
    }
  }
