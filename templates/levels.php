<?php
/*
	Based on the pmpro_levels shortcode bundled in the Paid Memberships Pro plugin.
	
	This shortcode will display the membership levels and additional content based on the defined attributes.
*/
function pmpro_advanced_levels_shortcode($atts, $content=null, $code="")
{
    global $pmproal_link_arguments;
    
	// $atts    ::= array of attributes
	// $content ::= text within enclosing form of shortcode element
	// $code    ::= the shortcode found, when == callback name
	// examples: [pmpro_advanced_levels template="genesis" levels="1,2,3" layout="table" hightlight="2" description="false" checkout_button="Register Now"]
	
	extract(shortcode_atts(array(
		'account_button' => __('Your&nbsp;Level', 'pmpro-advanced-levels-shortcode'),
		'back_link' => '1',
		'compare' => NULL,
		'template' => NULL,
		'checkout_button' => __('Select', 'pmpro-advanced-levels-shortcode'),
		'description' => '1',
		'discount_code' => NULL,
		'expiration' => '1',
		'highlight' => NULL,
		'layout' => 'div',
		'levels' => NULL,		
		'more_button' => NULL,
		'price' => 'short',
		'renew_button' => __('Renew', 'pmpro-advanced-levels-shortcode'),
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
		$more_button = __( "Read More", "pmpro-advanced-levels-shortcode" );
		
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
					if($level->id == $level_id && true == $level->allow_signups)
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
					$pmproal_link_arguments['discount_code'] = $discount_code;
					
					$sqlQuery = "SELECT l.id, cl.*, l.name, l.description, l.allow_signups FROM $wpdb->pmpro_discount_codes_levels cl LEFT JOIN $wpdb->pmpro_membership_levels l ON cl.level_id = l.id LEFT JOIN $wpdb->pmpro_discount_codes dc ON dc.id = cl.code_id WHERE dc.code = '" . $discount_code . "' AND cl.level_id = '" . (int)$level->id . "' LIMIT 1";
					$pmpro_levels_filtered[$level_id] = $wpdb->get_row($sqlQuery);
					$pmpro_levels_filtered[$level_id]->base_level = $level;
     
				}
			}		
		}
	
		do_action('pmproal_before_template_load' );
		
		if($layout == 'table')
		{
			//load template for layout = "table"
   
			include("levels-table.php");
		}
		elseif($layout == 'compare_table')
		{
			//load template for layout = "compare_table"
			include("levels-compare_table.php");
		}
				else
				{				  
			//load template for layout = "div", "2col", "3col", "4col" or unspecified layout attribute
			include("levels-div.php");
						} 
					?>
		<?php if($template === "woothemes"|| $template === "genesis") { echo '<div class="clear"></div>'; } ?>
		<?php if(!empty($back_link)) { ?>
		<nav id="nav-below" class="navigation" role="navigation">
			<div class="nav-previous alignleft">
				<?php if(!empty($current_user->membership_level->ID)) { ?>
					<a href="<?php echo pmpro_url("account")?>"><?php _e('&larr; Return to Your Account', 'pmpro-advanced-levels-shortcode');?></a>
				<?php } elseif(!is_front_page()) { ?>
					<a href="<?php echo home_url()?>"><?php _e('&larr; Return to Home', 'pmpro-advanced-levels-shortcode');?></a>
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
add_shortcode("memberlite_levels", "pmpro_advanced_levels_shortcode");

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
