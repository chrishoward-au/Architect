# Architect change history 

## 1.3.0 25 June 2015
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

## 1.2.10  : 22 May 2015
* FIXED: Excerpts not going 100% wide when no thumb image
* CHANGED: Refactored sliders so devs can use their own
* ADDED: Option to hide archive prefix text when showing page title on an archive page
* CHANGED: If Architect is installed on a Headway themed site, Architect's own styling will be turned off as the default.

## 1.2.9 : 14 May 2015 
* FIXED: Date formatting for dummy content

### 1.2.8
* FIXED: Licence check error stopping pro features loading
* FIXED: Inbuilt lightbox still loading when alternate selected.

### 1.2.7
* CHANGED: Custom taxonomy terms selection is now a drop down populated with terms of the custom taxonomy.
* ADDED: Option to use alternate lightbox
* FIXED: whitescreen error when saving if PHP less than 5.3

### 1.2.6 
* ADDED: Option to have accordions closed on startup
* CHANGED: Licence check on WPMS using Headway to align with Headway validation system

### 1.2.5 
* ADDED: Option to exclude specific posts
* ADDED: Option to exclude specific pages
* ADDED: Option to exclude current post
* ADDED: Option to exclude current page
* FIXED: Post selector only showing 5 posts
* FIXED: White screen error when saving in EDD 
* FIXED: Fatal error on activating 1.2.4

### 1.2.4 
* FIXED: Animations stopped working at 1.2.2
* FIXED: Background images not animating

### 1.2.3 
* ADDED: Option to exclude margins from outer grid panels

### 1.2.2  
* FIXED: Animation errors if no animations set for content

### 1.2.1 
* FIXED: Blueprints not found message when using block display with no blueprints set for phone and tablet and viewing on tablet or phone

## 1.2.0 : 4 May 2015 
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
### 1.1.6 
*  ADDED: Additional filters and hooks for developers
*  ADDED: News feed to Help & Support
*  FIXED: Blueprint CSS may have been overly persistent on some caching systems.
*  CHANGED: Some text and help info for greater clarity
*  FIXED: Archive titles not displaying
*  ADDED: Styling for page titles in Blueprint Styling > Page
*  FIXED: Google fonts not rendering
*  ADDED: Wordpress custom fields settings to Snippets and Testimonials

### 1.1.5 
*  FIXED: Nothing showing for Accordions except when content is Dummy

### 1.1.4 
*  FIXED: Titles not selectable for styling in the Headway Visual Editor Design Mode
*  CHANGED: Spaces are stripped from css file name - just in case the short name validator allowed them
*  ADDED: Licencing for purchases from PizazzWP direct

### 1.1.3 : 7 April 2015
*  CHANGED: Blueprints are hidden until rendered. This is because their CSS loads at the bottom of the page (since it enqueues mid page) and can cause an ugly unstyled moment otherwise.
*  FIXED: Wrong classes for featured image in Headway
*  FIXED: Specific selection of posts, pages, snippets, slides, testimonials wasn't working
*  FIXED: Usability issues in Content Layout drag & drop when Feature Location set to "Outside Components"

### 1.1.2 : 4 April 2015 
*  FIXED: Rare install error about .sass-cache

### 1.1.1  : 2 April 2015
*  ADDED: Testimonials content type
*  ADDED: More presets. Spruced up some existing presets
*  ADDED: Option to turn off additional content types: Snippets, Testimonials and any future ones.
*  FIXED: Various small tweaks and fixes
*  CHANGED: Split the Body/Excerpt styling page into two tabs
*  CHANGED Using the simple prefix MYBLUEPRINT in the custom CSS fields of the Styling options, Architect will automatically substitute the correct class
*  FIXED: Custom field wrapper tag now applies to the actual field, not its contents
*  FIXED: Conflict with Redux 3.5

### 1.1.0 : 1 April 2015
*  ADDED: Presets Selector
*  ADDED: Architect builder 
*  ADDED: Export Blueprint
*  ADDED: Architect shortcode selector for post and page editor.
*  ADDED: Option for posts with no image to use lorempixel.
