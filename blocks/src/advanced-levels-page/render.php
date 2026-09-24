<?php
/**
 * Render the Advanced Levels Page block on the frontend.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$output = pmpro_advanced_levels_shortcode( $attributes );
?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Attributes are escaped by core. ?>>
	<?php echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Shortcode output is escaped in the templates. ?>
</div>