jQuery(document).ready(function() {

	 jQuery('.pizazz-meta-boxes').tabs({});

	var plugin_url = jQuery('#cplus-custom-contentplus_layout-layout .plugin_url').text();
 	var $preview_inputs = jQuery("#pizazz-form-table-Layout input")
 	var element_html = new Array();
 	element_html['title'] = '<span class="cplus-dropped cplus-dropped-title" title= "Post title" data-idcode="%title%" style="display: inline-block;font-weight:bold;font-size:15px;">This is the title</span>'
 	element_html['meta1'] = '<span class="cplus-dropped cplus-dropped-meta1"  title= "Meta info 1" data-idcode="%meta1%" style="font-size:11px;">Jan 1 2013</span>';
 	element_html['meta2'] = '<span class="cplus-dropped cplus-dropped-meta2" title= "Meta info 2"  data-idcode="%meta2%" style="font-size:11px;">Categories - News, Sport</span>';
	element_html['image'] = '<span class="cplus-dropped cplus-dropped-image"  title= "Featured image" data-idcode="%image%" style="max-height:100px;overflow:hidden;"><img src="CPLUS_PLUGIN_URL/images/sample-image.jpg" style="max-width:100%;"/></span>';
	element_html['image'] =element_html['image'].replace(/CPLUS_PLUGIN_URL/,plugin_url);
	element_html['excerpt'] = '<span class="cplus-dropped cplus-dropped-excerpt"  title= "Post excerpt" data-idcode="%excerpt%" style="font-size:13px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis...[more]</span>';
	element_html['content'] = '<span class="cplus-dropped cplus-dropped-content" title= "Full post content"  data-idcode="%content%" style="font-size:13px;"><img src="CPLUS_PLUGIN_URL/images/sample-image.jpg" style="max-width:30%;float:left;padding:5px;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>Cras semper sem hendrerit</li><li>Tortor porta at auctor</li></ul><strong>Lacus consequat</strong><br>Pellentesque pulvinar iaculis tellus in blandit. Suspendisse rhoncus, magna vel eleifend cursus, turpis odio molestie urna, quis posuere eros risus quis neque. </span>';
	element_html['content'] =element_html['content'].replace(/CPLUS_PLUGIN_URL/,plugin_url);
	element_html['excerptthumb'] = '<span class="cplus-dropped cplus-dropped-excerptthumb"  title= "Excerpt with featured image" data-idcode="%excerptthumb%" style="font-size:13px;"><img src="CPLUS_PLUGIN_URL/images/sample-image.jpg" style="max-width:20%;float:right;padding:2px;"/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis...[more]</span>';
	element_html['excerptthumb'] =element_html['excerptthumb'].replace(/CPLUS_PLUGIN_URL/,plugin_url);
	element_html['meta3'] = '<span class="cplus-dropped cplus-dropped-meta3"  title= "Meta info 3" data-idcode="%meta3%" style="font-size:11px;">Comments: 27</span>';


 	$preview_inputs.each(function(){
  	var altid = this.alt;
	  var $source_target = jQuery('.cplus-dropped-'+altid);
	  var $source_percent_text = jQuery('span.percent-contentplus_layout-'+altid+'-width'); 

	  if (jQuery(this).val()==0) {
	  	$source_target.hide();
      $source_percent_text.text('Not used');
	  } else {
	    $source_target.show();
			$source_target.css("width" , jQuery(this).val()+'%');
	  }

 	})

// TO DO

// setup elements in correct order

    jQuery( ".cplus-dropzone").sortable({
    	cursor: "move",

      sort: function(event,ui) {
        // gets added unintentionally by droppable interacting with sortable
        // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
        jQuery( this ).removeClass( "ui-state-default" );
      },
      update: function(event,ui) {
					var sorted = jQuery(".cplus-dropzone").sortable( "toArray", {attribute: "data-idcode"});
					jQuery('input[name="contentplus_layout-layout"]').val(sorted);
					jQuery('input[name="contentplus_layout-layout"]').change();
				},
			create: function(event,ui) {
				var sorted = jQuery('input[name="contentplus_layout-layout"]').val();
				var sort_order = sorted.split(',');
				var sortzone = jQuery(".cplus-dropzone");
				sortzone.html('');
				for (i=0;i<sort_order.length;i++)  {

					var element_id = sort_order[i].replace(/%/g,"");
				  sortzone.html(sortzone.html()+ element_html[element_id]);
					var $source_target = jQuery('.cplus-dropped-'+element_id);
					var $source_percent_text = jQuery('span.percent-contentplus_layout-'+element_id+'-width'); 
					var $source_input = jQuery('input[name="contentplus_layout-'+element_id+'-width"]');

					if ($source_input.val()==0) {
						$source_target.hide();
						$source_percent_text.text('Not used');
					} else {
						$source_target.show();
						$source_target.css("width" , $source_input.val()+'%');
					}
      	}

			} 
    });
 
  jQuery("#pizazz-form-table-Layout input").mouseover(function(event){

  	var $source_input = jQuery(event.target);
  	var altid = $source_input.attr("alt");
	  var $source_target = jQuery('.cplus-dropped-'+altid);
	  var $source_percent_text = jQuery('span.percent-contentplus_layout-'+altid+'-width'); 

  	$source_target.css("background" , "#caa");  

	  $source_input.mousedown(function () {
	      $source_input.change(function(){

	        var $input = jQuery(this);

					$source_target.css("width" , $input.val()+'%');
			    if ($input.val() == 0) {
			      $source_target.hide();
			      $source_percent_text.text('Not used');
			    } else {
			      $source_target.show();
			      $source_percent_text.text($input.val()+'%');
			    }

	  	});
	  });

	  $source_input.mouseleave(function () {
	    $source_target.css("background" , "#ddd")  
	  });
  });




});
