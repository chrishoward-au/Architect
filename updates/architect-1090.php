<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-1090.php
   * User: chrishoward
   * Date: 9/03/15
   * Time: 8:55 PM
   */


  add_action('admin_notices', 'pzarc_admin_notice_1100');
  function pzarc_admin_notice_1090()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v1100')) {
        echo '<div class="message updated highlight"><p>';
        printf(__('<h3>Architect v1.0.9</h3>
<strong>This verson is a major change in workflow. Therefore it also updates the database so old Architect Blueprints will still work.</strong>
Please check your Blueprints to ensure they are still displaying correctly.

<h4>New features in 1.0.9</h4>
<ul>
<li>&bull; Panels design is now a part of Blueprints design</li>
<li>&bull; Blueprints listing sorted alphabetically</li>
</ul>
<strong style="color:tomato">If you are updating Architect from v1.0.8.x or earlier, please got to the Architect Tools menu and rebuild CSS</strong>
<p style="margin:20px 0;"><a class="arc-important-button" href="%1$s">Go to Tools</a></p>
 <p><a href="mailto:support@pizazzwp.com">Email support</a> '), 'admin.php?page=pzarc_tools');

        echo "</p>
</div>";
      }
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

    // Now update the css

  }
