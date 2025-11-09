<?php
/**
 * Serve uploaded files
 * This route handles direct access to uploaded files
 */

// Get filename from URI
$uri_parts = $GLOBALS['uri_parts'] ?? [];
$filename = $uri_parts[1] ?? '';

if (empty($filename)) {
    http_response_code(404);
    echo json_encode(['error' => 'File tidak ditemukan']);
    exit();
}

// Security: prevent directory traversal
$filename = basename($filename);

// File path
$file_path = UPLOAD_DIR . '/' . $filename;

// Check if file exists
if (!file_exists($file_path)) {
    http_response_code(404);
    echo json_encode(['error' => 'File tidak ditemukan']);
    exit();
}

// Get file mime type
$mime_type = mime_content_type($file_path);
if (!$mime_type) {
    $mime_type = 'application/octet-stream';
}

// Set headers
header('Content-Type: ' . $mime_type);
header('Content-Length: ' . filesize($file_path));
header('Content-Disposition: inline; filename="' . basename($file_path) . '"');

// Output file
readfile($file_path);
exit();

