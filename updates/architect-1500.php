<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-1500.php
   * User: chrishoward
   * Date: 25/07/15
   * Time: 8:55 PM
   */


  add_action('admin_notices', 'pzarc_admin_notice_1500');
  function pzarc_admin_notice_1500()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v1500')) {
        echo '<div class="message updated highlight"><p>';
        printf(__('<h1>Architect v1.5.0</h1>
        <h3>Architect 1.5 is a small update with a big new feature!</h3>
        <p>You can now provide sorting and filtering options on masonry layouts. Importantly, it works with custom content (such as Woo Commerce). This opens up many possibilities for more user friendly site interaction.</p>
        <p>Demo</p>
                        <div>
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a>&nbsp;
                        <a class="pzarc-button-help" href="https://pizazzwp.freshdesk.com/support/discussions" target="_blank">
                        <span class="dashicons dashicons-groups"></span>
                        Community support</a>&nbsp;
                        <a class="pzarc-button-help" href="https://pizazzwp.freshdesk.com/support/tickets/new" target="_blank">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Tech support</a>
                        </div>
        <p><a href="https://s3.amazonaws.com/341public/LATEST/Architect/architect-changelog.html" target="_blank">View full change log</a> |<a href="%1$s">Hide Notice</a>','pzarchitect'), '?pzarc_nag_ignore_v1500=0');

        echo "
        </div>";
      }
    }
  }


  add_action('admin_init', 'pzarc_nag_ignore_1500');

  function pzarc_nag_ignore_1500()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v1500' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v1500' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v1500', 'true', true);
    }
  }
