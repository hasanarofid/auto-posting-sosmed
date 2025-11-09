<?php
/**
 * Sosmed Auto Poster - PHP Backend
 * Main entry point for API
 */

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Load configuration
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Get request URI and method
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove query string
$request_uri = strtok($request_uri, '?');

// Handle routing from .htaccess
// If REDIRECT_URL is set, use it (this is set by .htaccess rewrite)
if (isset($_SERVER['REDIRECT_URL']) && !empty($_SERVER['REDIRECT_URL'])) {
    $request_uri = $_SERVER['REDIRECT_URL'];
}

// Remove base path if exists
$base_path = '/api';
if (strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}

// Remove leading slash
$request_uri = ltrim($request_uri, '/');

// Split URI into parts
$uri_parts = explode('/', $request_uri);
$endpoint = $uri_parts[0] ?? '';
$action = $uri_parts[1] ?? '';

// Make $uri_parts available globally for routes
$GLOBALS['uri_parts'] = $uri_parts;

// Route handling
try {
    switch ($endpoint) {
        case 'health':
            require_once __DIR__ . '/routes/health.php';
            break;
            
        case 'upload':
            require_once __DIR__ . '/routes/upload.php';
            break;
            
        case 'facebook':
            require_once __DIR__ . '/routes/facebook.php';
            break;
            
        case 'instagram':
            require_once __DIR__ . '/routes/instagram.php';
            break;
            
        case 'linkedin':
            require_once __DIR__ . '/routes/linkedin.php';
            break;
            
        case 'threads':
            require_once __DIR__ . '/routes/threads.php';
            break;
            
        case 'tiktok':
            require_once __DIR__ . '/routes/tiktok.php';
            break;
            
        case 'content':
            require_once __DIR__ . '/routes/content.php';
            break;
            
        case 'uploads':
            require_once __DIR__ . '/routes/uploads.php';
            break;
            
        case 'webhook':
            require_once __DIR__ . '/routes/webhook.php';
            break;
            
        default:
            http_response_code(404);
            echo json_encode([
                'error' => 'Endpoint tidak ditemukan',
                'endpoint' => $endpoint
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage()
    ]);
}

