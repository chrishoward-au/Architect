/**
 * Created by chrishoward on 16/04/15.
 */


function testAnim(x,target) {
  console.log(x,target);
  jQuery('.'+target).addClass(x + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
    jQuery(this).removeClass().addClass('redux-normal redux-notice-field redux-field-info animation-demo '+target);
  });
}

jQuery(document).ready(function(){

  jQuery('.js--animations').change(function(){
    var target = this.name.replace('-animation]','-demo' ).replace('_architect[','');
    var anim = jQuery(this).val();
    testAnim(anim,target);
  });



});


