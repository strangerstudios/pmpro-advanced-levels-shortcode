<?php
/*
	template for layout="table"
*/


// Build the selectors for the levels wrapper.
$wrapper_classes = array();
$wrapper_classes[] = 'pmpro_table';
$wrapper_classes[] = 'pmpro_advanced_levels-table';
$wrapper_class = implode( ' ', array_unique( $wrapper_classes ) );
?>
<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_card' ) ); ?>">
	<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_card_content' ) ); ?>">
		<table id="pmpro_levels" class="<?php echo esc_attr( pmpro_get_element_class( $wrapper_class ) ); ?>">
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
					// Build the selectors for the single level elements.
					$element_classes = array();
					$element_classes[] = 'pmpro_level';
					if ( $highlight == $level->id ) {
						$element_classes[] = 'pmpro_level-highlight';
					}
					if ( $level->current_level ) {
						$element_classes[] = 'pmpro_level-current';
					}
					$element_class = implode( ' ', array_unique( $element_classes ) );
					?>
					<tr id="pmpro_level-<?php echo esc_attr( $level->id ); ?>" class="<?php echo esc_attr( $element_class ); ?>">
						<?php do_action('pmproal_extra_cols_before_body', $level->id, $template); ?>
						<th>
							<h2 class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_font-large' ) ); ?>"><?php echo wp_kses( $level->name, pmproal_allowed_html() ); ?></h2>
							<?php if ( ! empty( $description ) && ! empty( $level->description ) ) { ?>
								<div class="pmpro_level-description">
									<?php echo wp_kses_post( wpautop($level->description) ); ?>
								</div> <!-- end .pmpro_level-description -->
							<?php } ?>
						</th>
						<?php if ( ! empty( $show_price ) ) { ?>
							<td>
								<?php pmproal_getLevelPrice( $level, $price ); ?>
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
							<?php pmproal_level_button( $level, $checkout_button, $renew_button, $account_button ); ?>
						</td>
						<?php do_action('pmproal_extra_cols_after_body', $level->id, $template); ?>
					</tr>
				<?php
				}
			?>
			</tbody>
		</table>
	</div> <!-- end .pmpro_card_content -->
</div> <!-- end .pmpro_card -->
