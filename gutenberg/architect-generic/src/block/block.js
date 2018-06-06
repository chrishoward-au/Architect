/**
 * BLOCK: pizazzwp-arc-guten
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */
// import React from 'react';
// import ReactDOM from 'react-dom';

//  Import CSS.
import './style.scss';
import './editor.scss';

const {__} = wp.i18n; // Import __() from wp.i18n
//const {registerBlockType} = wp.blocks; // Import registerBlockType() from wp.blocks
const {
  registerBlockType,
  TextControl,
  TextareaControl,
  SelectControl,
  source: {
    attr,
    children
  }
} = wp.blocks;
// let el = wp.element.createElement;
// //let children = wp.blocks.source.children;
// //let BlockControls = wp.blocks.BlockControls;
// // var AlignmentToolbar = wp.blocks.AlignmentToolbar;
// // var MediaUploadButton = wp.blocks.MediaUploadButton;
// let InspectorControls = wp.blocks.InspectorControls;
// // let TextControl = wp.blocks.InspectorControls.TextControl;
// // let TextareaControl = wp.blocks.InspectorControls.TextareaControl;
// // let SelectControl = wp.blocks.InspectorControls.SelectControl;


/*

InspectorControls.BaseControl = BaseControl;
InspectorControls.CheckboxControl = CheckboxControl;
InspectorControls.RadioControl = RadioControl;
InspectorControls.RangeControl = RangeControl;
InspectorControls.ToggleControl = ToggleControl;
InspectorControls.SelectControl = SelectControl;
InspectorControls.TextareaControl = TextareaControl;
InspectorControls.TextControl = TextControl;
*/


/**

 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType('cgb/block-pizazzwp-arc-guten', {
  // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
  title: __('Architect', 'pizazz-arc-guten'), // Block title.
  icon: 'layout', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
  category: 'layout', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  keywords: [
    __('pizazzwp-arc-guten — CGB Block'),
    __('Pizazz Architect'),
    __('create-guten-block'),
  ],
  className: 'wp-architect-block',
  attributes:{},

  edit: function (props) {
    let retval = [];
    let focus = props.focus;

    // Render the editor block
    retval.push(
        <div className={props.className}>
          <p>Architect block: <strong>{props.attributes.blueprint_default}</strong></p>
        </div>
    );

    let arc_block_fields=arc_data['fields'];
    let arc_blueprints = arc_data['blueprints'];

    // const blueprints = arc_blueprints.map((blueprint) =>
    //     <option key={blueprint}
    //             value={blueprint} >{blueprint}
    //     </option>
    //
    // );


//    console.log(arc_blueprints);
    // Render the editor fields
    if (!!focus) {
      for (let arc_field in arc_block_fields) {
        if (arc_block_fields.hasOwnProperty(arc_field)) {  // Because PHPStorm was throwing a warning.

          switch (arc_block_fields[arc_field].fieldtype) {

              // textarea field
            case 'textarea':
              retval.push(pizazz_textarea_field(arc_block_fields, arc_field, props));
              break;

              // input field
            case 'text':
//            default: // TODO: Uncomment for production
//              retval.push(pizazz_input_field(arc_block_fields, arc_field, props,TextControl));
                retval.push(
                    <div className={`arc-gb-field ${arc_block_fields[arc_field]['class']} `}>
                      <TextControl
                          label={arc_block_fields[arc_field]['label']}
                          type={arc_block_fields[arc_field]['fieldtype']}
                          className={arc_block_fields[arc_field]['class']}
                          value={props.attributes[arc_field]}
                          help={arc_block_fields[arc_field]['placeholder']}
                          onChange={(event) => props.setAttributes({
                                [arc_field]: event.target.value,
                              }
                          )}
                      />

                    </div>

                );
              break;

          }
        }
      }
    }


    return retval;
  },

  // The "save" property must be specified and must be a valid function.
  save: function (props) {
    // Return null for dynamic blocks because they don't have editable html
    return null;
  },
});

const pizazz_input_field= (pizazz_field_data, pizazz_field, props,TextControl) => {

  return (
      <div className={`arc-gb-field ${pizazz_field_data[pizazz_field]['class']} `}>
        <TextControl
            label={pizazz_field_data[pizazz_field]['label']}
            type={pizazz_field_data[pizazz_field]['fieldtype']}
            className={pizazz_field_data[pizazz_field]['class']}
            value={props.attributes[pizazz_field]}
            help={pizazz_field_data[pizazz_field]['placeholder']}
            onChange={(event) => props.setAttributes({
                  [pizazz_field]: event.target.value,
                }
            )}
        />

      </div>

  );

};
//export default pizazz_input_field();

function pizazz_textarea_field(pizazz_field_data, pizazz_field, props) {

  return (
      <div className={`arc-gb-field ${pizazz_field_data[pizazz_field]['class']} `}>
        <label htmlFor={pizazz_field_data[pizazz_field]['class']}>
          {pizazz_field_data[pizazz_field]['label']}
        </label>
        <textarea
            id={pizazz_field_data[pizazz_field]['class']}
            value={props.attributes[pizazz_field]}
            rows={pizazz_field_data[pizazz_field]['rows']}
            placeholder={pizazz_field_data[pizazz_field]['placeholder']}
            onChange={(event) => props.setAttributes({
                  [pizazz_field]: event.target.value,
                }
            )}
        />

      </div>

  )
}
