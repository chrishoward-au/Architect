jQuery(document).ready(function() {

	 jQuery('.pizazz-meta-boxes').tabs({});
    jQuery( ".cplus-dropzone").sortable({
    	cursor: "move",
      sort: function() {
        // gets added unintentionally by droppable interacting with sortable
        // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
        jQuery( this ).removeClass( "ui-state-default" );
      }
    });
 
  jQuery("#pizazz-form-table-Layout input").mouseover(function(event){
  	var $source_input = jQuery(event.target);
  	var altid = $source_input.attr("alt");
  	console.log(altid);
	  var $source_target = jQuery('.cplus-dropped-'+altid);
	  var $source_percent_text = jQuery('span.percent-contentplus_layout-'+altid+'-width'); 
  	$source_target.css("background" , "#def");  
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
