=== Paid Memberships Pro - Advanced Levels Page Add On ===
Contributors: strangerstudios
Tags: pmpro, paid memberships pro, members, memberships, levels, templates, pricing, columns, themes
Requires at least: 5.4
Tested up to: 6.6
Stable tag: 1.2

Build a beautiful membership levels page for Paid Memberships Pro using a customizable block or shortcode.

== Description ==

An enhanced block and shortcode for customizing the display of your Membership Levels Page for Paid Memberships Pro. 

Attributes in the block settings or the [pmpro_advanced_levels] shortcode can be used to tweak how levels are displayed on the levels page, including options to display levels in a more HTML5-friendly div layout or popular column layouts.

For more information, see add on documentation:
https://www.paidmembershipspro.com/add-ons/pmpro-advanced-levels-shortcode/

== Installation ==

1. Upload the `pmpro-advanced-levels` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Replace the [pmpro_levels] shortcode on your site with the Advanced Levels Page block or [pmpro_advanced_levels] shortcode.

For more information about block settings and shortcode attributes, see the documentation here:
https://www.paidmembershipspro.com/add-ons/pmpro-advanced-levels-shortcode/

== Changelog ==
= 1.2 - 2024-07-18 =
* ENHANCEMENT: Updated the frontend UI for compatibility with PMPro v3.1. #73 (@kimcoleman)

= 1.1 - 2024-02-13 =
* BUG FIX/ENHANCEMENT: Now using an unordered-list on comparison table mobile view for better spacing between items.
* BUG FIX: Fixing bug where comparison table mobile view was showing the incorrect comparison items.
* REFACTOR: Cleaned up some code and logic around showing the "Renew" button on the levels page.

= 1.0 - 2024-01-23 =
* FEATURE: Added the Advanced Levels Block as a new way to build your levels page.
* ENHANCEMENT: Now showing a message for admins only when an included level ID does not exist.
* ENHANCEMENT: Improved styling; Column type layouts now use CSS flexbox for better appearance on all screens.
* BUG FIX/ENHANCEMENT: Fixed issues with the discount code attribute and supported levels or duplicate levels layouts.
* BUG FIX/ENHANCEMENT: Removed `template` shortcode attribute that was not fully supported by with modern theme frameworks.
* BUG FIX: Fixed an issue where the "Renew" button on the level page would not show correctly.
* BUG FIX: Fixed an issue where the "hide" price attribute wasn't working with the DIV layout.
* REFACTOR: General code cleanup and improvements.

= 0.2.6 - 2023-08-29 =
* ENHANCEMENT: Improved support for the Custom Level Cost Text Add On and free levels. (@MaximilianoRicoTabo, @andrewlimaza)
* ENHANCEMENT: Added the ability to show the level's description within the compare table layout. (@dparker1005)
* ENHANCEMENT: Improved compatibility for PHP8+. (@JarrydLong)
* BUG FIX: Fixed an issue where the discount code attribute would apply to all shortcodes on a page (if there were more than one shortcode on a single page). (@MaximilianoRicoTabo)
* BUG FIX: Fixed an issue with compare table, when no `levels` attribute were supplied in the shortcode. (@andrewlimaza)
* REFACTOR: Removed duplicate "template" attribute from shortcode. (@andrewlimaza)
* REFACTOR: Moved to using the default get_option instead of pmpro_getOption functions. (@JarrydLong)

= 0.2.5 - 2023-01-25 =
* SECURITY: Improved sanitization and escaping of strings throughout the plugin.
* ENHANCEMENT: Added support for Multiple Memberships Per User. This now shows the "Renew" button on the levels page for all active membership levels.
* BUG FIX: Fixed a warning when no attributes were set for the shortcode.

= .2.4 =
* ENHANCEMENT: Added pmproal_before_level hook to the div/column layouts.
* ENHANCEMENT: Added pmproal_after_level hook to the div/column layouts.
* ENHANCEMENT: Added pmproal_extra_cols_before_header hook to the table layout.
* ENHANCEMENT: Added pmproal_extra_cols_after_header hook to the table layout.
* ENHANCEMENT: Added pmproal_extra_cols_before_body hook to the table layout.
* ENHANCEMENT: Added pmproal_extra_cols_after_body hook to the table layout.

= .2.3 =
* BUG FIX: Make Read More text translatable
* BUG FIX: Whitescreen in Conmparison table template (Thanks, BingoTheIguana on GitHub)
* BUG FIX: Incorrect function name on init
* BUG FIX/ENHANCEMENT: Glotpress updates (pmproal -> pmpro-advanced-levels-shortcode)
* BUG FIX/ENHANCEMENT: Use standard WordPress URL builder
* BUG FIX/ENHANCEMENT: Include discount code in all checkout page destination URLs
* BUG FIX/ENHANCEMENT: Glotpress update (renamed translation files)
* BUG FIX/ENHANCEMENT: Use array and add_query_arg() for all checkout page links and include discount code if specified
* ENHANCEMENT: Add pmproal_before_template_load action

= .2.2 =
* BUG FIX: Fixed some strings that needed to be wrapped for translation.
* BUG FIX/ENHANCEMENT: Now honoring the "Disable New Signups" option on the edit membership levels page. If checked, the level will be excluded from lists generated by the shortcode.
* ENHANCEMENT: Updating stylesheet for WordPress 4.7 and new Twenty Seventeen theme support.
* ENHANCEMENT: Added Spanish translation. (Thanks, David A. Lareo)
* ENHANCEMENT: Added French translation. (Thanks, paramedicquebec on GitHub)

= .2.1 =
* ENHANCEMENT: Moving all individual layouts into separate template files.
* BUG: Fixed responsive layout for small screens when using 2, 3, or 4 columns.

= .2 =
* BUG: Fixed highlight and current level classes in compare_table layout.
* BUG: Fixed notice in compare_table layout.
* ENHANCEMENT: Added responsive support for compare_table layout. Layout now collapses to a single column div-type layout with comparison attributes for device width < 767px.

= .1.8.2 =
* Fixed navigation clearing issue for Genesis layout option.
* Added translation support to add-on and included Norwegian translation files.

= .1.8.1 =
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
* Fixed bug where discount codes were not being embedded in the checkout link URLs when added to the shortcode. (Thanks, 3fingas)

= .1.3 =
* If no specific levels are passed in the pmpro_levels_array filter is applied to the levels.

= .1.2 =
* Some more style updates.

= .1.1 =
* Fixed some PHP warnings that were affecting layout and some CSS styles were tweaked.
* Fixed enqueue of CSS to work for different plugin directory names.

= .1 =
* First version.
