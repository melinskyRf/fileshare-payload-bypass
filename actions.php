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
    $fileId = (int)($_POST['file_id'] ?? 0);
    
    $files = getFiles();
    $fileIndex = -1;
    
    // Find file index
    for ($i = 0; $i < count($files); $i++) {
        if ($files[$i]['id'] === $fileId) {
            $fileIndex = $i;
            break;
        }
    }
    
    if ($fileIndex === -1) {
        $_SESSION['error'] = 'Arquivo não encontrado.';
        header('Location: index.php');
        exit;
    }
    
    switch ($action) {
        case 'rename':
            $newFilename = trim($_POST['new_filename'] ?? '');
            if (empty($newFilename)) {
                $_SESSION['error'] = 'Nome do arquivo não pode estar vazio.';
                break;
            }
            
            $files[$fileIndex]['name'] = $newFilename;
            $files[$fileIndex]['original_name'] = $newFilename;
            
            if (saveFiles($files)) {
                $_SESSION['success'] = 'Arquivo renomeado com sucesso.';
            } else {
                $_SESSION['error'] = 'Erro ao renomear arquivo.';
            }
            break;
            
        case 'delete':
            $filePath = $files[$fileIndex]['file_path'];
            
            // Delete file from disk
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Remove from database
            array_splice($files, $fileIndex, 1);
            
            if (saveFiles($files)) {
                $_SESSION['success'] = 'Arquivo excluído com sucesso.';
            } else {
                $_SESSION['error'] = 'Erro ao excluir arquivo.';
            }
            break;
            
        default:
            $_SESSION['error'] = 'Ação inválida.';
            break;
    }
}

header('Location: index.php');
exit;
?>