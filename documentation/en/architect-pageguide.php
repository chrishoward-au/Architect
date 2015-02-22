<?php

  wp_enqueue_script('jquery-pageguide');
  wp_enqueue_style('css-pageguide');
  wp_enqueue_script('jquery-cookie');

  wp_enqueue_script('jquery-pageguide-architect', PZARC_PLUGIN_APP_URL . 'admin/js/arc-pageguide-architect.js',null,true);
  //add_action('admin_footer', 'arc_architect_pageguide_welcome');

  function arc_architect_pageguide_welcome()
  {
    echo '
<div class="tlyPageGuideWelcome">
  <h2>'.__('Welcome to Architect.','pzarchitect').'</h2>
  <h3>'.__('Help & Support','pzarchitect').'</h3>
  <p>'.__('Have a look through these pages before beginning to get a feel for how Architect hangs together.

  Architect pages have a built in guide that helps you understand some of the key settings. Not all settings though, because that would be too overwhelming for an introduction.
  You can open the guide at any time by clicking the orange guide button in the top right of each screen.','pzarchitect').'
  <p>'.__('Note: Even with the guide enabled, you can still interact with the pages','pzarchitect').'</p>
  <p>'.__('Close this dialog and','pzarchitect').' <button class="tlypageguide_ignore">'.__('Reopen next time','pzarchitect').'</button>
    <button class="tlypageguide_dismiss">'.__('Don\'t show again','pzarchitect').'</button>
  </p>
</div>

</ul></div>
';
  }


  add_action('admin_footer', 'arc_architect_pageguide_layouts');
  function arc_architect_pageguide_layouts()
  {
    echo '
<ul style="display:none;" id="tlyPageGuide" data-tourtitle="Architect Help & Support">
  <li class="tlypageguide_bottom" data-tourtarget="#tlyPageGuideToggles">
    <div>'.__('
      <h1>Welcome to the Architect!</h1>
      <h3>Architect Guide</h3>
      <p>A button like this appears on most Architect screens and will automatically open when you view the pages the first time. Click it to open display more help for the page you are viewing. Click it again to hide it.</p>
      <p>The guides are made up of numbered items, which yuou can click on any of the numbers, or use the left right navigation on the left of this panel.</p>
      <p><strong>Your turn: Try it now on with the items on this page</strong></p>
      The great thing about the guides is you can keep them open and still interact with the Architect pages.<br>


    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_bottom" data-tourtarget=".pzarc-about-box .tabby.tabs">
    <div>'.__('<h3>Help & Support</h3>
  Look through each of these tabs for information that will help you get started, understand Architect better and access support.
    ','pzarchitect').'</div>
  </li>
  <li class="tlypageguide_bottom" data-tourtarget="a.arc-codex">
    <div>'.__('<h3>Online help</h3>
    Check out the ever expanding online help at the Architect Codex for help, tutorials and videos.
    ','pzarchitect').'</div>
  </li>

</ul>
';


  }
