(function ( $ )
{
    "use strict";
    init();
    function init()
    {
        var tabbed = jQuery( '.redux-container-tabbed li' );
        tabbed.each( function ()
        {
            var targets = jQuery( this ).data( 'targets' ).split( ',' );
            jQuery( targets ).each( function ()
            {
               // jQuery( this ).find( '.hndle' ).addClass( 'pzarcHeader' ).css( {'cursor': 'default'} ).removeClass( 'hndle' );
            } );

            jQuery( targets ).each( function ()
            {
                jQuery( this ).find( '.handlediv' ).hide();
            } );

            if ( jQuery( this ).hasClass( "active" ) )
            {
                jQuery( targets ).each( function ()
                {
                    jQuery( this ).show();
                } );

                jQuery( targets ).each( function ()
                {
                    jQuery( this ).find( 'ul.redux-group-menu li a' ).first().trigger( 'click' );
                } );
            }
            else
            {
                jQuery( targets ).each( function ()
                {
                    jQuery( this ).hide();
                } );
            }
        } );

    }
//    #redux-_architect-metabox-_panels_settings_general-settings
//    #redux-_architect-metabox-_panels_settings_general_settings

//    #redux-_architect-metabox-_panels_settings_general_settings
//    #redux-_architect-metabox-_panels_settings_general-settings

    jQuery( '.redux-container-tabbed li' ).on( 'click', function ( e )
    {
        var clickedThis = this;
        var targets = jQuery( clickedThis ).data( 'targets' ).split( ',' );

        jQuery( targets ).each( function ()
        {
            //console.log("Show: ",this);
            jQuery( this ).show();
        } );
        jQuery( targets ).each( function ()
        {
            // TODO: Which is worse? Select first tab? Or no tab?
            // jQuery( this ).find( 'ul.redux-group-menu li a' ).first().trigger( 'click' );
        } );
        jQuery( clickedThis ).addClass( "active" );



        var siblings = jQuery( clickedThis ).siblings();
        siblings.each( function ()
        {
            var t = this;
            // For each sibling, remove the active class
            jQuery( t ).removeClass( "active" );

            //For each sibling, hide its boxes
            //console.log(t);
            var sibling_targets = jQuery( t ).data( 'targets' ).split( ',');
            //console.log(sibling_targets);
            jQuery(sibling_targets).each( function ()
            {
                //console.log("Hide: ",this);
                //console.log(jQuery(this));
                jQuery( this ).hide();
            } );


        } );

    } );


})( jQuery );