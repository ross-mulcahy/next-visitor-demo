<?php
/**
 * A write ability that stays safe: it can only create a draft.
 * A human still reviews and publishes.
 */
add_action( 'wp_abilities_api_init', function () {
	wp_register_ability( 'acme/create-draft', array(
		'label'               => __( 'Create draft', 'acme' ),
		'description'         => __( 'Create a draft post. Does not publish.', 'acme' ),
		'category'            => 'content',
		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'title'   => array( 'type' => 'string', 'minLength' => 3, 'maxLength' => 120 ),
				'content' => array( 'type' => 'string', 'maxLength' => 20000 ),
			),
			'required'             => array( 'title' ),
			'additionalProperties' => false,
		),
		'output_schema'       => array(
			'type'       => 'object',
			'properties' => array(
				'id'       => array( 'type' => 'integer' ),
				'edit_url' => array( 'type' => 'string' ),
			),
		),
		'execute_callback'    => function ( $input ) {
			$id = wp_insert_post( array(
				'post_title'   => sanitize_text_field( $input['title'] ),
				'post_content' => wp_kses_post( $input['content'] ?? '' ),
				'post_status'  => 'draft',
			), true );

			if ( is_wp_error( $id ) ) {
				return $id;
			}
			return array( 'id' => $id, 'edit_url' => get_edit_post_link( $id, 'raw' ) );
		},
		'permission_callback' => function () {
			return current_user_can( 'edit_posts' );
		},
		'meta'                => array(
			'show_in_rest' => true,
			'annotations'  => array( 'readonly' => false, 'destructive' => false, 'idempotent' => false ),
			'mcp'          => array( 'public' => true ),
		),
	) );
} );
