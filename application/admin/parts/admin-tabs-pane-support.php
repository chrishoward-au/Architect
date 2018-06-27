<?php
	/**
	 * Created by PhpStorm.
	 * User: chrishoward
	 * Date: 18/8/17
	 * Time: 12:39 PM
	 */

	echo'
                <div class="tabs-pane " id="help">
                    <h2>' . __('Support') . '</h2>
                    <div class="arc-info-boxes">
                    <div class="arc-info col1">
                    <h4>' . __('Currently installed version') . ': ' . PZARC_VERSION . '</h4>';
	echo '<p>' . __('For more detailed help, visit', 'pzarchitect') . ' <a href="http://architect4wp.com/codex-listings" target="_blank" class="arc-codex">' . __('Architect documentation at architect4wp.com', 'pzarchitect') . '</a></p>
                        <p>' . __('For <strong>technical support</strong>, email us at', 'pzarchitect') . ' <a href="mailto:support@pizazzwp.com" target="_blank" class="arc-codex">' . __('support@pizazzwp.com', 'pzarchitect') . '</a></p>
                    <h3>' . __('Things to try first', 'pzarchitect') . '</h3>
                    <ul>
                  	<li>If updates are not showing, try looking in Dashboard > Updates. If they still don\'t show, try deactivating and reactivating the Architect licence and trying again.</li>
                    <li>' . __('If Blueprints are not displaying as expected, please try emptying your WP cache if you are using one and then the Architect cache (under <em>Architect</em> > <em>Tools</em>)', 'pzarchitect') . '</li>
                    <li>' . __('If things just aren\'t working, e.g. nothing displays, the page is broken - then try deactivating all other plugins. If that fixes things, reactivate one at a time until you identify the conflict, then let us know what the plugin is.', 'pzarchitect') . '</li>
                    </ul>
                    </div>
                    </div>
                    </div>
                    ';
