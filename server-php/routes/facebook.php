<?php
/**
 * Facebook API routes
 */

$uri_parts = $GLOBALS['uri_parts'] ?? [];
$action = $uri_parts[1] ?? '';

switch ($action) {
    case 'post':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = getJsonBody();
            validateRequired($data, ['accessToken', 'pageId', 'message']);
            
            $accessToken = $data['accessToken'] ?: FACEBOOK_PAGE_ACCESS_TOKEN;
            $pageId = $data['pageId'] ?: FACEBOOK_PAGE_ID;
            $message = $data['message'];
            $imageUrl = $data['imageUrl'] ?? null;
            
            try {
                $graphApi = 'https://graph.facebook.com/v18.0';
                
                if ($imageUrl) {
                    // Post with image
                    $url = $graphApi . '/' . $pageId . '/photos';
                    $postData = [
                        'url' => $imageUrl,
                        'caption' => $message,
                        'access_token' => $accessToken
                    ];
                    
                    $response = makeRequest($url, 'POST', $postData);
                    
                    if ($response['status_code'] === 200) {
                        sendJsonResponse([
                            'success' => true,
                            'postId' => $response['data']['id'],
                            'message' => 'Post berhasil diupload ke Facebook'
                        ]);
                    } else {
                        throw new Exception('Facebook API Error: ' . json_encode($response['data']));
                    }
                } else {
                    // Post text only
                    $url = $graphApi . '/' . $pageId . '/feed';
                    $postData = [
                        'message' => $message,
                        'access_token' => $accessToken
                    ];
                    
                    $response = makeRequest($url, 'POST', $postData);
                    
                    if ($response['status_code'] === 200) {
                        sendJsonResponse([
                            'success' => true,
                            'postId' => $response['data']['id'],
                            'message' => 'Post berhasil diupload ke Facebook'
                        ]);
                    } else {
                        throw new Exception('Facebook API Error: ' . json_encode($response['data']));
                    }
                }
            } catch (Exception $e) {
                sendJsonResponse([
                    'error' => 'Gagal posting ke Facebook',
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
                'message' => 'Facebook API endpoint aktif',
                'note' => 'Gunakan Facebook Graph API untuk posting. Perlu setup di Facebook Developer Console.'
            ]);
        } else {
            sendJsonResponse(['error' => 'Method not allowed'], 405);
        }
        break;
        
    default:
        sendJsonResponse(['error' => 'Action tidak ditemukan'], 404);
        break;
}

