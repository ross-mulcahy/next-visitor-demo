#!/usr/bin/env bash
# Count AI bot hits by name in an access log.
# Usage: ./log-check.sh /var/log/nginx/access.log

LOG="${1:-access.log}"
BOTS='GPTBot|OAI-SearchBot|ChatGPT-User|ClaudeBot|Claude-SearchBot|Claude-User'
BOTS+='|PerplexityBot|Perplexity-User|CCBot|Bytespider|Meta-ExternalAgent'

grep -Eio "$BOTS" "$LOG" | tr 'A-Z' 'a-z' | sort | uniq -c | sort -rn
