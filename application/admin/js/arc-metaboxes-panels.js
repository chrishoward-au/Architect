jQuery( document ).ready( function ()
{
    "use strict";



    /**
     * Update status message and field data
     */
    function pzarc_update_status( cell_layout )
    {
        var showing = "";
        jQuery.each( cell_layout, function ( index, value )
        {
            if ( value.show )
            {
                showing = showing + " <strong>" + index + ":</strong> " + value.width + "% &nbsp;&nbsp;&nbsp;";
            }
        } );
        jQuery( "p.pzarc-states" ).html( showing );
        jQuery( 'input#_panels_design_preview-text' ).val( JSON.stringify( cell_layout ) );
    }

//***********************
// Process field values
//***********************


    // Hidden field containing the plugin url
    var plugin_url = jQuery( 'fieldset#_architect-_panels_design_preview .plugin_url' ).text();


    // Get the current order and widths from the hidden text field attached to preview layout box
    var cell_layout = jQuery.parseJSON( jQuery( 'input#_panels_design_preview-text' ).val() );

    pzarc_update_status( cell_layout );
    jQuery( 'input[name="_architect[_panels_design_thumb-position]"]' ).on( 'change', function (  )
    {
        var buttonClicked = this.value;
        pzarc_update_thumb_position( buttonClicked );
    } );


    // FEATURE LOCATION
    jQuery( 'input[name="_architect[_panels_design_feature-location]"]' ).on( 'click', function ( e )
    {
        var cell_layout = jQuery.parseJSON( jQuery( 'input#_panels_design_preview-text' ).val() );
        pzarc_update_feature( cell_layout );
        pzarc_update_tabs_to_show( e );
    } );

    // COMPONENTS TO SHOW
    jQuery( 'fieldset#_architect-_panels_design_components-to-show input' ).change( function ( e )
    {
        var cell_layout = jQuery.parseJSON( jQuery( 'input#_panels_design_preview-text' ).val() );
        pzarc_update_component_visibility( cell_layout, e );
        pzarc_update_tabs_to_show( e );
    } );


    // COMPONENTS POSITION
    jQuery( 'input[name="_architect[_panels_design_components-position]"]' ).on( 'click', function (  )
    {
        var cell_layout = jQuery.parseJSON( jQuery( 'input#_panels_design_preview-text' ).val() );
        pzarc_update_component_location( cell_layout );
    } );

    // PANEL HEIGHT TYPE
    jQuery( 'input[name="_architect[panels_settingds_panel-height-type]"]' ).on( 'click', function (  )
    {
       // var cell_layout = jQuery.parseJSON( jQuery( 'input#_panels_design_preview-text' ).val() );
    } );


    jQuery( 'fieldset#_architect-_panels_design_components-widths .redux-slider-label' ).on( "DOMSubtreeModified", function (  )
    {
        var cell_layout = jQuery.parseJSON( jQuery( 'input#_panels_design_preview-text' ).val() );
        pzarc_update_components_container_width( cell_layout );
    } );

    jQuery( 'fieldset#_architect-_panels_design_components-nudge-x .redux-slider-label' ).on( "DOMSubtreeModified", function (  )
    {
        var cell_layout = jQuery.parseJSON( jQuery( 'input#_panels_design_preview-text' ).val() );
        pzarc_update_components_nudge( cell_layout );
    } );

    jQuery( 'fieldset#_architect-_panels_design_components-nudge-y  .redux-slider-label' ).on( "DOMSubtreeModified", function ( )
    {
        var cell_layout = jQuery.parseJSON( jQuery( 'input#_panels_design_preview-text' ).val() );
        pzarc_update_components_nudge( cell_layout );
    } );

    /***************************
     *
     * sort
     *
     */
    jQuery( ".pzarc-dropzone .pzarc-content-area" ).sortable( {
        cursor: "move",
        opacity: 0.6,
        forceHelperSize: true,
        sort: function (  )
        {
            // gets added unintentionally by droppable interacting with sortable
            // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
            jQuery( this ).removeClass( "ui-state-default" );
        },
        update: function ( )
        {
            var cell_layout = jQuery.parseJSON( jQuery( 'input#_panels_design_preview-text' ).val() );
            cell_layout = pzarc_resort_components( cell_layout );
            pzarc_update_status( cell_layout );
        },

        /*************
         Do on create
         *************/
        create: function (  )
        {
            var element_html = [];
            element_html.title = '<span class="pzarc-draggable pzarc-draggable-title" title= "Post title" data-idcode=title style="display: inline-block;font-weight:bold;font-size:15px;"><span>This is the title</span></span>';

            element_html.meta1 = '<span class="pzarc-draggable pzarc-draggable-meta1 pzarc-draggable-meta"  title= "Meta info 1" data-idcode=meta1 style="font-size:11px;"><span>Jan 1 2013</span></span>';

            element_html.meta2 = '<span class="pzarc-draggable pzarc-draggable-meta2 pzarc-draggable-meta" title= "Meta info 2"  data-idcode=meta2 style="font-size:11px;"><span>Categories - News, Sport</span></span>';

            element_html.image = '<span class="pzarc-draggable pzarc-draggable-image"  title= "Featured image" data-idcode=image style="max-height:100px;overflow:hidden;"><span><img src="PZARC_PLUGIN_URL/shared/assets/images/sample-image.jpg" style="max-width:100%;"/></span></span>';
            element_html.image = element_html.image.replace( /PZARC_PLUGIN_URL/g, plugin_url );

            element_html.content = '<span class="pzarc-draggable pzarc-draggable-content" title= "Full post content"  data-idcode=content style="font-size:13px;"><span><img src="PZARC_PLUGIN_URL/shared/assets/images/sample-image.jpg" class="pzarc-align ' + jQuery( "select#_pzarc_layout-excerpt-thumb-cmb-field-0" ).val() + '" style="max-width:20%;"/><img src="PZARC_PLUGIN_URL/shared/assets/images/fireworks.jpg" style="max-width:30%;float:left;padding:5px;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;&bull;&nbsp;Cras semper sem hendrerit</li><li>&nbsp;&bull;&nbsp;Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><p>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </p></span></span>';
            element_html.content = element_html.content.replace( /PZARC_PLUGIN_URL/g, plugin_url );

            element_html.excerpt = '<span class="pzarc-draggable pzarc-draggable-excerpt"  title= "Excerpt with featured image" data-idcode=excerpt style="font-size:13px;"><span><img src="PZARC_PLUGIN_URL/shared/assets/images/sample-image.jpg" class="pzarc-align ' + jQuery( "select#_pzarc_layout-excerpt-thumb-cmb-field-0" ).val() + '" style="max-width:20%;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>';

            element_html.excerpt = element_html.excerpt.replace( /PZARC_PLUGIN_URL/g, plugin_url );

            element_html.meta3 = '<span class="pzarc-draggable pzarc-draggable-meta3 pzarc-draggable-meta"  title= "Meta info 3" data-idcode=meta3 style="font-size:11px;"><span>Comments: 27</span></span>';
            element_html.custom1 = '<span class="pzarc-draggable pzarc-draggable-custom1 pzarc-draggable-meta"  title= "Custom field 1" data-idcode=custom1 style="font-size:11px;"><span>Custom content group 1</span></span>';
            element_html.custom2 = '<span class="pzarc-draggable pzarc-draggable-custom2 pzarc-draggable-meta"  title= "Custom field 2" data-idcode=custom2 style="font-size:11px;"><span>Custom content group 2</span></span>';
            element_html.custom3 = '<span class="pzarc-draggable pzarc-draggable-custom3 pzarc-draggable-meta"  title= "Custom field 3" data-idcode=custom3 style="font-size:11px;"><span>Custom content group 3</span></span>';

            var cell_layout = jQuery.parseJSON( jQuery( 'input#_panels_design_preview-text' ).val() );

            jQuery( ".pzarc-content-area.sortable" ).html( '' );
            jQuery.each( cell_layout, function ( index )
            {
                jQuery( ".pzarc-content-area.sortable" ).append( element_html[index] );
            } );
            pzarc_update_component_location( cell_layout );
            pzarc_update_components_container_width( cell_layout );
            pzarc_update_components_nudge( cell_layout );
            pzarc_update_component_visibility( cell_layout );
//            pzarc_update_feature( cell_layout );
            pzarc_update_status( cell_layout );
        }
    } );


    /**
     * Process resizing
     *
     */
    jQuery( ".pzarc-draggable" ).resizable( {
        handles: "e",
        containment: "parent",
        grid: [4, 1],
        minWidth: 10,
        maxWidth: 400,
        autoHide: true,
        resize: function ()
        {
            var zone = jQuery( this ).data( "idcode" );
            var new_width = Math.floor( (jQuery( this ).width() / 400) * 100 );
            var cell_layout = jQuery.parseJSON( jQuery( 'input#_panels_design_preview-text' ).val() );
            cell_layout = pzarc_update_component_width( cell_layout, zone, new_width );
            pzarc_update_status( cell_layout );
        }
    } );


    /**
     * Resort the components
     */
    function pzarc_resort_components( cell_layout )
    {
        var new_order = {};
        var sorted = jQuery( ".pzarc-dropzone .pzarc-content-area" ).sortable( "toArray", {
            attribute: "data-idcode"
        } );

        jQuery.each( sorted, function ( index, value )
        {
            new_order[value] = cell_layout[value];
        } );
        return new_order;
    }



    /**
     * Update visibility
     */
    function pzarc_update_component_visibility( cell_layout )
    {
        var components_state = jQuery( "fieldset#_architect-_panels_design_components-to-show input" );
        jQuery.each( components_state, function ( index, value )
        {
            if ( value.value !== '' )
            {
                cell_layout[value.value].show = value.checked;
                if ( value.checked )
                {
                    jQuery( '.pzarc-draggable-' + value.value ).show();
                    jQuery( '.pzarc-draggable-' + value.value ).css( 'width', cell_layout[value.value].width + '%' );

                }
                else
                {
                    jQuery( '.pzarc-draggable-' + value.value ).hide();
                }
            }
        } );
        pzarc_update_feature();
        pzarc_update_status( cell_layout );
        return cell_layout;
    }

    /**
     * Update component widths
     * Although this is a seemingly superfluous function, it does provide consistency and makes future changes easier.
     */
    function pzarc_update_component_width( cell_layout, zone, new_width )
    {
        cell_layout[zone].width = new_width;
        return cell_layout;
    }


    function pzarc_update_feature()
    {

        /*********************
         // Update feature location
         *********************/
        var featureLocation = 'none';
        // Because this function is called in different ways,we reget the value of feature location
        jQuery( 'input[name="_architect[_panels_design_feature-location]"]' ).each( function ()
        {
            if ( this.checked )
            {
                featureLocation = this.value;
            }
        } );

        // Resets
        jQuery( '.pzarc-draggable-excerpt img.pzarc-align' ).addClass( 'none' ).removeClass( 'left right' );
        jQuery( '.pzarc-draggable-content img.pzarc-align' ).addClass( 'none' ).removeClass( 'left right' );
        jQuery( '.pzarc-dropzone .pzgp-cell-image-behind' ).html( '' );
        jQuery('.pzarc-draggable-image').hide();
        var isFeatureOn = jQuery( 'input#_panels_design_components-to-show-buttonsetimage' ).prop('checked');
        if (!isFeatureOn) {
            return;
        }


        switch (featureLocation)
        {

            case ('fill'):
                jQuery( '.pzarc-dropzone .pzgp-cell-image-behind' ).html( '<img src="' + plugin_url + '/shared/assets/images/sample-image.jpg"/>' );
                jQuery( '.pzarc-dropzone .pzgp-cell-image-behind' ).css( {
                    'left': '0',
                    'top': '0',
                    'right': '',
                    'bottom': '',
                    'width':'100%',
                    'height':'auto'
                } );
                // jQuery( '.pzarc-dropzone .pzgp-cell-image-behind img' ).css( {
                //     'height': '300px',
                //     'width': ''
                // } );

                // TODO: Update image width to 100%?
                break;

            case 'components':
        jQuery('.pzarc-draggable-image').show();
                break;

            case 'content-left':
                jQuery( '.pzarc-draggable-excerpt img.pzarc-align' ).removeClass( 'right none' ).addClass( 'left' );

                jQuery( '.pzarc-draggable-content img.pzarc-align' ).removeClass( 'right none' ).addClass( 'left' );
                break;

            case 'content-right':
                jQuery( '.pzarc-draggable-excerpt img.pzarc-align' ).removeClass( 'left none' ).addClass( 'right' );
                jQuery( '.pzarc-draggable-content img.pzarc-align' ).removeClass( 'left none' ).addClass( 'right' );
                break;

            case 'float':
            /// TODO: Work out how to make before or after components group/
                jQuery( '.pzarc-dropzone .pzgp-cell-image-behind' ).html( '<img src="' + plugin_url + 'shared/assets/images/sample-image.jpg"/>' );
                var zonesWidth = jQuery( '.pzarc-content-area' ).width();
                var zonesHeight = jQuery( '.pzarc-content-area' ).height();

                var sections_position = 'top';
                jQuery( 'input[name="_architect[_panels_design_components-position]"]' ).each( function ()
                {
                    if ( this.checked )
                    {
                        sections_position = this.value;
                    }
                } );
                switch (sections_position)
                {
                    case 'left':
                        jQuery( '.pzarc-dropzone .pzgp-cell-image-behind img' ).css( {
                            'height': 'auto',
                            'width': '100%',
                            'left': ''
                        } );
                        jQuery( '.pzarc-dropzone .pzgp-cell-image-behind' ).css( {
                            'width': (400-zonesWidth) + 'px',
                            'left': zonesWidth + 'px',
                            'right': '',
                            'top': '',
                            'bottom': ''
                        } );
                        break;

                    case 'right':
                        jQuery( '.pzarc-dropzone .pzgp-cell-image-behind img' ).css( {
                            'height': 'auto',
                            'width': '100%',
                            'left': ''
                        } );
                        jQuery( '.pzarc-dropzone .pzgp-cell-image-behind' ).css( {
                            'width': (400-zonesWidth) + 'px',
                            'right': zonesWidth + 'px',
                            'left': '',
                            'top': '',
                            'bottom': ''
                        } );
                        break;

                    case 'top':
                        jQuery( '.pzarc-dropzone .pzgp-cell-image-behind img' ).css( {
                            'width': '100%',
                            'height': '',
                            'left': ''
                        } );
                        jQuery( '.pzarc-dropzone .pzgp-cell-image-behind' ).css( {
                            'top': zonesHeight + 'px',
                            'right': '',
                            'left': '',
                            'bottom': '',
                            'width':'100%'
                        } );
                        break;

                    case 'bottom':
                        jQuery( '.pzarc-dropzone .pzgp-cell-image-behind img' ).css( {
                            'width': '100%',
                            'height': '',
                            'left': ''
                        } );
                        jQuery( '.pzarc-dropzone .pzgp-cell-image-behind' ).css( {
                            'bottom': zonesHeight + 'px',
                            'right': '',
                            'top': '0',
                            'left': '',
                            'width':'100%'
                        } );
                        break;
                }
                break;
            case 'none':
                jQuery( '.pzarc-dropzone .pzgp-cell-image-behind' ).html( '' );
                break;
        }

    }

    function pzarc_update_component_location( cell_layout )
    {
        /******************
         // Update component location
         ******************/
        var sections_position = 'top';
        jQuery( 'input[name="_architect[_panels_design_components-position]"]' ).each( function ()
        {
            if ( this.checked )
            {
                sections_position = this.value;
            }
        } );
        switch (sections_position)
        {
            case 'bottom':
                jQuery( '.pzarc-content-area' ).css( {
                    bottom: '0',
                    top: '',
                    left: '',
                    right: ''
                } );
                break;
            case 'top':
                jQuery( '.pzarc-content-area' ).css( {
                    bottom: '',
                    top: '0',
                    left: '',
                    right: ''
                } );
                break;
            case 'left':
                jQuery( '.pzarc-content-area' ).css( {
                    bottom: '0',
                    top: '0',
                    left: '0',
                    right: ''
                } );
                break;
            case 'right':
                jQuery( '.pzarc-content-area' ).css( {
                    bottom: '0',
                    top: '0',
                    left: '',
                    right: '0'
                } );
                break;
        }
        pzarc_update_feature( cell_layout );
    }

    function pzarc_update_components_container_width(  )
    {
        /**********************
         // update components width
         **********************/
            // TODO: Get Dovy to change sliders to update inout val in real time
        jQuery( '.pzarc-content-area' ).css( 'width', jQuery( 'fieldset#_architect-_panels_design_components-widths .redux-slider-label' ).text() + '%' );
    }


    function pzarc_update_components_nudge(  )
    {
        /*********************
         // Update components nudge
         *********************/
        var nudgexy = [jQuery( 'fieldset#_architect-_panels_design_components-nudge-x .redux-slider-label' ).text(), jQuery( 'fieldset#_architect-_panels_design_components-nudge-y .redux-slider-label' ).text()];
        var sections_position = 'top';
        jQuery( 'input[name="_architect[_panels_design_components-position]"]' ).each( function ()
        {
            if ( this.checked )
            {
                sections_position = this.value;
            }

        } );

        if ( sections_position === 'left' )
        {
            jQuery( '.pzarc-content-area' ).css( 'marginLeft', nudgexy[0] + '%' ).css( 'marginTop', nudgexy[1] + '%' );
        }

        else if ( sections_position === 'top' )
        {
            jQuery( '.pzarc-content-area' ).css( 'marginLeft', nudgexy[0] + '%' ).css( 'marginTop', nudgexy[1] + '%' );
        }

        else if ( sections_position === 'right' )
        {
            jQuery( '.pzarc-content-area' ).css( 'marginRight', nudgexy[0] + '%' ).css( 'marginTop', nudgexy[1] + '%' );
        }

        else if ( sections_position === 'bottom' )
        {
            jQuery( '.pzarc-content-area' ).css( 'marginLeft', nudgexy[0] + '%' ).css( 'marginBottom', nudgexy[1] + '%' );
        }
    }


    /**
     * Update tabs to show
     */

    function pzarc_update_tabs_to_show( e )
    {
        jQuery( e ).each( function ()
        {
            var tab_status = true;
            switch (this.target.value)
            {
                case 'title':
                    jQuery( '#1_box_redux-_architect-metabox-panels-design_section_group_li' ).toggle( this.target.checked );
                    break;
                case 'meta1':
                case 'meta2':
                case 'meta3':
                    tab_status = (
                    jQuery( 'input#_panels_design_components-to-show-buttonsetmeta1:checked' ).length === 1 ||
                    jQuery( 'input#_panels_design_components-to-show-buttonsetmeta2:checked' ).length === 1 ||
                    jQuery( 'input#_panels_design_components-to-show-buttonsetmeta3:checked' ).length === 1
                    );
                    jQuery( '#2_box_redux-_architect-metabox-panels-design_section_group_li' ).toggle( tab_status );
                    break;
                case 'content':
                case 'excerpt':
                    tab_status = (
                    jQuery( 'input#_panels_design_components-to-show-buttonsetcontent:checked' ).length === 1 ||
                    jQuery( 'input#_panels_design_components-to-show-buttonsetexcerpt:checked' ).length === 1);
                    jQuery( '#3_box_redux-_architect-metabox-panels-design_section_group_li' ).toggle( tab_status );
                    break;
               case 'image':

                   tab_status = (
                   jQuery( 'input#_panels_design_components-to-show-buttonsetimage:checked' ).length === 1
                   );
                   jQuery( '#4_box_redux-_architect-metabox-panels-design_section_group_li' ).toggle( tab_status );
                   break;
                case 'custom1':
                case 'custom2':
                case 'custom3':
                    tab_status = (
                    jQuery( 'input#_panels_design_components-to-show-buttonsetcustom1:checked' ).length === 1 ||
                    jQuery( 'input#_panels_design_components-to-show-buttonsetcustom2:checked' ).length === 1 ||
                    jQuery( 'input#_panels_design_components-to-show-buttonsetcustom3:checked' ).length === 1
                    );
                    jQuery( '#5_box_redux-_architect-metabox-panels-design_section_group_li' ).toggle( tab_status );
                    break;

            }
        } );

    }


} )
; // End


jQuery( window ).load( function ()
{
    if ( jQuery( '#redux-_architect-metabox-panels-design ul.redux-group-menu li.active' ).length === 0 )
    {
        jQuery( '#0_box_redux-_architect-metabox-panels-design_section_group_li' ).find( 'a' ).trigger( 'click' );
    }
} );

// Nothing past here!

