<?php
/**
 * CE Programs Post Type
 *
 * @package cecf
 * @since 2.0
 */

/**
 * Registers the `ceprograms` post type.
 */
function ceprograms_init() {
	register_post_type(
		'ceprograms',
		array(
			'labels'                => array(
				'name'                  => __( 'Program Areas', 'cecf' ),
				'singular_name'         => __( 'Program Areas', 'cecf' ),
				'all_items'             => __( 'All Program Areas', 'cecf' ),
				'archives'              => __( 'Program Areas Archives', 'cecf' ),
				'attributes'            => __( 'Program Areas Attributes', 'cecf' ),
				'insert_into_item'      => __( 'Insert into Program Areas', 'cecf' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Program Areas', 'cecf' ),
				'featured_image'        => _x( 'Featured Image', 'ceprograms', 'cecf' ),
				'set_featured_image'    => _x( 'Set featured image', 'ceprograms', 'cecf' ),
				'remove_featured_image' => _x( 'Remove featured image', 'ceprograms', 'cecf' ),
				'use_featured_image'    => _x( 'Use as featured image', 'ceprograms', 'cecf' ),
				'filter_items_list'     => __( 'Filter Program Areas list', 'cecf' ),
				'items_list_navigation' => __( 'Program Areas list navigation', 'cecf' ),
				'items_list'            => __( 'Program Areas list', 'cecf' ),
				'new_item'              => __( 'New Program Areas', 'cecf' ),
				'add_new'               => __( 'Add New', 'cecf' ),
				'add_new_item'          => __( 'Add New Program Areas', 'cecf' ),
				'edit_item'             => __( 'Edit Program Areas', 'cecf' ),
				'view_item'             => __( 'View Program Areas', 'cecf' ),
				'view_items'            => __( 'View Program Areas', 'cecf' ),
				'search_items'          => __( 'Search Program Areas', 'cecf' ),
				'not_found'             => __( 'No Program Areas found', 'cecf' ),
				'not_found_in_trash'    => __( 'No Program Areas found in trash', 'cecf' ),
				'parent_item_colon'     => __( 'Parent Program Areas:', 'cecf' ),
				'menu_name'             => __( 'Program Areas', 'cecf' ),
			),
			'public'                => true,
			'hierarchical'          => true,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => array( 'title', 'editor', 'author', 'excerpt', 'revisions', 'page-attributes' ),
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-cloud',
			'show_in_rest'          => true,
			'rest_base'             => 'ceprograms',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rewrite'               => array( 'slug' => 'programs' ),
		)
	);

}
add_action( 'init', 'ceprograms_init' );

/**
 * Sets the post updated messages for the `ceprograms` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `ceprograms` post type.
 */
function ceprograms_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['ceprograms'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Program Areas updated. <a target="_blank" href="%s">View Program Areas</a>', 'cecf' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'cecf' ),
		3  => __( 'Custom field deleted.', 'cecf' ),
		4  => __( 'Program Areas updated.', 'cecf' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Program Areas restored to revision from %s', 'cecf' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Program Areas published. <a href="%s">View Program Areas</a>', 'cecf' ), esc_url( $permalink ) ),
		7  => __( 'Program Areas saved.', 'cecf' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Program Areas submitted. <a target="_blank" href="%s">Preview Program Areas</a>', 'cecf' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf(
			// Translators: $1 is time, other is link.
			__( 'Program Areas scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Program Areas</a>', 'cecf' ),
			date_i18n( __( 'M j, Y @ G:i', 'cecf' ), strtotime( $post->post_date ) ),
			esc_url( $permalink )
		),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Program Areas draft updated. <a target="_blank" href="%s">Preview Program Areas</a>', 'cecf' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'ceprograms_updated_messages' );
