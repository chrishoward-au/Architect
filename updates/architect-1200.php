<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-1200.php
   * User: chrishoward
   * Date: 9/03/15
   * Time: 8:55 PM
   */


  add_action('admin_notices', 'pzarc_admin_notice_1200');
  function pzarc_admin_notice_1200()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v1200')) {
        echo '<div class="message updated highlight"><p>';
        printf(__('<h1>Architect v1.2.0 - Adaptive and animated!</h1>
        <h3>Architect 1.2 contains many fixes, changes and new features. But the big two are Animations and device Adaptive.</h3>
        <p><strong>Animation</strong> allows you to animate the various components of each panel - e.g. the title, featured image, content etc. Animation was requested before Architect was first released and now it\'s here! In this first version there are still limitations and it doesn\'t work well with sliders (yet!) but you are going to be able to do some pretty cool and eye catching animations with your content.</p>
        <p><strong>Adaptive</strong> means you can now choose what Blueprints display on what device type: Phone, tablet or desktop and others. No longer will you have to try to responsively squeeze a desktop layout into a phone screen. You can now design specifically for the phone, then set what Blueprints to use for each device. Or you can just still use one for all devices.</p>
        <p>Visit the <a href=""http://architect4wp.com/codex-listings/" target=_blank>Architect Documentation</a> for tutorials on using each</p>
        <p><strong style="color:tomato">If you are updating Architect from an older version, please got to the Architect Tools menu and rebuild CSS, and then if you have a cache plugin or service, clear it.</strong>&nbsp;
        <a href="'.site_url('/wp-admin/admin.php?page=pzarc_tools').'">Go to Tools</a></p>
        <p><a href="https://s3.amazonaws.com/341public/LATEST/Architect/architect-changelog.html" target="_blank">View full change log</a> |<a href="%1$s">Hide Notice</a>','pzarchitect'), '?pzarc_nag_ignore_v1200=0');

        echo "
        </div>";
      }
    }
  }


  add_action('admin_init', 'pzarc_nag_ignore_1200');

  function pzarc_nag_ignore_1200()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v1200' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v1200' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v1200', 'true', true);
    }
  }
