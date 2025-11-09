<?php
/**
 * LinkedIn API routes
 */

$uri_parts = $GLOBALS['uri_parts'] ?? [];
$action = $uri_parts[1] ?? '';

switch ($action) {
    case 'post':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = getJsonBody();
            validateRequired($data, ['accessToken', 'text']);
            
            $accessToken = $data['accessToken'] ?: LINKEDIN_ACCESS_TOKEN;
            $text = $data['text'];
            $imageUrl = $data['imageUrl'] ?? null;
            $personUrn = $data['personUrn'] ?: LINKEDIN_PERSON_URN;
            
            try {
                $linkedinApi = 'https://api.linkedin.com/v2';
                
                // Get person URN if not provided
                if (!$personUrn) {
                    $meResponse = makeRequest(
                        $linkedinApi . '/me',
                        'GET',
                        null,
                        ['Authorization: Bearer ' . $accessToken, 'X-Restli-Protocol-Version: 2.0.0']
                    );
                    
                    if ($meResponse['status_code'] !== 200) {
                        throw new Exception('LinkedIn API Error: ' . json_encode($meResponse['data']));
                    }
                    
                    $personUrn = 'urn:li:person:' . $meResponse['data']['id'];
                }
                
                // Prepare post data
                $postData = [
                    'author' => $personUrn,
                    'lifecycleState' => 'PUBLISHED',
                    'specificContent' => [
                        'com.linkedin.ugc.ShareContent' => [
                            'shareCommentary' => [
                                'text' => $text
                            ],
                            'shareMediaCategory' => $imageUrl ? 'IMAGE' : 'NONE'
                        ]
                    ],
                    'visibility' => [
                        'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'
                    ]
                ];
                
                // If image exists, upload it
                if ($imageUrl) {
                    // Register upload
                    $registerData = [
                        'registerUploadRequest' => [
                            'recipes' => ['urn:li:digitalmediaRecipe:feedshare-image'],
                            'owner' => $personUrn,
                            'serviceRelationships' => [[
                                'relationshipType' => 'OWNER',
                                'identifier' => 'urn:li:userGeneratedContent'
                            ]]
                        ]
                    ];
                    
                    $registerResponse = makeRequest(
                        $linkedinApi . '/assets?action=registerUpload',
                        'POST',
                        $registerData,
                        [
                            'Authorization: Bearer ' . $accessToken,
                            'X-Restli-Protocol-Version: 2.0.0',
                            'Content-Type: application/json'
                        ]
                    );
                    
                    if ($registerResponse['status_code'] !== 200) {
                        throw new Exception('LinkedIn Upload Registration Error: ' . json_encode($registerResponse['data']));
                    }
                    
                    $uploadUrl = $registerResponse['data']['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'];
                    $asset = $registerResponse['data']['value']['asset'];
                    
                    // Download and upload image
                    $imageResponse = makeRequest($imageUrl, 'GET');
                    if ($imageResponse['status_code'] !== 200) {
                        throw new Exception('Gagal download gambar');
                    }
                    
                    // Upload to LinkedIn
                    $uploadResponse = makeRequest(
                        $uploadUrl,
                        'PUT',
                        $imageResponse['body'],
                        [
                            'Authorization: Bearer ' . $accessToken,
                            'Content-Type: image/jpeg'
                        ]
                    );
                    
                    $postData['specificContent']['com.linkedin.ugc.ShareContent']['media'] = [[
                        'status' => 'READY',
                        'description' => ['text' => $text],
                        'media' => $asset,
                        'title' => ['text' => 'Shared Image']
                    ]];
                }
                
                // Post to LinkedIn
                $postResponse = makeRequest(
                    $linkedinApi . '/ugcPosts',
                    'POST',
                    $postData,
                    [
                        'Authorization: Bearer ' . $accessToken,
                        'X-Restli-Protocol-Version: 2.0.0',
                        'Content-Type: application/json'
                    ]
                );
                
                if ($postResponse['status_code'] === 201 || $postResponse['status_code'] === 200) {
                    sendJsonResponse([
                        'success' => true,
                        'postId' => $postResponse['data']['id'] ?? 'success',
                        'message' => 'Post berhasil diupload ke LinkedIn'
                    ]);
                } else {
                    throw new Exception('LinkedIn API Error: ' . json_encode($postResponse['data']));
                }
            } catch (Exception $e) {
                sendJsonResponse([
                    'error' => 'Gagal posting ke LinkedIn',
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
                'message' => 'LinkedIn API endpoint aktif',
                'note' => 'Perlu setup LinkedIn App di LinkedIn Developer Portal dan mendapatkan OAuth token.'
            ]);
        } else {
            sendJsonResponse(['error' => 'Method not allowed'], 405);
        }
        break;
        
    default:
        sendJsonResponse(['error' => 'Action tidak ditemukan'], 404);
        break;
}

