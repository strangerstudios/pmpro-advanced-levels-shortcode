<?php
/*
	template for layout="table"
*/

global $pmproal_link_arguments;
?>
<table id="pmpro_levels" class="<?php
	if(!empty($template))
		echo "pmpro_advanced_levels-" . $template;
	else
		echo "pmpro_advanced_levels-table";
	if($template === "gantry" || $template === "bootstrap")
		echo " table table-striped table-bordered";
?>">
<thead>
  <tr>
	<?php do_action('pmproal_extra_cols_before_header'); ?>
	<th><?php _e('Level', 'pmpro-advanced-levels-shortcode');?></th>
	<?php if(!empty($show_price)) { ?>
		<th><?php _e('Price', 'pmpro-advanced-levels-shortcode');?></th>
	<?php } ?>
	<?php if(!empty($expiration)) { ?>
		<th><?php _e('Expiration', 'pmpro-advanced-levels-shortcode');?></th>
	<?php } ?>
	<th>&nbsp;</th>
	<?php do_action('pmproal_extra_cols_after_header'); ?>
  </tr>
</thead>
<tbody>
<?php	
	$count = 0;
	foreach($pmpro_levels_filtered as $level)
	{
        $pmproal_link_arguments['level'] = $level->id;
        
	  if(isset($current_user->membership_level->ID))
		  $current_level = ($current_user->membership_level->ID == $level->id);
	  else
		  $current_level = false;
	?>
	<tr id="pmpro_level-<?php echo $level->id; ?>" class="<?php if($current_level) { echo 'pmpro_level-current '; } if($highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
		<?php do_action('pmproal_extra_cols_before_body', $level->id, $template); ?>
		<td>
			<h2><?php echo $level->name?></h2>
			<?php if(!empty($description)) { echo wpautop($level->description); } ?>
		</td>
		<?php if(!empty($show_price)) { ?>
		<td>
			<?php 
				if($price === 'full')
					echo pmpro_getLevelCost($level, true, false);
				else
					echo pmpro_getLevelCost($level, false, true); 
			?>
		</td>
		<?php } ?>
		<?php 
			if(!empty($expiration)) 
			{ 
				?>
				<td>
				<?php 
					$level_expiration = pmpro_getLevelExpiration($level);
					if(empty($level_expiration))
						_e('Membership Never Expires.', 'pmpro-advanced-levels-shortcode');
					else
						echo $level_expiration;
				?>
				</td>
				<?php 
			} 
		?>
		<td>
		<?php if(empty($current_user->membership_level->ID)) { ?>
			<a class="<?php
				if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
				elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
				elseif($template === "woothemes") { echo "woo-sc-button custom"; }
				else { echo "pmpro_btn pmpro_btn-select"; }
			?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ); ?>"><?php echo $checkout_button; ?></a>
		<?php } elseif ( !$current_level ) { ?>                	
			<a class="<?php
				if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
				elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
				elseif($template === "woothemes") { echo "woo-sc-button custom"; }
				else { echo "pmpro_btn pmpro_btn-select"; }
			?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ); ?>"><?php echo $checkout_button; ?></a>
		<?php } elseif($current_level) { ?>      
			
			<?php
				//if it's a one-time-payment level, offer a link to renew				
				if(!pmpro_isLevelRecurring($current_user->membership_level) && !empty($current_user->membership_level->enddate))
				{
				?>
					<a class="<?php
						if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
						elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
						elseif($template === "woothemes") { echo "woo-sc-button custom"; }
						else { echo "pmpro_btn pmpro_btn-select"; }
					?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ); ?>"><?php echo $renew_button; ?></a>
				<?php
				}
				else
				{
				?>
					<a class="<?php
						if($template === "genesis" || $template === "twentyfourteen") { echo "button"; }
						elseif($template === "foundation") { echo "button info"; }
						elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-info"; }
						elseif($template === "woothemes") { echo "woo-sc-button silver"; }
						else { echo "pmpro_btn disabled"; }
					?>" href="<?php echo pmpro_url("account")?>"><?php echo $account_button; ?></a>
				<?php
				}
			?>
			
		<?php } ?>
		</td>
		<?php do_action('pmproal_extra_cols_after_body', $level->id, $template); ?>
	</tr>
	<?php
	}
?>
</tbody>
</table>
