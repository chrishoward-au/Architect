
/* global redux_change, wp */

jQuery(function($){
    "use strict";
    
    $.reduxMetaBoxes = $.reduxMetaBoxes || {};
    
    $(document).ready(function () {

         $.reduxMetaBoxes.init();
    });

    /**
    * Redux Metaboxes
    * Dependencies      : jquery
    * Feature added by  : Dovy Paukstys
    * Date              : 19 Feb. 2014
    */
    $.reduxMetaBoxes.init = function(){

        $('.redux-container').each(function() {
            if ($(this).hasClass('redux-has-sections')) {
                $(this).parents('.postbox:first').find('h3.hndle').attr('class', 'redux-hndle');  
            }
        });
    };

});