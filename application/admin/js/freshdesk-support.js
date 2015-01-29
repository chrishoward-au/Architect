/**
 * Created by chrishoward on 22/01/15.
 */

jQuery( document ).ready( function ()
{
  try
  {
    FreshWidget.init( "", {
      "queryString": "&widgetType=popup&searchArea=no",
      "widgetType": "popup",
      "buttonType": "text",
      "buttonText": "Architect Support",
      "buttonColor": "white",
      "buttonBg": "#268fa5",
      "alignment": "1",
      "offset": "70%",
      "formHeight": "500px",
      "url": "https://pizazzwp.freshdesk.com"
    } );
  } catch(e) {

  }
} );
