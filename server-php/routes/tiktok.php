<?php
/**
 * TikTok API routes
 */

$uri_parts = $GLOBALS['uri_parts'] ?? [];
$action = $uri_parts[1] ?? '';

switch ($action) {
    case 'post':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = getJsonBody();
            validateRequired($data, ['accessToken', 'videoUrl', 'openId']);
            
            $accessToken = $data['accessToken'] ?: TIKTOK_ACCESS_TOKEN;
            $videoUrl = $data['videoUrl'];
            $caption = $data['caption'] ?? 'Check out this video!';
            $openId = $data['openId'] ?: TIKTOK_OPEN_ID;
            
            try {
                $tiktokApi = 'https://open-api.tiktok.com';
                
                // Initialize upload
                $initData = [
                    'source_info' => [
                        'source' => 'FILE_UPLOAD'
                    ]
                ];
                
                $initResponse = makeRequest(
                    $tiktokApi . '/share/video/upload/',
                    'POST',
                    $initData,
                    ['Authorization: Bearer ' . $accessToken, 'Content-Type: application/json']
                );
                
                if ($initResponse['status_code'] !== 200) {
                    throw new Exception('TikTok API Error: ' . json_encode($initResponse['data']));
                }
                
                $uploadUrl = $initResponse['data']['data']['upload_url'];
                $publishId = $initResponse['data']['data']['publish_id'];
                
                // Download video
                $videoResponse = makeRequest($videoUrl, 'GET');
                if ($videoResponse['status_code'] !== 200) {
                    throw new Exception('Gagal download video');
                }
                
                // Upload video
                $uploadResponse = makeRequest(
                    $uploadUrl,
                    'PUT',
                    $videoResponse['body'],
                    ['Content-Type: video/mp4']
                );
                
                // Publish video
                $publishData = [
                    'publish_id' => $publishId,
                    'post_info' => [
                        'title' => $caption,
                        'privacy_level' => 'PUBLIC_TO_EVERYONE',
                        'disable_duet' => false,
                        'disable_comment' => false,
                        'disable_stitch' => false,
                        'video_cover_timestamp_ms' => 1000
                    ],
                    'source_info' => [
                        'source' => 'FILE_UPLOAD'
                    ]
                ];
                
                $publishResponse = makeRequest(
                    $tiktokApi . '/share/video/publish/',
                    'POST',
                    $publishData,
                    ['Authorization: Bearer ' . $accessToken, 'Content-Type: application/json']
                );
                
                if ($publishResponse['status_code'] === 200) {
                    sendJsonResponse([
                        'success' => true,
                        'videoId' => $publishResponse['data']['data']['share_id'] ?? 'success',
                        'message' => 'Video berhasil diupload ke TikTok'
                    ]);
                } else {
                    throw new Exception('TikTok API Error: ' . json_encode($publishResponse['data']));
                }
            } catch (Exception $e) {
                sendJsonResponse([
                    'error' => 'Gagal posting ke TikTok',
                    'details' => $e->getMessage(),
                    'note' => 'TikTok API memerlukan setup khusus dan approval. Pastikan sudah mendaftar di TikTok Developer Portal.'
                ], 500);
            }
        } else {
            sendJsonResponse(['error' => 'Method not allowed'], 405);
        }
        break;
        
    case 'test':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            sendJsonResponse([
                'message' => 'TikTok API endpoint aktif',
                'note' => 'TikTok API memerlukan setup di TikTok Developer Portal dan approval untuk production use.'
            ]);
        } else {
            sendJsonResponse(['error' => 'Method not allowed'], 405);
        }
        break;
        
    default:
        sendJsonResponse(['error' => 'Action tidak ditemukan'], 404);
        break;
}

