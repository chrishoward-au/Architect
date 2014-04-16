jQuery(document).ready(function () {
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
    // Control the visibility of the tabs for CONTENT TYPES in Blueprints metaboxes
    // This one is for when using a dropdown selector.
    // ********************************************************************************************
    jQuery( "select#_blueprints_content-source-select" ).find("option").each( function ( i )
    {
        console.log(this,this.selected,jQuery(this).text());
        // there's a blank one at the to  because of the placeholder
        if (i==0){return;}
        if ( this.selected )
        {
            jQuery( ".redux-sidebar li#" + (i - 1) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).show().trigger("click");
        }
        else
        {
            jQuery( ".redux-sidebar li#" + (i - 1) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).hide();
        }
    } );

    jQuery( "select#_blueprints_content-source-select" ).on( 'click', function (  )
    {
        pzarc_show_hide_content_tabs( jQuery(this ).find("option:selected" ).text().trim() );
    } );

    function pzarc_show_hide_content_tabs( tab )
    {
        var a = ["Defaults", "Posts", "Pages", "Galleries", "Slides", "Custom Post Types"];
        for ( var i = 1; i <= a.length; i++ )
        {
            jQuery( ".redux-sidebar li#" + (i-1) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).hide();
        }
        jQuery( ".redux-sidebar li#" + a.indexOf( tab ) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).show().find("a" ).trigger("click");
    }

    // ********************************************************************************************
    // Control visibility of NAVIGATION tabs
    // ********************************************************************************************
    jQuery( "#_architect-blueprints_navigation input" ).each( function ( i )
    {
        if ( i == 0 && this.checked )
        {
            jQuery( ".redux-sidebar li#3_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
            jQuery( ".redux-sidebar li#4_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
            return;
        }
        //   console.log(i,this.checked);
        if ( this.checked )
        {
            jQuery( ".redux-sidebar li#" + (i + 2) + "_box_redux-_architect-metabox-layout-settings_section_group_li" ).show();
        }
        else
        {
            jQuery( ".redux-sidebar li#" + (i + 2) + "_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
        }
    } );

    jQuery( "#_architect-blueprints_navigation label" ).on( 'click', function ( e )
    {
        pzarc_show_hide_nav_tabs( e.target.innerText.trim() );
    } );

    function pzarc_show_hide_nav_tabs( tab )
    {
        if ( tab == 'None' )
        {
            jQuery( ".redux-sidebar li#3_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
            jQuery( ".redux-sidebar li#4_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
            jQuery( ".redux-sidebar li#0_box_redux-_architect-metabox-layout-settings_section_group_li" ).find('a').trigger("click");

        }
        else
        {
            var a = ["Pagination", "Navigator"];
            for ( var i = 1; i <= 2; i++ )
            {
                jQuery( ".redux-sidebar li#" + (i + 2) + "_box_redux-_architect-metabox-layout-settings_section_group_li" ).hide();
            }
            var j = a.indexOf( tab ) + 3;
            jQuery( ".redux-sidebar li#" + j + "_box_redux-_architect-metabox-layout-settings_section_group_li" ).show().find('a' ).trigger('click');
        }
    }

    // ********************************************************************************************
    // Show hide SECTION tabs
    // ********************************************************************************************
    jQuery( ".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( jQuery( "fieldset#_architect-blueprints_section-1-enable" ).find( 'input' ).val() == "1" );
    jQuery( ".redux-sidebar li#2_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( jQuery( "fieldset#_architect-blueprints_section-2-enable" ).find( 'input' ).val() == "1" );

    jQuery( "fieldset#_architect-blueprints_section-1-enable" ).on( 'click', function ()
    {
        var state = (jQuery( this ).find( 'input' ).val() == "1");
        jQuery( ".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( state ).find('a').trigger("click");

        if (state) { jQuery( ".redux-sidebar li#0_box_redux-_architect-metabox-layout-settings_section_group_li" ).find('a').trigger("click"); }
    } );

    jQuery( "fieldset#_architect-blueprints_section-2-enable" ).on( 'click', function ()
    {
        jQuery( ".redux-sidebar li#2_box_redux-_architect-metabox-layout-settings_section_group_li" ).toggle( jQuery( this ).find( 'input' ).val() == "1" ).find('a').trigger("click");
        jQuery( "#section-table-blueprints_section-start-content" ).toggle( jQuery( this ).find( 'input' ).val() == "1" );
        if (jQuery( this ).find( 'input' ).val() != "1") {
            jQuery( ".redux-sidebar li#0_box_redux-_architect-metabox-layout-settings_section_group_li" ).find('a').trigger("click");
        }
    } );

    // ********************************************************************************************
    // Show hide GENERAL SETTINGS sections
    // ********************************************************************************************
    jQuery( "table#section-table-blueprints_section-start-layout, div#section-blueprints_section-start-layout, div#section-blueprints_section-end-layout, table#section-table-blueprints_section-end-layout" ).show();
    jQuery( "table#section-table-blueprints_section-start-content,div#section-blueprints_section-start-content,div#section-blueprints_section-end-content,table#section-table-blueprints_section-end-content" ).hide();
    jQuery("fieldset#_architect-blueprints_section-start-layout, fieldset#_architect-blueprints_section-start-content,fieldset#_architect-blueprints_section-end-layout, fieldset#_architect-blueprints_section-end-content" ).hide();

    jQuery("#_architect-blueprints_tabs li" ).on("click",function(){

        var clickedOn = jQuery(this).find("span").text();
        if (clickedOn=="Layout") {
            jQuery( "table#section-table-blueprints_section-start-layout, div#section-blueprints_section-start-layout, div#section-blueprints_section-end-layout, table#section-table-blueprints_section-end-layout" ).show();
            jQuery( "table#section-table-blueprints_section-start-content,div#section-blueprints_section-start-content,div#section-blueprints_section-end-content,table#section-table-blueprints_section-end-content" ).hide();
        }
        if (clickedOn=="Content") {
            var tab = jQuery( "select#_blueprints_content-source-select" ).find("option:selected");
            var chosenOne = tab.index();
            console.log(chosenOne);

            jQuery( "table#section-table-blueprints_section-start-layout, div#section-blueprints_section-start-layout, div#section-blueprints_section-end-layout, table#section-table-blueprints_section-end-layout" ).hide();
            jQuery( "table#section-table-blueprints_section-start-content,div#section-blueprints_section-start-content,div#section-blueprints_section-end-content,table#section-table-blueprints_section-end-content" ).show();
            jQuery( ".redux-sidebar li#" + (chosenOne - 1) + "_box_redux-_architect-metabox-content-selections_section_group_li" ).find("a").trigger("click");
        }

    });



    // Show hide navigator tabs














    /*
     var $container = jQuery('#pzarc-sections-preview.pzarc-section-1');
     var $cell = jQuery('.pzarc-section-cell')
     // initialize Isotope
     $container.isotope({
     // options...
     resizable: false, // disable normal resizing
     // set columnWidth to a percentage of container width
     itemClass: 'pzarc-section-cell',
     masonry: { columnWidth: $container.width()/3, gutterWidth:20 }
     });

     // update columnWidth on window resize
     jQuery(window).smartresize(function(){
     $container.isotope({
     itemClass: 'pzarc-section-cell',
     // update columnWidth to a percentage of container width
     masonry: { columnWidth: $container.width()/3, gutterWidth:20 }
     });
     });
     */
//_pzarc_section-group-_pzarc_blueprint-cells-per-view-cmb-group-0-cmb-field-0
//_pzarc_section-group-_pzarc_blueprint-cells-per-view-cmb-group-1-cmb-field-0


//  init();
//  function init(){
//    pzarc_refresh_blueprint_layout(0);
//    pzarc_refresh_blueprint_layout(1);
//    pzarc_refresh_blueprint_layout(2);
//    pzarc_update_usage_info(jQuery("#_pzarc_blueprint-short-name-cmb-field-0").get(0));
//    pzarc_show_navtype();
//  }
//
//  jQuery('select#_pzarc_blueprint-navigation-cmb-field-0').on('change',function(){pzarc_show_navtype();});
//
//
//function pzarc_show_navtype() {
//  var navtype =  jQuery('select#_pzarc_blueprint-navigation-cmb-field-0').find('option:selected').get(0).value;
//
//  console.log(navtype);
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
//}
//
//  function pzarc_refresh_blueprint_layout(i) {
    //console.log(i,jQuery('#_pzarc_' + i + '-blueprint-cells-per-view-cmb-field-0').get(0));
//    pzarc_update_cell_count(i, jQuery('#_pzarc_' + i + '-blueprint-cells-per-view-cmb-field-0').get(0));
//    pzarc_update_cell_margin(i, jQuery('#_pzarc_' + i + '-blueprint-cells-vert-margin-cmb-field-0').get(0));
//    pzarc_update_cell_across(i, jQuery('#_pzarc_' + i + '-blueprint-cells-across-cmb-field-0').get(0));
//    pzarc_update_min_width(i, jQuery('#_pzarc_' + i + '-blueprint-min-cell-width-cmb-field-0').get(0));
//    pzarc_show_hide_section(i);
//  }
//
////  jQuery('#pzarc-sections-preview-0').resize(function(){//console.log(this.width);pzarc_refresh_blueprint_layout(0)});
////  jQuery('#pzarc-sections-preview-1').resize(function(){//console.log(this.width);pzarc_refresh_blueprint_layout(1)});
////  jQuery('#pzarc-sections-preview-2').resize(function(){//console.log(this.width);pzarc_refresh_blueprint_layout(2)});
//
//
//  for (var i = 0; i < 3; i++) {
//    jQuery('#_pzarc_' + i + '-blueprint-cells-vert-margin-cmb-field-0').change(function () {
//      pzarc_refresh_blueprint_layout(this.id.substr(7,1));
//    });
//    jQuery('#_pzarc_' + i + '-blueprint-cells-across-cmb-field-0').change(function () {
//      pzarc_refresh_blueprint_layout(this.id.substr(7,1));
//    });
//    jQuery('#_pzarc_' + i + '-blueprint-cells-per-view-cmb-field-0').change(function () {
//      pzarc_refresh_blueprint_layout(this.id.substr(7,1));
//    });
//    jQuery('#_pzarc_' + i + '-blueprint-min-cell-width-cmb-field-0').change(function () {
//      pzarc_refresh_blueprint_layout(this.id.substr(7,1));
//    });
//
//    jQuery('#_pzarc_' + i + '-blueprint-section-enable-cmb-field-0').change(function () {
//      var x = this.id.substr(7,1);
//      pzarc_show_hide_section(x);
//    });
//  }
//  jQuery('#_pzarc_blueprint-short-name-cmb-field-0').change(function () {
//    pzarc_update_usage_info(this);
//  });
//  function pzarc_update_usage_info(t){
//    //console.log(t.value);
//    jQuery('span.pzarc-shortname').text(t.value);
//  }
//
//
///* Switched to pixel based once, but not as fluid. Butwhat was it's advantage? Why did I switch? */
//  function pzarc_update_cell_margin(i,t) {
// //   console.log(i);
//    var cellsAcross = jQuery('#_pzarc_'+i+'-blueprint-cells-across-cmb-field-0').get(0).value;
//    var containerWidth = jQuery('.pzarc-section-'+i).width();
//    //  //console.log(containerWidth);
//    jQuery('#pzarc-sections-preview-'+i+' .pzarc-section-cell').each(function (index, value) {
// //     //console.log(((index+1)%cellsAcross),cellsAcross);
////      if (((index+1)%cellsAcross) != 0) {
//      jQuery(value).css({'marginRight': (t.value ) + '%'});
////      }
//    });
//  }
//
//  function pzarc_update_cell_across(i,t) {
// //console.log(i);
//    var containerWidth = jQuery('#pzarc-sections-preview-'+i).width();
////    //console.log(containerWidth);
//    var cellRightMargin = jQuery('#_pzarc_'+i+'-blueprint-cells-vert-margin-cmb-field-0').val();
//    var new_cell_width = (100/ t.value)- cellRightMargin;
//    // Can't use width(), it breaks when padding is set.
//    jQuery('#pzarc-sections-preview-'+i+' .pzarc-section-cell').css( {"width":new_cell_width + '%'});
//  }
//
//  function pzarc_update_cell_count(i,t) {
//   // console.log(i);
//    jQuery('.pzarc-section-'+i).empty();
//    var plugin_url = jQuery('.field.Pizazz_Layout_Field .plugin_url').get(0).textContent;
//    var show_count = (t.value==0?10: t.value);
//    for (var j = 1; j <= show_count; j++) {
//        jQuery('#pzarc-sections-preview-'+i).append('<div class="pzarc-section-cell-' + j + ' pzarc-section-cell" ></div>');
//    }
//  }
//
//  function pzarc_update_min_width(i,t) {
//   //console.log(i);
//    jQuery('#pzarc-sections-preview-'+i+' .pzarc-section-cell').css({'minWidth': t.value + 'px'});
//  }
//
//  function pzarc_show_hide_section(x) {
//    var y = parseInt(x)+1;
// //   console.log(x);
//    if (x==0){
//      jQuery('#pzarc-sections-preview-0').show();
//      jQuery('#blueprint-section-1.postbox').show();
//      jQuery('.item-blueprint-section-1').show();
//      return;
//    }
////    console.log(x,jQuery('#_pzarc_'+x+'-blueprint-section-enable-cmb-field-0').get(0).checked);
//
//    jQuery('#pzarc-sections-preview-'+x).toggle(jQuery('#_pzarc_'+x+'-blueprint-section-enable-cmb-field-0').get(0).checked);
//    jQuery('#blueprint-section-'+y+'.postbox').toggle(jQuery('#_pzarc_'+x+'-blueprint-section-enable-cmb-field-0').get(0).checked);
//    jQuery('.item-blueprint-section-'+y).fadeToggle(jQuery('#_pzarc_'+x+'-blueprint-section-enable-cmb-field-0').get(0).checked);
//
//    // Need to make this a little clevered
//    // needed this coz container was staying big
//    jQuery('.item-blueprint-wireframe-preview').trigger('click');
//
//  }

});

//id="_pzarc_blueprint-navigation-cmb-field-0"