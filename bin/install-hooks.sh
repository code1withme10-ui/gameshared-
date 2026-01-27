#!/usr/bin/env sh

set -e
ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"



 git config core.hooksPath "$ROOT_DIR/scripts/git-hooks"

#ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
#HOOK_DIR="$ROOT_DIR/.git/hooks"
#SRC_DIR="$ROOT_DIR/scripts/git-hooks"

#for hook in pre-commit pre-push; do
#  if [ -f "$SRC_DIR/$hook" ]; then
#    echo "Installing $hook hook"
#    cp "$SRC_DIR/$hook" "$HOOK_DIR/$hook"
#    chmod +x "$HOOK_DIR/$hook"
#  fi
#done

echo "Git hooks installed"
