<?php
/**
 * File upload endpoint
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $file = handleFileUpload('image');
        
        if (!$file) {
            sendJsonResponse(['error' => 'Tidak ada file yang diupload'], 400);
        }
        
        sendJsonResponse([
            'success' => true,
            'file' => $file
        ]);
    } catch (Exception $e) {
        sendJsonResponse(['error' => $e->getMessage()], 500);
    }
} else {
    sendJsonResponse(['error' => 'Method not allowed'], 405);
}

