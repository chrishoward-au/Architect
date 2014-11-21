<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Field_Color RGBA
 * @author      Sandro Bilbeisi
 * @version     3.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Don't duplicate me!
if (!class_exists('ReduxFramework_spectrum')) {

    /**
     * Main ReduxFramework_color class
     *
     * @since       1.0.0
     */
    class ReduxFramework_spectrum {

        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since 		1.0.0
         * @access		public
         * @return		void
         */
        function __construct($field = array(), $value = array(), $parent) {
            $this->parent   = $parent;
            $this->field    = $field;
            $this->value    = $value;
        }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since 		1.0.0
         * @access		public
         * @return		void
         */
        public function render() {

            $defaults = array(
                'color' => '',
            );
          $this->field['default']['color'] = (!isset($this->field['default']['color']))?'':$this->field['default']['color'];


            $this->value = wp_parse_args($this->value, $defaults);
            echo '<input data-id="' . $this->field['id'] . '" name="' . $this->field['name'] . '[color]' . $this->field['name_suffix'] . '" id="' . $this->field['id'] . '-color" class="redux-spectrum redux-spectrum-init ' . $this->field['class'] . '"  type="text" value="' . $this->value['color'] . '"  data-default-color="' . $this->field['default']['color'] . '" data-defaultvalue="' . $this->field['default']['color'] . '" />';

        }

        public function output() {

//            if ((!isset($this->field['output']) || !is_array($this->field['output']) ) && (!isset($this->field['compiler']) )) {
//                return;
//            }
//
//            if (!empty($this->value)) {
//                $mode = ( isset($this->field['mode']) && !empty($this->field['mode']) ? $this->field['mode'] : 'color' );
//
//                if ($this->value['alpha'] == "0.00" || empty($this->value['color'])) {
//                    $style = $mode . ':transparent;';
//                } elseif (!empty($this->value['color'])) {
//                    $style = $mode . ':rgba(' . Redux_Helpers::hex2rgba($this->value['color']) . ',' . $this->value['alpha'] . ');';
//                }
//
//                if (!empty($this->field['output']) && is_array($this->field['output'])) {
//                    $css = Redux_Functions::parseCSS($this->field['output'], $style, $this->value);
//                    $this->parent->outputCSS .= $css;
//                }
//
//                if (!empty($this->field['compiler']) && is_array($this->field['compiler'])) {
//                    $css = Redux_Functions::parseCSS($this->field['compiler'], $style, $this->value);
//                    $this->parent->compilerCSS .= $css;
//                }
//            }
        }

        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {

            $min = Redux_Functions::isMin();

          wp_enqueue_script(
              'redux-field-spectrum-min-js',
              PZARC_PLUGIN_APP_URL . '/shared/includes/php/redux-custom-fields/spectrum/vendor/spectrum/min/spectrum-ck.js',
              array('jquery'),
              time(),
              true
          );

          wp_enqueue_style(
              'redux-field-spectrum-min-css',
              PZARC_PLUGIN_APP_URL . '/shared/includes/php/redux-custom-fields/spectrum/vendor/spectrum/min/spectrum.css',
              time(),
              true
          );

            wp_enqueue_script(
                'redux-field-spectrum-js',
                PZARC_PLUGIN_APP_URL . '/shared/includes/php/redux-custom-fields/spectrum/field_spectrum.js',
                array('jquery'),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-spectrum-css',
                PZARC_PLUGIN_APP_URL . '/shared/includes/php/redux-custom-fields/spectrum/field_spectrum.css',
                time(),
                true
            );
        }
    }
}