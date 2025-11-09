<?php
/**
 * Threads API routes
 */

$uri_parts = $GLOBALS['uri_parts'] ?? [];
$action = $uri_parts[1] ?? '';

switch ($action) {
    case 'post':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = getJsonBody();
            validateRequired($data, ['accessToken', 'text', 'appId']);
            
            $accessToken = $data['accessToken'] ?: THREADS_ACCESS_TOKEN;
            $text = $data['text'];
            $imageUrl = $data['imageUrl'] ?? null;
            $appId = $data['appId'] ?: THREADS_APP_ID;
            
            try {
                $threadsApi = 'https://graph.threads.net/v1.0';
                
                // Create media container
                $mediaData = [
                    'media_type' => $imageUrl ? 'IMAGE' : 'TEXT',
                    'text' => $text,
                    'access_token' => $accessToken
                ];
                
                if ($imageUrl) {
                    $mediaData['image_url'] = $imageUrl;
                }
                
                $createUrl = $threadsApi . '/' . $appId . '/threads';
                $createResponse = makeRequest($createUrl, 'POST', $mediaData);
                
                if ($createResponse['status_code'] !== 200) {
                    throw new Exception('Threads API Error: ' . json_encode($createResponse['data']));
                }
                
                $threadId = $createResponse['data']['id'];
                
                // Publish the thread
                $publishUrl = $threadsApi . '/' . $appId . '/threads_publish';
                $publishData = [
                    'creation_id' => $threadId,
                    'access_token' => $accessToken
                ];
                
                $publishResponse = makeRequest($publishUrl, 'POST', $publishData);
                
                if ($publishResponse['status_code'] === 200) {
                    sendJsonResponse([
                        'success' => true,
                        'threadId' => $publishResponse['data']['id'],
                        'message' => 'Thread berhasil diupload'
                    ]);
                } else {
                    throw new Exception('Threads API Error: ' . json_encode($publishResponse['data']));
                }
            } catch (Exception $e) {
                sendJsonResponse([
                    'error' => 'Gagal posting ke Threads',
                    'details' => $e->getMessage()
                ], 500);
            }
        } else {
            sendJsonResponse(['error' => 'Method not allowed'], 405);
        }
        break;
        
    case 'test':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            sendJsonResponse([
                'message' => 'Threads API endpoint aktif',
                'note' => 'Threads menggunakan Meta Threads API. Perlu setup di Meta Developer Console.'
            ]);
        } else {
            sendJsonResponse(['error' => 'Method not allowed'], 405);
        }
        break;
        
    default:
        sendJsonResponse(['error' => 'Action tidak ditemukan'], 404);
        break;
}

