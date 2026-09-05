<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'generate_token') {
        $fileId = (int)($_POST['file_id'] ?? 0);
        $expiresIn = (int)($_POST['expires_in'] ?? 24);
        
        if ($fileId > 0) {
            $token = createDownloadToken($fileId, $_SESSION['user']['username'], $expiresIn);
            $_SESSION['generated_token'] = [
                'token' => $token,
                'file_id' => $fileId,
                'expires_in' => $expiresIn
            ];
            $_SESSION['success'] = 'Token gerado com sucesso!';
        } else {
            $_SESSION['error'] = 'ID do arquivo inválido.';
        }
    }
}

header('Location: index.php#tokens');
exit;
?>