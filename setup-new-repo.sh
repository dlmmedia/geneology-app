#!/bin/bash

# Script to set up a new GitHub repository and deploy

echo "=== Setting up new GitHub repository ==="
echo ""

# Check if git is initialized
if [ ! -d ".git" ]; then
    echo "Initializing git repository..."
    git init
fi

# Show current remote
echo "Current remote:"
git remote -v
echo ""

# Ask if user wants to remove existing remote
read -p "Do you want to remove the existing remote and create a new one? (y/n) " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]; then
    git remote remove origin
    echo "Removed existing remote."
fi

# Stage all changes
echo ""
echo "Staging all changes..."
git add .

# Show status
echo ""
echo "Files to be committed:"
git status --short | head -20
echo ""

# Commit if there are changes
if ! git diff --cached --quiet; then
    read -p "Commit these changes? (y/n) " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        git commit -m "Add photo management features and deployment configuration"
        echo "✓ Changes committed"
    fi
fi

echo ""
echo "=== Next Steps ==="
echo ""
echo "1. Create a new repository on GitHub:"
echo "   - Go to https://github.com/new"
echo "   - Name: genealogy-app (or your preferred name)"
echo "   - Choose Public or Private"
echo "   - DO NOT initialize with README, .gitignore, or license"
echo "   - Click 'Create repository'"
echo ""
echo "2. Connect your local repository:"
echo "   git remote add origin https://github.com/YOUR_USERNAME/genealogy-app.git"
echo "   git branch -M main"
echo "   git push -u origin main"
echo ""
echo "3. Deploy to Railway (recommended for Laravel):"
echo "   - Go to https://railway.app"
echo "   - Sign up with GitHub"
echo "   - New Project → Deploy from GitHub repo"
echo "   - Select your repository"
echo "   - Add MySQL database"
echo "   - Configure environment variables"
echo ""
echo "For more details, see SETUP_GITHUB.md"

