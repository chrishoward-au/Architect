jQuery( document ).ready( function ()
{
    "use strict";

    // Active form validation
    jQuery( "#post" ).validationEngine();

//	jQuery('.pzucd-meta-boxes').tabs({});

    jQuery( '.cmb_metabox_description' ).each( function ()
    {
        var theparent = jQuery( this ).parent();
        jQuery( this ).remove().appendTo( theparent );
    } );

//    var tooltips = jQuery( '.redux_field_th .description' );
//    tooltips.each( function ()
//    {
//        var parent = jQuery( this ).parent();
//        var tooltip = jQuery( this ).text();
//        jQuery( parent ).append( '<span class="architect-help el-icon-question-sign"></span>' );
//        jQuery( parent ).find( '.architect-help' ).attr( 'title', tooltip );
//        jQuery( this ).remove();
//    } )
//
//    if ( jQuery().tipsy )
//    {
//        jQuery( '.architect-help' ).tipsy( {
//            fade: false,
//            gravity: 'e',
//            opacity: 1,
//            delayIn: 800,
//            html: true,
//            offset:20,
//            className: 'architect-tips'
//        } );
//    }


} );