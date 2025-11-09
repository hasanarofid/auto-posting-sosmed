<?php
/**
 * Configuration file
 * Copy this file to config.local.php and fill with your credentials
 */

// Load environment variables from config.local.php if exists
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
} else {
    // Default values (for development)
    define('BASE_URL', 'https://sosmed.solusicodekata.com');
    define('UPLOAD_DIR', __DIR__ . '/uploads');
    define('DATA_DIR', __DIR__ . '/data');
    
    // Facebook API
    define('FACEBOOK_APP_ID', '812332124920892');
    define('FACEBOOK_APP_SECRET', 'b1981ea92f4a092b2bdf17b6c7b9e230');
    define('FACEBOOK_ACCESS_TOKEN', '');
    define('FACEBOOK_PAGE_ID', '');
    define('FACEBOOK_PAGE_ACCESS_TOKEN', '');
    
    // Instagram API
    define('INSTAGRAM_ACCESS_TOKEN', '');
    define('INSTAGRAM_USER_ID', '');
    define('INSTAGRAM_APP_ID', '');
    define('INSTAGRAM_APP_SECRET', '');
    
    // LinkedIn API
    define('LINKEDIN_ACCESS_TOKEN', '');
    define('LINKEDIN_CLIENT_ID', '');
    define('LINKEDIN_CLIENT_SECRET', '');
    define('LINKEDIN_PERSON_URN', '');
    
    // Threads API
    define('THREADS_ACCESS_TOKEN', '');
    define('THREADS_APP_ID', '');
    define('THREADS_USER_ID', '');
    
    // TikTok API
    define('TIKTOK_ACCESS_TOKEN', '');
    define('TIKTOK_CLIENT_KEY', '');
    define('TIKTOK_CLIENT_SECRET', '');
    define('TIKTOK_OPEN_ID', '');
}

// Create directories if not exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

