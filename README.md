# Continuing Education Custom Functions Plugin

This plugin provides custom functions needed for the Continuing Education website to be integrated with our main Wordpress website.

##Settings

After activation, settings for the plugin can be updated via a submenu "CE Custom Functions" under the main administrative Settings menu. Available settings:

###CampusCE settings
- **CampusCE data URL** - The URL used to retrieve CampusCE data
- **CampusCE user key** - The special user key that is required to use the data URL

##Functions

###CampusCE functions
All return a `SimpleXMLElement` object of the data directly as returned from CampusCE. No data or field manipulation is done. Use `var_dump()` to see complete object. All are static methods of the the plugin's `CE_Custom_Functions` class.

- `cecf_get_courses_by_category_id($category_id)`
- `cecf_get_category_by_id($category_id)` 
- `cecf_get_course_by_id($course_id)` 

###Example usage

_Frontend example_

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active('ce-custom-functions/ce-custom-functions.php') ) { 
		$courses = CE_Custom_Functions::cecf_get_courses_by_category_id("1882");
		$category = CE_Custom_Functions::cecf_get_category_by_id("1882");
		$course = CE_Custom_Functions::cecf_get_course_by_id("12662");
	}