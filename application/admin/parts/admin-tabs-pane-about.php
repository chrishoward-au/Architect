<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 18/8/17
   * Time: 12:40 PM
   */
  echo '
    <div class="tabs-pane active" id="quick">';
      include_once( 'updates/110.php' );
  echo '<div class="arc-info-boxes">
          <h2>Quick start</h2>
          <div class="arc-info col1">
            <ol style="list-style-type:lower-roman">
              <li>' . __( 'From the <em>Architect</em> > <em>Blueprints</em> listing, click the button that says <em>Create a new Blueprint from a Preset design</em>', 'pzarchitect' ) . '</li>
              <li>' . __( 'Browse the various Presets and select one to use', 'pzarchitect' ) . '</li>
              <li>' . __( 'To create a new Blueprint with the Preset\'s inbuilt styles, click <em>Use styled</em>', 'pzarchitect' ) . '</li>
              <li>' . __( 'To create a new Blueprint without any inbuilt styles, click <em>Use unstyled</em>. Note, the Blueprint when dispalyed will inherit some styling from your theme.', 'pzarchitect' ) . '</li>
              <li>' . __( 'Change the <em>Title</em> and <em>Blueprint Short name</em> to whatever is suitable', 'pzarchitect' ) . '</li>
              <li>' . __( 'Click <em>Update</em> to save.', 'pzarchitect' ) . '</li>
              <li>' . __( 'Within a WordPress page or post, add the shortcode <strong>[architect yourblueprint]</strong> or <strong>[architect blueprint="yourblueprint"]</strong> where <em>yourblueprint</em> is the <em>Shortname</em> you gave the Blueprint.', 'pzarchitect' ) . '</li>
              <li>' . __( 'Click <em>Update</em> to save and visit that page on your site to see your awesome Architect Blueprint displayed.', 'pzarchitect' ) . '</li>
            </ol>
          </div>
        </div>
        <h2>' . __( 'Menu overview' ) . '</h2>
        <div class="arc-info-boxes">
          <div class="arc-info col2"><h3><span class="dashicons dashicons-editor-help"></span>Help & Support</h3>
            <p>This page! Provides a brief Quick start guide, an overview of the Architect menus and:</p>
            <ul>
            <li>Usage instructions for displaying your Blueprints</li>
            <li>A form for submitting a tech support request</li>
            <li>and most importantly, a shout out to all the third party code that make Architect great.</li>
          </div>
          <div class="arc-info col2"><h3><span class="dashicons dashicons-welcome-widgets-menus"></span>Blueprints</h3>
            <p>Blueprints is where you create and manage the content layouts.</p>
            <p> These can take any of the six basic forms: Grid, Slider, Tabbed, Tabular, Masonry and Accordion.</p>
            <p>Blueprints can be displayed using Widgets; Shortcodes; Headway blocks; Blox blocks; Template tags; Action Hooks; or the Architect Beaver Builder module</p>
          </div>
          <div class="arc-info col2"><h3><span class="dashicons dashicons-hammer"></span>Tools</h3>
            <p>The Tools menu provides methods for clearing the Architect CSS cache and the Architect image cache.</p>
            <p>If you change the Focal Point of an image, you will need to clear the Architect image cache.</p>
            <p>If you have a caching plugin installed and clear either of these caches, you will still need to clear it too.</p>
          </div>
          <div class="arc-info col2"><h3><span class="dashicons dashicons-admin-settings"></span>Options</h3>
            <p>Options contains a lot of useful settings for controlling the behaviour of Architect.</p>
            <p> This includes, setting Responsive breakpoints, default for shortcodes and other modifications to Architect\'s behaviour.</p>
          </div>
          <div class="arc-info col2"><h3><span class="dashicons dashicons-admin-appearance"></span>Styling Defaults</h3>
            <p>Styling Defaults are very useful. Set these before you get started creating Blueprints to save time setting styling for every Blueprint. </p>
          </div>
          <div class="arc-info col2"><h3><span class="dashicons dashicons-migrate"></span>Actions Editor</h3>
            <p>If you know the names of the action hooks in your theme, the Actions Editor allows you to hook an Architect Blueprint into them without any coding!</p>
          </div>
          <div class="arc-info col2"><h3>Account</h3>
            <p>Check and manage your Architect licence</p>
          </div>
          <div class="arc-info col2"><h3>Contact us</h3>
            <p>A built-in form to contact Pizazz support</p>
          </div>
          <div class="arc-info col2"><h3>Pricing/Upgrade</h3>
            <p>If you want to buy or upgrade Architect</p>
          </div>
        </div>
  </div><!-- tab pane -->';
