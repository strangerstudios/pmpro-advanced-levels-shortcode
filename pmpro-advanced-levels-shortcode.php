<?php
/*
Plugin Name: Paid Memberships Pro - Advanced Levels Page Shortcode Add On
Plugin URI: https://www.paidmembershipspro.com/add-ons/pmpro-advanced-levels-shortcode/
Description: An enhanced shortcode for customizing the display of your Membership Levels Page for Paid Memberships Pro
Version: 0.2.6
Author: Paid Memberships Pro
Author URI: https://www.paidmembershipspro.com/
Text Domain: pmpro-advanced-levels-shortcode
Domain Path: /languages
*/

define( 'PMPRO_ADVANCED_LEVELS_DIR', dirname( __FILE__ ) );

global $pmproal_link_arguments;
$pmproal_link_arguments = array();

// Include required files.
require_once( PMPRO_ADVANCED_LEVELS_DIR . '/templates/levels.php' );

/**
 * Register the Advanced Levels Shortcode styles.
 */
function pmpro_advanced_levels_register_styles() {
	wp_register_style( 'pmpro-advanced-levels-styles', plugins_url( 'css/pmpro-advanced-levels.css', __FILE__ ) );
	wp_enqueue_style( 'pmpro-advanced-levels-styles' );
}
add_action( 'wp_enqueue_scripts', 'pmpro_advanced_levels_register_styles' );
add_action( 'enqueue_block_editor_assets', 'pmpro_advanced_levels_register_styles' );

function pmproal_load_textdomain()
{
	//get the locale
	$locale = apply_filters("plugin_locale", get_locale(), "pmpro-advanced-levels-shortcode");
	$mofile = "pmpro-advanced-levels-shortcode-" . $locale . ".mo";

	//paths to local (plugin) and global (WP) language files
	$mofile_local  = plugin_dir_path(__FILE__)."/languages/" . $mofile;
	$mofile_global = WP_LANG_DIR . '/pmpro/' . $mofile;

	//load global first
	load_textdomain("pmpro-advanced-levels-shortcode", $mofile_global);

	//load local second
	load_textdomain("pmpro-advanced-levels-shortcode", $mofile_local);
}
add_action("init", "pmproal_load_textdomain", 1);

function pmproal_getLevelLandingPage($level_id) {
	if(is_object($level_id))
		$level_id = $level_id->id;

	$args = array(
		'post_type' => apply_filters('pmproal_level_landing_page_post_types', array('page', 'post')),
		'meta_query' => array(
			array(
				'key' => '_pmproal_landing_page_level',
				'value' => $level_id,
			)
		)
	);

	$posts = get_posts($args);

	if(empty($posts))
		return false;
	else
		return $posts[0];
}

/**
 * Function for allowed HTML tags in various templates
 * 
 * @since TBD
 * @return array $allowed_html The allowed HTML to be used for wp_kses escaping.
 */
function pmproal_allowed_html() {
	$allowed_html = array (
		'a' => array (
			'class' => array(),
			'href' => array(),
			'target' => array(),
			'title' => array(),
		),
		'p' => array(
			'class' => array(),
		),
		'b' => array(
			'class' => array(),
		),
		'em' => array(
			'class' => array(),
		),
		'br' => array(),
		'strike' => array(),
		'strong' => array(),
	);

	/**
	 * Filters the allowed HTML tags for the Advanced Levels Shortcode.
	 * @param array $allowed_html The allowed html elements for the Advanced Levels Shortcode escaping where wp_kses is used (like in compared elements etc.)
	 * @since TBD
	 */
	return apply_filters( 'pmproal_allowed_html', $allowed_html );
}

/**
 * Function to get the level button.
 *
 * @since TBD
 * @param array $pmproal_link_arguments The arguments to be added to the checkout URL.
 * @param object $current_level The user's current level object.
 * @param string $checkout_button The text for the checkout button from shortcode or block atts.
 * @param string $renew_button The text for the renew button from shortcode or block atts.
 * @param string $account_button The text for the account button from shortcode or block atts.
 */
function pmproal_level_button( $level, $pmproal_link_arguments, $current_level, $checkout_button, $renew_button, $account_button ) {
	global $current_user;

	// Set up the button classes.
	$button_classes = array();
	$button_classes[] = 'pmpro_btn';

	if ( ! pmpro_hasMembershipLevel() || ! $current_level ) {
		// Show checkout button if the user has no membership level or $current_level is false
		$button_classes[] = 'pmpro_btn-select';
		$button_link = add_query_arg( $pmproal_link_arguments, pmpro_url( 'checkout', '', 'https' ) );
		$button_text = $checkout_button;
	} elseif( $current_level ) {
		// Get specific level details for the user
		$specific_level = pmpro_getSpecificMembershipLevelForUser( $current_user->ID, $level->id );
		if ( pmpro_isLevelExpiringSoon( $specific_level ) && $specific_level->allow_signups ) {
			// Show renew button if the level is expiring soon and signups are allowed
			$button_classes[] = 'pmpro_btn-select';
			$button_classes[] = 'pmpro_btn-renew';
			$button_link = add_query_arg( $pmproal_link_arguments, pmpro_url( 'checkout', '', 'https' ) );
			$button_text = $renew_button;
		} else {
			// Show account button otherwise
			$button_classes[] = 'disabled';
			$button_link = pmpro_url( 'account' );
			$button_text = $account_button;
		}
	}

	// Output the button.
	?>
	<a class="<?php echo esc_attr( implode( ' ', array_unique( $button_classes ) ) ); ?>" href="<?php echo esc_url( $button_link ); ?>"><?php echo esc_html( $button_text ); ?></a>
	<?php
}


/**
 * Register block types for the block editor.
 *
 * @since TBD
 */
function pmpro_advanced_levels_register_block_types() {
	register_block_type( __DIR__ . '/blocks/build/advanced-levels-page' );
}
add_action( 'init', 'pmpro_advanced_levels_register_block_types' );

/**
 * Function to add links to the plugin row meta
 */
function pmpro_advanced_levels_plugin_row_meta($links, $file) {
	if(strpos($file, 'pmpro-advanced-levels-shortcode.php') !== false)
	{
		$new_links = array(
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/add-ons/pmpro-advanced-levels-shortcode/' )  . '" title="' . esc_attr__( 'View Documentation', 'pmpro-advanced-levels-shortcode' ) . '">' . esc_html__( 'Docs', 'pmpro-advanced-levels-shortcode' ) . '</a>',
			'<a href="' . esc_url( 'https://www.paidmembershipspro.com/support/') . '" title="' . esc_attr__( 'Visit Customer Support Forum', 'pmpro-advanced-levels-shortcode' ) . '">' . esc_html__( 'Support', 'pmpro-advanced-levels-shortcode' ) . '</a>',
		);
		$links = array_merge($links, $new_links);
	}
	return $links;
}
add_filter('plugin_row_meta', 'pmpro_advanced_levels_plugin_row_meta', 10, 2);
