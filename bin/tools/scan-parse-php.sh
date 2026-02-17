#!/usr/bin/env bash
FILE="$1"

echo "/* PHP Skeleton */"

grep -E "class |function " "$FILE" \
  | sed 's/{.*//g' \
  | sed 's/^[ \t]*//'
