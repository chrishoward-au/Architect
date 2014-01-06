jQuery(document).ready(function() {
	"use strict";

	// Active form validation
	jQuery("#post").validationEngine();

//	jQuery('.pzucd-meta-boxes').tabs({});

  jQuery('.cmb_metabox_description').each(function(){
    var theparent = jQuery(this).parent();
    jQuery(this).remove().appendTo(theparent);
  });



});