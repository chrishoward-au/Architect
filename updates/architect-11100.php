<?php
  /**
   * Project pizazzwp-architect.
   * File: architect-1800.php
   * User: chrishoward
   * Date: 25/07/15
   * Time: 8:55 PM
   */


//  add_action('admin_init', 'pzarc_nag_ignore_1800');
//
//  function pzarc_nag_ignore_1800()
//  {
//    global $current_user;
//    $user_id = $current_user->ID;
//    /* If user clicks to ignore the notice, add that to their user meta */
//    if (isset($_GET[ 'pzarc_nag_ignore_v1800' ]) && '0' == $_GET[ 'pzarc_nag_ignore_v1800' ]) {
//      add_user_meta($user_id, 'pzarc_ignore_notice_v1800', 'true', true);
//    }
//  }


  add_action('admin_init','pzarc_update_11100');
  function pzarc_update_11100() {
    // This update requires migrating field data
/*
 * foreach blueprints as blueprint
 *  if using custom fields
 *    for each '_panels_design_custom-fields-count' = $i
 *      if content source = custom field
 *        update_post_meta( $post_id, $meta_key, $meta_value, $prev_value );
 *        '_blueprint_cfield-' . $i . '-content-source' = "customfield"
 *     else
 *      '_blueprint_cfield-' . $i . '-content-source' = '_blueprint_cfield-' . $i . '-name'
 *      '_blueprint_cfield-' . $i . '-name' = ''
 */

  }