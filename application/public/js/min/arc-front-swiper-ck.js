jQuery(document).ready(function(){"use strict";var e=jQuery(".swiper-container.slider");e.each(function(){var e=jQuery(this).attr("data-swiperid"),t=jQuery(this).attr("data-transtype"),a=jQuery(this).attr("data-opts"),r=jQuery(this).find(".pzarc-panel").length;if(null!==e&&null!==a){a=a.replace(/#/g,'"');var i=JSON.parse(a),s=jQuery(".pzarc-blueprint_"+e+" .swiper-nav.swiper-container").swiper({slidesPerView:"auto",createPagination:!1,paginationClickable:!1,simulateTouch:!1,keyboardControl:!1,freeMode:!0,freeModeFluid:!0,calculateHeight:!1,useCSS3Transforms:!1,grabCursor:!1,onSlideChangeStart:function(t){jQuery(".swiper-nav.pzarc-navigator-"+e+" .active").removeClass("active"),jQuery(jQuery(".swiper-nav.pzarc-navigator-"+e+" span.swiper-pagination-switch").get(t.activeIndex)).addClass("active")},onSlideClick:function(e){n.swipeTo(e.clickedSlideIndex)}});jQuery(".skip-left").on("click",function(e){e.preventDefault();var t=Math.max(0,s.activeIndex-i.tskip);s.swipeTo(t,1e3,!1),n.swipeTo(t)}),jQuery(".skip-right").on("click",function(e){e.preventDefault();var t=Math.min(r-1,s.activeIndex+i.tskip);s.swipeTo(t,1e3,!1),n.swipeTo(t)});var n=jQuery(".swiper-container.swiper-container-"+e).swiper({mode:"horizontal",loop:!1,calculateHeight:!0,cssWidthAndHeight:!1,grabCursor:!0,createPagination:!1,paginationClickable:!0,scrollContainer:!1,keyboardControl:!0,slidesPerView:1,useCSS3Transforms:!1,speed:i.tduration,autoplay:i.tinterval,autoplayDisableOnInteraction:!1,roundLength:!0,onSlideChangeStart:function(t){jQuery(".pzarc-navigator-"+e+" .active").removeClass("active"),jQuery(jQuery(".pzarc-navigator-"+e+" span.swiper-pagination-switch").get(t.activeIndex)).addClass("active")},progress:!0,onProgressChange:function(e){var a,r,i,s,n,o;switch(t){case"fade":for(a=0;a<e.slides.length;a++)r=e.slides[a],i=r.progress,s=i*e.width,o=1-Math.min(Math.abs(i),1),r.style.opacity=o,e.setTransform(r,"translate3d("+s+"px,0,0)");break;case"swipe":for(a=0;a<e.slides.length;a++)r=e.slides[a],i=r.progress,e.setTransform(r,"translate3d(0px,0,"+-Math.abs(1500*i)+"px)");break;case"rotate":for(a=0;a<e.slides.length;a++)r=e.slides[a],i=r.progress,n=-90*i,-90>n&&(n=-90),n>90&&(n=90),s=i*e.width/2,o=1-Math.min(Math.abs(i),1),r.style.opacity=o,e.setTransform(r,"rotateY("+n+"deg) translate3d("+s+"px,0,0)");break;case"flip":for(a=0;a<e.slides.length;a++)r=e.slides[a],i=r.progress,n=-180*i,-180>n&&(n=-180),n>180&&(n=180),s=i*e.width,e.setTransform(r,"translate3d("+s+"px,0,"+500*-Math.abs(i)+"px)"),e.setTransform(r.querySelector(".flip-container"),"rotateY("+n+"deg)")}},onTouchStart:function(e){for(var a=0;a<e.slides.length;a++)switch(e.setTransition(e.slides[a],0),t){case"flip":e.setTransition(e.slides[a].querySelector(".flip-container"),0)}},onSetWrapperTransition:function(e){for(var a=0;a<e.slides.length;a++)switch(e.setTransition(e.slides[a],e.params.speed),t){case"flip":e.setTransition(e.slides[a].querySelector(".flip-container"),e.params.speed)}}}),o=jQuery(".pzarc-navigator-"+e+" span");o.on("touchstart",function(e){return jQuery(this).hasClass("active")?(e.preventDefault(),!1):(e.preventDefault(),n.swipeTo(jQuery(this).index()),!0)}),o.click(function(e){return jQuery(this).hasClass("active")?(e.preventDefault(),!1):(e.preventDefault(),!0)}),jQuery(".arrow-left").on("click",function(e){e.preventDefault(),n.swipePrev()}),jQuery(".arrow-right").on("click",function(e){e.preventDefault(),n.swipeNext()})}})});