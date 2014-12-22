/**
 * Created by chrishoward on 21/06/2014.
 */
jQuery( document ).ready( function ()
{

    //http://jsfiddle.net/g4AXp/

    jQuery( ".media-modal .attachment-details .thumbnail, .wp_attachment_holder img.thumbnail, .wp_attachment_holder .imgedit-crop-wrap img" ).attr( "title", "Double click to set focal point" ).css("cursor",'crosshair');

    var dst = "tr.compat-field-pzgp-focal-point input[type=\"text\"]";

    //Set up bindings
    jQuery( document ).on( "dblclick", ".media-modal .attachment-media-view img.details-image", function ( e )
    {
      process_coordinate_set( e, this, dst );
        document.getSelection().removeAllRanges();
    } );

  // Feature image selector

  jQuery(document ).on('dblclick', '.attachment-info .thumbnail-image' , function ( e )
  {
    process_coordinate_set( e, this, dst );
  } );

  // Edit Media screen
  jQuery( document ).on( 'dblclick',"div.wp_attachment_holder img.thumbnail" , function ( e )
  {
    process_coordinate_set( e, this, dst );
  } );

  //
  jQuery( document ).on('dblclick', "div.wp_attachment_holder .imgedit-crop-wrap img", function ( e )
  {
    process_coordinate_set( e, this, dst );
  } );

    function process_coordinate_set( e, src, dst_field )
    {
        var relX,relY;

        // Get mouse click coordinates
        if ( e.offsetX === undefined ) // this works for Firefox
        {
            relX = e.pageX - jQuery( src ).offset().left;
            relY = e.pageY - jQuery( src ).offset().top;
        }
        else                     // works in Google Chrome
        {
            relX = e.offsetX;
            relY = e.offsetY;
        }

        // get width and height of image
        var imgW = jQuery( src ).width();
        var imgH = jQuery( src ).height();
        // Calculate percentage coordinates
        var percX = Math.floor( relX / imgW * 100 );
        var percY = Math.floor( relY / imgH * 100 );
        var coords = percX + ',' + percY;

        jQuery( dst_field ).val( coords ).change();

    }

        // Draw a traget indicator which shows when hovering over the image.


} );
