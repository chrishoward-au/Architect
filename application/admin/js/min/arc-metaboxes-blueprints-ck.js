jQuery(document).ready(function(){"use strict";function e(){var e=jQuery("select#_blueprints_content-source-select").find("option");e.each(function(e){console.log(e,this.value),0!==e&&"help"!==this.value&&this.selected&&console.log(e,this.value)})}function t(e){for(var t=["Defaults","Posts","Pages","Snippets","Galleries","Slides","Custom Post Types"],i=1;i<=t.length;i++)jQuery(".redux-sidebar li#"+(i-1)+"_box_redux-_architect-metabox-content-selections_section_group_li").hide();jQuery(".redux-sidebar li#"+t.indexOf(e)+"_box_redux-_architect-metabox-content-selections_section_group_li").show().find("a").trigger("click")}function i(e){if("None"===e)jQuery(".redux-sidebar li#3_box_redux-_architect-metabox-layout-settings_section_group_li").hide(),jQuery(".redux-sidebar li#4_box_redux-_architect-metabox-layout-settings_section_group_li").hide(),0===jQuery("#5_box_redux-_architect-metabox-layout-settings_section_group_li.active").length&&jQuery(".redux-sidebar li#0_box_redux-_architect-metabox-layout-settings_section_group_li").find("a").trigger("click");else{for(var t=["Pagination","Navigator"],i=1;2>=i;i++)jQuery(".redux-sidebar li#"+(i+2)+"_box_redux-_architect-metabox-layout-settings_section_group_li").hide();var n=t.indexOf(e)+3;jQuery(".redux-sidebar li#"+n+"_box_redux-_architect-metabox-layout-settings_section_group_li").show().find("a"),0===jQuery("#5_box_redux-_architect-metabox-layout-settings_section_group_li.active").length&&jQuery(".redux-sidebar li#"+n+"_box_redux-_architect-metabox-layout-settings_section_group_li").find("a").trigger("click")}}function n(e){var t=jQuery(e).val();jQuery("#pzarc-navigator-preview .pzarc-sections").hide(),"none"!==t&&jQuery("#pzarc-navigator-preview .pzarc-section-"+t).toggle(jQuery(e).checked)}function r(){jQuery("input#_blueprints_short-name-text").change(function(){s(this)}),n()}function o(e){a(e,jQuery("input#_blueprints_section-"+e+"-panels-per-view")),c(e,jQuery("input#_blueprints_section-"+e+"-panels-vert-margin")),u(e,jQuery("input#_blueprints_section-"+e+"-columns")),_(e,jQuery("input#_blueprints_section-"+e+"-min-panel-width")),l(e)}function s(e){jQuery("span.pzarc-shortname").text(jQuery(e).val())}function c(e,t){var i=jQuery("input#_blueprints_section-"+e+"-columns").value;jQuery("#pzarc-sections-preview-"+e+" .pzarc-section-cell").css({marginRight:t.val()+"%"})}function u(e,t){var i=jQuery("#pzarc-sections-preview-"+e).width(),n=jQuery("#_blueprints_section-"+e+"-panels-vert-margin").val(),r=100/t.val()-n;jQuery("#pzarc-sections-preview-"+e+" .pzarc-section-cell").css({width:r+"%"})}function a(e,t){jQuery(".pzarc-section-"+e).empty();for(var i=0===t.val()?10:t.val(),n=1;i>=n;n++)jQuery("#pzarc-sections-preview-"+e).append('<div class="pzarc-section-cell-'+n+' pzarc-section-cell" ></div>')}function _(e,t){jQuery("#pzarc-sections-preview-"+e+" .pzarc-section-cell").css({minWidth:t.value+"px"})}function l(e){var t=parseInt(e)+1;return 0===e?(jQuery("#pzarc-sections-preview-0").show(),jQuery("#blueprint-section-1.postbox").show(),void jQuery(".item-blueprint-section-1").show()):(jQuery("#pzarc-sections-preview-"+e).toggle(jQuery("input#_blueprints_section-"+e+"-enable").val()),jQuery("#blueprint-section-"+t+".postbox").toggle(jQuery("input#_blueprints_section-"+e+"-enable").val()),jQuery(".item-blueprint-section-"+t).fadeToggle(jQuery("input#_blueprints_section-"+e+"-enable").val()),void jQuery(".item-blueprint-wireframe-preview").trigger("click"))}e(),jQuery("select#_blueprints_content-source-select").on("click",function(){t(jQuery(this).find("option:selected").text().trim())}),jQuery("#_architect-_blueprints_navigation input").each(function(e){return 0===e&&this.checked?(jQuery(".redux-sidebar li#3_box_redux-_architect-metabox-layout-settings_section_group_li").hide(),void jQuery(".redux-sidebar li#4_box_redux-_architect-metabox-layout-settings_section_group_li").hide()):void(this.checked?jQuery(".redux-sidebar li#"+(e+2)+"_box_redux-_architect-metabox-layout-settings_section_group_li").show():jQuery(".redux-sidebar li#"+(e+2)+"_box_redux-_architect-metabox-layout-settings_section_group_li").hide())}),jQuery("#_architect-_blueprints_navigation label").on("click",function(e){i(e.target.innerText.trim())}),jQuery(".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li").toggle("1"===jQuery("fieldset#_architect-_blueprints_section-1-enable").find("input").val()),jQuery(".redux-sidebar li#2_box_redux-_architect-metabox-layout-settings_section_group_li").toggle("1"===jQuery("fieldset#_architect-_blueprints_section-2-enable").find("input").val()),jQuery("fieldset#_architect-_blueprints_section-1-enable").on("click",function(){var e="1"===jQuery(this).find("input").val();jQuery(".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li").toggle(e),0===jQuery("#5_box_redux-_architect-metabox-layout-settings_section_group_li.active").length&&jQuery(".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li").find("a").trigger("click"),e&&(jQuery(".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li").toggle(e),o(1),0===jQuery("#5_box_redux-_architect-metabox-layout-settings_section_group_li.active").length&&jQuery(".redux-sidebar li#1_box_redux-_architect-metabox-layout-settings_section_group_li").find("a").trigger("click"))}),jQuery("fieldset#_architect-_blueprints_section-2-enable").on("click",function(){o(1),jQuery(".redux-sidebar li#2_box_redux-_architect-metabox-layout-settings_section_group_li").toggle("1"===jQuery(this).find("input").val()),0===jQuery("#5_box_redux-_architect-metabox-layout-settings_section_group_li.active").length&&jQuery(".redux-sidebar li#2_box_redux-_architect-metabox-layout-settings_section_group_li").find("a").trigger("click"),jQuery("#section-table-blueprints_section-start-content").toggle("1"===jQuery(this).find("input").val()),"1"!==jQuery(this).find("input").val()&&0===jQuery("#5_box_redux-_architect-metabox-layout-settings_section_group_li.active").length&&jQuery(".redux-sidebar li#0_box_redux-_architect-metabox-layout-settings_section_group_li").find("a").trigger("click")}),jQuery("table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-table-_blueprints_section-end-layout").show(),jQuery("table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-table-_blueprints_section-end-content").hide(),jQuery("fieldset#_architect-_blueprints_section-start-layout, fieldset#_architect-_blueprints_section-start-content,fieldset#_architect-_blueprints_section-end-layout, fieldset#_architect-_blueprints_section-end-content").hide(),jQuery("#_architect-_blueprints_tabs li").on("click",function(){var e=jQuery(this).find("span").text().trim();if("Layout"===e&&(jQuery("table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-table-_blueprints_section-end-layout").show(),jQuery("table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-table-_blueprints_section-end-content").hide()),"Content"===e){var t=jQuery("select#_blueprints_content-source-select").find("option:selected"),i=t.index();jQuery("table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-_table-blueprints_section-end-layout").hide(),jQuery("table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-_table-blueprints_section-end-content").show(),jQuery(".redux-sidebar li#"+(i-1)+"_box_redux-_architect-metabox-content-selections_section_group_li").find("a").trigger("click")}}),jQuery("fieldset#_architect-_blueprints_navigation input").on("change",function(){n(this)}),r()}),jQuery(window).load(function(){0===jQuery("#0_box_redux-_architect-metabox-layout-settings_section_group_li.active").length&&jQuery("#0_box_redux-_architect-metabox-layout-settings_section_group_li").find("a").trigger("click")});