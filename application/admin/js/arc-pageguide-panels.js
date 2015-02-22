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

    }
);





