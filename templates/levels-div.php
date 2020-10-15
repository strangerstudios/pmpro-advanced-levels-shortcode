<?php
/*
	Template for layout = "div" or "2col" or "3col" or "4col"
*/
global $pmproal_link_arguments;
?>
<div id="pmpro_levels" class="<?php echo pmpro_advanced_levels_wrapper_class( $layout, $template ); ?>">
<?php
	$current_level = pmpro_hasMembershipLevel( $level->id );

	foreach( $pmpro_levels_filtered as $level ) {
		$pmproal_link_arguments['level'] = $level->id;
	?>
	<div id="pmpro_level-<?php echo $level->id; ?>" class="<?php echo pmpro_advanced_levels_level_class( $level->id, $layout, $template ); ?>">

		<div class="<?php echo pmpro_advanced_levels_level_inner_class( $level->id, $layout, $template, $current_level, $highlight ); ?>">

			<?php do_action( 'pmproal_before_level', $level->id, $layout ); ?>

			<h2 class="level-name"><?php echo $level->name; ?></h2>

			<?php if ( ! empty( $description ) ) { ?>
				<div class="level-description">
					<?php echo wpautop( $level->description ); ?>
				</div>
			<?php } ?>

			<a class="<?php echo pmpro_advanced_levels_level_button_class( $level->id, $layout, $template, $current_level ); ?>" href="<?php echo pmpro_advanced_levels_level_button_link( $pmproal_link_arguments, $level->id, $current_level ); ?>"><?php echo pmpro_advanced_levels_level_button_text( $level->id, $current_level, $checkout_button, $renew_button, $account_button ); ?></a>

			<?php if ( ! empty( $show_price ) ) { ?>
				<span class="pmpro_level-price">
					<?php if ( pmpro_isLevelFree( $level ) ) { ?>
						<strong><?php _e('Free', 'pmpro-advanced-levels-shortcode'); ?></strong>
					<?php } elseif ($price === 'full') {
							echo pmpro_getLevelCost($level, true, false);
						} else {
							echo pmpro_getLevelCost($level, false, true);
						}
					?>
				</span> <!-- end pmpro_level-price -->
			<?php } ?>

			<?php if ( ! empty( $expiration ) ) { ?>
				<span class="pmpro_level-expiration">
				<?php
					$level_expiration = pmpro_getLevelExpiration( $level );

					if ( empty( $level_expiration ) ) {
						_e( 'Membership Never Expires.', 'pmpro-advanced-levels-shortcode' );
					} else {
						echo $level_expiration;
					}
				?>
				</span> <!-- end pmpro_level-expiration -->
			<?php } ?>

			<?php do_action( 'pmproal_after_level', $level->id, $layout ); ?>
		</div> <!-- end post -->
	</div><!-- .pmpro_level -->
	<?php
	}
?>
</div> <!-- #pmpro_levels -->
