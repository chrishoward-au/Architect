<?php

  global $_architect_options;
  if (!empty($_architect_options['architect_hide_guides'])) {
    return;
  }
  wp_enqueue_script('jquery-pageguide');
  wp_enqueue_style('css-pageguide');
  wp_enqueue_script('jquery-cookie');

  wp_enqueue_script('jquery-pageguide-panels', PZARC_PLUGIN_APP_URL . 'admin/js/arc-pageguide-panels.js', null, true);


  add_action('admin_footer', 'arc_panels_pageguide_layouts');
  function arc_panels_pageguide_layouts()
  {
    echo '
<ul style="display:none;" id="tlyPageGuide" data-tourtitle="Getting started with Panels">
  <li class="tlypageguide_left" data-tourtarget="#tlyPageGuideToggles :nth-child(1)">
    <div>' . __('
      <h1>Welcome to the Architect Panel Designer!</h1>
      <strong><em>There is a simple slider tutorial included in the steps of this guide and the one in Blueprints. Simply follow these blue prompts.</em></strong>
<h3>Page Guide</h3>
<p>The Page Guide button appears on many screens within Architect. It provides additional help and guidance. Click it to open or close it.</p>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_top" data-tourtarget="#titlewrap">
    <div>' . __('
      <h3>Title</h3>
      Like any WordPress post type, the Title is the first thing you need to enter. This is used to identify Panels when you select them in Blueprints.
      <p><strong><em>Enter a title now for your slider Panel</em></strong></p>
    ', 'pzarchitect') . '</div>
  </li>

  <li class="tlypageguide_bottom" data-tourtarget="#tab-design">
    <div>' . __('
      <h3>Panel Layout</h3>This is where you design the layout of the content of the individual post, page or other content type.
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_bottom" data-tourtarget="#tab-styling">
    <div>' . __('
      <h3>Panel Styling</h3>Go here to refine the styling of the Panel to match your theme. Panels by default have no styling, however, they should inherit much of it from your theme.
    ', 'pzarchitect') . '</div>
  </li>

<!-- General -->
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_settings_short-name">
    <div>' . __('<h3>Short name</h3>
      The Short name is mandatory and is used in the Blueprints to connect back to the Panel. <strong>Your turn: Enter a Short name now<em>Enter a Short name for this Panels layout</em></strong>
    ', 'pzarchitect') . '</div>
  </li>

  <!-- Design -->

  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-to-show">
    <div>' . __('<h3>Components to show</h3>To enable different components of the post or page to show in the Panel, click their corresponding button.<br>The Custom components are actually groups of fields. In this way, you can have three groups each of unlimited number of custom fields.
If you enable any of the Custom groups, you will also need to set the <em>Number of custom fields</em> below and Update this Panel.
     <strong>Your turn: Enable and disable various components. <em>Enable Title, Excerpt and Feature only.</em></strong>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_preview">
    <div>' . __('<h3>Panel preview</h3>This is a simplified drag and drop layout editor. You can also resize the components from their right edge. When you do, the size will be displayed below.
<strong>Your turn: Practice dragging and resizing the components. <em>Resize Title and Excerpt to 100% wide</em></strong>

    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_feature-location">
    <div>' . __('<h3>Feature location</h3>WordPress has featured images. Architect extends that by adding Featured Videos. The Feature is either of these for the post/page. To choose which, look in the <em>Featured Images/Videos</em> tab.<br>
The <em>Feature location</em> allows you to choose where in the Panel to display the Feature.
<strong>Your turn: Click on the different options now and see how it changes the placement of the Feature. <em>Set the Feature to the Background.</em></strong>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-position">
    <div>' . __('<h3>Components area position</h3>Architect allows general control over the position of the components group, which can then be refined with further controls below.
    <strong><em>Set the <em>Components area position</em> to Bottom</em>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-widths">
    <div>' . __('<h3>Components area width</h3>Generally the components group is 100% of the Panel when its position is either top or bottom; however when you set the components to be left or right, you will need to reduce their width.
<strong>Your turn: Drag the slider back and forth and watch the components area shrink and expand. <em>Set the <em>Components area width</em> to 100%</em></strong>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-nudge-y">
    <div>' . __('<h3>Nudge components area up/down</h3> We can move the components area up or down, and left or right.
<strong>Your turn: Try moving the sliders to see the components area move.<em>Set <em>Nudge components are up/down</em> to 10%</em></strong>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-nudge-x">
    <div>' . __('<h3>Nudge components area left/right</h3>Generally the components group is 100% of the Panel when its position is either top or bottom; however when you set the components to be left or right, you will need to reduce their width.
<strong>Your turn: Drag the slider back and forth and watch the components area shrink and expand.</strong>
    ', 'pzarchitect') . '</div>
  </li>

  <!-- Styling -->
  <li class="tlypageguide_right" data-tourtarget="#redux-_architect-metabox-panels-styling">
    <div>' . __('<h3>Panels Styling</h3>
        <em>By default, no stylings are set as Architect inherits your theme\'s styling as much as possible.</em><br> The various parts of a Panel can be styled here. Alternatively, you can write and use your own CSS stylesheet, or if you are using Headway, you can style in the Visual Editor Design mode.<strong>Your turn: Create a Panel first, use it in a Blueprint, display the Blueprint, then come back here and start styling the Panel.</strong>
    ', 'pzarchitect') . '</div>
  </li>

    <!-- Meta -->
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_meta1-config">
    <div>' . __('<h3>Meta 1,2,3 fields config</h3>
    Each of the Meta fields can be configured to show various meta data using special tags. Available tags are <em>%author%</em>, <em>%email%</em>, <em>%date%</em>, <em>%categories%</em>, <em>%tags</em>, <em>%commentslink%</em> and <em>%editlink%</em>. The meta fields can contain the HTML tags: p, br, span, strong and em.<br>
         For custom taxonomies, prefix the taxonomy with <em>ct:</em>. e.g. To display the Woo Testimonials category, you would use %ct:testimonial-category%.<br><br>
         <h4>Adding PHP to a meta field</h4>
         If you want to execute your own code to generate something additional to display in the meta field, you will need to set up a shortcode to do so and use it in the meta field. Your code will probably need to know the ID of the post. If so, you can pass the shortcode a special tag, <em>%id</em>.
         <br>For example, to display the WooCommerce add to cart button, you would add this shortcode to your meta field: <em>[add_to_cart id="%id%"]</em>
    ', 'pzarchitect') . '</div>
  </li>

  <!-- Content -->
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_manual-excerpts">
    <div>' . __('<h3>Display entered excerpts only</h3>
    <p>Because of the flexibility Architect gives you, you can display both Excerpts and Content in the same Panel. For example, you might use the excerpt as an article introduction that is displayed before the full content, like is common in magazines.</p>
    <p>In that situation, you wouldn\'t want to display WordPress generated excerpts on the occasions the excerpt field has been left blank for the post.</p>
    <p>That is one example of when you would enable this setting</p>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#section-table-_panels_design_content-responsive-heading">
    <div>' . __('<h3>Responsive settings</h3>
    <p>As the page scales on different devices, you might find the content becomes obtrusive. Architect provides two way to handle this:</p>
    <h3>Hide Content at breakpoint</h3>
    With this setting, once the screen size gets below the specified break point, the Content component will no longer be shown
    <h3>Use responsive font sizes</h3>
    Alternatively, you can set the Content component\'s font size to get smaller at lower breakpoints.
    ', 'pzarchitect') . '</div>
  </li>

  <!-- Features -->
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_settings_feature-type">
    <div>' . __('<h3>Feature Type</h3>
  Architect can show the Featured image or the Featured video (if enabled and set) in the Feature component. Switching between them here will reveal different settings for each. The image used in the Panels layout will also change to reflect what time of Feature is being used.
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_settings_image-focal-point">
    <div>' . __('<h3>Image cropping</h3>
<p>Cropping and image can cause undesired results. The most common being heads chopped off people because the code to crop the image used an arbitrary point on the image to crop from.</p>
<p>With Architect, this can be avoided by taking advantage of the "Respect Focal Point" functionality. This begins by setting a focal point when you upload your image.</p>
<p><a href="http://architect4wp.com/codex/setting-and-using-focal-points/" target="_blank">View the tutorial on setting Focal Points</a></p>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_settings_use-retina-images">
    <div>' . __('<h3>Use retina images</h3>
<p>If enabled, Architect will create double sized images for retina display. These will only be shown on retina screens. Currently Architect only creates images for @2x but will in the near future support @3x.</p>
 <strong>Ensure the global setting in Architect Options is on as well</strong>.
 <p>NOTE: This will make your site load slower on retina devices, so you may only want consider which panels you have it enabled on.</p>', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_image-max-dimensions">
    <div>' . __('<h3>Maximum image dimensions</h3>
<p>When setting the Maximum dimensions, it is important to set these to what you expect the maximum size they will ever been seen at. When only displaying one Panel across, this is pretty easy to know.</p>
<p>However, when using a grid of multiple columns, the image could be larger at a lower breakpoint than a higher one, so you will have to consider this. </p>
<p>If this is a Panel is to be used in a full screen width Blueprint, you maximum width will need to be at least as large as the commonest largest desktop - i.e. around 2600px.</p>
<p>In the future, Architect will support different Maximum dimensions for the different breakpoints.</p>
<p>Setting the Maximum dimensions too low will make the images smaller than the area set for Features. Setting it higher than necessary, will make for slower loading of the page than it should.</p>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_background-image-resize">
    <div>' . __('<h3>Effect on screen resize</h3>
<p>If the image can\'t be displayed fully within its container element, this setting controls how it is then treated.</p>
<p><em>Trim horizontally, retain height</em> will centre the image in the container, keep the same height, but any overflow left or right will be hidden.</p>
<p><em>Scale vertically and horizontally</em> will shrink the image to fit the width of the container, maintaining its proportions.</p>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_link-image">
    <div>' . __('<h3>Link to</h3>
<em>None</em> Clicking the image will do nothing.<br>
<em>Post</em> Clicking the image will take the reader to the corresponding post.<br>
<em>Attachment page</em> This will open the attachment page for the image, which is like a post page, but only shows the attachment.<br>
<em>Lightbox</em> This will open the image in a full screen popup.<br>
<em>Specific URL</em> This sets ALL images to a single URL you specify below.<br>
    ', 'pzarchitect') . '</div>
  </li>


</ul>
';


  }

//  add_action('admin_footer', 'arc_panels_pageguide_tute1');
  function arc_panels_pageguide_tute1()
  {
    echo '
<ul style="display:none;" id="tlyPageGuideTute1" data-tourtitle="Building an excerpts Panel">
  <li class="tlypageguide_top" data-tourtarget="#titlewrap">
    <div>' . __('
      <h1>Tutorial 1: Building an excerpts Panel</h1>
      Sorry, this one is yet to be done.
    ', 'pzarchitect') . '</div>
  </li>
</ul>
';


  }

//  add_action('admin_footer', 'arc_panels_pageguide_tute2');
  function arc_panels_pageguide_tute2()
  {
    echo '
<ul style="display:none;" id="tlyPageGuideTute2" data-tourtitle="Building a slideshow Panel">
  <li class="tlypageguide_top" data-tourtarget="#titlewrap">
    <div>' . __('
      <h3>Title</h3>
      Like any WordPress post type, the Title is the first thing you need to enter. This is used to identify Panels when you select them in Blueprints.
      <p><strong><em>Enter a title now for your slider Panel</em></strong></p>
    ', 'pzarchitect') . '</div>
  </li>


<!-- General -->
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_settings_short-name">
    <div>' . __('<h3>Short name</h3>
      <strong><em>Enter a Short name for the slider Panels layout</em></strong>
    ', 'pzarchitect') . '</div>
  </li>

  <!-- Design -->

  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-to-show">
    <div>' . __('<h3>Components to show</h3>
     <strong><em>Enable Title, Excerpt and Feature only.</em></strong>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_preview">
    <div>' . __('<h3>Panel preview</h3>
<strong><em>Resize Title and Excerpt to 100% wide</em></strong>
Don\'t worry about the size of the Feature, the next step take care of that.

    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_feature-location">
    <div>' . __('<h3>Feature location</h3>
We want the Feature to fill the Panel.
<strong><em>Set the Feature to the Background.</em></strong>

    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-position">
    <div>' . __('<h3>Components area position</h3>
    <strong><em>Set the <em>Components area position</em> to Bottom</em>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-widths">
    <div>' . __('<h3>Components area width</h3>
<strong><em>Set the <em>Components area width</em> to 100%</em></strong>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-nudge-y">
    <div>' . __('<h3>Nudge components area up/down</h3>
<strong><em>Set <em>Nudge components are up/down</em> to 10%</em></strong>
    <p>Switch to the <em>Featured Images/Videos</em> tab on the left.</p>

    ', 'pzarchitect') . '</div>
  </li>



  <!-- Features -->
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_settings_feature-type">
    <div>' . __('<h3>Feature Type</h3>
<strong><em>Set the Feature Type to Images</em></strong>

    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_settings_image-focal-point">
    <div>' . __('<h3>Image cropping</h3>
<p>Cropping and image can cause undesired results. The most common being heads chopped off people because the code to crop the image used an arbitrary point on the image to crop from.</p>
<p>With Architect, this can be avoided by taking advantage of the "Respect Focal Point" functionality. This begins by setting a focal point when you upload your image.</p>
<p><a href="http://architect4wp.com/codex/setting-and-using-focal-points/" target="_blank">View the tutorial on setting Focal Points</a></p>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_settings_use-retina-images">
    <div>' . __('<h3>Use retina images</h3>
<p>If enabled, Architect will create double sized images for retina display. These will only be shown on retina screens. Currently Architect only creates images for @2x but will in the near future support @3x.</p>
 <strong>Ensure the global setting in Architect Options is on as well</strong>.
 <p>NOTE: This will make your site load slower on retina devices, so you may only want consider which panels you have it enabled on.</p>', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_image-max-dimensions">
    <div>' . __('<h3>Maximum image dimensions</h3>
<p>When setting the Maximum dimensions, it is important to set these to what you expect the maximum size they will ever been seen at. When only displaying one Panel across, this is pretty easy to know.</p>
<p>However, when using a grid of multiple columns, the image could be larger at a lower breakpoint than a higher one, so you will have to consider this. </p>
<p>If this is a Panel is to be used in a full screen width Blueprint, you maximum width will need to be at least as large as the commonest largest desktop - i.e. around 2600px.</p>
<p>In the future, Architect will support different Maximum dimensions for the different breakpoints.</p>
<p>Setting the Maximum dimensions too low will make the images smaller than the area set for Features. Setting it higher than necessary, will make for slower loading of the page than it should.</p>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_background-image-resize">
    <div>' . __('<h3>Effect on screen resize</h3>
<p>If the image can\'t be displayed fully within its container element, this setting controls how it is then treated.</p>
<p><em>Trim horizontally, retain height</em> will centre the image in the container, keep the same height, but any overflow left or right will be hidden.</p>
<p><em>Scale vertically and horizontally</em> will shrink the image to fit the width of the container, maintaining its proportions.</p>
    ', 'pzarchitect') . '</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_link-image">
    <div>' . __('<h3>Link to</h3>
<em>None</em> Clicking the image will do nothing.<br>
<em>Post</em> Clicking the image will take the reader to the corresponding post.<br>
<em>Attachment page</em> This will open the attachment page for the image, which is like a post page, but only shows the attachment.<br>
<em>Lightbox</em> This will open the image in a full screen popup.<br>
<em>Specific URL</em> This sets ALL images to a single URL you specify below.<br>
    ', 'pzarchitect') . '</div>
  </li>
</ul>
<!-- Put some space sin so info box doesn\'t cover steps -->
<!-- This is only needed once per page -->
<br><br><br><br>
<br><br><br><br>
';


  }
 // add_action('admin_footer', 'arc_panels_pageguide_tute3');
  function arc_panels_pageguide_tute3()
  {
    echo '
<ul style="display:none;" id="tlyPageGuideTute3" data-tourtitle="Building a Panel for an image gallery">
  <li class="tlypageguide_top" data-tourtarget="#titlewrap">
    <div>' . __('
      <h1>Tutorial 3: Building a Panel for an image gallery</h1>
    ', 'pzarchitect') . '</div>
  </li>
</ul>
';


  }
