<?php

require_once( 'ce-plugin-config.php' );
require_once( 'ce-plugin-settings.php' );
if ( ! class_exists( 'CE_Custom_Functions' ) ) {

	class CE_Custom_Functions {

		//get CampusCE courses by category ID
		public static function cecf_get_courses_by_category_id( $category_id ) {
			$data_url = sprintf( CE_Plugin_Settings::get_data_url() . '?CategoryID=%s', esc_attr( $category_id ) );
			$data = CE_Custom_Functions::cecf_call_data_url( $data_url );

			//loop through all returned classes to extract unique courses
			$already_processed = array();	//keep track of courses already processed
			$courses = array();	//keep track of courses to return
			$classes = $data->Class;	//get only class data
			if ( ! empty( $classes ) ) {
				foreach ( $classes as $class ) {
					if ( ! in_array( $class->CourseID, $already_processed ) ) {
						//not already processed, so create course object with relevant data and add to courses array
						$obj = new StdClass();
						$obj->CourseID = (string) $class->CourseID;
						$obj->Title = trim( (string) $class->Title);
						$obj->WebDescr = trim( (string) $class->WebDescr);
						$obj->CategoryID = (string) $class->CategoryID;
						$obj->NewTag = (string) $class->NewTag;
						$courses[] = $obj;
						$already_processed[] = ( string ) $class->CourseID;	//add courseID to list of processed courses
						//var_dump($obj);
					}
				}
			}
			//var_dump($courses);
			return $courses;
		}

		//get CampusCE classes by category ID
		public static function cecf_get_classes_by_category_id( $category_id ) {
			$data_url = sprintf( CE_Plugin_Settings::get_data_url() . '?CategoryID=%s', esc_attr( $category_id ) );
			$classes = CE_Custom_Functions::cecf_call_data_url( $data_url );
			//var_dump($classes);
			return $classes->Class;
		}

		//get CampusCE category by ID
		public static function cecf_get_category_by_id( $category_id ) {
			$data_url = sprintf( CE_Plugin_Settings::get_data_url() . '?CategoryID=%s', esc_attr( $category_id ) );

			$courses = CE_Custom_Functions::cecf_call_data_url($data_url);
			$category = null;
			if ( isset( $courses->Category ) ) {
				$category = $courses->Category;
			}

			//var_dump($category);
			return $category;
		}

		/** REMOVING for now as this call doesn't return the data as expected **/
		//get single CampusCE course by ID
		/*public static function cecf_get_course_by_id($course_id) {
			$data_url = sprintf(CE_Plugin_Settings::get_data_url() . "?CourseID=%s", esc_attr($course_id));
			$course = CE_Custom_Functions::cecf_call_data_url($data_url);
			//var_dump($course);
			return $course;
		}*/

		//call data source to return information
		private static function cecf_call_data_url( $data_url ){
			$user_key = base64_encode( CE_Plugin_Settings::get_user_key() ); //encode user key
			$output = null;

			try {
				//use cURL to contact server and get response with data
				$ch = curl_init( $data_url );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array ( 'UserKey:' . $user_key ) );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				$output = curl_exec( $ch );
				$output1 = curl_close( $ch );
				$output = simplexml_load_string( $output );
			} catch ( Exception $e ) {
				trigger_error( 'Unable to retrieve information from data source.' );
			}

			return $output;
		}

		/*
		 * cecf_rest_course_info() is a callback function passed into a registered route
		 * to send class data via REST
		 * @param WP_REST_Request $request The request object being served from the registered route
		 * @return WP_REST_Response $data in JSON, an array of courses and the corresponding category
 		*/
		public static function cecf_rest_course_info( WP_REST_Request $request) {

			$parameters = $request->get_url_params(); //get the URL parameters from the request
			$rest_url_category_ID = $parameters['id']; //get only the ID passed into URL parameters from the registered route
			
			//Verify ID format (4 numeric chars)
			if ( preg_match( '/^(\d{4})$/', $rest_url_category_ID ) ) {

				// Retrieve data from CampusCE
				$courses   = CE_Custom_Functions::cecf_get_courses_by_category_id( $rest_url_category_ID );
				$category  = CE_Custom_Functions::cecf_get_category_by_id( $rest_url_category_ID );

				// If there are courses
				if ( ! empty( $courses ) ) {
					// Truncate Description
					foreach ( $courses as $class ) {
						if ( false == empty( $class->CourseID ) ) {
							//Load title and desc in to variables, and force tags to be balanced
							$class_title = balanceTags( $class->Title, true );
							$class->Title = $class_title;
							$class_desc  = balanceTags( wp_trim_words( $class->WebDescr, 40, '...' ), true );
							$class->WebDescr = $class_desc;
						}
					}

					// Build data array from course and category arrays
					$data = array(
						'courses' => (array) $courses,
						'category' => (array) $category,
					);

					//send the data as a JSON response
					return new WP_Rest_Response($data, 200);
				} else {
					// Message if there are no classes
					echo '<p>Courses have begun. Please check back for future offerings.</p><p>Also, check out our <a href="http://www.campusce.net/BC/category/category.aspx">online catalog</a> for other offerings.</p>';
				}
			} else {
				// Return error if ID format is incorrect
				echo '<p>Error: Incorrect CampusCE ID Format</p>';
			}
			// Don't print any extra information
			die();
		}
	}
}
if ( class_exists( 'CE_Plugin_Settings' ) ) {
	//instantiate settings class
	$ce_plugin_settings = new CE_Plugin_Settings();
}
