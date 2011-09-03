<?php



function bp_profile_privacy_setup_globals() {
	global $bp, $wpdb;

	/* For internal identification */
	$bp->profile_privacy->id = BP_PROFILE_PRIVACY_SLUG;

	//$bp->profile_privacy->table_name = $wpdb->base_prefix . 'bp_profile_privacy';
	//$bp->profile_privacy->format_notification_function = 'bp_profile_privacy_format_notifications';
	$bp->profile_privacy->slug = BP_PROFILE_PRIVACY_SLUG;

	/* Register this in the active components array */
	$bp->active_components[$bp->profile_privacy->slug] = $bp->profile_privacy->id;
}
add_action( 'wp', 'bp_profile_privacy_setup_globals', 2 );
add_action( 'admin_menu', 'bp_profile_privacy_setup_globals', 2 );
add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', 'bp_profile_privacy_setup_globals',2);

function bp_profile_privacy_add_admin_menu() {
	global $bp;

	if ( !$bp->loggedin_user->is_site_admin )
		return false;

	require ( dirname( __FILE__ ) . '/includes/bp-profile-privacy-admin.php' );
	
	register_setting( 'bp-profile-privacy', 'bp_profile_privacy', 'bp_profile_privacy_sanitize_settings' );

	add_submenu_page( 'bp-general-settings', __('Profile Privacy Setup', PPL_TD), __('Profile Privacy Setup', PPL_TD), 'manage_options', 'bp-profile-privacy', 'bp_profile_privacy_admin' );
}
add_action( 'admin_menu', 'bp_profile_privacy_add_admin_menu' );
add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', 'bp_profile_privacy_add_admin_menu');

function bp_profile_privacy_xprofile_group_fields( $fields, $group_id ) {
	global $bp;
	if ( 'profile' == $bp->current_component && 'public' == $bp->current_action && !current_user_can( 'edit_users' ) ) {
		// 0 = Everyone, 1 = Friends, 2 = User, 3 = Let User Decide
		$admin_settings = (array) get_option( 'bp_profile_privacy' );
		// 0 = Everyone, 1 = Friends, 2 = User
		$user_settings = (array) get_usermeta( $bp->displayed_user->id, 'bp_profile_privacy' );
		foreach ( (array) $fields as $key => $field ) {
			$check_against = ( isset( $admin_settings[$field->id] ) && 3 == $admin_settings[$field->id] ) ? $user_settings : $admin_settings;
			if ( isset( $check_against[$field->id] ) ) {
				if ( 0 == $check_against[$field->id] ) {
					continue;
				} elseif ( 1 == $check_against[$field->id] ) {
					if ( function_exists( 'friends_check_friendship' ) ) {
						if ( friends_check_friendship( $bp->displayed_user->id, $bp->loggedin_user->id ) || bp_is_my_profile() )
							continue;
					}
				} elseif ( 2 == $check_against[$field->id] ) {
					if ( bp_is_my_profile() )
						continue;
				}
				unset( $fields[$key]->data );
			}
		}
	}
	return $fields;
}
add_filter( 'xprofile_group_fields', 'bp_profile_privacy_xprofile_group_fields', 10, 2 );

function bp_profile_privacy_custom_profile_edit_fields() {
	global $bp, $field;
	if ( 'profile' == $bp->current_component && 'edit' == $bp->current_action ) {
		// 0 = Everyone, 1 = Friends, 2 = User, 3 = Let User Decide
		$admin_settings = (array) get_option( 'bp_profile_privacy' );
		// 0 = Everyone, 1 = Friends, 2 = User
		$user_settings = (array) get_usermeta( $bp->displayed_user->id, 'bp_profile_privacy' );
		if ( isset( $admin_settings[$field->id] ) && 3 == $admin_settings[$field->id] ) {
			$label = apply_filters( 'bp_profile_privacy_label', sprintf( __( 'Who can see "%s"?', PPL_TD ), $field->name ) );
		?>
<div class="bp-profile-privacy">
<?php if ( $label ) echo '<span class="label">' . $label . '</span>'; ?>
<select name="bp_profile_privacy[<?php echo $field->id; ?>]" id="select_field-<?php echo $field->id; ?>">
	<?php $options = array( 0 => __( 'Everyone', PPL_TD ), 1 => __( 'My Friends', PPL_TD  ), 2 => __( 'Only Me', PPL_TD ) );
	foreach ( $options as $value => $label ) {
		$selected = ( isset( $user_settings[$field->id] ) && $value == $user_settings[$field->id] ) ? ' selected="selected"' : '';
		echo "\n<option value='$value'$selected>$label</option>";
	} ?>
</select>
</div>
<?php
		}
	}
}
add_action( 'bp_custom_profile_edit_fields', 'bp_profile_privacy_custom_profile_edit_fields' );

function bp_profile_privacy_updated_profile($posted_field_ids) {
	global $bp;
	$user_settings = (array) get_usermeta( $bp->loggedin_user->id, 'bp_profile_privacy' );
	if ( isset( $_POST['bp_profile_privacy'] ) ) {
		foreach ( $_POST['bp_profile_privacy'] as $field_id => $value ) {
			$user_settings[$field_id] = absint( $value );
		}
		update_usermeta( $bp->loggedin_user->id, 'bp_profile_privacy', $user_settings );
	}
}
add_action( 'xprofile_updated_profile', 'bp_profile_privacy_updated_profile' );

?>