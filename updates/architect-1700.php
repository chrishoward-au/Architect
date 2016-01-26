<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-1700.php
   * User: chrishoward
   * Date: 25/07/15
   * Time: 8:55 PM
   */


  add_action('admin_notices', 'pzarc_admin_notice_1700');
  function pzarc_admin_notice_1700()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v1700')) {
        echo '<div class="message updated highlight arc-updates"><p>';
        printf(__('<h2>Architect v1.7.0</h2>
        <h4>Architect 1.7 has some useful enhancements</h4>
        <ul>
        <li>Added options for Scaled font sizes for title and content for even better responsive design. Look in Content Layout > Titles, and Content Layout > Body/Excerpt</li>
        <li>Added Image Carousel preset.</li>
        <li>New option for Excerpt trimming for Characters, Paragraphs, More tag. Previously was words only.</li>
        <li>New option to remove shortcodes from Excerpts.</li>
        </ul>
        <p>As always there are a few bug fixes and other tweaks that you can read about in the changelog (link below).</p>
        <p>Also, don\'t forget to checkout my <a href="http://pizazzwp.com/codex/about-codex-documentation-system/" target="_blank">Codex plugin</a>. Codex is for making beautiful step-based documentation.</p>

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
        <p><strong><a href="https://s3.amazonaws.com/341public/LATEST/Architect/architect-changelog.html" target="_blank">View full change log</a> <a href="%1$s" style="float:right">Hide Notice</a></strong></p>','pzarchitect'), '?pzarc_nag_ignore_v1700=0');

        echo "
        </div>";
      }
    }
  }


  add_action('admin_init', 'pzarc_nag_ignore_1700');

  function pzarc_nag_ignore_1700()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v1700' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v1700' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v1700', 'true', true);
    }
  }
