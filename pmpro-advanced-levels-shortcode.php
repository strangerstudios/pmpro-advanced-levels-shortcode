<?php
/*
Plugin Name: Paid Memberships Pro - Advanced Levels Page Shortcode Add On
Plugin URI: https://www.paidmembershipspro.com/add-ons/pmpro-advanced-levels-shortcode/
Description: An enhanced shortcode for customizing the display of your Membership Levels Page for Paid Memberships Pro
Version: .2.4
Author: Paid Memberships Pro
Author URI: https://www.paidmembershipspro.com
Text Domain: pmpro-advanced-levels-shortcode
Domain Path: /languages
*/

global $pmproal_link_arguments;
$pmproal_link_arguments = array();

$path = dirname(__FILE__);
require_once($path . "/templates/levels.php");

/**
 * Enqueue a stylesheet.
 *
 */
function pmpro_advanced_levels_register_styles() {
	wp_register_style( 'pmpro-advanced-levels-styles', plugins_url( 'css/pmpro-advanced-levels.css', __FILE__ ) );
	wp_enqueue_style( 'pmpro-advanced-levels-styles' );
}
add_action( 'wp_enqueue_scripts', 'pmpro_advanced_levels_register_styles' );

function pmproal_load_textdomain() {
	//get the locale
	$locale = apply_filters( 'plugin_locale', get_locale(), 'pmpro-advanced-levels-shortcode' );
	$mofile = 'pmpro-advanced-levels-shortcode-' . $locale . '.mo';

	//paths to local (plugin) and global (WP) language files
	$mofile_local  = plugin_dir_path( __FILE__ ) . '/languages/' . $mofile;
	$mofile_global = WP_LANG_DIR . '/pmpro/' . $mofile;

	//load global first
	load_textdomain( 'pmpro-advanced-levels-shortcode', $mofile_global );

	//load local second
	load_textdomain( 'pmpro-advanced-levels-shortcode', $mofile_local );
}
add_action( 'init', 'pmproal_load_textdomain', 1 );

function pmproal_getLevelLandingPage( $level_id ) {
	if ( is_object( $level_id ) ) {
		$level_id = $level_id->id;
	}

	$args = array(
		'post_type' => apply_filters( 'pmproal_level_landing_page_post_types', array( 'page', 'post' ) ),
		'meta_query' => array(
			array(
				'key' => '_pmproal_landing_page_level',
				'value' => $level_id,
			)
		)
	);

	$posts = get_posts( $args );

	if ( empty( $posts ) ) {
		return false;
	} else {
		return $posts[0];
	}
}

/**
 * Get the class for the wrapping levels output div.
 *
 * @param string layout Optional layout passed in shortcode attributes.
 * @param string template Optional template passed in shortcode attributes.
 * @return string
 *
 */
function pmpro_advanced_levels_wrapper_class( $layout, $template ) {

	// The return variable
	$r = '';

	// Get the class name for the chosen template, if available.
	if ( ! empty( $template ) ) {
		$r .= ' pmpro_advanced_levels-' . $template;
	}

	// Add the class for the chosen layout, if available.
	if ( ! empty( $layout ) ) {
		if ( $layout == 'table' ) {
			$r .= ' pmpro_advanced_levels-table';
			if ( $template === 'gantry' || $template === 'bootstrap' ) {
				$r .= ' table table-striped table-bordered';
			}
		}

		$r .= ' pmpro_levels-' . $layout;
	}

	/**
	 * Allow custom code to filter the levels output class.
	 *
	 * @param string layout Optional layout passed in shortcode attributes.
	 * @param string template Optional template passed in shortcode attributes.
	 *
	 */
	$r = apply_filters( 'pmpro_advanced_levels_wrapper_class', $r, $layout, $template );

	return $r;
}

/**
 * Get the class for the individual level item output.
 *
 * @param int level_id The ID of the level displayed.
 * @param string layout Optional layout passed in shortcode attributes.
 * @param string template Optional template passed in shortcode attributes.
 * @return string
 *
 */
function pmpro_advanced_levels_level_class( $level_id, $layout, $template ) {

	// The return variable.
	$r = 'pmpro_level';
	
	/**
	 * Allow custom code to filter the level output class.
	 *
	 * @param string layout Optional layout passed in shortcode attributes.
	 * @param string template Optional template passed in shortcode attributes.
	 *
	 */
	$r = apply_filters( 'pmpro_advanced_levels_level_class', $r, $level_id, $layout, $template );

	return $r;
}

/**
 * Get the class for the inner div for each level item output.
 *
 * @param int level_id The ID of the level displayed.
 * @param string layout Optional layout passed in shortcode attributes.
 * @param string template Optional template passed in shortcode attributes.
 * @param object current_level The current user's level object if set.
 * @param int highlight The ID of the level to highlight if set.
 * @return string
 *
 */
function pmpro_advanced_levels_level_inner_class( $level_id, $layout, $template, $current_level, $highlight ) {

	// The return variable
	$r = '';

	if ( ! in_array( $layout, array( 'table', 'compare_table' ) ) ) {
		$r .= ' entry post';
	}

	if ( $level_id == $current_level ) {
		$r .= ' pmpro_level-current';
	}

	if ( $level_id == $highlight ) {
		$r .= ' pmpro_level-highlight';
	}

	/**
	 * Allow custom code to filter the level output class.
	 *
	 * @param int level_id The ID of the level displayed.
	 * @param string layout Optional layout passed in shortcode attributes.
	 * @param string template Optional template passed in shortcode attributes.
	 * @param int current_level The current user's level ID if set.
	 * @param int highlight The ID of the level to highlight if set.
	 *
	 */
	$r = apply_filters( 'pmpro_advanced_levels_level_inner_class', $r, $level_id, $layout, $template, $current_level, $highlight );

	return $r;
}

/**
 * Get the class for the inner div for each level item output.
 *
 * @param int level_id The ID of the level displayed.
 * @param string layout Optional layout passed in shortcode attributes.
 * @param string template Optional template passed in shortcode attributes.
 * @param int current_level The current user's level ID if set.
 * @return string
 */
function pmpro_advanced_levels_level_button_class( $level_id, $layout, $template, $current_level ) {

	// The return variable.
	$r = 'pmpro_level_btn';

	if ( in_array( $template, array( 'genesis', 'twentyfourteen', 'foundation' ) ) ) {
		$r .= ' button';
	} elseif ( in_array( $template, array( 'gantry', 'bootstrap' ) ) ) {
		$r .= ' btn btn-primary';
	} elseif ( $template === 'woothemes') { 
		$r .= ' woo-sc-button custom';
	} else {
		$r .= ' pmpro_btn pmpro_btn-select';
	}

	// Show a disabled/greyed button that links to Account page.
	if ( ! empty( $current_level ) && $level_id == $current_level->id ) {
		// If it's a one-time-payment level or recurring level that's expiring soon, offer a link to renew.
		if ( pmpro_isLevelExpiringSoon( $current_user->membership_level ) && $current_user->membership_level->allow_signups ) {
			$r .= ' pmpro_btn-renew';
		} else {
			$r .= ' pmpro_btn-disabled';
		}
	}

	/**
	 * Allow custom code to filter the level output class.
	 *
	 * @param int level_id The ID of the level displayed.
	 * @param string layout Optional layout passed in shortcode attributes.
	 * @param string template Optional template passed in shortcode attributes.
	 * @param int current_level The current user's level ID if set.
	 *
	 */
	$r = apply_filters( 'pmpro_advanced_levels_level_button_class', $r, $level_id, $layout, $template, $current_level );

	return $r;
}

/**
 * Get the button text for the checkout, renew, or account link for each level item output.
 *
 * @param int level_id The ID of the level displayed.
 * @param object current_level The current user's level object if set.
 * @param string checkout_button Text for checkout button passed in shortcode attributes.
 * @param string renew_button Text for renew button passed in shortcode attributes.
 * @param string account_button Text for account button passed in shortcode attributes.
 * @return string
 */
function pmpro_advanced_levels_level_button_text( $level_id, $current_level, $checkout_button, $renew_button, $account_button ) {

	// The return variable.
	$r = '';

	if ( ! empty( $current_level ) && $level_id == $current_level->id ) {
		// If it's a one-time-payment level, offer a link to renew.
		if ( ! pmpro_isLevelRecurring( $current_level ) && ! empty( $current_user->membership_level->enddate ) ) {
			$r = $renew_button;
		} else {
			$r = $account_button;
		}
	} else {
		$r = $checkout_button;
	}

	/**
	 * Allow custom code to filter the level button text.
	 *
	 * @param int level_id The ID of the level displayed.
	 * @param object current_level The current user's level object if set.
	 * @param string checkout_button Text for checkout button passed in shortcode attributes.
	 * @param string renew_button Text for renew button passed in shortcode attributes.
	 * @param string account_button Text for account button passed in shortcode attributes.
	 *
	 */
	$r = apply_filters( 'pmpro_advanced_levels_level_button_text', $r, $level_id, $current_level, $checkout_button, $renew_button, $account_button );

	return $r;
}

/**
 * Build the href for the checkout, renew, or account link for each level item output.
 *
 * @param int level_id The ID of the level displayed.
 * @param object current_level The current user's level object if set.
 * @return string
 */
function pmpro_advanced_levels_level_button_link( $pmproal_link_arguments, $level_id, $current_level ) {

	// The return variable.
	$r = '';

	if ( ! empty( $current_level ) && $level_id == $current_level->id ) {
		// If it's a one-time-payment level, offer a link to renew.
		if ( ! pmpro_isLevelRecurring( $current_level ) && ! empty( $current_user->membership_level->enddate ) ) {
			$r = add_query_arg( $pmproal_link_arguments, pmpro_url( 'checkout', null, 'https' ) );
		} else {
			$r = add_query_arg( $pmproal_link_arguments, pmpro_url( 'account', null, 'https' ) );
		}
	} else {
		$r = add_query_arg( $pmproal_link_arguments, pmpro_url( 'checkout', null, 'https' ) );
	}

	/**
	 * Allow custom code to filter the level href.
	 *
	 * @param int level_id The ID of the level displayed.
	 * @param object current_level The current user's level object if set.
	 *
	 */
	$r = apply_filters( 'pmpro_advanced_levels_level_button_link', $r, $pmproal_link_arguments, $level_id, $current_level );

	return $r;
}

/**
 * Function to add links to the plugin row meta
 *
 */
function pmpro_advanced_levels_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'pmpro-advanced-levels-shortcode.php' ) !== false ) {
		$new_links = array(
			'<a href="' . esc_url('https://www.paidmembershipspro.com/add-ons/pmpro-advanced-levels-shortcode/')  . '" title="' . esc_attr( __( 'View Documentation', 'pmpro-advanced-levels-shortcode' ) ) . '">' . __( 'Docs', 'pmpro-advanced-levels-shortcode' ) . '</a>',
			'<a href="' . esc_url('https://paidmembershipspro.com/support/') . '" title="' . esc_attr( __( 'Visit Customer Support Forum', 'pmpro-advanced-levels-shortcode' ) ) . '">' . __( 'Support', 'pmpro-advanced-levels-shortcode' ) . '</a>',
		);
		$links = array_merge($links, $new_links);
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'pmpro_advanced_levels_plugin_row_meta', 10, 2 );
