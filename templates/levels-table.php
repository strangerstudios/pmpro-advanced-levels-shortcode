<?php
/*
	template for layout="table"
*/

global $pmproal_link_arguments;
?>
<table id="pmpro_levels" class="<?php echo pmpro_advanced_levels_wrapper_class( $layout, $template ); ?>">
<thead>
  <tr>
	<?php do_action( 'pmproal_extra_cols_before_header' ); ?>
	<th><?php _e('Level', 'pmpro-advanced-levels-shortcode' );?></th>
	<?php if ( ! empty( $show_price ) ) { ?>
		<th><?php _e( 'Price', 'pmpro-advanced-levels-shortcode' ); ?></th>
	<?php } ?>
	<?php if ( ! empty( $expiration ) ) { ?>
		<th><?php _e( 'Expiration', 'pmpro-advanced-levels-shortcode' ); ?></th>
	<?php } ?>
	<th>&nbsp;</th>
	<?php do_action( 'pmproal_extra_cols_after_header' ); ?>
  </tr>
</thead>
<tbody>
<?php
	foreach( $pmpro_levels_filtered as $level ) {
		$pmproal_link_arguments['level'] = $level->id;
		if ( isset( $current_user->membership_level->ID ) ) {
		  $current_level = $current_user->membership_level;
		} else {
		  $current_level = false;
		}
	?>
	<tr id="pmpro_level-<?php echo $level->id; ?>" class="<?php echo pmpro_advanced_levels_level_inner_class( $level->id, $layout, $template, $current_level, $highlight ); ?>">

		<?php do_action( 'pmproal_extra_cols_before_body', $level->id, $template ); ?>

		<td>
			<h2 class="level-name"><?php echo $level->name; ?></h2>
			<?php if ( ! empty( $description ) ) { ?>
				<div class="level-description">
					<?php echo wpautop( $level->description ); ?>
				</div>
			<?php } ?>
		</td>

		<?php if ( ! empty( $show_price ) ) { ?>
			<td class="pmpro_level-price">
				<?php if ( $price === 'full' ) {
						echo pmpro_getLevelCost( $level, true, false );
					} else {
						echo pmpro_getLevelCost( $level, false, true );
					}
				?>
			</td>
		<?php } ?>

		<?php if ( ! empty( $expiration ) ) { ?>
			<td class="pmpro_level-expiration">
			<?php 
				$level_expiration = pmpro_getLevelExpiration( $level );
				if ( empty( $level_expiration ) ) {
					_e( 'Membership Never Expires.', 'pmpro-advanced-levels-shortcode' );
				} else {
					echo $level_expiration;
				}
			?>
			</td>
			<?php
			} 
		?>

		<td>
			<a class="<?php echo pmpro_advanced_levels_level_button_class( $level->id, $layout, $template, $current_level ); ?>" href="<?php echo pmpro_advanced_levels_level_button_link( $pmproal_link_arguments, $level->id, $current_level ); ?>"><?php echo pmpro_advanced_levels_level_button_text( $level->id, $current_level, $checkout_button, $renew_button, $account_button ); ?></a>
		</td>

		<?php do_action( 'pmproal_extra_cols_after_body', $level->id, $template ); ?>

	</tr>
	<?php
	}
?>
</tbody>
</table>
