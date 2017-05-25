<?php
/*
	template for layout= "div" or "2col" or "3col" or "4col"
*/
global $pmproal_link_arguments;
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
	    $pmproal_link_arguments['level'] = $level->id;
		$count++;				
		if(isset($current_user->membership_level->ID))
		  $current_level = ($current_user->membership_level->ID == $level->id);
		else
		  $current_level = false;
	?>
	<div id="pmpro_level-<?php echo $level->id; ?>" class="pmpro_level <?php 
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
			small-12 medium-<?php
			if($layout == '2col') { echo '6'; }
			elseif($layout == '3col') { echo '4 text-center'; }
			elseif($layout == '4col') { echo '3 text-center'; } 
			else { if(count($pmpro_levels_filtered) > 1) { echo '12'; } } 
		?> columns
		<?php
		}
	?>">
	<div class="<?php if($layout != "4col" || $layout != "3col") { echo "entry "; } if($template != "bootstrap") { echo " post "; } ?><?php if($current_level) { echo "pmpro_level-current "; } if($highlight == $level->id) { echo "pmpro_level-highlight "; } if($template === "foundation" && ($layout === "2col" || $layout === "div" || empty($layout))) { echo " panel"; } if($template === "gantry") { echo " well"; } if($template === "bootstrap") { echo " panel panel-default"; } ?>"<?php if($template === "foundation" && $layout === "2col") { echo " data-equalizer-watch"; } ?>>
	<?php do_action('pmproal_before_level', $level->id, $layout); ?>
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
							_e('Membership Never Expires.', 'pmpro-advanced-levels-shortcode');
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
						?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") );?>"><?php echo $checkout_button; ?></a>
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
						?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ); ?>"><?php echo $checkout_button; ?></a>
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
						} ?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ); ?>"><?php echo $checkout_button; ?></a>
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
						} ?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ); ?>"><?php echo $checkout_button; ?></a>
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
							} ?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ); ?>"><?php echo $renew_button; ?></a>
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

				<?php 
					if(!empty($expiration)) 
					{ 
						if($template === "bootstrap")
							echo '<span class="text-muted">';
						$level_expiration = pmpro_getLevelExpiration($level);
						if(empty($level_expiration))
							_e('Membership Never Expires.', 'pmpro-advanced-levels-shortcode');
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
								} ?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ); ?>"><?php echo $checkout_button; ?></a>
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
								} ?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ); ?>"><?php echo $checkout_button; ?></a>
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
										} ?>" href="<?php echo add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ); ?>"><?php echo $renew_button; ?></a>
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
							_e('Membership Never Expires.', 'pmpro-advanced-levels-shortcode');
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
		</div><!-- .entry -->
		<?php do_action('pmproal_after_level', $level->id, $layout); ?>
	</div><!-- .pmpro_level -->
	<?php
	}
?>
</div> <!-- #pmpro_levels, .row -->