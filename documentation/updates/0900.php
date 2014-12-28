<?php
/**
 * Project pizazzwp-architect.
 * File: 0900.php
 * User: chrishoward
 * Date: 24/12/14
 * Time: 4:32 PM
 */

  /** Special notices */
  /* Display a notice that can be dismissed */

  add_action('admin_notices', 'pzarc_admin_notice_090');
  function pzarc_admin_notice_090()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v090')) {
        echo '<div class="message updated highlight"><p>';
        printf(__('<h3>Architect beta 0.9.0</h3><p><strong>Architect v0.9.0 changes how CSS is created.</strong> If you are <strong>upgrading</strong> you will need to recreate Architect CSS. To do so:</p>
<ol><li>Go to Architect > Styling Defaults and click <em>Reset All</em>. Apologies if you are using the Defaults options and need to re-enter them.</li>
<li>Go to Architect > Tools and click <em>Rebuild Architect CSS Cache</em>.</li>
 <li>If your site has a a caching plugin or service, you will need to clear that as well</li></ol>
 <p>If your Panels or Blueprints still look scrambled on the front end, go to their list in admin, select them all, select Edit from the dropdown and click Apply.</p>
<h4>New features in 0.9.0</h4>
<ul>
<li>&bull; Tabular layout. Select in Blueprints > Blueprint Layout > Layout mode. Many thanks to Matt Davis for this idea.</li>
<li>&bull; Accordion layout. Select in Blueprints > Blueprint Layout > Layout mode.</li>
<li>&bull; Navigation type Labels. Let you set specific labels for navigation items.</li>
<li>&bull; In Meta fields, Accordion titles and Navigation Labels, you can now include shortcodes. This is the best and most secure way to include and execute custom code.</li>
</ul>
 <p><a href="http://discourse.pizazzwp.com/t/architect-beta-v0-9-0" target="_blank">Full change log</a> | <a href="http://discourse.pizazzwp.com" target="_blank">Support</a> |<a href="%1$s">Hide Notice</a>'), '?pzarc_nag_ignore_v090=0');

        echo "</p>
</div>";
      }
    }
  }

  add_action('admin_init', 'pzarc_nag_ignore_090');

  function pzarc_nag_ignore_090()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v090' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v090' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v090', 'true', true);
    }
  }