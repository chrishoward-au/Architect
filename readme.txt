=== Architect: A WP Content Layout Framework ===
Contributors: chrishoward
Tags: content,display,posts,pages,gallery,slider,tabs,tabbed,tabular,widget,hooks
Requires at least: 3.5.0
Tested up to: 4.2.0
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Plugin URI: http://architect4wp.com

Go beyond the limitations of the layouts in the theme you use to easily build any content layouts for it. E.g. grids, sliders, lists, galleries, tables etc.

== Description ==
Most themes give you great looking layouts for your content, but are usually limited to what that theme provides. Frameworks give you control, but often require getting your hands dirty.

Architect breaks down both those barriers.

What if you love your theme, but just want the content layout of the homepage to be done your way? Architect solves that.

What if you love your theme framework but want an easy way to build the content layouts without having to cut code? Architect solves that.

With Architect you can build the layouts your theme doesn't provide - and all from one plugin. No longer do you need a plugin for sliders, another for magazine grids, another for tabbed content or another for displaying photo galleries.


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

= Do you have a demo version? =
Yes. A "lite" version that displays only posts is available on request to support@pizazzwp.com

= Does a licence have an expiry =
Yes. 12 months.

= How many sites can I install Architect on? =
As per Headway licencing agreement

= Can I install Architect on client sites using my licence? =
As per Headway licencing agreement

= If I deactivate Architect, will I lose all my Panels and Blueprints =
No. We don't delete any of your data. In the future a method will be provided for you to remove all Architect data if you really need to.

== Known issues ==
* Autoplay slideshows reverse at the end instead of looping back to the start
* Importing Blueprints, you will have to redo filters. This is a limitation of Redux not Architect.
* Videos don't autopause when changing slides
* Retina images currently only generates 2x

== Support ==

For support, please send an email to support@pizazzwp.com


== Screenshots ==

1. Architect Blueprint - Layout design
2. Slider example


== Changelog ==

= 1.1.4 =
* FIXED: Titles not selectable for styling in the Headway Visual Editor Design Mode

= 1.1.3 =
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
