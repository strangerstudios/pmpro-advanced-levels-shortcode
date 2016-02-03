=== Paid Memberships Pro - Advanced Levels Page Shortcode Add On ===
Contributors: strangerstudios
Tags: pmpro, paid memberships pro, members, memberships, levels, templates, pricing, columns, themes
Requires at least: 3.5
Tested up to: 4.4.2
Stable tag: .2

An enhanced shortcode for customizing the display of your Membership Levels Page for Paid Memberships Pro.

== Description ==

An enhanced shortcode for customizing the display of your Membership Levels Page for Paid Memberships Pro. 

Attributes in the [pmpro_advanced_levels] shortcode can be used to tweak how levels are displayed on the levels page, including options to display levels in a more HTML5-friendly div layout or popular column layouts.

Also includes specific styling support for Bootstrap v3+, StudioPress/Genesis, Woo Themes, Gantry, and Foundation based themes as well as some of the default WP themes like TwentyFourteen.

For more information, see add on documentation:
http://www.paidmembershipspro.com/add-ons/plus-add-ons/pmpro-advanced-levels-shortcode/

== Installation ==

1. Upload the `pmpro-advanced-levels` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Replace the [pmpro_levels] shortcode on your site with a [pmpro_advanced_levels] one.

Here is an example of the pmpro_advanced_levels shortcode with all attributes.

[pmpro_advanced_levels template="genesis" levels="1,2,3" layout="table" hightlight="2" description="false" checkout_button="Register Now"]

For more information, see our blog post here:
http://www.paidmembershipspro.com/2015/02/new-plugin-with-advanced-options-for-membership-levels-page-display/

== Changelog == 
= .2
* BUG: Fixed highlight and current level classes in compare_table layout.
* BUG: Fixed notice in compare_table layout.
* ENHANCEMENT: Added responsive support for compare_table layout. Layout now collapses to a single column div-type layout with comparison attributes for device width < 767px.

= .1.8.2
* Fixed navigation clearing issue for Genesis layout option.
* Added translation support to add-on and included Norwegian translation files.

= .1.8.1
* Fixed bug if first level column of compare_table was highlight.

= .1.8 =
* Added support for Levels Comparison Table layout type (ex. layout="compare_table").
* Added shortcode attribute for renew_button text.

= .1.7 =
* Added support for Bootstrap v3+ based themes.

= .1.6 =
* Fixed bug where the current level CSS class wasn't always set correctly on elements.

= .1.5 =
* Now applying the pmpro_levels_array filter to the array of levels when the levels are specified in a shortcode attribute. If you are using the pmpro_advanced_levels shortcode AND the pmpro_levels_array filter already, make sure your filter is programmed to account for this (perhaps by checking the globsl $post->ID to only run on certain pages/et). (Thanks, Camouyer)

= .1.4 =
* Fixed bug where discount codes were not being embedded in the checkout link URLs when added to the shortcake. (Thanks, 3fingas)

= .1.3 =
* If no specific levels are passed in the pmpro_levels_array filter is applied to the levels.

= .1.2 =
* Some more style updates.

= .1.1 =
* Fixed some PHP warnings that were affecting layout and some CSS styles were tweaked.
* Fixed enqueue of CSS to work for different plugin directory names.

= .1 =
* First version.