jQuery(document).ready(function(){"use strict";function e(){var e=jQuery("fieldset#_architect-_blueprints_content-source input:checked").val();t(e)}function t(e){var t=jQuery("fieldset#_architect-_blueprints_content-source input");t.each(function(e){jQuery(".redux-sidebar li#_"+jQuery(this).val()+"_box_redux-_architect-metabox-content-selections_section_group_li").hide()}),jQuery(".redux-sidebar li#_"+e+"_box_redux-_architect-metabox-content-selections_section_group_li").show()}function i(e){switch(jQuery("#_slidertabbed_box_redux-_architect-metabox-layout-settings_section_group_li").hide(),jQuery("#_tabular_box_redux-_architect-metabox-layout-settings_section_group_li").hide(),jQuery("#_accordion_box_redux-_architect-metabox-layout-settings_section_group_li").hide(),jQuery(".redux-sidebar li#_section2_box_redux-_architect-metabox-layout-settings_section_group_li").toggle("1"===jQuery("fieldset#_architect-_blueprints_section-1-enable").find("input").val()),jQuery(".redux-sidebar li#_section3_box_redux-_architect-metabox-layout-settings_section_group_li").toggle("1"===jQuery("fieldset#_architect-_blueprints_section-2-enable").find("input").val()),jQuery("fieldset#_architect-_blueprints_section-1-enable").toggle(!0).parent().toggle(!0),jQuery("fieldset#_architect-_blueprints_section-2-enable").toggle(!0).parent().toggle(!0),jQuery("fieldset#_architect-_blueprints_pagination").toggle(!0).parent().toggle(!0),jQuery("fieldset#_architect-_blueprints_pager").toggle(!0).parent().toggle(!0),jQuery("fieldset#_architect-_blueprints_pager-single").toggle(!0).parent().toggle(!0),jQuery("fieldset#_architect-_blueprints_pager-archives").toggle(!0).parent().toggle(!0),jQuery("fieldset#_architect-_blueprints_pager-location").toggle(!0).parent().toggle(!0),e){case"slider":case"tabbed":jQuery("#_slidertabbed_box_redux-_architect-metabox-layout-settings_section_group_li").show(),jQuery("#_section2_box_redux-_architect-metabox-layout-settings_section_group_li").hide(),jQuery("#_section3_box_redux-_architect-metabox-layout-settings_section_group_li").hide(),jQuery("fieldset#_architect-_blueprints_section-1-enable").toggle(!1).parent().toggle(!1),jQuery("fieldset#_architect-_blueprints_section-2-enable").toggle(!1).parent().toggle(!1),jQuery("fieldset#_architect-_blueprints_pagination").toggle(!1).parent().toggle(!1),jQuery("fieldset#_architect-_blueprints_pager").toggle(!1).parent().toggle(!1),jQuery("fieldset#_architect-_blueprints_pager-single").toggle(!1).parent().toggle(!1),jQuery("fieldset#_architect-_blueprints_pager-archives").toggle(!1).parent().toggle(!1),jQuery("fieldset#_architect-_blueprints_pager-location").toggle(!1).parent().toggle(!1);break;case"table":jQuery("#_tabular_box_redux-_architect-metabox-layout-settings_section_group_li").show();break;case"accordion":jQuery("#_accordion_box_redux-_architect-metabox-layout-settings_section_group_li").show()}}function r(e){var t=jQuery(e).val();jQuery("#pzarc-navigator-preview .pzarc-sections").hide(),"none"!==t&&jQuery("#pzarc-navigator-preview .pzarc-section-"+t).toggle(jQuery(e).checked)}function n(){jQuery(".redux-sidebar li#_section2_box_redux-_architect-metabox-layout-settings_section_group_li").toggle("1"===jQuery("fieldset#_architect-_blueprints_section-1-enable").find("input").val()),jQuery(".redux-sidebar li#_section3_box_redux-_architect-metabox-layout-settings_section_group_li").toggle("1"===jQuery("fieldset#_architect-_blueprints_section-2-enable").find("input").val()),jQuery("input#_blueprints_short-name").change(function(){c(this)}),c(jQuery("input#_blueprints_short-name")),r();var e=jQuery("#_architect-_blueprints_section-0-layout-mode :checked");e.length>0&&i(e.get(0).value),jQuery(".redux-sidebar li#_section1_box_redux-_architect-metabox-layout-settings_section_group_li").find("a").trigger("click"),jQuery(".redux-sidebar li#_general_box_redux-_architect-metabox-content-selections_section_group_li").find("a").trigger("click")}function o(e){u(e,jQuery("input#_blueprints_section-"+e+"-panels-per-view")),s(e,jQuery("input#_blueprints_section-"+e+"-panels-vert-margin")),_(e,jQuery("input#_blueprints_section-"+e+"-columns")),l(e,jQuery("input#_blueprints_section-"+e+"-min-panel-width")),a(e)}function c(e){jQuery("span.pzarc-shortname").text(jQuery(e).val())}function s(e,t){var i=jQuery("input#_blueprints_section-"+e+"-columns").value;jQuery("#pzarc-sections-preview-"+e+" .pzarc-section-cell").css({marginRight:t.val()+"%"})}function _(e,t){var i=jQuery("#pzarc-sections-preview-"+e).width(),r=jQuery("#_blueprints_section-"+e+"-panels-vert-margin").val(),n=100/t.val()-r;jQuery("#pzarc-sections-preview-"+e+" .pzarc-section-cell").css({width:n+"%"})}function u(e,t){jQuery(".pzarc-section-"+e).empty();for(var i=0===t.val()?10:t.val(),r=1;i>=r;r++)jQuery("#pzarc-sections-preview-"+e).append('<div class="pzarc-section-cell-'+r+' pzarc-section-cell" ></div>')}function l(e,t){jQuery("#pzarc-sections-preview-"+e+" .pzarc-section-cell").css({minWidth:t.value+"px"})}function a(e){var t=parseInt(e)+1;return 0===e?(jQuery("#pzarc-sections-preview-0").show(),jQuery("#blueprint-section-1.postbox").show(),void jQuery(".item-blueprint-section-1").show()):(jQuery("#pzarc-sections-preview-"+e).toggle(jQuery("input#_blueprints_section-"+e+"-enable").val()),jQuery("#blueprint-section-"+t+".postbox").toggle(jQuery("input#_blueprints_section-"+e+"-enable").val()),jQuery(".item-blueprint-section-"+t).fadeToggle(jQuery("input#_blueprints_section-"+e+"-enable").val()),void jQuery(".item-blueprint-wireframe-preview").trigger("click"))}jQuery("input#_blueprints_short-name").attr("required","required"),jQuery("input#_blueprints_short-name").attr("pattern","[a-zA-Z0-9-_]+"),e(),jQuery("fieldset#_architect-_blueprints_content-source").on("change",function(){t(jQuery(this).find(":checked").val().trim())}),jQuery("fieldset#_architect-_blueprints_section-1-enable").on("click",function(){var e="1"===jQuery(this).find("input").val();jQuery(".redux-sidebar li#_section2_box_redux-_architect-metabox-layout-settings_section_group_li").toggle(e),jQuery(".redux-sidebar li#_styling_section_2_box_redux-_architect-metabox-blueprint-stylings_section_group_li").toggle(e),1===jQuery("#_section2_box_redux-_architect-metabox-layout-settings_section_group_li.active").length&&jQuery(".redux-sidebar li#_section1_box_redux-_architect-metabox-layout-settings_section_group_li").find("a").trigger("click"),e&&jQuery(".redux-sidebar li#_section2_box_redux-_architect-metabox-layout-settings_section_group_li").toggle(e)}),jQuery("fieldset#_architect-_blueprints_section-2-enable").on("click",function(){var e="1"===jQuery(this).find("input").val();jQuery(".redux-sidebar li#_section3_box_redux-_architect-metabox-layout-settings_section_group_li").toggle(e),jQuery(".redux-sidebar li#_styling_section_3_box_redux-_architect-metabox-blueprint-stylings_section_group_li").toggle(e),1===jQuery("#_section3_box_redux-_architect-metabox-layout-settings_section_group_li.active").length&&jQuery(".redux-sidebar li#_section1_box_redux-_architect-metabox-layout-settings_section_group_li").find("a").trigger("click"),e&&jQuery(".redux-sidebar li#_section3_box_redux-_architect-metabox-layout-settings_section_group_li").toggle(e)}),jQuery("#_architect-_blueprints_section-0-layout-mode").on("click",function(){var e=jQuery("#_architect-_blueprints_section-0-layout-mode :checked").get(0).value;i(e)}),jQuery("table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-table-_blueprints_section-end-layout").show(),jQuery("table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-table-_blueprints_section-end-content").hide(),jQuery("fieldset#_architect-_blueprints_section-start-layout, fieldset#_architect-_blueprints_section-start-content,fieldset#_architect-_blueprints_section-end-layout, fieldset#_architect-_blueprints_section-end-content").hide(),jQuery("#_architect-_blueprints_tabs li").on("click",function(){var e=jQuery(this).find("span").text().trim();if("Layout"===e&&(jQuery("table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-table-_blueprints_section-end-layout").show(),jQuery("table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-table-_blueprints_section-end-content").hide()),"Content"===e){var t=jQuery("select#_blueprints_content-source-select").find("option:selected"),i=t.index();jQuery("table#section-table-_blueprints_section-start-layout, div#section-_blueprints_section-start-layout, div#section-_blueprints_section-end-layout, table#section-_table-blueprints_section-end-layout").hide(),jQuery("table#section-table-_blueprints_section-start-content,div#section-_blueprints_section-start-content,div#section-_blueprints_section-end-content,table#section-_table-blueprints_section-end-content").show(),jQuery(".redux-sidebar li#"+(i-1)+"_box_redux-_architect-metabox-content-selections_section_group_li").find("a").trigger("click")}}),jQuery("fieldset#_architect-_blueprints_navigation input").on("change",function(){r(this)}),n()}),jQuery(window).load(function(){0===jQuery("#0_box_redux-_architect-metabox-layout-settings_section_group_li.active").length&&jQuery("#0_box_redux-_architect-metabox-layout-settings_section_group_li").find("a").trigger("click")});