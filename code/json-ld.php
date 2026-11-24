<?php
/**
 * Print Article structured data from the post itself.
 * Only mark up what is visible on the page.
 */
add_action( 'wp_head', function () {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$post = get_queried_object();
	$data = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => get_the_title( $post ),
		'datePublished'    => get_the_date( 'c', $post ),
		'dateModified'     => get_the_modified_date( 'c', $post ),
		'mainEntityOfPage' => get_permalink( $post ),
		'author'           => array(
			'@type' => 'Person',
			'name'  => get_the_author_meta( 'display_name', $post->post_author ),
		),
	);
	echo '<script type="application/ld+json">'
		. wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG )
		. '</script>';
} );
