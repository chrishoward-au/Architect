<?php
	/**
	 * Created by PhpStorm.
	 * User: chrishoward
	 * Date: 18/8/17
	 * Time: 12:40 PM
	 */

	echo'
                <div class="tabs-pane " id="changes">
                    <h2>' . __('Latest changes. Version ') .PZARC_VERSION. '</h2>
                    <div class="arc-info-boxes">
                    <div class="arc-info col1">'.
wpautop('
<h2>1.10.2</h2>
* ADDED: Date filtering. Finally!
* ADDED: Option to hide empty Blueprints
* ADDED: Optional message if no content found for a Blueprint

* FIXED: Default classes not being applied to components in custom post types
* FIXED: Rounding problem that could cause some grid layouts to wrap around
* FIXED: Content not showing for RSS feed source.
* FIXED: Error if number of fields is less than number of columns in tabular
* FIXED: Author filtering not working!
* FIXED: Date filtering applied to all Blueprints on the same page

* UPGRADED: Slick.js v1.8.0

<h2>1.10.1</h2>
* FIXED: Old Architect widgets could return an error
* FIXED: WPML overriding CSS for paragraphs affecting layout

<h2>1.10.0</h2>
<h3>Beaver Builder related enhancements and fixes</h3>
* ADDED: More extensive filtering overrides in the Beaver Architect module
* ADDED: Extensive styling options to Architect Beaver module
* ADDED: Blueprint type to dropdown selector in Widget and Beaver module Blueprint selector
* ADDED: Option to change Blueprint display title to Architect Beaver module
* FIXED: Comments showing with Architect Beaver module when type is posts or page
* REMOVED: Removed Architect page builder. Recommend to use Beaver instead!

<h3>Content related enhancements</h3>
* ADDED: RSS Feed as a content source. In Blueprints Source tab
* ADDED: Option to set and use a default Focal Point when no Focal Point set on an image. In Architect > Options
* ADDED: Option to use \'Specific Text, HTML or Shortcodes\' as a custom field source. In Blueprints Custom Fields tab
* ADDED: Option to hide specific category names in meta field category name list. In Blueprints Meta tab
* ADDED: Message field that can be displayed immediately below content. In Blueprints Body/Excerpt tab
* ADDED: Option to insert a shortcode (or basic HTML) after a specific paragraph in body content. Good for inserting advertising!  In Blueprints Body/Excerpt tab

<h3>Miscellaneous enhancements and fixes</h3>
* ADDED: Shortcode [arc_hasmedia] Displays icons if post has media. Can be used in meta or custom fields display. Currently identifies galleries, videos, audio playlists and various document links
* ADDED: Shortcode [arc_debug] Dumps the $wp_query variable and some Blueprint info
* ADDED: Simple demo mode that outlines Architect Blueprints. Add /?demo to the page URL.
* ADDED: Message on Help & Support screen prompting crossgrade of Architect licence from Headway.
* ADDED: Option to name new Blueprints when importing. In Architect > Tools
* ADDED: Server info, BlogID and DB name to System Info. In Tools > SysInfo

* CHANGED: Export a Blueprint automatically downloads to a file now
* CHANGED: Admin backgrounds are now just CSS gradients rather than SVG to save size in the zip
* CHANGED: Using the "No field. Use prefix and suffix only" option for custom field source no longer requires the Link Field attribute enabled.
* CHANGED: Tech support links to support email address now
* CHANGED: In Blueprint listings, have now limited the "Used on" column to only show the first 15 instances
* CHANGED: In Blueprint listings, "Used on" column\'s data is scrollable when more than 5 items
* CHANGED: New paint job on the presets selector
* CHANGED: Hover on panels designer is now blue toned
* CHANGED: Updated help text on Help & Support screen
* CHANGED: Flattened defaults for slider nav buttons
* CHANGED: Combined Rebuild and clear image cache into one option for all caches. In Architect > Tools
* CHANGED: Default title wrapper is now H2. ***This MAY affect some existing layouts that style based on the tag rather than class.***
* CHANGED: Update info is now on the Help & Support tab, rather than appearing as a message.
* CHANGED: Changed licensing from EDD to Freemius

* FIXED: Filler images not working for Featured images
* FIXED: Category and Tag exclusion only working when inclusion was set too
* FIXED: Duplication of @media CSS declarations on panels.
* FIXED: Incorrect email url for support
* FIXED: Importing Blueprint failed to go to new Blueprint screen. Now provides a link.
* FIXED: Rebuilding cache was giving a success message even when it failed.
* FIXED: Blueprint "none" not found error on mobile devices
* FIXED: In Blueprint listings, \'Used on\' column was showing Blueprints as a usage
* FIXED: Bug in Architect\'s custom post types not showing custom fields.

* UPGRADED: Isotope to v3.0.4
* UPGRADED: Mobile Detect to 2.8.5

* REMOVED: Support contact form as no longer using Freshdesk for support
* REMOVED: Support forum link as no longer using Freshdesk for support

').'
                    <h3>Full changelog</h3>
                    Click to view the full historical <a href="'.PZARC_PLUGIN_URL.'readme.txt" target="_blank">changelog</a>
			</div>
		</div>
	</div>
	
	';