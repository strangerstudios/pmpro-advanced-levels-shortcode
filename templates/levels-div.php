<?php
/*
	Template for layout= "div" or "2col" or "3col" or "4col"
*/
global $pmproal_link_arguments;

// Build the selectors for the levels div wrapper.
$wrapper_classes = array();
$wrapper_classes[] = 'pmpro_advanced_levels-div';
if ( ! empty( $layout ) ) {
	$wrapper_classes[] = 'pmpro_levels-' . esc_attr( $layout );
}
$wrapper_class = implode( ' ', array_unique( $wrapper_classes ) );
?>
<div id="pmpro_levels" class="<?php echo esc_attr( $wrapper_class ); ?>">
<?php
	foreach ( $pmpro_levels_filtered as $level ) {
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
		<div id="pmpro_level-<?php echo esc_attr( $level->id ); ?>" class="<?php echo esc_attr( $element_class ); ?>">
			<?php do_action('pmproal_before_level', $level->id, $layout ); ?>

			<h2><?php echo esc_html( $level->name ); ?></h2>

			<?php if ( $layout === 'div' || $layout === '2col' || empty( $layout ) ) { ?>
				<?php if ( ! empty( $description ) && ! empty( $level->description ) ) { ?>
					<div class="pmpro_level-description">
						<?php echo wp_kses_post( wpautop($level->description) ); ?>
					</div> <!-- end .pmpro_level-description -->
				<?php } ?>

				<div class="pmpro_level-meta">
					
					<?php pmproal_level_button( $level, $pmproal_link_arguments, $current_level, $checkout_button, $renew_button, $account_button ); ?>

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
						<p class="<?php echo esc_attr( $price_class ); ?>">
							<?php echo wp_kses( $price_text, pmproal_allowed_html() ); ?>
						</p> <!-- end pmpro_level-price -->
					<?php } ?>

					<?php if ( ! empty ( $expiration ) ) {
						$level_expiration = pmpro_getLevelExpiration($level); ?>
						<p class="pmpro_level-expiration">
							<?php if ( empty ( $level_expiration ) ) {
								esc_html_e('Membership never expires.', 'pmpro-advanced-levels-shortcode');
							} else {
								echo wp_kses( $level_expiration, pmproal_allowed_html() );
							} ?>
						</p> <!-- end pmpro_level-expiration -->
					<?php } ?>

				</div> <!-- .pmpro_level-meta -->

				<?php 
			} else {
				// This is a column-type div layout
				if ( ! empty( $show_price ) ) {
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
					<p class="<?php echo esc_attr( $price_class ); ?>">
						<?php echo wp_kses( $price_text, pmproal_allowed_html() ); ?>
					</p> <!-- end pmpro_level-price -->
				<?php } ?>
					
				<p class="pmpro_level-select">
					<?php 						var_dump( $pmproal_link_arguments );
pmproal_level_button( $level, $pmproal_link_arguments, $current_level, $checkout_button, $renew_button, $account_button ); ?>
				</p> <!-- end .pmpro_level-select -->

				<?php if ( ! empty( $description ) && ! empty( $level->description ) ) { ?>
					<div class="pmpro_level-description">
						<?php echo wp_kses_post( wpautop($level->description) ); ?>
					</div> <!-- end .pmpro_level-description -->
				<?php } ?>

				<?php if ( ! empty ( $expiration ) ) {
					$level_expiration = pmpro_getLevelExpiration($level); ?>
					<p class="pmpro_level-expiration">
						<?php if ( empty ( $level_expiration ) ) {
							esc_html_e('Membership never expires.', 'pmpro-advanced-levels-shortcode');
						} else {
							echo wp_kses( $level_expiration, pmproal_allowed_html() );
						} ?>
					</p> <!-- end pmpro_level-expiration -->
				<?php } ?>

			<?php } ?>

			<?php do_action('pmproal_after_level', $level->id, $layout); ?>

		</div><!-- .pmpro_level -->
		<?php
	}
?>
</div> <!-- #pmpro_levels, .row -->
