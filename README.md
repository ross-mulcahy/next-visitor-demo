# Your next visitor isn't human

Code from a talk at a WordPress meetup, 25 November 2026. The talk covers practical steps for sites that now get visits from AI crawlers, answer engines and agents.

Everything here is demo code. Read it and test it on a staging site before you use it anywhere that matters.

## What is in here

| Path | What it is |
|---|---|
| `plugin/next-visitor-demo/` | A WordPress plugin that bundles the examples. Install it as a normal plugin. |
| `code/log-check.sh` | Counts AI bot hits by name in an access log |
| `code/robots-filter.php` | Adds Disallow rules for AI training bots to the robots.txt WordPress generates |
| `code/robots.txt` | The same policy as a static file |
| `code/json-ld.php` | Article structured data on single posts |
| `code/markdown-negotiation.php` | Serves Markdown when a client sends `Accept: text/markdown` |
| `code/search-posts-ability.php` | A read-only Abilities API ability that searches published posts |
| `code/create-draft-ability.php` | A write ability that can only create drafts |
| `code/rest-call.sh` | Calling an ability over REST |
| `code/mcp-client.json` | MCP client config for the WordPress MCP Adapter |

## The plugin

Needs WordPress 6.9 or later, which is when the Abilities API landed in core. To expose abilities to external AI clients, also install the MCP Adapter plugin.

By default the plugin registers only `acme/search-posts`, a read-only ability. That ability is public by design. It returns title, URL and excerpt for published, non-password-protected posts.

Other modules are off until you enable them in `wp-config.php`:

```php
define( 'NVD_MODULES', array( 'abilities-search', 'json-ld', 'markdown', 'robots' ) );
```

| Module | What it does |
|---|---|
| `abilities-search` | Read-only post search (default) |
| `abilities-draft` | Creates drafts only. Needs the `edit_posts` capability. |
| `json-ld` | Article structured data. Skip it if an SEO plugin already outputs schema. |
| `markdown` | Needs `composer require league/html-to-markdown` inside the plugin folder |
| `robots` | Disallows GPTBot, ClaudeBot, CCBot, Google-Extended and Applebot-Extended |

If a physical `robots.txt` file exists on disk, WordPress never runs the `robots_txt` filter.

## Try the ability

For a read-only ability, REST uses GET, and the input goes in the query string:

```
curl -g "https://example.com/wp-json/wp-abilities/v1/abilities/acme/search-posts/run?input[query]=agents&input[limit]=3"
```

Listing all abilities needs authentication:

```
curl -s -u "USER:APPLICATION PASSWORD" https://example.com/wp-json/wp-abilities/v1/abilities
```

For MCP, create a dedicated low-privilege user with its own application password, then use `code/mcp-client.json` as a starting point. Replace the placeholder URL, username and password.

## Safety notes

- Use one user per agent, with the lowest role that works. Revoke by deleting its application password.
- An ability is a public API you design on purpose. Return only what an agent needs.
- Text an agent reads can try to give it orders. Keep write access small, and keep a human on publish.
- Log every call: which ability, which user, what input, when.
- Never commit real application passwords. The ones in these files are placeholders.

## Links

- Abilities API: https://developer.wordpress.org/apis/abilities-api
- MCP Adapter: https://github.com/WordPress/mcp-adapter
- OpenAI bot documentation: https://developers.openai.com/docs/bots

## License

The plugin header declares GPL-2.0-or-later. Add a `LICENSE` file before you accept contributions.
