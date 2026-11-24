=== Next Visitor Demo ===
Requires at least: 6.9
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later

Demo code for the talk "Your next visitor isn't human".

== Description ==

Registers `acme/search-posts`, a read-only ability that searches published posts and returns title, URL and excerpt. Add the MCP Adapter plugin to expose it to MCP clients.

Optional modules, enabled in wp-config.php:

    define( 'NVD_MODULES', array( 'abilities-search', 'json-ld', 'markdown', 'robots' ) );

* abilities-search: read-only search ability (default)
* abilities-draft: creates drafts only, never publishes. Needs edit_posts.
* json-ld: Article structured data on single posts. Skip if an SEO plugin already outputs schema.
* markdown: serves Markdown for Accept: text/markdown. Run `composer require league/html-to-markdown` in the plugin folder first.
* robots: appends Disallow rules for training bots (GPTBot, ClaudeBot, CCBot, Google-Extended, Applebot-Extended) to robots.txt.

== Testing ==

1. Activate on WordPress 6.9+ with the MCP Adapter plugin.
2. Create a low-privilege user with an application password.
3. GET /wp-json/wp-abilities/v1/abilities and look for acme/search-posts.
4. Check the exact run route on your site before the talk.

== Security ==

The search ability is public by design and returns published, non-password-protected posts only. Review before using on a production site.
