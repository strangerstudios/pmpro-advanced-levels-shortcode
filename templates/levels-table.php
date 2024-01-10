<?php
/*
	template for layout="table"
*/

global $pmproal_link_arguments;

// Build the selectors for the levels wrapper.
$wrapper_classes = array();
$wrapper_classes[] = 'pmpro_advanced_levels-table';
$wrapper_class = implode( ' ', array_unique( $wrapper_classes ) );
?>
<table id="pmpro_levels" class="<?php echo esc_attr( $wrapper_class ); ?>">
	<thead>
		<tr>
			<?php do_action('pmproal_extra_cols_before_header'); ?>
			<th><?php esc_html_e('Level', 'pmpro-advanced-levels-shortcode');?></th>
			<?php if ( ! empty( $show_price ) ) { ?>
				<th><?php esc_html_e('Price', 'pmpro-advanced-levels-shortcode');?></th>
			<?php } ?>
			<?php if ( ! empty( $expiration ) ) { ?>
				<th><?php esc_html_e('Expiration', 'pmpro-advanced-levels-shortcode');?></th>
			<?php } ?>
			<th>&nbsp;</th>
			<?php do_action('pmproal_extra_cols_after_header'); ?>
		</tr>
	</thead>
	<tbody>
	<?php
		foreach( $pmpro_levels_filtered as $level ) {
			$pmproal_link_arguments['level'] = $level->id;
			$current_level = pmpro_hasMembershipLevel( $level->id );

			// Build the selectors for the single level elements.
			$element_classes = array();
			$element_classes[] = 'pmpro_level';
			if ( $highlight == $level->id ) {
				$element_classes[] = 'pmpro_level-highlight';
			}
			if ( $current_level ) {
				$element_classes[] = 'pmpro_level-current';
			}
			$element_class = implode( ' ', array_unique( $element_classes ) );
			?>
			<tr id="pmpro_level-<?php echo esc_attr( $level->id ); ?>" class="<?php echo esc_attr( $element_class ); ?>">
				<?php do_action('pmproal_extra_cols_before_body', $level->id, $template); ?>
				<th>
					<h2><?php echo wp_kses( $level->name, pmproal_allowed_html() ); ?></h2>
					<?php if ( ! empty( $description ) && ! empty( $level->description ) ) { ?>
						<div class="pmpro_level-description">
							<?php echo wp_kses_post( wpautop($level->description) ); ?>
						</div> <!-- end .pmpro_level-description -->
					<?php } ?>
				</th>
				<?php if ( ! empty( $show_price ) ) {
					if ( pmpro_isLevelFree ( $level ) ) {
						// if pmpro-level-cost-text Add On is installed and activated and the level has a cost text, use that
						if ( function_exists( 'pmpro_getCustomLevelCostText' ) && ! empty( pmpro_getCustomLevelCostText( $level->id ) ) ) {
							$price_text = pmpro_getCustomLevelCostText( $level->id );
						} else {
							$price_text = __( 'Free', 'pmpro-advanced-levels-shortcode' );
						}
					} elseif ( $price === 'full' ) {
						$price_text = pmpro_getLevelCost( $level, true, false );
					} else {
						$price_text = pmpro_getLevelCost( $level, false, true );
					}

					$price_classes = array();
					$price_classes[] = 'pmpro_level-price';
					if ( pmpro_isLevelFree ( $level ) ) {
						$price_classes[] = 'pmpro_level-price-free';
					}
					$price_class = implode( ' ', array_unique( $price_classes ) );
					?>
					<td class="<?php echo esc_attr( $price_class ); ?>">
						<?php echo wp_kses( $price_text, pmproal_allowed_html() ); ?>
				</td>
				<?php } ?>

				<?php if ( ! empty ( $expiration ) ) {
					$level_expiration = pmpro_getLevelExpiration($level); ?>
					<td class="pmpro_level-expiration">
						<?php if ( empty ( $level_expiration ) ) {
							esc_html_e('Membership never expires.', 'pmpro-advanced-levels-shortcode');
						} else {
							echo wp_kses( $level_expiration, pmproal_allowed_html() );
						} ?>
					</td>
				<?php } ?>

				<td>
					<?php pmproal_level_button( $level, $pmproal_link_arguments, $current_level, $checkout_button, $renew_button, $account_button ); ?>
				</td>
				<?php do_action('pmproal_extra_cols_after_body', $level->id, $template); ?>
			</tr>
		<?php
		}
	?>
	</tbody>
</table>
