=== Paid Memberships Pro - Advanced Levels Page Shortcode Add On ===
Contributors: strangerstudios
Tags: pmpro, paid memberships pro, members, memberships, levels, templates, pricing, columns, themes
Requires at least: 3.5
Tested up to: 4.1.1
Stable tag: .1.3

An enhanced shortcode for customizing the display of your Membership Levels Page for Paid Memberships Pro.

== Description ==

An enhanced shortcode for customizing the display of your Membership Levels Page for Paid Memberships Pro. 

Attributes in the [pmpro_advanced_levels] shortcode can be used to tweak how levels are displayed on the levels page, including options to display levels in a more HTML5-friendly div layout or popular column layouts.

Also includes specific styling support for StudioPress/Genesis, Woo Themes, Gantry, and Foundation based themes as well as some of the default WP themes like TwentyFourteen.

For more information, see our blog post here:
http://www.paidmembershipspro.com/2015/02/new-plugin-with-advanced-options-for-membership-levels-page-display/

== Installation ==

1. Upload the `pmpro-advanced-levels` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Replace the [pmpro_levels] shortcode on your site with a [pmpro_advanced_levels] one.

Here is an example of the pmpro_advanced_levels shortcode with all attributes.

[pmpro_advanced_levels template="genesis" levels="1,2,3" layout="table" hightlight="2" description="false" checkout_button="Register Now"]

For more information, see our blog post here:
http://www.paidmembershipspro.com/2015/02/new-plugin-with-advanced-options-for-membership-levels-page-display/

== Changelog == 
= .1.3 =
* If no specific levels are passed in the pmpro_levels_array filter is applied to the levels.

= .1.2 =
* Some more style updates.

= .1.1 =
* Fixed some PHP warnings that were affecting layout and some CSS styles were tweaked.
* Fixed enqueue of CSS to work for different plugin directory names.

= .1 =
* First version.
