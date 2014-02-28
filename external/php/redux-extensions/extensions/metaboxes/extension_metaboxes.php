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
 * @author      Dovy Paukstys (dovy)
 * @version     1.0.8
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'ReduxFramework_extension_metaboxes' ) ) {

    /**
     * Main ReduxFramework customizer extension class
     *
     * @since       1.0.0
     */
    class ReduxFramework_extension_metaboxes {

        static $version = "1.1.3";

        public $boxes = array();
        public $sections = array();
        private $parent;
        public $options = array();
        public $options_defaults = array();
        public $localize_data = array();
        public $_extension_url;
        public $_extension_dir;
        public $meta = array();

        public function __construct( $parent ) {

            
            $this->parent = $parent;
            if ( empty( self::$_extension_dir ) ) {
                $this->_extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->_extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->_extension_dir ) );
            }
            if ( isset( $parent->metaboxes ) ) {
                $this->boxes = $parent->metaboxes;  
            }

            $this->boxes = apply_filters('redux/metaboxes/'.$this->parent->args['opt_name'].'/boxes',$this->boxes);

            if ( empty( $this->boxes ) ) {
                return; // Don't do it! There's nothing here.
            }
            $types = array();
            foreach( $this->boxes as $boxKey => $box ) {
                if ( !empty( $box['sections'] ) ) {
                    $this->sections[] = $box['sections'];
                    
                    
                }
            }

            add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
            add_action( 'save_post', array( $this, 'meta_boxes_save' ), 1, 2 );
            add_action( 'pre_post_update', array( $this, 'pre_post_update' ) ); 
            add_action( 'admin_notices', array( $this, 'meta_boxes_show_errors' ) );  
            add_filter( "redux/options/{$this->parent->args['opt_name']}/global_variable", array( $this, '_override_values' ) ); 
            add_action( 'admin_enqueue_scripts', array( $this, '_enqueue' ), 0 ); 

        } // __construct()

        public function _enqueue() {

            global $pagenow;
            
            $types = array();
            // Enqueue css
            foreach ($this->boxes as $key => $box) {
                if ( empty( $box['sections'] ) ) {
                    continue;
                }
                if ( isset( $box['post_types'] ) ) {
                    $types = array_merge($box['post_types'], $types);
                }
                if ( isset( $box['post_types'] ) && !empty( $box['post_types'] ) ) {
                    if ( !is_array( $box['post_types'] ) ) {
                        $box['post_types'] = array( $box['post_types'] );
                        $this->boxes[$key]['post_types'] = $box['post_types'];
                        add_action( 'admin_enqueue_scripts', array( $this->parent, '_enqueue' ) );    
                    }
                }
            }  


            if ( $pagenow == "post-new.php" || $pagenow == "post.php" ) {

                if ( ( isset( $_GET['post_type'] ) && in_array( $_GET['post_type'], $types ) ) || ( empty( $_GET['post_type'] ) && in_array( 'post', $types ) ) ) {
                    $this->parent->_enqueue();
                    do_action( "redux/metaboxes/{$this->parent->args['opt_name']}/enqueue" );

                    /**
                     * Redux metaboxes CSS
                     * filter 'redux/page/{opt_name}/enqueue/redux-extension-metaboxes-css'
                     * @param string  bundled stylesheet src
                     */
                    wp_enqueue_style(
                        'redux-extension-metaboxes-css',
                        apply_filters( "redux/metaboxes/{$this->parent->args['opt_name']}/enqueue/redux-extension-metaboxes-css", $this->_extension_url . 'extension_metaboxes.css' ),
                        '',
                        filemtime( $this->_extension_dir . 'extension_metaboxes.css' ), // todo - version should be based on above post-filter src
                        'all'
                    );
                    /**
                     * Redux metaboxes JS
                     * filter 'redux/page/{opt_name}/enqueue/redux-extension-metaboxes-js
                     * @param string  bundled javscript
                     */
                    wp_enqueue_script(
                        'redux-extension-metaboxes-js',
                        apply_filters( "redux/metaboxes/{$this->parent->args['opt_name']}/enqueue/redux-extension-metaboxes-js", $this->_extension_url . 'extension_metaboxes.js' ),
                        '',
                        filemtime( $this->_extension_dir . 'extension_metaboxes.js' ), // todo - version should be based on above post-filter src
                        'all'
                    );                    
                }

            }

        } // _enqueue()   

        /* Post URLs to IDs function, supports custom post types - borrowed and modified from url_to_postid() in wp-includes/rewrite.php */
        // Taken from http://betterwp.net/wordpress-tips/url_to_postid-for-custom-post-types/
        // Customized to work with non-rewrite URLs
        function url_to_postid($url) {
            global $wp_rewrite;

            $url = apply_filters('url_to_postid', $url);

            // First, check to see if there is a 'p=N' or 'page_id=N' to match against
            if ( preg_match('#[?&](p|page_id|attachment_id)=(\d+)#', $url, $values) )   {
                $id = absint($values[2]);
                if ( $id ) {
                    return $id;
                }
            }

            // Check to see if we are using rewrite rules
            if ( isset( $wp_rewrite ) ) {
                $rewrite = $wp_rewrite->wp_rewrite_rules();    
            }
            

            // Not using rewrite rules, and 'p=N' and 'page_id=N' methods failed, so we're out of options
            if ( empty( $rewrite ) ) {
                if ( isset( $_GET ) && empty( $_GET ) ) {
                    // Add custom rules for non-rewrite URLs
                    foreach( $GLOBALS['wp_post_types'] as $key => $value ) {
                        if ( isset( $_GET[$key] ) && !empty( $_GET[$key] ) ) {
                            $args = array(
                              'name'        => $_GET[$key],
                              'post_type'   => $key,
                              'showposts'   => 1,
                            );
                            if( $post = get_posts( $args ) ) {
                                return $post[0]->ID;
                            }                        
                        }
                    }
                } 
            }

            // Get rid of the #anchor
            $url_split = explode( '#', $url );
            $url = $url_split[0];

            // Get rid of URL ?query=string
            $url_split = explode( '?', $url );
            $url = $url_split[0];

            // Add 'www.' if it is absent and should be there
            if ( false !== strpos( home_url(), '://www.' ) && false === strpos( $url, '://www.' ) ) {
                $url = str_replace( '://', '://www.', $url );
            }

            // Strip 'www.' if it is present and shouldn't be
            if ( false === strpos( home_url(), '://www.' ) ) {
                $url = str_replace('://www.', '://', $url);
            }

            // Strip 'index.php/' if we're not using path info permalinks
            if ( isset( $wp_rewrite ) && !$wp_rewrite->using_index_permalinks() ) {
                $url = str_replace('index.php/', '', $url);
            }

            if ( false !== strpos( $url, home_url() ) ) {
                // Chop off http://domain.com
                $url = str_replace(home_url(), '', $url);
            } else {
                // Chop off /path/to/blog
                $home_path = parse_url(home_url());
                $home_path = isset( $home_path['path'] ) ? $home_path['path'] : '' ;
                $url = str_replace($home_path, '', $url);
            }

            // Trim leading and lagging slashes
            $url = trim( $url, '/' );

            $request = $url;
            if ( empty( $request ) && ( !isset( $_GET ) || empty( $_GET ) ) ) {
                return get_option('page_on_front');
            }
            // Look for matches.
            $request_match = $request;
            foreach ( (array)$rewrite as $match => $query) {
                // If the requesting file is the anchor of the match, prepend it
                // to the path info.
                if ( !empty( $url ) && ( $url != $request ) && ( strpos( $match, $url ) === 0 ) )
                    $request_match = $url . '/' . $request;

                if ( preg_match( "!^$match!", $request_match, $matches ) ) {

                    // Got a match.
                    // Trim the query of everything up to the '?'.
                    $query = preg_replace( "!^.+\?!", '', $query );

                    // Substitute the substring matches into the query.
                    $query = addslashes( WP_MatchesMapRegex::apply( $query, $matches ) );

                    // Filter out non-public query vars
                    global $wp;
                    parse_str( $query, $query_vars );
                    $query = array();
                    foreach ( (array) $query_vars as $key => $value ) {
                        if ( in_array( $key, $wp->public_query_vars ) ) {
                            $query[$key] = $value;
                        }
                    }

                // Taken from class-wp.php
                foreach ( $GLOBALS['wp_post_types'] as $post_type => $t ) {
                    if ( $t->query_var ) {
                        $post_type_query_vars[$t->query_var] = $post_type;
                    }
                }

                foreach ( $wp->public_query_vars as $wpvar ) {
                    if ( isset( $wp->extra_query_vars[$wpvar] ) ) {
                        $query[$wpvar] = $wp->extra_query_vars[$wpvar];
                    } elseif ( isset( $_POST[$wpvar] ) ) {
                        $query[$wpvar] = $_POST[$wpvar];
                    } elseif ( isset( $_GET[$wpvar] ) ) {
                        $query[$wpvar] = $_GET[$wpvar];
                    } elseif ( isset( $query_vars[$wpvar] ) ) {
                        $query[$wpvar] = $query_vars[$wpvar];
                    }
                        

                    if ( !empty( $query[$wpvar] ) ) {
                        if ( ! is_array( $query[$wpvar] ) ) {
                            $query[$wpvar] = (string) $query[$wpvar];
                        } else {
                            foreach ( $query[$wpvar] as $vkey => $v ) {
                                if ( !is_object( $v ) ) {
                                    $query[$wpvar][$vkey] = (string) $v;
                                }
                            }
                        }

                        if ( isset( $post_type_query_vars[$wpvar] ) ) {
                            $query['post_type'] = $post_type_query_vars[$wpvar];
                            $query['name'] = $query[$wpvar];
                        }
                    }
                }
                // Do the query
                if (isset($query['pagename']) && !empty($query['pagename'])) {
                    $args = array(
                      'name'        => $query['pagename'],
                      'post_type'   => 'page',
                      'showposts'   => 1,
                    );
                    if( $post = get_posts( $args ) ) {
                        return $post[0]->ID;
                    }                                              
                }
                $query = new WP_Query($query);

                if ( !empty($query->posts) && $query->is_singular )
                    return $query->post->ID;
                else {
                    return 0;
                }
                        
                }
            }
            
            return 0;
        }                 

        public function _override_values( $options ) {

            global $post, $wp_query;
            
            if ( is_admin() ) {
                return $options;
            }

            $post_id = $this->url_to_postid( 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] );
           
            if ( empty( $post_id ) ) {
                return $options;
            }            

            if( empty( $this->options_defaults ) ) {
                $this->_default_values(); // fill cache
            }
            if ( empty( $this->meta ) ) {
                $this->meta = get_post_meta( $post_id, $this->parent->args['opt_name'], true );    
            }
            // Add the defaults to the current meta
            $meta = wp_parse_args( $this->meta, $this->options_defaults );
            // Override the global defaults
            $options = wp_parse_args($meta, $options);

            return $options;

        } // _override_values()

        public function _default_values() {
            if( !empty( $this->boxes ) && empty( $this->options_defaults ) ) {
                foreach ($this->boxes as $key => $box) {
                    if ( empty( $box['sections'] ) ) {
                        continue;
                    }
                    // fill the cache
                    foreach( $box['sections'] as $section ) {
                        if( isset( $section['fields'] ) ) {
                            foreach( $section['fields'] as $field ) {
                                if( isset( $field['default'] ) ) {
                                    $this->options_defaults[$field['id']] = $field['default'];
                                }
                            }
                        }
                    }       
                }
            }
            $this->options_defaults = apply_filters( 'redux/metabox/'.$this->parent->args['opt_name'].'/defaults', $this->options_defaults );
        } // _default_values()


        public function add_meta_boxes() {

            if ( empty( $this->boxes ) || !is_array( $this->boxes ) ) {
                return;
            }

            foreach ($this->boxes as $key => $box) {
                if ( empty( $box['sections'] ) ) {
                    continue;
                }
                // Save users from themselves
                if ( isset( $box['position'] ) && !in_array( strtolower( $box['position'] ), array( 'normal', 'advanced', 'side' ) ) ) {
                    unset( $box['position'] );
                }
                if ( isset( $box['priority'] ) && !in_array( strtolower( $box['priority'] ), array( 'high', 'core', 'default', 'low' ) ) ) {
                    unset( $box['priority'] );
                }             
                $defaults = array(
                    'id' => $key.'-'.$this->parent->args['opt_name'],
                    'post_types' => array('page', 'post'),
                    'position' => 'normal',
                    'priority' => 'high',
                    );

                $box = wp_parse_args( $box, $defaults );
                if ( isset( $box['post_types'] ) && !empty( $box['post_types'] ) ) {
                    foreach( $box['post_types'] as $posttype ) {
                        if ( isset( $box['title'] ) ) {
                            $title = $box['title'];
                        } else {
                            if ( isset( $box['sections'] ) && count( $box['sections'] ) == 1 && isset( $box['sections'][0]['fields'] ) && count($box['sections'][0]['fields']) == 1 && isset( $box['sections'][0]['fields'][0]['title'] ) ) {
                                // If only one field in this box
                                $title = $box['sections'][0]['fields'][0]['title'];
                            } else {
                                $title = ucfirst( $posttype ) . " " . __('Options', 'redux-framework'); 
                            }

                        }
                        $args = array(
                            'position' => $box['position'],
                            'sections' => $box['sections']
                            );
                        add_meta_box( 'redux-'.$this->parent->args['opt_name'].'-metabox-'.$box['id'], $title, array( $this, 'generate_boxes' ), $posttype, $box['position'], $box['priority'], $args );  
                    } 
                }
            }
        } // add_meta_boxes()

        function _field_default($field_id) {
            if ( !isset( $this->parent->options_defaults ) ) {
                $this->parent->options_defaults = $this->parent->_default_values();
            }
            if ( isset($this->parent->options[$field_id] ) && $this->parent->options[$field_id] != $this->parent->options_defaults[$field_id] ) {
                return $this->parent->options[$field_id];
            } else {
                if( empty( $this->options_defaults ) ) {
                    $this->_default_values(); // fill cache
                }
                if ( !empty( $this->options_defaults ) ) {
                    $data = isset( $this->options_defaults[$field_id] ) ? $this->options_defaults[$field_id] : '';  
                } 
                if ( empty( $data ) && isset( $this->parent->options_defaults[$field_id] ) ) {
                    $data = $this->parent->options_defaults[$field_id]; 
                }
                return $data;                                               
            } 

        } // _field_default()

        function generate_boxes($post, $metabox) {
            global $wpdb;
            $sections = $metabox['args']['sections'];
            wp_nonce_field( 'redux_metaboxes_meta_nonce', 'redux_metaboxes_meta_nonce' );

            $this->parent->_enqueue();

            wp_dequeue_script('json-view-js');

            $sidebar = true;
            if ( $metabox['args']['position'] == "side" || count($sections) == 1 || ( isset( $metabox['args']['sidebar'] ) && $metabox['args']['sidebar'] === false ) ) {
                $sidebar = false; // Show the section dividers or not
            }
            //$this->parent->options = wp_parse_args(get_post_meta( $post->ID, $this->parent->args['opt_name'], true ), $this->parent->options);
            $data = get_post_meta( $post->ID, $this->parent->args['opt_name'], true );
//print_r($data);
            ?>

            <div class="redux-container<?php echo ( $sidebar ) ? ' redux-has-sections' : ' redux-no-sections'; ?> redux-box-<?php echo $metabox['args']['position']; ?>">
                <?php 
                echo '<a href="javascript:void(0);" class="expand_options hide" style="display:none;">' . __( 'Expand', 'redux-framework' ) . '</a>';
                if ( $sidebar ) { ?>
                    <div class="redux-sidebar">
                        <ul class="redux-group-menu">
                            <?php 
                            foreach( $sections as $sKey => $section ) {
                                echo $this->parent->section_menu($sKey, $section, '_box_'.$metabox['id']);
                            }
                            ?>
                        </ul>
                    </div>
                <?php 
                } ?>
                
                <div class="redux-main">
                
                    <?php 

                    $updateLocalize = false;
                    
                    foreach($sections as $sKey => $section) : 
                  
                        if ( isset( $section['fields'] ) && !empty( $section['fields'] ) ) {
                            if ( $sidebar ) {
                                echo '<div id="' . $sKey.'_box_'.$metabox['id'] . '_section_group' . '" class="redux-group-tab redux_metabox_panel">';
                            }   
                            if ( isset( $section['title'] ) && !empty( $section['title'] ) ) {
                                //if ( count( $sections ) == 1 && isset( $section[0]['fields'] ) && count( $section[0]['fields'] ) == 1 && isset( $section[0]['fields'][0]['title'] ) ) {
                                   echo '<h3 class="redux-section-title">'.$section['title'].'</h3>'; 
                                //}
                            }

                            if ( isset( $section['desc'] ) && !empty( $section['desc'] ) ) {
                                echo '<div class="redux-section-desc">' . $section['desc'] . '</div>';    
                            }                                  
                            echo '<table class="form-table"><tbody>';
                            foreach( $section['fields'] as $fKey=> $field ) {
                                $field['name'] = $this->parent->args['opt_name'] . '[' . $field['id'] . ']';
                                echo '<tr valign="top">';
                                $th = "";
                                if( isset( $field['title'] ) && isset( $field['type'] ) && $field['type'] !== "info" && $field['type'] !== "group" && $field['type'] !== "section" ) {
                                    $default_mark = ( isset( $field['default'] ) && !empty($field['default']) && isset($this->parent->options[$field['id']]) && $this->parent->options[$field['id']] == $field['default'] && !empty( $this->parent->args['default_mark'] ) && isset( $field['default'] ) ) ? $this->parent->args['default_mark'] : '';
                                    if ( !empty( $field['title'] ) ) {
                                        $th = $field['title'] . $default_mark."";
                                    }

                                    if( isset( $field['subtitle'] ) ) {
                                        $th .= '<span class="description">' . $field['subtitle'] . '</span>';
                                    }
                                } 
                                // TITLE
                                // Show if various
                                // 
                                if ( $this->parent->args['default_show'] === true && isset( $field['default'] ) && isset($this->options) && isset( $data[$field['id']] ) && $data[$field['id']] != $field['default'] && $field['type'] !== "info" && $field['type'] !== "group" && $field['type'] !== "section" && $field['type'] !== "editor" && $field['type'] !== "ace_editor" ) {
                                    $th .= $this->parent->get_default_output_string($field); // Get the default output string if set    
                                }

                                if ( $sidebar ) {
                                    if ( !( isset( $metabox['args']['sections'] ) && count( $metabox['args']['sections'] ) == 1 && isset( $metabox['args']['sections'][0]['fields'] ) && count( $metabox['args']['sections'][0]['fields'] ) == 1 ) && isset( $field['title'] ) ) {
                                        echo '<th scope="row"><div class="redux_field_th">'.$th.'</div></th>';
                                        echo '<td>';
                                    }     
                                } else {
                                    //<i style="float:right; " class="elusive el-icon-address-book"></i>  //hints for right metaboxes
                                    echo '<td>'.$th.'';
                                }
                                /*
                                if ($field['id'] == "layout") {
                                    print_r($field);        
                                    exit();
                                }
                                */

                                // Set the default if it's a new field
                                $default = $this->_field_default( $field['id'] );
                                $this->parent->options_defaults[$field['id']] = $default;
                                if (!isset($data[$field['id']])) {
                                    $data[$field['id']] = $default;
                                    $this->parent->options[$field['id']] = $default;
                                }
                                if (!isset($field['class'])) {
                                    $field['class'] = "";
                                }

                                if ( isset($field['fields'] ) && !empty( $field['fields'] ) ) {
                                    foreach( $field['fields'] as $gField ) {
                                        $updateLocalize = $this->update_localize( $gField, $data, $updateLocalize );
                                    }
                                } else {
                                    $updateLocalize = $this->update_localize( $field, $data, $updateLocalize );
                                }
                                
                                if (!isset($data[$field['id']])) {
                                    $data[$field['id']] = "";
                                }
                                
                                $this->parent->_field_input($field, $data[$field['id']]);
                                echo '</td></tr>';
                            }
                            echo '</tbody></table>';
                        }
                        if ( $sidebar ) {
                            echo '</div>';
                        }
                    endforeach; ?>
                </div>
                <div class="clear"></div>
            </div>
<?php            
            if ( $updateLocalize ) {
                // Values used by the javascript
                wp_localize_script(
                    'redux-js', 
                    'redux', 
                    $this->localize_data
                );
            }
                

        } // generate_boxes()

        function update_localize( $field, $data, $updateLocalize ) {
            // Update the localization info
            $field_class = 'ReduxFramework_'.$field['type'];

            if( !class_exists( $field_class ) ) {

                if ( !isset( $field['compiler'] ) ) {
                    $field['compiler'] = "";
                }

                 /**
                 * Field class file
                 * 
                 * filter 'redux/{opt_name}/field/class/{field.type}
                 * @param string        field class file
                 * @param array $field  field config data
                 */
                $class_file = apply_filters( "redux/{$this->parent->args['opt_name']}/field/class/{$field['type']}", ReduxFramework::$_dir . "inc/fields/{$field['type']}/field_{$field['type']}.php", $field );
                
                if( $class_file && file_exists($class_file) && !class_exists( $field_class ) ) {
                    /** @noinspection PhpIncludeInspection */
                    require_once( $class_file );
                }

            }  

            if ( ( method_exists( $field_class, 'enqueue' ) ) || method_exists( $field_class, 'localize' ) ) {
                if ( !isset( $data[$field['id']] ) ) {
                    $data[$field['id']] = "";
                }

                $theField = new $field_class( $field, $data[$field['id']], $this->parent );
                
                //if ( !wp_script_is( 'redux-field-'.$field['type'].'-js', 'enqueued' ) && class_exists($field_class) && $this->args['dev_mode'] === true && method_exists( $field_class, 'enqueue' ) ) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    //echo "DOVY";
                    if ( $this->parent->args['dev_mode'] ) {
                        $theField->enqueue();        
                    }
                //}
                unset($theField);                               
            }

            if (method_exists( $field_class, 'localize' )) {
                if ( empty( $this->localize_data ) ) {
                    $this->localize_data = $this->parent->localize_data;
                }
                $this->localize_data[$field['type']][$field['id']] = $field_class::localize($field, $data[$field['id']]);
                if ( !isset( $this->parent->localize_data[$field['type']][$field['id']] ) || $this->localize_data[$field['type']][$field['id']] != $this->parent->localize_data[$field['type']][$field['id']] ) {
                    $updateLocalize = true;    
                }
            }

            return $updateLocalize;      
        }

        /**
         * Save meta boxes
         *
         * Runs when a post is saved and does an action which the write panel save scripts can hook into.
         *
         * @access public
         * @param mixed $post_id
         * @param mixed $post
         * @return void
         */
        function meta_boxes_save( $post_id, $post ) {
            // Check if our nonce is set.
            if ( ! isset( $_POST['redux_metaboxes_meta_nonce'] ) )
                return $post_id;
            $nonce = $_POST['redux_metaboxes_meta_nonce'];
            // Verify that the nonce is valid.
            // Validate fields (if needed)
            //$plugin_options = $this->_validate_values( $plugin_options, $this->options );

            if ( ! wp_verify_nonce( $nonce, 'redux_metaboxes_meta_nonce' ) ) {
                return $post_id;
            }

            // If this is an autosave, our form has not been submitted, so we don't want to do anything.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return $post_id;
            }

            // Check the user's permissions, even allowing custom capabilities
            $obj = get_post_type_object( $post->post_type );                
            if ( ! current_user_can( $obj->cap->edit_post, $post_id ) ) {
                return $post_id;
            }
        
            $toSave = array();
            $toCompare = array();
            foreach($_POST[$this->parent->args['opt_name']] as $key => $value) {
                // Have to remove the escaping for array comparison
                if (is_array($value)) {
                    foreach($value as $k=>$v) {
                        if ( !is_array($v) ) {
                            $value[$k] = stripslashes($v);
                        }
                    }
                }
                if (!isset( $this->parent->options[$key] ) || ( isset( $this->parent->options[$key] ) && $this->parent->options[$key] != $value ) ) {
                    $toSave[$key] = $value;
                    $toCompare[$key] = isset($this->parent->options[$key]) ? $this->parent->options[$key] : "";
                }
            }

            //print_r($toSave);
            //exit();
            if (!empty($toSave)) {
                foreach ($this->boxes as $key => $box) {
                    if ( empty( $box['sections'] ) ) {
                        continue;
                    }
                    // fill the cache
                    $toSave = $this->parent->_validate_values( $toSave, $toCompare, $box['sections'] );
                    //print_r($toSave);
                    //print_r($toCompare);
                    foreach( $box['sections'] as $section ) {
                        if( isset( $section['fields'] ) ) {
                            foreach( $section['fields'] as $field ) {
                                if ( isset( $toSave[$field['id']] ) ) {
                                    if( isset( $field['validate'] ) ) {
                                        if (class_exists("ReduxFramework_{$field['type']}")) {


                                            echo "VALIDATE";    
                                        }
                                        
                                    }
                                }
                            }
                        }
                    }       
                }                
            }

            /* OK, its safe for us to save the data now. */
            update_post_meta( $post_id, $this->parent->args['opt_name'], $toSave );
            
        } // meta_boxes_save()
        

        /**
         * Some functions, like the term recount, require the visibility to be set prior. Lets save that here.
         *
         * @access public
         * @param mixed $post_id
         * @return void
         */
        function pre_post_update( $post_id ) {
            if ( isset( $_POST['_visibility'] ) ) {
                update_post_meta( $post_id, '_visibility', stripslashes( $_POST['_visibility'] ) );
            }
            if ( isset( $_POST['_stock_status'] ) ) {
                update_post_meta( $post_id, '_stock_status', stripslashes( $_POST['_stock_status'] ) );
            }
        } // pre_post_update()

        /**
         * Show any stored error messages.
         *
         * @access public
         * @return void
         */
        function meta_boxes_show_errors() {
            global $redux_metaboxes_errors;

            $redux_metaboxes_errors = maybe_unserialize( get_transient( 'redux_'.$this->parent->args['opt_name'].'_metaboxes_errors' ) );

            if ( ! empty( $redux_metaboxes_errors ) ) {
                echo '<div id="redux_metaboxes_errors" class="error fade">';
                foreach ( $redux_metaboxes_errors as $error ) {
                    echo '<p>' . esc_html( $error ) . '</p>';
                }
                echo '</div>';

                // Clear
                delete_transient( 'redux_'.$this->parent->args['opt_name'].'_metaboxes_errors' );
                $redux_metaboxes_errors = array();
            }

        } // meta_boxes_show_errors()

    } // class ReduxFramework_extension_metaboxes
    
} // if ( !class_exists( 'ReduxFramework_extension_metaboxes' ) )
