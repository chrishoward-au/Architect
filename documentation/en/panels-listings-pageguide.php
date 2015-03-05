<?php
  global $_architect_options;
  if (!empty($_architect_options['architect_hide_guides'])) {
    return;
  }

  wp_enqueue_script('jquery-pageguide');
  wp_enqueue_style('css-pageguide');
  wp_enqueue_script('jquery-cookie');

  wp_enqueue_script('jquery-pageguide-panel-listing', PZARC_PLUGIN_APP_URL . 'admin/js/arc-pageguide-panel-listing.js',null,true);


  add_action('admin_footer', 'arc_panel_pageguide_listings');
  function arc_panel_pageguide_listings()
  {
    echo '
<ul style="display:none;" id="tlyPageGuide" data-tourtitle="About Panels">

  <li class="tlypageguide_bottom" data-tourtarget="h2">
    <div>'.__('
      <h1>Welcome to the Architect Panels!</h1>
<h3>About Panels</h3>
<p>Architect Panels are where you design the layout of the content, that is, choosing how to display the titles, meta data, featured images, excerpts, content etc.</p>

<p>Documentation can be found throughout Architect or online at the <a href="'.PZARC_CODEX.'" targer="_blank">Architect Codex</a></p>
      <strong><em>Also, there is a simple slider tutorial included in the steps of this Blueprint designer guide which follows on from the one in the Panels designer guide. Simply follow these blue prompts.</em></strong>
      <p><strong>Your turn: Click on "Add New Panel design" to get started.</strong></p>
    ','pzarchitect').'</div>
  </li>

  <li class="tlypageguide_bottom" data-tourtarget="#tlyPageGuideToggles">
    <div>'.__('
<h3>Page Guide</h3>
<p>The Page Guide button appears on many screens within Architect. It provides additional help and guidance. Click it to open or close it.</p>
    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_bottom" data-tourtarget="#_blueprints_short-name">
    <div>'.__('
<h3>Blueprints short name</h3>
<p>The Blueprints short name is used in the Blueprints shortcode. e.g. [architect my-blueprint]</p>
    ','pzarchitect').'</div>
  </li>


</ul>
';


  }
