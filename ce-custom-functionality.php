<?php
/*
Plugin Name: Continuing Education Custom Functionality
Plugin URI: https://github.com/BellevueCollege/
Description: Custom functions for Continuing Education
Author: Bellevue College Integration Team
Version: 0.0.0.1
Author URI: http://www.bellevuecollege.edu
GitHub Plugin URI: BellevueCollege/ce-custom-functions
*/
defined( 'ABSPATH' ) OR exit;

require_once("ce-plugin-config.php");
require_once("ce-plugin-settings.php");
require_once("ce-custom-functions.php");
require_once ("ce-widget.php");

// register widget
add_action( 'widgets_init', create_function( '', 'register_widget( "ce_widget" );' ) );

