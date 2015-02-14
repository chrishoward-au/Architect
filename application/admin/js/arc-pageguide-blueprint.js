/**
 * Created by chrishoward on 13/02/15.
 */

jQuery( document ).ready( function ()
{
  tl.pg.init( {
    steps_element: '#tlyPageGuide',
    auto_refresh: true,
    pg_caption: '<span class="title">Blueprints Guide</span>',
    refresh_interval:250
  } );

  //var bpoverview = '<ul style="display:none;" id="tlyPageGuide" data-tourtitle="Blueprints Overview">    <li class="tlypageguide_top" data-tourtarget="#redux-_architect-metabox-_blueprint_tabs_blueprints"><div>Select what part of the Blueprit you want </div></li><li class="tlypageguide_left" data-tourtarget="#redux-_architect-metabox-_blueprints_layout-general-settings"><div>Here is the fourth item description. The number will appear below the element.</div><li class="tlypageguide_bottom" data-tourtarget="#_architect-_blueprints_content-source"><div>Here is the sixth item description. The number will appear below the element.</div></li></ul>';
  //jQuery( '.arc-bp-overview' ).on( 'click', function ()
  //{
  //
  //  jQuery( '.arc-pageguide-button.arc-bp-overview ' ).on( 'click', function ()
  //  {
  //    jQuery( '.arc-pageguide' ).html( function ()
  //    {
  //      return bpoverview;
  //    } );
  //    console.log( jQuery( '.arc-pageguide' ) );
  //    tl.pg.init({
  //      steps_element:'#tlyPageGuide',
  //      auto_refresh:true,
  //      pg_caption:'Guides'
  //    });
  //  } );
  //  var bpimportant = '<ul style="display:none;" id="tlyPageGuide" data-tourtitle="Blueprints Overview">    <li class="tlypageguide_top" data-tourtarget="#redux-_architect-metabox-_blueprint_tabs_blueprints"><div>Here is the second item description. The number will appear to the right of the element.</div></li><li class="tlypageguide_bottom" data-tourtarget="#tab-layout"><div>Here is the fifth item description. The number will appear below the element.</div></li><li class="tlypageguide_bottom" data-tourtarget="#tab-content"><div>Here is the fifth item description. The number will appear below the element.</div></li><li class="tlypageguide_bottom" data-tourtarget="#tab-styling"><div>Here is the fifth item description. The number will appear below the element.</div></li><li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-panel-layout"><div>Here is the first item description. The number will appear to the left of the element.</div></li><li class="tlypageguide_left" data-tourtarget="#_architect-_blueprints_section-0-panels-limited"><div>Here is the third item description. The number will appear above the element.</div></li><li class="tlypageguide_left" data-tourtarget="#redux-_architect-metabox-_blueprints_layout-general-settings"><div>Here is the fourth item description. The number will appear below the element.</div><li class="tlypageguide_bottom" data-tourtarget="#_architect-_blueprints_content-source"><div>Here is the sixth item description. The number will appear below the element.</div></li></ul>';
  //
//  tl.pg.init({ steps_element:'#tlyPageGuide_content' });
//  } );
} );





