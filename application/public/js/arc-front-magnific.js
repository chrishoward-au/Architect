jQuery( document ).ready( function ()
{
    "use strict";
    var arcSections = jQuery( '.pzarchitect .pzarc-section' );
    var arcRSIDs = '';
    arcSections.each( function ( i )
    {
        arcRSIDs = this.id;
        var arcLightbox = jQuery( 'a.lightbox-' + arcRSIDs );
        if ( arcLightbox.length > 0 )
        {
            arcLightbox.magnificPopup( {
                type: 'image',
                gallery: {enabled: true}
            } );

        }
    } );
} );