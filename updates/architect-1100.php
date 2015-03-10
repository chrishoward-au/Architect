<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-1100.php
   * User: chrishoward
   * Date: 9/03/15
   * Time: 8:55 PM
   */


  add_action('admin_notices', 'pzarc_admin_notice_1100');
  function pzarc_admin_notice_1100()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v1100')) {
        echo '<div class="message updated highlight"><p>';
        printf(__('<h3>Architect v1.1.0</h3>
<strong>This verson is a major change in workflow. Therefore it also updates the database so old Architect Blueprints still work.</strong>
<h4>New features in 1.1.0</h4>
<ul>
<li>&bull; Panels design is now a part of Blueprints design</li>
</ul>
 <p><a href="mailto:support@pizazzwp.com">Support</a> |<a href="%1$s">Hide Notice</a>'), '?pzarc_nag_ignore_v1100=0');

        echo "</p>
</div>";
      }
    }
  }
  add_action('admin_init', 'pzarc_nag_ignore_1100');

  function pzarc_nag_ignore_1100()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v1100' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v090' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v1100', 'true', true);
    }
  }

echo '<div class="message">Pretending to update...</div>';
  var_dump(post_type_exists( 'arc-panels' ));