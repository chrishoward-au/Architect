/*global jQuery, document, redux_change */

(function ( $ )
{
    'use strict';

    $.redux = $.redux || {};

    $( document ).ready( function ()
    {
        $.redux.spectrum();
    } );

    $.redux.spectrum = function ()
    {
        $( '.redux-spectrum-init' ).spectrum( {
            showAlpha: true,
            showInput: true,
            allowEmpty: true,
            showPalette: true,
            showButtons: false,
            palette: [],
            localStorageKey: "redux.spectrum",
            showInitial: true,
            preferredFormat: "hex"
        } );

        $( '.redux-spectrum' ).on( 'change', function ()
        {
            var t = $( this ).spectrum( "get" );
            var tcs = (t ? t.toRgbString() : '');
            jQuery( this ).val( tcs );
        } );

        $( '.redux-spectrum' ).on( 'focus', function ()
        {
            $( this ).data( 'oldcolor', $( this ).val() );
        } );
//
//        $('.redux-spectrum').on('keyup', function() {
//            var value = $(this).val();
//            var color = redux_spectrum_validate(this);
//            var id = '#' + $(this).attr('id');
//
//            if (value === "transparent") {
//                $('#' + $(this).data('id')).parent().parent().find('.minicolors-swatch-color').attr('style', '');
//                $(id + '-transparency').attr('checked', 'checked');
//            } else {
//                $(id + '-transparency').removeAttr('checked');
//                if (color && color !== $(this).val()) {
//                    $(this).val(color);
//                }
//            }
//        });
//
        // Replace and validate field on blur
        $( '.redux-spectrum' ).on( 'blur', function ()
        {
            var t = $( this ).spectrum( "get" );
            var tcs = (t ? t.toRgbString() : '');
            jQuery( this ).val( tcs );
        } );
//
//        // Store the old valid color on keydown
//        $('.redux-spectrum').on('keydown', function() {
//            $(this).data('oldkeypress', $(this).val());
//        });
//
//        $('.spectrum-transparency').on('click', function() {
//            // Getting the specific input based from field ID
//            var pfs = $(this).parent().parent().data('id');
//            var op = $(this).parent().parent().find('.minicolors-swatch-color').css('opacity').substring(0, 4);
//
//            if ($(this).is(":checked")) {
//
//                //Set data-opacity attribute to 0.00 when transparent checkbox is check
//                $('#' + $(this).data('id')).attr('data-opacity', '0.00');
//
//                //Set hidded input value alpha opacity to 0.00 when transparent checkbox is check
//                $('#' + pfs + '-alpha').val('0.00');
//
//                //Hide .minicolors-swatch-color SPAN when its check
//                $('#' + $(this).data('id')).parent().parent().find('.minicolors-swatch-color').css('display', 'none');
//            } else {
//
//                //might need to restore data-opacity attribute and hidden input alpha value when uncheck
//                $('#' + $(this).data('id')).attr('data-opacity', op);
//                $('#' + pfs + '-alpha').val(op);
//
//                //Unhide .minicolors-swatch-color SPAN when its check
//                $('#' + $(this).data('id')).parent().parent().find('.minicolors-swatch-color').css('display', '');
//            }
//        });
//
//        //Unhide .minicolors-swatch-color SPAN when its check on redux-spectrum input focus
//        $('.redux-spectrum').on('focus', function() {
//
//            var op = $(this).parent().find('.minicolors-swatch-color').css('opacity').substring(0, 4);
//
//            // re-store data-opacity value of the input field
//            $(this).attr('data-opacity', op);
//
//            // re-store alpha hidden input value (not really nescessary)
//            $('#' + $(this).parent().parent().data('id') + '-alpha').val(op);
//
//            //unhide .mini-swatch-color
//            $(this).parent().find('.minicolors-swatch-color').css('display', '');
//        });
    };
})( jQuery );

// Run the validation
function redux_spectrum_validate( field )
{
    var value = jQuery( field ).val();
    /*
     if (colourNameToHex(value) !== value.replace('#', '')) {
     return colourNameToHex(value);
     }
     */
    return value;
}