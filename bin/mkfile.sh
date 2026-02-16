#!/usr/bin/env bash

set -euo pipefail

if [[ $# -lt 1 ]]; then
  echo "Usage: $0 <path/to/file>"
  exit 1
fi

FILE_PATH="$1"
DIR_PATH="$(dirname "$FILE_PATH")"

# Create parent directories if they don't exist
mkdir -p "$DIR_PATH"

# Create the file if it doesn't exist
if [[ ! -f "$FILE_PATH" ]]; then
  touch "$FILE_PATH"
  echo "Created file: $FILE_PATH"
else
  echo "File already exists: $FILE_PATH"
fi
