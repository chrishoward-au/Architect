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
                    <div class="arc-info col1">'.
wpautop('
<h2 id="1.11.0-1feb2018">1.11.0 - 1 Feb 2018</h2>

<h3 id="beaverbuilderspecificenhancements">Beaver Builder specific enhancements</h3>

<ul>
<li>ADDED: Beaver module Any Fields to display any field from any table</li>
<li>ADDED: Custom version of Beaver Sidebar module can display different widget areas on different devices, or none at all</li>
<li>ADDED: Custom version of Beaver Maps module that allows you to use any fields</li>
<li>FIXED: Default content in BB editor would break if no preview. Displays dummy text instead.</li>
</ul>

<h3 id="other">Other</h3>

<ul>
<li>ADDED: Any field from any table to list of custom fields and link fields</li>
<li>ADDED: Option to not use font family in Typography in Blueprints editor. (If you want to manage all font families from CSS)</li>
<li>ADDED: Option to not use Google fonts in Typography in Blueprints editor. This can improve loading time of Blueprint editor.</li>
<li>ADDED: Option to use caption as image alt text (default is alt text or title if no alt text)</li>
<li>ADDED: Option to remove shortcodes from Body text</li>
<li>ADDED: Affiliates menu item</li>
<li>ADDED: Table prefix to Tools &gt; Sysinfo</li>
<li>ADDED: Option to set image quality (compression)</li>
<li>ADDED: Option to disable right-click saving and copying of images.</li>
<li>ADDED: Option to add copyright to images</li>
<li>CHANGED: Account info now shows affiliate info.</li>
<li>CHANGED: Image creation now uses Imagick by default if installed, which means it keeps Exif information (unless you use another plugin that has already stripped it out).</li>
<li>UPDATED: Freemius SDK to 1.2.4</li>
<li>FIXED: Was using an img tag even when no image.</li>
<li>FIXED: Was carrying forward image data in the loop</li>
<li>FIXED: Showing broken images when no featured image</li>
<li>FIXED: Set show_in_rest to false in custom post types (Snippets, Testimonials, Showcases, Blueprints) to ensure they don&#8217;t use Gutenberg editor</li>
<li>FIXED: Blueprints in Blueprints using parent Blueprint CSS</li>
<li>FIXED: Gutenberg content not displaying in Blueprints in Blueprints</li>
<li>FIXED: Gutenberg html comments skewing paragraph counting when forming excerpts by paragraphs</li>
<li>FIXED: Tools &gt; Sysinfo always showing Imagick disabled</li>
<li>FIXED: Filtering not working on second taxonomy in Masonry</li>
<li>FIXED: Focal point not working for non admin users</li>
<li>FIXED: Featured video metabox not showing for non admin users</li>
</ul>

<h2 id="1.10.7:24nov2017">1.10.7 : 24 Nov 2017</h2>

<ul>
<li>ADDED: Image Alt tag will automatically use image title if no Alt tag set</li>
<li>FIXED: Error on plugins list page on wpms sites</li>
<li>FIXED: Presets selector stopped working.</li>
</ul>

<h2 id="1.10.6:22nov2017">1.10.6 : 22 Nov 2017</h2>

<ul>
<li>ADDED: Changelog link to plugin listing since Freemius doesn&#8217;t yet support displaying changelog</li>
</ul>

<h2>1.10.5 : 18 Nov 2017</h2>
* ADDED: Shortcode to use in custom field display to show any field from any table. Usage [arccf table="tablename" field="fieldname"]
* CHANGED: Removed caching of custom field list as was making refreshing it difficult
* FIXED: Read more link not working for pages
* FIXED: Read more links showing when no content

<h2>1.10.4 : 2 Nov 2017 </h2>
* ADDED: Option to exclude Snippets from search results

* FIXED: Page navigation  not showing on search results page
* FIXED: Bug in Freemius code that caused error on EDD shops with Freemius Migration plugin installed
* FIXED: Couple of errors caused by using empty() on a function in PHP < 5.5

<h2>1.10.3 : 23 Oct 2017</h2>

* ADDED: RSS feed URL override to shortcode. Parameter: rssurl
* ADDED: Blueprint Title override to shortcode. Parameter: title
* ADDED: Support for custom overrides in Beaver Module. These are entered just like shorcode overrides and will be ignored if not valid.

* FIXED: RSS exclude tags not working
* FIXED: RSS hide title not working

* CHANGED: Removed pageguides until updated

<h2>1.10.2 : 20 Oct 2017</h2>
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
                    Click to view the full historical <a href="'.PZARC_PLUGIN_URL.'architect-changelog.html" target="_blank">changelog</a>
			</div>
		</div>
	</div>
	
	';