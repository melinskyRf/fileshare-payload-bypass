<?php
// Database configuration (using JSON file for simplicity)
define('FILES_DB', 'files.json');
define('TOKENS_DB', 'tokens.json');
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 3 * 1024 * 1024 * 1024); // 3GB in bytes
define('ALLOWED_EXTENSIONS', ['exe', 'zip']);

// Create upload directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// Initialize files database if it doesn't exist
if (!file_exists(FILES_DB)) {
    $initialFiles = [
        [
            'id' => 1,
            'name' => 'setup.exe',
            'original_name' => 'setup.exe',
            'type' => 'exe',
            'size' => 13107200, // 12.5MB in bytes
            'uploaded' => '2023-10-15',
            'uploaded_by' => 'admin',
            'file_path' => 'uploads/setup.exe'
        ],
        [
            'id' => 2,
            'name' => 'backup.zip',
            'original_name' => 'backup.zip',
            'type' => 'zip',
            'size' => 25890816, // 24.7MB in bytes
            'uploaded' => '2023-10-14',
            'uploaded_by' => 'admin',
            'file_path' => 'uploads/backup.zip'
        ],
        [
            'id' => 3,
            'name' => 'app_installer.exe',
            'original_name' => 'app_installer.exe',
            'type' => 'exe',
            'size' => 8601600, // 8.2MB in bytes
            'uploaded' => '2023-10-12',
            'uploaded_by' => 'admin',
            'file_path' => 'uploads/app_installer.exe'
        ]
    ];
    file_put_contents(FILES_DB, json_encode($initialFiles, JSON_PRETTY_PRINT));
}

// Initialize tokens database if it doesn't exist
if (!file_exists(TOKENS_DB)) {
    file_put_contents(TOKENS_DB, json_encode([], JSON_PRETTY_PRINT));
}

// Helper functions
function getFiles() {
    if (!file_exists(FILES_DB)) {
        return [];
    }
    $content = file_get_contents(FILES_DB);
    return json_decode($content, true) ?: [];
}

function saveFiles($files) {
    return file_put_contents(FILES_DB, json_encode($files, JSON_PRETTY_PRINT));
}

function getTokens() {
    if (!file_exists(TOKENS_DB)) {
        return [];
    }
    $content = file_get_contents(TOKENS_DB);
    return json_decode($content, true) ?: [];
}

function saveTokens($tokens) {
    return file_put_contents(TOKENS_DB, json_encode($tokens, JSON_PRETTY_PRINT));
}

function generateToken() {
    return bin2hex(random_bytes(16));
}

function createDownloadToken($fileId, $createdBy, $expiresInHours = 24) {
    $tokens = getTokens();
    $token = generateToken();
    
    $newToken = [
        'token' => $token,
        'file_id' => $fileId,
        'created_by' => $createdBy,
        'created_at' => date('Y-m-d H:i:s'),
        'expires_at' => date('Y-m-d H:i:s', strtotime("+{$expiresInHours} hours")),
        'used' => false,
        'used_at' => null,
        'used_by' => null
    ];
    
    $tokens[] = $newToken;
    saveTokens($tokens);
    
    return $token;
}

function validateToken($token, $fileId, $username) {
    $tokens = getTokens();
    
    foreach ($tokens as $index => $tokenData) {
        if ($tokenData['token'] === $token && $tokenData['file_id'] == $fileId) {
            // Check if token is expired
            if (strtotime($tokenData['expires_at']) < time()) {
                return ['valid' => false, 'error' => 'Token expirado'];
            }
            
            // Check if token is already used
            if ($tokenData['used']) {
                return ['valid' => false, 'error' => 'Token já foi utilizado'];
            }
            
            // Mark token as used
            $tokens[$index]['used'] = true;
            $tokens[$index]['used_at'] = date('Y-m-d H:i:s');
            $tokens[$index]['used_by'] = $username;
            saveTokens($tokens);
            
            return ['valid' => true];
        }
    }
    
    return ['valid' => false, 'error' => 'Token inválido'];
}

function formatFileSize($bytes) {
    if ($bytes === 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), $i >= 2 ? 1 : 0) . ' ' . $sizes[$i];
}

function getNextFileId() {
    $files = getFiles();
    $maxId = 0;
    foreach ($files as $file) {
        if ($file['id'] > $maxId) {
            $maxId = $file['id'];
        }
    }
    return $maxId + 1;
}
?>