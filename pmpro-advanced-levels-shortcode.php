<?php
/*
Plugin Name: PMPro Advanced Levels Page Shortcode
Plugin URI: http://www.paidmembershipspro.com/wp/pmpro-advanced-levels/
Description: An enhanced shortcode for customizing the display of your Membership Levels Page for Paid Memberships Pro
Version: .1
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