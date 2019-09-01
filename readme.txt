=== Architect content block builder ===
Contributors: chrishoward
Tags: content,display,posts,pages,gallery,slider,tabs,tabbed,tabular,widget,hooks
Requires at least: 4.0
Tested up to: 5.2
Requires PHP: 5.6
Stable tag: 10.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Plugin URI: http://architect4wp.com

Themes, page builders, and now Gutenberg provide blocks or modules for displaying content. Architect lets your create custom content blocks that you can then display with any theme, page builder or Gutenberg.

== Description ==
Go beyond the limitations of the layouts in the theme or page builder you use to easily build any content layouts for it. E.g. grids, sliders, lists, galleries, tables etc.

Most themes give you great looking layouts for your content, but are usually limited to what that theme provides. Frameworks give you control, but often require getting your hands dirty.

Architect breaks down both those barriers.

What if you love your theme, but just want the content layout of the homepage to be done your way? Architect solves that.

What if you love your theme framework but want an easy way to build the content layouts without having to cut code? Architect solves that.

With Architect you can build the layouts your theme does not provide - and all from one plugin. No longer do you need a plugin for sliders, another for magazine grids, another for tabbed content or another for displaying photo galleries.

Also, to ease maintenance and improve performance, it's best to minimize the number of plugins on your site. Architect helps that by replacing several plugins with one.

= Why use Architect? =
Architect greatly reduces the complexity of designing and developing methods to display your content. For example, if you are using the Twenty Thirteen and decide you want a 3x3 grid of excerpts to display on the home page, you would have to code a page template for that purpose.

With Architect, you code the shell of the page template, but build the rest in Architect and paste one very simple line of code into your page template that calls and displays the Architect Blueprint.

Blueprints can be displayed using widgets and shortcodes as well.

You can even override the design of the WP Gallery shortcode with your own Blueprint.

== Installation ==
1. Install and activate the plugin through the Plugins menu in WordPress.

== Usage ==
There are several ways to display Architect Blueprints.

* Shortcode: [architect blueprint="yourblueprint" ids="1,2,3,4,5"]
* Template tag: echo pzarchitect('yourblueprint','1,2,3,4,5');
* Widget
* Headway Block
* Blox Block
* Beaver module
* Action hooks (Insert a hook in your theme page template, then hook Architect to it)
* Actions editor (Built into Architect, all you have to know is the name of the hook you want to use)
* WP Gallery shortcode override


== Frequently Asked Questions ==

= What themes is Architect compatible with =
Architect should work with any theme but some will take more effort than others, especially in terms of the look and layout. To that end, we provide the means to match CSS classes and create your own panel definitions.

= Is Architect compatible with all plugins =
The short answer is "Unlikely". No plugin can hope to be compatible with all other plugins (there are more than 36,000 of them!) but we do aim to be compatible with the more popular ones. We will endeavour to rectify any incompatibilities found; however, it's also not uncommon for the root cause to be the conflicting plugin. Always make sure you are running the latest version of your plugins.

= Is Architect WPML compatible =
Yes. Architect has been successfully tested with WPML.

= Do you provide refunds =
Yes! We're pretty flexible on refunds, allowing 60 days. But we would like you to make sure you've done everything possible to get Architect to work to your requirements, especially seeking support on our help desk.

= If I deactivate Architect, will I lose all my Blueprints =
No. We don't delete any of your data. In the future a method will be provided for you to remove all Architect data if you really need to.

== Known issues ==
* Importing Blueprints, you will have to redo filters. This is a limitation of Redux not Architect.
* Videos don't autopause when changing slides
* Retina images currently only generates 2x
* Some fields may not save when offline. This is a Redux issue too.
* Architect not compatible with JetPack Photon

== Support ==

For support, please send an email to support@pizazzwp.com

== Changelog ==

= Notes =
* If you have trouble with the new licensing on Freemius, please contact support@pizazzwp.com
* If Blueprints editor fails to render, go to options and turn on option to prevent post lookup lists loading.

TODO: Update shortcode help!
TODO: ** Debug slideshows *nem
TODO: Don't upscale images smaller than dimensions. *nem
TODO: SrcSet *nem
TODO: Fix content animation demo on demos
TODO: Fill space when no images for sliders *nem
TODO: Option to fill space when no images in grid.
TODO: Dynamically load post lists instead of fully loading.
TODO: Turnoff architect timers
TODO: FIX Drop down content making components anchor to top of panel
TODO: Add reading view option.
TODO: Fix .pzarc-fakethumb showing when no feature
TODO: Fix loading and rendering issue with sliders
TODO: Add page categories option (taxonomy)
TODO: Check if TGMPA message is breaking admin when Redux needs updating
TODO: Add option for full size components with options to float content.
MYPANELS .pzarc-components {    height:100%;    display: flex;  flex-direction: column;  justify-content: center;}
TODO: Add font scaling to meta
TODO: INCLUDE REDUX
TODO: Inlcude Sudo - but fix nav centeringe
TODO: Include Users post type
TODO Add image trim to featured images
TODO: Add method to remove all Blueprint data
todo: implement object fit for images to fix scaling - see Wangclub
MYPANELS {    display:flex!important;}
MYPANELS figure.entry-thumbnail {    max-height:100%;}
MYPANELS figure.entry-thumbnail img {    object-fit:cover;    width:100%;    height:100%;}
** TODO: Add a warning if in lite mode and Blueprint is using Pro features.


= 10.9 =
* UPDATED: Changed version numbering to match releases. This is the 109th update so v10.9
* ADDED: Showcase link a link option for images (of course only relevant if Showcase is source!)
* ADDED: Option in Blueprints settings to include/exclude hidden custom fields in dropdowns.
* ADDED: New custom field type: Email
* ADDED: New custom field type: Link
* ADDED: New custom field type: File
* ADDED: New custom field type: Image
* ADDED: New custom field type: Map
* ADDED: New custom field type: Repeater
* ADDED: New custom field type: Group TODO
* ADDED: New custom field type: Multi value ??? TODO
* ADDED: New custom field type: Gallery
* ADDED: Money prefix/suffix to Number field
* ADDED: Admin option to show the "Insert Architect Blueprint" button in the post editor to admins only.
* ADDED: Option to select custom field source type. Currently support standard WP and Advanced Custom Fields
* ADDED: Custom CSS field to custom fields styling
* ADDED: Not Used option to custom fields
* CHANGED: Architect Maps Extend module for Beaver Builder now requires your own Google API key because of Google's changes.
* FIXED: Showcases and Testimonials not displaying extra fields metabox
* ADDED: Override for Skip N Posts value in the Beaver module
* UPDATED: Freemius SDK to 2.2.4 - Security update
** TODO: Setup defaault repeater row layouts
** TODO: Test Group and multi-value fields - taxonomies?

= 1.17.0
* FIXED: Beaver Builder Maps and Any Field modules loading field arrays unnecessarily on front end.
* FIXED: CSS caching problem in Blox since 1.16.0
* FIXED: Possible warning if php_uname is not available in sysinfo
* FIXED: Possible notice in Blueprint listing is "Used on" page no longer available.
* FIXED: Alignment not working in slider navigator.
* CHANGED: Default option for populating posts dropdown is now off.
* CHANGED: CSS is now cached in WP Options and loaded in page footer. This allows on the fly regeneration of CSS and reduces problems with file-based CSS not being available on some hosts.


= 1.16.0 :
FIXED: Beaver Builder module is not using number of posts to show
FIXED: Sometimes CSS hasn't regenerated making for broken layouts
FIXED: Clearing cache now clears all files in the Architect cache.

= 1.15.1 : 12 August 2018
* FIXED: Error in Blox when no action options
* FIXED: Inaccessible features displaying in free version.

= 1.15.0 : 8 August 2018

* CHANGED: Switched to loremflickr.com as lorempixel went down
* FIXED: Read more link not showing when using More tag as Excerpts break.
* FIXED: Errors in free version calling premium functions when it shouldn't
* FIXED: Various PHP notices

= 1.14.0: 3 July 2018
* CHANGED: When content source is RSS, source images are now cached
* FIXED: RSS source images not scaling and cropping

= 1.13.0 : 23 June 2018

* ADDED: More filter options to Blox block
* CHANGED: Rejigged Blox block menus
* REMOVED: Gutenberg block. Will be a separate plugin until Gutenberg is out of beta

= 1.12.0
* CHANGED: SysInfo now shows WP_MEMORY_LIMIT
* CHANGED: SysInfo now shows average Blueprint loading times (a hugh time is likely indicative of a slow site)
* CHANGED: Removed affiliation

* FIXED: Error in SysInfo PHP Memory Limit
* FIXED: Masonry broke with v1.11.1
* FIXED: Problems with grid margins breaking layout wrapping

* UPGRADED: Isotope to 3.0.6
* UPGRADED: ImagesLoaded to 4.1.4
* UPGRADED: Freemius SDK to 2.1.1. Includes GDPR support and compliance


= 1.11.1
* ADDED: Error msg if Presets selector can't read presets files.
* FIXED: Removed Features tab as it's not populated
* FIXED: Changed misleading message saying "This theme requires Redux" to "This plugin requires Redux"
* FIXED: Strip architect shortcodes in excerpts to prevent loops
* FIXED: Replaced sections array keys with numeric constants due to PHP 7.1.12 notices
* FIXED: Prevent a Blueprint from calling itself when nesting Blueprints
* FIXED: Patch for Freemius where sometimes giving cURL error when activating licence
* CHANGED: Now using class rather than ID when creating Blueprint CSS since Blueprints can be re-used on the same page. You will need to change any custom CSS that was using the ID.
* CHANGED: Blueprint CSS to load as late as possible in head.
* CHANGED: Remove custom fields support from free version

= 1.11.0 - 1 Feb 2018
Beaver Builder specific enhancements
* ADDED: Beaver module Any Fields to display any field from any table
* ADDED: Custom version of Beaver Sidebar module can display different widget areas on different devices, or none at all
* ADDED: Custom version of Beaver Maps module that allows you to use any fields
* FIXED: Default content in BB editor would break if no preview. Displays dummy text instead.

Other
* ADDED: Any field from any table to list of custom fields and link fields
* ADDED: Option to not use font family in Typography in Blueprints editor. (If you want to manage all font families from CSS)
* ADDED: Option to not use Google fonts in Typography in Blueprints editor. This can improve loading time of Blueprint editor.
* ADDED: Option to use caption as image alt text (default is alt text or title if no alt text)
* ADDED: Option to remove shortcodes from Body text
* ADDED: Affiliates menu item
* ADDED: Table prefix to Tools > Sysinfo
* ADDED: Option to set image quality (compression)
* ADDED: Option to disable right-click saving and copying of images.
* ADDED: Option to add copyright to images

* CHANGED: Account info now shows affiliate info.
* CHANGED: Image creation now uses Imagick by default if installed, which means it keeps Exif information (unless you use another plugin that has already stripped it out).

* UPDATED: Freemius SDK to 1.2.4

* FIXED: Was using an img tag even when no image.
* FIXED: Was carrying forward image data in the loop
* FIXED: Showing broken images when no featured image
* FIXED: Set show_in_rest to false in custom post types (Snippets, Testimonials, Showcases, Blueprints) to ensure they don't use Gutenberg editor
* FIXED: Blueprints in Blueprints using parent Blueprint CSS
* FIXED: Gutenberg content not displaying in Blueprints in Blueprints
* FIXED: Gutenberg html comments skewing paragraph counting when forming excerpts by paragraphs
* FIXED: Tools > Sysinfo always showing Imagick disabled
* FIXED: Filtering not working on second taxonomy in Masonry
* FIXED: Focal point not working for non admin users
* FIXED: Featured video metabox not showing for non admin users

= 1.10.7  : 24 Nov 2017
* ADDED: Image Alt tag will automatically use image title if no Alt tag set
* FIXED: Error on plugins list page on wpms sites
* FIXED: Presets selector stopped working.

= 1.10.6 : 22 Nov 2017
* ADDED: Changelog link to plugin listing since Freemius doesn't yet support displaying changelog

= 1.10.5 :  18 Nov 2017
* ADDED: Shortcode to use in custom field display to show any field from any table. Usage [arccf table="tablename" field="fieldname"]
* CHANGED: Removed caching of custom field list as was making refreshing it difficult
* FIXED: Read more link not working for pages
* FIXED: Read more links showing when no content

= 1.10.4 : 2 Nov 2017
* ADDED: Option to exclude Snippets from search results

* FIXED: Page navigation  not showing on search results page
* FIXED: Bug in Freemius code that caused error on EDD shops with Freemius Migration plugin installed
* FIXED: Couple of errors caused by using empty() on a function in PHP < 5.5

= 1.10.3 : 23 Oct 2017

* ADDED: RSS feed URL override to shortcode. Parameter: rssurl
* ADDED: Blueprint Title override to shortcode. Parameter: title
* ADDED: Support for custom overrides in Beaver Module. These are entered just like shorcode overrides and will be ignored if not valid.

* FIXED: RSS exclude tags not working
* FIXED: RSS hide title not working

* CHANGED: Removed pageguides until updated

= 1.10.2 : 20 Oct 2017

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

= 1.10.1 =
* FIXED: Old Architect widgets could return an error
* FIXED: WPML overriding CSS for paragraphs affecting layout

= 1.10.0 =
Beaver Builder related enhancements:
* ADDED: More extensive filtering overrides in the Beaver Architect module
* ADDED: Extensive styling options to Architect Beaver module
* ADDED: Blueprint type to dropdown selector in Widget and Beaver module Blueprint selector
* ADDED: Option to change Blueprint display title to Architect Beaver module
* FIXED: Comments showing with Architect Beaver module when type is posts or page
* REMOVED: Removed Architect page builder. Recommend to use Beaver instead!

Content related enhancements:
* ADDED: RSS Feed as a content source. In Blueprints Source tab
* ADDED: Option to set and use a default Focal Point when no Focal Point set on an image. In Architect > Options
* ADDED: Option to use 'Specific Text, HTML or Shortcodes' as a custom field source. In Blueprints Custom Fields tab
* ADDED: Option to hide specific category names in meta field category name list. In Blueprints Meta tab
* ADDED: Message field that can be displayed immediately below content. In Blueprints Body/Excerpt tab
* ADDED: Option to insert a shortcode (or basic HTML) after a specific paragraph in body content. Good for inserting advertising!  In Blueprints Body/Excerpt tab

Miscellaneous enhancements:
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
* CHANGED: In Blueprint listings, "Used on" column's data is scrollable when more than 5 items
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
* FIXED: In Blueprint listings, 'Used on' column was showing Blueprints as a usage
* FIXED: Bug in Architect's custom post types not showing custom fields.

* UPGRADED: Isotope to v3.0.4
* UPGRADED: Mobile Detect to 2.8.5

* REMOVED: Support contact form as no longer using Freshdesk for support
* REMOVED: Support forum link as no longer using Freshdesk for support

= 1.9.9 = : 4 Apr 2017
* FIXED: Redux 3.6.4 broke parts of the Blueprint designer

= 1.9.8 = 26 Mar 2017
* ADDED: Shortcode processing option to custom fields
* FIXED: Notices warning on custom field setting Field Type in admin
* FIXED: Set defaults for responsive Title and Content fonts

= 1.9.7 =
* FIXED: Missing options when Blox is the active theme
* FIXED: Architect menu was only meant to be available to Admins

= 1.9.6 = 7 Dec 2016
* FIXED: Incorrect version number causing upgrade info to not go away

= 1.9.5 = 30 Nov 2016
* ADDED: Option for custom fields that are saved as an array to be displayed in a table by selecting Group. This does not work for ACF repeater fields, as they are stored differently.

= 1.9.4 = 15 Nov 2016
* CHANGED: Added Blox compatibility

= 1.9.3 = 30 Sep 2016

* ADDED: Option to set default terms selection in Masonry filtering.
* ADDED: Option in Sources for multiple content types. Using this has limitations, as any filtering or other criteria will apply to all content. For example, if you mix pages and posts, don't filter on categories since no pages will be found.
* ADDED: Options to change the slug name that appears in the URL of Architect's custom post types - Snippets, Testimonials and Showcases.
* ADDED: Text field for filter labels
* ADDED: Option to display of archive descriptions.
* ADDED: Option to use generic email addresses for users with specific roles. Great when an author leaves but you still want a valid email on their posts. i.e. set them to subscriber
* CHANGED: Blueprint dropdown lists to default to showing content type too.
* CHANGED: Extended Architect name to Architect DIY Kit
* FIXED: Accordions closed styling overridden by Bootstrap styling
* FIXED: Blueprints not showing in dropdown in Architect Beaver module
* FIXED: Filtering and sorting not working when multiple masonry Blueprints on a page.
* FIXED: Masonry losing gutter when filtering
* FIXED: Actions Editor not displaying Blueprints on tablets
* FIXED: Shortcode showing wrong content
* FIXED: Blueprint editor tabs disappeared if /wp-content was moved
* REMOVED: Retina images option as it wasn't working properly anymore. Will rebuild it.
* UPDATED: Masonry library, Isotope, to v3.
* UPDATED: Masonry library, Packery, to v2.
* UPDATED: TGM to 2.6.1

= 1.9.2 : 15 May 2016 =
* ADDED: Column in Blueprint list showing the pages Blueprints are used on. Requires pages to have been viewed since 1.9.2 update.
* CHANGED: Blueprint list now indicates layout type with an icon
* CHANGED: Finally found a way to get the Blueprint CSS to load in the header
* CHANGED: Various code tweaks
* CHANGED: Faster check for online access before using dummy images
* UPDATED: Magnific lightbox popup to v1.1
* FIXED: Slider autoplay going backwards if infinite is off
* FIXED: Hide Revolution Slider metabox on Blueprint editor
* FIXED: Bug in licence activation might fail with an error

= 1.9.1 : 25 March 2016 =
* FIXED: Removed Whoops debugger

= 1.9.0 : 24 March 2016 =
* ADDED: Custom field filtering
* ADDED: Custom field sorting
* FIXED: Testimonials specified order not working
* FIXED: Masonry filter by message showing Pz when using a PizazzWP tax type.
* ADDED: Message on Help and Blueprint listings suggesting upgrading PHP if less than 5.4

= 1.8.2 =
* ADDED: Message reminding to increase max input vars. This has become necessary as 1.8 added more styling fields - which use a lot of input vars.

= 1.8.1
* FIXED: Small layout issues in editor screens

= 1.8.0 : 25 Feb 2016 =
* CHANGED: Revamped the layout of the Blueprint editor for improved usability
* CHANGED: Defaults are now saved in a WP option to speed things up a little.
* CHANGED: Custom field types now include a text type that will add paragraphs
* CHANGED: Tweaked the Layout Type icons
* FIXED: When updating a Blueprint, it will now go back to the exact same tab
* FIXED: When opening a different Blueprint to the previous one it will open at the main tab.
* FIXED: Missing Max Input Vars message.
* FIXED: PHP Notices in Blueprint editor when styling turned off

= 1.7.0 =
* ADDED: Added options for Scaled font sizes for title and content for even better responsive design. Look in Titles > Responsive overrides, and Body/Excerpt > Responsive overrides
* ADDED: Image Carousel preset the same as on the demos site.
* ADDED: Excerpt trimming for Characters, Paragraphs, More tag
* ADDED: Option to remove shortcodes from Excerpts.
* ADDED: Option to link images to a custom link per image. Requires the WP Gallery Custom Links plugin
* ADDED: Option for Blueprint footer text. Can include limited HTML and shortcodes.
* ADDED: Option for showing full content without leaving the page.
* CHANGED: Masonry transition time is now much faster
* CHANGED: Included a link to the importing Blueprints and Presets tutorial
* CHANGED: Small visual changes to Preset Selector
* CHANGED: Tweaked sysinfo
* CHANGED: Updated Slick to 1.5.9
* FIXED: The way custom post types load. Improves Beaver Builder compatibility
* FIXED: Problem if Architect called in a WP loop with Defaults as content source
* FIXED: Rounding of panels margin needed two decimal places (was 0 in changes in 1.6.1)
* FIXED: Actions Editor was showing Blueprints on all pages
* FIXED: Blueprint PHP notice if no images are selected for Gallery content source
* FIXED: Extra quotes mark in div panel opening class
* FIXED: Some Blueprint styling defaults not showing correct in editor
* FIXED: PHP Warning in date display

= 1.6.1 =
* FIXED: Minor security issue because of missing index.php
* FIXED: Cache creation could cause a warning if a directory in the cache.
* FIXED: Layouts issues with percentages and calcs in Firefox and Internet Explorer 11.

= 1.6.0 =
* ADDED: Beaver Builder module for selecting a Blueprint to display.

= 1.5.19 =
* ADDED: Option to Shrink to fit image limits in Image cropping.
* ADDED: Packed Masonry option. Works best with image galleries using the above Shrink option.
* CHANGED: Setting "Feature as thumbnail" width to zero will use image at actual size. This is only valid when feature is shown in the content.

= 1.5.18 =
* FIXED: Sometimes strtotime returns wrong timestamp if there is commas in the date string

= 1.5.17 : 24-Dec-2015 =
* CHANGED: In Options, "Use Architect styling" is now always enabled by default. Previously was off for Headway users, but that meant Presets were unstyled.

= 1.5.16 =
* ADDED: Affiliates link
* ADDED: Link to customer dashboard

= 1.5.15 =
* ADDED: Option to galleries to display all attached images in the primary post/page

= 1.5.14 =
* FIXED: Rogue space causing warning message

= 1.5.13 =
* FIXED: Name conflict in TGMPA

= 1.5.12 =
* FIXED: Fluid and fixed width tabs not working.

= 1.5.11 =
* FIXED: Preset Selector disabled when Page Guide turned off

= 1.5.10 : 20 Nov 2015 =
* FIXED: Conflict with Yoast 3 caused by old Redux hack to counter a Yoast conflict in pre v3 Yoast!

= 1.5.9 : 11 Nov 2015 =
* FIXED: Removed content filtering of dummy content as causing an infinite loop in Beaver

= 1.5.8 : 9 Nov 2015 =
* ADDED: Option to set behaviour of custom field links

= 1.5.7 =
* CHANGED: Message in Presets Selector about styling is more obvious
* CHANGED: Thumbs now have title in tooltip
* CHANGED: Horizontal tabbed left/right margins now defaults to zero.
* FIXED: Slider fade option stopped working

= 1.5.6 =
* CHANGED: Excerpts will now process shortcodes. This includes any in image captions and descriptions.
* ADDED: Option for fixed width tabs to add a margin for compensation

= 1.5.5 =
* FIXED: Sharing icons not showing for arcshare.
* UPDATED: Slickjs to 1.5.8

= 1.5.4 =
* FIXED: Added missing nav items styling to Headway Visual Editor design mode

= 1.5.3 =
* ADDED: Option to exclude current Snippet if a Blueprint displays Snippets on a Snippets page.
* CHANGED: Horizontal tabs to wrap, minimum width 80px and use full width when smaller screens.
* CHANGED: Slick Slider infinite now off by default
* CHANGED: Moved Slick Slider transition selection to Slick Slider settings
* CHANGED: Renamed Transitions section to Transitions Timing
* UPGRADED: Isotope to v2.2.2
* FIXED: Extra end div breaking layouts with masonry
* FIXED: Titles bullet margins not working
* FIXED: Horizontal tabs not equal heights
* FIXED: Masonry sorting and filtering showing when not enabled
* FIXED: "Missing image" thumbnail not sizing correctly.
* FIXED: Specific filler image not showing when in background
* FIXED: Layout issue with vertical tabbed preset

= 1.5.2 =
* CHANGED: Thumbs library to load only as required thus fixing conflict with WooCommerce
* ADDED: More cropping options Crop to top, centre or bottom
* FIXED: Various small tweaks and fixes

= 1.5.1 =
* ADDED: Rendering of shortcodes in custom fields
* ADDED: Shortcode [arcpagetitle] to display the parent page's title. Use in meta fields.
* CHANGED: Slides hidden before loading for tidiness.
* FIXED: CSS not loading when using a Blueprint to override WP Gallery.
* FIXED: Slideshow jQuery loading wrong sometimes

= 1.5.0 : 3 Oct 2015=
* ADDED: Support for filtering and sorting in masonry layouts!! (Thanks, Jamie!)
* ADDED: Option to use Headway alternative titles. On by default.
* ADDED: Option to display author avatars (Thanks Courtney!)
* ADDED: [arcshare] shortcode for sharing links to each specific panel.
* ADDED: Links to specific slides using link from arcshare shortcode
* ADDED: Message on Blueprint editor for Headway users about Architect styling.
* ADDED: Option for responsive font sizes on titles (Thanks, Chiara!)
* ADDED: Option to set Dummy content to use your own set of images (Thanks, Jeremy!)
* ---
* CHANGED: Tidied up and compressed images (using tinypng.com). Saved 300KB in zip file!
* CHANGED: Tightened specificity of panels class to minimize conflicts in nested blueprints (e.g. content blueprint with a shortcode blueprint within)
* ---
* FIXED: Masonry sorting on numeric and date custom fields now working
* FIXED: Only first custom taxonomy showing in meta fields
* FIXED: A few bugs and styling in Masonry filtering and sorting.
* FIXED: Transition speed not working
* FIXED: Bug where responsive font sizes for content not working for medium breakpoint
* FIXED: Errors in filters and sorting if chosen custom post type deactivated

= 1.4.9 : 16 Sept 2015 =
* FIXED: Pages instead of posts showing on Blog index
* CHANGED: Default posts per page for pagination to value set in WP Settings > Reading

= 1.4.8.2 =
* ADDED: Option for specific filler image (thanks Cemil!)

= 1.4.8.1 =
* FIXED: Error displaying in Headway Visual Editor Design Mode when Defaults was content source.

= 1.4.8 : 9 Sept 2015 =
* FIXED: Change at v1.4.3 broke single post display when using Defaults as content source.

= 1.4.7 : 4 Sept 2015 =
* ADDED: Support for Shortcake a user friendly shortcode tool. Install the Shortcake (Shortcode UI) plugin and then click Add Media in Posts etc, then Insert Post Element and select the Architect Blueprint. For compatibility, there is a specific Shortcake shortcode, [architectsc]
* FIXED: Bug where if a WP gallery used in Blueprint content source no longer existed, an error would display

= 1.4.6 : 30-August-2015 =
* FIXED: Make Header and Footers option doing nothing!
* FIXED: Panels min width doing nothing!
* ADDED: Option for fixed with panels. In conjunction with flexbox, this allows full width grids that nicely space
* ADDED: Option for panels justification when using fixed width panels.

= 1.4.5 =
* CHANGED: Added a message to Presets selector to explain "Use styled" won't be styled if "Use Architect styling" is turned off.
* FIXED: function name conflict in EDD module
* ADDED: Showcase custom post type
* ADDED: Options in custom fields to use the post title or no field (allowing the use of just the prefix and suffix. Useful with links where you want a generic link text)
* ADDED: Category and tag columns to Snippets, Testimonials and Showcases listing screens.

= 1.4.4 =
* FIXED: Snippets selector not sortable

= 1.4.3 =
* FIXED: Wording of accordions option that controls if it's open or closed at startup
* CHANGED: Installed Presets list on Tools page not shown if empty
* FIXED: Problems with Defaults content source not displaying correct content when Overrides enabled

= 1.4.2 : 5 August 2015 =
* CHANGED: BFI Thumbs options page removed (it is a duplicate of the Refresh Cache in Architect > Options)

= 1.4.1 : 4 August 2015 =
* FIXED: Possible security risk with SysInfo library

= 1.4.0 : 3 August 2015 =
* ADDED: Option to alternate feature left/right when outside components
* ADDED: Option to float feature left/right when in components to close up space
* ADDED: Option to make a custom field display as an embedded url. E.g. YouTube link displays as video
* ADDED: Option to not use a dropdown for post lists. A text field is used instead where you then need to enter a specific ID(s). Required when lots of posts that kill the memory loading into the dropdown array. This affects the Posts and Galleries content sources only.
* CHANGED: All actions and filters prefixes changed from 'pzarc' to 'arc'
* CHANGED: Message on update now - finally! - says Blueprint updated
* FIXED: Scaled cropping not working when using NextGen galleries

= 1.3.7 =
* ADDED: Accordion title styling to Headway Design Mode
* CHANGED: Tweaks to Lite version and messages
* CHANGED: Message on Headway Options page to only put HW purchased licences there.
* CHANGED: Much tidying up of files reduced zip size.
* CHANGED: Menu title now includes version number on hover
* FIXED: Custom taxonomies displayed in meta not showing comma separator
* FIXED: Error with uploads url not being https sometimes
* FIXED: Tabular column and table widths when content doesn't fill last cell in first row.

= 1.3.6 =
* ADDED: Blueprint Preset importing
* ADDED: Check for custom Blueprint Presets in uploads folder /pizazzwp/architect/presets
* CHANGED: Added messages when licence activation fails in the Architect licence activation screen
* CHANGED: Column order in Blueprints listing
* FIXED: Sliders now respond to RTL
* FIXED: Accordions will display open/close indicator on left on RTL sites.

= 1.3.5 =
* FIXED: Dummy images not working with lightbox
* FIXED: NextGen images not working with lightbox

= 1.3.4 =
* FIXED: Nav align needed -webkit-justify-content rules for Safari

= 1.3.3 =
* ADDED: Option to set HTML tag for post titles
* CHANGED: Preset selector is now always visible
* CHANGED: Small visual changes to Preset selector
* FIXED: Thumbnails in navigator not using Focal Point

= 1.3.2 =
* FIXED: Turned off debugging code accidentally left on in EDD which prevented Manage WP remote updates from working.

= 1.3.1 =
* FIXED: Errors if custom fields set to display but not setup in Architect
* FIXED: get_posts called too early in Slide content type causing error in Marketplace plugin

= 1.3.0 : 25 June 2015 =
* ADDED: Option to activate the Architect Builder on WP Pages editor. Off by default
* ADDED: More links to documentation, forums and support
* ADDED: Option to thumbs nav for continuous row of thumbs
* ADDED: Several new options to titles and labels in navigator
* ADDED: Option for horizontal nav items alignment to justify
* ADDED: Option to make each whole panel link to the post or page.
* ADDED: Options to shortcode and block to override taxonomy and terms. In shortcode eg: [architect myblueprint tax="category" terms="uncategorized"]
* ADDED: Architect Builder page template with no sidebars
* ADDED: Cropping option "Preserve aspect, fit to height"
* ADDED: Sorting options of None and Specified. Specified uses the order of images in a gallery, or the order of specifically selected posts or pages
* CHANGED: If no alternate animation set for Panels, will use the primary
* CHANGED: Animations now can be turned off without having to unset their options
* CHANGED: Simplified default title when creating a Blueprint from a Preset
* CHANGED: Included classes in nav items to uniquely identify them
* CHANGED: Links to support are now within the Blueprint editor
* CHANGED: Defaults for nav item padding to 2px as % values causing problems with updated Slick and Firefox ignores the top and bottom when %
* CHANGED: Option for nav skipper now includes None
* CHANGED: Create blueprint function allows option to preserve shortname
* CHANGED: Specific posts and pages can be manually ordered
* CHANGED: Icons for the layout type selector
* CHANGED: Licence owner name now displays on licence screen
* FIXED: Slider navigation div showing when no nav and slick error
* FIXED: Slick error when no nav
* FIXED: Some custom fields not showing in dropdown (because of transients)
* FIXED: Slick 1.5 various problems
* FIXED: Licence check now works when Headway child theme being used
* FIXED: Image cache not clearing when saving Blueprints
* FIXED: Images no resizing correctly for the "Preserve aspect" options.
* FIXED: Notice when displaying Testimonials
* FIXED: Skip buttons showing on tabbed layout
* FIXED: Blueprint title not being displayed
* FIXED: Removed possible mobile detect lib conflict
* FIXED: Title not being added to image link attributes when Title not shown.
* FIXED: Bug sometimes where rotating a device would make images disappear
* UPGRADED: Slider engine, Slick, to version 1.5. This fixes several problems, including: autoplay will loop to beginning after the last slide; Blueprints will no longer start at the largest height of slides in it; carousels now work (set columns to multiple).
* UPGRADED: WOW.js to v1.1.2

= 1.2.10 : 22 May 2015 =
* FIXED: Excerpts not going 100% wide when no thumb image
* CHANGED: Refactored sliders so devs can use their own
* ADDED: Option to hide archive prefix text when showing page title on an archive page
* CHANGED: If Architect is installed on a Headway themed site, Architect's own styling will be turned off as the default.

= 1.2.9 : 14 May 2015 =
* FIXED: Date formatting for dummy content

= 1.2.8 =
* FIXED: Licence check error stopping pro features loading
* FIXED: Inbuilt lightbox still loading when alternate selected.

= 1.2.7 =
* CHANGED: Custom taxonomy terms selection is now a drop down populated with terms of the custom taxonomy.
* ADDED: Option to use alternate lightbox
* FIXED: whitescreen error when saving if PHP less than 5.3

= 1.2.6 =
* ADDED: Option to have accordions closed on startup
* CHANGED: Licence check on WPMS using Headway to align with Headway validation system

= 1.2.5 =
* ADDED: Option to exclude specific posts
* ADDED: Option to exclude specific pages
* ADDED: Option to exclude current post
* ADDED: Option to exclude current page
* FIXED: Post selector only showing 5 posts
* FIXED: White screen error when saving in EDD
* FIXED: Fatal error on activating 1.2.4

= 1.2.4 =
* FIXED: Animations stopped working at 1.2.2
* FIXED: Background images not animating
* ADDED: Cache automatically clears after installing Architect update

= 1.2.3 =
* ADDED: Option to exclude margins from outer grid panels

= 1.2.2  =
* FIXED: Animation errors if no animations set for content

= 1.2.1  =
* FIXED: Blueprints not found message when using block display with no blueprints set for phone and tablet and viewing on tablet or phone

= 1.2.0 : 4 May 2015 =
* ADDED: Animation of components. Doesn't react to sliding yet.
* ADDED: Block, shortcode, template tag, builder and widget now support different Blueprints per device type: Desktop/any, Tablet, Phone.
* ADDED: Sysinfo to Tools menu
* ADDED: Option for custom previous/next text for pagination
* ADDED: EDD licencing for purchases direct from Pizazz
* ADDED: Filter pzarc-add-presets to allow devs to include their own Presets
* ADDED: Function pzarc_create_blueprint to allow devs to automatically create new Blueprints

* CHANGED: Typography to treat line heights less than three as multipliers, not absolutes.
* CHANGED: Architect will run in Lite mode if no valid licence from either Headway Extend store or Pizazz shop is active
* CHANGED: Architect Builder is hidden if its page template is not selected

* FIXED: Some PHP notices
* FIXED: Custom fields styling pretty much didn't work!
* FIXED: Blueprints custom CSS went missing
* FIXED: Some issues with panels margins not applying
* FIXED: Some incorrect text in pagination links
* FIXED: Errors if trying to use pagination wth dummy content.
* FIXED: Some themes prevented adding the .pzarchitect body class so removed that class from the css
* FIXED: Featured images not centring when set to centre
* FIXED: Grabbing all custom fields could be slow. Using a transient which may cause problems of its own! New custom fields may not appear straight away (remembering tho they must contain data first)
* FIXED: Various minor tweaks and fixes
* FIXED: Licence system now works for both Pizazz shop and Headway store from the one file
* FIXED: Animation not working on custom field groups
* FIXED: Adaptive not displaying from shortcodes
* FIXED: Component animation runs panel-by-panel
* FIXED: Fields not rendering correctly in Blueprint editor

* UPDATED: TGM Library to v2.4.1 with XSS vulnerability fix

* REMOVED: Custom field group styling defaults since they're so variable and thus just clutter

= 1.1.6 =
* ADDED: Additional filters and hooks for developers
* ADDED: News feed to Help & Support
* ADDED: Wordpress custom fields settings to Snippets and Testimonials
* ADDED: Styling for page titles in Blueprint Styling > Page

* CHANGED: Some text and help info for greater clarity

* FIXED: Blueprint CSS may have been overly persistent on some caching systems.
* FIXED: Archive titles not displaying
* FIXED: Google fonts not rendering

= 1.1.5 =
* FIXED: Nothing showing for Accordions except when content is Dummy

= 1.1.4 =
* CHANGED: Spaces are stripped from css file name - just in case the short name validator allowed them

* FIXED: Titles not selectable for styling in the Headway Visual Editor Design Mode

= 1.1.3 : 7 April 2015=
* CHANGED: Blueprints are hidden until rendered. This is because their CSS loads at the bottom of the page (since it enqueues mid page) and can cause an ugly unstyled moment otherwise.
* FIXED: Wrong classes for featured image in Headway
* FIXED: Specific selection of posts, pages, snippets, slides, testimonials wasn't working
* FIXED: Usability issues in Content Layout drag & drop when Feature Location set to "Outside Components"

= 1.1.2 =
* FIXED: Rare install error about .sass-cache

= 1.1.1 =
* ADDED: Testimonials content type
* ADDED: More presets. Spruced up some existing presets
* ADDED: Option to turn off additional content types: Snippets, Testimonials and any future ones.
* FIXED: Various small tweaks and fixes
* CHANGED: Split the Body/Excerpt styling page into two tabs
* CHANGED Using the simple prefix MYBLUEPRINT in the custom CSS fields of the Styling options, Architect will automatically substitute the correct class
* FIXED: Custom field wrapper tag now applies to the actual field, not its contents
* FIXED: Conflict with Redux 3.5

= 1.1.0 =
* ADDED: Presets Selector
* ADDED: Architect builder
* ADDED: Export Blueprint
* ADDED: Architect shortcode selector for post and page editor.
* ADDED: Option for posts with no image to use lorempixel.
* ADDED: Styling for body and excerpt paragraphs
* ADDED: Option to select different placeholder image source for Dummy content
* ADDED: Greyscale option for dummy images
* ADDED: Categories for dummy images
* ADDED: Option to not apply Trim oer Scale to background images for those who want to CSS this themselves

* CHANGED: Combine Panels and Blueprints screen. Remove sections. Simplify. Improve workflow.
* CHANGED: Blueprints listing screen sorted by title
* CHANGED: Yay! Inadvertently sped up opening and saving Blueprints by HEAPS! Probably was the external help links to Wistia and Amazon.
* CHANGED: Set some default stylings sizing and spacing to make blank blueprints a little more visually appealing.
* CHANGED: Improved Blueprint CSS loading
* CHANGED: Images in Preset selector now don't load until it is opened. Which is going to be really handy if they get hosted online.
* CHANGED: Architect can now be used by Editor level users.
* CHANGED: More options for dummy images
* CHANGED: Limited materials design palette in custom colour picker

* FIXED: Various small bugs on new installs and others
* FIXED: Excerpt trimming with dummy content
* FIXED: Stupid bug in presets where it was multi-selecting. (Chris the Coder, coding all nigh'; found a bug that made him cry; Once the bug was coded away; Chris the Coder dropped a freakin' expletive!)
* FIXED: Broken image in dummy content when offline. Now uses a nice sky blue placeholder image.
* FIXED: Issue with servers that have allow_url_fopen disabled

= 1.0.8.8 =
* FIXED: Transients not working with terms override
* DOING: Inbuilt stepped tutorials
* ADDED: Option to show/hide more advanced settings in Panels.
* ADDED: [mailto]you@email.com[/mailto] shortcode to obfuscate email addresses.

= 1.0.8.7 =
* CHANGED: In Galleries content, changed Specific IDs to Media Library and added message explaining how to use media categories plugins
* ADDED: Option to shortcodes to include taxonomy and terms as overrides. eg [architect mygallery tax="media_category" terms="people"]
* ADDED: Option to widget to override taxonomy and terms

= 1.0.8.6 =
* CHANGED: Debug constant
* FIXED: Rare warning for undefined titles

= 1.0.8.5 =
* FIXED: Various PHP notices
* FIXED: Sliders broken if no navigation chosen
* CHANGED: Stylings added for accordions and tabular


= 1.0.8.4 =
* FIXED: Meta fields not being wrapped in class since added strip_tags.

= 1.0.8.3 =
* CHANGED: More work on Page guides

= 1.0.8.2 =
* CHANGED: Extensive work on Page guides including automatic opening the first time

= 1.0.8.1 =
* FIXED: PHP errors when creating first Panel.

= 1.0.8 =
* FIXED: PHP messages when no Panel set
* CHANGED: Added more help info
* FIXED: Stupid git problem with filename changes when upper to lower.

= 1.0.7 =
* ADDED: More help info
* ADDED: Pageguide based step by step help and tutorial
* ADDED: Setting for left and right margins in Titles when using bullets

* CHANGED: Panel Layout option is now first setting in Section settings
* CHANGED: Layout options won't show until a Panel Layout is selected
* CHANGED: Blueprint Layout is now the first screen when adding a new Blueprint.

= 1.0.6 =
* FIXED: Retina images weren't being shown. Now both settings must be on to show. Changed defaults to on for local, off for global.
* CHANGED: Added info on Support tab for testing before contacting support.

= 1.0.5 =
* FIXED: Notices when adding new Panels and Blueprints on new intalls


= 1.0.4 =
* CHANGED: Added a class name arc-nav-thumb to navigation thumbs so Advanced Lazy Load can filter them
* CHANGED: Updated to Redux Metaboxes 1.3

* FIXED: Sections alignment problems when width in pixels
* FIXED: Blueprints right alignment problems
* FIXED: Added classes for page navigator styling to Headway Visual Editor
* FIXED: Caption styling
* FIXED: Tabular styling
* FIXED: Panel styling not applied correctly when feature outside components
* FIXED: Tightened CSS specificity so themes shouldn't override specific Architect styling
* FIXED: Bug in the CSS cahce generation that wasn't generating for all Panels and Blueprints

* REMOVED: Page builder! Ugh! Wasn't working when other loops on the page, like recent posts widgets. Will fix before global release.

= 1.0.3 =
* FIXED: Missing Featured Video metabox
* FIXED: Another CSS cascade problem. This time with .hentry
* FIXED: Lightbox not working on background images

= 1.0.2 =
* ADDED: %id% as an option in meta mainly for passing id into shortcodes - which is really cool! e.g. In Woo Commerce: [add_to_cart id="%id%"]

* CHANGED: Trimmed some fat to get zip below 2MB
* CHANGED: Upgraded Isotope to v2.1

* FIXED: Blueprints weren't using default styling
* FIXED: More PHP5.2 errors
* FIXED: Alignment problem when feature left/right outside components
* FIXED: Page builder not showing Blueprints that are to appear after the Original Content.
* FIXED: Styling not applied to images if outside components
* FIXED: PHP notices generated at activation
* FIXED: Masonry layout scrambled in Chrome, Safari and Opera when Panels have iamges
* FIXED: Can now have multiple masonry Blueprints on a page.

= 1.0.1 =
* FIXED: Doofus error! i.e. I accidentally removed a path name that then threw a fatal error. :S

= 1.0.0 =

* FIXED: Finally dinky-di fixed the Panels Design not showing if Styling turned off
* FIXED: More bug fixes and performance tweaks
* FIXED: Now PHP 5.2 compatible

* CHANGED: Updating system for v1 and Headway compatibility


== Upgrade Notice ==
Various fixes and enhancements. See changelog

== Possible Todos ==
TODO: Add a default single post/page Blueprint;  Add codex links;
TODO: Add option for custom content within Blueprints
TODO: Replace curl with wp_remote_get
TODO: Remove shortcode Defaults blocking message.
TODO: Add min specs for max input vars
TODO: Add description option to archive pages
TODO: Fix images cropping on iPhone?
TODO: Add Unsplash as a source for dummy photos
TODO: Add a full backup option for Blueprints
TODO: Add a backup and restore option for all settings
TODO: Add wireframe view?
TODO: Add Jetpack sitemap support https://developer.jetpack.com/2016/01/28/sitemaps-add-custom-post-type-support/
TODO: Transition to Codestar framework (less than 1MB footprint in zip!)
TODO Architect Galleries custom post type
TODO: * FIX: Small images scaling up
TODO: * FIX: stickies possibly showing incorrectly in Tabular
TODO: * FIX: filler image not cropping
TODO: * FIX: Cache not clearing on install/update
TODO: Implement srcset. See wp_get_attachment_image_srcset
TODO Update tutorials
TODO: Investigate filters etc having no effect on Search results
TODO: Add Default/Custom button to Content Source
TODO:? Advanced Featured Images module
TODO: Focal point tute
TODO: Add users as content source
TODO:? Add any fields to custom field filtering
TODO: Investigate CSS Grid
TODO: Rework fields so all generic
