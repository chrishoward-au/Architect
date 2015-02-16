<?php

  wp_enqueue_script('jquery-pageguide-blueprint', PZARC_PLUGIN_APP_URL . 'admin/js/arc-pageguide-blueprint.js',null,true);
 // add_action('admin_footer', 'arc_blueprints_pageguide_welcome');

  function arc_blueprints_pageguide_welcome()
  {
    echo '
<div class="tlyPageGuideWelcome">
  <p>'.__('Welcome to Architect Blueprints.','pzarchitect').'</p>
  <p>'.__('Blueprints define how you want to layout your Panels, and what content they should contain.','pzarchitect').'</p>
  <p>'.__('Architect Blueprints have a built in guide that helps you understand some of the key settings. You can open it any time by clicking the orange "Blueprints Guide" button in the top right of the Blueprints screen. You can also','pzarchitect').'
     <button class="tlypageguide_start">',__('open the guide now.','pzarchitect').'</button></p>
  <p>'.__('Note: Even with the guide enabled, you can still interact with the fields in Blueprints','pzarchitect').'</p>
  <p>'.__('Close this dialog and','pzarchitect').' <button class="tlypageguide_ignore">'.__('Reopen next time','pzarchitect').'</button>
    <button class="tlypageguide_dismiss">'.__('Don\'t show again','pzarchitect').'</button>
  </p>
</div>

</ul></div>
';
  }


  add_action('admin_footer', 'arc_blueprints_pageguide_layouts');
  function arc_blueprints_pageguide_layouts()
  {
    echo '
<ul style="display:none;" id="tlyPageGuide" data-tourtitle="Important Blueprint settings">

  <!-- Layout -->
  <li class="tlypageguide_bottom" data-tourtarget="#tab-layout">
    <div>'.__('
      <h3>Blueprint Layout</h3>This is where you choose which Panel layout to use in your posts or page, and how you want to lay out those Panels, whether one or many, and gridded, tabbed, slider, accordion or tabular.
      <strong><em>You can now setup your slider Blueprint. Once you are finished, then to display it, add the Architect shortcode to a post or page of your choice. e.g. [architect my-blueprint] Replace "my-blueprint" with the shortname you gave yours.</em></strong>
    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_bottom" data-tourtarget="#tab-content">
    <div>'.__('
      <h3>Panels Content</h3>This is where you select the specific posts or pages to display within this Blueprint\'s Panels. The one content selection is spread across all sections
    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_bottom" data-tourtarget="#tab-styling">
    <div>'.__('
      <h3>Blueprint Styling</h3>This is for refining the styling of the Blueprint to match your theme. Blueprints by default have limited styling to avoid visually clashing with your theme.
    ','pzarchitect').'</div>
  </li>

<!-- General -->
  <li class="tlypageguide_bottom" data-tourtarget="#_architect-_blueprints_short-name">
    <div>'.__('
      <h3>Short name</h3>This is mandatory and is used in shortcodes. e.g [architect my-first-blueprint]. It can only contain letters, numbers and dashes.
    ','pzarchitect').'</div>
  </li>

  <li class="tlypageguide_bottom" data-tourtarget="#_architect-_blueprints_blueprint-width">
    <div>'.__('<h3>Blueprint max width</h3>If you set the this to 100%, it will fill the width of the container it is in. However, images will only ever scale to the maximum width you set for the Feature images in the Panel. For some Blueprints, it is therefore necessary to set Blueprint width the same as that maximum width.
    <strong><em>Set this to 400px (unless you changed the default maximum image width for Feature images for your Panel)</em></strong>
    ','pzarchitect').'</div>
  </li>

  <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-panel-layout">
    <div>'.__('
      <h3>Panels layout</h3>The most important thing to do is select a Panel layout. Once you do, more options will be presented.<strong>Your turn: Select a Panel layout to get started if you have created one. Otherwise create one first. <em>Select the Panel you created for the slider tutorial</em></strong>
    ','pzarchitect').'</div>
  </li>

  <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-layout-mode">
    <div>'.__('<h3>Layout type</h3>This is the magic - the bit that determines what type of layout your Blueprint will be.
The options are: <em>Grid</em> (which is what you also use for single post layouts);
<em>Sliders</em> (Needs no explanation - very popular!;
<em>Tabbed</em> (very similar to sliders but with navigation that depends on clicking the tabs);
<em>Masonry</em> (like Pinterest is famous for);
<em>Tabular</em> (show your posts or pages in a columnular list. Great for some custom content types.);
<em>Accordion</em> (collapsible content areas);
Note: Only Section 1 can contain Sliders and Tabbed. <strong>Your turn: Select one to get started. <em>Select the Slider. We will keep the default settings for Sliders & Tabbed.</em></strong>
    ','pzarchitect').'</div>
  </li>

    <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-panels-limited">
    <div>'.__('<h3>Limit Panels</h3>
      For most Blueprints you will probably limit the number of Panels shown. If you don\'t, all posts or pages will be shown, therefore, you probably will need to enable Pagination which is in <em>Content</em> > <em>Settings</em>.
      <strong><em>Set this to Yes</em></strong>
    ','pzarchitect').'</div>
  </li>
    <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-panels-per-view">
    <div>'.__('<h3>Panels to show</h3>
      When you set the Panels to be limited, you can then choose how many to show.<strong><em>Set this to 5</em></strong>
    ','pzarchitect').'</div>
  </li>

    <li class="tlypageguide_left" data-tourtarget="#section-table-_blueprints_section-0-columns-heading">
    <div>'.__('<h3>Columns per breakpoint</h3>
      Here you can set not only the number of columns of Panels across, but how that changes for each breakpoint. Breakpoints are defined in <em>Architect</em> > <em>Options</em> > <em>Responsive</em>.<strong><em>Set each of these to 1</em></strong>
    ','pzarchitect').'</div>
  </li>

    <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-panels-margins">
    <div>'.__('<h3>Panels margins</h3>
      When you set multiple Panels across, you will need to set margins here to stop them butting up against each other. Percentage margin units work best for responsive designs.<strong><em>These should all be on zero.</em></strong>
    ','pzarchitect').'</div>
  </li>

  <!-- Contents -->
  <li class="tlypageguide_bottom" data-tourtarget="#_architect-_blueprints_content-source">
    <div>'.__('
        This is where you choose the source of the content to show in the Panels. This source is applied to all three sections in the Blueprint (if you are using the second or third).<strong><em>Choose the Dummy content source</em></strong>.
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

</ul>
';


  }
