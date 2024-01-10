<?php
/**
 * Render the Advanced Levels Page block on the frontend.
 */
$output = pmpro_advanced_levels_shortcode( $attributes );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php echo $output; ?>
</div>