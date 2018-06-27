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
   * @subpackage  Field_Color_Gradient
   * @author      Luciano "WebCaos" Ubertini
   * @author      Daniel J Griffiths (Ghost1227)
   * @author      Dovy Paukstys
   * @version     3.0.0
   */

// Exit if accessed directly
  if (!defined('ABSPATH')) {
    exit;
  }

// Don't duplicate me!
  if (!class_exists('ReduxFramework_links')) {

    /**
     * Main ReduxFramework_link_color class
     *
     * @since       1.0.0
     */
    class ReduxFramework_links
    {

      /**
       * Field Constructor.
       *
       * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
       *
       * @since       1.0.0
       * @access      public
       * @return      void
       */
      function __construct($field = array(), $value = '', $parent)
      {
        $this->parent = $parent;
        $this->field  = $field;
        $this->value  = $value;

        wp_enqueue_style(
          'redux-field-links-css',
          plugin_dir_url( __FILE__ ) .'/field_links.css',
          time(),
          true
        );

        $defaults    = array(
            'regular'      => true,
            'hover'        => true,
            'visited'      => true,
            'active'       => true,
            'regular-deco' => true,
            'hover-deco'   => true,
            'visited-deco' => true,
            'active-deco'  => true
        );
        $this->field = wp_parse_args($this->field, $defaults);

        $defaults = array(
            'regular'      => '',
            'hover'        => '',
            'visited'      => '',
            'active'       => '',
            'regular-deco' => 'Default',
            'hover-deco'   => 'Default',
            'visited-deco' => 'Default',
            'active-deco'  => 'Default'
        );

        $this->value = wp_parse_args($this->value, $defaults);

        // In case user passes no default values.
        if (isset($this->field[ 'default' ])) {
          $this->field[ 'default' ] = wp_parse_args($this->field[ 'default' ], $defaults);
        } else {
          $this->field[ 'default' ] = $defaults;
        }
      }

      /**
       * Field Render Function.
       *
       * Takes the vars and outputs the HTML for the field in the settings
       *
       * @since       1.0.0
       * @access      public
       * @return      void
       */
      public function render()
      {
        echo '<table class="pzarc-links-table">';
        if ($this->field[ 'regular' ] === true) {
          echo '<tr><td class="pzarc-links-text1"><strong>Regular</strong>:</td><td class="pzarc-links-drop1"><input id="' . $this->field[ 'id' ] . '-regular" name="' . $this->field[ 'name' ] . '[regular]' . $this->field[ 'name_suffix' ] . '" value="' . $this->value[ 'regular' ] . '" class="redux-spectrum redux-spectrum-init ' . $this->field[ 'class' ] . '"  type="text" data-default-spectrum="' . $this->field[ 'default' ][ 'regular' ] . '" /></td>';
          echo '<td class="pzarc-links-text2">'.__('Underline', 'redux-framework') . '&nbsp;<select id="' . $this->field[ 'id' ] . '-regular-deco" name="' . $this->field[ 'name' ] . '[regular-deco]' . $this->field[ 'name_suffix' ] . '"  class="redux-links ' . $this->field[ 'class' ] . '" style="width:100px;">';
          echo '<option  value="Default" ' . (strtolower($this->value[ 'regular-deco' ]) === 'default' ? 'selected' : '') . '>Default</option>';
          echo '<option  value="Inherit" ' . (strtolower($this->value[ 'regular-deco' ]) === 'inherit' ? 'selected' : '') . '>Inherit</option>';
          echo '<option  value="None" ' . (strtolower($this->value[ 'regular-deco' ]) === 'none' ? 'selected' : '') . '>None</option>';
          echo '<option  value="Underline" ' . (strtolower($this->value[ 'regular-deco' ]) === 'underline' ? 'selected' : '') . '>Underline</option>';
          echo '</select></td></tr>';
        }

        if ($this->field[ 'hover' ] === true) {
          echo '<tr><td class="pzarc-links-text1"><strong>Hover</strong>:</td><td class="pzarc-links-drop1"><input id="' . $this->field[ 'id' ] . '-hover" name="' . $this->field[ 'name' ] . '[hover]' . $this->field[ 'name_suffix' ] . '" value="' . $this->value[ 'hover' ] . '" class="redux-spectrum redux-spectrum-init ' . $this->field[ 'class' ] . '"  type="text" data-default-spectrum="' . $this->field[ 'default' ][ 'hover' ] . '" /></td>';
          echo '<td class="pzarc-links-text2">'.__('Underline', 'redux-framework') . '&nbsp;<select id="' . $this->field[ 'id' ] . '-hover-deco" name="' . $this->field[ 'name' ] . '[hover-deco]' . $this->field[ 'name_suffix' ] . '"  class="redux-links ' . $this->field[ 'class' ] . '" style="width:100px;">';
          echo '<option  value="Default" ' . (strtolower($this->value[ 'hover-deco' ]) === 'default' ? 'selected' : '') . '>Default</option>';
          echo '<option  value="Inherit" ' . (strtolower($this->value[ 'hover-deco' ]) === 'inherit' ? 'selected' : '') . '>Inherit</option>';
          echo '<option  value="None" ' . (strtolower($this->value[ 'hover-deco' ]) === 'none' ? 'selected' : '') . '>None</option>';
          echo '<option  value="Underline" ' . (strtolower($this->value[ 'hover-deco' ]) === 'underline' ? 'selected' : '') . '>Underline</option>';
          echo '</select></td></tr>';
        }

        if ($this->field[ 'active' ] === true) {
          echo '<tr><td class="pzarc-links-text1"><strong>Active</strong>:</td><td class="pzarc-links-drop1"><input id="' . $this->field[ 'id' ] . '-active" name="' . $this->field[ 'name' ] . '[active]' . $this->field[ 'name_suffix' ] . '" value="' . $this->value[ 'active' ] . '" class="redux-spectrum redux-spectrum-init ' . $this->field[ 'class' ] . '"  type="text" data-default-spectrum="' . $this->field[ 'default' ][ 'active' ] . '" /></td>';
          echo '<td class="pzarc-links-text2">'.__('Underline', 'redux-framework') . '&nbsp;<select id="' . $this->field[ 'id' ] . '-active-deco" name="' . $this->field[ 'name' ] . '[active-deco]' . $this->field[ 'name_suffix' ] . '"  class="redux-links ' . $this->field[ 'class' ] . '" style="width:100px;">';
          echo '<option  value="Default" ' . (strtolower($this->value[ 'active-deco' ]) === 'default' ? 'selected' : '') . '>Default</option>';
          echo '<option  value="Inherit" ' . (strtolower($this->value[ 'active-deco' ]) === 'inherit' ? 'selected' : '') . '>Inherit</option>';
          echo '<option  value="None" ' . (strtolower($this->value[ 'active-deco' ]) === 'none' ? 'selected' : '') . '>None</option>';
          echo '<option  value="Underline" ' . (strtolower($this->value[ 'active-deco' ]) === 'underline' ? 'selected' : '') . '>Underline</option>';
          echo '</select></td></tr>';
        }

        if ($this->field[ 'visited' ] === true) {
          echo '<tr><td class="pzarc-links-text1"><strong>Visited</strong>:</td><td class="pzarc-links-drop1"><input id="' . $this->field[ 'id' ] . '-visited" name="' . $this->field[ 'name' ] . '[visited]' . $this->field[ 'name_suffix' ] . '" value="' . $this->value[ 'visited' ] . '" class="redux-spectrum redux-spectrum-init ' . $this->field[ 'class' ] . '"  type="text" data-default-spectrum="' . $this->field[ 'default' ][ 'visited' ] . '" /></td>';
          echo '<td class="pzarc-links-text2">'.__('Underline', 'redux-framework') . '&nbsp;<select id="' . $this->field[ 'id' ] . '-visited-deco" name="' . $this->field[ 'name' ] . '[visited-deco]' . $this->field[ 'name_suffix' ] . '"  class="redux-links ' . $this->field[ 'class' ] . '" style="width:100px;">';
          echo '<option  value="Default" ' . (strtolower($this->value[ 'visited-deco' ]) === 'default' ? 'selected' : '') . '>Default</option>';
          echo '<option  value="Inherit" ' . (strtolower($this->value[ 'visited-deco' ]) === 'inherit' ? 'selected' : '') . '>Inherit</option>';
          echo '<option  value="None" ' . (strtolower($this->value[ 'visited-deco' ]) === 'none' ? 'selected' : '') . '>None</option>';
          echo '<option  value="Underline" ' . (strtolower($this->value[ 'visited-deco' ]) === 'underline' ? 'selected' : '') . '>Underline</option>';
          echo '</select></td></tr>';
        }
        echo '</table>';

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
      public function enqueue()
      {

        wp_enqueue_script(
            'redux-field-spectrum-min-js',
            PZARC_PLUGIN_APP_URL . '/shared/thirdparty/php/redux-custom-fields/spectrum/vendor/spectrum/min/spectrum-ck.js',
            array('jquery'),
            time(),
            true
        );

        wp_enqueue_style(
            'redux-field-spectrum-min-css',
            PZARC_PLUGIN_APP_URL . '/shared/thirdparty/php/redux-custom-fields/spectrum/vendor/spectrum/min/spectrum.css',
            time(),
            true
        );

        wp_enqueue_script(
            'redux-field-spectrum-js',
            PZARC_PLUGIN_APP_URL . '/shared/thirdparty/php/redux-custom-fields/spectrum/field_spectrum.js',
            array('jquery'),
            time(),
            true
        );

        wp_enqueue_style(
            'redux-field-spectrum-css',
            PZARC_PLUGIN_APP_URL . '/shared/thirdparty/php/redux-custom-fields/spectrum/field_spectrum.css',
            time(),
            true
        );
      }

      public function output()
      {

        $style = array();

        if (!empty($this->value[ 'regular' ]) && $this->field[ 'regular' ] === true && $this->field[ 'default' ][ 'regular' ] !== false) {
          $style[ ] = 'color:' . $this->value[ 'regular' ] . ';';
        }

        if (!empty($this->value[ 'hover' ]) && $this->field[ 'hover' ] === true && $this->field[ 'default' ][ 'hover' ] !== false) {
          $style[ 'hover' ] = 'color:' . $this->value[ 'hover' ] . ';';
        }

        if (!empty($this->value[ 'active' ]) && $this->field[ 'active' ] === true && $this->field[ 'default' ][ 'active' ] !== false) {
          $style[ 'active' ] = 'color:' . $this->value[ 'active' ] . ';';
        }

        if (!empty($this->value[ 'visited' ]) && $this->field[ 'visited' ] === true && $this->field[ 'default' ][ 'visited' ] !== false) {
          $style[ 'visited' ] = 'color:' . $this->value[ 'visited' ] . ';';
        }

        if (!empty($style)) {
          if (!empty($this->field[ 'output' ]) && is_array($this->field[ 'output' ])) {
            $styleString = "";

            foreach ($style as $key => $value) {
              if (is_numeric($key)) {
                $styleString .= implode(",", $this->field[ 'output' ]) . "{" . $value . '}';
              } else {
                if (count($this->field[ 'output' ]) == 1) {
                  $styleString .= $this->field[ 'output' ][ 0 ] . ":" . $key . "{" . $value . '}';
                } else {
                  $styleString .= implode(":" . $key . ",", $this->field[ 'output' ]) . "{" . $value . '}';
                }
              }
            }

            $this->parent->outputCSS .= $styleString;
          }

          if (!empty($this->field[ 'compiler' ]) && is_array($this->field[ 'compiler' ])) {
            $styleString = "";

            foreach ($style as $key => $value) {
              if (is_numeric($key)) {
                $styleString .= implode(",", $this->field[ 'compiler' ]) . "{" . $value . '}';
              } else {
                if (count($this->field[ 'compiler' ]) == 1) {
                  $styleString .= $this->field[ 'compiler' ][ 0 ] . ":" . $key . "{" . $value . '}';
                } else {
                  $styleString .= implode(":" . $key . ",", $this->field[ 'compiler' ]) . "{" . $value . '}';
                }
              }
            }
            $this->parent->compilerCSS .= $styleString;
          }
        }
      }
    }
  }