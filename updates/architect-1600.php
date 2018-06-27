<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-1600.php
   * User: chrishoward
   * Date: 25/07/15
   * Time: 8:55 PM
   */


  add_action('admin_notices', 'pzarc_admin_notice_1600');
  function pzarc_admin_notice_1600()
  {
    if (current_user_can('install_plugins')) {

      global $current_user;
      $user_id = $current_user->ID;
      /* Check that the user hasn't already clicked to ignore the message */
      if (!get_user_meta($user_id, 'pzarc_ignore_notice_v1600')) {
        echo '<div class="message updated highlight arc-updates"><p>';
        printf(__('<h2>Architect v1.6.0</h2>
        <h4>Architect 1.6 three big new features!</h4>
        <p>First off, it now has a <strong>Beaver Builder module</strong>, so now you can easily display your Blueprints in Beaver Builder.</p>
        <p>Secondly, the Masonry mode now supports "packed" masonry. Whereas Masonry until now still used the columns, packed ignores the columns and packs everything in both vertically and horizontally.</p>
        <p>This, of course, is only beneficial when the widths vary. And to that end, the third new feature is in Image Cropping, you can now choose to scale the images to fit the image limits.</p>
        <p>Demo: <a href="http://demos.architect4wp.com/layouts/image-gallery-packed-masonry/" target="_blank">Packed masonry</a></p>
        <p>Another way you can use these scaled images, is when you display them within the content, if you set the feature image width to 0%, they\'ll display at their actual scaled size.</p>
        <h4>Other news</h4>
        <p>This week I also released a new plugin, <a href="http://pizazzwp.com/codex/about-codex-documentation-system/" target="_blank">Codex</a>, for making beautiful step-based documentation.</p>
        <p>Next week I will be officially launching my <a href="https://shop.pizazzwp.com/affiliate-area/" target="_blank">affiliates program</a>. It is already live, so you can already join and start making money from recommending Architect.</p>

                        <div class="pzarc-help-section">
                        <a class="pzarc-button-help" href="http://architect4wp.com/codex-listings/" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        Documentation</a>&nbsp;
                        <a class="pzarc-button-help" href="mailto:support@pizazzwp.com?subject=Architect%20help" target="_blank">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Tech support</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/checkout/customer-dashboard/" target="_blank">
                        <span class="dashicons dashicons-admin-users"></span>
                        Customer dashboard</a>
                        <a class="pzarc-button-help" href="https://shop.pizazzwp.com/affiliate-area/" target="_blank">
                        <span class="dashicons" style="font-size:1.3em">$</span>
                        Affiliates</a>
                        </div>
        <p><strong><a href="https://s3.amazonaws.com/341public/LATEST/Architect/architect-changelog.html" target="_blank">View full change log</a> <a href="%1$s" style="float:right">Hide Notice</a></strong></p>','pzarchitect'), '?pzarc_nag_ignore_v1600=0');

        echo "
        </div>";
      }
    }
  }


  add_action('admin_init', 'pzarc_nag_ignore_1600');

  function pzarc_nag_ignore_1600()
  {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET[ 'pzarc_nag_ignore_v1600' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v1600' ]) {
      add_user_meta($user_id, 'pzarc_ignore_notice_v1600', 'true', true);
    }
  }
