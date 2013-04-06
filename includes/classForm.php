<?php

class pzucdForm
{

	function __construct()
	{
		
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	protected function _label( $field )
	{
		$return = 'Label goes here' . esc_html( $field[ 'label' ] );
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	protected function _help( $field )
	{
		$return = '<span class="howto">' . esc_html( $field[ 'help' ] ) . '</span>';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function text( $field, $pizazz_value, $is_new )
	{
		$return = '<input type="text"';
		$return .= ' name="' . sanitize_html_class( $field[ 'id' ] );
		$return .= '" id="' . sanitize_html_class( $field[ 'id' ] );
		$return .= '" value="' . esc_attr( (!$is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field[ 'default' ]  );
		$return .= '" size="30" style="width:97%" ';
		$return .= esc_attr( (!empty( $field[ 'class' ] )) ? ' class="' . $field[ 'class' ] . '" ' : null  );
		$return .= '/><br />';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function textarea( $field, $pizazz_value, $is_new )
	{
		$return = '<textarea ';
		$return .= 'name="' . sanitize_html_class( $field[ 'id' ] );
		$return .= '" id="' . sanitize_html_class( $field[ 'id' ] );
		$return .= '" cols="60" rows="' . esc_attr( (!empty( $field[ 'class' ] )) ? ' class="' . $field[ 'rows' ] . '" ' : 4  ) . '" style="width:97%" ';
		$return .= esc_attr( (!empty( $field[ 'class' ] )) ? ' class="' . $field[ 'class' ] . '" ' : null  );
		$return .= ' />';
		$return .= esc_attr( (!$is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field[ 'default' ]  );
		$return .= '</textarea><br />';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function heading( $field, $pizazz_value, $is_new )
	{
		$return = '';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function infobox( $field, $pizazz_value, $is_new )
	{
		$return = '<span class="pzucd-infobox ';
		$return .= esc_attr( (!empty( $field[ 'class' ] )) ? $field[ 'class' ] : null  );
		$return .= ';">';
		$return .= $field[ 'desc' ];
		$return .= '</span>';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function readonly( $field, $pizazz_value, $is_new )
	{
		$return = '<input type="text" ';
		$return .= 'name="' . sanitize_html_class( $field[ 'id' ] );
		$return .= '" id="' . sanitize_html_class( $field[ 'id' ] );
		$return .= '" readonly="readonly"';
		$return .= 'value="' . esc_attr( (!$is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field[ 'default' ]  );
		$return .= '" ';
		$return .= esc_attr( (!empty( $field[ 'class' ] )) ? ' class="' . $field[ 'class' ] . '" ' : null  );
		$return .= '/>';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function hidden( $field, $pizazz_value, $is_new )
	{
		$return = '<input type="hidden" ';
		$return .= 'name="' . sanitize_html_class( $field[ 'id' ] );
		$return .= '" id="' . sanitize_html_class( $field[ 'id' ] );
		$return .= '" value="' . esc_attr( (!$is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field[ 'default' ]  );
		$return .= '" style="height:0" ';
		$return .= esc_attr( (!empty( $field[ 'class' ] )) ? ' class="' . $field[ 'class' ] . '" ' : null  );
		$return .= ' />';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function percent( $field, $pizazz_value, $is_new )
	{
		$return = '<input alt="' . $field[ 'alt' ] . '"" type="range" min=0 max=100 name="' . sanitize_html_class( $field[ 'id' ] ) . '" id="' . sanitize_html_class( $field[ 'id' ] ) . '" value="' . esc_attr( (!$is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field[ 'default' ]  ) . '"style="width:80%" /><span class="pzucd-range-percent percent-' . sanitize_html_class( $field[ 'id' ] ) . '">' . $pizazz_value . '%</span><br />';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function colorpicker( $field, $pizazz_value, $is_new )
	{
		$return = '<input type="text" name="' . sanitize_html_class( $field[ 'id' ] ) . '" id="' . sanitize_html_class( $field[ 'id' ] ) . '" value="' . esc_attr( (!$is_new && $pizazz_value) ? $pizazz_value : $field[ 'default' ]  ) . '" size="30" style="width:100px" />' . '<span class="pzucd_colour_swatch pzucd_colour_' . sanitize_html_class( $field[ 'id' ] ) . '">&nbsp;</span><br />';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function numeric( $field, $pizazz_value, $is_new )
	{
		$return = '<input type="number" name="' . sanitize_html_class( $field[ 'id' ] ) . '" id="' . sanitize_html_class( $field[ 'id' ] ) . '" value="' . esc_attr( (!$is_new && ($pizazz_value || $pizazz_value === '0' || $pizazz_value === '')) ? $pizazz_value : $field[ 'default' ]  ) . '" size="30" min="' . $field[ 'min' ] . '" max="' . $field[ 'max' ] . '" step="' . $field[ 'step' ] . '" style="width:100px" />' . '<br />';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function select( $field, $pizazz_value, $is_new )
	{
		$pizazz_value	 = ($is_new) ? $field[ 'default' ] : $pizazz_value;
		$return				 = '<select  name="' . sanitize_html_class( $field[ 'id' ] ) . '" id="' . sanitize_html_class( $field[ 'id' ] ) . '">';
		foreach ( $field[ 'options' ] as $option )
		{
			$pizazz_value = (!$pizazz_value) ? $field[ 'default' ] : $pizazz_value;
			$return .= '<option' . (($pizazz_value == $option[ 'value' ]) ? ' selected="selected"' : '') . ' value="' . esc_attr( $option[ 'value' ] ) . '">' . $option[ 'text' ] . '</option>';
		}
		$return .= '</select>';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function multiselect( $field, $pizazz_value, $is_new )
	{
		$return				 = '';
		$pizazz_value	 = ($is_new) ? $field[ 'default' ] : $pizazz_value;
		if ( !$field[ 'options' ] )
		{
			$return .= '<div id="' . sanitize_html_class( $field[ 'id' ] ) . '">';
			$return .= '<span class="pzucd-infobox">No options available</span>';
			$return .= '</div>';
			$return .= '<span class="howto">' . $field[ 'help' ] . '</span>';
			return $return;
		}
		// $return .= '<div id="'. sanitize_html_class($field['id']). '" style="overflow-x:auto;max-height:100px;background:white;border:1px #eee solid">';
		$return .= '<div id="' . sanitize_html_class( $field[ 'id' ] ) . '" style="background:white;border:1px #eee solid">';
		foreach ( $field[ 'options' ] as $option )
		{
			if ( substr( $option[ 'text' ], 0, 1 ) == '>' )
			{
				$return .= '<strong>&nbsp;=== ' . substr( $option[ 'text' ], 1 ) . ' ==========</strong><br/>';
			}
			else
			{
				$pizazz_array_value	 = (is_string( $pizazz_value )) ? array( $pizazz_value ) : $pizazz_value;
				$pzucd_in_array			 = ($pizazz_value) ? (in_array( $option[ 'value' ], $pizazz_array_value ) || $pizazz_value == $option[ 'value' ]) : false;
				$return .= '&nbsp;<input type="checkbox" name="' . sanitize_html_class( $field[ 'id' ] ) . '[]" id="' . sanitize_html_class( $field[ 'id' ] ) . '" value="' . esc_attr( $option[ 'value' ] ) . '"' . (($pzucd_in_array) ? ' checked="checked"' : '') . ' />&nbsp;' . $option[ 'text' ] . '<br/>';
			}
		}
		$return .= '</div>';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function checkbox( $field, $pizazz_value, $is_new )
	{
		$pizazz_value	 = ($is_new) ? $field[ 'default' ] : $pizazz_value;
		$return				 = '<input type="checkbox" name="' . sanitize_html_class( $field[ 'id' ] ) . '" id="' . sanitize_html_class( $field[ 'id' ] ) . '"' . $pizazz_value ? ' checked="checked"' : '' . ' />';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function multicheck( $field, $pizazz_value, $is_new )
	{
		$return				 = '';
		$pizazz_value	 = ($is_new) ? $field[ 'default' ] : $pizazz_value;
		if ( !$field[ 'options' ] )
		{
			$return .= '<div id="' . sanitize_html_class( $field[ 'id' ] ) . '">';
			$return .= '<span class="pzucd-infobox">No options available</span>';
			$return .= '</div>';
			$return .= '<span class="howto">' . $field[ 'help' ] . '</span>';
			return $return;
		}
		$return .= '<div id="' . sanitize_html_class( $field[ 'id' ] ) . '" >';
		foreach ( $field[ 'options' ] as $option )
		{
			$pizazz_array_value	 = (is_string( $pizazz_value )) ? array( $pizazz_value ) : $pizazz_value;
			$pzucd_in_array			 = ($pizazz_value) ? (in_array( $option[ 'value' ], $pizazz_array_value ) || $pizazz_value == $option[ 'value' ]) : false;
			$return .= '<input type="checkbox" name="' . sanitize_html_class( $field[ 'id' ] ) . '[]" id="' . sanitize_html_class( $field[ 'id' ] ) . '_' . $option[ 'value' ] . '" value="' . esc_attr( $option[ 'value' ] ) . '"' . (($pzucd_in_array) ? ' checked="checked"' : '') . ' />&nbsp;' . $option[ 'text' ];
		}
		$return .= '</div>';
		return $return;
	}

	/**
	 * 
	 * @param type $field
	 * @param type $is_new
	 */
	public function custom( $field, $pizazz_value, $is_new )
	{
		$return = '<div id="pzucd-custom-' . sanitize_html_class( $field[ 'id' ] ) . '" class="pzucd-custom">';
		$return .= $field[ 'code' ];
		$return .= '<input type="hidden" readonly name="' . sanitize_html_class( $field[ 'id' ] ) . '" id="' . sanitize_html_class( $field[ 'id' ] ) . '" value="' . esc_attr( (!$is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field[ 'default' ]  ) . '" style="display:none;height:0;/>' . '<br />';
		$return .= '</div>';
		return $return;
	}

	// Fields to add
	public function pages( $field, $pizazz_value, $is_new )
	{
		// Call a select field with pages array
	}

	public function posts( $field, $pizazz_value, $is_new )
	{
		
	}

	public function categories( $field, $pizazz_value, $is_new )
	{
		
	}

	public function tags( $field, $pizazz_value, $is_new )
	{
		
	}

	// Callback function to show fields in meta box
	/**
	 * [pzucd_show_box description]
	 * @param  [type] $postobj              [description]
	 * @param  [type] $callback_args [description]
	 * @return [type]                       [description]
	 */
	function show_meta_box( $postobj, $callback_args )
	{
		global $post, $post_ID;
// var_dump( $callback_args );

		$is_new = (get_post_status() == 'auto-draft');

		$pizazz_meta_boxes = $callback_args[ 'args' ];

		// Use nonce for verification
		echo '<input type="hidden" name="pizazz_meta_box_nonce" value="', wp_create_nonce( basename( __FILE__ ) ), '" />';
		echo '<div id="pzucd_' . esc_attr( $pizazz_meta_boxes[ 'id' ] ) . '" class="pizazz-meta-boxes" >';
		echo '<ul id="pizazz-meta-nav" class="pizazz-meta-nav">';

		foreach ( $pizazz_meta_boxes[ 'tabs' ] as $pizazz_meta_box_tab )
		{
			$pzucd_label_icon			 = $pizazz_meta_box_tab[ 'icon' ];
			$pzucd_showhide_labels = 'show';
			echo '<li class="pizazz-meta-tab-title"><a href="#pizazz-form-table-' . str_replace( ' ', '-', esc_attr( $pizazz_meta_box_tab[ 'label' ] ) ) . '">' . $pzucd_label_icon . '<span class="pzucd_' . esc_attr( $pzucd_showhide_labels ) . '_labels"><div class="contentplus-arrow-left"></div>' . esc_attr( $pizazz_meta_box_tab[ 'label' ] ) . '</span></a></li>';
		}

		echo '</ul><div class="pzucd_the_tables" style="min-height:' . (count( ( int ) $pizazz_meta_boxes[ 'tabs' ] ) * 52 + 10) . 'px">';

		foreach ( $pizazz_meta_boxes[ 'tabs' ] as $pizazz_meta_box_tab )
		{

			echo '<table id="pizazz-form-table-' . str_replace( ' ', '-', esc_attr( $pizazz_meta_box_tab[ 'label' ] ) ) . '" class="form-table pizazz-form-table" "style="width:60%!important;background:#eff!important;">';

			foreach ( $pizazz_meta_box_tab[ 'fields' ] as $field )
			{
				// get current post meta data
				//			$pzucd_class	 = (!empty( $field[ 'class' ] ) ? ' class="' . $field[ 'class' ] . '" ' : null);
				$pizazz_value = get_post_meta( $post->ID, $field[ 'id' ], true );

				// WORK ON THIS!!
				// $pizazz_value = ($force_default && $pizazz_value === '')?$field['default']:$pizazz_value;
				//	if $pizazz_value is null it chucks a warning in in_array as it wants an array

				echo '<tr id="pizazz-form-table-row-' . str_replace( ' ', '-', sanitize_html_class( $pizazz_meta_box_tab[ 'label' ] ) ) . '-field-' . sanitize_html_class( $field[ 'id' ] ) . '" class="row-' . sanitize_html_class( $field[ 'id' ] ) . ' ' . 'pzucd_field_' . $field[ 'type' ] . '">';

				if ( $field[ 'type' ] == 'hidden' )
				{
					echo '<th></th>';
				}
				elseif ( $field[ 'type' ] != 'heading' )
				{
					echo '<th><label class="title-' . sanitize_html_class( $field[ 'id' ] ) . '" for="', sanitize_html_class( $field[ 'id' ] ), '">', esc_attr( $field[ 'label' ] ), '</label></th>';
				}
				else
				{
					echo '<th class="pz-field-heading"><h4 class="pz-field-heading">' . esc_html( $field[ 'label' ] ) . '</h4></th>';
				}

				if ( $field[ 'type' ] != 'infobox' && $field[ 'type' ] != 'heading' && $field[ 'type' ] != 'hidden' )
				{
					echo '<td class="pz-help"><span class="pz-help-button">?<span class="pz-help-text">' . esc_html( $field[ 'desc' ] ) . '</span></span></td>';
				}
				else
				{
					echo '<td class="pz-help"></td>';
				}

				echo '<td class="cell-' . sanitize_html_class( $field[ 'id' ] ) . '">';


				/* By using a variable to represent the field type, we can do away with the switch statements */
				// once we get the defaults working right, then there shouldn't be a need to set pizazz_value or sue defaults in the field display 
				// the ideal method is the load the record with the defaults then replace with the db values if the exist.
				// Devs can create their own fields by naming them "classname::fieldtype"
				if ( strpos( $field[ 'type' ], '::' ) )
				{
					echo apply_filters( 'pzucd_display_metabox_field', $field[ 'type' ]( $field, $pizazz_value, $is_new ) );
				}
				elseif ( in_array( $field[ 'type' ], array(
										'help',
										'label',
										'checkbox',
										'colorpicker',
										'custom',
										'heading',
										'hidden',
										'infobox',
										'multicheck',
										'multiselect',
										'numeric',
										'percent',
										'readonly',
										'select',
										'text',
										'textarea'
								) ) )
				{
					echo apply_filters( 'pzucd_display_metabox_field', self::$field[ 'type' ]( $field, $pizazz_value, $is_new ) );
				}
				else
				{
					// oops! this is not good! An unknown field. Better throw an exception or something
					echo 'Unknown field type: ', $field[ 'type' ];
				}

				if ( array_key_exists( 'help', $field ) )
					echo apply_filters( 'pzucd_display_metabox_field_help', self::_help( $field ) );
				echo apply_filters( 'pzucd_display_metabox_field_end', '' );

				echo '</div><td>',
				'</tr>';
			}

			echo '</table>';
		}

		echo '</div>';
		echo '</div>';
	}

	/**
	 * Save data from custom meta boxes
	 * @param  [type] $post_id [Custom post type Post ID]
	 * @return [type]          [no return value]
	 */
	public function save_data( $post_id, $meta_box_layout )
	{

		// This just traps when the post is created as WP save runs straight away
		if ( !isset( $_POST[ 'post_type' ] ) )
		{
			return false;
		}


		// verify nonce
		if ( !wp_verify_nonce( $_POST[ 'pizazz_meta_box_nonce' ], basename( __FILE__ ) ) )
		{
			return $post_id;
		}

		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return $post_id;
		}

		// check permissions
		if ( 'page' == $_POST[ 'post_type' ] )
		{
			if ( !current_user_can( 'edit_page', $post_id ) )
			{
				return $post_id;
			}
		}
		elseif ( !current_user_can( 'edit_post', $post_id ) )
		{
			return $post_id;
		}

		foreach ( $meta_box_layout[ 'tabs' ] as $meta_box )
		{

			foreach ( $meta_box[ 'fields' ] as $field )
			{
				$old = get_post_meta( $post_id, $field[ 'id' ], true );
				$new = (isset( $_POST[ $field[ 'id' ] ] )) ? $_POST[ $field[ 'id' ] ] : null;
				if ( $new != $old )
				{
					update_post_meta( $post_id, $field[ 'id' ], $new );
				}
				elseif ( '' == $new && $old )
				{
					delete_post_meta( $post_id, $field[ 'id' ], $old );
				}
			}
		}
	}

	//add_action('post_updated', 'pizazz_save_data');
}

// EOC