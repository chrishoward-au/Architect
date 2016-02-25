<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-1800.php
   * User: chrishoward
   * Date: 25/07/15
   * Time: 8:55 PM
   */


  add_action('admin_notices', 'pzarc_admin_notice_1800');
  function pzarc_admin_notice_1800()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v1800')) {
        echo '<div class="message updated highlight arc-updates"><p>';
        printf(__('<h2>Architect v1.8.0</h2>
        <h3>Architect 1.8 makes things easier</h3>
        <h4>The Blueprint editor is now easier to use, as is responsive titles and content.</h4>
        <p>This was going to be version 1.7... until I decided to revamp the Blueprint editor. All stylings are under their relevant tab now. And Component options are now grouped each in their own tab. And the Layout Type has its own tab. All this helps make things more visible and easier to find.</p>
        <p><strong><em>Documentation will be updated as soon as possible</em></strong></p>
        <p>Other cool features to look for are:</p>
        <ul>
        <li><strong>Added options for Scaled font sizes for title and content</strong> for even better responsive design. Look in Titles > Responsive overrides, and Body/Excerpt > Responsive overrides</li>
        <li><strong>Added option in for showing full content without leaving the page</strong>. Look in Body/Excerpt > Body/excerpt settings > Action on More click</li>
        <li>Added Image Carousel preset.</li>
        <li>New option for Excerpt trimming for Characters, Paragraphs, More tag. Previously was words only.</li>
        <li>New option to remove shortcodes from Excerpts.</li>
        <li>Images can now link to custom URLs per image. Requires the WP Gallery Custom Links plugin.</li>
        <li>Added option for Blueprint footer text. Can include limited HTML and shortcodes.</li>
                </ul>
        <p>As always there are a few bug fixes and other tweaks that you can read about in the changelog (link below).</p>
        <p><strong>Also, if you haven\'t already, do remember to checkout my <a href="http://pizazzwp.com/codex/about-codex-documentation-system/" target="_blank">Codex plugin</a>. Codex is for making beautiful step-based documentation.</strong></p>

                        <div class="pzarc-help-section">
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a>&nbsp;
                        <a class="pzarc-button-help" href="https://pizazzwp.freshdesk.com/support/tickets/new" target="_blank">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Tech support</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/checkout/customer-dashboard/" target="_blank">
                        <span class="dashicons dashicons-admin-users"></span>
                        Customer dashboard</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/affiliate-area/" target="_blank">
                        <span class="dashicons" style="font-size:1.3em">$</span>
                        Affiliates</a>
                        </div>
        <p><strong><a href="https://s3.amazonaws.com/341public/LATEST/Architect/architect-changelog.html" target="_blank">View full change log</a> <a href="%1$s" style="float:right">Hide Notice</a></strong></p>','pzarchitect'), '?pzarc_nag_ignore_v1800=0');

        echo "
        </div>";
      }
    }
  }


  add_action('admin_init', 'pzarc_nag_ignore_1800');

  function pzarc_nag_ignore_1800()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v1800' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v1800' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v1800', 'true', true);
    }
  }
