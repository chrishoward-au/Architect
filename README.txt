=== Architect WP Content Display Framework ===
Contributors: chrishoward
Tags: content,display,posts,pages,gallery,slider
Donate link: http://
Requires at least: 3.5.0
Tested up to: 4.0.0
Stable tag: 0.8.4.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Go beyond the limits of the layouts in the theme you use, to easily build any content layouts for it. E.g. grids, sliders, lists, galleries etc.

== Description ==
Most themes give you great looking layouts for your content, but are usually limited to what that theme provides. Frameworks give you control, but often require getting your hands dirty.

Architect breaks down both those barriers.

What if you love your theme, but just want the content layout of the homepage to be done your way? Architect solves that.

What if you love your theme framework but want an easy way to build the content layouts without having to cut code? Architect solves that.

With Architect you can build the layouts your theme doesn't provide - and all from one plugin. No longer do you need a plugin for sliders, another for magazine grids, another for tabbed content or another for displaying photo galleries.

= How Architect works =
Displaying content with Architect is made of two components: Panels and Blueprints.

Panels allow you design a layout for the content itself.

Blueprints lets you design a layout of how those panels wil be displayed on the page, e.g. grids, sliders etc.

In Blueprints you also choose what content to display - e.g. posts, pages, galleries.

This allows Panels to be easily reusable and cuts down on duplication of effort. You might design a panel that is used in a featured posts slider, but that panel may also be used to display the top story.

You can then display those Blueprints using widgets, shortcodes, action hooks, template tags, Headway blocks and even the WP Gallery shortcode.

= Why use Architect? =
Architect greatly reduces the complexity of designing and developing methods to display your content. For example, if you are using the Twenty Thirteen and decide you want a 3x3 grid of excerpts to display on the home page, you would have to code a page template for that purpose.

With Architect, you code the shell of the page template, but build the rest in Architect and paste one very simple line of code into your page template that calls and displays the Architect Blueprint.

Blueprints can be displayed using widgets and shortcodes as well.

You can even override the design of the WP Gallery shortcode with your own Blueprint.

== Installation ==
1. Activate the plugin through the Plugins menu in WordPress.

== Usage ==
There are several ways to display Architect Blueprints.

* Shortcode: [architect blueprint="yourblueprint" ids="1,2,3,4,5"]
* Template tag: echo pzarc('yourblueprint','1,2,3,4,5');
* Widget
* Headway Block
* Action hooks
* Actions editor (Built into Architect, all you have to know is the name of the hook you want to use)
* WP Gallery shortcode override

== Frequently Asked Questions ==
= What themes is Architect compatible with =
Architect should work with any theme but some will take more effort than others, especially in terms of the look and layout. To that end, we provide the means to match CSS classes and create your own panel definitions.

= Is Architect compatible with all plugins =
The short answer is "Unlikely". No plugin can hope to be compatible with all other plugins (there are more than 36,000 of them!) but we do aim to be compatible with the more popular ones. We will endeavour to rectify any incompatibilities found; however, it's also not uncommon for the root cause to be the conflicting plugin.

= Is Architect WPML compatible =
At this point in time, and to the best of our knowledge, yes!

= Do you provide refunds =
Yes! We're pretty flexible on refunds, allowing 60 days. But we would like you to make sure you've done everything possible to get Architect to work to your requirements, especially seeking support on our help desk.

= Is Architect for newbies? =
The short answer is "No". We advise you get to know the theme you have chosen before embarking on the Architect adventure. Learn about posts, pages, taxonomies, custom post types, shortcodes and other more technical things like page blueprints, because ultimately, this is the language of Architect.

= Do you have a trial version? =
No. Sorry. But we are working on a "lite" version.

= Does a licence have an expiry =
Yes. 12 months.

= How many sites can I install Architect on? =
As per Headway licencing

= Can I install Architect on client sites using my licence? =
As per Headway licencing

= Support =
Support for the beta version is available at: [Architect Beta](http://architect4wp.com/beta)

For all other Pizazz support, please send an email to support@pizazzwp.com or access the support form in WP Admin> PizazzWP > About & Support

= Known issues =
This is BETA software. So there are many besides these ones!
* In tabbed navigation, Firefox ignores padding

== Screenshots ==
1. Architect Panel Designer
2. Architect Blueprint Designer
3. Output of various Architect Blueprints

== Changelog ==

= 0.8.4.8 =
* FIXED: Bug with saving panels giving message about missing functions. Sorry about that!

= 0.8.4.7 =
* UPDATED Redux metaboxes. Seem to display right now.

= 0.8.4.5 =
* ADDED: Dummy content type. Seriously!! Now your site doesn't need content to begin development. (Note: it's not yet working with pagination or navigator. These will happen  in time. It won't work with archive type pages and might not ever - dont' know if I've got enough tricks left up my sleeve!)

= 0.8.4.3 =
* ADDED: Automatically generates and uses retina versions of images!
* FIXED: Various bugs in panel designer.


= 0.8.4 =
* CHANGED: Image component is now called Feature.
* ADDED: Option to display Video as the Feature
* ADDED: Video code field added to Posts, Pages and Snippets.

= 0.8.3.1 =
* FIXED: Notice that would go away
* FIXED: Background images not showing when components position bottom
* CHANGED: Prettied up the panel previewer

= 0.8.3 =
* Updated Slick.js slider
* CHANGED: Merged image and background images settings and controls into one making image control vastly easier. HOWEVER, THIS WILL BREAK SOME SETTINGS IN PANELS AND BLUEPRINTS THAT YOU WILL NEED TO FIX UP. A message will show in WP Admin to explain.
* CHANGED: Panel selector in the Blueprints is now panel slug based rather than panel ID. This is necessary for export/import to work smoothly.
* CHANGED: Updated About page with infographic


= 0.8.1 =
* Refactored the renderer to be extensible

= 0.8.0 =
* PUBLIC BETA

= 0.7.9.7 =
* FIXED: Problem where WP keeps thinking Architect needs updating

= 0.7.9 =
* FIXED: Nav by thumbs
* FIXED: Responsive content font sizing
* ADDED: Heaps of HW Design Mode stylings
* ADDED: Page title option to HW VE overrides

= 0.7.8 =
* ADDED: Headway Visual Editor option for post ID overrides
* ENABLED: Custom taxonomies to show in meta area.

= 0.7.7 =
* ENABLED: Lightbox functionality

= 0.7.6 =
* FIXED: Left/right alignment of background images
* ENABLED: Excerpt options - length, truncation indicator, link text

= 0.7.5 =
* First pre beta

= 0.6 =
* Switched to Redux for metaboxes and options pages

= 0.5 =
* A new name! Architect


== Upgrade Notice ==
* Added custom taxonomies to Snippets

