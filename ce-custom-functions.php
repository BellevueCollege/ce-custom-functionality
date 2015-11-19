<?php
require_once("ce-plugin-config.php");
require_once("ce-plugin-settings.php");
if(!class_exists('CE_Custom_Functions')) {
	
	class CE_Custom_Functions { 	

		//get CampusCE courses by category ID
		public static function cecf_get_courses_by_category_id($category_id) {
			$data_url = sprintf(CE_Plugin_Settings::get_data_url() . "?CategoryID=%s", esc_attr($category_id));
			$courses = CE_Custom_Functions::cecf_call_data_url($data_url);
			//var_dump($courses);
			return $courses;
		}
		
		//get CampusCE category by ID
		public static function cecf_get_category_by_id($category_id) {
			$data_url = sprintf(CE_Plugin_Settings::get_data_url() . "?CategoryID=%s", esc_attr($category_id));

			$courses = CE_Custom_Functions::cecf_call_data_url($data_url);
			$category = null;
			if ( isset($courses->Category) ){
				$category = $courses->Category;
			}
			
			//var_dump($category);
			return $category;
		}
		
		//get single CampusCE course by ID
		public static function cecf_get_course_by_id($course_id) {
			$data_url = sprintf(CE_Plugin_Settings::get_data_url() . "?CourseID=%s", esc_attr($course_id));
			$course = CE_Custom_Functions::cecf_call_data_url($data_url);
			//var_dump($course);
			return $course;
		}
		
		//call data source to return information
		private static function cecf_call_data_url($data_url){
			$user_key = base64_encode(CE_Plugin_Settings::get_user_key());	//encode user key
			$output = null;
			
			try {
				//use cURL to contact server and get response with data
				$ch = curl_init($data_url);
				curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('UserKey:' . $user_key));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				$output1 = curl_close($ch);
				$output = simplexml_load_string($output);
			} catch ( Exception $e ){
				trigger_error("Unable to retrieve information from data source.");
			}
			
			return $output;
		}
		
	}
}
if ( class_exists("CE_Plugin_Settings") ) {
	//instantiate settings class
	$ce_plugin_settings = new CE_Plugin_Settings();
}
?>