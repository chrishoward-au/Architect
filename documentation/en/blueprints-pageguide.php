<?php

  global $_architect_options;
  if (!empty($_architect_options['architect_hide_guides'])) {
    return;
  }

  wp_enqueue_script('jquery-pageguide');
  wp_enqueue_style('css-pageguide');
  wp_enqueue_script('jquery-cookie');

  wp_enqueue_script('jquery-pageguide-blueprint', PZARC_PLUGIN_APP_URL . 'admin/js/arc-pageguide-blueprint.js',null,true);


  add_action('admin_footer', 'arc_blueprints_pageguide_layouts');
  function arc_blueprints_pageguide_layouts()
  {
    echo '
<ul style="display:none;" id="tlyPageGuide" data-tourtitle="Getting started with Blueprints">

  <li class="tlypageguide_left" data-tourtarget="#tlyPageGuideToggles">
    <div>'.__('
      <h1>Welcome to the Architect Blueprint Designer!</h1>
      <h3>Using the guide</h3>
      This guide covers the more important settings in Blueprints. Additional help is built-in, via text beside or below fields, and via tooltips when you hover over the <div style="height:auto;width:auto;vertical-align:middle;color:white;background:#bbb;padding:2px 4px;border-radius:100%;font-size:14px;display: inline;position:static">?</div> symbol.
      <p><strong><em>Slider tutorial: There is a simple slider tutorial included in the steps of this guide. Simply follow these blue prompts.</em></strong></p>
      <h3>Page Guide</h3>
      The Page Guide button appears on many screens within Architect. It provides additional help and guidance. Click it to open or close it.
    ','pzarchitect').'</div>
  </li>


  <li class="tlypageguide_top" data-tourtarget="#titlewrap">
    <div>'.__('
      <h3>Title</h3>
      Like any WordPress post type, the Title is the first thing you need to enter. This is used to identify the Blueprint in places you would select from a list of Blueprints. e.g. The widget; the Headway Block.
      <p><strong><em>Slider tutorial: Enter a title now for your slider Blueprint</em></strong></p>
    ','pzarchitect').'</div>
  </li>


  <!-- Tabs -->
  <li class="tlypageguide_top" data-tourtarget="#redux-_architect-metabox-_blueprint_tabs_blueprints">
    <div>'.__('
      <h3>Blueprint Design</h3>This is where you choose what type of layout this Blueprint will be, i.e. grid, tabbed, slider, accordion, tabular or masonry, and set other aspects of its functionality and appearance.
    ','pzarchitect').'</div>
    <div>'.__('
      <h3>Content Select</h3>This is where you select the specific posts, pages or other content field_types to display within this Blueprint.
    ','pzarchitect').'</div>
    <div>'.__('
      <h3>Blueprint Styling</h3>This is for refining the styling of the Blueprint to match your theme. Blueprints by default have limited styling to avoid visually clashing with your theme.
    ','pzarchitect').'</div>
  </li>


<!-- General -->
  <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_short-name">
    <div>'.__('
      <h3>Short name</h3>This is mandatory and is used in shortcodes. e.g [architect my-first-blueprint]. It can only contain letters, numbers and dashes.
    ','pzarchitect').'</div>
  </li>

<!-- Layout -->

  <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-layout-mode">
    <div>'.__('<h3>Layout type</h3>This is the magic - the bit that determines what type of layout your Blueprint will be.
The options are: <em>Grid</em> (which is what you also use for single post layouts);
<em>Sliders</em> (Needs no explanation - very popular!;
<em>Tabbed</em> (very similar to sliders but with navigation that depends on clicking the tabs);
<em>Masonry</em> (like Pinterest is famous for);
<em>Tabular</em> (show your posts or pages in a columnular list. Great for some custom content field_types.);
<em>Accordion</em> (collapsible content areas);
Note: Only Section 1 can contain Sliders and Tabbed. <strong>Your turn: Select one to get started. <em>Select the Slider. We will keep the default settings for Sliders & Tabbed.</em></strong>
    ','pzarchitect').'</div>
  </li>

    <li class="tlypageguide_left" data-tourtarget="#section-table-_blueprints_section-0-panels-heading">
    <div>'.__('<h3>Limit Panels</h3>
      For most Blueprints you will probably limit the number of Panels shown. If you don\'t, all posts or pages will be shown, therefore, you probably will need to enable Pagination which is in <em>Content</em> > <em>Settings</em>.
      <strong><em>Slider tutorial: Set this to Yes</em></strong>
    ','pzarchitect').'</div>
    <div>'.__('<h3>Panels to show</h3>
      When you set the Panels to be limited, you can then choose how many to show.<strong><em>Slider tutorial: Set this to 5</em></strong>
    ','pzarchitect').'</div>
  </li>

    <li class="tlypageguide_left" data-tourtarget="#section-table-_blueprints_section-0-columns-heading">
    <div>'.__('<h3>Columns per breakpoint</h3>
      Here you can set not only the number of columns of Panels across, but how that changes for each breakpoint. Breakpoints are defined in <em>Architect</em> > <em>Options</em> > <em>Responsive</em>.<strong><em>Slider tutorial: Set each of these to 1</em></strong>
    ','pzarchitect').'</div>
  </li>

    <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-panels-margins">
    <div>'.__('<h3>Panels margins</h3>
      When you set multiple Panels across, you will need to set margins here to stop them butting up against each other. Percentage margin units work best for responsive designs.<strong><em>Slider tutorial: These should all be on zero.</em></strong>
    ','pzarchitect').'</div>
  </li>

  <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_blueprint-width">
    <div>'.__('<h3>Blueprint max width</h3>If you set the this to 100%, it will fill the width of the container element this Blueprint is displayed in. That might be the post, the wrapper,thhe sidebar, the body etc. However, images will only ever scale to the maximum width you set for the Feature images in the Panel. For some Blueprints, it is therefore necessary to set Blueprint width the same as that maximum width.
    <strong><em>Slider tutorial: Set this to 400px (unless you changed the default maximum image width for Feature images for your Panel)</em></strong>
    ','pzarchitect').'</div>
  </li>

  <!-- Contents -->
  <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_content-source">
    <div>'.__('
        This is where you choose the source of the content to show in each panel of your Blueprint.
        <strong>Your turn: Click through the different Content sources noting how the selection options change on this tab.</strong>
        <strong><em>Slider tutorial: Select the <em>Dummy</em> content source</em></strong>
    ','pzarchitect').'</div>
  </li>

  <!-- Styling -->
  <li class="tlypageguide_top" data-tourtarget="#redux-_architect-metabox-blueprint-stylings">
    <div>'.__('
        <em>By default, limited default styling is set for Blueprints so as not to risk visually clashing with your theme.</em><br>
        The various parts of a Blueprint can be styled here. Alternatively, you can write and use your own CSS stylesheet, or if you are using Headway, you can style in the Visual Editor Design mode.
        <strong>Your turn: Create a Panel first, use it in a Blueprint, display the Blueprint, then come back here and start styling the Panel.</strong>
    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_bottom" data-tourtarget="#tab-design">
    <div>' . __('
      <h3>Panel Layout</h3>This is where you design the layout of the content of the individual post, page or other content type.
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
    <div>' . __('
<h3>Feature location</h3>
WordPress has featured images. Architect extends that by adding Featured Videos. The Feature is either of these for the post/page. To choose which, look in the <em>Featured Images/Videos</em> tab.<br>
The <em>Feature location</em> allows you to choose where in the Panel to display the Feature.
<strong>Your turn: Click on the different options now and see how it changes the placement of the Feature. <em>Set the Feature to the Background.</em></strong>
    ', 'pzarchitect') . '</div>
  </li>


  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_settings_feature-type">
    <div>' . __('<h3>Feature Type</h3>
  Architect can show the Featured image or the Featured video (if enabled and set) in the Feature component. Switching between them here will reveal different settings for each. The image used in the Panels layout will also change to reflect what time of Feature is being used.
    ', 'pzarchitect') . '</div>
  </li>


  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-position">
    <div>' . __('<h3>Components area position</h3>Architect allows general control over the position of the components group, which can then be refined with further controls below.
    <strong><em>Slider tutorial: Set the <em>Components area position</em> to Bottom</em>
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
 <strong>Ensure the global setting in Architect Options is on as well.</strong>
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

  <!-- Styling -->
  <li class="tlypageguide_top" data-tourtarget="#redux-_architect-metabox-panels-styling">
    <div>' . __('<h3>Content Styling</h3>
        <em>By default, no stylings are set as Architect inherits your theme\'s styling as much as possible.</em><br> The various parts of a post/page can be styled here. Alternatively, you can write and use your own CSS stylesheet, or if you are using Headway, you can style in the Visual Editor Design mode.<strong>Your turn: Create a Panel first, use it in a Blueprint, display the Blueprint, then come back here and start styling the Panel.</strong>
    ', 'pzarchitect') . '</div>
  </li>

</ul>
';


  }
