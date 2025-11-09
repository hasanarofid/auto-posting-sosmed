<?php
/**
 * Helper functions
 */

/**
 * Make HTTP request using cURL
 */
function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            if (is_array($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $headers[] = 'Content-Type: application/json';
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $headers[] = 'Content-Type: application/json';
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        throw new Exception("cURL Error: " . $error);
    }
    
    return [
        'status_code' => $http_code,
        'body' => $response,
        'data' => json_decode($response, true)
    ];
}

/**
 * Get JSON request body
 */
function getJsonBody() {
    $input = file_get_contents('php://input');
    return json_decode($input, true);
}

/**
 * Send JSON response
 */
function sendJsonResponse($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

/**
 * Validate required fields
 */
function validateRequired($data, $required_fields) {
    $missing = [];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            $missing[] = $field;
        }
    }
    
    if (!empty($missing)) {
        sendJsonResponse([
            'error' => 'Field yang diperlukan tidak ditemukan',
            'missing_fields' => $missing
        ], 400);
    }
}

/**
 * Handle file upload
 */
function handleFileUpload($field_name = 'image') {
    if (!isset($_FILES[$field_name])) {
        return null;
    }
    
    $file = $_FILES[$field_name];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error: ' . $file['error']);
    }
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/mov'];
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('File type tidak diizinkan. Hanya gambar (JPEG, PNG, GIF) atau video (MP4, MOV) yang diizinkan.');
    }
    
    // Validate file size (10MB max)
    $max_size = 10 * 1024 * 1024; // 10MB
    if ($file['size'] > $max_size) {
        throw new Exception('File terlalu besar. Maksimal 10MB.');
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '-' . uniqid() . '.' . $extension;
    $destination = UPLOAD_DIR . '/' . $filename;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception('Gagal menyimpan file.');
    }
    
    return [
        'filename' => $filename,
        'originalname' => $file['name'],
        'path' => '/uploads/' . $filename,
        'size' => $file['size'],
        'type' => $file['type']
    ];
}

