/* global redux_change */
(function ( $ )
{
    "use strict";
    init();
    function init()
    {
        var tabbed = jQuery( '.redux-container-tabbed li' );
        tabbed.each( function ()
        {
            var target = jQuery( this ).data( 'target' );
            if ( jQuery( this ).hasClass( "active" ) )
            {
                jQuery( target ).show();
                console.log(jQuery(target).find( 'ul.redux-group-menu li a' ).first().trigger("click"));
                jQuery( target ).find( 'ul.redux-group-menu li a' ).first().trigger( 'click' );
            }
            else
            {
                jQuery( target ).hide();
            }
        } );

    }

    jQuery( '.redux-container-tabbed li' ).on( 'click', function ( e )
    {
        var clickedThis = this;
        var target = jQuery( clickedThis ).data( 'target' );
        var parent = jQuery( clickedThis ).siblings();
        parent.each( function ()
        {
            jQuery( this ).removeClass( "active" );
            jQuery( jQuery( this ).data( 'target' ) ).hide();
        } );
        jQuery( target ).slideDown(300);
        jQuery( target ).find( 'ul.redux-group-menu li a' ).first().trigger( 'click' );
        jQuery( clickedThis ).addClass( "active" );

    } );


})( jQuery );