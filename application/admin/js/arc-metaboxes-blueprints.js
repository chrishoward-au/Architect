jQuery( document ).ready( function ()
{
  "use strict";

  /**
   * Set validation on blueprint shortname. Once Redux gets it working, can remove this.
   */

  jQuery( "input#_blueprints_short-name-text" ).attr( "required", "required" );
  jQuery( "input#_blueprints_short-name-text" ).attr( "pattern", "[a-zA-Z0-9\-\_]+" );
  // Weird. Was this, then that ^ Now this again
  jQuery( "input#_blueprints_short-name" ).attr( "required", "required" );
  jQuery( "input#_blueprints_short-name" ).attr( "pattern", "[a-zA-Z0-9\-\_]+" );

  /********************************************************************************************
   //
   // Control the visibility of the tabs for CONTENT TYPES in Blueprints metaboxes
   // This one is for when using a dropdown selector.
   //
   ********************************************************************************************/
  function init_content_tabs()
  {
    var content_options_selected = jQuery( 'fieldset#_architect-_blueprints_content-source input:checked' ).val();
    pzarc_show_hide_content_tabs( content_options_selected )

  }

  init_content_tabs();

  // Change content tab on content source change
  jQuery( "fieldset#_architect-_blueprints_content-source" ).on( 'change', function ()
  {
    pzarc_show_hide_content_tabs( jQuery( this ).find( ":checked" ).val().trim() );
  } );


  /**
   *
   * pzarc_show_hide_content_tabs
   *
   */
  function pzarc_show_hide_content_tabs( tab )
  {
    var content_options = jQuery( 'fieldset#_architect-_blueprints_content-source input' );
    content_options.each( function ( i )
        {
          jQuery( ".redux-sidebar li#_" + (jQuery( this ).val()) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).hide();
        }
    );
    jQuery( ".redux-sidebar li#_" + (tab) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).show();
  }


  /********************************************************************************************
   //
   // Show hide SECTION tabs
   //
   // ********************************************************************************************/
    // TODO: Use the jquery :gt(n) psuedo element

  jQuery( "fieldset#_architect-_blueprints_section-1-enable" ).on( 'click', function ()
  {
    var state = (jQuery( this ).find( 'input' ).val() === "1");
    jQuery( ".redux-sidebar li#_section2_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( state );
    jQuery( ".redux-sidebar li#_styling_section_2_box_redux-_architect-metabox-blueprint-stylings_section_group_li" ).toggle( state );

    if ( jQuery( '#_section2_box_redux-_architect-metabox-layout-settings_section_group_li.active' ).length === 1 )
    {
      jQuery( ".redux-sidebar li#_section1_box_redux-_architect-metabox-layout-settings_section_group_li" ).find( 'a' ).trigger( "click" );
    }

    if ( state )
    {
      jQuery( ".redux-sidebar li#_section2_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( state );
    }
  } );

  jQuery( "fieldset#_architect-_blueprints_section-2-enable" ).on( 'click', function ()
  {
    var state = (jQuery( this ).find( 'input' ).val() === "1");
    jQuery( ".redux-sidebar li#_section3_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( state );
    jQuery( ".redux-sidebar li#_styling_section_3_box_redux-_architect-metabox-blueprint-stylings_section_group_li" ).toggle( state );

    if ( jQuery( '#_section3_box_redux-_architect-metabox-layout-settings_section_group_li.active' ).length === 1 )
    {
      jQuery( ".redux-sidebar li#_section1_box_redux-_architect-metabox-layout-settings_section_group_li" ).find( 'a' ).trigger( "click" );
    }

    if ( state )
    {
      jQuery( ".redux-sidebar li#_section3_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( state );
    }
  } );


  /********************************************************************************************
   //
   // Control visibility of NAVIGATION tabs
   //
   // ********************************************************************************************/

  jQuery( '#_architect-_blueprints_section-0-layout-mode' ).on( 'click', function ()
  {
    var layout_type = jQuery( '#_architect-_blueprints_section-0-layout-mode :checked' ).get( 0 ).value;

    process_tabs( layout_type );
  } );

  /**
   *
   * @param layout_mode
   */
  function process_tabs( layout_mode )
  {
    // First off reset everything hidden or visible
    jQuery( '#_slidertabbed_box_redux-_architect-metabox-type-settings_section_group_li,' +
        '#_tabular_box_redux-_architect-metabox-type-settings_section_group_li,' +
        '#_accordion_box_redux-_architect-metabox-type-settings_section_group_li,' +
        '#_pagination_box_redux-_architect-metabox-type-settings_section_group_li,' +
        '#_masonry_box_redux-_architect-metabox-type-settings_section_group_li,' +
        '#_styling_slidertabbed_box_redux-_architect-metabox-type-settings_section_group_li,' +
        '#_styling_masonry_box_redux-_architect-metabox-type-settings_section_group_li,' +
        '#_styling_accordion_box_redux-_architect-metabox-type-settings_section_group_li,' +
        '#_styling_tabular_box_redux-_architect-metabox-type-settings_section_group_li'
    ).hide();
    jQuery( "fieldset#_architect-_blueprints_masonry," +
        "fieldset#_architect-_blueprints_pagination," +
        "fieldset#_architect-_blueprints_pager," +
        "fieldset#_architect-_blueprints_pager-single," +
        "fieldset#_architect-_blueprints_pager-archives," +
        "fieldset#_architect-_blueprints_pager-location"
    ).toggle( true ).parent().toggle( true );

//console.log(layout_mode);
    switch (layout_mode)
    {

      case 'basic':
        jQuery( '#_pagination_box_redux-_architect-metabox-type-settings_section_group_li' ).show();
        jQuery( '#_pagination_box_redux-_architect-metabox-type-settings_section_group_li_a' ).trigger( 'click' );
        jQuery( window ).load( function () {jQuery( '#_pagination_box_redux-_architect-metabox-type-settings_section_group_li_a' ).trigger( 'click' )} );
        jQuery( '#_architect-_blueprint_tabs_tabs .pzarc-blueprint-type' ).text( 'Grids' );
        break;

      case 'masonry':
        jQuery( '#_pagination_box_redux-_architect-metabox-type-settings_section_group_li' ).show();
        jQuery( '#_masonry_box_redux-_architect-metabox-type-settings_section_group_li' ).show();
        jQuery( '#_styling_masonry_box_redux-_architect-metabox-type-settings_section_group_li' ).show();
        jQuery( '#_masonry_box_redux-_architect-metabox-type-settings_section_group_li_a' ).trigger( 'click' );
        jQuery( window ).load( function () {jQuery( '#_masonry_box_redux-_architect-metabox-type-settings_section_group_li_a' ).trigger( 'click' )} );
        jQuery( '#_architect-_blueprint_tabs_tabs .pzarc-blueprint-type' ).text( 'Masonry' );
        break;

      case 'slider':
      case 'tabbed':
        jQuery( '#_slidertabbed_box_redux-_architect-metabox-type-settings_section_group_li' ).show();
        jQuery( '#_section2_box_redux-_architect-metabox-type-settings_section_group_li' ).hide();
        jQuery( '#_section3_box_redux-_architect-metabox-type-settings_section_group_li' ).hide();
        jQuery( "fieldset#_architect-_blueprints_masonry" ).toggle( false ).parent().toggle( false );
        jQuery( "fieldset#_architect-_blueprints_pagination" ).toggle( false ).parent().toggle( false );
        jQuery( "fieldset#_architect-_blueprints_pager" ).toggle( false ).parent().toggle( false );
        jQuery( "fieldset#_architect-_blueprints_pager-single" ).toggle( false ).parent().toggle( false );
        jQuery( "fieldset#_architect-_blueprints_pager-archives" ).toggle( false ).parent().toggle( false );
        jQuery( "fieldset#_architect-_blueprints_pager-location" ).toggle( false ).parent().toggle( false );
        jQuery( '#_styling_slidertabbed_box_redux-_architect-metabox-type-settings_section_group_li' ).show();
        jQuery( '#_slidertabbed_box_redux-_architect-metabox-type-settings_section_group_li_a' ).trigger( 'click' );
        jQuery( window ).load( function () {jQuery( '#_slidertabbed_box_redux-_architect-metabox-type-settings_section_group_li_a' ).trigger( 'click' )} );
        if ( 'slider' === layout_mode )
        {
          jQuery( '#_architect-_blueprint_tabs_tabs .pzarc-blueprint-type' ).text( 'Sliders' );
        }
        else
        {
          jQuery( '#_architect-_blueprint_tabs_tabs .pzarc-blueprint-type' ).text( 'Tabbed' );
        }
        break;

      case 'table':
        jQuery( '#_tabular_box_redux-_architect-metabox-type-settings_section_group_li' ).show();
        jQuery( '#_styling_tabular_box_redux-_architect-metabox-type-settings_section_group_li' ).show();
        jQuery( '#_tabular_box_redux-_architect-metabox-type-settings_section_group_li_a' ).trigger( 'click' );
        jQuery( window ).load( function () {jQuery( '#_tabular_box_redux-_architect-metabox-type-settings_section_group_li_a' ).trigger( 'click' )} );
        jQuery( '#_architect-_blueprint_tabs_tabs .pzarc-blueprint-type' ).text( 'Tabular' );
        break;

      case 'accordion':
        jQuery( '#_accordion_box_redux-_architect-metabox-type-settings_section_group_li' ).show();
        jQuery( '#_styling_accordion_box_redux-_architect-metabox-type-settings_section_group_li' ).show();
        jQuery( '#_architect-_blueprint_tabs_tabs .pzarc-blueprint-type' ).text( 'Accordion' );
        jQuery( '#_accordion_box_redux-_architect-metabox-type-settings_section_group_li_a' ).trigger( 'click' );
        jQuery( window ).load( function () {jQuery( '#_accordion_box_redux-_architect-metabox-type-settings_section_group_li_a' ).trigger( 'click' );} );


        break;

    }

  }

  /********************************************************************************************
   //
   // Show hide GENERAL SETTINGS sections off tabs
   //
   // ********************************************************************************************/
  jQuery( "table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-table-_blueprints_section-end-layout" ).show();
  jQuery( "table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-table-_blueprints_section-end-content" ).hide();
  jQuery( "fieldset#_architect-_blueprints_section-start-layout, fieldset#_architect-_blueprints_section-start-content,fieldset#_architect-_blueprints_section-end-layout, fieldset#_architect-_blueprints_section-end-content" ).hide();

  jQuery( "#_architect-_blueprints_tabs li" ).on( "click", function ()
  {

    var clickedOn = jQuery( this ).find( "span" ).text().trim();
    //console.log(clickedOn);
    if ( clickedOn === "Layout" )
    {
      jQuery( "table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-table-_blueprints_section-end-layout" ).show();
      jQuery( "table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-table-_blueprints_section-end-content" ).hide();
    }
    if ( clickedOn === "Content" )
    {
      var tab = jQuery( "select#_blueprints_content-source-select" ).find( "option:selected" );
      var chosenOne = tab.index();

      jQuery( "table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-_table-blueprints_section-end-layout" ).hide();
      jQuery( "table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-_table-blueprints_section-end-content" ).show();
      jQuery( ".redux-sidebar li#" + (chosenOne - 1) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).find( "a" ).trigger( "click" );
    }

  } );


  jQuery( 'fieldset#_architect-_blueprints_navigation input' ).on( 'change', function () {pzarc_show_navtype( this );} );


  function pzarc_show_navtype( t )
  {
    var navtype = jQuery( t ).val();
    jQuery( "#pzarc-navigator-preview .pzarc-sections" ).hide();
    if ( navtype === "none" )
    {return;}
    jQuery( "#pzarc-navigator-preview .pzarc-section-" + navtype ).toggle( jQuery( t ).checked );
  }

  /**
   *
   */
  function init()
  {


    // This updates the shortname help text the explains how to use the shortcode
    jQuery( 'input#_blueprints_short-name' ).change( function ()
    {
      pzarc_update_usage_info( this );
    } );

    pzarc_update_usage_info( jQuery( 'input#_blueprints_short-name' ) );

    pzarc_show_navtype();
    var layout_mode = jQuery( '#_architect-_blueprints_section-0-layout-mode :checked' );
    if ( layout_mode.length > 0 )
    {
      process_tabs( layout_mode.get( 0 ).value );
    }

    var arc_shortname = localStorage.getItem( "arc_shortname" );

    if (jQuery("#_blueprints_short-name" ).val()=== arc_shortname)
    {
      var prev_active = localStorage.getItem( "arc_current_tab" );
      if ( prev_active )
      {
        jQuery( prev_active ).trigger( 'click', function ()
        {
          console.log( this );
        } );
      }

      jQuery( window ).load( function ( hitTab )
      {
        var prev_sidetab = JSON.parse( localStorage.getItem( "arc_current_sidetab" ) );
        if ( prev_sidetab )
        {
          for ( var i = 0; i < prev_sidetab.length; i++ )
          {
            jQuery( prev_sidetab[i] ).find( 'a' ).trigger( 'click' );
          }
        }
      } );
    }
  }


  /**
   *
   * @param i
   */
  function pzarc_refresh_blueprint_layout( i )
  {
    pzarc_update_cell_count( i, jQuery( 'input#_blueprints_section-' + i + '-panels-per-view' ) );
    pzarc_update_cell_margin( i, jQuery( 'input#_blueprints_section-' + i + '-panels-vert-margin' ) );
    pzarc_update_cell_across( i, jQuery( 'input#_blueprints_section-' + i + '-columns' ) );
    pzarc_update_min_width( i, jQuery( 'input#_blueprints_section-' + i + '-min-panel-width' ) );
  }


  /**
   *
   * @param t
   */
  function pzarc_update_usage_info( t )
  {
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
    var cellsAcross = jQuery( 'input#_blueprints_section-' + i + '-columns' ).value;
    jQuery( '#pzarc-sections-preview-' + i + ' .pzarc-section-cell' ).css( {'marginRight': (t.val() ) + '%'} );
  }

  /***
   *
   * @param i
   * @param t
   */
  function pzarc_update_cell_across( i, t )
  {
    var containerWidth = jQuery( '#pzarc-sections-preview-' + i ).width();
    var cellRightMargin = jQuery( '#_blueprints_section-' + i + '-panels-vert-margin' ).val();
    var new_cell_width = (100 / t.val()) - cellRightMargin;
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
    var show_count = (t.val() === 0 ? 10 : t.val());
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
    jQuery( '#pzarc-sections-preview-' + i + ' .pzarc-section-cell' ).css( {'minWidth': t.value + 'px'} );
  }

  /**
   *
   * @param x
   */
  function pzarc_show_hide_section( x )
  {
    var y = parseInt( x ) + 1;
    if ( x === 0 )
    {
      jQuery( '#pzarc-sections-preview-0' ).show();
      jQuery( '#blueprint-section-1.postbox' ).show();
      jQuery( '.item-blueprint-section-1' ).show();
      return;
    }

    jQuery( '#pzarc-sections-preview-' + x ).toggle( jQuery( 'input#_blueprints_section-' + x + '-enable' ).val() );
    jQuery( '#blueprint-section-' + y + '.postbox' ).toggle( jQuery( 'input#_blueprints_section-' + x + '-enable' ).val() );
    jQuery( '.item-blueprint-section-' + y ).fadeToggle( jQuery( 'input#_blueprints_section-' + x + '-enable' ).val() );

    // Need to make this a little clevered
    // needed this coz container was staying big
    jQuery( '.item-blueprint-wireframe-preview' ).trigger( 'click' );

  }

  jQuery( "input#publish" ).on( 'click', function ()
  {
    var current_tab = jQuery( '#_architect-_blueprint_tabs_tabs ul li.active' ).attr( 'id' );
    localStorage.setItem( "arc_current_tab", '#' + current_tab );
    var current_sidetab = jQuery( '.redux-group-tab-link-li.active' );
    var current_sidetabs = [];
    jQuery( current_sidetab ).each( function ()
    {
      current_sidetabs.push( '#' + jQuery( this ).attr( 'id' ) );
    } )

    localStorage.setItem( "arc_current_sidetab", JSON.stringify( current_sidetabs ) );
    localStorage.setItem("arc_shortname",jQuery("#_blueprints_short-name" ).val());

  } );

  init();

} );


jQuery( window ).load( function ()
{

} );
