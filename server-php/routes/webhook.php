<?php
/**
 * Facebook Webhook endpoint
 * Handles webhook verification and events from Facebook
 */

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Facebook webhook verification
if ($method === 'GET') {
    // Facebook sends GET request for verification
    $hub_mode = $_GET['hub_mode'] ?? '';
    $hub_verify_token = $_GET['hub_verify_token'] ?? '';
    $hub_challenge = $_GET['hub_challenge'] ?? '';
    
    // Get verification token from config
    $verify_token = defined('FACEBOOK_WEBHOOK_VERIFY_TOKEN') 
        ? constant('FACEBOOK_WEBHOOK_VERIFY_TOKEN')
        : 'sosmed_webhook_verification_2024';
    
    // Verify token
    if ($hub_mode === 'subscribe' && $hub_verify_token === $verify_token) {
        // Return challenge to verify webhook
        http_response_code(200);
        echo $hub_challenge;
        exit();
    } else {
        // Token mismatch
        http_response_code(403);
        echo json_encode([
            'error' => 'Verification token mismatch'
        ]);
        exit();
    }
}

// Handle webhook events (POST requests)
if ($method === 'POST') {
    // Get webhook data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // Verify signature (optional but recommended)
    $signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
    
    // Process webhook events
    if (isset($data['object'])) {
        $object = $data['object'];
        $entries = $data['entry'] ?? [];
        
        // Log webhook event (optional)
        error_log('Facebook Webhook: ' . json_encode($data));
        
        // Process each entry
        foreach ($entries as $entry) {
            // Handle different webhook events
            // You can add your logic here based on the event type
        }
        
        // Return 200 OK to acknowledge receipt
        http_response_code(200);
        echo json_encode(['status' => 'ok']);
        exit();
    } else {
        // Invalid webhook data
        http_response_code(400);
        echo json_encode(['error' => 'Invalid webhook data']);
        exit();
    }
}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);

