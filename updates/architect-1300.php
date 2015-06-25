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
        <h3>Architect 1.3 contains many fixes, changes and new features.</h3>
        Check animations and sliders and tabbed still work.
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
