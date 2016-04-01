<?php

  // Get a reference to the SysInfo instance
  $sysinfo = SysInfo::get_instance();

  // Now get information from the environment
  $theme                  = wp_get_theme();
  $browser                = $sysinfo->get_browser();
  $plugins                = $sysinfo->get_all_plugins();
  $active_plugins         = $sysinfo->get_active_plugins();
  $memory_limit           = ini_get( 'memory_limit' );
  $memory_usage           = $sysinfo->get_memory_usage();
  $all_options            = $sysinfo->get_all_options();
  $all_options_serialized = serialize( $all_options );
  $all_options_bytes      = round( mb_strlen( $all_options_serialized, '8bit' ) / 1024, 2 );
  $all_options_transients = $sysinfo->get_transients_in_options( $all_options );
  global $_architect_options;

  $blueprints=get_posts(array('post_type'=>'arc-blueprints','posts_per_page'=>-1));

?>

<div id="sysinfo">
  <div class="wrap">
    <div class="icon32">
      <img src="<?php echo SYSINFO_PLUGIN_URL ?>/images/sysinfo.png"/>
    </div>
    <!-- /.icon32 -->

    <h2 class="title"><?php _e( ' SysInfo', 'pzarchitect' ); ?></h2><!-- /.title -->

    <div class="clear"></div>
    <!-- /.clear -->

    <div class="section">
      <div class="header">
        <?php _e( 'System Information', 'pzarchitect' ); ?>
      </div>
      <!-- /.header -->

      <div class="inside">
        <a class="button-primary" href="#"
           onclick="window.open('<?php echo SYSINFO_PLUGIN_URL ?>/views/phpinfo.php', 'PHPInfo', 'width=800, height=600, scrollbars=1'); return false;"><?php _e( 'PHP Info', 'pzarchitect' ) ?></a>

        <p style="color:red;">Copy and paste the information below when submitting any support requests to theme or
          plugin developers.</p>

<textarea readonly="readonly" wrap="off">
<?php _e( 'WordPress Version:', 'pzarchitect' ); ?>      <?php echo get_bloginfo( 'version' ) . "\n"; ?>
<?php _e( 'PHP Version:', 'pzarchitect' ); ?>            <?php echo PHP_VERSION . "\n"; ?>
<?php // _e( 'MySQL Version:', 'pzarchitect' ); ?>          <?php //echo mysql_get_server_info() . "\n"; ?>
<?php _e( 'Web Server:', 'pzarchitect' ); ?>             <?php echo $_SERVER[ 'SERVER_SOFTWARE' ] . "\n"; ?>

<?php _e( 'WordPress URL:', 'pzarchitect' ); ?>          <?php echo get_bloginfo( 'wpurl' ) . "\n"; ?>
<?php _e( 'Home URL: ', 'pzarchitect' ); ?>              <?php echo get_bloginfo( 'url' ) . "\n"; ?>

<?php _e( 'Content Directory:', 'pzarchitect' ); ?>      <?php echo WP_CONTENT_DIR . "\n"; ?>
<?php _e( 'Content URL:', 'pzarchitect' ); ?>            <?php echo WP_CONTENT_URL . "\n"; ?>
<?php _e( 'Plugins Directory:', 'pzarchitect' ); ?>      <?php echo WP_PLUGIN_DIR . "\n"; ?>
<?php _e( 'Plugins URL:', 'pzarchitect' ); ?>            <?php echo WP_PLUGIN_URL . "\n"; ?>
<?php _e( 'Uploads Directory:', 'pzarchitect' ); ?>      <?php echo ( defined( 'UPLOADS' ) ? UPLOADS : WP_CONTENT_DIR . '/uploads' ) . "\n"; ?>

<?php _e( 'Cookie Domain:', 'pzarchitect' ); ?>          <?php echo defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN ? COOKIE_DOMAIN . "\n" : _e( 'Disabled', 'pzarchitect' ) . "\n" : _e( 'Not set', 'pzarchitect' ) . "\n" ?>
<?php _e( 'Multi-Site Active:', 'pzarchitect' ); ?>      <?php echo is_multisite() ? _e( 'Yes', 'pzarchitect' ) . "\n" : _e( 'No', 'pzarchitect' ) . "\n" ?>

<?php _e( 'PHP cURL Support:', 'pzarchitect' ); ?>       <?php echo ( function_exists( 'curl_init' ) ) ? _e( 'Yes', 'pzarchitect' ) . "\n" : _e( 'No', 'pzarchitect' ) . "\n"; ?>
<?php _e( 'PHP GD Support:', 'pzarchitect' ); ?>         <?php echo ( function_exists( 'gd_info' ) ) ? _e( 'Yes', 'pzarchitect' ) . "\n" : _e( 'No', 'pzarchitect' ) . "\n"; ?>
<?php _e( 'PHP ImgMagick Support:', 'pzarchitect' ); ?>  <?php echo ( class_exists( 'Gmagick' ) ) ? _e( 'Yes', 'pzarchitect' ) . "\n" : _e( 'No', 'pzarchitect' ) . "\n"; ?>
<?php _e( 'PHP Memory Limit:', 'pzarchitect' ); ?>       <?php echo $memory_limit . "\n"; ?>
<?php _e( 'PHP Memory Usage:', 'pzarchitect' ); ?>       <?php echo $memory_usage . "M (" . round( $memory_usage / $memory_limit * 100, 0 ) . "%)\n"; ?>
<?php _e( 'PHP Post Max Size:', 'pzarchitect' ); ?>      <?php echo ini_get( 'post_max_size' ) . "\n"; ?>
<?php _e( 'PHP Upload Max Size:', 'pzarchitect' ); ?>    <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
<?php _e( 'PHP Max Input Vars:', 'pzarchitect' ); ?>     <?php echo ini_get( 'max_input_vars' ) . "\n"; ?>
<?php if (ini_get( 'suhosin.post.max_vars' )) {_e( 'Suhosin Max Input Vars:', 'pzarchitect' ); ?>     <?php echo ini_get( 'suhosin.post.max_vars' ) . "\n";} ?>
<?php if (ini_get( 'suhosin.request.max_vars' )) {_e( 'Suhosin Request Max Input Vars:', 'pzarchitect' ); ?>     <?php echo ini_get( 'suhosin.request.max_vars' ) . "\n";} ?>

<?php _e( 'WP Options Count:', 'pzarchitect' ); ?>       <?php echo count( $all_options ) . "\n"; ?>
<?php _e( 'WP Options Size:', 'pzarchitect' ); ?>        <?php echo $all_options_bytes . "kb\n" ?>
<?php _e( 'WP Options Transients:', 'pzarchitect' ); ?>  <?php echo count( $all_options_transients ) . "\n"; ?>


WP_DEBUG:               <?php echo defined( 'WP_DEBUG' ) ? WP_DEBUG ? _e( 'Enabled', 'pzarchitect' ) . "\n" : _e( 'Disabled', 'pzarchitect' ) . "\n" : _e( 'Not set', 'pzarchitect' ) . "\n" ?>
SCRIPT_DEBUG:           <?php echo defined( 'SCRIPT_DEBUG' ) ? SCRIPT_DEBUG ? _e( 'Enabled', 'pzarchitect' ) . "\n" : _e( 'Disabled', 'pzarchitect' ) . "\n" : _e( 'Not set', 'pzarchitect' ) . "\n" ?>
SAVEQUERIES:            <?php echo defined( 'SAVEQUERIES' ) ? SAVEQUERIES ? _e( 'Enabled', 'pzarchitect' ) . "\n" : _e( 'Disabled', 'pzarchitect' ) . "\n" : _e( 'Not set', 'pzarchitect' ) . "\n" ?>
AUTOSAVE_INTERVAL:      <?php echo defined( 'AUTOSAVE_INTERVAL' ) ? AUTOSAVE_INTERVAL ? AUTOSAVE_INTERVAL . "\n" : _e( 'Disabled', 'pzarchitect' ) . "\n" : _e( 'Not set', 'pzarchitect' ) . "\n" ?>
WP_POST_REVISIONS:      <?php echo defined( 'WP_POST_REVISIONS' ) ? WP_POST_REVISIONS ? WP_POST_REVISIONS . "\n" : _e( 'Disabled', 'pzarchitect' ) . "\n" : _e( 'Not set', 'pzarchitect' ) . "\n" ?>

<?php _e( 'Operating System:', 'pzarchitect' ); ?>       <?php echo $browser[ 'platform' ] . "\n"; ?>
<?php _e( 'Browser:', 'pzarchitect' ); ?>                <?php echo $browser[ 'name' ] . ' ' . $browser[ 'version' ] . "\n"; ?>
<?php _e( 'User Agent:', 'pzarchitect' ); ?>             <?php echo $browser[ 'user_agent' ] . "\n"; ?>

<?php _e( 'ARCHITECT ', 'pzarchitect' ); ?>
<?php echo PZARC_SHOP.':'; ?>
<?php echo "\n=============\n\r"; ?>
<?php _e( 'Number of Blueprints:', 'pzarchitect' ); ?>   <?php echo count($blueprints) . "\n"; ?>
<?php _e( 'Styling enabled:', 'pzarchitect' ); ?>        <?php echo ( ! empty( $_architect_options[ 'architect_enable_styling' ] ) ? 'Yes' : 'No' ) . "\n"; ?>
<?php _e( 'All custom fields:', 'pzarchitect' ); ?>      <?php echo ( ! empty( $_architect_options[ 'architect_exclude_hidden_custom' ] ) ? 'Yes' : 'No' ) . "\n"; ?>
<?php _e( 'Animation enabled:', 'pzarchitect' ); ?>      <?php echo ( ! empty( $_architect_options[ 'architect_animation-enable' ] ) ? 'Yes' : 'No' ) . "\n"; ?>
<?php _e( 'Beta enabled:', 'pzarchitect' ); ?>           <?php echo ( ! empty( $_architect_options[ 'architect_beta_features' ] ) ? 'Yes' : 'No' ) . "\n"; ?>

<?php _e( 'ACTIVE THEME:', 'pzarchitect' ); ?>
<?php echo "\n=============\n\r"; ?>
- <?php echo $theme->get( 'Name' ) ?> <?php echo $theme->get( 'Version' ) . "\n"; ?>
<?php echo $theme->get( 'ThemeURI' ) . "\n"; ?>

<?php _e( 'ACTIVE PLUGINS:', 'pzarchitect' ); ?>
<?php echo "\n===============\n\r"; ?>
<?php
  foreach ( $plugins as $plugin_path => $plugin ) {

    // Only show active plugins
    if ( in_array( $plugin_path, $active_plugins ) ) {

      echo '- ' . $plugin[ 'Name' ] . ' ' . $plugin[ 'Version' ] . "\n";

      if ( isset( $plugin[ 'PluginURI' ] ) ) {
        echo '  ' . $plugin[ 'PluginURI' ] . "\n";
      } // end if

      echo "\n";
    } // end if
  } // end foreach
?>
</textarea>
      </div>
      <!-- /.inside -->
    </div>
    <!-- /.section -->
  </div>
  <!-- /.wrap -->
</div><!-- /#sysinfo -->