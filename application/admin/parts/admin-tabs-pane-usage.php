<?php
	/**
	 * Created by PhpStorm.
	 * User: chrishoward
	 * Date: 18/8/17
	 * Time: 12:26 PM
	 */

	echo'
	<div class="tabs-pane " id="how">
                    <h2>' . __('Usage') . '</h2>
                    <div class="arc-info-boxes">
                    <div class="arc-info col1">
                      <h3>' . __('Shortcode', 'pzarchitect') . '</h3>
                      <p>' . __('For example, using shortcodes, you can use any of the following formats:', 'pzarchitect') . '</p>
                      <p><strong>[architect ' . __('blog-page-layout') . ']</strong></p>
                      <p><strong>[architect blueprint="' . __('blog-page-layout') . '"]</strong></p>
                      <p><strong>[architect blueprint="' . __('thumb-gallery') . '" ids="321,456,987,123,654,789"]</strong></p>
                      <p>Since version 1.2, you can now specify Blueprints to show on phones and/or tablets. For eaxmple:</p>
                      <p><strong>[architect' . __('blog-page-layout') . '  phone="' . __('blog-page-layout-phone') . '"  tablet="' . __('blog-page-layout-tablet') . '" ]</strong></p>

                      <p>' . __('<em>ids</em> are the specific post, page etc IDs and are used to override the defined selection for the Blueprint', 'pzarchitect') . '</p>
                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('Template tag', 'pzarchitect') . '</h3>
                    <p>' . __('Template tags are inserted in your page templates and the first parameter is the Blueprint short name, and the optional second one is a list of IDs to override the defaults.', 'pzarchitect') . '</p>
                    <p><strong>pzarchitect(\'' . __('blog-page-layout') . '\')</strong></p>
                    <p><strong>pzarchitect(\'' . __('thumb-gallery') . '\', \'321,456,987,123,654,789\')</strong></p>
                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('Widget', 'pzarchitect') . '</h3>
                    Add the Architect widgets through the <em>WP</em> > <em>Appearance</em> > <em>Widgets</em> screen
                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('Headway Block', 'pzarchitect') . '</h3>
                    Add the Architect Headway blocks in the <em>Headway Visual Editor</em>
                    </div>

                    <div class="arc-info col1">
                    <h3>' . __('Blox Block', 'pzarchitect') . '</h3>
                    Add the Architect Blox blocks in the <em>Blox Visual Editor</em>
                    </div>
                    <div class="arc-info col1">
                    <h3>' . __('Padma Block', 'pzarchitect') . '</h3>
                    Add the Architect Padma blocks in the <em>Padma Visual Editor</em>
                    </div>

                    <div class="arc-info col1">
                    <h3>' . __('Action Hooks', 'pzarchitect') . '</h3>
                    <p>If your theme had action hooks, you can hook specific Blueprints to them in your functions.php with the following base code:</p>
                        <pre>new showBlueprint(’action’, ’blueprint’, ’pageids’);</pre>
    <p><em>action</em> = Action hook to hook into</p>
    <p><em>blueprint</em> = Blueprint short name to display</p>
    <p><em>pageids</em> = Override IDs</p>
                        <p>Here is a a more extensive example that would work with Genesis (if you had those named Blueprints):</p>
<pre>function gs_init(){
  if (class_exists(\'showBlueprint\')) {
    new showBlueprint(\'genesis_before_header\',\'featured-posts-2x4\',\'home\');
    new showBlueprint(\'genesis_after_header\',\'basic-grid-2x3\',\'home\');
  }
}
add_action(\'init\',\'gs_init\');
</pre>

                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('Actions Editor', 'pzarchitect') . '</h3>
                    <p>The Actions Editor is in the <em>Architect</em> > <em>Actions Editor</em> menu and is a non-coding way to do the same thing as the Action Hooks do.</p>
                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('WP Gallery Shortcode Override', 'pzarchitect') . '</h3>
                    <p>An option in <em>Architect</em> > <em>Options</em> lets you set an override for all usages of the WP Gallery shortcode with a Blueprint of your own design. The only condition is the Blueprint must be set to use <em>Galleries</em> as the content source.</p>
                    <p>If you want to change individual <em>WP Gallery</em> shortcodes, switch to Text mode in the post editor, and replace the the word <em>gallery</em> in the short code with <em>architect</em> followed by the Blueprint short name. Keep the IDs.</p>
                    <p>e.g. <strong>[gallery ids="11,222,33,44,555"]</strong> you would change to <strong>[architect myblueprint ids="11,222,33,44,555"]</strong> where <em>myblueprint</em> is the <em>Shortname</em> of you Blueprint.</em></p>
                    </div>


                    <div class="arc-info col1">
                    <h3>' . __('Beaver Builder', 'pzarchitect') . '</h3>
                      <p>In the Beaver Builder page builder, drag and drop the Architect module to your page.</p>
                    </div>
                </div>
                </div>

';
