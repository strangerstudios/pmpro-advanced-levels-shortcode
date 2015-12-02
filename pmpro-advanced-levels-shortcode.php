<?php
/*
Plugin Name: Paid Memberships Pro - Advanced Levels Page Shortcode Add On
Plugin URI: http://www.paidmembershipspro.com/wp/pmpro-advanced-levels/
Description: An enhanced shortcode for customizing the display of your Membership Levels Page for Paid Memberships Pro
Version: .1.8
Author: Stranger Studios
Author URI: http://www.strangerstudios.com
*/

$path = dirname(__FILE__);
require_once($path . "/templates/levels.php");

function pmpro_advanced_levels_register_styles() {
	wp_register_style( 'pmpro-advanced-levels-styles', plugins_url( 'css/pmpro-advanced-levels.css', __FILE__ ) );
	wp_enqueue_style( 'pmpro-advanced-levels-styles' );
}
add_action( 'wp_enqueue_scripts', 'pmpro_advanced_levels_register_styles' );


/*
Function to add links to the plugin row meta
*/
function pmpro_advanced_levels_plugin_row_meta($links, $file) {
	if(strpos($file, 'pmpro-advanced-levels-shortcode.php') !== false)
	{
		$new_links = array(
			'<a href="' . esc_url('http://www.paidmembershipspro.com/add-ons/plus-add-ons/pmpro-advanced-levels-shortcode/')  . '" title="' . esc_attr( __( 'View Documentation', 'pmpro' ) ) . '">' . __( 'Docs', 'pmpro' ) . '</a>',
			'<a href="' . esc_url('http://paidmembershipspro.com/support/') . '" title="' . esc_attr( __( 'Visit Customer Support Forum', 'pmpro' ) ) . '">' . __( 'Support', 'pmpro' ) . '</a>',
		);
		$links = array_merge($links, $new_links);
	}
	return $links;
}
add_filter('plugin_row_meta', 'pmpro_advanced_levels_plugin_row_meta', 10, 2);