<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 21/06/2014
   * Time: 12:18 PM
   */

  if (!function_exists('pzwp_add_media_fields')) {

    add_action('admin_enqueue_scripts', 'pzwp_admin_enqueue_fp');
    function pzwp_admin_enqueue_fp()
    {
     wp_enqueue_script('pzwp-focal-point-js',plugin_dir_url(__FILE__) .'/pzwp-focal-point.js',array('jquery'),true);
    }

    function pzwp_add_media_fields($form_fields, $post)
    {
      // $form_fields['be-photographer-name'] = array(
      // 	'label' => 'Photographer Name',
      // 	'input' => 'text',
      // 	'value' => get_post_meta( $post->ID, 'be_photographer_name', true ),
      // 	'helps' => 'If provided, photo credit will be displayed',
      // );


      //NOTE: the focal pont field name really is pzgp-... That is a throw back to where it began life in GalleryPlus and maintains compatibility with all existing installs
      $form_fields[ 'pzgp-focal-point' ] = array(
          'label' => 'Focal Point',
          'input' => 'text',
          'value' => get_post_meta($post->ID, 'pzgp_focal_point', true),
          'helps' => 'Format: X,Y percents.<strong>Double click on the image to automatically fill the focal point field.</strong>',
      );

      return $form_fields;
    }

    add_filter('attachment_fields_to_edit', 'pzwp_add_media_fields', 10, 2);

    /**
     * Save values of Photographer Name and URL in media uploader
     *
     * @param $post array, the post data for database
     * @param $attachment array, attachment fields from $_POST form
     * @return $post array, modified post data
     */
    function pzwp_add_media_fields_save($post, $attachment)
    {
      // if( isset( $attachment['be-photographer-name'] ) )
      // 	update_post_meta( $post['ID'], 'be_photographer_name', $attachment['be-photographer-name'] );

      //NOTE: the focal pont field name really is pzgp-... That is a throw back to where it began life in GalleryPlus and maintains compatibility with all existing installs
      if (isset($attachment[ 'pzgp-focal-point' ])) {
        update_post_meta($post[ 'ID' ], 'pzgp_focal_point', $attachment[ 'pzgp-focal-point' ]);
      }

      return $post;
    }

    add_filter('attachment_fields_to_save', 'pzwp_add_media_fields_save', 10, 2);
  }