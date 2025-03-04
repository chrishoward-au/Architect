jQuery( document ).ready( function ()
{
  "use strict";

  /**
   * Set validation on panel shortname. Once Redux gets  working, can remove this.
   */
  jQuery( "input#_panels_settings_short-name-text" ).attr( "required", "required" );
  jQuery( "input#_panels_settings_short-name-text" ).attr( "pattern", "[a-zA-Z0-9\-\_]+" );
  // Weird. Was this, then that ^ Now this again
  jQuery( "input#_panels_settings_short-name" ).attr( "required", "required" );
  jQuery( "input#_panels_settings_short-name" ).attr( "pattern", "[a-zA-Z0-9\-\_]+" );

  // This is necessary when no tabs to switch with.
//  jQuery('#redux-_architect-metabox-panels-design').show();

 pzarc_update_tabs_to_show( jQuery('#_architect-_panels_design_components-to-show .buttonset .buttonset-item') );

  /** ***********************************************************************************************************************
   * Update status message and field data
   */
  function pzarc_update_status( cell_layout )
  {
    var showing = "";
    jQuery.each( cell_layout, function ( index, value )
    {
      //console.log(value);
      if ( value.show )
      {
        index = ('image' === index) ? 'feature' : index;
        var component_width = value.width;
        var feature_location = jQuery( 'input[name="_architect[_panels_design_feature-location]"]:checked' );
        component_width = ('fill' === feature_location.val() && index=='feature') ? '100' : component_width;
        showing = showing + " <strong>" + index + ":</strong> " + component_width + "% &nbsp;&nbsp;&nbsp;";
      }
    } );
    jQuery( "p.pzarc-states" ).html( 'Order: '+showing );
    jQuery( 'input#_panels_design_preview-text' ).val( JSON.stringify( cell_layout ) );

    //var pztarget = jQuery( 'fieldset#_architect-_panels_design_components-to-show input#_panels_design_components-to-show-buttonsetimage' );
    //var features_row = jQuery( "fieldset#_architect-_panels_design_feature-location" ).parentsUntil( 'tbody' );
    //if ( 'checked' !== jQuery( pztarget ).attr( 'checked' ) )
    //{
    //  features_row.hide();
    //
    //}
    //else
    //{
    //  features_row.show();
    //
    //}

  }

  /** ***********************************************************************************************************************
   Process field values
   ***********************/


  // Hidden field containing the plugin url
  var plugin_url = jQuery( 'fieldset#_architect-_panels_design_preview .plugin_url' ).text();


  // Get the current order and widths from the hidden text field attached to preview layout box

  var initial_cell_layout = get_cell_layout();
  pzarc_update_status(initial_cell_layout );
  //jQuery( 'input[name="_architect[_panels_design_thumb-position]"]' ).on( 'change', function ()
  //{
  //    var buttonClicked = this.value;
  //    pzarc_update_thumb_position( buttonClicked );
  //} );


  /** ***********************************************************************************************************************/
  /** FEATURE LOCATION **/
  jQuery( 'input[name="_architect[_panels_design_feature-location]"]' ).on( 'click', function ( e )
  {
    var cell_layout = get_cell_layout();
    //jQuery( 'fieldset#_architect-_panels_design_components-to-show input#_panels_design_components-to-show-buttonsetimage' ).attr( 'checked', 'checked' );
    //jQuery( 'fieldset#_architect-_panels_design_components-to-show label[for="_panels_design_components-to-show-buttonsetimage"]' ).addClass( 'ui-state-active' );
    pzarc_update_feature( cell_layout );
    pzarc_update_tabs_to_show( e );
  } );
  /** ***********************************************************************************************************************/
  /** FEATURE IN **/
  jQuery( '#_architect-_panels_design_feature-in' ).on('click',function ( e )
  {
    var cell_layout = get_cell_layout();
    //jQuery( 'fieldset#_architect-_panels_design_components-to-show input#_panels_design_components-to-show-buttonsetimage' ).attr( 'checked', 'checked' );
    //jQuery( 'fieldset#_architect-_panels_design_components-to-show label[for="_panels_design_components-to-show-buttonsetimage"]' ).addClass( 'ui-state-active' );
    pzarc_update_feature( cell_layout );
  } );

  /** ***********************************************************************************************************************/
  /** FEATURE FLOAT **/
  jQuery( '#_architect-_panels_design_feature-float' ).on('click',function ( e )
  {
    var cell_layout = get_cell_layout();
    //jQuery( 'fieldset#_architect-_panels_design_components-to-show input#_panels_design_components-to-show-buttonsetimage' ).attr( 'checked', 'checked' );
    //jQuery( 'fieldset#_architect-_panels_design_components-to-show label[for="_panels_design_components-to-show-buttonsetimage"]' ).addClass( 'ui-state-active' );
    pzarc_update_feature( cell_layout );
  } );

  /** ***********************************************************************************************************************/
  /** FEATURE type **/
  jQuery( 'input[name="_architect[_panels_settings_feature-type]"]' ).on( 'click', function ()
  {
    var imgorvid = this.value;
    pzarc_update_feature_type( imgorvid );
  } );


  /** ***********************************************************************************************************************/
  /** COMPONENTS TO SHOW **/
  jQuery( 'fieldset#_architect-_panels_design_components-to-show input.buttonset-item' ).change( function ( e )
  {
    var pztarget = e.target;
    var targVal = getValue(pztarget);
    switch (targVal)
    {
      case 'image':
        var features_row = jQuery( "fieldset#_architect-_panels_design_feature-location" ).parentsUntil( 'tbody' );
        if ( 'checked' !== jQuery( pztarget ).attr( 'checked' ) )
        {
          features_row.hide();
        }
        else
        {
          features_row.show();
        }
        break;
      case 'custom1xxx':
      case 'custom2xxx':
      case 'custom3xxx':
        var custom_row = jQuery( "fieldset#_architect-_panels_design_custom-fields-count" ).parentsUntil( 'tbody' );
        if (
            'checked' === jQuery( 'fieldset#_architect-_panels_design_components-to-show input#_panels_design_components-to-show-buttonsetcustom1' ) ||
            'checked' === jQuery( 'fieldset#_architect-_panels_design_components-to-show input#_panels_design_components-to-show-buttonsetcustom2' ) ||
            'checked' === jQuery( 'fieldset#_architect-_panels_design_components-to-show input#_panels_design_components-to-show-buttonsetcustom3' )
        )
        {
          custom_row.show();
        }
        else
        {
          console.log(
              'checked' === jQuery( 'fieldset#_architect-_panels_design_components-to-show input#_panels_design_components-to-show-buttonsetcustom1' ),
              'checked' === jQuery( 'fieldset#_architect-_panels_design_components-to-show input#_panels_design_components-to-show-buttonsetcustom2' ),
              'checked' === jQuery( 'fieldset#_architect-_panels_design_components-to-show input#_panels_design_components-to-show-buttonsetcustom3' )
          );
          custom_row.hide();
        }
        break;
    }
    var cell_layout = get_cell_layout();
    pzarc_update_component_visibility( cell_layout, e );
    pzarc_update_tabs_to_show( e );
  } );


  /** ***********************************************************************************************************************/
  /** COMPONENTS POSITION **/
  jQuery( 'input[name="_architect[_panels_design_components-position]"]' ).on( 'click', function ()
  {
    var cell_layout = get_cell_layout();
    pzarc_update_component_location( cell_layout );
    pzarc_reposition_components( cell_layout, jQuery( 'fieldset#_architect-_panels_design_components-widths .redux-slider-label' ) );
    pzarc_update_feature( cell_layout );
  } );

  /** ***********************************************************************************************************************/
  /** PANEL HEIGHT TYPE **/
  jQuery( 'input[name="_architect[panels_settingds_panel-height-type]"]' ).on( 'click', function ()
  {
    // var cell_layout = get_cell_layout();
  } );


  /** ***********************************************************************************************************************/
  /** COMPONENTS WIDTH**/
  jQuery( 'fieldset#_architect-_panels_design_components-widths .redux-slider-label' ).on( "DOMSubtreeModified", function ()
  {
    var cell_layout = get_cell_layout();
    pzarc_reposition_components( cell_layout, this );
  } );

  /** ***********************************************************************************************************************/
  /** COMPONENTS NUDGE X **/
  jQuery( 'fieldset#_architect-_panels_design_components-nudge-x .redux-slider-label' ).on( "DOMSubtreeModified", function ()
  {
    var cell_layout = get_cell_layout();
    pzarc_update_components_nudge( cell_layout );
  } );

  /** ***********************************************************************************************************************/
  /** COMPONENTS NUDGE Y **/
  jQuery( 'fieldset#_architect-_panels_design_components-nudge-y  .redux-slider-label' ).on( "DOMSubtreeModified", function ()
  {
    var cell_layout = get_cell_layout();
    pzarc_update_components_nudge( cell_layout );
  } );

  /************************************************************************************************************************
   * SORT
   */
  jQuery( ".pzarc-dropzone .pzarc-content-area" ).sortable( {
    cursor: "move",
    opacity: 0.6,
    forceHelperSize: true,
    sort: function ()
    {
      // gets added unintentionally by droppable interacting with sortable
      // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
      jQuery( this ).removeClass( "ui-state-default" );
    },
    update: function ()
    {
      var cell_layout = get_cell_layout();
      cell_layout = pzarc_resort_components( cell_layout );
      pzarc_update_status( cell_layout );
    },

    /************************************************************************************************************************
     Do on create
     *************/
    create: function ()
    {
      var element_html = [];
      element_html.title = '<span class="pzarc-draggable pzarc-draggable-title" title= "Post title" data-idcode=title style="display: inline-block;font-weight:bold;font-size:15px;"><span>This is the title</span></span>';

      element_html.meta1 = '<span class="pzarc-draggable pzarc-draggable-meta1 pzarc-draggable-meta"  title= "Meta info 1" data-idcode=meta1 style="font-size:11px;"><span>Jan 1 2013</span></span>';

      element_html.meta2 = '<span class="pzarc-draggable pzarc-draggable-meta2 pzarc-draggable-meta" title= "Meta info 2"  data-idcode=meta2 style="font-size:11px;"><span>Categories - News, Sport</span></span>';

      element_html.image = '<span class="pzarc-draggable pzarc-draggable-image"  title= "Feature" data-idcode=image style="max-height:100px;overflow:hidden;"><span><img src="PZARC_PLUGIN_URL/shared/assets/images/sample-' + jQuery( 'input[name="_architect[_panels_settings_feature-type]"]:checked' ).get( 0 ).value + '.jpg" style="max-width:100%;" class="feature-image-video"/></span></span>';
      element_html.image = element_html.image.replace( /PZARC_PLUGIN_URL/g, plugin_url );

      element_html.content = '<span class="pzarc-draggable pzarc-draggable-content" title= "Full post content"  data-idcode=content style="font-size:13px;"><span><img  src="PZARC_PLUGIN_URL/shared/assets/images/sample-' + jQuery( 'input[name="_architect[_panels_settings_feature-type]"]:checked' ).get( 0 ).value + '.jpg" class="pzarc-align feature-image-video ' + jQuery( "select#_pzarc_layout-excerpt-thumb-cmb-field-0" ).val() + '" style="max-width:20%;"/><img src="PZARC_PLUGIN_URL/shared/assets/images/sample-in-content.jpg" style="max-width:30%;float:left;padding:5px;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;&bull;&nbsp;Cras semper sem hendrerit</li><li>&nbsp;&bull;&nbsp;Tortor porta at auctor</li></ul></span></span>';
      element_html.content = element_html.content.replace( /PZARC_PLUGIN_URL/g, plugin_url );

      element_html.excerpt = '<span class="pzarc-draggable pzarc-draggable-excerpt"  title= "Excerpt with featured image" data-idcode=excerpt style="font-size:13px;"><span><img  src="PZARC_PLUGIN_URL/shared/assets/images/sample-' + jQuery( 'input[name="_architect[_panels_settings_feature-type]"]:checked' ).get( 0 ).value + '.jpg" class="pzarc-align feature-image-video ' + jQuery( "select#_pzarc_layout-excerpt-thumb-cmb-field-0" ).val() + '" style="max-width:20%;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>';

      element_html.excerpt = element_html.excerpt.replace( /PZARC_PLUGIN_URL/g, plugin_url );

      element_html.meta3 = '<span class="pzarc-draggable pzarc-draggable-meta3 pzarc-draggable-meta"  title= "Meta info 3" data-idcode=meta3 style="font-size:11px;"><span>Comments: 27</span></span>';
      element_html.custom1 = '<span class="pzarc-draggable pzarc-draggable-custom1 pzarc-draggable-meta arc-pro-only"  title= "Custom field 1" data-idcode=custom1 style="font-size:11px;"><span>Custom content group 1</span></span>';
      element_html.custom2 = '<span class="pzarc-draggable pzarc-draggable-custom2 pzarc-draggable-meta arc-pro-only"  title= "Custom field 2" data-idcode=custom2 style="font-size:11px;"><span>Custom content group 2</span></span>';
      element_html.custom3 = '<span class="pzarc-draggable pzarc-draggable-custom3 pzarc-draggable-meta arc-pro-only"  title= "Custom field 3" data-idcode=custom3 style="font-size:11px;"><span>Custom content group 3</span></span>';

      var cells_layout = get_cell_layout();

      jQuery( ".pzarc-content-area.sortable" ).html( '' );
      jQuery.each( cells_layout, function ( index )
      {
        jQuery( ".pzarc-content-area.sortable" ).append( element_html[index] );
      } );
      var cell_layout = get_cell_layout();
      pzarc_update_component_location( cell_layout );
      pzarc_update_components_container_width(  );
      pzarc_update_components_nudge(  );
      pzarc_update_component_visibility( cell_layout );
//            pzarc_update_feature( cell_layout );
      pzarc_update_status( cell_layout );
    }
  } );


  /************************************************************************************************************************
   * Process resizing
   *
   */
  jQuery( ".pzarc-draggable" ).resizable( {
    handles: "e",
    containment: "parent",
    grid: [4, 1],
    minWidth: 10,
    maxWidth: 450,
    autoHide: true,
    resize: function ()
    {
      pzarc_resize_component( this );
    }
  } );


  /************************************************************************************************************************
   *
   * @param t
   */
  function pzarc_resize_component( t )
  {
    var zone = jQuery( t ).data( "idcode" );
    var components_width = jQuery( '.pzarc-content-area' ).width();
    var new_width = Math.floor( (jQuery( t ).width() / components_width) * 100 );
    var cell_layout = get_cell_layout();
    cell_layout = pzarc_update_component_width( cell_layout, zone, new_width );
    pzarc_update_status( cell_layout );
    // Resizable makes it a px. Reset it to %
    jQuery( t ).width( new_width + '%' );
  }

  /************************************************************************************************************************
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


  /************************************************************************************************************************
   * Update visibility
   */
  function pzarc_update_component_visibility( cell_layout )
  {
    var components_state = jQuery( "fieldset#_architect-_panels_design_components-to-show input.buttonset-item" );
    jQuery.each( components_state, function ( index, value )
    {

      var theValue = getValue(value);

      if ( theValue !== '' && theValue !== undefined)
      {
        cell_layout[theValue].show = value.checked;
        if ( value.checked )
        {
          jQuery( '.pzarc-draggable-' + theValue ).show();
          jQuery( '.pzarc-draggable-' + theValue ).css( 'width', cell_layout[theValue].width + '%' );

        }
        else
        {
          jQuery( '.pzarc-draggable-' + theValue ).hide();
        }
      }
    } );
    pzarc_update_feature_type( jQuery( 'input[name="_architect[_panels_settings_feature-type]"]:checked' ).get( 0 ).value );
    pzarc_update_feature( cell_layout );
    pzarc_update_status( cell_layout );
    return cell_layout;
  }

  /************************************************************************************************************************
   * Update component widths
   * Although this is a seemingly superfluous function, it does provide consistency and makes future changes easier.
   */
  function pzarc_update_component_width( cell_layout, zone, new_width )
  {
    cell_layout[zone].width = new_width;
    return cell_layout;
  }


  function pzarc_update_feature( cell_layout )
  {

    /************************************************************************************************************************
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
    jQuery( '.pzarc-draggable-image' ).removeClass( 'left right' );
    var imgBhnd = jQuery( '.pzarc-dropzone .pzgp-cell-image-behind' );
    imgBhnd.html( '' );
    jQuery( '.pzarc-draggable-image' ).hide();
    var isFeatureOn = jQuery( 'input#_panels_design_components-to-show-buttonsetimage' ).prop( 'checked' );
    if ( !isFeatureOn )
    {
      return;
    }


    switch (featureLocation)
    {

      case ('fill'):
        imgBhnd.html( '<img  class="feature-image-video" src="' + plugin_url + '/shared/assets/images/sample-' + jQuery( 'input[name="_architect[_panels_settings_feature-type]"]:checked' ).get( 0 ).value + '.jpg"/>' );
        imgBhnd.css( {
          'left': '0',
          'top': '0',
          'right': '',
          'bottom': '',
          'width': '100%',
          'height': 'auto',
          'position':'absolute'

        } );
        // jQuery( '.pzarc-dropzone .pzgp-cell-image-behind img' ).css( {
        //     'height': '300px',
        //     'width': ''
        // } );

        // TODO: Update image width to 100%?
        break;

      case 'components':
        jQuery( '.pzarc-draggable-image' ).show();
        if ( jQuery( '#_panels_design_feature-float-buttonsetright:checked' ).length ) {
          jQuery( '.pzarc-draggable-image' ).addClass('right');
        }
        break;

      case 'content-left':
        if ( jQuery( '#_panels_design_feature-in-buttonsetexcerpt:checked' ).length )
        {
          jQuery( '.pzarc-draggable-excerpt img.pzarc-align' ).removeClass( 'right none' ).addClass( 'left' );
        }
        if ( jQuery( '#_panels_design_feature-in-buttonsetcontent:checked' ).length )
        {
          jQuery( '.pzarc-draggable-content img.pzarc-align' ).removeClass( 'right none' ).addClass( 'left' );
        }
        break;

      case 'content-right':
        if (jQuery('#_panels_design_feature-in-buttonsetexcerpt:checked' ).length)
        {
          jQuery( '.pzarc-draggable-excerpt img.pzarc-align' ).removeClass( 'left none' ).addClass( 'right' );
        }
        if (jQuery('#_panels_design_feature-in-buttonsetcontent:checked' ).length)
        {
          jQuery( '.pzarc-draggable-content img.pzarc-align' ).removeClass( 'left none' ).addClass( 'right' );
        }        break;

      case 'float':
        /// TODO: Work out how to make before or after components group/
        imgBhnd.html( '<img  class="feature-image-video" src="' + plugin_url + 'shared/assets/images/sample-' + jQuery( 'input[name="_architect[_panels_settings_feature-type]"]:checked' ).get( 0 ).value + '.jpg"/>' );
        var zonesWidth = jQuery( '.pzarc-content-area' ).get( 0 ).clientWidth;
        var zonesHeight = jQuery( '.pzarc-content-area' ).get( 0 ).clientHeight;
        var sections_position = 'top';
        jQuery( 'input[name="_architect[_panels_design_components-position]"]' ).each( function ()
        {
          if ( this.checked )
          {
            sections_position = this.value;
          }
        } );
        var imgBhndImg = jQuery( '.pzarc-dropzone .pzgp-cell-image-behind img' );
        switch (sections_position)
        {
          case 'left':
            imgBhndImg.css( {
              'height': 'auto',
              'width': '100%',
              'left': '',
              'position':'relative'
            } );
            imgBhnd.css( {
              'width': (450 - zonesWidth) + 'px',
              'left': '',
              'right': '0',
              'top': '',
              'bottom': '',
              'position':'absolute'

            } );
            break;

          case 'right':
            imgBhndImg.css( {
              'height': 'auto',
              'width': '100%',
              'left': '',
              'position':'relative'

            } );
            imgBhnd.css( {
              'width': (450 - zonesWidth) + 'px',
              'right': '',
              'left': '0',
              'top': '',
              'bottom': '',
              'position':'absolute'

            } );
            break;

          case 'top':
            imgBhndImg.css( {
              'width': '100%',
              'height': '150px',
              'left': '',
              'position':'relative'

            } );
            imgBhnd.css( {
              'top': zonesHeight + 'px',
              'right': '',
              'left': '',
              'bottom': '',
              'width': '100%',
              'position':'relative'

            } );
            break;

          case 'bottom':
            imgBhndImg.css( {
              'width': '100%',
              'height': '150px',
              'left': '',
              'position':'relative'

            } );
            imgBhnd.css( {
              'bottom': zonesHeight + 'px',
              'right': '',
              'top': '0',
              'left': '',
              'width': '100%',
              'position':'relative'

            } );
            break;
        }
        break;
      case 'none':
        imgBhnd.html( '' );
        break;
    }
    pzarc_update_status( cell_layout );
  }

  function pzarc_update_component_location( cell_layout )
  {
    /************************************************************************************************************************
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
        jQuery( '.pzgp-cell-image-behind' ).css( 'right', '0' );
        break;
      case 'right':
        jQuery( '.pzarc-content-area' ).css( {
          bottom: '0',
          top: '0',
          left: '',
          right: '0'
        } );
        jQuery( '.pzgp-cell-image-behind' ).css( 'left', '0' );
        break;
    }
    pzarc_update_status( cell_layout );
  }

  function pzarc_update_components_container_width()
  {
    /************************************************************************************************************************
     // update components width
     **********************/
      // TODO: Get Dovy to change sliders to update inout val in real time
    jQuery( '.pzarc-content-area' ).css( 'width', jQuery( 'fieldset#_architect-_panels_design_components-widths .redux-slider-label' ).text() + '%' );


  }

  function pzarc_update_components_nudge()
  {
    /************************************************************************************************************************
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


  /************************************************************************************************************************
   * Update tabs to show
   */

  function pzarc_update_tabs_to_show( et )
  {
    var tabs = jQuery('#_architect-_blueprint_tabs_tabs');
    var els;
    if (undefined === et.target)
    {
      els = et;
    } else {
       els = et.target;
    }
    jQuery( els ).each( function ()
    {
      var tab_status = true;
       var tabVal = getValue(this);
      switch (tabVal)
      {
        case 'title':
          tab_status = (
              jQuery( 'input#_panels_design_components-to-show-buttonsettitle:checked' ).length === 1
          );
//          jQuery(tabs).find('#tab-titles' ).toggle(tab_status);

          var theTabTitles = jQuery( tabs ).find( '#tab-titles' );
          if (!tab_status) {
              theTabTitles.addClass('pztabs-unused' ).attr('title','Titles not used in this Blueprint');
            } else {
              theTabTitles.removeClass('pztabs-unused' ).removeAttr('title');
            }
          break;
        case 'meta1':
        case 'meta2':
        case 'meta3':
          tab_status = (
          jQuery( 'input#_panels_design_components-to-show-buttonsetmeta1:checked' ).length === 1 ||
          jQuery( 'input#_panels_design_components-to-show-buttonsetmeta2:checked' ).length === 1 ||
          jQuery( 'input#_panels_design_components-to-show-buttonsetmeta3:checked' ).length === 1
          );
      //    jQuery(tabs).find('#tab-meta' ).toggle(tab_status);
          var theTabMeta = jQuery(tabs).find('#tab-meta' );
          if (!tab_status) {
            theTabMeta.addClass('pztabs-unused').attr('title','No meta fields used in this Blueprint');
          } else {
            theTabMeta.removeClass('pztabs-unused').removeAttr('title');
          }
          break;
        case 'content':
        case 'excerpt':
          tab_status = (
          jQuery( 'input#_panels_design_components-to-show-buttonsetcontent:checked' ).length === 1 ||
          jQuery( 'input#_panels_design_components-to-show-buttonsetexcerpt:checked' ).length === 1);
       //   jQuery(tabs).find('#tab-body' ).toggle(tab_status);
          var theTabBody = jQuery(tabs).find('#tab-body' );
          if (!tab_status) {
            theTabBody.addClass('pztabs-unused').attr('title','Body/excerpt not used in this Blueprint');
          } else {
            theTabBody.removeClass('pztabs-unused').removeAttr('title');
          }
          break;
        case 'image':

          tab_status = (
          jQuery( 'input#_panels_design_components-to-show-buttonsetimage:checked' ).length === 1
          );
    //      jQuery(tabs).find('#tab-features' ).toggle(tab_status);
          var theTabFeatures = jQuery(tabs).find('#tab-features' );
          if (!tab_status) {
            theTabFeatures.addClass('pztabs-unused').attr('title','Featured image/video not used in this Blueprint');
          } else {
            theTabFeatures.removeClass('pztabs-unused').removeAttr('title');
          }
          break;
        case 'custom1':
        case 'custom2':
        case 'custom3':
          tab_status = (
          jQuery( 'input#_panels_design_components-to-show-buttonsetcustom1:checked' ).length === 1 ||
          jQuery( 'input#_panels_design_components-to-show-buttonsetcustom2:checked' ).length === 1 ||
          jQuery( 'input#_panels_design_components-to-show-buttonsetcustom3:checked' ).length === 1
          );
        //  jQuery(tabs).find('#tab-customfields' ).toggle(tab_status);
          var theTabCustomFields = jQuery(tabs).find('#tab-customfields' );
          if (!tab_status) {
            theTabCustomFields.addClass('pztabs-unused').attr('title','No custom fields used in this Blueprint');
          } else {
            theTabCustomFields.removeClass('pztabs-unused').removeAttr('title');
          }
          break;

      }
    } );

  }

  /************************************************************************************************************************
   *
   * @param imgorvid
   */
  function pzarc_update_feature_type( imgorvid )
  {
    jQuery( 'img.feature-image-video' ).each( function ()
    {
      if ( imgorvid === 'image' )
      {
        this.src = this.src.replace( /sample-video.jpg/g, "sample-image.jpg" );
      }
      else if ( imgorvid === 'video' )
      {
        this.src = this.src.replace( /sample-image.jpg/g, "sample-video.jpg" );
      }
    } );
  }

  function pzarc_reposition_components( cell_layout, t )
  {

    pzarc_update_components_container_width( cell_layout );

    var feature = jQuery( '.pzgp-cell-image-behind' ).detach();
    var components = jQuery( '.pzarc-content-area' ).detach();

    if ( 1 === jQuery( 'input#_panels_design_feature-location-buttonsetfloat:checked' ).length )
    {
      switch (true)
      {
        case ( (1 === jQuery( 'input#_panels_design_components-position-buttonsetleft:checked' ).length || 1 === jQuery( 'input#_panels_design_components-position-buttonsetright:checked' ).length) ):
          // UPDATE
          jQuery( '.pzarc-dropzone' ).append( feature ).append( components );
          jQuery( '.pzgp-cell-image-behind' ).width( 100 - t.textContent + '%' ).css( 'position', 'absolute' );
          jQuery( '.pzarc-content-area' ).css( 'position', 'absolute' );
          break;
        case ( (1 === jQuery( 'input#_panels_design_components-position-buttonsetbottom:checked' ).length)):
          jQuery( '.pzarc-dropzone' ).append( feature ).append( components );
          jQuery( '.pzarc-content-area' ).css( 'position', 'relative' )
          jQuery( '.pzgp-cell-image-behind' ).css( 'position', 'relative' );
          break;

        case ( (1 === jQuery( 'input#_panels_design_components-position-buttonsettop:checked' ).length)):
          jQuery( '.pzarc-dropzone' ).append( components ).append( feature );
          jQuery( '.pzarc-content-area' ).css( 'position', 'relative' )
          jQuery( '.pzgp-cell-image-behind' ).css( 'position', 'relative' );

          break;

      }

    }
    else
    {
      jQuery( '.pzarc-dropzone' ).append( feature ).append( components );
      jQuery( '.pzgp-cell-image-behind' ).css( 'position', 'absolute' );
      jQuery( '.pzarc-content-area' ).css( 'position', 'absolute' );

    }

  }

  /**
   *
   */
  function get_cell_layout() {
    var cell_layout_serialized = jQuery( '#_panels_design_preview-text' ).val();
    var cell_layout = jQuery.parseJSON( cell_layout_serialized );
    return cell_layout;
  }

  /**
   *
   * @param t
   * @returns {*|string}
   */
  function getValue(t){
    return (jQuery(t).data("val") === undefined ?t.value:jQuery(t).data("val"));
  }

} )
; // End


/************************************************************************************************************************
 */
jQuery( window ).load( function ()
{
  if ( jQuery( '#redux-_architect-metabox-panels-design ul.redux-group-menu li.active' ).length === 0 )
  {
    jQuery( '#0_box_redux-_architect-metabox-panels-design_section_group_li' ).find( 'a' ).trigger( 'click' );
  }
} );

// Nothing past here!

