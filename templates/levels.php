<?php
/**
 * This shortcode displays the membership levels and additional content based on the defined attributes.
 */
function pmpro_advanced_levels_shortcode($atts, $content=null, $code="") {
	// $atts    ::= array of attributes
	// $content ::= text within enclosing form of shortcode element
	// $code    ::= the shortcode found, when == callback name
	// examples: [pmpro_advanced_levels template="genesis" levels="1,2,3" layout="table" hightlight="2" description="false" checkout_button="Register Now"]
	
	extract(shortcode_atts(array(
		'account_button' => __('Your&nbsp;Level', 'pmpro-advanced-levels-shortcode'),
		'back_link' => '1',
		'compare' => NULL,
		'checkout_button' => __('Select', 'pmpro-advanced-levels-shortcode'),
		'description' => '1',
		'discount_code' => NULL,
		'expiration' => '1',
		'highlight' => NULL,
		'layout' => 'div',
		'levels' => NULL,
		'more_button' => NULL,
		'price' => 'short',
		'renew_button' => __('Renew', 'pmpro-advanced-levels-shortcode'),
		'template' => NULL,
	), $atts));

	global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user, $pmpro_currency_symbol, $pmpro_all_levels, $pmpro_visible_levels, $current_user, $membership_levels;
	
	if ( $back_link === "0" || $back_link === "false" || $back_link === "no"  || ! $back_link )
		$back_link = false;
	else
		$back_link = true;

	if ( $compare === "0" || $compare === "false" || $compare === "no" || empty( $compare ) ) {
		$compare = false;
	} else {
		$compare = rtrim( $compare, ';' ); // clear up a stray ; at the end.
		$compareitems = explode( ";", $compare );
	}

	if ( $description === "0" || $description === "false" || $description === "no" || ! $description ) {
		$description = false;
	} else {
		$description = true;
	}

	if ( $expiration === "0" || $expiration === "false" || $expiration === "no" || ! $expiration ) {
		$expiration = false;
	} else {
		$expiration = true;
	}

	if ( $more_button === "0" || $more_button === "false" || $more_button === "no" || empty($more_button) || ! $more_button ) {
		$more_button = false;
	} elseif ( $more_button === "1" || $more_button === "true" || $more_button === "yes" || $more_button == true ) {
		$more_button = __( "Read More", "pmpro-advanced-levels-shortcode" );
	}

	if ( $price === "0" || $price === "false" || $price === "hide" ) {
		$show_price = false;
	} else {
		$show_price = true;	
	}

	ob_start();

	//make sure pmpro_levels has all levels
	if ( ! isset( $pmpro_all_levels ) ) {
		$pmpro_all_levels = pmpro_getAllLevels( false, true );
	}
	
	$pmpro_levels_filtered = array();
	if ( ! empty( $levels ) ) {
		// Generate level data for SQL query.
		if ( is_array( $levels ) ) {
			// We need to ensure backwards compatibility with the old way of passing levels.
			// Select2 passes a multidimensional array but it used to be single dimension array.
			$levels = count( $levels ) == count( $levels, COUNT_RECURSIVE )
			? implode( ',', $levels )
			: implode( ',', wp_list_pluck( $levels, 'value' ) );
		}

		// Clean up the $levels_order to prevent false positives and ensure data integrity.
		$levels_order = explode( ',', $levels );
		$levels_order = array_map( 'trim', $levels_order );
		$levels_order = array_filter( $levels_order );

		//loop through $levels_order array and pull levels from $levels
		foreach($levels_order as $level_id) {
			foreach($pmpro_all_levels as $level) {
				if($level->id == $level_id && true == $level->allow_signups) {
					$pmpro_levels_filtered[$level->id] = $level;
					break;
				}
			}
		}
	} else {
		$pmpro_level_order = get_option( 'pmpro_level_order' ) ?: $pmpro_all_levels;

		if ( ! is_array( $pmpro_level_order ) ) {
			$levels_order = explode( ',', $pmpro_level_order );
		} else {
			$levels_order = array_keys( $pmpro_level_order );
		}

		// Reorder array and remove levels that don't exist or have signup disabled.
		foreach ( $levels_order as $level_id ) {
			foreach ( $pmpro_all_levels as $key => $level ) {
				if ( $level_id == $level->id && true == $level->allow_signups) {
					$pmpro_levels_filtered[] = $pmpro_all_levels[$key];
				}
			}
		}
	}

	// Check if we have any specified level IDs that aren't shown and set a message for admins.
	if ( ! empty( $levels ) ) {
		$pmpro_levels_filtered_not_shown = array_diff( $levels_order, wp_list_pluck( $pmpro_levels_filtered, 'id' ) );
		if ( ! empty( $pmpro_levels_filtered_not_shown ) && current_user_can( 'manage_options' ) ) {
			// Make sure no level IDs are duplicated.
			$pmpro_levels_filtered_not_shown = array_unique( $pmpro_levels_filtered_not_shown );

			// Create a message to display to admins.
			pmpro_setMessage(
				sprintf(
					esc_html__( 'Admin-only message: The following level IDs are not shown because they do not exist or signup is disabled: %s', 'pmpro-advanced-levels-shortcode' ),
					implode( ', ', $pmpro_levels_filtered_not_shown )
				),
				'pmpro_error'
			);
		}
	}

	$pmpro_levels_filtered = apply_filters("pmpro_levels_array", $pmpro_levels_filtered);
	$numeric_levels_array = array_values($pmpro_levels_filtered);

	// Allows you to add ?discount_code=code to your URL. Shortcode attribute overrides URL parameter.
	if ( empty( $discount_code ) && ! empty( $_REQUEST['discount_code'] ) ) {
		$discount_code = sanitize_text_field( $_REQUEST['discount_code'] );
	}

	// Set up the link arguments and level objects to price.
	foreach ( $pmpro_levels_filtered as $level ) {
		// Set some defaults on the object.
		$level->discounted_level = null;
		$level->current_level = false;

		// Set the level ID in the link arguments.
		$level->link_arguments = array();
		$level->link_arguments['level'] = $level->id;

		// Check the discount code for this level and update the link arguments if applicable.
		if ( ! empty( $discount_code ) && pmpro_checkDiscountCode( $discount_code, $level->id ) ) {
			$level->link_arguments['discount_code'] = $discount_code;
			$level->discounted_level = pmpro_getLevelAtCheckout( $level->id, $discount_code );
		}

		// Check if user has this level.
		$level->current_level = pmpro_hasMembershipLevel( $level->id );
	}

	// Open the wrapping div for the levels.
	?>
	<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro' ) ); ?>">
	<?php

	// Show the pmpro_msg variable.
	if ( $pmpro_msg ) { ?>
		<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_message ' . $pmpro_msgt, $pmpro_msgt ) ); ?>"><?php echo wp_kses_post( $pmpro_msg ); ?></div>
	<?php }

	do_action( 'pmproal_before_template_load' );

	if ( $layout == 'table' ) {
		// load template for layout = "table"
		include( 'levels-table.php' );
	} elseif( $layout == 'compare_table' ) {
		// load template for layout = "compare_table"
		include( 'levels-compare_table.php' );
	} else {
		// load template for layout = "div", "2col", "3col", "4col" or unspecified layout attribute
		include( 'levels-div.php' );
	}
	?>

	<?php if ( ! empty( $back_link ) ) { ?>
		<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_actions_nav pmproal_actions_nav', 'pmpro_actions_nav' ) ); ?>">
			<span class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_actions_nav-left' ) ); ?>">
				<?php if ( pmpro_hasMembershipLevel() ) { ?>
					<a href="<?php echo esc_url( pmpro_url( 'account' ) )?>"><?php esc_html_e( '&larr; Return to Your Account', 'pmpro-advanced-levels-shortcode' ); ?></a>
				<?php } elseif( ! is_front_page() ) { ?>
					<a href="<?php echo esc_url( home_url() )?>"><?php esc_html_e( '&larr; Return to Home', 'pmpro-advanced-levels-shortcode' ); ?></a>
				<?php } ?>
			</span>
		</div>
	<?php } ?>

	</div><!--.pmpro-->

	<?php
	$temp_content = ob_get_contents();
	ob_end_clean();
	return $temp_content;
}
add_shortcode( 'pmpro_advanced_levels', 'pmpro_advanced_levels_shortcode' );
