<?php

  add_action('admin_footer', 'arc_blueprints_pageguide_welcome');

  function arc_blueprints_pageguide_welcome()
  {
    echo '
<div class="tlyPageGuideWelcome">
  <p>Here\'s a snappy modal to welcome you to my new page! pageguide is here to help you learn more.</p>
  <p>
    This button will launch pageguide:
    <button class="tlypageguide_start">let\'s go</button>
  </p>
  <p>
    This button will close the modal without doing anything:
    <button class="tlypageguide_ignore">not now</button>
  </p>
  <p>
    This button will close the modal and prevent it from being opened again:
    <button class="tlypageguide_dismiss">got it, thanks</button>
  </p>
</div>

</ul></div>
';
  }


  add_action('admin_footer', 'arc_blueprints_pageguide_layouts');
  function arc_blueprints_pageguide_layouts()
  {
    echo '
<ul style="display:none;" id="tlyPageGuide" data-tourtitle="Key settings">

  <li class="tlypageguide_bottom" data-tourtarget="#tab-layout">
    <div>
      Blueprint Layout is where you choose which Panel design to use for posts or page, and how you want to lay out those Panels.
    </div>
  </li>

  <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-panel-layout">
    <div>
      The first and most important thing to do is select a Panel layout. Once you do, more options will be presented.
    </div>
  </li>

    <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-panels-limited">
    <div>
      For most Blueprints you will probably limit the number of Panels shown. If you don\'t, all posts or pages will be shown, therefore, you probably will need to enable Pagination which is in Content > Settings
    </div>
  </li>

    <li class="tlypageguide_left" data-tourtarget="#section-table-_blueprints_section-0-columns-heading">
    <div>
      Here you can set not only the number of columns of Panels across, but how that changes for each breakpoint. Breakpoints are defined in Architect > Options > Responsive.
    </div>
  </li>

    <li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-panels-margins">
    <div>
      When you set multiple Panels across, you will need to set margins here to stop them butting up against each other. Percentage margin units work best for responsive designs.
    </div>
  </li>

  <!-- Contents -->
  <li class="tlypageguide_bottom" data-tourtarget="#tab-content">
    <div>
      Panels Content is where you select the specific posts or pages to display within this Blueprint\'s Panels. The one content selection is spread across all sections
    </div>
  </li>

  <li class="tlypageguide_bottom" data-tourtarget="#_architect-_blueprints_content-source">
    <div>
        This is where you choose the source of the content to show in the Panels. This source is applied to all three sections in the Blueprint (if you are using the second or third).
    </div>
  </li>

  <!-- Styling -->
  <li class="tlypageguide_bottom" data-tourtarget="#tab-styling">
    <div>
      Blueprints by default have limited styling. Use Blueprint Styling to refine the styling of the Blueprint to match your theme.
    </div>
  </li>

</ul>
';


  }
