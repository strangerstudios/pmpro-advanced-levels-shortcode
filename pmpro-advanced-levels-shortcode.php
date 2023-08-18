<?php
/*
Plugin Name: Paid Memberships Pro - Advanced Levels Page Shortcode Add On
Plugin URI: https://www.paidmembershipspro.com/add-ons/pmpro-advanced-levels-shortcode/
Description: An enhanced shortcode for customizing the display of your Membership Levels Page for Paid Memberships Pro
Version: 0.2.5
Author: Paid Memberships Pro
Author URI: https://www.paidmembershipspro.com/
Text Domain: pmpro-advanced-levels-shortcode
Domain Path: /languages
*/

global $pmproal_link_arguments;
$pmproal_link_arguments = array();

$path = dirname(__FILE__);
require_once($path . "/templates/levels.php");


function pmpro_advanced_levels_register_styles() {
	wp_register_style( 'pmpro-advanced-levels-styles', plugins_url( 'css/pmpro-advanced-levels.css', __FILE__ ) );
	wp_enqueue_style( 'pmpro-advanced-levels-styles' );
}
add_action( 'wp_enqueue_scripts', 'pmpro_advanced_levels_register_styles' );

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

/*
Function to add links to the plugin row meta
*/
function pmpro_advanced_levels_plugin_row_meta($links, $file) {
	if(strpos($file, 'pmpro-advanced-levels-shortcode.php') !== false)
	{
		$new_links = array(
			'<a href="' . esc_url('http://www.paidmembershipspro.com/add-ons/plus-add-ons/pmpro-advanced-levels-shortcode/')  . '" title="' . esc_attr__( 'View Documentation', 'pmpro-advanced-levels-shortcode' ) . '">' . esc_html__( 'Docs', 'pmpro-advanced-levels-shortcode' ) . '</a>',
			'<a href="' . esc_url('http://paidmembershipspro.com/support/') . '" title="' . esc_attr__( 'Visit Customer Support Forum', 'pmpro-advanced-levels-shortcode' ) . '">' . esc_html__( 'Support', 'pmpro-advanced-levels-shortcode' ) . '</a>',
		);
		$links = array_merge($links, $new_links);
	}
	return $links;
}
add_filter('plugin_row_meta', 'pmpro_advanced_levels_plugin_row_meta', 10, 2);

/**
 * Create the block for the Advanced Levels Shortcode. Call register_block_type() to register the block.
 * 
 * @since TBD
 */
function create_block_advanced_level_page_block_init() {
	register_block_type( __DIR__ . '/build/pmpro-advanced-level-page',array (
		'attributes' => array(
			'back_link'=>array('type'=>'boolean', 'default' => true),
			'more_button'=>array('type'=>'boolean', 'default' => false), 
			'checkout_button'=>array('type'=>'string', 'default' => 'Select'), 
			'description'=>array('type'=>'boolean', 'default' => true), 
			'discount_code'=>array('type'=>'string', 'default' => ''), 
			'expiration'=>array('type'=>'boolean', 'default' => true), 
			'levels'=>array('type'=>'array','default'=>[]), 
			'layout'=>array('type'=>'string', 'default' => 'div'), 
			'price'=>array('type'=>'string', 'default' => 'short'), 
			'renew_button'=>array('type'=>'string', 'default' => 'Renew'),
		  	'template'=>array('type'=>'boolean', 'default' => 'none')),
			'compare'=>array('type'=>'string', 'default' => ''),
		'render_callback' => 'pmpro_advanced_level_shortcode') );

	add_shortcode( 'pmpro_advanced_levels', 'pmpro_advanced_level_shortcode' );
	add_shortcode( 'ppmpro_advanced_level', 'pmpro_advanced_level_shortcode' );
}
add_action( 'init', 'create_block_advanced_level_page_block_init' );

/**
 * Advanced Levels Shortcode block render callback. Get the attributes and call the function than render the shortcode.
 *
 * @atts array The attributes of the registered block.
 * @since TBD.
 */
function pmpro_advanced_level_shortcode( $atts) {
	require_once 'templates' . DIRECTORY_SEPARATOR . 'levels.php';
 	return pmpro_advanced_levels_shortcode( $atts );
} 
