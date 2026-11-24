<?php
/**
 * Serve a Markdown version of a post when the client asks for it.
 * Requires: composer require league/html-to-markdown
 *
 * Test: curl -H "Accept: text/markdown" https://example.com/my-post/
 */
add_action( 'template_redirect', function () {
	if ( ! is_singular() ) {
		return;
	}

	// Tell caches the response depends on the Accept header.
	header( 'Vary: Accept', false );

	$accept = $_SERVER['HTTP_ACCEPT'] ?? '';
	if ( false === strpos( $accept, 'text/markdown' ) ) {
		return;
	}

	$post = get_queried_object();
	if ( 'publish' !== $post->post_status || post_password_required( $post ) ) {
		return;
	}

	$html = apply_filters( 'the_content', $post->post_content );
	$md   = ( new League\HTMLToMarkdown\HtmlConverter( array( 'strip_tags' => true ) ) )->convert( $html );

	header( 'Content-Type: text/markdown; charset=utf-8' );
	echo '# ' . get_the_title( $post ) . "\n\n" . $md;
	exit;
} );
