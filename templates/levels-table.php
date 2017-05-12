<?php
/*
	template for layout="table"
*/
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
	<th><?php _e('Level', 'pmproal');?></th>
	<?php if(!empty($show_price)) { ?>
		<th><?php _e('Price', 'pmproal');?></th>
	<?php } ?>
	<?php if(!empty($expiration)) { ?>
		<th><?php _e('Expiration', 'pmproal');?></th>
	<?php } ?>
	<th>&nbsp;</th>
  </tr>
</thead>
<tbody>
<?php	
	$count = 0;
	foreach($pmpro_levels_filtered as $level)
	{				  
	  if(isset($current_user->membership_level->ID))
		  $current_level = ($current_user->membership_level->ID == $level->id);
	  else
		  $current_level = false;
	?>
	<tr class="<?php if($current_level) { echo 'pmpro_level-current '; } if($highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
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
						_e('Membership Never Expires.', 'pmproal');
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
			?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id . $checkout_url_params, "https")?>"><?php echo $checkout_button; ?></a>
		<?php } elseif ( !$current_level ) { ?>                	
			<a class="<?php
				if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
				elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
				elseif($template === "woothemes") { echo "woo-sc-button custom"; }
				else { echo "pmpro_btn pmpro_btn-select"; }
			?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id . $checkout_url_params, "https")?>"><?php echo $checkout_button; ?></a>
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
					?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id . $checkout_url_params, "https")?>"><?php echo $renew_button; ?></a>
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
	</tr>
	<?php
	}
?>
</tbody>
</table>
