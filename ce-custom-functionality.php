<?php
/*
Plugin Name: Continuing Education Custom Functionality
Plugin URI: https://github.com/BellevueCollege/ce-custom-functionality
Description: Custom functions for Continuing Education
Author: Bellevue College Integration Team
Version: 1.3 #{versionStamp}#
Author URI: http://www.bellevuecollege.edu
GitHub Plugin URI: BellevueCollege/ce-custom-functionality
*/
defined( 'ABSPATH' ) || exit;

require_once( 'ce-plugin-config.php' );
require_once( 'ce-plugin-settings.php' );
require_once( 'ce-custom-functions.php' );
require_once( 'ce-widget.php' );

class CE_Custom_Functionality {

	public function __construct() {
		add_action( 'rest_api_init' , array( $this, 'rest_register_routes') ); //initiate REST API
		add_action( 'widgets_init', function() { register_widget( 'ce_widget' ); } ); // register widget
		add_action( 'mayflower_register_sidebar', array( $this, 'ce_register_widget_area'), 10 );
		add_filter( 'mayflower_active_sidebar', array( $this, 'ce_active_widget_area'), 1, 10 );
		add_action( 'mayflower_display_sidebar', array( $this, 'ce_display_widget_area'), 10 );
	}

	/**
	 * CE Initiate REST API
	 *
	 * Register the REST routes
	 */

	public static $rest_version = '1'; //initiate version of this REST API

	//register rest endpoints and provide callback to the handler
	function rest_register_routes( ) {
		$version = self::$rest_version;
		$namespace = 'ce/v' . $version; //declares the home route of the REST API

		//registered route tells the API to respond to a given request with a callback function
		//this is one route with one endpoint method GET requesting a parameter ID on the URL
		register_rest_route( $namespace, '/courses/(?P<id>\d+)', array(
			'methods' => 'GET',
			'callback' => array( 'CE_Custom_Functions', 'cecf_rest_course_info' ),
			'args' => array(
			  'id' => array(
				'validate_callback' => function($param, $request, $key) {
				  return is_numeric( $param );
				}
			  ),
			),
		) );
	}

	/**
	 * CE Programs Widget Area
	 *
	 * Add widget area to Mayflower sidebar
	 */

	// Register new widget area
	public static function ce_register_widget_area() {
		register_sidebar( array(
			'name' => __( 'CE Programs Widget Area', 'mayflower' ),
			'id' => 'ceprograms-widget-area',
			'description' => __( 'This is the CE Programs widget area. Items will appear on CE Programs custom post type pages.', 'mayflower' ),
			'before_widget' => '<div class="wp-widget wp-widget-ceprograms %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h2 class="widget-title content-padding">',
			'after_title' => '</h2>',
		) );
	}
	
	// Filter active sidebar function to insert new sidebar
	public static function ce_active_widget_area( $active ) {
		if ( is_singular( CE_Plugin_Settings::get_ce_post_type() ) ) {
			return true;
		} elseif ( $active ) {
			return true;
		} else {
			return false;
		}
	}

	// Display new widget area
	public static function ce_display_widget_area() {
		if ( is_active_sidebar( 'ceprograms-widget-area' ) ) :
			if ( is_singular( CE_Plugin_Settings::get_ce_post_type() ) ) :
				dynamic_sidebar( 'ceprograms-widget-area' );
			endif;
		endif;
	}
}

$ce_custom_functionality = new CE_Custom_Functionality();	