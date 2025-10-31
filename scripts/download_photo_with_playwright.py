#!/usr/bin/env python3
"""
Download a photo for a person using Wikimedia Commons API or Google Images search.
This script tries multiple sources to find suitable images.
"""

import sys
import json
import time
import requests
from pathlib import Path

# Try to import Playwright
try:
    from playwright.sync_api import sync_playwright
    PLAYWRIGHT_AVAILABLE = True
except ImportError:
    PLAYWRIGHT_AVAILABLE = False

def download_image_from_url(image_url, output_path):
    """Download an image from a URL."""
    try:
        headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        }
        response = requests.get(image_url, headers=headers, timeout=30, stream=True)
        response.raise_for_status()
        
        # Verify it's an image
        content_type = response.headers.get('content-type', '').lower()
        if not content_type.startswith('image/'):
            return False
        
        Path(output_path).parent.mkdir(parents=True, exist_ok=True)
        with open(output_path, 'wb') as f:
            for chunk in response.iter_content(chunk_size=8192):
                f.write(chunk)
        
        return True
    except Exception as e:
        return False

def search_wikimedia_commons(person_name):
    """Search Wikimedia Commons for images and return direct image URL."""
    try:
        api_url = "https://commons.wikimedia.org/w/api.php"
        
        # First, search for files
        search_params = {
            'action': 'query',
            'format': 'json',
            'list': 'search',
            'srsearch': person_name,
            'srnamespace': '6',  # File namespace
            'srlimit': '5'
        }
        
        response = requests.get(api_url, params=search_params, timeout=10)
        response.raise_for_status()
        data = response.json()
        
        if 'query' in data and 'search' in data['query']:
            results = data['query']['search']
            if results:
                # Get the first result's filename
                filename = results[0]['title'].replace('File:', '')
                
                # Get image info to get direct URL
                info_params = {
                    'action': 'query',
                    'format': 'json',
                    'titles': results[0]['title'],
                    'prop': 'imageinfo',
                    'iiprop': 'url',
                    'iiurlwidth': '800'  # Get a reasonable size
                }
                
                info_response = requests.get(api_url, params=info_params, timeout=10)
                info_response.raise_for_status()
                info_data = info_response.json()
                
                if 'query' in info_data and 'pages' in info_data['query']:
                    pages = info_data['query']['pages']
                    for page_id, page_data in pages.items():
                        if 'imageinfo' in page_data and len(page_data['imageinfo']) > 0:
                            image_url = page_data['imageinfo'][0].get('url') or page_data['imageinfo'][0].get('thumburl')
                            if image_url:
                                return image_url
                
                # Fallback: construct URL directly
                encoded_filename = filename.replace(' ', '_')
                return f"https://commons.wikimedia.org/wiki/Special:FilePath/{encoded_filename}"
    except Exception as e:
        print(f"Wikimedia Commons search error: {e}", file=sys.stderr)
    
    return None

def search_google_images_playwright(person_name, output_path):
    """Search Google Images using Playwright and download the first suitable image."""
    if not PLAYWRIGHT_AVAILABLE:
        return {
            "success": False,
            "error": "Playwright not installed. Install with: pip install playwright && playwright install"
        }
    
    try:
        with sync_playwright() as p:
            # Launch browser
            browser = p.chromium.launch(headless=True)
            page = browser.new_page()
            
            # Set user agent
            page.set_extra_http_headers({
                "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"
            })
            
            # Search Google Images
            query = person_name.replace(' ', '+')
            search_url = f"https://www.google.com/search?q={query}&tbm=isch&safe=active"
            
            print(f"Searching for: {person_name}", file=sys.stderr)
            page.goto(search_url, wait_until="networkidle")
            
            # Wait a bit for images to load
            time.sleep(2)
            
            # Try to find the first image
            # Google Images structure may vary, so we try multiple selectors
            image_selectors = [
                'img[data-src]',
                'img[src*="gstatic"]',
                'img[src*="googleusercontent"]',
                'img.rg_i',
                'img.Q4LuWd'
            ]
            
            image_url = None
            for selector in image_selectors:
                try:
                    images = page.query_selector_all(selector)
                    if images:
                        # Get the first image
                        first_img = images[0]
                        image_url = first_img.get_attribute('src') or first_img.get_attribute('data-src')
                        
                        # If it's a data URL, try to get the actual URL
                        if image_url and image_url.startswith('data:'):
                            continue
                        
                        # Sometimes Google uses a redirect URL, try to extract actual URL
                        if image_url and ('gstatic.com' in image_url or 'googleusercontent.com' in image_url):
                            break
                except Exception as e:
                    print(f"Error with selector {selector}: {e}", file=sys.stderr)
                    continue
            
            if not image_url:
                # Fallback: try to extract from page source
                page_content = page.content()
                # Look for image URLs in the page
                import re
                img_pattern = r'https://[^"\s]*\.(?:jpg|jpeg|png|webp)'
                matches = re.findall(img_pattern, page_content)
                if matches:
                    image_url = matches[0]
            
            if not image_url:
                browser.close()
                return {
                    "success": False,
                    "error": "No image found"
                }
            
            print(f"Found image URL: {image_url}", file=sys.stderr)
            
            # Download the image
            try:
                response = page.request.get(image_url, timeout=30000)
                if response.status == 200:
                    image_data = response.body()
                    
                    # Save to file
                    Path(output_path).parent.mkdir(parents=True, exist_ok=True)
                    with open(output_path, 'wb') as f:
                        f.write(image_data)
                    
                    browser.close()
                    return {
                        "success": True,
                        "url": image_url,
                        "path": output_path
                    }
                else:
                    browser.close()
                    return {
                        "success": False,
                        "error": f"HTTP {response.status}"
                    }
            except Exception as e:
                browser.close()
                return {
                    "success": False,
                    "error": f"Download error: {str(e)}"
                }
                
    except Exception as e:
        return {
            "success": False,
            "error": f"Playwright error: {str(e)}"
        }

def download_image_with_playwright(person_name, output_path):
    """
    Try multiple sources to find and download an image for a person.
    Priority: 1. Wikimedia Commons API, 2. Google Images via Playwright
    """
    # First, try Wikimedia Commons (faster and more reliable)
    wikimedia_url = search_wikimedia_commons(person_name)
    if wikimedia_url:
        if download_image_from_url(wikimedia_url, output_path):
            return {
                "success": True,
                "url": wikimedia_url,
                "path": output_path,
                "source": "wikimedia"
            }
    
    # Fallback to Google Images via Playwright
    result = search_google_images_playwright(person_name, output_path)
    if result.get("success"):
        result["source"] = "google"
    return result

if __name__ == "__main__":
    if len(sys.argv) < 3:
        print(json.dumps({
            "success": False,
            "error": "Usage: download_photo_with_playwright.py <person_name> <output_path>"
        }))
        sys.exit(1)
    
    person_name = sys.argv[1]
    output_path = sys.argv[2]
    
    result = download_image_with_playwright(person_name, output_path)
    print(json.dumps(result))

