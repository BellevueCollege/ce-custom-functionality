<?php
/*
Plugin Name: Continuing Education Custom Functions
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


// Construct CE widget to display posts associated to custom post type.
if(!class_exists('Ce_Widget')) {   
    class Ce_Widget extends WP_Widget {
        //Construct Widget//
        function __construct() {
                parent::__construct(
                        // Base ID of your widget
                        'ce_widget',
                        // Widget name will appear in UI
                        __('CE Posts Widget', 'wp_widget_plugin'),
                        // Widget description
                        array( 'description' => __( 'Show your Bellevue College CE information!', 'wp_widget_plugin' ), )
                );
        }

        // widget form creation
        function form($instance) {
            // Check values
            if( $instance) {
                $ce_widget_title = isset($instance['ce_widget_title']) ? esc_attr($instance['ce_widget_title']) : "";           
            } else {
                $ce_widget_title = 'CE Posts';
            }
            if( $instance) {
                $ce_no_of_posts = isset($instance['ce_no_of_posts']) ? esc_attr($instance['ce_no_of_posts']) : "";           
            } else {
                $ce_no_of_posts = 3;
            }
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('ce_widget_title'); ?>"><?php _e('CE Widget Title:', 'wp_widget_plugin'); ?></label>
                <input id="<?php echo $this->get_field_id('ce_widget_title'); ?>" class="widefat" name="<?php echo $this->get_field_name('ce_widget_title'); ?>" type="text" value="<?php echo $ce_widget_title; ?>" />
            </p> 
            <p>
                <label for="<?php echo $this->get_field_id('ce_no_of_posts'); ?>"><?php _e('Number of posts to show:', 'wp_widget_plugin'); ?></label>
                <input id="<?php echo $this->get_field_id('ce_no_of_posts'); ?>"  name="<?php echo $this->get_field_name('ce_no_of_posts'); ?>" type="text" value="<?php echo $ce_no_of_posts; ?>" />
            </p> 
        <?php
        }

        // update widget
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            // Fields
            $instance['ce_widget_title'] = strip_tags($new_instance['ce_widget_title']);
            $instance['ce_no_of_posts'] = strip_tags($new_instance['ce_no_of_posts']);
            return $instance;
        }
        // display widget
        function widget($args, $instance) {
            $post_type = get_post_type();
            $custompress_post_type = CE_Plugin_Settings::get_ce_post_type();           
            if(isset($post_type) && isset($custompress_post_type) && $post_type == $custompress_post_type)
            {
                extract( $args );
                // these are the widget options
                
                $ce_widget_title = apply_filters('widget_title', $instance['ce_widget_title']);
                $ce_no_of_posts = $instance['ce_no_of_posts'];
               //echo $ce_no_of_posts;
                
            $custom_post_id = get_the_ID();            
            if(!$custom_post_id)
                die("Posts cannot be displayed");
            $custompress_taxonomy = CE_Plugin_Settings::get_ce_taxonomy();
            //echo $custompress_taxonomy;
            $terms_for_custom_post = wp_get_post_terms( $custom_post_id, $custompress_taxonomy);             
            if(isset($terms_for_custom_post) && !empty($terms_for_custom_post))
            {               
                 // Display the widget
                $content = "";              
                $content .= '<div class="wp-widget wp-widget-global widget_recent_entries">';
        
                // Check if contact title is set
                if ( $ce_widget_title ) {                    
                    $content .= "<h2 class='widget-title content-padding'>"  . $ce_widget_title ."</h2>";
                }
                $content .= "<ul class='latest-posts'>";
                $the_query = new WP_Query(
                                array(
                                    'post_type' => 'post',
                                    'orderby'   => 'date',
                                    'order'     => 'DESC',
                            ));
                $all_posts = $the_query->get_posts();
             //Display posts 
                $count_matched_posts = 0;
                
                foreach($all_posts as $post) {
                    //var_dump($post);
                    $post_terms = wp_get_post_terms( $post->ID, $custompress_taxonomy); 
                        for($i=0;$i<count($post_terms);$i++)
                        {
                            for($j=0;$j<count($terms_for_custom_post);$j++)
                            {
                                if(isset($post_terms[$i]->term_id) && isset($terms_for_custom_post[$j]->term_id))
                                {
                                   if($post_terms[$i]->term_id == $terms_for_custom_post[$j]->term_id) 
                                   {
                                        $link = get_permalink($post->ID);
                                        $title = $post->post_title;                                                  
                                        $content .= "<li><a href='$link' target='_top'>$title </a </li>\n";
                                        $count_matched_posts++;
                                        //$content .= "<p class='excerpt'>" . get_the_excerpt() . "</p>";
                                   }                                  
                                }                                 
                                 if($count_matched_posts >= $ce_no_of_posts)
                                       break 3;
                            }
                           
                        }// end of for                       
                    }// end of foreach
                 $content .= "</ul> </div>";      
                 wp_reset_query(); 
                //endif;
                 if($count_matched_posts !=0)
                        echo $content;
            }
        ?>
           
            <?php
            //echo $after_widget;
          } // end of post_type if statement
        } // end of function widget()
    } // end of class Ce_Widget 
 }// end of if  class_exists('Ce_Widget') 

// register widget
add_action( 'widgets_init', create_function( '', 'register_widget( "ce_widget" );' ) );



?>