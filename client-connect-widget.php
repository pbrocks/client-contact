<?php
/**
 * Plugin Name: Client Connect Widget
 * Plugin URI: http://pbrx.com/plugins/
 * Description: Demonstrates how to add a simple dashboard widget
 * Version: 0.2
 * Author: pbrocks
 * Author URI: http://pbrx.com/
 * License: GPLv2 or later
 http://www.finalwebsites.com/ajax-contact-form-wordpress/
 */

require_once( 'inc/classes/class-client-connect.php' );


// add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'my_plugin_action_links' );
function my_plugin_action_links( $links ) {
	$links[] = '<a href="http://paul.barthmaier.rocks/plugins" target="_blank">PBrocks Plugins</a>';
	$links[] = '<a href="http://paul.barthmaier.rocks/plugins" target="_blank">More plugins by PBrocks</a>';
	return $links;
}

add_filter( 'plugin_row_meta', 'custom_plugin_row_meta', 10, 2 );

function custom_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'pbrx-dashboard-widget.php' ) !== false ) {
		$new_links = array(
				'donate' => '<a href="donation_url" target="_blank">Donate</a>',
								'doc' => '<a href="doc_url" target="_blank">Documentation</a>',
				);

		$links = array_merge( $links, $new_links );
	}

	return $links;
}

function remove_quickpress_dashboard_widget() {
	remove_meta_box( 'dashboard_browser_nag', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
}

// add_action('wp_dashboard_setup', 'remove_quickpress_dashboard_widget' );
// remove_action( 'welcome_panel', 'wp_welcome_panel' );
