<?php
/**
 * Health check endpoint
 */

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    sendJsonResponse([
        'status' => 'OK',
        'message' => 'Server berjalan dengan baik',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    sendJsonResponse(['error' => 'Method not allowed'], 405);
}

