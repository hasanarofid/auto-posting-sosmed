<?php
/**
 * Content management routes
 */

$uri_parts = $GLOBALS['uri_parts'] ?? [];
$contentFile = DATA_DIR . '/content.json';

// Helper functions
function readContent() {
    global $contentFile;
    if (!file_exists($contentFile)) {
        return [];
    }
    $content = file_get_contents($contentFile);
    return json_decode($content, true) ?: [];
}

function writeContent($content) {
    global $contentFile;
    file_put_contents($contentFile, json_encode($content, JSON_PRETTY_PRINT));
}

$action = $uri_parts[1] ?? '';

switch ($action) {
    case '':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Get all content
            $content = readContent();
            sendJsonResponse($content);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Create new content
            $data = getJsonBody();
            validateRequired($data, ['caption', 'image']);
            
            $content = readContent();
            $newContent = [
                'id' => time(),
                'caption' => $data['caption'],
                'image' => $data['image'],
                'platforms' => $data['platforms'] ?? [],
                'schedule' => $data['schedule'] ?? null,
                'status' => 'draft',
                'createdAt' => date('Y-m-d H:i:s')
            ];
            
            $content[] = $newContent;
            writeContent($content);
            
            sendJsonResponse(['success' => true, 'content' => $newContent]);
        } else {
            sendJsonResponse(['error' => 'Method not allowed'], 405);
        }
        break;
        
    default:
        $id = $action;
        
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Update content
            $data = getJsonBody();
            $content = readContent();
            
            $index = -1;
            foreach ($content as $i => $item) {
                if ($item['id'] == $id) {
                    $index = $i;
                    break;
                }
            }
            
            if ($index === -1) {
                sendJsonResponse(['error' => 'Konten tidak ditemukan'], 404);
            }
            
            $content[$index] = array_merge($content[$index], $data);
            writeContent($content);
            
            sendJsonResponse(['success' => true, 'content' => $content[$index]]);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Delete content
            $content = readContent();
            $filtered = array_filter($content, function($item) use ($id) {
                return $item['id'] != $id;
            });
            
            if (count($content) === count($filtered)) {
                sendJsonResponse(['error' => 'Konten tidak ditemukan'], 404);
            }
            
            writeContent(array_values($filtered));
            sendJsonResponse(['success' => true]);
        } else {
            sendJsonResponse(['error' => 'Method not allowed'], 405);
        }
        break;
}

