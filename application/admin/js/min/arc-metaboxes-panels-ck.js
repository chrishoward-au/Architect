jQuery(document).ready(function(){"use strict";function e(){}function a(e){var a={},t=jQuery(".pzarc-dropzone .pzarc-content-area").sortable("toArray",{attribute:"data-idcode"});return jQuery.each(t,function(t,r){a[r]=e[r]}),a}function t(e){var a="";jQuery.each(e,function(e,t){t.show&&(a=a+" <strong>"+e+":</strong> "+t.width+"% &nbsp;&nbsp;&nbsp;")}),jQuery("p.pzarc-states").html(a),jQuery("input#_panels_design_preview-text").val(JSON.stringify(e))}function r(e){var a=jQuery("fieldset#_architect-_panels_design_components-to-show input");return jQuery.each(a,function(a,t){""!=t.value&&(e[t.value].show=t.checked,t.checked?(jQuery(".pzarc-draggable-"+t.value).show(),jQuery(".pzarc-draggable-"+t.value).css("width",e[t.value].width+"%")):jQuery(".pzarc-draggable-"+t.value).hide())}),t(e),e}function n(e,a,t){return e[a].width=t,e}function s(e){var a="none";switch(jQuery('input[name="_architect[_panels_design_background-position]"]').each(function(){this.checked&&(a=this.value)}),a){case"fill":jQuery(".pzarc-dropzone .pzgp-cell-image-behind").html('<img src="'+d+'/shared/assets/images/sample-image.jpg"/>'),jQuery(".pzarc-dropzone .pzgp-cell-image-behind").css({left:"0",top:"0",right:"",bottom:""}),jQuery(".pzarc-dropzone .pzgp-cell-image-behind img").css({height:"300px",width:""});break;case"align":jQuery(".pzarc-dropzone .pzgp-cell-image-behind").html('<img src="'+d+'shared/assets/images/sample-image.jpg"/>');var t=jQuery(".pzarc-content-area").width(),r=jQuery(".pzarc-content-area").height(),n=400-t,s=300-r,i="top";switch(jQuery('input[name="_architect[_panels_design_components-position]"]').each(function(){this.checked&&(i=this.value)}),i){case"left":jQuery(".pzarc-dropzone .pzgp-cell-image-behind img").css({height:"300px",width:"",left:""}),jQuery(".pzarc-dropzone .pzgp-cell-image-behind").css({left:t+"px",right:"",top:"",bottom:""});break;case"right":jQuery(".pzarc-dropzone .pzgp-cell-image-behind img").css({height:"300px",width:"",left:t+"px"}),jQuery(".pzarc-dropzone .pzgp-cell-image-behind").css({right:t+"px",left:"",top:"",bottom:""});break;case"top":jQuery(".pzarc-dropzone .pzgp-cell-image-behind img").css({width:"400px",height:"",left:""}),jQuery(".pzarc-dropzone .pzgp-cell-image-behind").css({top:r+"px",right:"",left:"",bottom:""});break;case"bottom":jQuery(".pzarc-dropzone .pzgp-cell-image-behind img").css({width:"400px",height:"",left:""}),jQuery(".pzarc-dropzone .pzgp-cell-image-behind").css({bottom:r+"px",right:"",top:"0",left:""})}break;case"none":jQuery(".pzarc-dropzone .pzgp-cell-image-behind").html("")}}function i(e){var a="top";switch(jQuery('input[name="_architect[_panels_design_components-position]"]').each(function(){this.checked&&(a=this.value)}),a){case"bottom":jQuery(".pzarc-content-area").css({bottom:"0",top:"",left:"",right:""});break;case"top":jQuery(".pzarc-content-area").css({bottom:"",top:"0",left:"",right:""});break;case"left":jQuery(".pzarc-content-area").css({bottom:"0",top:"0",left:"0",right:""});break;case"right":jQuery(".pzarc-content-area").css({bottom:"0",top:"0",left:"",right:"0"})}s(e)}function c(e){jQuery(".pzarc-content-area").css("width",jQuery("fieldset#_architect-_panels_design_components-widths .redux-slider-label").text()+"%")}function p(e){}function o(e){var a=[jQuery("fieldset#_architect-_panels_design_components-nudge-x .redux-slider-label").text(),jQuery("fieldset#_architect-_panels_design_components-nudge-y .redux-slider-label").text()],t="top";jQuery('input[name="_architect[_panels_design_components-position]"]').each(function(){this.checked&&(t=this.value)}),"left"===t?(jQuery(".pzarc-content-area").css("marginLeft",a[0]+"%"),jQuery(".pzarc-content-area").css("marginTop",a[1]+"%")):"top"===t?(jQuery(".pzarc-content-area").css("marginLeft",a[0]+"%"),jQuery(".pzarc-content-area").css("marginTop",a[1]+"%")):"right"===t?(jQuery(".pzarc-content-area").css("marginRight",a[0]+"%"),jQuery(".pzarc-content-area").css("marginTop",a[1]+"%")):"bottom"===t&&(jQuery(".pzarc-content-area").css("marginLeft",a[0]+"%"),jQuery(".pzarc-content-area").css("marginBottom",a[1]+"%"))}function l(e){r(e)}function g(e){"left"===e?(jQuery(".pzarc-draggable-excerpt img.pzarc-align").removeClass("right"),jQuery(".pzarc-draggable-excerpt img.pzarc-align").removeClass("none"),jQuery(".pzarc-draggable-excerpt img.pzarc-align").addClass("left"),jQuery(".pzarc-draggable-content img.pzarc-align").removeClass("right"),jQuery(".pzarc-draggable-content img.pzarc-align").removeClass("none"),jQuery(".pzarc-draggable-content img.pzarc-align").addClass("left")):"right"===e?(jQuery(".pzarc-draggable-excerpt img.pzarc-align").removeClass("left"),jQuery(".pzarc-draggable-excerpt img.pzarc-align").removeClass("none"),jQuery(".pzarc-draggable-excerpt img.pzarc-align").addClass("right"),jQuery(".pzarc-draggable-content img.pzarc-align").removeClass("left"),jQuery(".pzarc-draggable-content img.pzarc-align").removeClass("none"),jQuery(".pzarc-draggable-content img.pzarc-align").addClass("right")):(jQuery(".pzarc-draggable-excerpt img.pzarc-align").addClass("none"),jQuery(".pzarc-draggable-excerpt img.pzarc-align").removeClass("left"),jQuery(".pzarc-draggable-excerpt img.pzarc-align").removeClass("right"),jQuery(".pzarc-draggable-content img.pzarc-align").addClass("none"),jQuery(".pzarc-draggable-content img.pzarc-align").removeClass("left"),jQuery(".pzarc-draggable-content img.pzarc-align").removeClass("right"))}function u(e){jQuery(e).each(function(){var e=!0;switch(this.target.value){case"title":jQuery("#1_box_redux-_architect-metabox-panels-design_section_group_li").toggle(this.target.checked);break;case"meta1":case"meta2":case"meta3":e=1===jQuery("input#_panels_design_components-to-show-buttonsetmeta1:checked").length||1===jQuery("input#_panels_design_components-to-show-buttonsetmeta2:checked").length||1===jQuery("input#_panels_design_components-to-show-buttonsetmeta3:checked").length,jQuery("#2_box_redux-_architect-metabox-panels-design_section_group_li").toggle(e);break;case"content":case"excerpt":e=1===jQuery("input#_panels_design_components-to-show-buttonsetcontent:checked").length||1===jQuery("input#_panels_design_components-to-show-buttonsetexcerpt:checked").length,jQuery("#3_box_redux-_architect-metabox-panels-design_section_group_li").toggle(e);break;case"image":case"fill":case"align":case"none":e=1===jQuery("input#_panels_design_components-to-show-buttonsetimage:checked").length||0===jQuery("input#_panels_design_background-position-buttonsetnone:checked").length,jQuery("#4_box_redux-_architect-metabox-panels-design_section_group_li").toggle(e);break;case"custom1":case"custom2":case"custom3":e=1===jQuery("input#_panels_design_components-to-show-buttonsetcustom1:checked").length||1===jQuery("input#_panels_design_components-to-show-buttonsetcustom2:checked").length||1===jQuery("input#_panels_design_components-to-show-buttonsetcustom3:checked").length,jQuery("#5_box_redux-_architect-metabox-panels-design_section_group_li").toggle(e)}})}e();var d=jQuery("fieldset#_architect-_panels_design_preview .plugin_url").text(),m=jQuery.parseJSON(jQuery("input#_panels_design_preview-text").val());t(m),jQuery('input[name="_architect[_panels_design_thumb-position]"]').on("click",function(e){var a=this.value;g(a)}),jQuery('input[name="_architect[_panels_design_background-position]"]').on("click",function(e){var a=jQuery.parseJSON(jQuery("input#_panels_design_preview-text").val());s(a),u(e)}),jQuery("fieldset#_architect-_panels_design_components-to-show input").change(function(e){var a=jQuery.parseJSON(jQuery("input#_panels_design_preview-text").val());l(a,e),u(e)}),jQuery('input[name="_architect[_panels_design_components-position]"]').on("click",function(e){var a=jQuery.parseJSON(jQuery("input#_panels_design_preview-text").val());i(a)}),jQuery('input[name="_architect[panels_settingds_panel-height-type]"]').on("click",function(e){var a=jQuery.parseJSON(jQuery("input#_panels_design_preview-text").val())}),jQuery("fieldset#_architect-_panels_design_components-widths .redux-slider-label").on("DOMSubtreeModified",function(e){var a=jQuery.parseJSON(jQuery("input#_panels_design_preview-text").val());c(a)}),jQuery("fieldset#_architect-_panels_design_components-nudge-x .redux-slider-label").on("DOMSubtreeModified",function(e){var a=jQuery.parseJSON(jQuery("input#_panels_design_preview-text").val());o(a)}),jQuery("fieldset#_architect-_panels_design_components-nudge-y  .redux-slider-label").on("DOMSubtreeModified",function(e){var a=jQuery.parseJSON(jQuery("input#_panels_design_preview-text").val());o(a)}),jQuery(".pzarc-dropzone .pzarc-content-area").sortable({cursor:"move",opacity:.6,forceHelperSize:!0,sort:function(e,a){jQuery(this).removeClass("ui-state-default")},update:function(e,r){var n=jQuery.parseJSON(jQuery("input#_panels_design_preview-text").val());n=a(n),t(n)},create:function(e,a){var n=new Array;n.title='<span class="pzarc-draggable pzarc-draggable-title" title= "Post title" data-idcode=title style="display: inline-block;font-weight:bold;font-size:15px;"><span>This is the title</span></span>',n.meta1='<span class="pzarc-draggable pzarc-draggable-meta1 pzarc-draggable-meta"  title= "Meta info 1" data-idcode=meta1 style="font-size:11px;"><span>Jan 1 2013</span></span>',n.meta2='<span class="pzarc-draggable pzarc-draggable-meta2 pzarc-draggable-meta" title= "Meta info 2"  data-idcode=meta2 style="font-size:11px;"><span>Categories - News, Sport</span></span>',n.image='<span class="pzarc-draggable pzarc-draggable-image"  title= "Featured image" data-idcode=image style="max-height:100px;overflow:hidden;"><span><img src="PZARC_PLUGIN_URL/shared/assets/images/sample-image.jpg" style="max-width:100%;"/></span></span>',n.image=n.image.replace(/PZARC_PLUGIN_URL/g,d),n.content='<span class="pzarc-draggable pzarc-draggable-content" title= "Full post content"  data-idcode=content style="font-size:13px;"><span><img src="PZARC_PLUGIN_URL/shared/assets/images/sample-image.jpg" class="pzarc-align '+jQuery("select#_pzarc_layout-excerpt-thumb-cmb-field-0").val()+'" style="max-width:20%;"/><img src="PZARC_PLUGIN_URL/shared/assets/images/fireworks.jpg" style="max-width:30%;float:left;padding:5px;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;&bull;&nbsp;Cras semper sem hendrerit</li><li>&nbsp;&bull;&nbsp;Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><p>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </p></span></span>',n.content=n.content.replace(/PZARC_PLUGIN_URL/g,d),n.excerpt='<span class="pzarc-draggable pzarc-draggable-excerpt"  title= "Excerpt with featured image" data-idcode=excerpt style="font-size:13px;"><span><img src="PZARC_PLUGIN_URL/shared/assets/images/sample-image.jpg" class="pzarc-align '+jQuery("select#_pzarc_layout-excerpt-thumb-cmb-field-0").val()+'" style="max-width:20%;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>',n.excerpt=n.excerpt.replace(/PZARC_PLUGIN_URL/g,d),n.meta3='<span class="pzarc-draggable pzarc-draggable-meta3 pzarc-draggable-meta"  title= "Meta info 3" data-idcode=meta3 style="font-size:11px;"><span>Comments: 27</span></span>',n.custom1='<span class="pzarc-draggable pzarc-draggable-custom1 pzarc-draggable-meta"  title= "Custom field 1" data-idcode=custom1 style="font-size:11px;"><span>Custom content group 1</span></span>',n.custom2='<span class="pzarc-draggable pzarc-draggable-custom2 pzarc-draggable-meta"  title= "Custom field 2" data-idcode=custom2 style="font-size:11px;"><span>Custom content group 2</span></span>',n.custom3='<span class="pzarc-draggable pzarc-draggable-custom3 pzarc-draggable-meta"  title= "Custom field 3" data-idcode=custom3 style="font-size:11px;"><span>Custom content group 3</span></span>';var u=jQuery.parseJSON(jQuery("input#_panels_design_preview-text").val());jQuery(".pzarc-content-area.sortable").html(""),jQuery.each(u,function(e,a){jQuery(".pzarc-content-area.sortable").append(n[e])}),i(u),c(u),p(u),o(u),l(u),r(u),s(u),t(u),g(jQuery("input[name='_architect[_panels_design_thumb-position]']").value)}}),jQuery(".pzarc-draggable").resizable({handles:"e",containment:"parent",grid:[4,1],minWidth:10,maxWidth:400,autoHide:!0,resize:function(e,a){var r=jQuery(this).data("idcode"),s=Math.floor(jQuery(this).width()/400*100),i=jQuery.parseJSON(jQuery("input#_panels_design_preview-text").val());i=n(i,r,s),t(i)}})}),jQuery(window).load(function(){0===jQuery("#redux-_architect-metabox-panels-design ul.redux-group-menu li.active").length&&jQuery("#0_box_redux-_architect-metabox-panels-design_section_group_li").find("a").trigger("click")});