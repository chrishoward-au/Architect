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
      <h3>Notice to existing Architect users</h3>
        <strong>This version is a major change in workflow. Therefore it also updates the database so old Architect Blueprints will still work.</strong>
        Please check your Blueprints to ensure they are still displaying correctly. Note: If you had any custom css that specifically styled panels by their panel short name, you will need to change that.
        <p><strong>Multiple sections are no longer available</strong>If you were using them, create Blueprint for each of your number 2 and 3 sections</p>

        stylings #


        <h4>Major new features in 1.1.0</h4>
        <ul>
        <li>&bull; Panels design is now a part of Blueprints design. It all happens in one screen now.</li>
        <li>&bull; Additional settings for Dummy Content images</li>
        <li>&bull; Architect Builder for easily arranging Blueprints on a page</li>
        <li>&bull; Shortcode generator for inserting Architect shortcodes in posts and pages</li>
        </ul>
        <strong class="arc-important">If you are updating Architect from v1.0.8.x or earlier, please got to the Architect Tools menu and rebuild CSS</strong>
        <p style="margin:20px 0;"><a class="arc-important-button" href="%1$s">Go to Tools</a></p>
         <p><a href="mailto:support@pizazzwp.com">Email support</a> '), 'admin.php?page=pzarc_tools');

        echo "</p>
        </div>";
      }
    }
  }


// Merge Panels into Blueprints
  update_1100();
  function update_1100()
  {
    $args       = array('posts_per_page' => -1, 'post_type' => 'arc-blueprints');
    $blueprints = get_posts($args);
    foreach ($blueprints as $k => $blueprint) {
      $bp_meta = get_post_meta($blueprint->ID);
      // It uses the slug!
      $args = array('posts_per_page' => -1,
                    'post_type'      => 'arc-panels',
                    'name'           => $bp_meta[ '_blueprints_section-0-panel-layout' ][ 0 ]);

      $bp_panel      = get_posts($args);
      $bp_panel_meta = get_post_meta($bp_panel[ 0 ]->ID);

      if (!empty($bp_panel_meta)) {
        foreach ($bp_panel_meta as $key => $value) {
          if ('_edit_lock' !== $key && '_edit_last' !== $key) {
            // Delete the key first to be sure no weird shtuff happens
            delete_post_meta($blueprint->ID, $key);
            update_post_meta($blueprint->ID, $key, maybe_unserialize($value[ 0 ]));
          }
        }
      }
      // Need a reset so can run again.

      // Now update the css - except that didn't work so had to tell them to manually do so.

    }
  }