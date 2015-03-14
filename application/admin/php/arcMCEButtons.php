<?php
/**
 * Project pizazzwp-architect.
 * File: arcMCEButtons.php
 * User: chrishoward
 * Date: 13/03/15
 * Time: 9:52 PM
 */

  new arcMCEButtons();
  class arcMCEButtons
  {
    public function __construct()
    {
      add_action('admin_init', array($this, 'pu_shortcode_button'));
      add_action('admin_footer', array($this, 'pu_get_shortcodes'));
    }

    /**
     * Create a shortcode button for tinymce
     *
     * @return [type] [description]
     */
    public function pu_shortcode_button()
    {
      if( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
      {
        add_filter( 'mce_external_plugins', array($this, 'pu_add_buttons' ));
        add_filter( 'mce_buttons', array($this, 'pu_register_buttons' ));
      }
    }

    /**
     * Add new Javascript to the plugin scrippt array
     *
     * @param  Array $plugin_array - Array of scripts
     *
     * @return Array
     */
    public function pu_add_buttons( $plugin_array )
    {
      $plugin_array['pushortcodes'] = PZARC_PLUGIN_APP_URL . 'admin/js/arc-mce-buttons.js';

      return $plugin_array;
    }

    /**
     * Add new button to tinymce
     *
     * @param  Array $buttons - Array of buttons
     *
     * @return Array
     */
    public function pu_register_buttons( $buttons )
    {
      array_push( $buttons, 'separator', 'pushortcodes' );
      return $buttons;
    }

    /**
     * Add shortcode JS to the page
     *
     * @return HTML
     */
    public function pu_get_shortcodes()
    {
      global $shortcode_tags;

      echo '<script type="text/javascript">
        var shortcodes_button = new Array();';

      $count = 0;

      foreach($shortcode_tags as $tag => $code)
      {
        echo "shortcodes_button[{$count}] = '{$tag}';";
        $count++;
      }

      echo '</script>';
    }

  }