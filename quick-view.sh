#!/usr/bin/env bash
#
# start_sites.sh — Start multiple PHP dev servers, generate site list, and open main site.
#
# Works on Git Bash, Linux, and macOS.
#

# ============================================================
# 🧱 CONFIGURATION
# ============================================================
BASE_DIR="$(pwd)"          # Root folder for all projects
BASE_PORT=8040                 # Starting port
FRAGMENT_FILE="$BASE_DIR/public/sites_list.html"
INDEX_URL="http://localhost"   # Base for gateway open
SITE_INFO_FILE="config/auto-site-list-info.txt"

# ============================================================
# 🎨 COLORS
# ============================================================
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# ============================================================
# ⚙️ FUNCTIONS
# ============================================================

# --- Check if a port is free ---
is_port_free() {
  local port=$1
  (echo > "/dev/tcp/127.0.0.1/$port") >/dev/null 2>&1
  [[ $? -ne 0 ]]
}

# --- Find the next available port ---
get_free_port() {
  local port=$1
  while ! is_port_free "$port"; do
    ((port++))
  done
  echo "$port"
}

# --- Extract site info from config file ---
get_site_info() {
  local folder=$1
  local info_file="$folder/$SITE_INFO_FILE"
  if [[ -f "$info_file" ]]; then
    # Expected format: Name|relative_wwwroot|Description
    cat "$info_file"
  else
    echo ""  # No info file = skip this folder
  fi
}

# --- Scan for sites (only include those with config file) ---
scan_sites() {
  
# Define your sites as a Bash associative array list.
# Each element is: "name|path|meta(optional)"
local sites=(
  "Main-Site|/|Main-&-chats" 
  "Main-Site|collaboration|Collaboration-&-chats" 
  
)
  for dir in "$BASE_DIR"/*; do
    [[ -d "$dir/public" ]] || continue
    local info
    info=$(get_site_info "$dir")
    [[ -z "$info" ]] && continue
    sites+=("$info")
  done
 sites+=("Sample-Site|sample|Sample-creche" )
  echo "${sites[@]}"
}

# --- Generate HTML fragment ---
generate_html_fragment() {
  local html_list="$1"
  cat > "$FRAGMENT_FILE" <<EOF
<!--
  Auto-generated: $(date)
  This file is created by start_sites.sh.
  To add your site to the development list:
  1. Create a folder under www/, e.g. www/myportal/
  2. Inside it, create a 'public/' folder for your PHP app.
  3. Add a config/auto-site-list-info.txt file with this format:
       Site Name|relative/path/to/public|Description text
  4. Run ./start_sites.sh again.
-->
<ul>
$html_list
</ul>
EOF
  echo -e "${GREEN}✅ Generated HTML fragment:${NC} $FRAGMENT_FILE"
}

# --- Start PHP servers ---
start_servers() {
  local commands="$1"
  echo -e "${CYAN}🚀 Starting PHP development servers...${NC}"
  eval "$commands wait" &
  sleep 2
}

# --- Open main site in browser ---
open_browser() {
  local port="$1"
  local url="${INDEX_URL}:${port}"
  echo -e "${CYAN}🌐 Opening main site:${NC} $url"
  case "$OSTYPE" in
    linux*)   xdg-open "$url" >/dev/null 2>&1 ;;
    darwin*)  open "$url" >/dev/null 2>&1 ;;
    cygwin*|msys*) start "$url" >/dev/null 2>&1 ;;
    *)        echo "Please open $url manually." ;;
  esac
}

# --- Ensure sites_list.html is gitignored ---
check_gitignore() {
  local gitroot
  gitroot=$(git rev-parse --show-toplevel 2>/dev/null)
  if [[ -n "$gitroot" ]] && ! grep -q "sites_list.html" "$gitroot/.gitignore" 2>/dev/null; then
    echo -e "${YELLOW}⚠️  Reminder: Add to your .gitignore:${NC}"
    echo "    www/public/sites_list.html"
  fi
}

# --- Pretty table of running sites ---
show_summary_table() {
  echo -e "\n${CYAN}📋 Summary of sites:${NC}"
  printf "%-25s | %-10s | %-40s\n" "Name" "Port" "Description"
  printf "%-25s-+-%-10s-+-%-40s\n" "-------------------------" "----------" "----------------------------------------"
  while IFS="|" read -r NAME PORT META; do
    printf "%-25s | %-10s | %-40s\n" "$NAME" "$PORT" "$META"
  done <<< "$1"
  echo
}

# ============================================================
# 🧠 MAIN LOGIC
# ============================================================

main() {
  echo "======================================================="
  echo -e "${CYAN}🌍 Scanning for available PHP site directories...${NC}"
  echo "======================================================="

  local SITES=($(scan_sites))
  local PORT=$BASE_PORT
  local COMMANDS=""
  local HTML_LIST=""
  local TABLE_LINES=""
  local FIRST_PORT=""

  if [[ ${#SITES[@]} -eq 0 ]]; then
    echo -e "${YELLOW}⚠️  No valid sites found!${NC}"
    echo "Make sure each project folder has:"
    echo "  - a public/ directory"
    echo "  - a config/auto-site-list-info.txt file"
    exit 0
  fi

  for SITE_INFO in "${SITES[@]}"; do
    IFS="|" read -r NAME REL_PATH META <<< "$SITE_INFO"
    local WWWROOT="$BASE_DIR/$REL_PATH"

    if [[ ! -d "$WWWROOT" ]]; then
      echo -e "${RED}❌ Skipping ${NAME}:${NC} missing folder: $WWWROOT"
      continue
    fi

    PORT=$(get_free_port "$PORT")

    echo -e "${GREEN}▶️  Starting:${NC} $NAME"
    echo "    Path: $REL_PATH"
    echo "    Port: $PORT"
    echo "    Meta: $META"
    echo "-------------------------------------------------------"

    COMMANDS+="php -S 0.0.0.0:${PORT} -t \"$WWWROOT\" &"$'\n'
    HTML_LIST+="    <li><a href=\"http://localhost:${PORT}\" target=\"_blank\">${NAME}</a> - <small>${META}</small></li>"
    TABLE_LINES+="$NAME|$PORT|$META"$'\n'

    if [[ -z "$FIRST_PORT" ]]; then
      FIRST_PORT="$PORT"
    fi
    ((PORT++))
  done

  # Generate HTML list
  generate_html_fragment "$HTML_LIST"

  # Ensure gitignore
  check_gitignore

  # Start servers
  start_servers "$COMMANDS"

  # Show summary
  show_summary_table "$TABLE_LINES"

  # Open browser
  if [[ -n "$FIRST_PORT" ]]; then
    open_browser "$FIRST_PORT"
  fi

  wait
}

# ============================================================
# 🏁 RUN SCRIPT
# ============================================================

main
