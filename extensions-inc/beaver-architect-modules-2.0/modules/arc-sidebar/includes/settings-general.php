<?php $sidebar_list=FLBuilderModel::get_wp_sidebars();
  $sidebars = array_merge(
    array('none'=>array (
      'name' => __('Do not show a sidebar on this device','pzarchitect') ,
      'id' => 'nosidebar' ,
    )),$sidebar_list);
?>
<div id="fl-builder-settings-section-general" class="fl-builder-settings-section">
	<table class="fl-form-table">
		<tr id="fl-field-sidebar" class="fl-field" data-type="select" data-preview='{"type":"refresh"}'>
			<th>
				<label for="default_sidebar"><?php _e( 'Default Sidebar', 'fl-builder' ); ?></label>
			</th>
			<td>
				<select name="default_sidebar">
					<?php foreach ( $sidebars as $sidebar ) : ?>
					<option value="<?php echo $sidebar['id']; ?>"<?php if ( isset( $settings->default_sidebar ) && $settings->default_sidebar == $sidebar['id'] ) { echo ' selected="selected"';} ?>><?php echo $sidebar['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr id="fl-field-sidebar" class="fl-field" data-type="select" data-preview='{"type":"refresh"}'>
			<th>
				<label for="tablet_sidebar"><?php _e( 'Tablet Sidebar', 'fl-builder' ); ?></label>
			</th>
			<td>
				<select name="tablet_sidebar">
					<?php foreach ( $sidebars as $sidebar ) : ?>
					<option value="<?php echo $sidebar['id']; ?>"<?php if ( isset( $settings->tablet_sidebar ) && $settings->tablet_sidebar == $sidebar['id'] ) { echo ' selected="selected"';} ?>><?php echo $sidebar['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr id="fl-field-sidebar" class="fl-field" data-type="select" data-preview='{"type":"refresh"}'>
			<th>
				<label for="phone_sidebar"><?php _e( 'Phone Sidebar', 'fl-builder' ); ?></label>
			</th>
			<td>
				<select name="phone_sidebar">
					<?php foreach ( $sidebars as $sidebar ) : ?>
					<option value="<?php echo $sidebar['id']; ?>"<?php if ( isset( $settings->phone_sidebar ) && $settings->phone_sidebar == $sidebar['id'] ) { echo ' selected="selected"';} ?>><?php echo $sidebar['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</table>
</div>
