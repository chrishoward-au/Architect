!function($){$.fn.marginsPercentify=function(a){var t=$.extend({panel:".panel",applyType:"width",flexOnly:!0},a),n=$(t.panel).parent();if("flex"===n.css("display")||"flex"===n.css("display")||!flexOnly){var o=$(t.panel);if(console.log(container,o),container&&o){container.hide();var r=container.outerHeight(),e=container.outerWidth(),i=o.css(["margin-top","margin-bottom"]);if("%"===i["margin-top"].substr(-1,1)){var p=parseFloat(i["margin-top"])*r/100,s=parseFloat(i["margin-top"])*e/100;o.css("margin-top",("width"===t.applyType?s:p)+"px")}if("%"===o["margin-bottom"].substr(-1,1)){var l=parseFloat(i["margin-bottom"])*r/100,m=parseFloat(i["margin-bottom"])*e/100;o.css("margin-bottom",("width"===t.applyType?m:l)+"px")}container.show()}}}}(jQuery);