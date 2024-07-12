<?php
/**
 * Template for layout="compare_table".
 *
 */

 // Build the selectors for the single level elements.
foreach ( $pmpro_levels_filtered as $key => $level ) {
	$element_classes = array();
	$element_classes[] = 'pmpro_level';
	if ( $highlight == $level->id ) {
		$element_classes[] = 'pmpro_level-highlight';
	}
	if ( $level->current_level ) {
		$element_classes[] = 'pmpro_level-current';
	}

	$element_class = implode( ' ', array_unique( $element_classes ) );
	$pmpro_levels_filtered[$key]->element_class = $element_class;
}

// Build the selectors for the levels wrapper.
$wrapper_classes = array();
$wrapper_classes[] = 'pmpro_advanced_levels-compare_table';
$wrapper_class = implode( ' ', array_unique( $wrapper_classes ) );
?>
<table id="pmpro_levels" class="<?php echo esc_attr( $wrapper_class ); ?>">
	<thead>
		<tr>
			<th><span class="screen-reader-text"><?php esc_html_e('Level', 'pmpro-advanced-levels-shortcode');?></span></th>
			<?php
				$count = 0;
				foreach ( $pmpro_levels_filtered as $level ) { ?>
					<th class="<?php echo esc_attr( $level->element_class ); ?>">
					<h2 class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_font-large' ) ); ?>"><?php echo wp_kses( $level->name, pmproal_allowed_html() ); ?></h2>
					</th>
					<?php
				}
			?>
		</tr>
		<?php if ( ! empty( $show_price ) ) { ?>
			<tr>
				<th><span class="screen-reader-text"><?php esc_html_e( 'Price', 'pmpro-advanced-levels-shortcode' ); ?></span></th>
				<?php foreach ( $pmpro_levels_filtered as $level ) { ?>
					<th class="<?php echo esc_attr( $level->element_class ); ?>">
						<?php pmproal_getLevelPrice( $level, $price ); ?>
					</th>
				<?php } ?>
			</tr>
		<?php } ?>
		<?php if ( ! empty( $description ) ) { ?>
			<tr>
				<th><span class="screen-reader-text"><?php esc_html_e( 'Description', 'pmpro-advanced-levels-shortcode' ); ?></span></th>
				<?php foreach ( $pmpro_levels_filtered as $level ) { ?>
					<th class="<?php echo esc_attr( $level->element_class ); ?> pmpro_level-description">
						<?php echo wpautop($level->description); ?>
					</th>
				<?php } ?>
			</tr>
		<?php } ?>
		<?php if ( ! empty( $expiration ) ) { ?>
			<tr>
				<th><span class="screen-reader-text"><?php esc_html_e( 'Expiration', 'pmpro-advanced-levels-shortcode' );?></span></th>
				<?php foreach ( $pmpro_levels_filtered as $level ) { ?>
					<th class="<?php echo esc_attr( $level->element_class ); ?> pmpro_level-expiration">
						<?php 
							$level_expiration = pmpro_getLevelExpiration($level);
							if ( empty( $level_expiration ) ) {
								esc_html_e( 'Membership never expires.', 'pmpro-advanced-levels-shortcode' );
							} else {
								echo wp_kses( $level_expiration, pmproal_allowed_html() );
							}
						?>
					</th>
				<?php } ?>
			</tr>
		<?php } ?>
		<tr>
			<th>&nbsp;</th>
			<?php foreach ( $pmpro_levels_filtered as $level ) { ?>
				<th class="<?php echo esc_attr( $level->element_class ); ?>">
					<?php pmproal_level_button( $level, $checkout_button, $renew_button, $account_button ); ?>
				</th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php
			if ( ! empty( $compareitems ) ) {
			foreach ( $compareitems as $compareitem ) { ?>
				<tr>
				<?php
					$count = -1;

					// Build the array of compare items.
					$compareitem_values = explode(",", $compareitem);

					/**
					 * Filter the compare items.
					 *
					 * @since 0.2.6
					 * @param array $compareitem_values The compare items.
					 * @return array $compareitem_values The filtered compare items.
					 */
					$compareitem_values = apply_filters( 'pmpro_advanced_levels_compare_items', $compareitem_values );

					foreach ( $compareitem_values as $compareitem_value ) {
						if ( $count >= 0 && ! empty( $numeric_levels_array[$count] ) ) {
							$level = $numeric_levels_array[$count];
						} else {
							$level = NULL;
						}
						$count++;
						?>
						<td <?php echo $level ? 'class="' . esc_attr( $level->element_class ) . '"' : ''; ?>>
							<?php
								if ( $compareitem_value == '1' ) {
									echo '<span class="pmpro_level-compare-true"><span class="screen-reader-text">' . esc_html__( 'Yes', 'pmpro-advanced-levels-shortcode' ) . '</span></span>';
								} elseif ( $compareitem_value == '0' ) {
									echo '<span class="pmpro_level-compare-false"><span class="screen-reader-text">' . esc_html__( 'No', 'pmpro-advanced-levels-shortcode' ) . '</span></span>';
								} else {
									echo wp_kses( $compareitem_value, pmproal_allowed_html() );
								}
							?>
						</td>
						<?php
					}
				?>
				</tr>
				<?php 
			}
		}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td>&nbsp;</td>
			<?php foreach ( $pmpro_levels_filtered as $level ) { ?>
				<td class="<?php echo esc_attr( $level->element_class ); ?>">
					<?php pmproal_level_button( $level, $checkout_button, $renew_button, $account_button ); ?>
				</td>
			<?php } ?>
		</tr>
		<?php if ( ! empty( $expiration ) ) { ?>
		<tr>
			<td><span class="screen-reader-text"><?php esc_html_e( 'Expiration', 'pmpro-advanced-levels-shortcode' );?></span></td>
			<?php foreach ( $pmpro_levels_filtered as $level ) { ?>
				<td class="<?php echo esc_attr( $level->element_class ); ?> pmpro_level-expiration">
					<?php 
						$level_expiration = pmpro_getLevelExpiration( $level );
						if ( empty( $level_expiration ) ) {
							esc_html_e( 'Membership never expires.', 'pmpro-advanced-levels-shortcode' );
						} else {
							echo wp_kses( $level_expiration, pmproal_allowed_html() );
						}
					?>
				</td>
			<?php } ?>
		</tr>
		<?php } ?>
	</tfoot>
</table>	

<?php
/**
 * Add a hidden version of the levels layout for smaller screens.
 */

// Build the selectors for the levels div wrapper.
$wrapper_classes = array();
$wrapper_classes[] = 'pmpro_advanced_levels-div';
$wrapper_classes[] = 'pmpro_advanced_levels-compare_table_responsive';
if ( ! empty( $layout ) ) {
	$wrapper_classes[] = 'pmpro_levels-' . esc_attr( $layout );
}
$wrapper_class = implode( ' ', array_unique( $wrapper_classes ) );
?>
<div id="pmpro_levels" class="<?php echo esc_attr( $wrapper_class ); ?>">
<?php
	// Reset the count.
	$count = 0;
	foreach ( $pmpro_levels_filtered as $level ) {
		// Increment the count so we know what place we are in the compare items list.
		$count++;

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
		<div id="pmpro_level-<?php echo esc_attr( $level->id ); ?>" class="<?php echo esc_attr( $element_class ); ?>">
			<?php do_action('pmproal_before_level', $level->id, $layout ); ?>

			<h2><?php echo wp_kses( $level->name, pmproal_allowed_html() ); ?></h2>

			<?php if ( ! empty( $description ) && ! empty( $level->description ) ) { ?>
				<div class="pmpro_level-description">
					<?php echo wp_kses_post( wpautop($level->description) ); ?>
				</div> <!-- end .pmpro_level-description -->
			<?php } ?>

			<?php if ( ! empty( $compareitems ) ) {
				echo '<ul>';
				foreach ( $compareitems as $compareitem ) {

					// Build the array of compare items.
					$compareitem_values = explode(",", $compareitem);

					/**
					 * Filter the compare items.
					 *
					 * @since 0.2.6
					 * @param array $compareitem_values The compare items.
					 * @return array $compareitem_values The filtered compare items.
					 */
					$compareitem_values = apply_filters( 'pmpro_advanced_levels_compare_items', $compareitem_values );

					$compareitem_values = explode( ',', $compareitem );
					if ( $count >= 0 && ! empty( $numeric_levels_array[$count] ) ) {
						$compare_level = $numeric_levels_array[$count];
					} else {
						$compare_level = NULL;
					}

					if ( $compareitem_values[$count] != '0' ) { 
						if ( $compareitem_values[$count] == '1' ) {
							echo '<li><strong>' . wp_kses( $compareitem_values[0], pmproal_allowed_html() ) . '</strong></li>';
						} else {
							echo '<li><strong>' . wp_kses( $compareitem_values[0], pmproal_allowed_html() ) . '</strong>: ';
							echo wp_kses( $compareitem_values[$count], pmproal_allowed_html() ) . '</li>';
						}
					}
				}
				echo '</ul>';
			} ?>

			<div class="pmpro_level-meta">

				<?php pmproal_level_button( $level, $checkout_button, $renew_button, $account_button ); ?>

				<?php pmproal_getLevelPrice( $level, $price ); ?>

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
			<?php do_action( 'pmproal_after_level', $level->id, $layout ); ?>
		</div><!-- .pmpro_level -->
		<?php
	}
?>
</div> <!-- #pmpro_levels, .row -->
