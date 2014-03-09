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
 * @subpackage  Field_Media
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_custom_fonts' ) ) {

    /**
     * Main ReduxFramework_custom_fonts class
     *
     * @since       1.0.0
     */
    class ReduxFramework_custom_fonts {
    
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value ='', $parent ) {
        
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;
            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
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
        public function render() {

            echo '</fieldset></td></tr></table><fieldset class="redux-field">';

            $nonce = wp_create_nonce("redux_{$this->parent->args['opt_name']}_custom_fonts");

            // No errors please
            $defaults = array(
                'id'        => '',
                'url'       => '',
                'width'     => '',
                'height'    => '',
                'thumbnail' => '',
            );

            $this->value = wp_parse_args( $this->value, $defaults );


        
            //Upload controls DIV
            echo '<div class="upload_button_div">';

            //If the user has WP3.5+ show upload/remove button
            echo '<span class="button media_add_font" data-nonce="' . $nonce . '" id="' . $this->field['id'] . '-custom_fonts">' . __( 'Add Font', 'redux-framework' ) . '</span><br />';
          
            //echo '<a href="#TB_inline?width=600&height=550&inlineId=nameFont_'.$this->field['id'].'" class="thickbox">Open Thickbox</a>';
            echo '</div>';  

            $this->field['custom_fonts'] = apply_filters("Redux/{$this->parent->args['opt_name']}/Field/Typography/Custom_Fonts", array());


            
            if (!empty($this->field['custom_fonts'])) {
                echo '<table class="wp-list-table widefat plugins" cellspacing="0"><thead><tr><th scope="col" id="name" class="manage-column column-name" style="">Font Name</th><th scope="col" id="description" class="manage-column column-description">Font Files</th><th class="manage-column column-action">Actions</th></tr></thead><tbody>';    
                foreach($this->field['custom_fonts'] as $font => $pieces) {
                    echo '<tr class="active">
                    <td class="plugin-title"><strong>'.$font.'</strong></td>
                    <td class="column-description desc">
                        <div class="plugin-description">';
                        if (!empty($pieces)) {
                            foreach ( $pieces as $piece ) {
                                echo "<span clas=\"font-pieces\">{$piece}</span> ";
                            }
                        }
                    echo '</div>
                    </td>
                    <td>
                        <div class="row-actions visible"><a href="#" class="rename">Rename</a> | <a href="#" class="delete">Delete</a></div>
                    </td>
                  </tr>  ';
                }
                echo '</tbody></table>';
            } 

            echo '</fieldset>';        
            
        }

            /**
             * 
             * Functions to pass data from the PHP to the JS at render time.
             * 
             * @return array Params to be saved as a javascript object accessable to the UI.
             * 
             * @since  Redux_Framework 3.1.1
             * 
             */
            function localize($field, $value = "") {
                
                $params = array();

                if ( !isset( $field['mode'] ) ) {
                    $field['mode'] = "image";
                }          
                $params['mode'] = $field['mode'];

                if ( empty( $value ) && isset( $this->value ) ) {
                    $value = $this->value;
                }   
                $params['val'] = $value;

                return $params;
          
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

            wp_enqueue_script(
                'redux-field-custom_fonts-js',
                $this->extension_url . '/field_custom_fonts.js',
                array( 'jquery', 'wp-color-picker' ),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-custom_fonts-css',
                $this->extension_url . 'field_custom_fonts.css',
                time(),
                true
            );

            $class = ReduxFramework_extension_custom_fonts::getInstance();
            if ( !empty( $class->custom_fonts ) ) {
                wp_enqueue_style(
                    'redux-custom_fonts-css',
                    $class->upload_url . "fonts.css",
                    time(),
                    true
                );                
            }   

        }
    }
}
