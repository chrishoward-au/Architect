/**
 * Created by chrishoward on 13/02/15.
 */


jQuery( document ).ready( function ()
    {

      var panels_pg = tl.pg.init( {
            steps_element: '#tlyPageGuide',
            auto_refresh: true,
            pg_caption: '<span class="title">Panels Guide</span>',
            refresh_interval: 250          }
      );

      var panels_welcome = jQuery.cookie('panels_welcome');
      if (typeof panels_welcome === "undefined") {
        panels_pg.open();
        jQuery.cookie('panels_welcome','seen',9999);
      }

      var panels_pg_tute1 = tl.pg.init( {
            steps_element: '#tlyPageGuideTute1',
            auto_refresh: true,
            pg_caption: '<span class="title">Tutorial 1</span>',
            refresh_interval: 250
          }
      );
      var panels_pg_tute1 = tl.pg.init( {
            steps_element: '#tlyPageGuideTute2',
            auto_refresh: true,
            pg_caption: '<span class="title">Tutorial 2</span>',
            refresh_interval: 250
          }
      );
      var panels_pg_tute1 = tl.pg.init( {
            steps_element: '#tlyPageGuideTute3',
            auto_refresh: true,
            pg_caption: '<span class="title">Tutorial 3</span>',
            refresh_interval: 250
          }
      );

      var pg_toggles= jQuery('.tlypageguide_toggle');

      pg_toggles.on('click',function(e){
        console.log(this);
      })
    }
);





