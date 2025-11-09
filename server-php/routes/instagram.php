<?php
/**
 * Instagram API routes
 */

$uri_parts = $GLOBALS['uri_parts'] ?? [];
$action = $uri_parts[1] ?? '';

switch ($action) {
    case 'post':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = getJsonBody();
            validateRequired($data, ['accessToken', 'imageUrl', 'caption', 'userId']);
            
            $accessToken = $data['accessToken'] ?: INSTAGRAM_ACCESS_TOKEN;
            $imageUrl = $data['imageUrl'];
            $caption = $data['caption'];
            $userId = $data['userId'] ?: INSTAGRAM_USER_ID;
            
            try {
                $graphApi = 'https://graph.instagram.com/v18.0';
                
                // Step 1: Create media container
                $createUrl = $graphApi . '/' . $userId . '/media';
                $createData = [
                    'image_url' => $imageUrl,
                    'caption' => $caption,
                    'access_token' => $accessToken
                ];
                
                $createResponse = makeRequest($createUrl, 'POST', $createData);
                
                if ($createResponse['status_code'] !== 200) {
                    throw new Exception('Instagram API Error: ' . json_encode($createResponse['data']));
                }
                
                $creationId = $createResponse['data']['id'];
                
                // Step 2: Publish the media container
                $publishUrl = $graphApi . '/' . $userId . '/media_publish';
                $publishData = [
                    'creation_id' => $creationId,
                    'access_token' => $accessToken
                ];
                
                $publishResponse = makeRequest($publishUrl, 'POST', $publishData);
                
                if ($publishResponse['status_code'] === 200) {
                    sendJsonResponse([
                        'success' => true,
                        'mediaId' => $publishResponse['data']['id'],
                        'message' => 'Post berhasil diupload ke Instagram'
                    ]);
                } else {
                    throw new Exception('Instagram API Error: ' . json_encode($publishResponse['data']));
                }
            } catch (Exception $e) {
                sendJsonResponse([
                    'error' => 'Gagal posting ke Instagram',
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
                'message' => 'Instagram API endpoint aktif',
                'note' => 'Gunakan Instagram Graph API untuk posting. Perlu setup di Facebook Developer Console.'
            ]);
        } else {
            sendJsonResponse(['error' => 'Method not allowed'], 405);
        }
        break;
        
    default:
        sendJsonResponse(['error' => 'Action tidak ditemukan'], 404);
        break;
}

