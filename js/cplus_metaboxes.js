jQuery(document).ready(function() {

	 jQuery('.pizazz-meta-boxes').tabs({});
//    var $dragsource = jQuery("div.cplus-dragsource");
//     $dragsource.draggable({
// 			revert:true,
// 			revertDuration:0,
// 			// helper:"clone"
// stack: "#contentplus_layout-elements div",
// cursor:"move",

//       zindex:99

//     });


    // var $dropzone = jQuery( ".cplus-dropzone");
    // $dropzone.droppable({
    //   activeClass: ".ui-state-default",
    //   hoverClass: ".ui-state-hover",
    //   accept: ":not(.ui-sortable-helper)",
    //   drop: function( event, ui ) {
    //     var alt = ui.helper.attr('alt');
    //     var $d = jQuery( '<span class="cplus-dropped cplus-dropped-'+alt+'" alt="%'+alt+'%"></span>' );
    //     $d.html(ui.helper.text() ).appendTo( this );
    //     var x = jQuery(".cell-contentplus_layout-layout").find('input[name="contentplus_layout-layout"]');
    //     x.val(x.val() + '%' + ui.helper.attr("alt") + '% ');
    //     ui.draggable.hide();
    //     x.change();
    //      // jQuery("span.cplus-dropped").resizable({
    //      //  handles:"s",
    //      //  ghost:true,
    //      //  minHeight:40,
    //      //  resize: function( event, ui ) {
    //      //    var rs = 'contentplus_layout-'+alt+'-width';
    //      //    console.log(rs);
    //      //    var $r = jQuery( 'input[name="'+rs+'"]');
    //      //    // this may be inacurate as it 's using the whole width but buthas padding'
    //      //    $r.val(Math.round(ui.size.width/500*100));
    //      //  }
    //     // });
    //  }
    // })
    jQuery( ".cplus-dropzone").sortable({
    	cursor: "move",
      sort: function() {
        // gets added unintentionally by droppable interacting with sortable
        // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
        jQuery( this ).removeClass( "ui-state-default" );
      }
    });
    // On disadvantage is can't size to zero
    // jQuery('.cplus-dropped').resizable({
    // 	handle:'e',
    // 	maxWidth:500
    // });


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
