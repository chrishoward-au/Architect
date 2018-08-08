<?php
	/**
	 * Created by PhpStorm.
	 * User: chrishoward
	 * Date: 18/8/17
	 * Time: 12:40 PM
	 */

	echo'
                <div class="tabs-pane " id="changes">
                    <h2>' . __('Latest changes.'). '</h2>
                    <div class="arc-info-boxes">
                    <div class="arc-info col1">';
  include_once( PZARC_PLUGIN_PATH.'/architect-changelog.html' );

echo '			</div>
		</div>
	</div>
	
	';