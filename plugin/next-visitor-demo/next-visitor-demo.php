<?php
/**
 * Plugin Name:       Next Visitor Demo
 * Description:       Demo code for the talk "Your next visitor isn't human". Registers a read-only Abilities API ability by default. Other modules are opt-in.
 * Version:           1.0.0
 * Requires at least: 6.9
 * Requires PHP:      7.4
 * License:           GPL-2.0-or-later
 * Text Domain:       acme
 *
 * Modules (set in wp-config.php to enable others):
 *   define( 'NVD_MODULES', array( 'abilities-search', 'json-ld', 'markdown', 'robots', 'abilities-draft' ) );
 * Or use the 'nvd_modules' filter.
 *
 * Default: abilities-search only. It is read-only and exposes published posts.
 */

defined( 'ABSPATH' ) || exit;

define( 'NVD_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Modules that are on when nothing is configured.
 */
function nvd_default_modules() {
	return array( 'abilities-search' );
}

function nvd_active_modules() {
	$modules = defined( 'NVD_MODULES' ) ? (array) NVD_MODULES : nvd_default_modules();
	return (array) apply_filters( 'nvd_modules', $modules );
}

add_action( 'plugins_loaded', function () {
	$known = array( 'abilities-search', 'abilities-draft', 'json-ld', 'markdown', 'robots' );

	if ( file_exists( NVD_DIR . 'vendor/autoload.php' ) ) {
		require_once NVD_DIR . 'vendor/autoload.php';
	}

	foreach ( nvd_active_modules() as $module ) {
		if ( ! in_array( $module, $known, true ) ) {
			continue;
		}
		// The Markdown module needs league/html-to-markdown. Skip it if missing.
		if ( 'markdown' === $module && ! class_exists( 'League\HTMLToMarkdown\HtmlConverter' ) ) {
			add_action( 'admin_notices', function () {
				echo '<div class="notice notice-warning"><p>Next Visitor Demo: the markdown module needs <code>composer require league/html-to-markdown</code> inside the plugin folder.</p></div>';
			} );
			continue;
		}
		require_once NVD_DIR . 'modules/' . $module . '.php';
	}
} );

// Shared category for the ability modules.
add_action( 'wp_abilities_api_categories_init', function () {
	wp_register_ability_category( 'content', array(
		'label'       => __( 'Content', 'acme' ),
		'description' => __( 'Read site content.', 'acme' ),
	) );
} );

add_action( 'admin_notices', function () {
	if ( function_exists( 'wp_register_ability' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>Next Visitor Demo needs WordPress 6.9 or later for the Abilities API. Abilities modules are inactive until then.</p></div>';
} );
