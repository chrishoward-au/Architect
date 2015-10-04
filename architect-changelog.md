# Architect change history 

## 1.5.0 : 3 Oct2015 
* ADDED: Support for filtering and sorting in masonry layouts!! (Thanks, Jamie!)
* ADDED: Option to use Headway alternative titles. On by default.
* ADDED: Option to display author avatars (Thanks Courtney!)
* ADDED: [arcshare] shortcode for sharing links to each specific panel.
* ADDED: Links to specific slides using link from arcshare shortcode
* ADDED: Message on Blueprint editor for Headway users about Architect styling.
* ADDED: Option for responsive font sizes on titles (Thanks, Chiara!)
* ADDED: Option to set Dummy content to use your own set of images
* ---
* CHANGED: Tidied up and compressed images (using tinypng.com). Saved 300KB in zip file!
* CHANGED: Tightened specificity of panels class to minimize conflicts in nested blueprints (e.g. content blueprint with a shortcode blueprint within)
 *  ---
* FIXED: Masonry sorting on numeric and date custom fields now working
* FIXED: Only first custom taxonomy showing in meta fields
* FIXED: A few bugs and styling in Masonry filtering and sorting.
* FIXED: Transition speed not working
* FIXED: Bug where responsive font sizes for content not working for medium breakpoint
* FIXED: Errors in filters and sorting if chosen custom post type deactivated

## 1.4.9 : 16 Sept 2015
* FIXED: Pages instead of posts showing on Blog index
* CHANGED: Default posts per page for pagination to value set in WP Settings > Reading
 
## 1.4.8.2 
* ADDED: Option for specific filler image (thanks Cemil!)

## 1.4.8.1
* FIXED: Error displaying in Headway Visual Editor Design Mode when Defaults was content source.

## 1.4.8 : 9 Sept 2015 
* FIXED: Change at v1.4.3 broke single post display when using Defaults as content source.

## 1.4.7 : 4 Sept 2015 
* ADDED: Support for Shortcake a user friendly shortcode tool. Install the Shortcake (Shortcode UI) plugin and then click Add Media in Posts etc, then Insert Post Element and select the Architect Blueprint. For compatibility, there is a specific Shortcake shortcode, [architectsc]
* FIXED: Bug where if a WP gallery used in Blueprint content source no longer existed, an error would display

## 1.4.6 : 30-August-2015 
* FIXED: Make Header and Footers option doing nothing!
* FIXED: Panels min width doing nothing!
* ADDED: Option for fixed with panels. In conjunction with flexbox, this allows full width grids that nicely space
* ADDED: Option for panels justification when using fixed width panels.

## 1.4.5 
* CHANGED: Added a message to Presets selector to explain "Use styled" won't be styled if "Use Architect styling" is turned off.
* FIXED: function name conflict in EDD module
* ADDED: Showcase custom post type
* ADDED: Options in custom fields to use the post title or no field (allowing the use of just the prefix and suffix. Useful with links where you want a generic link text)
* ADDED: Category and tag columns to Snippets, Testimonials and Showcases listing screens.

## 1.4.4 
* FIXED: Snippets selector not sortable

## 1.4.3 
* FIXED: Wording of accordions option that controls if it's open or closed at startup
* CHANGED: Installed Presets list on Tools page not shown if empty
* FIXED: Problems with Defaults content source not displaying correct content when Overrides enabled

## 1.4.2 : 5 August 2015 
* CHANGED: BFI Thumbs options page removed (it is a duplicate of the Refresh Cache in Architect > Options)

## 1.4.1 : 4 August  2015
* FIXED: Possible security risk with SysInfo library

## 1.4.0 : 3 August 2015 
* ADDED: Option to alternate feature left/right when outside components
* ADDED: Option to float feature left/right when in components to close up space
* ADDED: Option to make a custom field display as an embedded url. E.g. YouTube link displays as video
* ADDED: Option to not use a dropdown for post lists. A text field is used instead where you then need to enter a specific ID(s). Required when lots of posts that kill the memory loading into the dropdown array. This affects the Posts and Galleries content sources only.
* CHANGED: All actions and filters prefixes changed from 'pzarc' to 'arc'
*  CHANGED: Message on update now - finally! - says Blueprint updated
* FIXED: Scaled cropping not working when using NextGen galleries 


## 1.3.7 
* ADDED: Accordion title styling to Headway Design Mode
* CHANGED: Tweaks to Lite version and messages
* CHANGED: Message on Headway Options page to only put HW purchased licences there.
* CHANGED: Much tidying up of files reduced zip size.
* CHANGED: Menu title now includes version number on hover
* FIXED: Custom taxonomies displayed in meta not showing comma separator
* FIXED: Error with uploads url not being https sometimes
* FIXED: Tabular column and table widths when content doesn't fill last cell in first row.

## 1.3.6 
* ADDED: Blueprint Preset importing
* ADDED: Check for custom Blueprint Presets in uploads folder /pizazzwp/architect/presets
* CHANGED: Added messages when licence activation fails in the Architect licence activation screen
* CHANGED: Column order in Blueprints listing
* FIXED: Sliders now respond to RTL
* FIXED: Accordions will display open/close indicator on left on RTL sites.

## 1.3.5 
* FIXED: Dummy images not working with lightbox
* FIXED: NextGen images not working with lightbox

## 1.3.4 
* FIXED: Nav align needed -webkit-justify-content rules for Safari

## 1.3.3 
* ADDED: Option to set HTML tag for post titles
* CHANGED: Preset selector is now always visible
* CHANGED: Small visual changes to Preset selector
* FIXED: Thumbnails in navigator not using Focal Point

## 1.3.2 : 27 June 2015
* FIXED: Turned off debugging code accidentally left on in EDD caller which prevented Manage WP remote updates from working.
 
## 1.3.1
* FIXED: Errors if custom fields set to display but not setup in Architect
* FIXED: get_posts called too early in Slide content type causing error in Marketplace plugin

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
