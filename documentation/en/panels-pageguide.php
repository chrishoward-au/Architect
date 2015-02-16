<?php


  wp_enqueue_script('jquery-pageguide-panels', PZARC_PLUGIN_APP_URL . 'admin/js/arc-pageguide-panels.js',null,true);

  add_action('admin_footer', 'arc_panels_pageguide_welcome');

  function arc_panels_pageguide_welcome()
  {
    echo '
<div class="tlyPageGuideWelcome">
  <h2>'.__('Welcome to Architect Panels.','pzarchitect').'</h2>
  <p>'.__('Panels define how you want to layout the individual posts or pages you will display in a Blueprint.','pzarchitect').'</p>
  <p>'.__('Architect Panels have a built in guide that helps you understand some of the key settings. You can open it any time by clicking the orange "Panels Guide" button in the top right of the Panels screen. You can also','pzarchitect').'
     <button class="tlypageguide_start">',__('open the guide now.','pzarchitect').'</button></p>
     <p>'.__('The guide has a simple slider tutorial you can follow too.','pzarchitect').'
  <p>'.__('Note: Even with the guide enabled, you can still interact with the fields in Panels','pzarchitect').'</p>
  <p>'.__('Close this dialog and','pzarchitect').' <button class="tlypageguide_ignore">'.__('Reopen next time','pzarchitect').'</button>
    <button class="tlypageguide_dismiss">'.__('Don\'t show again','pzarchitect').'</button>
  </p>
</div>

</ul></div>
';
  }


  add_action('admin_footer', 'arc_panels_pageguide_layouts');
  function arc_panels_pageguide_layouts()
  {
    echo '
<ul style="display:none;" id="tlyPageGuide" data-tourtitle="Getting started with Panels">

  <li class="tlypageguide_bottom" data-tourtarget="#tab-design">
    <div>'.__('
      <h3>Panel Layout</h3>This is where you design the layout of the content of the individual post, pages or other content types.
    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_bottom" data-tourtarget="#tab-styling">
    <div>'.__('
      <h3>Panel Styling</h3>Go here to refine the styling of the Panel to match your theme. Panels by default have no styling, however, they should inherit much of it from your theme.
    ','pzarchitect').'</div>
  </li>

<!-- General -->
  <li class="tlypageguide_bottom" data-tourtarget="#_architect-_panels_settings_short-name">
    <div>'.__('<h3>hort name</h3>
      The Short name is mandatory and is used in the Blueprints to connect back to the Panel. <strong>Your turn: Enter a Short name now</strong>
    ','pzarchitect').'</div>
  </li>

  <!-- Design -->
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-to-show">
    <div>'.__('<h3>Components to show</h3>To enable different components of the post or page to show in the Panel, click their corresponding button.<br>The Custom components are actually groups of fields. In this way, you can have three groups each of unlimited number of custom fields.
If you enable any of the Custom groups, you will also need to set the <em>Number of custom fields</em> below and Update this Panel.
     <strong>Your turn: Enable and disable various components. <em>Enable Title, Excerpt and Feature only.</em></strong>
    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_preview">
    <div>'.__('<h3>Panel preview</h3>This is a simplified drag and drop layout editor. You can also resize the components from their right edge. When you do, the size will be displayed below.
<strong>Your turn: Practice dragging and resizing the components. <em>Resize Title and Excerpt to 100% wide</em></strong>

    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_feature-location">
    <div>'.__('<h3>Feature location</h3>WordPress has featured images. Architect extends that by adding Featured Videos. The Feature is either of these for the post/page. To choose which, look in the <em>Featured Images/Videos</em> tab.<br>
The <em>Feature location</em> allows you to choose where in the Panel to display the Feature.
<strong>Your turn: Click on the different options now and see how it changes the placement of the Feature. <em>Set the Feature to the Background.</em></strong>
    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-position">
    <div>'.__('<h3>Components area position</h3>Architect allows general control over the position of the components group, which can then be refined with further controls below.
    <strong><em>Set the <em>Components area position</em> to Bottom</em>
    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-widths">
    <div>'.__('<h3>Components are width</h3>Generally the components group is 100% of the Panel when its position is either top or bottom; however when you set the components to be left or right, you will need to reduce their width.
<strong>Your turn: Drag the slider back and forth and watch the components area shrink and expand. <em>Set the <em>Components area width</em> to 100%</em></strong>
    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-nudge-y">
    <div>'.__('<h3>Nudge components area up/down</h3> We can move the components area up or down, and left or right.
<strong>Your turn: Try moving the sliders to see the components area move.<em>Set <em>Nudge components are up/down</em> to 10%</em></strong>
    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_left" data-tourtarget="#_architect-_panels_design_components-nudge-x">
    <div>'.__('<h3>Nudege components area left/right</h3>Generally the components group is 100% of the Panel when its position is either top or bottom; however when you set the components to be left or right, you will need to reduce their width.
<strong>Your turn: Drag the slider back and forth and watch the components area shrink and expand.</strong>
    ','pzarchitect').'</div>
  </li>
  <!-- Styling -->
  <li class="tlypageguide_right" data-tourtarget="#redux-_architect-metabox-panels-styling">
    <div>'.__('<h3>Panels Styling</h3>
        <em>By default, no stylings are set as Architect inherits your theme\'s styling as much as possible.</em><br> The various parts of a Panel can be styled here. Alternatively, you can write and use your own CSS stylesheet, or if you are using Headway, you can style in the Visual Editor Design mode.<strong>Your turn: Create a Panel first, use it in a Blueprint, display the Blueprint, then come back here and start styling the Panel.</strong>
    ','pzarchitect').'</div>
  </li>


</ul>
';


  }
