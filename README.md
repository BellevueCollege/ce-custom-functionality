# Continuing Education Custom Functions Plugin

This plugin provides custom functions needed for the Continuing Education website to be integrated with our main WordPress website. It also provides a widget that can be used to display posts linked to a CustomPress custom post type using CustomPress taxonomy.

## Settings

After activation, settings for the plugin can be updated via a submenu "CE Custom Functions" under the main administrative Settings menu. Available settings:

### CampusCE settings
- **CampusCE data URL** - The URL used to retrieve CampusCE data
- **CampusCE user key** - The special user key that is required to use the data URL
- **CustomPress post type** - The post type created in CustomPress plugin.
- **CustomPress taxonomy** - The taxonomy created in CustomPress plugin.
- **CustomPress field ID** - The field ID created in CustomPress plugin.

## Functions

### CampusCE functions
All return an object generated from the data XML as returned from CampusCE. All are static methods of the plugin's `CE_Custom_Functions` class.

> Note: The functions now distinguish between courses and classes. Per the data, classes are an offering of a course. As such, pulling classes will return all available offerings of a course.

- `cecf_get_courses_by_category_id($category_id)`

	Returns array of course objects. Course object contains `CourseID`, `Title`, `WebDescr`, `CategoryID`, and `NewTag` data members.

- `cecf_get_classes_by_category_id($category_id)`

	Returns array of classes. Class data is directly as returned from CampusCE.
	
- `cecf_get_category_by_id($category_id)` 

	Returns category object. Category data is directly as returned from CampusCE.
	
- <del>cecf_get_course_by_id($course_id)</del> 

	Removed as did not return expected results.

### Example usage

_Frontend PHP example_
```PHP
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active('ce-custom-functions/ce-custom-functionality.php') ) { 
	$courses = CE_Custom_Functions::cecf_get_courses_by_category_id("1882");
	$category = CE_Custom_Functions::cecf_get_category_by_id("1882");
	$classes = CE_Custom_Functions::cecf_get_classes_by_category_id("1882");
}
```
_Frontend AJAX example_
```JavaScript
<script type="text/javascript">
	jQuery( document ).ready( function( ) {
		// Get data from WordPress
		jQuery.post (
			"<?php echo admin_url( 'admin-ajax.php' ); ?>",
			{
				action:   'cecf_ajax_get_data',
				catid:    'CAMPUSCE CATGEORY ID',
			},
			function( campusce_data ) {
				var data = campusce_data; // Data from CampusCE
				var output = '';

				if ( typeof data == 'object' ) { // verify data is JSON
					jQuery.each( data.courses, function( i, course ) {
						// Set Variables
						var title = course.Title;
						var descr = course.WebDescr;
						output += title + ' ' + descr;
					});
				} else { // if non - JSON data is returned
					output += data;
				}
				// Output
				jQuery("#response_area_id").html( output );
			}
		);
	});
</script>
```

## Widget

### Acceptance criteria
* Admin should be able to create a cross-reference between blog categories and class categories
* Blog titles from applicable category should display on sidebar
* Should link to full story in blog section
* Should be link to category page for more stories
