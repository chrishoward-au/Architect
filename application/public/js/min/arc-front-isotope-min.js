jQuery(document).ready(function(){"use strict";var e=jQuery(".js-isotope").each(function(){var t=this;jQuery(t).imagesLoaded(function(){var r=jQuery(t).attr("data-uid");console.log(r),e.isotope({layoutMode:"masonry",itemSelector:"."+r+" .pzarc-panel",masonry:{columnWidth:".grid-sizer",gutter:".gutter-sizer"}})})})});