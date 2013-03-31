<?php

class pzucdForm {


	function __construct() 
	{
		add_action('save_post', array($this,'save_data'));
	}
	private function _label($field)
	{
		echo 'Label goes here'.$field['label'];
	}

	private function _help($field) 
	{
		echo '<span class="howto">'.$field['help'].'</span>';
	}


	public function text($field)
	{
		echo '<tr>';
			echo '<td>';
				_label($field);
			echo '</td><td>';
				echo '<input type="text"';
				echo ' name="', sanitize_html_class($field['id']);
				echo '" id="', sanitize_html_class($field['id']);
				echo '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']);
				echo '" size="30" style="width:97%" ';
				echo esc_attr((!empty($field['class']))?' class="'.$field['class'].'" ':null);
				echo '/> <br />';
				_help($field);
			echo '</td>';
		echo '</tr>';
	}

	public function textarea($field)
	{
		echo '<textarea name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" cols="60" rows="4" style="width:97%">', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default']), '</textarea>', '<br />';
		echo '<span class="howto">'.$field['help'].'</span>';
	}

	public function heading($field)
	{
			echo '';
	}

	public function infobox($field)
	{
		echo '<span class="pzucd-infobox">',$field['desc'],'</span>';
		
	}

	public function readonly($field)
	{
		echo '<input type="text" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" readonly="readonly" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '"  '.esc_attr($pzucd_class).'/>';
		
	}

	public function hidden($field)
	{
		echo '<input type="hidden" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '" style="height:0" />';
	}

	public function percent($field)
	{
		echo '<input alt="'.$field['alt'].'"" type="range" min=0 max=100 name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '"style="width:80%" /><span class="pzucd-range-percent percent-'.sanitize_html_class($field['id']).'">', $pizazz_value,'%</span><br />';
		echo '<span class="howto">'.$field['help'].'</span>';
	}

	public function colorpicker($field)
	{
		echo '<input type="text" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && $pizazz_value) ? $pizazz_value : $field['default']), '" size="30" style="width:100px" />', '<span class="pzucd_colour_swatch pzucd_colour_'.sanitize_html_class($field['id']).'">&nbsp;</span><br />';
		echo '<span class="howto">'.$field['help'].'</span>';
	}

	public function numeric($field)
	{
		echo '<input type="number" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="',esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '0' || $pizazz_value === '')) ? $pizazz_value : $field['default']), '" size="30" min="'.$field['min'].'" max="'.$field['max'].'" step="'.$field['step'].'" style="width:100px" />', '<br />';
		echo '<span class="howto">'.$field['help'].'</span>';
	}

	public function select($field)
	{
											$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
											echo '<select  name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '">';
											foreach ($field['options'] as $option) {
												$pizazz_value = (!$pizazz_value) ? $field['default'] : $pizazz_value;
												echo '<option'.(($pizazz_value == $option['value']) ? ' selected="selected"' : '').' value="'.esc_attr($option['value']).'">'.$option['text'].'</option>';
											}
											echo '</select>';
											echo '<span class="howto">'.$field['help'].'</span>';
		
	}

	public function multiselect($field)
	{
		$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
		if (!$field['options']) {
			echo '<div id="', sanitize_html_class($field['id']), '">';
			echo '<span class="pzucd-infobox">No options available</span>';
			echo '</div>';
			echo '<span class="howto">'.$field['help'].'</span>';
			return;
		}
		// echo '<div id="', sanitize_html_class($field['id']), '" style="overflow-x:auto;max-height:100px;background:white;border:1px #eee solid">';
		echo '<div id="', sanitize_html_class($field['id']), '" style="background:white;border:1px #eee solid">';
		foreach ($field['options'] as $option) {
			if (substr($option['text'],0,1)=='>') {
				echo '<strong>&nbsp;=== '.substr($option['text'],1).' ==========</strong><br/>';
			} else {
				$pizazz_array_value = (is_string($pizazz_value))?array($pizazz_value):$pizazz_value;
				$pzucd_in_array = ($pizazz_value) ? (in_array($option['value'],$pizazz_array_value) || $pizazz_value == $option['value']) : false;
				echo '&nbsp;<input type="checkbox" name="'.sanitize_html_class($field['id']).'[]" id="'.sanitize_html_class($field['id']).'" value="'.esc_attr($option['value']).'"'.(($pzucd_in_array) ? ' checked="checked"' : '').' />&nbsp;'.$option['text'].'<br/>';
			}
		}
		echo '</div>';
		
	}

	public function checkbox($field)
	{
		
	}

	public function multicheck($field)
	{
		$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
		if (!$field['options']) {
			echo '<div id="', sanitize_html_class($field['id']), '">';
			echo '<span class="pzucd-infobox">No options available</span>';
			echo '</div>';
			echo '<span class="howto">'.$field['help'].'</span>';
			return;
		}
		echo '<div id="', sanitize_html_class($field['id']), '" >';
		foreach ($field['options'] as $option) {
				$pizazz_array_value = (is_string($pizazz_value))?array($pizazz_value):$pizazz_value;
				$pzucd_in_array = ($pizazz_value) ? (in_array($option['value'],$pizazz_array_value) || $pizazz_value == $option['value']) : false;
				echo '<input type="checkbox" name="'.sanitize_html_class($field['id']).'[]" id="'.sanitize_html_class($field['id']).'_'.$option['value'].'" value="'.esc_attr($option['value']).'"'.(($pzucd_in_array) ? ' checked="checked"' : '').' />&nbsp;'.$option['text'];
		}
		echo '</div>';
	}

	public function custom($field)
	{
		
	}
	
  public function display_form($fields) {
		global  $post, $post_ID;
		$pzucd_is_new = (get_post_status()=='auto-draft');
		$pizazz_meta_boxes = $pizazz_callback_args['args'];
		// Use nonce for verification
		echo '<input type="hidden" name="pizazz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		echo '<div id="pzucd_'.esc_attr($pizazz_meta_boxes['id']).'" class="pizazz-meta-boxes" >';
		
		echo '<ul id="pizazz-meta-nav" class="pizazz-meta-nav">';
		foreach ($pizazz_meta_boxes['tabs'] as $pizazz_meta_box_tab) {
					$pzucd_label_icon = $pizazz_meta_box_tab['icon'];
					$pzucd_showhide_labels ='show';
			echo '<li class="pizazz-meta-tab-title"><a href="#pizazz-form-table-'.str_replace(' ', '-', esc_attr($pizazz_meta_box_tab['label'])).'">'.$pzucd_label_icon.'<span class="pzucd_'.esc_attr($pzucd_showhide_labels).'_labels"><div class="contentplus-arrow-left"></div>'.esc_attr($pizazz_meta_box_tab['label']).'</span></a></li>';
		}
		echo '</ul>
		<div class="pzucd_the_tables" style="min-height:'.(count((int) $pizazz_meta_boxes['tabs'])*52+10).'px">';
		foreach ($pizazz_meta_boxes['tabs'] as $pizazz_meta_box_tab) {
				echo '<table id="pizazz-form-table-'.str_replace(' ', '-', esc_attr($pizazz_meta_box_tab['label'])).'" class="form-table pizazz-form-table" "style="width:60%!important;background:#eff!important;">';
				foreach ($pizazz_meta_box_tab['fields'] as $field) {
						// get current post meta data
					$pzucd_class = (!empty($field['class']))?' class="'.$field['class'].'" ':null;
						$pizazz_value = get_post_meta($post->ID, $field['id'], true);




						/////
						// WORK ON THIS!!
						// $pizazz_value = ($force_default && $pizazz_value === '')?$field['default']:$pizazz_value;
						/////








						//	if $pizazz_value is null it chucks a warning in in_array as it wants an array
						echo '<tr id="pizazz-form-table-row-'.str_replace(' ', '-', sanitize_html_class($pizazz_meta_box_tab['label'])).'-field-'.sanitize_html_class($field['id']).'" class="row-'.sanitize_html_class($field['id']).' '.'pzucd_field_'.$field['type'].'">';

						if ($field['type'] == 'hidden') {
							echo '<th></th>';
						} elseif ($field['type'] != 'heading') {
							echo '<th><label class="title-'.sanitize_html_class($field['id']).'" for="', sanitize_html_class($field['id']), '">', esc_attr($field['label']), '</label></th>';
						} else {
							echo '<th class="pz-field-heading"><h4 class="pz-field-heading">'.esc_html($field['label']).'</h4></th>';
						}

						if ($field['type'] != 'infobox' && $field['type'] != 'heading' && $field['type'] != 'hidden') {
							echo '<td class="pz-help"><span class="pz-help-button">?<span class="pz-help-text">'.esc_html($field['desc']).'</span></span></td>';
						} else {
							echo '<td class="pz-help"></td>';
						}

						echo '<td class="cell-'.sanitize_html_class($field['id']).'">';
						// This is simply to stop PHP debugging notice about missing index 'help' when help isn't specified
						$field['help'] = ((!array_key_exists('help',$field))?null:$field['help']);
								echo '</div><td>',
											'</tr>';
							}
					 
							echo '</table>';
					}
				echo '</div>';
				echo '</div>';

  }

  public function close_form() {
				}

	// Callback function to show fields in meta box
	/**
	 * [pzucd_show_box description]
	 * @param  [type] $postobj              [description]
	 * @param  [type] $pizazz_callback_args [description]
	 * @return [type]                       [description]
	 */
	function show_box($postobj,$pizazz_callback_args) {
			global  $post, $post_ID;
			$pzucd_is_new = (get_post_status()=='auto-draft');
			$pizazz_meta_boxes = $pizazz_callback_args['args'];
			// Use nonce for verification
			echo '<input type="hidden" name="pizazz_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
			echo '<div id="pzucd_'.esc_attr($pizazz_meta_boxes['id']).'" class="pizazz-meta-boxes" >';
			
			echo '<ul id="pizazz-meta-nav" class="pizazz-meta-nav">';
			foreach ($pizazz_meta_boxes['tabs'] as $pizazz_meta_box_tab) {
						$pzucd_label_icon = $pizazz_meta_box_tab['icon'];
						$pzucd_showhide_labels ='show';
				echo '<li class="pizazz-meta-tab-title"><a href="#pizazz-form-table-'.str_replace(' ', '-', esc_attr($pizazz_meta_box_tab['label'])).'">'.$pzucd_label_icon.'<span class="pzucd_'.esc_attr($pzucd_showhide_labels).'_labels"><div class="contentplus-arrow-left"></div>'.esc_attr($pizazz_meta_box_tab['label']).'</span></a></li>';
			}
			echo '</ul>
			<div class="pzucd_the_tables" style="min-height:'.(count((int) $pizazz_meta_boxes['tabs'])*52+10).'px">';
			foreach ($pizazz_meta_boxes['tabs'] as $pizazz_meta_box_tab) {
					echo '<table id="pizazz-form-table-'.str_replace(' ', '-', esc_attr($pizazz_meta_box_tab['label'])).'" class="form-table pizazz-form-table" "style="width:60%!important;background:#eff!important;">';
					foreach ($pizazz_meta_box_tab['fields'] as $field) {
							// get current post meta data
						$pzucd_class = (!empty($field['class']))?' class="'.$field['class'].'" ':null;
							$pizazz_value = get_post_meta($post->ID, $field['id'], true);




	/////
	// WORK ON THIS!!
	// $pizazz_value = ($force_default && $pizazz_value === '')?$field['default']:$pizazz_value;
	/////








							//	if $pizazz_value is null it chucks a warning in in_array as it wants an array
							echo '<tr id="pizazz-form-table-row-'.str_replace(' ', '-', sanitize_html_class($pizazz_meta_box_tab['label'])).'-field-'.sanitize_html_class($field['id']).'" class="row-'.sanitize_html_class($field['id']).' '.'pzucd_field_'.$field['type'].'">';

							if ($field['type'] == 'hidden') {
								echo '<th></th>';
							} elseif ($field['type'] != 'heading') {
								echo '<th><label class="title-'.sanitize_html_class($field['id']).'" for="', sanitize_html_class($field['id']), '">', esc_attr($field['label']), '</label></th>';
							} else {
								echo '<th class="pz-field-heading"><h4 class="pz-field-heading">'.esc_html($field['label']).'</h4></th>';
							}

							if ($field['type'] != 'infobox' && $field['type'] != 'heading' && $field['type'] != 'hidden') {
								echo '<td class="pz-help"><span class="pz-help-button">?<span class="pz-help-text">'.esc_html($field['desc']).'</span></span></td>';
							} else {
								echo '<td class="pz-help"></td>';
							}

							echo '<td class="cell-'.sanitize_html_class($field['id']).'">';
							// This is simply to stop PHP debugging notice about missing index 'help' when help isn't specified
							$field['help'] = ((!array_key_exists('help',$field))?null:$field['help']);
							switch ($field['type']) {
									case 'heading':
											echo '';
											break;
									case 'infobox':
											echo '<span class="pzucd-infobox">',$field['desc'],'</span>';
											break;
									case 'readonly':
											echo '<input type="text" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" readonly="readonly" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '"  '.esc_attr($pzucd_class).'/>';
											break;
									case 'text':
											echo '<input type="text" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '" size="30" style="width:97%" '.esc_attr($pzucd_class).'/>', '<br />';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
									case 'hidden':
											echo '<input type="hidden" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '" style="height:0" />';
											break;
									case 'percent':
											echo '<input alt="'.$field['alt'].'"" type="range" min=0 max=100 name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '"style="width:80%" /><span class="pzucd-range-percent percent-'.sanitize_html_class($field['id']).'">', $pizazz_value,'%</span><br />';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
									case 'colorpicker':
											echo '<input type="text" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && $pizazz_value) ? $pizazz_value : $field['default']), '" size="30" style="width:100px" />', '<span class="pzucd_colour_swatch pzucd_colour_'.sanitize_html_class($field['id']).'">&nbsp;</span><br />';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
									case 'numeric':
											echo '<input type="number" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="',esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '0' || $pizazz_value === '')) ? $pizazz_value : $field['default']), '" size="30" min="'.$field['min'].'" max="'.$field['max'].'" step="'.$field['step'].'" style="width:100px" />', '<br />';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
										case 'textarea':
											echo '<textarea name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" cols="60" rows="4" style="width:97%">', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default']), '</textarea>', '<br />';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
										case 'textarea-oneline':
											echo '<textarea name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" cols="60" rows="1" style="width:97%">', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default']), '</textarea>', '<br />';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
										case 'textarea-small':
											echo '<textarea name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" cols="60" rows="2" style="width:97%">', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default']), '</textarea>', '<br />';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
										case 'textarea-large':
											echo '<textarea name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" cols="60" rows="16" style="width:97%">', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '')) ? $pizazz_value : $field['default']), '</textarea>', '<br />';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
									case 'select':
											$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
											echo '<select  name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '">';
											foreach ($field['options'] as $option) {
												$pizazz_value = (!$pizazz_value) ? $field['default'] : $pizazz_value;
												echo '<option'.(($pizazz_value == $option['value']) ? ' selected="selected"' : '').' value="'.esc_attr($option['value']).'">'.$option['text'].'</option>';
											}
											echo '</select>';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
									case 'ddslick-select':
											$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
											echo '<select  name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" class="pzucd_ddslick">';
											foreach ($field['options'] as $option) {
												$pizazz_value = (!$pizazz_value) ? $field['default'] : $pizazz_value;
												echo '<option'.(($pizazz_value == $option['value']) ? ' selected="selected"' : '').' value="'.esc_attr($option['value']).'">'.$option['text'].'</option>';
											}
											echo '</select>';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
									case 'multicheck':
											$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
											if (!$field['options']) {
												echo '<div id="', sanitize_html_class($field['id']), '">';
												echo '<span class="pzucd-infobox">No options available</span>';
												echo '</div>';
												echo '<span class="howto">'.$field['help'].'</span>';
												break;
											}
											// echo '<div id="', sanitize_html_class($field['id']), '" style="overflow-x:auto;max-height:100px;background:white;border:1px #eee solid">';
											echo '<div id="', sanitize_html_class($field['id']), '" >';
											foreach ($field['options'] as $option) {
													$pizazz_array_value = (is_string($pizazz_value))?array($pizazz_value):$pizazz_value;
													$pzucd_in_array = ($pizazz_value) ? (in_array($option['value'],$pizazz_array_value) || $pizazz_value == $option['value']) : false;
													echo '<input type="checkbox" name="'.sanitize_html_class($field['id']).'[]" id="'.sanitize_html_class($field['id']).'_'.$option['value'].'" value="'.esc_attr($option['value']).'"'.(($pzucd_in_array) ? ' checked="checked"' : '').' />&nbsp;'.$option['text'];
											}
											echo '</div>';
											echo '<span class="howto">'.$field['help'].'</span>';
										break;
									case 'multiselect':
											$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
											if (!$field['options']) {
												echo '<div id="', sanitize_html_class($field['id']), '">';
												echo '<span class="pzucd-infobox">No options available</span>';
												echo '</div>';
												echo '<span class="howto">'.$field['help'].'</span>';
												break;
											}
											// echo '<div id="', sanitize_html_class($field['id']), '" style="overflow-x:auto;max-height:100px;background:white;border:1px #eee solid">';
											echo '<div id="', sanitize_html_class($field['id']), '" style="background:white;border:1px #eee solid">';
											foreach ($field['options'] as $option) {
												if (substr($option['text'],0,1)=='>') {
													echo '<strong>&nbsp;=== '.substr($option['text'],1).' ==========</strong><br/>';
												} else {
													$pizazz_array_value = (is_string($pizazz_value))?array($pizazz_value):$pizazz_value;
													$pzucd_in_array = ($pizazz_value) ? (in_array($option['value'],$pizazz_array_value) || $pizazz_value == $option['value']) : false;
													echo '&nbsp;<input type="checkbox" name="'.sanitize_html_class($field['id']).'[]" id="'.sanitize_html_class($field['id']).'" value="'.esc_attr($option['value']).'"'.(($pzucd_in_array) ? ' checked="checked"' : '').' />&nbsp;'.$option['text'].'<br/>';
												}
											}
											echo '</div>';
											echo '<span class="howto">'.$field['help'].'</span>';
										break;
							
									case 'radio':
											foreach ($field['options'] as $option) {
													$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
													echo '<input type="radio" name="', sanitize_html_class($field['id']), '" value="', esc_attr($option['value'], '"', $pizazz_value == $option['value'] ? ' checked="checked"' : ''), ' />&nbsp;', $option['text'], '&nbsp;&nbsp;&nbsp;';
											}
											echo '<span class="howto">'.$field['help'].'</span>';
										break;
									case 'checkbox':
											$pizazz_value = ($pzucd_is_new)?$field['default']:$pizazz_value;
											echo '<input type="checkbox" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '"', $pizazz_value ? ' checked="checked"' : '', ' />';
											echo '<span class="howto">'.$field['help'].'</span>';
										break;
									case 'dropzone':
										 echo '<div id="pzucd-dropzone-'.sanitize_html_class($field['id']).'" class="pzucd-dropzone">';
										 echo '</div>';
											echo '<input type="text" name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '" size="30" style="width:97%" />', '<br />';
											//echo '<input type="text" name="pzucd-cell-template-'.sanitize_html_class($field['id']).'" id="pzucd-cell-template-'.sanitize_html_class($field['id']).'" size=44><br/>';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
									case 'dragsource':
											$pizazz_value = ($pzucd_is_new) ? $field['default'] : $pizazz_value;
											if (!$field['options']) {
												echo '<div id="element-', sanitize_html_class($field['id']), '">';
												echo '<span class="pzucd-infobox">No options available</span>';
												echo '</div>';
												echo '<span class="howto">'.$field['help'].'</span>';
												break;
											}
											echo '<div id="', sanitize_html_class($field['id']), '">';
											foreach ($field['options'] as $option) {
													echo '<div id="element-'.sanitize_html_class($field['id']).'" alt="'.esc_attr($option['value']).'" class="pzucd-dragsource dragsource-'.sanitize_html_class($field['id']).'"><span>'.$option['text'].'</span></div>';
												}
											echo '</div>';
											echo '<span class="howto">'.$field['help'].'</span>';
											break;
									case 'custom':
										 echo '<div id="pzucd-custom-'.sanitize_html_class($field['id']).'" class="pzucd-custom">';
										 echo $field['code'];
											echo '<input type="hidden" readonly name="', sanitize_html_class($field['id']), '" id="', sanitize_html_class($field['id']), '" value="', esc_attr((!$pzucd_is_new && ($pizazz_value || $pizazz_value === '' || $pizazz_value === '0')) ? $pizazz_value : $field['default']), '" style="display:none;height:0;/>', '<br />';
										 echo '</div>';
										echo '<span class="howto">'.$field['help'].'</span>';
										break;
							}
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
	function save_data($post_id) {
		//var_dump($post_id,$_POST['post_type']);
		// Will need to manually add each case as new types created.
		if (!isset($_POST['post_type'])) {return false;}
		switch ($_POST['post_type']) {
			case 'ucd-layouts' :
				global $pzucd_cpt_layouts_meta_boxes;
	pzdebug($pzucd_cpt_layouts_meta_boxes);
				$pizazz_meta_boxes = $pzucd_cpt_layouts_meta_boxes;
				break;
			default:
				return false;
		}
	//pzdebug($pizazz_meta_boxes);
			// verify nonce
			if (!wp_verify_nonce($_POST['pizazz_meta_box_nonce'], basename(__FILE__))) {
					return $post_id;
			}
	 
			// check autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
					return $post_id;
			}
	 
			// check permissions
			if ('page' == $_POST['post_type']) {
					if (!current_user_can('edit_page', $post_id)) {
							return $post_id;
					}
			} elseif (!current_user_can('edit_post', $post_id)) {
					return $post_id;
			}
			foreach ($pizazz_meta_boxes['tabs'] as $pzucd_meta_box) {
				foreach ($pzucd_meta_box['fields'] as $field) {
						$old = get_post_meta($post_id, $field['id'], true);
						$new = (isset($_POST[$field['id']]))?$_POST[$field['id']]:null;
						if ($new != $old) {
								update_post_meta($post_id, $field['id'], $new);
						} elseif ('' == $new && $old) {
								delete_post_meta($post_id, $field['id'], $old);
						}
				}
		}
	}
	//add_action('post_updated', 'pizazz_save_data');

} // EOC