#!/usr/bin/env bash
# Call an ability over the REST API.
# Create an application password for a dedicated low-privilege user first.
# Check the exact route on your site: GET /wp-json/wp-abilities/v1/abilities

SITE="https://example.com"

# List what is available
curl -s -u 'agent-reader:xxxx xxxx xxxx xxxx xxxx xxxx' \
  "$SITE/wp-json/wp-abilities/v1/abilities"

# Run a read-only ability (read-only abilities use GET)
# Input goes in the query string as input[field]=value
curl -s -g -u 'agent-reader:xxxx xxxx xxxx xxxx' \
  "$SITE/wp-json/wp-abilities/v1/abilities/acme/search-posts/run?input[query]=agents&input[limit]=3"
