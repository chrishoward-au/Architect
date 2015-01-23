# Architect: A WP Content Layout Framework #
**Contributors:** chrishoward  
**Tags:** content,display,posts,pages,gallery,slider,tabs,tabbed,tabular,page builder,widget,hooks  
**Requires at least:** 3.5.0  
**Tested up to:** 4.1.0  
**Stable tag:** 0.9.3.1  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  
**Plugin URI:** http://architect4wp.com  

Go beyond the limitations of the layouts in the theme you use, to easily build any content layouts for it. E.g. grids, sliders, lists, galleries etc.

## Description ##
Most themes give you great looking layouts for your content, but are usually limited to what that theme provides. Frameworks give you control, but often require getting your hands dirty.

Architect breaks down both those barriers.

What if you love your theme, but just want the content layout of the homepage to be done your way? Architect solves that.

What if you love your theme framework but want an easy way to build the content layouts without having to cut code? Architect solves that.

With Architect you can build the layouts your theme doesn't provide - and all from one plugin. No longer do you need a plugin for sliders, another for magazine grids, another for tabbed content or another for displaying photo galleries.

### How Architect works ###
Displaying content with Architect is made of two components: Panels and Blueprints.

Panels allow you design a layout for the content itself.

Blueprints lets you design a layout of how those panels wil be displayed on the page, e.g. grids, sliders etc.

In Blueprints you also choose what content to display - e.g. posts, pages, galleries.

This allows Panels to be easily reusable and cuts down on duplication of effort. You might design a panel that is used in a featured posts slider, but that panel may also be used to display the top story.

You can then display those Blueprints using widgets, shortcodes, action hooks, template tags, Headway blocks and even the WP Gallery shortcode.

### Why use Architect? ###
Architect greatly reduces the complexity of designing and developing methods to display your content. For example, if you are using the Twenty Thirteen and decide you want a 3x3 grid of excerpts to display on the home page, you would have to code a page template for that purpose.

With Architect, you code the shell of the page template, but build the rest in Architect and paste one very simple line of code into your page template that calls and displays the Architect Blueprint.

Blueprints can be displayed using widgets and shortcodes as well.

You can even override the design of the WP Gallery shortcode with your own Blueprint.

## Installation ##
1. Install and activate the plugin through the Plugins menu in WordPress.

## Usage ##
There are several ways to display Architect Blueprints.

* Shortcode: [architect blueprint="yourblueprint" ids="1,2,3,4,5"]
* Template tag: echo pzarchitect('yourblueprint','1,2,3,4,5');
* Widget
* Headway Block
* Action hooks (Insert a hook in your theme page template, then hook Architect to it)
* Actions editor (Built into Architect, all you have to know is the name of the hook you want to use)
* WP Gallery shortcode override
* Page builder

## Frequently Asked Questions ##

### What themes is Architect compatible with ###
Architect should work with any theme but some will take more effort than others, especially in terms of the look and layout. To that end, we provide the means to match CSS classes and create your own panel definitions.

### Is Architect compatible with all plugins ###
The short answer is "Unlikely". No plugin can hope to be compatible with all other plugins (there are more than 36,000 of them!) but we do aim to be compatible with the more popular ones. We will endeavour to rectify any incompatibilities found; however, it's also not uncommon for the root cause to be the conflicting plugin. Always make sure you are running the latest version of your plugins.

### Is Architect WPML compatible ###
Yes. Architect has been succesfully tested with WPML.

### Is Architect one of those "page builder" plugins I'm hearing about ###
Architect is much more than a "page builder", a popular genre of plugins. It includes a page builder as a method to display your Blueprints but Architect also provides six other ways to display your Blueprints. With Architect, the layouts are built independent of the page. How they are places on the page, and thus the page is built, is up to you. That could be shortcodes, widgets, template tags, action hooks or blocks (Headway).

### Do you provide refunds ###
Yes! We're pretty flexible on refunds, allowing 60 days. But we would like you to make sure you've done everything possible to get Architect to work to your requirements, especially seeking support on our help desk.

### Is Architect for WordPress novices? ###
The short answer is "No". We advise you get to know the theme you have chosen before embarking on the Architect adventure. Learn about posts, pages, taxonomies, custom post types, shortcodes and other more technical things like page templates, because ultimately, this is the language of Architect.

### Do you have a demo version? ###
Yes. A "lite" version that displays only posts is available on request.

### Does a licence have an expiry ###
Yes. 12 months.

### How many sites can I install Architect on? ###
As per Headway licencing

### Can I install Architect on client sites using my licence? ###
As per Headway licencing

### If I deactivate Architect, will I lose all my Panels and Blueprints ###
No. We don't delete any of your data. In the future a method will be provided for you to remove all Architect data if you really need to.

### Support ###

For support, please send an email to support@pizazzwp.com


## Screenshots ##

### 1. Architect Panel Designer showing magazine style article design ###
![1. Architect Panel Designer showing magazine style article design](http://ps.w.org/architect:-a-wp-content-layout-framework/assets/screenshot-1.png)

### 2. Post on front end using magazine style artcile design ###
![2. Post on front end using magazine style artcile design](http://ps.w.org/architect:-a-wp-content-layout-framework/assets/screenshot-2.png)

### 3. Architect Blueprint - Content selection.  ###
![3. Architect Blueprint - Content selection. ](http://ps.w.org/architect:-a-wp-content-layout-framework/assets/screenshot-3.png)

### 4. Architect Blueprint - Layout design ###
![4. Architect Blueprint - Layout design](http://ps.w.org/architect:-a-wp-content-layout-framework/assets/screenshot-4.png)

### 5. Slider example ###
![5. Slider example](http://ps.w.org/architect:-a-wp-content-layout-framework/assets/screenshot-5.png)


## Changelog ##

### 0.9.3.1 ###
* CHANGED: Each Blueprint now has a unique ID. This changes with every page refresh, so is for internal code use only. i.e. Don't try and use it with custom CSS

* FIXED: Tweaking performance
* FIXED: Caching issue where some caches may not use updated CSS
* FIXED: Can now display the same Blueprint slider on the same page and nav will still work. 

### 0.9.3 ###
* ADDED: Page Builder to WP Pages editor screen. This is a simplified way to add Blueprints to a specific page.

* CHANGED: Removed option for vertical thumbs slider since it's simply not working yet.

### 0.9.2.2 ###
* ADDED: Option to turn oiff query caching. Use if other caching is interfering

* FIXED: Sticky background default colour
* FIXED: Removed 100% height from sections coz breaking in some themes. Keep an eye out for dominoes
* FIXED: Masonry picking up wrong layout when in a shortcode
* FIXED: Focal point stopped work coz of get_post_meta quirk
* FIXED: WP Gallery override might look for non-existent gallery.

### 0.9.2.1 ###
* CHANGED: Rejigged Blueprint options. Pagination is now within Settings.
* CHANGED: Rejigged Panels options. Drag abd Drop designer is now first on that screen.

* FIXED: Thumbnails not scaling when theme is Headway
* FIXED: Panel Design not visible in Panels editing.

### 0.9.2 ###
* ADDED: Option when Feature is in content, to show it in the excerpt, content or both.
* ADDED: Option to only show excerpts when there is an author created one.
* ADDED: Duplicate button on Panels and Blueprints list screen
* ADDED: Number type and formatting to custom fields

* FIXED: Option to make content 100% wide when no feature wasn't working
* FIXED: WPML compatibility issue
* FIXED: Validation of Panel and Blueprint short names had stopped working
* FIXED: Issues with CSS specificity that affected Architect Blueprints in shortcodes in other Architect generated content e.g. single posts.
* FIXED: Bug when server is case sensitive

### 0.9.1.3 ###
* FIXED: Blueprint options screen may have broken with previous release. You may need to reselect Layout Type

### 0.9.1.2 ###
* CHANGED: Replaced textual Layout Types selection with icons
* CHANGED: Dropped purple and red/blue admin backgrounds

* FIXED: Query transient not always working for non-admins

### 0.9.1.1 ###
* FIXED: Snippets disappeared

### 0.9.1 ###
* ADDED: Content source type NextGen Gallery
* ADDED: Dimensions options for thumbnails
* ADDED: Option for thumb nav skip buttons to be square or circle

* CHANGED: Sections to use divs for HTML validity
* CHANGED: Removed use of flexbox in layout coz of IE10 incompatibility issues.
* CHANGED: Getting Started to Help & Support page
* CHANGED: Layout types now include sliders and tabbed.
* CHANGED: Pagination now its own item in general
* CHANGED: Removed Navigator from general and is now selected when Sliders or Tabbed is layout type
* CHANGED: Panel margins in Blueprints to include units selection. This currently renders Architect not working in IE8 (2% marketshare)
* CHANGED: Only one click to set focal point now
* CHANGED: Special cursor for focal point
* CHANGED: Vastly more user friendly content source selection

* FIXED: Focal point stopped working in Featured Image selector.
* FIXED : D.A.R bug in admin metabox tabs where the first ones weren't showing sometimes.
* FIXED: Focal Point accuracy on media viewer
* FIXED: A few small issues in sliders

### 0.9.0.2 ###
* CHANGED: Custom query overwrites $wp_query. This makes is_main_query() work, but could have other serious consequences.

### 0.9.0.1 ###
* CHANGED: Blueprint field in Actions Editor to a dropdown

### 0.9.0 ###
* ADDED: Layout mode Tabular (Many thanks to Matt Davis for this idea)
* ADDED: Layout mode Accordion.
* ADDED: Navigation type Labels which lets you give specific titles. 
* ADDED: Option to set typography units as px, em or rem
* ADDED: Meta fields, Accordion titles and Navigation Labels can now parse shortcodes. Use shortcodes for any PHP you want to run. In Nav Label shortcodes, use global $pzarc_post_id to get post ID.

* CHANGED: Refactored to allow easy creation of a Lite version
* CHANGED: Option to deactivate wrapping of Panel header/footer components.
* CHANGED: Rejigged the layout of the Blueprints settings with Content selection first

* FIXED: Uses classes from Styling Options as it should have already been doing

* UPDATED: Spectrum js to 1.5.2 (one of the colour pickers)


### 0.8.8 ###
* CHANGED: Upgraded Slick.js to 1.3.15
* FIXED: Cleaned up a few PHP warnings and notices in WP admin (Thanks, Corey)

### 0.8.7 ###
* ADDED: Tools menu
* ADDED: Rebuild CSS cache in Tools menu
* ADDED: Quick Start guide to About screen
* ADDED: Lots more help
* ADDED: Styling for active navigator item
* ADDED: Border radius styling to navigator items
* ADDED: Notice to clear CSS cache.
* CHANGED: Removed excess files causing the zip to be so big
* CHANGED: CSS is cached in db and single styles file then created.
* CHANGED: Titles and meta at start of components are now wrapped in a header tag to align with WP methods
* CHANGED: Meta at end of components are now wrapped in a footer tag to align with WP methods
* FIXED: Widgets not using short name
* FIXED: Masonry layout broken by css compression
* FIXED: Background CSS on thumbs nav not applied
* FIXED: Font weight not being applied in stylings

* NOTE: LOTS of work still required on navigator especially thumbs and vertical

### 0.8.6 ###
* CHANGED: Improved js on blueprints admin
* ADDED: Vertical navigator (still being refined)
* ADDED: Option for last slide behaviour
* CHANGED: Hide next/previous arrows on first/last slide
* CHANGED: CSS is now compressed before saving

### 0.8.5.4 ###
* FIXED: Nav arrows disappearing in slider
* CHANGED: Sliders will use Headway Alternate Title if available

### 0.8.5.3 ###
* CHANGED: Custom fields show some hidden fields, such as those in Woo Commerce

### 0.8.5.2 ###
* CHANGED: Using transients to improve performance

### 0.8.5.1 ###
* ADDED: Option to have no navigator showing when Navigator is navigation type.
* FIXED: Navigator interval being ignored
* FIXED: Navigator Pager clicks affecting all sliders on a page

### 0.8.5.0 ###
* FIXED: Custom fields not displaying
* FIXED: Dummy content navigator thumbs now match featured image
* CHANGED: Made navs extensible

### 0.8.4.8 ###
* FIXED: Bug with saving panels giving message about missing functions. Sorry about that!
* CHANGED: Modified how content types are managed to enable extensibility. i.e. you can code up your own content types and plug them into Architect.

### 0.8.4.7 ###
* UPDATED Redux metaboxes. Seem to display right now.

### 0.8.4.5 ###
* ADDED: Dummy content type. Seriously!! Now your site doesn't need content to begin development. (Note: it's not yet working with pagination or navigator. These will happen  in time. It won't work with archive type pages and might not ever - dont' know if I've got enough tricks left up my sleeve!)

### 0.8.4.3 ###
* ADDED: Automatically generates and uses retina versions of images!
* FIXED: Various bugs in panel designer.


### 0.8.4 ###
* CHANGED: Image component is now called Feature.
* ADDED: Option to display Video as the Feature
* ADDED: Video code field added to Posts, Pages and Snippets.

### 0.8.3.1 ###
* FIXED: Notice that would go away
* FIXED: Background images not showing when components position bottom
* CHANGED: Prettied up the panel previewer

### 0.8.3 ###
* Updated Slick.js slider
* CHANGED: Merged image and background images settings and controls into one making image control vastly easier. HOWEVER, THIS WILL BREAK SOME SETTINGS IN PANELS AND BLUEPRINTS THAT YOU WILL NEED TO FIX UP. A message will show in WP Admin to explain.
* CHANGED: Panel selector in the Blueprints is now panel slug based rather than panel ID. This is necessary for export/import to work smoothly.
* CHANGED: Updated About page with infographic


### 0.8.1 ###
* Refactored the renderer to be extensible

### 0.8.0 ###
* PUBLIC BETA

### 0.7.9.7 ###
* FIXED: Problem where WP keeps thinking Architect needs updating

### 0.7.9 ###
* FIXED: Nav by thumbs
* FIXED: Responsive content font sizing
* ADDED: Heaps of HW Design Mode stylings
* ADDED: Page title option to HW VE overrides

### 0.7.8 ###
* ADDED: Headway Visual Editor option for post ID overrides
* ENABLED: Custom taxonomies to show in meta area.

### 0.7.7 ###
* ENABLED: Lightbox functionality

### 0.7.6 ###
* FIXED: Left/right alignment of background images
* ENABLED: Excerpt options - length, truncation indicator, link text

### 0.7.5 ###
* First pre beta

### 0.6 ###
* Switched to Redux for metaboxes and options pages

### 0.5 ###
* A new name! Architect


## Upgrade Notice ##
Various fixes and enhancements. See changelog
