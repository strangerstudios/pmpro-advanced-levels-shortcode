<?php
/*
	template for layout="compare_table"
*/
global $pmproal_link_arguments;
?>
<table id="pmpro_levels" class="<?php if(!empty($template)) { echo "pmpro_advanced_levels-" . $template. " "; } ?>pmpro_advanced_levels-compare_table">
	<thead>
		<tr>
			<th><?php _e('Level', 'pmpro');?></th>
			<?php	
				$count = 0;
				foreach($pmpro_levels_filtered as $level)
				{
					?>
					<th class="<?php if(!empty($level) && !empty($current_user->membership_level) && $current_user->membership_level->ID == $level->id) { echo 'pmpro_level-current '; } if(!empty($level) && $highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
						<h2><?php echo $level->name?></h2>
					</th>
					<?php
				}
			?>
		</tr>
		<?php if(!empty($show_price)) { ?>
		<tr>
			<th><?php _e('Price', 'pmpro');?></th>
			<?php
				foreach($pmpro_levels_filtered as $level)
				{				  
					?>
					<th class="pmpro_level-price <?php if(!empty($level) && !empty($current_user->membership_level) && $current_user->membership_level->ID == $level->id) { echo 'pmpro_level-current '; } if(!empty($level) && $highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
						<?php
							if(pmpro_isLevelFree($level))
							{
								if(!empty($expiration))
								{
									?>
									<strong><?php _e('Free', 'pmpro'); ?></strong>
									<?php
								}
								else
								{	
									?>
									<strong><?php _e('Free', 'pmpro'); ?></strong>
									<?php
								}
							}
							elseif($price === 'full')
								echo spanThePMProLevelCostText(pmpro_getLevelCost($level, true, false));
							else
								echo spanThePMProLevelCostText(pmpro_getLevelCost($level, false, true)); 
						?>
					</th>
					<?php 
				} 
			?>
		</tr>
		<?php } ?>
		<?php if(!empty($expiration)) { ?>
		<tr>
			<th><?php _e('Expiration', 'pmpro');?></th>
			<?php
				foreach($pmpro_levels_filtered as $level)
				{										  
					?>
					<th class="pmpro_level-expiration <?php if(!empty($level) && !empty($current_user->membership_level) && $current_user->membership_level->ID == $level->id) { echo 'pmpro_level-current '; } if(!empty($level) && $highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
						<?php 
							$level_expiration = pmpro_getLevelExpiration($level);
							if(empty($level_expiration))
								_e('Membership never expires.', 'pmpro');
							else
								echo $level_expiration;
						?>
					</th>
					<?php 
				} 
			?>
		</tr>
		<?php } ?>
		<tr>
			<th>&nbsp;</th>
			<?php
				foreach($pmpro_levels_filtered as $level)
				{	
					if(isset($current_user->membership_level->ID))
					  $current_level = ($current_user->membership_level->ID == $level->id);
					else
					  $current_level = false;
					
					$pmproal_link_arguments['level'] = $level->id;
					?>
					<th class="<?php if($current_level) { echo 'pmpro_level-current '; } if(!empty($level) && $highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
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
								?>" href="<?php echo pmpro_url("account" ); ?>"><?php echo $account_button; ?></a>
							<?php
							}
						?>								
					<?php } ?>
					</th>
					<?php
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($compareitems)) 
		{ 
			foreach($compareitems as $compareitem)
			{
				?>
				<tr>
				<?php
					$count = -1;
					$compareitem_values = explode(",", $compareitem);
					foreach($compareitem_values as $compareitem_value)
					{			
						if($count >= 0 && !empty($numeric_levels_array[$count]))
							$level = $numeric_levels_array[$count];
						else
							$level = NULL;
						$count++;
						?>
						<td class="<?php if(!empty($level) && !empty($current_user->membership_level) && $current_user->membership_level->ID == $level->id) { echo 'pmpro_level-current '; } if(!empty($level) && $highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
							<?php 
								if($compareitem_value == '1') { echo '<span class="pmpro_level-compare-true"></span>'; } 
								elseif($compareitem_value == '0') { echo '<span class="pmpro_level-compare-false"></span>'; } 
								else { echo $compareitem_value; } 
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
			<?php
				foreach($pmpro_levels_filtered as $level)
				{
					$pmproal_link_arguments['level'] = $level->id;
					
					if(isset($current_user->membership_level->ID))
					  $current_level = ($current_user->membership_level->ID == $level->id);
					else
					  $current_level = false;			  
					?>
					<td class="<?php if($current_level) { echo 'pmpro_level-current '; } if(!empty($level) && $highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
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
					<?php
				}
			?>
		</tr>
		<?php if(!empty($expiration)) { ?>
		<tr>
			<td><?php _e('Expiration', 'pmpro');?></td>
			<?php
				foreach($pmpro_levels_filtered as $level)
				{				  
					?>
					<td class="muted <?php if(!empty($level) && !empty($current_user->membership_level) && $current_user->membership_level->ID == $level->id) { echo 'pmpro_level-current '; } if(!empty($level) && $highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
						<?php 
							$level_expiration = pmpro_getLevelExpiration($level);
							if(empty($level_expiration))
								_e('Membership never expires.', 'pmpro');
							else
								echo $level_expiration;
						?>
					</td>
					<?php 
				} 
			?>
		</tr>
		<?php } ?>					
		<?php if(!empty($more_button)) { ?>
		<tr>
			<td><?php _e('More Information', 'pmpro');?></td>
			<?php	
				$count = 0;
				foreach($pmpro_levels_filtered as $level)
				{				  
					?>
					<td class="<?php if(!empty($level) && !empty($current_user->membership_level) && $current_user->membership_level->ID == $level->id) { echo 'pmpro_level-current '; } if(!empty($level) && $highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>"><?php
							if (function_exists('memberlite_getLevelLandingPage')) {
								$level_page = memberlite_getLevelLandingPage($level->id);
							} else {
								$level_page = null;

							}

							if(!empty($level_page))
							{
								?>
								<a href="<?php echo get_permalink($level_page->ID); ?>"><?php echo $more_button; ?></a>
								<?php
							}
						?>
					</td>
					<?php
				}
			?>
		</tr>
		<?php } ?>					
	</tfoot>
</table>	
<div id="pmpro_levels" class="
<?php
	if(!empty($template))
		echo "pmpro_advanced_levels-" . $template;
	else
		echo "pmpro_advanced_levels-div pmpro_levels-" . $layout;
	if($template === "gantry")
		echo " row-fluid";
?> pmpro_advanced_levels-compare_table_responsive"<?php if($template === "foundation") { echo " data-equalizer"; } ?>>
<?php	
	$count = 0;
	foreach($pmpro_levels_filtered as $level)
	{
	    $pmproal_link_arguments['level'] = $level->id;
	    
		$count++;
		if(isset($current_user->membership_level->ID))
		  $current_level = ($current_user->membership_level->ID == $level->id);
		else
		  $current_level = false;
		?>
		<div class="pmpro_level">
		<div class="entry <?php if($template != "bootstrap") { echo " post "; } ?><?php if($current_level) { echo "pmpro_level-current "; } if($highlight == $level->id) { echo "pmpro_level-highlight "; } if($template === "gantry") { echo " well"; } if($template === "bootstrap") { echo " panel panel-default"; } ?>">
			<header<?php if($template != "twentyfourteen") { ?> class="entry-header<?php } if($template === "bootstrap") { echo " panel-heading"; } ?>"><h2<?php if($template === "bootstrap" && ($layout == '3col' || $layout == '4col')) { echo ' class="text-center"'; } ?>><?php echo $level->name?></h2></header>
			
			<?php if($template === "bootstrap") { ?>
				<div class="panel-body">
			<?php } ?>
			<?php
				if(!empty($show_price))
				{
					if($template === "foundation")
					{
						?>
						<h5 class="subheader">
						<?php
					}
					else
					{
						?>
						<p class="pmpro_level-price<?php if($template === "gantry" || $template === "bootstrap") { echo " lead"; } ?>">
						<?php
					}
					?>
					<?php
						if(pmpro_isLevelFree($level))
						{
							if(!empty($expiration))
							{
								?>
								<strong><?php _e('Free.', 'pmpro-advanced-levels-shortcode'); ?></strong>
								<?php
							}
							else
							{	
								?>
								<strong><?php _e('Free', 'pmpro-advanced-levels-shortcode'); ?></strong>
								<?php
							}
						}
						elseif($price === 'full')
							echo pmpro_getLevelCost($level, true, false); 
						else
							echo pmpro_getLevelCost($level, false, true); 
					
					if($template === "foundation")
					{
						?>
						</h5>
						<?php
					}
					else
					{
						?>
						</p>
						<?php
					}
					?>
					<?php
				}
			?>
			<?php if((!empty($description) || !empty($more_button)) && !empty($level->description)) { ?>
				<div<?php if($template != "twentyfourteen") { ?> class="entry-content"<?php } ?>>
					<?php echo wpautop($level->description); ?>
				</div>
			<?php } ?>
			<?php 
				if(!empty($compareitems)) 
				{
					echo '<p>';
					foreach($compareitems as $compareitem)
					{
						$compareitem_values = explode(",", $compareitem);
						if($count >= 0 && !empty($numeric_levels_array[$count]))
							$compare_level = $numeric_levels_array[$count];
						else
							$compare_level = NULL;
						if($compareitem_values[$count] != '0')
						{ 
							if($compareitem_values[$count] == '1') 
							{
								echo ' <strong>' . $compareitem_values[0] . '</strong>';
							}
							else 
							{
								echo ' <strong>' . $compareitem_values[0] . '</strong>: ';
								echo $compareitem_values[$count]; 
							}
							echo '<br />';
						}
					}
					echo '</p>';
				}
			?>
			<footer<?php if($template != "twentyfourteen") { ?> class="entry-footer"<?php } ?>><div class="entry-meta">
			<?php 
				if($template === "foundation" || $template === "woothemes" || $template === "bootstrap") 
					echo "<hr />";
			?>
			<p>
			<?php 
				if(empty($current_user->membership_level->ID)) 
				{
					?>
					<a class="<?php
						if($template === "genesis" || $template === "twentyfourteen") { echo "button"; }
						elseif($template === "foundation") { echo "button"; }
						elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
						elseif($template === "woothemes") { echo "woo-sc-button custom"; }
						else { echo "pmpro_btn pmpro_btn-select"; }									
					?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") );?>"><?php echo $checkout_button; ?></a>
					<?php 
				}
				elseif(!$current_level) 
				{ 
					?>                	
					<a class="<?php
						if($template === "genesis" || $template === "twentyfourteen") { echo "button"; }
						elseif($template === "foundation") { echo "button right"; }
						elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
						elseif($template === "woothemes") { echo "woo-sc-button custom"; }
						else { echo "pmpro_btn pmpro_btn-select"; }									
					?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") );?>"><?php echo $checkout_button; ?></a>
					<?php
				}
				elseif($current_level)
				{
					//if it's a one-time-payment level, offer a link to renew				
					if(!pmpro_isLevelRecurring($current_user->membership_level) && !empty($current_user->membership_level->enddate))
					{
						?>
						<a class="<?php
							if($template === "genesis" || $template === "twentyfourteen") { echo "button"; }
							elseif($template === "foundation") { echo "button right"; }
							elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
							elseif($template === "woothemes") { echo "woo-sc-button custom"; }
							else { echo "pmpro_btn pmpro_btn-select"; }											
						?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") );?>"><?php echo $renew_button; ?></a>
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
				} 
			?>
			</p>
			<?php 
				if(!empty($expiration)) 
				{
					echo '<p class="pmpro_level-expiration">';
					if($template === "bootstrap")
						echo '<span class="text-muted">';
					$level_expiration = pmpro_getLevelExpiration($level);
					if(empty($level_expiration))
						_e('Membership Never Expires.', 'pmpro-advanced-levels-shortcode');
					else
						echo $level_expiration;
					if($template === "bootstrap")
						echo '</span>';
					echo '</p>';
				} 
			?>
			</div></footer> <!-- .entry-meta, .entry-footer -->
			<?php if($template === "bootstrap") { ?>
				</div><!-- .panel-body -->
			<?php } ?>
		</div></div>
		<?php
	}
?>
</div> <!-- #pmpro_levels, .row -->
