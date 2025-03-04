/**
 * Created by chrishoward on 13/03/15.
 */

(function($) {

  tinymce.PluginManager.add('pushortcodes', function( editor )
  {
    var shortcodeValues = [];
    jQuery.each(shortcodes_button, function(i)
    {
      shortcodeValues.push({text: shortcodes_button[i], value:i});
    });

    editor.addButton('pushortcodes', {
      type: 'listbox',
      text: 'Shortcodes',
      onselect: function(e) {
        var v = e.control._value;

        tinyMCE.activeEditor.selection.setContent( '[' + v + '][/' + v + ']' );
      },
      values: shortcodeValues
    });
  });
})();
