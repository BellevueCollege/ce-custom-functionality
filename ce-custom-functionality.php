<?php
/*
Plugin Name: Continuing Education Custom Functionality
Plugin URI: https://github.com/BellevueCollege/
Description: Custom functions for Continuing Education
Author: Bellevue College Integration Team
Version: 1.1
Author URI: http://www.bellevuecollege.edu
GitHub Plugin URI: BellevueCollege/ce-custom-functionality
*/
defined( 'ABSPATH' ) OR exit;

require_once("ce-plugin-config.php");
require_once("ce-plugin-settings.php");
require_once("ce-custom-functions.php");
require_once ("ce-widget.php");

// register widget
add_action( 'widgets_init', create_function( '', 'register_widget( "ce_widget" );' ) );


/**
 * CE Programs Widget Area
 *
 * Add widget area to Mayflower sidebar
 */

// Register new widget area
function ce_register_widget_area() {
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
add_action( 'mayflower_register_sidebar', 'ce_register_widget_area', 10);

// Filter active sidebar function to insert new sidebar
function ce_active_widget_area( $active ) {
	if ( is_singular( CE_Plugin_Settings::get_ce_post_type() ) ) {
		return true;
	} elseif ( $active ) {
		return true;
	} else {
		return false;
	}
}
add_filter( 'mayflower_active_sidebar', 'ce_active_widget_area', 1, 10);

// Display new widget area
function ce_display_widget_area() {
	if ( is_active_sidebar( 'ceprograms-widget-area' ) ) :
		if ( is_singular( CE_Plugin_Settings::get_ce_post_type() ) ) :
			dynamic_sidebar( 'ceprograms-widget-area' );
		endif;
	endif;
}
add_action( 'mayflower_display_sidebar', 'ce_display_widget_area', 10);
