<?php
/**
* This class holds the config values for the plugin. If you want to change a setting related to the inner workings, this is probably where it is.
**/
if ( ! class_exists( 'CE_Plugin_Config' ) ) {

	class CE_Plugin_Config {

		protected static $options_page_title 	= 'Continuing Education Custom Functions Plugin Options';	//plugin options page title
		protected static $options_section_title = 'CampusCE Settings';	//settings section title
		protected static $options_section_id	= 'cecf-plugin-settings-section';	//settings section id
		protected static $options_menu_text  	= 'CE Custom Functions';	//settings menu link text
		protected static $options_group_name 	= 'cecf-admin-options';	//variable name for	plugin admin options group name
		protected static $options_var_name   	= 'cecf_options';	//variable name for array of settings
		protected static $options_menu_slug  	= 'cecf-plugin'; //menu slug used to specify the show option page

		//return plugin options page title
		public static function get_options_page_title() {
			return self::$options_page_title;
		}

		//return settings section title
		public static function get_options_section_title() {
			return self::$options_section_title;
		}

		//return settings section id
		public static function get_options_section_id() {
			return self::$options_section_id;
		}

		//return settings menu link text
		public static function get_options_menu_text() {
			return self::$options_menu_text;
		}

		//return variable name for plugin admin options group name
		public static function get_options_group_name() {
			return self::$options_group_name;
		}

		//return variable name of plugin settings
		public static function get_options_var_name() {
			return self::$options_var_name;
		}

		//return menu slug used to specify the show options slug
		public static function get_options_menu_slug() {
			return self::$options_menu_slug;
		}
	}
}