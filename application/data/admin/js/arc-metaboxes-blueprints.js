jQuery( document ).ready( function ()
{
    "use strict";

    // This doesn't work but it should!!
    // jQuery( "0_box_redux-_architect-metabox-layout-settings_section_group_li" ).find('a' ).trigger('click');


    // ********************************************************************************************
    // Control the visibility of the tabs for CONTENT TYPES in Blueprints metaboxes
    // This one is for when using thebutton_Set in the main area. Need sto be modified for sidebar.
    // ********************************************************************************************
//    jQuery( "#_architect-content_general_content-source input" ).each( function ( i )
//    {
//        if ( this.checked )
//        {
//            jQuery( ".redux-sidebar li#" + (i + 1) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).show();
//        }
//        else
//        {
//            jQuery( ".redux-sidebar li#" + (i + 1) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).hide();
//        }
//    } );
//
//    jQuery( "#_architect-content_general_content-source label" ).on( 'click', function ( e )
//    {
//        pzarc_show_hide_content_tabs( e.target.innerText.trim() );
//    } );
//
//    function pzarc_show_hide_content_tabs( tab )
//    {
//        var a = ["Defaults", "Posts", "Pages", "Galleries", "Slides", "Custom Post Types"];
//        for ( var i = 1; i <= 6; i++ )
//        {
//            jQuery( ".redux-sidebar li#" + i + "_box_redux-_architect-metabox-content-selections_section_group_li" ).hide();
//        }
//        var j = a.indexOf( tab ) + 1;
//        jQuery( ".redux-sidebar li#" + j + "_box_redux-_architect-metabox-content-selections_section_group_li" ).show();
//    }

    // ********************************************************************************************
    // 
    // Control the visibility of the tabs for CONTENT TYPES in Blueprints metaboxes
    // This one is for when using a dropdown selector.
    // 
    // ********************************************************************************************
    jQuery( "select#_blueprints_content-source-select" ).find( "option" ).each( function ( i )
    {
        //console.log(this,this.selected,jQuery(this).text());
        // there's a blank one at the to  because of the placeholder
        if ( i == 0 )
        {return;}
        if ( this.selected )
        {
            jQuery( ".redux-sidebar li#" + (i - 1) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).show().trigger( "click" );
        }
        else
        {
            jQuery( ".redux-sidebar li#" + (i - 1) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).hide();
        }
    } );

    jQuery( "select#_blueprints_content-source-select" ).on( 'click', function ()
    {
        pzarc_show_hide_content_tabs( jQuery( this ).find( "option:selected" ).text().trim() );
    } );

    function pzarc_show_hide_content_tabs( tab )
    {
        var a = ["Defaults", "Posts", "Pages", "Galleries", "Slides", "Custom Post Types"];
        for ( var i = 1; i <= a.length; i++ )
        {
            jQuery( ".redux-sidebar li#" + (i - 1) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).hide();
        }
        jQuery( ".redux-sidebar li#" + a.indexOf( tab ) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).show().find( "a" ).trigger( "click" );
    }

    // ********************************************************************************************
    // 
    // Control visibility of NAVIGATION tabs
    // 
    // ********************************************************************************************
    jQuery( "#_architect-_blueprints_navigation input" ).each( function ( i )
    {
        if ( i == 0 && this.checked )
        {
            jQuery( ".redux-sidebar li#3_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
            jQuery( ".redux-sidebar li#4_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
            return;
        }
        //   //console.log(i,this.checked);
        if ( this.checked )
        {
            jQuery( ".redux-sidebar li#" + (i + 2) + "_box_redux-_architect-metabox-layout-settings_section_group_li" ).show();
        }
        else
        {
            jQuery( ".redux-sidebar li#" + (i + 2) + "_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
        }
    } );

    jQuery( "#_architect-_blueprints_navigation label" ).on( 'click', function ( e )
    {
        pzarc_show_hide_nav_tabs( e.target.innerText.trim() );
    } );

    function pzarc_show_hide_nav_tabs( tab )
    {
        if ( tab == 'None' )
        {
            jQuery( ".redux-sidebar li#3_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
            jQuery( ".redux-sidebar li#4_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
            if (jQuery('#5_box_redux-_architect-metabox-layout-settings_section_group_li.active' ).length == 0) {
                jQuery( ".redux-sidebar li#0_box_redux-_architect-metabox-layout-settings_section_group_li" ).find( 'a' ).trigger( "click" );
            }
        }
        else
        {
            var a = ["Pagination", "Navigator"];
            for ( var i = 1; i <= 2; i++ )
            {
                jQuery( ".redux-sidebar li#" + (i + 2) + "_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
            }
            var j = a.indexOf( tab ) + 3;
//            console.log(jQuery(jQuery('#5_box_redux-_architect-metabox-layout-settings_section_group_li.active' ).length));
            // This checks first if thewireframe preview tab is active.
            // Need tosetup somebetter method that is dumber
            jQuery( ".redux-sidebar li#" + j + "_box_redux-_architect-metabox-layout-settings_section_group_li" ).show().find( 'a' );
            if (jQuery('#5_box_redux-_architect-metabox-layout-settings_section_group_li.active' ).length == 0) {
                jQuery( ".redux-sidebar li#" + j + "_box_redux-_architect-metabox-layout-settings_section_group_li" ).find( 'a' ).trigger( 'click' );
            }
        }
    }

    // ********************************************************************************************
    // 
    // Show hide SECTION tabs
    // 
    // ********************************************************************************************
    jQuery( ".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( jQuery( "fieldset#_architect-_blueprints_section-1-enable" ).find( 'input' ).val() == "1" );
    jQuery( ".redux-sidebar li#2_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( jQuery( "fieldset#_architect-_blueprints_section-2-enable" ).find( 'input' ).val() == "1" );

    jQuery( "fieldset#_architect-_blueprints_section-1-enable" ).on( 'click', function ()
    {
        var state = (jQuery( this ).find( 'input' ).val() == "1");
        jQuery( ".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( state );
        if (jQuery('#5_box_redux-_architect-metabox-layout-settings_section_group_li.active' ).length == 0) {
            jQuery( ".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li" ).find( 'a' ).trigger( "click" );
        }

        if ( state )
        {
          jQuery( ".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( state );
            pzarc_refresh_blueprint_layout(1);
          if (jQuery('#5_box_redux-_architect-metabox-layout-settings_section_group_li.active' ).length == 0) {
            jQuery( ".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li" ).find( 'a' ).trigger( "click" );
          }
        }
    } );

    jQuery( "fieldset#_architect-_blueprints_section-2-enable" ).on( 'click', function ()
    {
        pzarc_refresh_blueprint_layout(1);
        jQuery( ".redux-sidebar li#2_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( jQuery( this ).find( 'input' ).val() == "1" );
        if (jQuery('#5_box_redux-_architect-metabox-layout-settings_section_group_li.active' ).length == 0) {
            jQuery( ".redux-sidebar li#2_box_redux-_architect-metabox-layout-settings_section_group_li" ).find( 'a' ).trigger( "click" );
        }
        jQuery( "#section-table-blueprints_section-start-content" ).toggle( jQuery( this ).find( 'input' ).val() == "1" );
        if ( jQuery( this ).find( 'input' ).val() != "1" )
        {
            if (jQuery('#5_box_redux-_architect-metabox-layout-settings_section_group_li.active' ).length == 0) {
                jQuery( ".redux-sidebar li#0_box_redux-_architect-metabox-layout-settings_section_group_li" ).find( 'a' ).trigger( "click" );
            }
        }
    } );

    // ********************************************************************************************
    // 
    // Show hide GENERAL SETTINGS sections off tabs
    // 
    // ********************************************************************************************
    jQuery( "table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-table-_blueprints_section-end-layout" ).show();
    jQuery( "table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-table-_blueprints_section-end-content" ).hide();
    jQuery( "fieldset#_architect-_blueprints_section-start-layout, fieldset#_architect-_blueprints_section-start-content,fieldset#_architect-_blueprints_section-end-layout, fieldset#_architect-_blueprints_section-end-content" ).hide();

    jQuery( "#_architect-_blueprints_tabs li" ).on( "click", function ()
    {

        var clickedOn = jQuery( this ).find( "span" ).text().trim();
        if ( clickedOn == "Layout" )
        {
            jQuery( "table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-table-_blueprints_section-end-layout" ).show();
            jQuery( "table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-table-_blueprints_section-end-content" ).hide();
        }
        if ( clickedOn == "Content" )
        {
            var tab = jQuery( "select#_blueprints_content-source-select" ).find( "option:selected" );
            var chosenOne = tab.index();
            //console.log(chosenOne);

            jQuery( "table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-_table-blueprints_section-end-layout" ).hide();
            jQuery( "table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-_table-blueprints_section-end-content" ).show();
            jQuery( ".redux-sidebar li#" + (chosenOne - 1) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).find( "a" ).trigger( "click" );
        }

    } );


    // Show hide navigator tabs


    // var $container = jQuery('#pzarc-sections-preview.pzarc-section-1');
    // var $cell = jQuery('.pzarc-section-cell')
    // // initialize Isotope
    // $container.isotope({
    // // options...
    // resizable: false, // disable normal resizing
    // // set columnWidth to a percentage of container width
    // itemClass: 'pzarc-section-cell',
    // masonry: { columnWidth: $container.width()/3, gutterWidth:20 }
    // });

    // // update columnWidth on window resize
    // jQuery(window).smartresize(function(){
    // $container.isotope({
    // itemClass: 'pzarc-section-cell',
    // // update columnWidth to a percentage of container width
    // masonry: { columnWidth: $container.width()/3, gutterWidth:20 }
    // });
    // });

//_pzarc_section-group-_pzarc_blueprint-cells-per-view-cmb-group-0-cmb-field-0
//_pzarc_section-group-_pzarc_blueprint-cells-per-view-cmb-group-1-cmb-field-0


    jQuery( 'fieldset#_architect-_blueprints_navigation input' ).on( 'change', function () {pzarc_show_navtype( this );} );


    function pzarc_show_navtype( t )
    {
        var navtype = jQuery( t ).val();
        jQuery( "#pzarc-navigator-preview .pzarc-sections" ).hide();
        if ( navtype == "none" ) {return;}
        jQuery( "#pzarc-navigator-preview .pzarc-section-" + navtype ).toggle( jQuery( t ).checked );
//  //console.log(navtype);
//  switch ( navtype) {
//    case 'none':
//      jQuery('.item-blueprint-pagination').hide();
//      jQuery('.item-blueprint-navigator').hide();
//      jQuery('.item-blueprint-section-1').trigger('click');
//      break;
//    case 'pagination':
//      jQuery('.item-blueprint-pagination').fadeIn();
//      jQuery('.item-blueprint-navigator').hide();
//      jQuery('.item-blueprint-pagination').trigger('click');
//
//      break;
//    case 'navigator':
//      jQuery('.item-blueprint-pagination').hide();
//      jQuery('.item-blueprint-navigator').fadeIn();
//      jQuery('.item-blueprint-navigator').trigger('click');
//      break;
//  }
    }

//fieldset#_architect-_blueprints_sections-preview

    init();
    /**
     *
     */
    function init()
    {
        // Setup section

        pzarc_refresh_blueprint_layout( 0 );
        pzarc_refresh_blueprint_layout( 1 );
        pzarc_refresh_blueprint_layout( 2 );

        var navtype = jQuery( 'fieldset#_architect-_blueprints_navigation input:checked' ).val();
        console.log(navtype);
        jQuery( "#pzarc-navigator-preview .pzarc-sections" ).hide();
        if ( navtype != "none" ) {
            var np =jQuery( ".pzarc-section-" + navtype ).get(0);
            jQuery( np ).show();
        }
        pzarc_update_usage_info( jQuery( "#_pzarc_blueprint-short-name-cmb-field-0" ).get( 0 ) );

        for ( var i = 0; i < 3; i++ )
        {
            jQuery( 'input#_blueprints_section-' + i + '-panels-vert-margin' ).change( function ()
            {
                //console.log(this.id.substr(20,1));
                pzarc_refresh_blueprint_layout( this.id.substr( 20, 1 ) );
            } );
            jQuery( 'input#_blueprints_section-' + i + '-panels-per-view' ).change( function ()
            {
                pzarc_refresh_blueprint_layout( this.id.substr( 20, 1 ) );
            } );
            jQuery( 'input#_blueprints_section-' + i + '-panels-per-view' ).change( function ()
            {
                pzarc_refresh_blueprint_layout( this.id.substr( 20, 1 ) );
            } );
            jQuery( 'input#_blueprints_section-' + i + '-min-panel-width' ).change( function ()
            {
                pzarc_refresh_blueprint_layout( this.id.substr( 20, 1 ) );
            } );

// ---   jQuery('#_pzarc_' + i + '-blueprint-section-enable-cmb-field-0').change(function () {
//      var x = this.id.substr(20,1);
//      pzarc_show_hide_section(x);
//    });
        }

        // This updates the shortname help text the explains how to use the shortcode
        jQuery( 'input#_blueprints_short-name-text' ).change( function ()
        {
            pzarc_update_usage_info( this );
        } );

        pzarc_show_navtype();
    }


    /**
     *
     * @param i
     */
    function pzarc_refresh_blueprint_layout( i )
    {
        //console.log(i,jQuery('input#_blueprints_section-' + i + '-columns'));
        pzarc_update_cell_count( i, jQuery( 'input#_blueprints_section-' + i + '-panels-per-view' ) );
        pzarc_update_cell_margin( i, jQuery( 'input#_blueprints_section-' + i + '-panels-vert-margin' ) );
        pzarc_update_cell_across( i, jQuery( 'input#_blueprints_section-' + i + '-columns' ) );
        pzarc_update_min_width( i, jQuery( 'input#_blueprints_section-' + i + '-min-panel-width' ) );
        pzarc_show_hide_section( i );
    }

//
////  jQuery('#pzarc-sections-preview-0').resize(function(){////console.log(this.width);pzarc_refresh_blueprint_layout(0)});
////  jQuery('#pzarc-sections-preview-1').resize(function(){////console.log(this.width);pzarc_refresh_blueprint_layout(1)});
////  jQuery('#pzarc-sections-preview-2').resize(function(){////console.log(this.width);pzarc_refresh_blueprint_layout(2)});
//
//

    /**
     *
     * @param t
     */
    function pzarc_update_usage_info( t )
    {
        ////console.log(t.value);
        jQuery( 'span.pzarc-shortname' ).text( jQuery( t ).val() );
    }


    /* Switched to pixel based once, but not as fluid. Butwhat was it's advantage? Why did I switch? */
    /**
     *
     * @param i
     * @param t
     */
    function pzarc_update_cell_margin( i, t )
    {
        //console.log(i,jQuery('#_architect-_blueprints_section-' + i + '-columns'));
        var cellsAcross = jQuery( 'input#_blueprints_section-' + i + '-columns' ).value;
//        var containerWidth = jQuery( '.pzarc-section-' + i ).width();
//        console.log(i, t.val());
        jQuery( '#pzarc-sections-preview-' + i + ' .pzarc-section-cell' ).css( {'marginRight': (t.val() ) + '%'} );
//        jQuery( '#pzarc-sections-preview-' + i + ' .pzarc-section-cell' ).each( function ( index, value )
//        {
////     ////console.log(((index+1)%cellsAcross),cellsAcross);
////      if (((index+1)%cellsAcross) != 0) {
//            jQuery( value ).css( {'marginRight': (t.value ) + '%'} );
////      }
//        } );
    }

    /***
     *
     * @param i
     * @param t
     */
    function pzarc_update_cell_across( i, t )
    {
////console.log(i);
        var containerWidth = jQuery( '#pzarc-sections-preview-' + i ).width();
//    ////console.log(containerWidth);
        var cellRightMargin = jQuery( '#_blueprints_section-' + i + '-panels-vert-margin' ).val();
        var new_cell_width = (100 / t.val()) - cellRightMargin;
        //  console.log( t.val(),cellRightMargin,new_cell_width);
        // Can't use width(), it breaks when padding is set.
        jQuery( '#pzarc-sections-preview-' + i + ' .pzarc-section-cell' ).css( {"width": new_cell_width + '%'} );
    }

    /**
     *
     * @param i
     * @param t
     */
    function pzarc_update_cell_count( i, t )
    {
        jQuery( '.pzarc-section-' + i ).empty();
//   var plugin_url = jQuery('fieldset#_architect-_blueprints_sections-preview .plugin_url').get(0).textContent;
        var show_count = (t.val() == 0 ? 10 : t.val());
        for ( var j = 1; j <= show_count; j++ )
        {
            jQuery( '#pzarc-sections-preview-' + i ).append( '<div class="pzarc-section-cell-' + j + ' pzarc-section-cell" ></div>' );
        }
    }

    /**
     *
     * @param i
     * @param t
     */
    function pzarc_update_min_width( i, t )
    {
        ////console.log(i);
        jQuery( '#pzarc-sections-preview-' + i + ' .pzarc-section-cell' ).css( {'minWidth': t.value + 'px'} );
    }

    /**
     *
     * @param x
     */
    function pzarc_show_hide_section( x )
    {
        var y = parseInt( x ) + 1;
        //console.log(x);
        if ( x == 0 )
        {
            jQuery( '#pzarc-sections-preview-0' ).show();
            jQuery( '#blueprint-section-1.postbox' ).show();
            jQuery( '.item-blueprint-section-1' ).show();
            return;
        }
//    //console.log(x,jQuery('#_pzarc_'+x+'-blueprint-section-enable-cmb-field-0').get(0).checked);

        jQuery( '#pzarc-sections-preview-' + x ).toggle( jQuery( 'input#_blueprints_section-' + x + '-enable' ).val() );
        jQuery( '#blueprint-section-' + y + '.postbox' ).toggle( jQuery( 'input#_blueprints_section-' + x + '-enable' ).val() );
        jQuery( '.item-blueprint-section-' + y ).fadeToggle( jQuery( 'input#_blueprints_section-' + x + '-enable' ).val() );

        // Need to make this a little clevered
        // needed this coz container was staying big
        jQuery( '.item-blueprint-wireframe-preview' ).trigger( 'click' );

    }

} );

//id="_pzarc_blueprint-navigation-cmb-field-0"