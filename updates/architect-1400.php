<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-1400.php
   * User: chrishoward
   * Date: 25/07/15
   * Time: 8:55 PM
   */


  add_action('admin_notices', 'pzarc_admin_notice_1400');
  function pzarc_admin_notice_1400()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v1400')) {
        echo '<div class="message updated highlight"><p>';
        printf(__('<h1>Architect v1.4.0</h1>
        <h3>Architect 1.4 contains more than two dozen enhancements, changes and fixes since v1.3.2.</h3>
        <p>The most significant of those are:</p>
        <ul style="list-style:disc;margin-left:20px;">
        <li>Preset Selector is now always visible (tho can be minimized)</li>
        <li>You can make your own Presets. See <a href="http://architect4wp.com/codex/architect-making-your-own-presets/" target="_blank">this tutorial</a> to get started</li>
        <li>Option in Content Layout to alternate the Feature left/right when location is <em>Outside Components</em>. See <a href="http://architect4wp.com/codex/making-a-grid-using-the-alternating-feature-option/" target="_blank">this tutorial</a> to get started</li>
        </ul>
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
        <p><a href="https://s3.amazonaws.com/341public/LATEST/Architect/architect-changelog.html" target="_blank">View full change log</a> |<a href="%1$s">Hide Notice</a>','pzarchitect'), '?pzarc_nag_ignore_v1400=0');

        echo "
        </div>";
      }
    }
  }


  add_action('admin_init', 'pzarc_nag_ignore_1400');

  function pzarc_nag_ignore_1400()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v1400' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v1400' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v1400', 'true', true);
    }
  }
