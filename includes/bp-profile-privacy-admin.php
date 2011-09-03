<?php

function bp_profile_privacy_admin() {
	global $bp, $wp_roles;

	$groups = BP_XProfile_Group::get( array( 'fetch_fields' => true ) );
	
	$admin_settings = get_option('bp_profile_privacy');
	if ( empty( $admin_settings ) || !is_array( $admin_settings ) )
		$admin_settings = array();

?>
	<div class="wrap">

		<h2><?php _e( 'Profile Privacy Setup', PPL_TD) ?></h2>

		<form action="../options.php" id="profile-type-form" method="post">

		<?php settings_fields( 'bp-profile-privacy' ); ?>
		
		<?php if ( $groups ) { ?>
		
			<?php foreach ( $groups as $group ) { ?>
			<p>
			<table id="group-<?php echo $group->id; ?>" class="widefat">
				<thead>
					<tr>
						<th scope="col" colspan="2"><?php echo attribute_escape( $group->name ); ?></th>
					</tr>
					<tr class="header">
						<td><?php _e( 'Who Can See?', PPL_TD ); ?></td>
						<td><?php _e( 'Field Name', PPL_TD ); ?></td>
				</thead>
				<tbody>

				<?php if ( isset( $group->fields ) && is_array( $group->fields ) ) { ?>
					<?php foreach ( $group->fields as $field ) { ?>
					<tr id="field-<?php echo $field->id; ?>">
						<td style="width:150px;">
							<select name="bp_profile_privacy[<?php echo $field->id; ?>]" id="select_field-<?php echo $field->id; ?>">
								<?php $options = array( 0 => __( 'Everyone', PPL_TD ), 1 => __( 'Friends', PPL_TD ), 2 => __( 'User', PPL_TD ), 3 => __( 'Let User Decide', PPL_TD ) );
								foreach ( $options as $value => $label ) {
									$selected = ( $value == $admin_settings[$field->id] ) ? ' selected="selected"' : '';
									echo "\n<option value='$value'$selected>$label</option>";
								} ?>
							</select>
						</td>
						<td><label for="select_field-<?php echo $field->id; ?>"><?php echo attribute_escape( $field->name ); ?></label></td>
					</tr>
					<?php } /* End Foreach Field */ ?>
				<?php } else { ?>
					<tr valign="top">
						<td colspan="2"><?php _e('There are no fields.', PPL_TD); ?></td>
					</tr>
				<?php } /* End If Fields */ ?>
				</tbody>
			</table>
			</p>
			<?php } /* End Foreach Group */ ?>

		<?php } else { ?>

			<p><?php _e( 'There are groups.', PPL_TD ); ?></p>

		<?php } /* End If Groups */ ?>

		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', PPL_TD); ?>" />
		</p>

		</form>

	</div>
<?php
}

function bp_profile_privacy_sanitize_settings($settings) {

	$groups = BP_XProfile_Group::get( array( 'fetch_fields' => true ) );
	
	$save = array();
	
	if ( $groups ) {
		foreach ( $groups as $group ) {
			if ( isset( $group->fields ) ) {
				foreach ( (array) $group->fields as $field ) {
					$save[$field->id] = absint( $settings[$field->id] );
				}
			}
		}
	}
	return $save;
}

?>