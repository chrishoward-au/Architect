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
<strong>This verson is a major change in workflow. Therefore it also updates the database so old Architect Blueprints will still work.</strong>
Please check your Blueprints to ensure they are still displaying correctly.
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

// Merge Panels into Blueprints
  $args       = array('posts_per_page' => -1, 'post_type' => 'arc-blueprints');
  $blueprints = get_posts($args);
  foreach ($blueprints as $k => $blueprint) {
    $bp_meta = get_post_meta($blueprint->ID);
    // It uses the slug!
    $args = array('posts_per_page' => -1,
                  'post_type'      => 'arc-panels',
                  'name'      => $bp_meta[ '_blueprints_section-0-panel-layout' ][ 0 ]);

    $bp_panel      = get_posts($args);
    $bp_panel_meta = get_post_meta($bp_panel[ 0 ]->ID);

    if (!empty($bp_panel_meta)) {
      foreach ($bp_panel_meta as $key => $value) {
        if ('_edit_lock' !== $key && '_edit_last' !== $key) {
          // Delete the key first to be sure no weird shtuff happens
          delete_post_meta($blueprint->ID,$key);
          update_post_meta($blueprint->ID,$key,maybe_unserialize($value[0]));
        }
      }
    }
    // Need a reset so can run again.
  }
