=== Architect WP Content Display Framework ===
Contributors: chrishoward
Tags: content,display,posts,pages,gallery,slider
Donate link: http://
Requires at least: 3.5.0
Tested up to: 3.8
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily build grids, galleries, sliders and much more from various content sources.


== Description ==
Easily display your content in grids, tabs, sliders, galleries with sources like posts, pages, galleries, widgets, custom code, Headway blocks and custom content types.

The core components of UCD are Content selections, Cell designs, and Blueprint layouts.

To save time, and keep UCD modular, Content and Cells can be re-used in many Blueprints.

= Why use UCD? =
UCD greatly reduces the complexity of designing and developing mehtods to display your content. For example, if you are using the Twenty Thirteen and decide youu want a 3x3 grid of excerpts to display on the home page, you would have to code a page tempalte for that purpose.

With UCD, you code the shell of the blueprint, but build the rest in UCD and paste one very simple line of code into your page blueprint that calls and displays the UCD Blueprint.

If you want to display a gallery in a post, build the Content selection, Cell design and Blueprint, then place a simple shortcode in your post. You can even cheat a bit, and copy the image ids from a standard WP gallery if you want to override the defaults. See instructions here

== Installation ==
1. Activate the plugin through the Plugins menu in WordPress.

== Usage ==
Shortcode: [pzucd blueprint="yourblueprint" ids="1,2,3,4,5"]
Blueprint tag: echo pzucd('yourblueprint','1,2,3,4,5');
Widget:
Block:

== Frequently Asked Questions ==
= What themes is UCD compatible with =
UCD should work with any theme but some will take more effort than others, especially in terms of the look and layout. To that end, we provide the emans to match CSS classes and create your own cell definitions.

= Is UCD compatible with all plugins =
The short answer is "Unlikely". No plugin can hope to be compatible with all other plugins (there are more than 36,000 of them!) but we do aim to be compatible with the more popular ones. We will endeavour to rectify any incompatibilities found; however, it's also not uncommon for the root cause to be the conflicting plugin.

= Is UCD WPML compatible =
At this point in time, and to the best of our knowledge, yes!

= Do you provide refunds =
Yes! We're pretty flexible on refunds, allowing 60 days. But we would like you to make sure you've done everything possible to get UCD to work to your requirements, especially seeking support on our help desk.

= Is UCD for newbies? =
The short answer is "No". We advise you get to know the theme you have chosen before embarking on the UCD adventure. Learn about posts, pages, taxonomies, custom post types, shortcodes and other more technical things like page blueprints, because ultimately, this is the language of UCD.

= Do you have a trial version? =
No. Sorry. But we are working on it.

= Does a licence have an expiry =
Yes. 12 months.

= How many sites can I install UCD on? =
You may install UCD on an unlimited number of sites that you personally own, or if you are a business, the business owns.

= Can I install UCD on client sites using my licence? =
Yes. Although to ensure long term support if you are no longer in the industry, from both ourselves and any other WP developer, it is recommended they buy theirown licence.

= Do you have an other licence deals? =
At this stage, no. We're trying to keep licencing as simple as possible, hence licence for all.

== Screenshots ==
1. The screenshot description corresponds to screenshot-1.(png|jpg|jpeg|gif).
2. The screenshot description corresponds to screenshot-2.(png|jpg|jpeg|gif).
3. The screenshot description corresponds to screenshot-3.(png|jpg|jpeg|gif).

== Changelog ==
= 0.5 =
* A new name! Architect
* First public beta

= 0.4 =
* began small scale beta testing

== Upgrade Notice ==
= 0.5 =
* A new name! Architect
* First public beta