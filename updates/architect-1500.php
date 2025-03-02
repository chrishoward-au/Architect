<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-1500.php
   * User: chrishoward
   * Date: 25/07/15
   * Time: 8:55 PM
   */


  add_action('admin_notices', 'pzarc_admin_notice_1500');
  function pzarc_admin_notice_1500()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v1500')) {
        echo '<div class="message updated highlight arc-updates"><p>';
        printf(__('<h1>Architect v1.5.0</h1>
        <h3>Architect 1.5 is several small improvements with a big new feature!</h3>
        <p>You can now provide sorting and filtering options on masonry layouts. Importantly, it works with custom content (such as Woo Commerce). This opens up many possibilities for more user friendly site interaction. Thanks to Jamie, support guru at Headway, for requesting this feature.</p>
        <p>Demo: <a href="http://demos.architect4wp.com/features/custom-content/" target="_blank" title="Woo Commerce products with sorting and filtering">Woo Commerce products with sorting and filtering</a></p>
        <p>Tutorial: <a href="http://architect4wp.com/codex/architect-using-masonry-filtering-and-sorting/" target="_blank">Using filtering and sorting in Masonry layouts</a></p>
        <p>Another new feature is you can share links to a specific slide in a slide show.</p>
        <p>Tutorial: <a href="http://architect4wp.com/codex/architect-sharing-blueprint-slides-with-the-arcshare-shortcode/" target="_blank">Sharing slides using the archsare shortcode</a></p>
        <p>Other features to look for are:</p>
        <ul>
          <li>Added option to use Headway alternative titles. (In Title settings. On by default)</li>
          <li>Added option to display author avatars (under Meta settings)</li>
          <li>Added option for responsive font sizes on titles (In Titles settings)</li>
          <li>Added option to Dummy content to use your own set of images (In Dummy Content source)</li>
        </ul>

                        <div>
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a>&nbsp;
                        <a class="pzarc-button-help" href="https://pizazzwp.freshdesk.com/support/discussions" target="_blank">
                        <span class="dashicons dashicons-groups"></span>
                        Community support</a>&nbsp;
                        <a class="pzarc-button-help" href="mailto:chrish@341design.com.au?subject=Architect%20help" target="_blank">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Tech support</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/checkout/customer-dashboard/" target="_blank">
                        <span class="dashicons dashicons-admin-users"></span>
                        Customer dashboard</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/affiliate-area/" target="_blank">
                        <span class="dashicons" style="font-size:1.3em">$</span>
                        Affiliates</a>
                        </div>
        <p><a href="https://s3.amazonaws.com/341public/LATEST/Architect/architect-changelog.html" target="_blank">View full change log</a> |<a href="%1$s">Hide Notice</a>','pzarchitect'), '?pzarc_nag_ignore_v1500=0');

        echo "
        </div>";
      }
    }
  }


  add_action('admin_init', 'pzarc_nag_ignore_1500');

  function pzarc_nag_ignore_1500()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v1500' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v1500' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v1500', 'true', true);
    }
  }
