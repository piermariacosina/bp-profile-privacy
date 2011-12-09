=== BP Profile Privacy ===
Author: jfarthing84
Contributors: Piermaria Cosina, Riccardo Strobbia
Donate link: http://www.jfarthing.com/donate
Tags: buddypress, profile, privacy, permissions
Requires at least: WordPress 2.8, BuddyPress 1.2.1
Tested up to: WordPress 3.2.1 / BuddyPress 1.5
Stable tag: trunk

******** this is a transitional fix, please check the functionality before implementing into a production site  ******

Allows "permissions" to be set for xprofile fields. Permissions set by site admin include "Everyone", "Friends", "User", "Let User Decide". If "Let user Decide" is chosen, the user can choose from "Everyone", "My Friends" and "Only Me".

== Description ==

Allows "permissions" to be set for xprofile fields.

= Features =
* Site admin can select a permission for each field from "Everyone", "Friends", "User" or "Let User Decide"
* User can select a permission for each field allowed by the site admin from "Everyone", "My Friends" or "Only Me"


== Installation ==

1. Upload the plugin to your 'wp-content/plugins' directory
2. Activate the plugin
3. under buddy press tab there is a new tab Profile Privacy Setup 

N.B
If you are the admin you will always see all the users infos

== Frequently Asked Questions ==

None yet. Please visit http://www.jfarthing.com/forum for any support!


== Changelog ==
= 0.5=
* Update to work with BP 1.5 this is a transitional fix, please check the functionality before implementing into a production site 
= 0.4 =
* Better translation support

= 0.3 =
* Implemented the support for multisite

= 0.2 =
* Users with cap of 'edit_users' can view all fields of all users
* Add "Logged In users" option to permissions
* Change permissions array to associative array

= 0.1 =
* Initial commit