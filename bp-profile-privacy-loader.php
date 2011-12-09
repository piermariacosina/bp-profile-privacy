<?php
/*
Plugin Name: BuddyPress Profile Privacy
Plugin URI: http://www.jfarthing.com/extend/plugins/bp-profile-privacy
Description: Allows "permissions" to be set for xprofile fields.
Version: 0.4
Requires at least: WP 2.8, BuddyPress 1.2.1
Tested up to: WP 3.2.1, BuddyPress 1.2.9
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: Jeff Farthing
Contributor: Piermaria Cosina, Riccardo Strobbia
Author URI: http://www.jfarthing.com
Network: true
*/

/* Define a constant that can be checked to see if the component is installed or not. */
define ( 'BP_PROFILE_PRIVACY_IS_INSTALLED', 1 );

/* Define a constant that will hold the current version number of the component */
define ( 'BP_PROFILE_PRIVACY_VERSION', '1.0' );


define('PPL_PATH', WP_PLUGIN_DIR . '/bp-profile-privacy/');
define('PPL_ABS_PATH', get_bloginfo('url').'/wp-content/plugins/bp-profile-privacy');
define('PPL_TD', 'profile-privacy');


if ( !defined( 'BP_PROFILE_PRIVACY_SLUG' ) )
	define ( 'BP_PROFILE_PRIVACY_SLUG', 'profile-privacy' );
	
load_plugin_textdomain( PPL_TD, false, dirname( plugin_basename( __FILE__ ) ).'/languages/' );


/* Only load the component if BuddyPress is loaded and initialized. */
function bp_profile_privacy_init() {
	// Check if xprofile is active
//	if ( !function_exists( 'xprofile_install' ) )
//		return;
//		
//	if ( !$admin_settings = get_option( 'bp_profile_privacy' ) )
//		bp_profile_privacy_install();

	require( dirname( __FILE__ ) . '/bp-profile-privacy-core.php' );
}
add_action( 'bp_init', 'bp_profile_privacy_init' );

function bp_profile_privacy_install() {
	// Check if xprofile is active
	if ( !function_exists( 'xprofile_install' ) )
		return false;
		
	if ( $admin_settings = get_option( 'bp_profile_privacy' ) )
		return true;
		
	$groups = BP_XProfile_Group::get( array( 'fetch_fields' => true ) );
	
	$fields = array();
	foreach ( $groups as $group ) {
		if ( isset( $group->fields ) && is_array( $group->fields ) ) {
			foreach ( $group->fields as $field ) {
				$fields[$field->id] = 0;
			}
		}
	}
	
	return update_option( 'bp_profile_privacy', $fields );
}
register_activation_hook( __FILE__, 'bp_profile_privacy_install' );

function bp_profile_privacy_uninstall() {
	delete_option( 'bp_profile_privacy' );
}
register_uninstall_hook( __FILE__, 'bp_profile_privacy_uninstall' );

?>