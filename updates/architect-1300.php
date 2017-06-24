<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-1300.php
   * User: chrishoward
   * Date: 9/03/15
   * Time: 8:55 PM
   */


  add_action('admin_notices', 'pzarc_admin_notice_1300');
  function pzarc_admin_notice_1300()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v1300')) {
        echo '<div class="message updated highlight"><p>';
        printf(__('<h1>Architect v1.3.0</h1>
        <h3>Architect 1.3 contains nearly three dozen enhancements, changes and fixes.</h3>
        <p>The most significant of those is an upgrade of the slider engine which has fixed several quirks and has now made carousel layouts work plus slideshows adapt correctly to the height of individual slides.</p>
        <ul>
        <li style="font-weight:bold;color:red;">Please check all sliders and tabbed layouts still display as expected. If not, edit their Blueprint and resave.</li>
        <li style="font-weight:bold;color:red;">With the change of default animation state to Off, you will need to set any Panels based animations back on.</li>
        <li style="font-weight:bold;color:red;">Double check any custom CSS on Sliders & Tabbed navigation.</li>
        </ul>
                        <div>
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a>&nbsp;
                        <a class="pzarc-button-help" href="https://pizazzwp.freshdesk.com/support/discussions" target="_blank">
                        <span class="dashicons dashicons-groups"></span>
                        Community support</a>&nbsp;
                        <a class="pzarc-button-help" href="mailto:support@pizazzwp.com?subject=Architect%20help" target="_blank">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Tech support</a>
                        </div>
        <p><a href="https://s3.amazonaws.com/341public/LATEST/Architect/architect-changelog.html" target="_blank">View full change log</a> |<a href="%1$s">Hide Notice</a>','pzarchitect'), '?pzarc_nag_ignore_v1300=0');

        echo "
        </div>";
      }
    }
  }


  add_action('admin_init', 'pzarc_nag_ignore_1300');

  function pzarc_nag_ignore_1300()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v1300' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v1300' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v1300', 'true', true);
    }
  }
