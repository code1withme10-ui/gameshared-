#!/usr/bin/env bash

set -euo pipefail

############################################
# Defaults
############################################
DIR=""
EXT=""
OUT=""
EXCL=""
TREE="true"
CONTENT="true"
SKELETON="false"

SCRIPT_NAME="bin/scan-concat-files.sh"
DATE_NOW=$(date +"%Y-%m-%d %H:%M:%S")

############################################
# Parse arguments
############################################
for arg in "$@"; do
  case $arg in
    --dir=*)
      DIR="${arg#*=}"
      ;;
    --ext=*)
      EXT="${arg#*=}"
      ;;
    --out=*)
      OUT="${arg#*=}"
      ;;
    --excl=*)
      EXCL="${arg#*=}"
      ;;
    --tree=*)
      TREE="${arg#*=}"
      ;;
    --content=*)
      CONTENT="${arg#*=}"
      ;;
    --skeleton=*)
      SKELETON="${arg#*=}"
      ;;
    *)
      echo "Unknown argument: $arg"
      exit 1
      ;;
  esac
done

############################################
# Validation
############################################
if [[ -z "$DIR" ]]; then
  echo "ERROR: --dir is required"
  exit 1
fi

if [[ ! -d "$DIR" ]]; then
  echo "ERROR: Directory does not exist: $DIR"
  exit 1
fi

if [[ -z "$OUT" ]]; then
  mkdir -p tmp
  OUT="tmp/scan-last.txt"
fi

############################################
# Build find command
############################################
FIND_CMD=(find "$DIR" -type f)

if [[ -n "$EXT" ]]; then
  FIND_CMD+=(-name "*${EXT}")
fi

IFS=',' read -ra EXCLUDES <<< "$EXCL"
for excl in "${EXCLUDES[@]}"; do
  [[ -z "$excl" ]] && continue
  FIND_CMD+=(! -path "*$excl*")
done

############################################
# Execute find
############################################
mapfile -t FILES < <("${FIND_CMD[@]}" | sort)

############################################
# Start writing output
############################################
{
echo "/*"
echo "Command used: $SCRIPT_NAME $*"
echo "Date: $DATE_NOW"
echo "*/"
echo ""

############################################
# File tree
############################################
if [[ "$TREE" == "true" ]]; then
  echo "## File Tree"
  printf '%s\n' "${FILES[@]}" | sed "s|$DIR|.|" 
  echo ""
fi

############################################
# Process each file
############################################
for file in "${FILES[@]}"; do
  echo "-----"
  echo "#file:{${file}}"

  if [[ "$SKELETON" == "true" ]]; then
    case "$file" in
      *.php)
        bin/scan-parse-php.sh "$file"
        ;;
      *.json)
        bin/scan-parse-json.sh "$file"
        ;;
      *)
        echo "[Skeleton mode not supported for this file type]"
        ;;
    esac
  elif [[ "$CONTENT" == "true" ]]; then
    cat "$file"
  fi

  echo ""
done

echo "___"

} > "$OUT"

############################################
# Always print output file path
############################################
echo "Output written to: $OUT"
