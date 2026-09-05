<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])) {
    $uploadedFiles = $_FILES['files'];
    $files = getFiles();
    $errors = [];
    $success = [];
    
    for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
        $fileName = $uploadedFiles['name'][$i];
        $fileTmpName = $uploadedFiles['tmp_name'][$i];
        $fileSize = $uploadedFiles['size'][$i];
        $fileError = $uploadedFiles['error'][$i];
        
        // Skip if no file uploaded
        if ($fileError === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        
        // Check for upload errors
        if ($fileError !== UPLOAD_ERR_OK) {
            $errors[] = "Erro no upload do arquivo: $fileName";
            continue;
        }
        
        // Validate file extension
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($fileExtension, ALLOWED_EXTENSIONS)) {
            $errors[] = "Tipo de arquivo não permitido: $fileName";
            continue;
        }
        
        // Validate file size
        if ($fileSize > MAX_FILE_SIZE) {
            $errors[] = "Arquivo muito grande (máximo 3GB): $fileName";
            continue;
        }
        
        // Generate unique filename to avoid conflicts
        $uniqueFileName = time() . '_' . $fileName;
        $filePath = UPLOAD_DIR . $uniqueFileName;
        
        // Move uploaded file
        if (move_uploaded_file($fileTmpName, $filePath)) {
            // Add to database
            $newFile = [
                'id' => getNextFileId(),
                'name' => $fileName,
                'original_name' => $fileName,
                'type' => $fileExtension,
                'size' => $fileSize,
                'uploaded' => date('Y-m-d'),
                'uploaded_by' => $_SESSION['user']['username'],
                'file_path' => $filePath
            ];
            
            $files[] = $newFile;
            $success[] = $fileName;
        } else {
            $errors[] = "Falha ao salvar o arquivo: $fileName";
        }
    }
    
    // Save updated files list
    if (!empty($success)) {
        saveFiles($files);
    }
    
    // Set session messages
    if (!empty($success)) {
        $_SESSION['success'] = 'Arquivos enviados com sucesso: ' . implode(', ', $success);
    }
    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}

header('Location: index.php');
exit;
?>