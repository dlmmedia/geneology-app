#!/bin/bash

# Railway Login Helper Script
# Opens browser for Railway authentication

echo "🚂 Railway Login Helper"
echo "======================="
echo ""
echo "Opening Railway login page in your browser..."
echo ""

# Open Railway login in browser
open "https://railway.app/login" 2>/dev/null || xdg-open "https://railway.app/login" 2>/dev/null || echo "Please visit: https://railway.app/login"

echo "📱 Browser opened! Please complete authentication in the browser."
echo ""
echo "After logging in, run this command in your terminal:"
echo "   railway login --browserless"
echo ""
echo "Or if that doesn't work, try:"
echo "   railway login"
echo ""
echo "Waiting for you to complete authentication..."
echo ""

# Wait a moment, then try to detect login
sleep 5

# Try to check if user is logged in
if railway whoami &> /dev/null; then
    echo "✅ Successfully logged in as: $(railway whoami)"
else
    echo "⏳ Please complete the login process in your browser, then run:"
    echo "   railway login --browserless"
    echo ""
    echo "Or run the interactive login:"
    echo "   railway login"
fi

