#!/usr/bin/env bash
FILE="$1"

echo "/* JSON Skeleton (Top-level keys) */"

jq 'keys' "$FILE" 2>/dev/null || echo "[Invalid JSON]"
