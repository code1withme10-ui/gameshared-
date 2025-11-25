#!/usr/bin/env bash
#
# bin/check-git-repo.sh
# Checks if the current Git repo is up-to-date and optionally fetches updates.
#

check_git_repo() {
  local RED='\033[0;31m'
  local GREEN='\033[0;32m'
  local YELLOW='\033[1;33m'
  local CYAN='\033[0;36m'
  local NC='\033[0m'

  # Ensure we’re inside a Git repo
  if ! git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
    echo -e "${YELLOW}⚠️  Not a Git repository. Skipping Git update check.${NC}"
    return 0
  fi

  echo -e "${CYAN}🔍 Checking repository status...${NC}"

  
  # Fetch latest changes from origin safely
  if ! git fetch origin main >/dev/null 2>&1; then
    echo -e "${YELLOW}⚠️  Unable to connect to remote repository (origin/main).${NC}"
    echo -e "   Continuing with current local version."
    return 0  # ✅ Gracefully continue
  fi

  local local_commit
  local remote_commit
  local_commit=$(git rev-parse main)
  remote_commit=$(git rev-parse origin/main)

  if [[ "$local_commit" == "$remote_commit" ]]; then
    echo -e "${GREEN}✅ Repository is up-to-date.${NC}"
    return 0
  fi

  echo -e "${YELLOW}⚠️  Updates available from origin/main.${NC}"
  echo

  # Show what files changed (limit 20)
  echo -e "${CYAN}📄 Files changed remotely (showing up to 20):${NC}"
  git diff --name-only "$local_commit" "$remote_commit" | head -n 20 | awk '{print "   • " $0}'
  local total_changes
  total_changes=$(git diff --name-only "$local_commit" "$remote_commit" | wc -l)
  if [[ $total_changes -gt 20 ]]; then
    echo "   ... ($((total_changes - 20)) more files changed)"
  fi
  echo

  # Prompt user
  read -p "Do you want to pull the latest updates? (y/N): " -r
  echo
  if [[ "$REPLY" =~ ^[Yy]$ ]]; then
    echo -e "${CYAN}⬇️  Pulling updates...${NC}"
    if ! git pull origin main; then
      echo -e "${RED}❌ Merge conflict detected.${NC}"
      echo -e "Please resolve conflicts manually and rerun this script."
      exit 1
    fi
    echo -e "${GREEN}✅ Repository successfully updated.${NC}"
  else
    echo -e "${YELLOW}⚠️  Skipping update. Continuing with local version.${NC}"
  fi
}

