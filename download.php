<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'Arquivo não especificado.';
    header('Location: index.php');
    exit;
}

$fileId = (int)$_GET['id'];
$files = getFiles();
$fileToDownload = null;

// Find the file
foreach ($files as $file) {
    if ($file['id'] === $fileId) {
        $fileToDownload = $file;
        break;
    }
}

if (!$fileToDownload) {
    $_SESSION['error'] = 'Arquivo não encontrado.';
    header('Location: index.php');
    exit;
}

// Se for admin, não precisa de token
if ($_SESSION['user']['role'] !== 'admin') {
    if (!isset($_GET['token'])) {
        $_SESSION['error'] = 'Token de download obrigatório.';
        header('Location: index.php');
        exit;
    }
    $token = $_GET['token'];
    // Validate token
    $tokenValidation = validateToken($token, $fileId, $_SESSION['user']['username']);
    if (!$tokenValidation['valid']) {
        $_SESSION['error'] = 'Erro no token: ' . $tokenValidation['error'];
        header('Location: index.php');
        exit;
    }
}

$filePath = $fileToDownload['file_path'];

// Check if file exists on disk
if (!file_exists($filePath)) {
    $_SESSION['error'] = 'Arquivo não encontrado no servidor.';
    header('Location: index.php');
    exit;
}

// Set headers for download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $fileToDownload['name'] . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

// Clear output buffer
ob_clean();
flush();

// Read and output file
readfile($filePath);
exit;
?>