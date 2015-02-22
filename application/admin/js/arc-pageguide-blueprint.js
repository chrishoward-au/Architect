/**
 * Created by chrishoward on 13/02/15.
 */

jQuery( document ).ready( function ()
{
  var blueprints_pg = tl.pg.init( {
        steps_element: '#tlyPageGuide',
        auto_refresh: true,
        pg_caption: '<span class="title">Blueprints Guide</span>',
        refresh_interval: 250          }
  );
  var blueprints_welcome = jQuery.cookie('blueprints_welcome');
  if (typeof blueprints_welcome === "undefined") {
    blueprints_pg.open();
    jQuery.cookie('blueprints_welcome','seen',9999);
  }
} );





