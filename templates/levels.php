<?php
/*
	Based on the pmpro_levels shortcode bundled in the Paid Memberships Pro plugin.
	
	This shortcode will display the membership levels and additional content based on the defined attributes.
*/
function pmpro_advanced_levels_shortcode($atts, $content=null, $code="")
{
	// $atts    ::= array of attributes
	// $content ::= text within enclosing form of shortcode element
	// $code    ::= the shortcode found, when == callback name
	// examples: [pmpro_advanced_levels template="genesis" levels="1,2,3" layout="table" hightlight="2" description="false" checkout_button="Register Now"]
	
	extract(shortcode_atts(array(
		'account_button' => __('Your&nbsp;Level', 'pmproal'),
		'back_link' => '1',
		'compare' => NULL,
		'template' => NULL,
		'checkout_button' => __('Select', 'pmproal'),
		'description' => '1',
		'discount_code' => NULL,
		'expiration' => '1',
		'highlight' => NULL,
		'layout' => 'div',
		'levels' => NULL,		
		'more_button' => NULL,
		'price' => 'short',
		'renew_button' => __('Renew', 'pmproal'),
		'template' => NULL,
	), $atts));
	
	global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user, $pmpro_currency_symbol, $pmpro_all_levels, $pmpro_visible_levels, $current_user, $membership_levels;
	
	if($back_link === "0" || $back_link === "false" || $back_link === "no")
		$back_link = false;
	else
		$back_link = true;
		
	if($compare === "0" || $compare === "false" || $compare === "no")
		$compare = false;
	else
		$compareitems = explode(";", $compare);
		
	//turn 0's into falses
	if($description === "0" || $description === "false" || $description === "no")
		$description = false;
	else
		$description = true;
		
	if($expiration === "0" || $expiration === "false" || $expiration === "no")
		$expiration = false;
	else
		$expiration = true;
	
	if($more_button === "0" || $more_button === "false" || $more_button === "no" || empty($more_button))
		$more_button = false;
	elseif($more_button === "1" || $more_button === "true" || $more_button === "yes")
		$more_button = "Read More";
		
	if($price === "0" || $price === "false" || $price === "hide")
		$show_price = false;
	else
		$show_price = true;

	//string to store other params to add to the checkout url
	$checkout_url_params = "";
		
	ob_start();					
		
		//make sure pmpro_levels has all levels
		if(!isset($pmpro_all_levels))
			$pmpro_all_levels = pmpro_getAllLevels(true, true);
		if(!isset($pmpro_visible_levels))
			$pmpro_visible_levels = pmpro_getAllLevels(false, true);
		
		if($pmpro_msg)
		{
			?>
			<div class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
			<?php
		}
		
		$pmpro_levels_filtered = array();
		if(!empty($levels))
		{
			$levels_order = explode(",", $levels);
			//loop through $levels_order array and pull levels from $levels
			foreach($levels_order as $level_id)
			{
				foreach($pmpro_all_levels as $level)
				{
					if($level->id == $level_id)
					{
						$pmpro_levels_filtered[$level->id] = $level;
						break;
					}
				}
			}
		}
		else
			$pmpro_levels_filtered = $pmpro_visible_levels;
		
		$pmpro_levels_filtered = apply_filters("pmpro_levels_array", $pmpro_levels_filtered);
		$numeric_levels_array = array_values($pmpro_levels_filtered);
		
		//update per discount code
		if(!empty($discount_code) && !empty($pmpro_levels_filtered))
		{			
			foreach($pmpro_levels_filtered as $level_id => $level)
			{				
				//check code for this level and update if applicable
				if(pmpro_checkDiscountCode($discount_code, $level->id))
				{
					$sqlQuery = "SELECT l.id, cl.*, l.name, l.description, l.allow_signups FROM $wpdb->pmpro_discount_codes_levels cl LEFT JOIN $wpdb->pmpro_membership_levels l ON cl.level_id = l.id LEFT JOIN $wpdb->pmpro_discount_codes dc ON dc.id = cl.code_id WHERE dc.code = '" . $discount_code . "' AND cl.level_id = '" . (int)$level->id . "' LIMIT 1";
					$pmpro_levels_filtered[$level_id] = $wpdb->get_row($sqlQuery);
					$pmpro_levels_filtered[$level_id]->base_level = $level;
					$checkout_url_params .= "&discount_code=" . $discount_code;
				}				
			}	
		}
				
		if($layout == 'table')
		{
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
			<?php
		}
		elseif($layout == 'compare_table')
		{
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
								?>
								<th class="<?php if($current_level) { echo 'pmpro_level-current '; } if(!empty($level) && $highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
								<?php if(empty($current_user->membership_level->ID)) { ?>
									<a class="<?php
										if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
										elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
										elseif($template === "woothemes") { echo "woo-sc-button custom"; }
										else { echo "pmpro_btn pmpro_btn-select"; }
									?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php echo $checkout_button; ?></a>
								<?php } elseif ( !$current_level ) { ?>                	
									<a class="<?php
										if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
										elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
										elseif($template === "woothemes") { echo "woo-sc-button custom"; }
										else { echo "pmpro_btn pmpro_btn-select"; }
									?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php echo $checkout_button; ?></a>
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
											?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php echo $renew_button; ?></a>
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
									?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php echo $checkout_button; ?></a>
								<?php } elseif ( !$current_level ) { ?>                	
									<a class="<?php
										if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
										elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
										elseif($template === "woothemes") { echo "woo-sc-button custom"; }
										else { echo "pmpro_btn pmpro_btn-select"; }
									?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php echo $checkout_button; ?></a>
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
											?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php echo $renew_button; ?></a>
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
								<td class="<?php if(!empty($level) && !empty($current_user->membership_level) && $current_user->membership_level->ID == $level->id) { echo 'pmpro_level-current '; } if(!empty($level) && $highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
									<?php
										$level_page = memberlite_getLevelLandingPage($level->id);
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
				if(empty($template) || $template === "foundation" || $template === "bootstrap")
					echo " row";
				if($template === "gantry")
					echo " row-fluid";
			?> pmpro_advanced_levels-compare_table_responsive"<?php if($template === "foundation") { echo " data-equalizer"; } ?>>
			<?php	
				$count = 0;
				foreach($pmpro_levels_filtered as $level)
				{
					$count++;
					if(isset($current_user->membership_level->ID))
					  $current_level = ($current_user->membership_level->ID == $level->id);
					else
					  $current_level = false;
					?>
					<div class="pmpro_level <?php 
						if($template === "genesis") {
							if(count($pmpro_levels_filtered) > 1) { echo '12'; } } 
							if($count == 1) { echo ' first';
						} 
						elseif($template === "bootstrap") {
							if(count($pmpro_levels_filtered) > 1) { echo 'col-md-12'; }
						} 
						elseif($template === "gantry") {
							if(count($pmpro_levels_filtered) > 1) { echo 'span12'; }
						} 
						elseif($template === "woothemes") {
							if(count($pmpro_levels_filtered) > 1) { echo 'full'; }
						}
						else 
						{
							?>
							medium-<?php
							if(count($pmpro_levels_filtered) > 1) { echo '12'; }
						?> columns
						<?php
						}
					?>">
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
											<strong><?php _e('Free.', 'pmproal'); ?></strong>
											<?php
										}
										else
										{	
											?>
											<strong><?php _e('Free', 'pmproal'); ?></strong>
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
								?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id . $checkout_url_params, "https")?>"><?php echo $checkout_button; ?></a>
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
								?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id . $checkout_url_params, "https")?>"><?php echo $checkout_button; ?></a>
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
									?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php echo $renew_button; ?></a>
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
									_e('Membership Never Expires.', 'pmproal');
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
			<?php
		}
		//'div' or No layout specified - use 'div'
		else
		{
			?>
			<div id="pmpro_levels" class="
			<?php
				if(!empty($template))
					echo "pmpro_advanced_levels-" . $template;
				else
					echo "pmpro_advanced_levels-div pmpro_levels-" . $layout;
				if(empty($template) || $template === "foundation" || $template === "bootstrap")
					echo " row";
				if($template === "gantry")
					echo " row-fluid";
				if($template === "twentyfourteen")
				{
					if($layout == '2col') { echo ' gallery-columns-2'; }
					elseif($layout == '3col') { echo ' gallery-columns-3'; }
					elseif($layout == '4col') { echo ' gallery-columns-4'; } 
				}
			?>"<?php if($template === "foundation") { echo " data-equalizer"; } ?>>
			<?php	
				$count = 0;
				foreach($pmpro_levels_filtered as $level)
				{
					$count++;				
					if(isset($current_user->membership_level->ID))
					  $current_level = ($current_user->membership_level->ID == $level->id);
					else
					  $current_level = false;
				?>
				<div class="pmpro_level <?php 
					if($template === "genesis") {
						if($layout == '2col') { echo 'one-half'; }
						elseif($layout == '3col') { echo 'one-third'; }
						elseif($layout == '4col') { echo 'one-fourth'; } 
						else { if(count($pmpro_levels_filtered) > 1) { echo '12'; } } 
						if($count == 1) { echo ' first'; }
					} 
					elseif($template === "bootstrap") {
						if($layout == '2col') { echo 'col-md-6'; }
						elseif($layout == '3col') { echo 'col-md-4'; }
						elseif($layout == '4col') { echo 'col-md-3'; } 
						else { if(count($pmpro_levels_filtered) > 1) { echo 'col-md-12'; } } 
					} 
					elseif($template === "gantry") {
						if($layout == '2col') { echo 'span6'; }
						elseif($layout == '3col') { echo 'span4'; }
						elseif($layout == '4col') { echo 'span3'; } 
						else { if(count($pmpro_levels_filtered) > 1) { echo 'span12'; } } 
					} 
					elseif($template === "twentyfourteen") {
						if($layout == '2col' || $layout == '3col' ||$layout == '4col') { echo 'gallery-item'; }
					} 
					elseif($template === "woothemes") {
						if($layout == '2col') { echo 'twocol-one'; }
						elseif($layout == '3col') { echo 'threecol-one'; }
						elseif($layout == '4col') { echo 'fourcol-one'; }
						else { if(count($pmpro_levels_filtered) > 1) { echo 'full'; } } 
						if($layout == '2col' && $count % 2 == 0) { echo ' last'; }
						elseif($layout == '3col' && $count % 3 == 0) { echo ' last'; }
						elseif($layout == '4col' && $count % 4 == 0) { echo ' last'; }
					}
					else 
					{
						?>
						medium-<?php
						if($layout == '2col') { echo '6'; }
						elseif($layout == '3col') { echo '4 text-center'; }
						elseif($layout == '4col') { echo '3 text-center'; } 
						else { if(count($pmpro_levels_filtered) > 1) { echo '12'; } } 
					?> columns
					<?php
					}
				?>">
				<div class="<?php if($layout != "4col") { echo "entry "; } if($template != "bootstrap") { echo " post "; } ?><?php if($current_level) { echo "pmpro_level-current "; } if($highlight == $level->id) { echo "pmpro_level-highlight "; } if($template === "foundation" && ($layout === "2col" || $layout === "div" || empty($layout))) { echo " panel"; } if($template === "gantry") { echo " well"; } if($template === "bootstrap") { echo " panel panel-default"; } ?>"<?php if($template === "foundation" && $layout === "2col") { echo " data-equalizer-watch"; } ?>>
				<?php 
					if($template === "foundation" && ($layout === "4col" || $layout === "3col")) 
					{ 
						?>
						<ul class="pricing-table" data-equalizer-watch>
							<li class="title"><?php echo $level->name?></li>
							<?php
								if(!empty($show_price))
								{
									?>
									<li class="price">
									<?php
										if(pmpro_isLevelFree($level))
										{
											if(!empty($expiration))
											{
												?>
												<strong><?php _e('Free.', 'pmproal'); ?></strong>
												<?php
											}
											else
											{	
												?>
												<strong><?php _e('Free', 'pmproal'); ?></strong>
												<?php
											}
										}
										elseif($price === 'full')
											echo spanThePMProLevelCostText(pmpro_getLevelCost($level, true, false));
										else
											echo spanThePMProLevelCostText(pmpro_getLevelCost($level, false, true)); 
									?>
									</li>
								<?php
								}
							?>
							<?php if(!empty($description)) { ?>
<li class="description">
									<?php echo $level->description; ?>
								</li>
							<?php } ?>
							<?php 
								if(!empty($expiration)) 
								{ 
									echo '<li class="description">';
									$level_expiration = pmpro_getLevelExpiration($level);
									if(empty($level_expiration))
										_e('Membership Never Expires.', 'pmproal');
									else
										echo $level_expiration;
									echo '</li>';
								} 
							?>
							<li class="cta-button"><?php 
								if(empty($current_user->membership_level->ID)) 
								{ 
									?>
									<a class="<?php
										if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
										elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
										elseif($template === "woothemes") { echo "woo-sc-button custom"; }
										else { echo "pmpro_btn pmpro_btn-select"; }
									?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id . $checkout_url_params, "https")?>"><?php echo $checkout_button; ?></a>
									<?php 
								}
								elseif(!$current_level) 
								{ 
									?>                	
									<a class="<?php
										if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
										elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
										elseif($template === "woothemes") { echo "woo-sc-button custom"; }
										else { echo "pmpro_btn pmpro_btn-select"; }
									?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id . $checkout_url_params, "https")?>"><?php echo $checkout_button; ?></a>
									<?php
								}
								elseif($current_level)
								{
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
								} 
							?></li>
							</ul>
						<?php
					}
					else
					{
						?>
						<header<?php if($template != "twentyfourteen") { ?> class="entry-header<?php } if($template === "bootstrap") { echo " panel-heading"; } ?>"><h2<?php if($template === "bootstrap" && ($layout == '3col' || $layout == '4col')) { echo ' class="text-center"'; } ?>><?php echo $level->name?></h2></header>
						<?php if($template === "bootstrap") { ?>
							<div class="panel-body<?php if($template === "bootstrap" && ($layout == '3col' || $layout == '4col')) { echo ' text-center'; } ?>">
						<?php } ?>
						<?php if((!empty($description) || !empty($more_button)) && ($layout == 'div' || $layout == '2col' || empty($layout))) { ?>
							<div<?php if($template != "twentyfourteen") { ?> class="entry-content"<?php } ?>>
								<?php echo wpautop($level->description); ?>
							</div>
						<?php } ?>
						<?php if($layout === 'div' || $layout === '2col' || empty($layout)) { ?>
							<footer<?php if($template != "twentyfourteen") { ?> class="entry-footer"<?php } ?>>	<div class="entry-meta">
							<?php 
								if($template === "foundation" || $template === "woothemes" || $template === "bootstrap") 
									echo "<hr />";
							?>
							<?php 
								if(empty($current_user->membership_level->ID)) 
								{ 
									?>
									<a class="<?php
										if($template === "genesis" || $template === "twentyfourteen") { echo "button alignright"; }
										elseif($template === "foundation") { echo "button right"; }
										elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary pull-right"; }
										elseif($template === "woothemes") { echo "woo-sc-button custom alignright pull-right"; }
										else { echo "pmpro_btn pmpro_btn-select"; if($layout == 'div' || $layout == '2col' || empty($layout)) { echo ' alignright'; }											
									} ?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id . $checkout_url_params, "https")?>"><?php echo $checkout_button; ?></a>
									<?php 
								}
								elseif(!$current_level) 
								{ 
									?>                	
									<a class="<?php
										if($template === "genesis" || $template === "twentyfourteen") { echo "button alignright"; }
										elseif($template === "foundation") { echo "button right"; }
										elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary pull-right"; }
										elseif($template === "woothemes") { echo "woo-sc-button custom alignright pull-right"; }
										else { echo "pmpro_btn pmpro_btn-select"; if($layout == 'div' || $layout == '2col' || empty($layout)) { echo ' alignright'; }											
									} ?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id . $checkout_url_params, "https")?>"><?php echo $checkout_button; ?></a>
									<?php
								}
								elseif($current_level)
								{
									//if it's a one-time-payment level, offer a link to renew				
									if(!pmpro_isLevelRecurring($current_user->membership_level) && !empty($current_user->membership_level->enddate))
									{
										?>
										<a class="<?php
											if($template === "genesis" || $template === "twentyfourteen") { echo "button alignright"; }
											elseif($template === "foundation") { echo "button right"; }
											elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary pull-right"; }
											elseif($template === "woothemes") { echo "woo-sc-button custom alignright pull-right"; }
											else { echo "pmpro_btn pmpro_btn-select"; if($layout == 'div' || $layout == '2col' || empty($layout)) { echo ' alignright'; }											
										} ?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php echo $renew_button; ?></a>
										<?php
									}
									else
									{
										?>
										<a class="<?php
											if($template === "genesis" || $template === "twentyfourteen") { echo "button alignright"; }
											elseif($template === "foundation") { echo "button info right"; }
											elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-info pull-right"; }
											elseif($template === "woothemes") { echo "woo-sc-button silver alignright pull-right"; }
											else { echo "pmpro_btn disabled"; if($layout == 'div' || $layout == '2col' || empty($layout)) { echo ' alignright'; }
										 } ?>" href="<?php echo pmpro_url("account")?>"><?php echo $account_button; ?></a>
										<?php
									}
								} 
							?>
							
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
												<strong><?php _e('Free.', 'pmproal'); ?></strong>
												<?php
											}
											else
											{	
												?>
												<strong><?php _e('Free', 'pmproal'); ?></strong>
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
			
							<?php 
								if(!empty($expiration)) 
								{ 
									if($template === "bootstrap")
										echo '<span class="text-muted">';
									$level_expiration = pmpro_getLevelExpiration($level);
									if(empty($level_expiration))
										_e('Membership Never Expires.', 'pmproal');
									else
										echo $level_expiration;
									if($template === "bootstrap")
										echo '</span>';
								} 
							?>
							<?php if($layout == 'div' || $layout == '2col' || empty($layout) || ($template === "woothemes")) { echo '<div class="clear"></div>'; } ?>
								</div></footer> <!-- .entry-meta, .entry-footer -->
							<?php 
							} 
							else
							{
								//This is a column-type div layout
								?>
								<div<?php if($template != "twentyfourteen") { ?> class="entry-content"<?php } ?>>
									<?php
										if(!empty($show_price))
										{
											?>
											<p class="pmpro_level-price<?php if($template === "gantry" || $template === "bootstrap") { echo " lead"; } ?>">
											<?php
												if($price === 'full')
													echo pmpro_getLevelCost($level, true, false); 
												else
													echo pmpro_getLevelCost($level, false, true); 
											?>
											</p>
											<?php
										}
									?>
									
									<p class="pmpro_level-select"><?php 
										if(empty($current_user->membership_level->ID)) 
										{ 
											?>
											<a class="<?php
												if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
												elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary btn-block"; }
												elseif($template === "woothemes") { echo "woo-sc-button custom"; }
												else { echo "pmpro_btn pmpro_btn-select";
											} ?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php echo $checkout_button; ?></a>
											<?php 
										}
										elseif(!$current_level) 
										{ 
											?>                	
											<a class="<?php
												if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
												elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary btn-block"; }
												elseif($template === "woothemes") { echo "woo-sc-button custom"; }
												else { echo "pmpro_btn pmpro_btn-select";
											} ?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id . $checkout_url_params, "https")?>"><?php echo $checkout_button; ?></a>
											<?php
										}
										elseif($current_level)
										{
											//if it's a one-time-payment level, offer a link to renew				
											if(!pmpro_isLevelRecurring($current_user->membership_level) && !empty($current_user->membership_level->enddate))
											{
												?>
												<a class="<?php
													if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
													elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary btn-block"; }
													elseif($template === "woothemes") { echo "woo-sc-button custom"; }
													else { echo "pmpro_btn pmpro_btn-select";
													} ?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id . $checkout_url_params, "https")?>"><?php echo $renew_button; ?></a>
												<?php
											}
											else
											{
												?>
												<a class="<?php
													if($template === "genesis" || $template === "twentyfourteen") { echo "button"; }
													elseif($template === "foundation") { echo "button info"; }
													elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-info btn-block"; }
													elseif($template === "woothemes") { echo "woo-sc-button silver"; }
													else { echo "pmpro_btn disabled";
													} ?>" href="<?php echo pmpro_url("account")?>"><?php echo $account_button; ?></a>
												<?php
											}
										} 
									?></p>
	
									<?php 
										if(!empty($description))
										{
											if($template === "woothemes")
												echo "<hr />";
											echo wpautop($level->description); 
										}
									?>
							</div> <!-- .entry-content -->		
							<?php
								if(!empty($expiration)) 
								{ 
									echo '<footer class="';
									if($template != "twentyfourteen")
										echo 'entry-footer';
									if($template === "bootstrap")
										echo ' text-muted';
									echo ' pmpro_level-expiration">';
									$level_expiration = pmpro_getLevelExpiration($level);
									if(empty($level_expiration))
										_e('Membership Never Expires.', 'pmproal');
									else
										echo $level_expiration;
									echo '</footer>';
								} 
							?>
	
								<?php
							}
						}
					?>	
						<?php if($template === "bootstrap") { ?>
							</div><!-- .panel-body -->
						<?php } ?>
					</div></div>
				<?php
				}
			?>
			</div> <!-- #pmpro_levels, .row -->
		<?php
		} //end else if no layout specified, use 'div'
	?>
		<?php if($template === "woothemes"|| $template === "genesis") { echo '<div class="clear"></div>'; } ?>
		<?php if(!empty($back_link)) { ?>
		<nav id="nav-below" class="navigation" role="navigation">
			<div class="nav-previous alignleft">
				<?php if(!empty($current_user->membership_level->ID)) { ?>
					<a href="<?php echo pmpro_url("account")?>"><?php _e('&larr; Return to Your Account', 'pmproal');?></a>
				<?php } elseif(!is_front_page()) { ?>
					<a href="<?php echo home_url()?>"><?php _e('&larr; Return to Home', 'pmproal');?></a>
				<?php } ?>
			</div>
		</nav>	
		<?php if($template === "woothemes"|| $template === "genesis") { echo '<div class="clear"></div>'; } ?>
		<?php } ?>
	<?php
	$temp_content = ob_get_contents();
	ob_end_clean();
	return $temp_content;
}
add_shortcode("pmpro_advanced_levels", "pmpro_advanced_levels_shortcode");

/*
	take a level cost text and return a version with the span in there.
*/
function spanThePMProLevelCostText($text)
{
	//generate a pattern like $[0-9]*.[0-9][0-9]
	$pattern = str_replace("$", "\$",
				str_replace("1", "[0-9]", 
				str_replace("1.", "[0-9]*\.", 
				pmpro_formatPrice("1.11")
			)));
			
	//replace with span wrapped version
	$level_cost = preg_replace("/(" . $pattern . ")/i", "<span>$1</span>", $text);
	
	//return
	return $level_cost;
}