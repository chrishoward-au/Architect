=== Architect: A WP Content Layout Framework ===
Contributors: chrishoward
Tags: content,display,posts,pages,gallery,slider,tabs,tabbed,tabular,page builder,widget,hooks
Requires at least: 3.5.0
Tested up to: 4.1.0
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Plugin URI: http://architect4wp.com

Go beyond the limitations of the layouts in the theme you use to easily build any content layouts for it. E.g. grids, sliders, lists, galleries etc.

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

You can then display those Blueprints using widgets, shortcodes, action hooks, template tags, page builder, Headway blocks and even the WP Gallery shortcode.

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
* Page builder

== Frequently Asked Questions ==

= What themes is Architect compatible with =
Architect should work with any theme but some will take more effort than others, especially in terms of the look and layout. To that end, we provide the means to match CSS classes and create your own panel definitions.

= Is Architect compatible with all plugins =
The short answer is "Unlikely". No plugin can hope to be compatible with all other plugins (there are more than 36,000 of them!) but we do aim to be compatible with the more popular ones. We will endeavour to rectify any incompatibilities found; however, it's also not uncommon for the root cause to be the conflicting plugin. Always make sure you are running the latest version of your plugins.

= Is Architect WPML compatible =
Yes. Architect has been successfully tested with WPML.

= Is Architect one of those "page builder" plugins I'm hearing about =
Architect is much more than a "page builder", a popular genre of plugins. It includes a page builder as a method to display your Blueprints but Architect also provides six other ways to display your Blueprints. With Architect, the layouts are built independent of the page. How they are places on the page, and thus the page is built, is up to you. That could be shortcodes, widgets, template tags, action hooks or blocks (Headway).

= Do you provide refunds =
Yes! We're pretty flexible on refunds, allowing 60 days. But we would like you to make sure you've done everything possible to get Architect to work to your requirements, especially seeking support on our help desk.

= Is Architect for WordPress novices? =
The short answer is "No". We advise you get to know the theme you have chosen before embarking on the Architect adventure. Learn about posts, pages, taxonomies, custom post types, shortcodes and other more technical things like page templates, because ultimately, this is the language of Architect.

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

== Support ==

For support, please send an email to support@pizazzwp.com


== Screenshots ==

1. Architect Panel Designer showing magazine style article design
2. Post on front end using magazine style article design
3. Architect Blueprint - Content selection. 
4. Architect Blueprint - Layout design
5. Slider example

== Changelog ==

= 1.0.2 =
* FIXED: Blueprints weren't using default styling

= 1.0.1 =
* FIXED: Doofus error! i.e. I accidentally removed a path name that then threw a fatal error. :S

= 1.0.0 =

* FIXED: Finally dinky-di fixed the Panels Design not showing if Styling turned off
* FIXED: More bug fixes and performance tweaks
* FIXED: Now PHP 5.2 compatible

* CHANGED: Updating system for v1 and Headway compatibility


== Upgrade Notice ==
Various fixes and enhancements. See changelog
