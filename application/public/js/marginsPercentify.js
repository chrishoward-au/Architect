/**
 * Created by chrishoward on 12/07/15.
 */
(function ( $ )
{
  $.fn.marginsPercentify = function ( options )
  {

// Establish default settings/variables
// ====================================
    var settings = $.extend( {
      panel: '.panel',
      applyType: 'width',
      flexOnly:true
    }, options );

    var panelContainer = $( settings.panel ).parent();
    if (panelContainer.css("display")==='flex' || panelContainer.css("display")==='flex' || !flexOnly)
    {
      var panel = $( settings.panel );
      console.log( container, panel );
      if ( container && panel )
      {
        container.hide();
        var containerHeight = container.outerHeight();
        var containerWidth = container.outerWidth();
        var panelMTB = panel.css( ["margin-top", "margin-bottom"] );

        if ( panelMTB["margin-top"].substr( -1, 1 ) === "%" )
        {
          var mth = parseFloat( panelMTB["margin-top"] ) * containerHeight / 100;
          var mtw = parseFloat( panelMTB["margin-top"] ) * containerWidth / 100;
          panel.css( "margin-top", (settings.applyType === 'width' ? mtw : mth) + 'px' );
        }

        if ( panel["margin-bottom"].substr( -1, 1 ) === "%" )
        {
          var mbh = parseFloat( panelMTB["margin-bottom"] ) * containerHeight / 100;
          var mbw = parseFloat( panelMTB["margin-bottom"] ) * containerWidth / 100;
          panel.css( "margin-bottom", (settings.applyType === 'width' ? mbw : mbh) + 'px' );
        }

        container.show();
      }
    }
  };
}( jQuery ));