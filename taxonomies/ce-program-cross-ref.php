<?php
/**
 * CE Program Cross Reference Taxonomy
 *
 * @package cecf
 * @since 2.0
 */

/**
 * Registers the `ce_program_cross_ref` taxonomy,
 * for use with 'ceprograms'.
 */
function ce_program_cross_ref_init() {
	register_taxonomy(
		'ce_program_cross_ref',
		array( 'ceprograms', 'post' ),
		array(
			'hierarchical'          => true,
			'public'                => true,
			'show_in_nav_menus'     => true,
			'show_ui'               => true,
			'show_admin_column'     => false,
			'query_var'             => true,
			'rewrite'               => true,
			'capabilities'          => array(
				'manage_terms' => 'edit_posts',
				'edit_terms'   => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			),
			'labels'                => array(
				'name'                       => __( 'Program / Post Cross Reference', 'cecf' ),
				'singular_name'              => _x( 'Cross Reference', 'taxonomy general name', 'cecf' ),
				'search_items'               => __( 'Search Cross References', 'cecf' ),
				'popular_items'              => __( 'Popular Cross References', 'cecf' ),
				'all_items'                  => __( 'All Cross References', 'cecf' ),
				'parent_item'                => __( 'Parent Cross Reference', 'cecf' ),
				'parent_item_colon'          => __( 'Parent Cross Reference:', 'cecf' ),
				'edit_item'                  => __( 'Edit Cross Reference', 'cecf' ),
				'update_item'                => __( 'Update Cross Reference', 'cecf' ),
				'view_item'                  => __( 'View Cross Reference', 'cecf' ),
				'add_new_item'               => __( 'Add New Cross Reference', 'cecf' ),
				'new_item_name'              => __( 'New Cross Reference', 'cecf' ),
				'separate_items_with_commas' => __( 'Separate Cross References with commas', 'cecf' ),
				'add_or_remove_items'        => __( 'Add or remove Cross References', 'cecf' ),
				'choose_from_most_used'      => __( 'Choose from the most used Cross References', 'cecf' ),
				'not_found'                  => __( 'No Cross References found.', 'cecf' ),
				'no_terms'                   => __( 'No Cross References', 'cecf' ),
				'menu_name'                  => __( 'Cross References', 'cecf' ),
				'items_list_navigation'      => __( 'Cross References list navigation', 'cecf' ),
				'items_list'                 => __( 'Cross References list', 'cecf' ),
				'most_used'                  => _x( 'Most Used', 'ce_program_cross_ref', 'cecf' ),
				'back_to_items'              => __( '&larr; Back to Cross References', 'cecf' ),
			),
			'show_in_rest'          => true,
			'rest_base'             => 'ce_program_cross_ref',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'rewrite'               => array( 'slug' => 'cross-ref' ),
		)
	);

}
add_action( 'init', 'ce_program_cross_ref_init' );

/**
 * Sets the post updated messages for the `ce_program_cross_ref` taxonomy.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `ce_program_cross_ref` taxonomy.
 */
function ce_program_cross_ref_updated_messages( $messages ) {

	$messages['ce_program_cross_ref'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Cross Reference added.', 'cecf' ),
		2 => __( 'Cross Reference deleted.', 'cecf' ),
		3 => __( 'Cross Reference updated.', 'cecf' ),
		4 => __( 'Cross Reference not added.', 'cecf' ),
		5 => __( 'Cross Reference not updated.', 'cecf' ),
		6 => __( 'Cross References deleted.', 'cecf' ),
	);

	return $messages;
}
add_filter( 'term_updated_messages', 'ce_program_cross_ref_updated_messages' );
