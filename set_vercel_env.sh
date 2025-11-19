#!/bin/bash

# Read the Vercel token from the auth file
TOKEN=$(cat ~/.config/vercel/auth.json 2>/dev/null | grep -o '"token":"[^"]*' | cut -d'"' -f4)

if [ -z "$TOKEN" ]; then
    echo "Error: No Vercel token found. Please run 'vercel login' first."
    exit 1
fi

PROJECT_ID="prj_vQTWPkzUPtclpuz57c25CrrFwJVS"
TEAM_ID="team_XLS4r1tfJ0Myv7zfinX8fJmo"

# Function to set environment variable
set_env_var() {
    local key=$1
    local value=$2
    local type=${3:-"encrypted"}
    
    echo "Setting $key..."
    
    curl -X POST "https://api.vercel.com/v10/projects/${PROJECT_ID}/env?teamId=${TEAM_ID}" \
      -H "Authorization: Bearer ${TOKEN}" \
      -H "Content-Type: application/json" \
      -d "{
        \"key\": \"${key}\",
        \"value\": \"${value}\",
        \"type\": \"${type}\",
        \"target\": [\"production\", \"preview\", \"development\"]
      }" 2>&1 | head -20
    
    echo ""
}

# Set essential Laravel environment variables
set_env_var "APP_KEY" "base64:VOX8tpXLzo0q9GC8bZR+ug60rfHtsahEHlt6qQ6JNwE="
set_env_var "APP_ENV" "production" "plain"
set_env_var "APP_DEBUG" "false" "plain"
set_env_var "LOG_CHANNEL" "stderr" "plain"
set_env_var "SESSION_DRIVER" "cookie" "plain"
set_env_var "CACHE_STORE" "array" "plain"
set_env_var "QUEUE_CONNECTION" "sync" "plain"
set_env_var "DB_CONNECTION" "sqlite" "plain"
set_env_var "DB_DATABASE" "/tmp/database.sqlite" "plain"

echo "Environment variables set successfully!"
