/**
 * Created by chrishoward on 13/02/15.
 */

jQuery( document ).ready( function ()
{
  var architect_pg = tl.pg.init( {
        steps_element: '#tlyPageGuide',
        auto_refresh: true,
        pg_caption: '<span class="title">Architect Guide</span>',
        refresh_interval: 250          }
  );
  var architect_welcome = jQuery.cookie('architect_welcome');
  if (typeof architect_welcome === "undefined") {
    architect_pg.open();
    jQuery.cookie('architect_welcome','seen',9999);
  }
} );





