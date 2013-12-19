/**
 * Created by chrishoward on 17/12/2013.
 */
jQuery(document).ready(function() {
  "use strict";

  /*
  Setup an array of WP metaboxes to turn into tabbed ones
  This will need an array of theslugs of the metaboxes,
  the title, and the panel element
  We could do massive manupulation, but it might be better to keepit siple, and use the slug element as the panel as well.
  We then jsut create an unordered list oit the titles. That coudl even allow us to then use jQueryUI to build the tabs.
   */
  // we should change this to work on any page by makign this defaults
  var tabscontainer = '#normal-sortables';
  var slugdivs = ['#what-do-you-want-to-do','#cell-designer','#settings','#styling'];
  var title = 'h3 span';
  var panelsdiv = '.inside';

  function add_ul() {

    // for each slug, get the title and created an li for it.
    // so, <ul class="tabs"><li class="tab">< href="slug">title</a></li>... </ul>
    // then add thisto the op of the container.
    // then initiate the tabs - jqueryui or whatever.

  }

}); // End
// Nothing past here!

