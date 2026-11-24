<?php
/**
 * Add a training opt-out to the robots.txt WordPress generates.
 * Search and answer bots are left alone, so you stay visible in AI answers.
 */
add_filter( 'robots_txt', function ( $output, $is_public ) {
	if ( ! $is_public ) {
		return $output;
	}

	$training_bots = array(
		'GPTBot',
		'ClaudeBot',
		'CCBot',
		'Google-Extended',
		'Applebot-Extended',
	);

	foreach ( $training_bots as $bot ) {
		$output .= "\nUser-agent: {$bot}\nDisallow: /\n";
	}

	return $output;
}, 10, 2 );
