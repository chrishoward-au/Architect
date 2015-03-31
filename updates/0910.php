<?php
/**
 * Project pizazzwp-architect.
 * File: 0910.php
 * User: chrishoward
 * Date: 24/12/14
 * Time: 4:25 PM
 */

  /** Special notices */
  /* Display a notice that can be dismissed */

  add_action('admin_notices', 'pzarc_admin_notice_091');
  function pzarc_admin_notice_091()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v091')) {
        echo '<div class="message error highlight"><p>';
        printf(__('<h3>Architect beta 0.9.1</h3><p><strong>Architect v0.9.1 changes significantly how the navigator is set.</strong> If you are <strong>upgrading</strong> you will need to update any Architect blueprints using navigation. To do so:</p>
<ol><li>Edit the Blueprint</li>
<li>For Blueprints using Pagination, in <em>General settings</em>, click <em>Yes</em> for <em>Pagination</em></li>
<li>For Blueprints using Navigator, in <em>Blueprint Layout > Section 1</em>, click either <em>Slider</em> or <em>Tabbed</em></li></ol>
</ol>
<p>0.9.1 sees three other significant changes:</p>
<ol>
<li>The Layout types now include Sliders and Tabbed. Which is why you have to redo those ones with Navigator or Pagination.</li>
<li>A content source type of NextGEN Galleries has been added</li>
<li>The content selection has been vastly improved to be much more user friendly</li>
</ol></u>
 <p><a href="http://discourse.pizazzwp.com/t/architect-beta-v0-9-1" target="_blank">Full change log</a> | <a href="http://discourse.pizazzwp.com" target="_blank">Support</a> |<a href="%1$s">Hide Notice</a>','pzarchitect'), '?pzarc_nag_ignore_v091=0');

        echo "</p>
</div>";
      }
    }
  }

  add_action('admin_init', 'pzarc_nag_ignore_091');

  function pzarc_nag_ignore_091()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v091' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v091' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v091', 'true', true);
    }
  }