<?php
/**
* This class manages the setup and saving of administrative plugin options.
* Current settings
*	- CampusCE data URL - URL to retrieve course/category data from
*	- CampusCE user key - Special key provided to CampusCE for security purposes
**/
require_once("ce-plugin-config.php");

if(!class_exists("CE_Plugin_Settings")) {
	
	class CE_Plugin_Settings { 

		private $options;	//admin settings/options variable
		
		//constructor
		function __construct() {			
			if ( is_admin() ){ // add menu and init if admin
				add_action("admin_menu", array($this, "add_menu"));
				add_action("admin_init", array($this, "admin_init"));
			}
		}
		
		//add submenu page to the Settings menu
		function add_menu() {
			add_options_page(
				CE_Plugin_Config::get_options_page_title(), 
				CE_Plugin_Config::get_options_menu_text(), 
				"manage_options", 
				CE_Plugin_Config::get_options_menu_slug(), 
				array($this,"create_settings_page"));
		}
		
		//output content of custom settings page
		function create_settings_page() {
			if(!current_user_can("manage_options")) {
        		wp_die(__("You do not have sufficient permissions to access this page."));
			}
    
			// Render the settings template
    		include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
		}
		
		//register settings for options, set fields
		function admin_init() {
			
			//register the var to hold the settings values
			register_setting( CE_Plugin_Config::get_options_group_name(), CE_Plugin_Config::get_options_var_name(), array($this, "settings_validate") );
			
			//add the settings section
			add_settings_section(CE_Plugin_Config::get_options_section_id(), CE_Plugin_Config::get_options_section_title(), "", CE_Plugin_Config::get_options_menu_slug());
			
			//add the setting fields
			add_settings_field(
				"cce-data_url", 
				"CampusCE class data URL", 
				array($this, "settings_field_input_text"), 
				CE_Plugin_Config::get_options_menu_slug(), 
				CE_Plugin_Config::get_options_section_id(),
				array( "field" => "cce-data_url") );
			add_settings_field(
				"cce-user_key", 
				"CampusCE user key", 
				array($this, "settings_field_input_text"), 
				CE_Plugin_Config::get_options_menu_slug(), 
				CE_Plugin_Config::get_options_section_id(),
				array( "field" => "cce-user_key") );
                        add_settings_field(
				"cce-post_type", 
				"CustomPress Post Type", 
				array($this, "settings_field_input_text"), 
				CE_Plugin_Config::get_options_menu_slug(), 
				CE_Plugin_Config::get_options_section_id(),
				array( "field" => "cce-post_type") );
                        add_settings_field(
				"cce-taxonomy", 
				"CustomPress Taxonomy", 
				array($this, "settings_field_input_text"), 
				CE_Plugin_Config::get_options_menu_slug(), 
				CE_Plugin_Config::get_options_section_id(),
				array( "field" => "cce-taxonomy") );
		}
		
		//callback function for outputting settings fields with text input (not textarea)
		function settings_field_input_text($args)
		{
    		// Get the field name from the $args array
    		$field = $args["field"];
			$value = "";
			$other_attrs = "";	//additional attributes to add to input element when outputting
			//field value exists so assign
			if ( isset($field) && isset($this->options) && is_array($this->options) && array_key_exists($field, $this->options) ) {
    			$value = $this->options[$field];
				if ( $field == "cce-data_url" ) {	//set additional attributes for this setting
					$other_attrs = 'type="url" size="45"';
				}
			}
    		echo sprintf('<input type="text" name="'. CE_Plugin_Config::get_options_var_name() . '[%s]" id="%s" value="%s" %s />', $field, $field, isset($value) ? esc_attr($value) : "", isset($other_attrs) ? $other_attrs : "");
		}
		
		//callback function to sanitize and validate settings input
		function settings_validate($input){
			$new_input = array();
        	if( isset( $input["cce-data_url"] ) ) {
				if ( !filter_var($input["cce-data_url"], FILTER_VALIDATE_URL) ) {	//check if valid URL
					//invalid so add settings error
					add_settings_error(
						"cce-data_url",
						"cce-data_url-error",
						__(esc_attr("The CampusCE data URL must be a valid URL.")),
						"error");
				} else {
            		$new_input["cce-data_url"] = sanitize_option("siteurl", $input["cce-data_url"]);
				}
			}
        	if( isset( $input["cce-user_key"] ) ) {
            	$new_input["cce-user_key"] = sanitize_text_field( $input["cce-user_key"] );
			}
                if( isset( $input["cce-post_type"] ) ) {
            	$new_input["cce-post_type"] = sanitize_text_field( $input["cce-post_type"] );
			}
                 if( isset( $input["cce-taxonomy"] ) ) {
            	$new_input["cce-taxonomy"] = sanitize_text_field( $input["cce-taxonomy"] );
                        }
			
        	return $new_input;
		}
		
		//static function to return the given setting/field
		public static function get_plugin_setting($field) {
			$settings = get_option( CE_Plugin_Config::get_options_var_name() );
			if ( isset($settings) ) {
				return $settings[$field];
			} else {
				return null;
			}
		}
		
		//static function to get the data url
		public static function get_data_url()
		{
			return self::get_plugin_setting("cce-data_url");
		}
		
		//static function to get the user key
		public static function get_user_key()
		{
			return self::get_plugin_setting("cce-user_key");
		}
                
                //static function to get the CE post type
		public static function get_ce_post_type()
		{
			return self::get_plugin_setting("cce-post_type");
		}
                
                //static function to get the CE taxonomy
		public static function get_ce_taxonomy()
		{
			return self::get_plugin_setting("cce-taxonomy");
		}
	}
}