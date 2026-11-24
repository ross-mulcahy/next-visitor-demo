<?php
/**
 * A read-only ability: search published posts.
 * Needs WordPress 6.9 or later (Abilities API).
 * Add the MCP Adapter plugin to expose it to external agents.
 */

add_action( 'wp_abilities_api_init', function () {
	wp_register_ability( 'acme/search-posts', array(
		'label'         => __( 'Search posts', 'acme' ),
		'description'   => __( 'Search published posts by keyword. Returns title, URL and excerpt.', 'acme' ),
		'category'      => 'content',
		'input_schema'  => array(
			'type'                 => 'object',
			'properties'           => array(
				'query' => array( 'type' => 'string', 'minLength' => 2 ),
				'limit' => array( 'type' => 'integer', 'minimum' => 1, 'maximum' => 10, 'default' => 5 ),
			),
			'required'             => array( 'query' ),
			'additionalProperties' => false,
		),
		'output_schema' => array(
			'type'  => 'array',
			'items' => array(
				'type'       => 'object',
				'properties' => array(
					'title'   => array( 'type' => 'string' ),
					'url'     => array( 'type' => 'string', 'format' => 'uri' ),
					'excerpt' => array( 'type' => 'string' ),
				),
			),
		),

		'execute_callback'    => function ( $input ) {
			$query = new WP_Query( array(
				's'              => $input['query'],
				'posts_per_page' => $input['limit'] ?? 5,
				'post_status'    => 'publish',
				'has_password'   => false,
			) );

			return array_map( function ( $post ) {
				return array(
					'title'   => get_the_title( $post ),
					'url'     => get_permalink( $post ),
					'excerpt' => wp_strip_all_tags( get_the_excerpt( $post ) ),
				);
			}, $query->posts );
		},

		'permission_callback' => '__return_true',
		'meta'                => array(
			'show_in_rest' => true,
			'annotations'  => array( 'readonly' => true ),
			'mcp'          => array( 'public' => true ),
		),
	) );
} );
