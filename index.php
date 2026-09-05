<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    // If not logged in, show only login form
    include 'login.php';
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$currentUser = $_SESSION['user'];

// Move este bloco para o topo do arquivo, logo após session_start() e require_once 'config.php';
if (
    isset($_POST['postar_nota']) &&
    !empty($_POST['nota_titulo']) &&
    !empty($_POST['nota_conteudo']) &&
    isset($currentUser) &&
    $currentUser['role'] === 'admin'
) {
    $notasFile = __DIR__ . '/notas.json';
    if (!file_exists($notasFile)) file_put_contents($notasFile, '[]');
    $notas = json_decode(file_get_contents($notasFile), true);
    $notas[] = [
        'titulo' => htmlspecialchars($_POST['nota_titulo']),
        'conteudo' => htmlspecialchars($_POST['nota_conteudo']),
        'autor' => $currentUser['username'],
        'data' => date('d/m/Y H:i')
    ];
    file_put_contents($notasFile, json_encode($notas, JSON_PRETTY_PRINT));
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FileSharePayloadeBypass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Card e containers para cada tema */
        .theme-card-light {
            background-color: #fff !important;
            color: #1a202c !important;
        }
        .theme-card-dark {
            background-color: #1a202c !important;
            color: #f7fafc !important;
        }
        .theme-card-colorful {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 50%, #21d4fd 100%) !important;
            color: #3b0764 !important;
            box-shadow: 0 10px 15px -3px rgba(33,212,253,0.3), 0 4px 6px -2px rgba(247,151,30,0.2) !important;
        }
        .theme-card-dark .text-gray-800,
        .theme-card-dark .text-gray-700,
        .theme-card-dark .text-gray-600,
        .theme-card-dark .text-gray-500,
        .theme-card-dark .text-gray-900 {
            color: #f7fafc !important;
        }
        .theme-card-dark .bg-white,
        .theme-card-dark .bg-gray-50,
        .theme-card-dark .bg-gray-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark input,
        .theme-card-dark select,
        .theme-card-dark textarea {
            background-color: #23272f !important;
            color: #f7fafc !important;
            border-color: #374151 !important;
        }
        .theme-card-dark .gradient-bg {
            background: linear-gradient(90deg, #23272f 0%, #374151 100%) !important;
        }
        .theme-card-dark .border,
        .theme-card-dark .border-gray-300,
        .theme-card-dark .border-gray-200 {
            border-color: #374151 !important;
        }
        .theme-card-dark .hover\:bg-gray-100:hover {
            background-color: #374151 !important;
        }
        .theme-card-dark .bg-green-50,
        .theme-card-dark .bg-yellow-50,
        .theme-card-dark .bg-red-100,
        .theme-card-dark .bg-blue-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .text-green-600,
        .theme-card-dark .text-yellow-800,
        .theme-card-dark .text-red-800,
        .theme-card-dark .text-blue-800 {
            color: #f7fafc !important;
        }
        .theme-card-dark .divide-gray-200 {
            border-color: #374151 !important;
        }
        .theme-card-dark .bg-gray-200 {
            background-color: #23272f !important;
        }
        .theme-card-dark .hover\:bg-gray-50:hover {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-green-100,
        .theme-card-dark .bg-red-100,
        .theme-card-dark .bg-gray-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .text-green-800,
        .theme-card-dark .text-red-800,
        .theme-card-dark .text-gray-800 {
            color: #f7fafc !important;
        }
        .theme-card-dark .bg-blue-500,
        .theme-card-dark .bg-green-500,
        .theme-card-dark .bg-red-500 {
            filter: brightness(0.8);
        }
        .theme-card-dark .hover\:bg-blue-600:hover,
        .theme-card-dark .hover\:bg-green-600:hover,
        .theme-card-dark .hover\:bg-red-600:hover {
            filter: brightness(0.7);
        }
        .theme-card-dark .bg-white {
            background-color: #23272f !important;
        }
        .theme-card-dark .select-all {
            background-color: #23272f !important;
            color: #f7fafc !important;
        }
        .theme-card-dark .border-green-200,
        .theme-card-dark .border-yellow-200 {
            border-color: #374151 !important;
        }
        .theme-card-dark .bg-gray-900 {
            background-color: #1a202c !important;
        }
        .theme-card-dark .bg-gray-800 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-700 {
            background-color: #374151 !important;
        }
        .theme-card-dark .bg-gray-600 {
            background-color: #4b5563 !important;
        }
        .theme-card-dark .bg-gray-500 {
            background-color: #6b7280 !important;
        }
        .theme-card-dark .bg-gray-400 {
            background-color: #9ca3af !important;
        }
        .theme-card-dark .bg-gray-300 {
            background-color: #d1d5db !important;
        }
        .theme-card-dark .bg-gray-200 {
            background-color: #e5e7eb !important;
        }
        .theme-card-dark .bg-gray-100 {
            background-color: #f3f4f6 !important;
        }
        .theme-card-dark .bg-gray-50 {
            background-color: #f9fafb !important;
        }
        .theme-card-dark .bg-yellow-50 {
            background-color: #23272f !important;
        }
        .theme-card-dark .text-yellow-700 {
            color: #fde68a !important;
        }
        .theme-card-dark .text-yellow-400 {
            color: #fde68a !important;
        }
        .theme-card-dark .border-yellow-400 {
            border-color: #fde68a !important;
        }
        .theme-card-dark .border-yellow-200 {
            border-color: #fde68a !important;
        }
        .theme-card-dark .bg-green-50 {
            background-color: #23272f !important;
        }
        .theme-card-dark .border-green-200 {
            border-color: #6ee7b7 !important;
        }
        .theme-card-dark .text-green-700 {
            color: #6ee7b7 !important;
        }
        .theme-card-dark .bg-blue-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .text-blue-800 {
            color: #bfdbfe !important;
        }
        .theme-card-dark .bg-purple-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .text-purple-800 {
            color: #c4b5fd !important;
        }
        .theme-card-dark .bg-red-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .text-red-800 {
            color: #fecaca !important;
        }
        .theme-card-dark .bg-green-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .text-green-800 {
            color: #bbf7d0 !important;
        }
        .theme-card-dark .bg-gray-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .text-gray-800 {
            color: #f7fafc !important;
        }
        .theme-card-dark .bg-white {
            background-color: #23272f !important;
        }
        .theme-card-dark .text-gray-900 {
            color: #f7fafc !important;
        }
        .theme-card-dark .divide-gray-200 {
            border-color: #374151 !important;
        }
        .theme-card-dark .bg-gray-50 {
            background-color: #23272f !important;
        }
        .theme-card-dark .text-gray-500 {
            color: #d1d5db !important;
        }
        .theme-card-dark .bg-gray-50 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-white {
            background-color: #23272f !important;
        }
        .theme-card-dark .border {
            border-color: #374151 !important;
        }
        .theme-card-dark .hover\:bg-gray-100:hover {
            background-color: #374151 !important;
        }
        .theme-card-dark .hover\:bg-gray-50:hover {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-white {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-50 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-200 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-300 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-400 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-500 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-600 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-700 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-800 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-900 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-yellow-50 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-green-50 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-blue-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-purple-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-red-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-green-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-gray-100 {
            background-color: #23272f !important;
        }
        .theme-card-dark .bg-white {
            background-color: #23272f !important;
        }
        .theme-card-dark .border {
            border-color: #374151 !important;
        }
        .theme-card-dark .hover\:bg-gray-100:hover {
            background-color: #374151 !important;
        }
        .theme-card-dark .hover\:bg-gray-50:hover {
            background-color: #23272f !important;
        }
        /* Header/Footer */
        .theme-header-light { background: linear-gradient(90deg, #2563eb 0%, #9333ea 100%) !important; color: #fff !important; }
        .theme-header-dark { background: #111827 !important; color: #f7fafc !important; }
        .theme-header-colorful { background: linear-gradient(90deg, #f7971e 0%, #ffd200 50%, #21d4fd 100%) !important; color: #3b0764 !important; }
        .theme-footer-light { background: #1f2937 !important; color: #fff !important; }
        .theme-footer-dark { background: #111827 !important; color: #f7fafc !important; }
        .theme-footer-colorful { background: linear-gradient(90deg, #f7971e 0%, #ffd200 50%, #21d4fd 100%) !important; color: #3b0764 !important; }
        /* Modais */
        .theme-modal-light { background: #fff !important; color: #1a202c !important; }
        .theme-modal-dark { background: #1a202c !important; color: #f7fafc !important; }
        .theme-modal-colorful { background: #fffbe7 !important; color: #3b0764 !important; }
        /* Body */
        .theme-body-light { background: #f3f4f6 !important; }
        .theme-body-dark { background: #111827 !important; }
        .theme-body-colorful { background: linear-gradient(135deg, #f7971e 0%, #ffd200 50%, #21d4fd 100%) !important; }
        /* Token modal theme support */
        .theme-modal-light .token-info-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }
        .theme-modal-dark .token-info-box {
            background: #23272f;
            border: 1px solid #374151;
        }
        .theme-modal-colorful .token-info-box {
            background: #fffbe7;
            border: 1px solid #f7971e;
        }
        .theme-modal-light .token-info-text,
        .theme-modal-light .token-code-box {
            color: #1a202c;
            background: #fff;
            border: 1px solid #e5e7eb;
        }
        .theme-modal-dark .token-info-text,
        .theme-modal-dark .token-code-box {
            color: #f7fafc;
            background: #23272f;
            border: 1px solid #374151;
        }
        .theme-modal-colorful .token-info-text,
        .theme-modal-colorful .token-code-box {
            color: #3b0764;
            background: #fffbe7;
            border: 1px solid #f7971e;
        }
        .theme-modal-light .token-warning-box {
            background: #fef9c3;
            border: 1px solid #fde68a;
        }
        .theme-modal-dark .token-warning-box {
            background: #23272f;
            border: 1px solid #fde68a;
        }
        .theme-modal-colorful .token-warning-box {
            background: #fffbe7;
            border: 1px solid #f7971e;
        }
        .theme-modal-light .token-warning-text { color: #b45309; }
        .theme-modal-dark .token-warning-text { color: #fde68a; }
        .theme-modal-colorful .token-warning-text { color: #b45309; }
    </style>
</head>
<body class="bg-gray-100 font-sans theme-body-light transition-colors duration-300">
    <!-- Seletor de tema centralizado -->
    <div class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50">
        <div class="relative inline-block">
            <button onclick="toggleThemeDropdown()" type="button"
                class="flex items-center gap-2 px-6 py-2 rounded-full shadow-lg bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white font-semibold text-base transition-all duration-300 hover:scale-105 hover:from-blue-600 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:from-gray-700 dark:via-gray-900 dark:to-gray-700 dark:hover:from-gray-800 dark:hover:to-gray-900"
                id="theme-toggle-btn">
                <span id="theme-btn-label"><i class="fas fa-sun"></i> Claro</span>
                <i class="fas fa-chevron-down ml-2"></i>
            </button>
            <div id="theme-dropdown" class="hidden absolute left-1/2 -translate-x-1/2 mt-2 w-44 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                <button onclick="setTheme('light')" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition rounded-t-lg">
                    <i class="fas fa-sun"></i> Claro
                </button>
                <button onclick="setTheme('dark')" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-moon"></i> Escuro
                </button>
                <button onclick="setTheme('colorful')" class="w-full flex items-center gap-2 px-4 py-2 text-yellow-700 hover:bg-yellow-100 transition rounded-b-lg">
                    <i class="fas fa-palette"></i> Colorido
                </button>
            </div>
        </div>
    </div>

    <!-- Token Generation Modal -->
    <div id="tokenModal" class="modal-overlay">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Gerar Token de Download</h3>
                <button onclick="closeModal('tokenModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="tokenForm" method="POST" action="token_manager.php">
                <input type="hidden" name="action" value="generate_token">
                <input type="hidden" id="tokenFileId" name="file_id" value="">
                <div class="mb-4">
                    <p class="text-gray-700 mb-2">Arquivo: <span id="tokenFileName" class="font-bold"></span></p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="expiresIn">
                        Token expira em (horas)
                    </label>
                    <select id="expiresIn" name="expires_in" class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="1">1 hora</option>
                        <option value="6">6 horas</option>
                        <option value="12">12 horas</option>
                        <option value="24" selected>24 horas (1 dia)</option>
                        <option value="72">72 horas (3 dias)</option>
                        <option value="168">168 horas (1 semana)</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('tokenModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="gradient-bg hover:opacity-90 text-white font-medium py-2 px-4 rounded">
                        Gerar Token
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Token Display Modal -->
    <?php if (isset($_SESSION['generated_token'])): ?>
    <div id="tokenDisplayModal" class="modal-overlay" style="display: flex;">
        <div class="modal-content max-w-2xl theme-modal-light" id="token-modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-green-600">
                    <i class="fas fa-check-circle mr-2"></i>Token Gerado com Sucesso!
                </h3>
                <button onclick="closeTokenDisplay()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php 
            $generatedToken = $_SESSION['generated_token'];
            $files = getFiles();
            $fileName = '';
            foreach ($files as $file) {
                if ($file['id'] == $generatedToken['file_id']) {
                    $fileName = $file['name'];
                    break;
                }
            }
            ?>
            <div class="token-info-box rounded-lg p-4 mb-4">
                <p class="token-info-text mb-2"><strong>Arquivo:</strong> <?php echo htmlspecialchars($fileName); ?></p>
                <p class="token-info-text mb-2"><strong>Expira em:</strong> <?php echo $generatedToken['expires_in']; ?> horas</p>
                <p class="token-info-text mb-4"><strong>Token:</strong></p>
                <div class="token-code-box rounded p-3 mb-4">
                    <code class="text-sm break-all select-all"><?php echo $generatedToken['token']; ?></code>
                </div>
                <p class="token-info-text mb-4"><strong>Link de Download:</strong></p>
                <div class="token-code-box rounded p-3 mb-4">
                    <code class="text-sm break-all select-all">
                        <?php echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']); ?>/download.php?id=<?php echo $generatedToken['file_id']; ?>&token=<?php echo $generatedToken['token']; ?>
                    </code>
                </div>
            </div>
            <div class="token-warning-box rounded-lg p-4 mb-4">
                <p class="token-warning-text text-sm">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Importante:</strong> Este token só pode ser usado uma vez e expira automaticamente.
                </p>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="copyToken()" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                    <i class="fas fa-copy mr-2"></i>Copiar Token
                </button>
                <button onclick="copyDownloadLink()" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                    <i class="fas fa-link mr-2"></i>Copiar Link
                </button>
                <button onclick="closeTokenDisplay()" class="gradient-bg hover:opacity-90 text-white font-medium py-2 px-4 rounded">
                    Fechar
                </button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['generated_token']); ?>
    <?php endif; ?>

    <!-- File Actions Modals -->
    <div id="renameModal" class="modal-overlay">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Renomear Arquivo</h3>
                <button onclick="closeModal('renameModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="renameForm" method="POST" action="actions.php">
                <input type="hidden" name="action" value="rename">
                <input type="hidden" id="renameFileId" name="file_id" value="">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="newFilename">
                        Novo Nome do Arquivo
                    </label>
                    <input type="text" id="newFilename" name="new_filename" class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('renameModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="gradient-bg hover:opacity-90 text-white font-medium py-2 px-4 rounded">
                        Renomear
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Excluir Arquivo</h3>
                <button onclick="closeModal('deleteModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="deleteForm" method="POST" action="actions.php">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" id="deleteFileId" name="file_id" value="">
                <p class="mb-6">Você tem certeza que deseja excluir o arquivo <span id="fileNameToDelete" class="font-bold"></span>? Esta ação não pode ser desfeita.</p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('deleteModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                        Excluir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Container -->
    <div id="app" class="min-h-screen flex flex-col">
        <!-- Header -->
        <header id="main-header" class="gradient-bg text-white shadow-lg theme-header-light">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-2xl"></i>
                    <h1 class="text-3xl font-bold text-gray-800">FileSharePayloadeBypass</h1>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="font-medium"><?php echo htmlspecialchars($currentUser['username']); ?></span>
                    <span class="px-2 py-1 rounded-full text-xs <?php echo $currentUser['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'; ?>">
                        <?php echo $currentUser['role'] === 'admin' ? 'ADMIN' : 'CONVIDADO'; ?>
                    </span>
                    <a href="?logout=1" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full text-sm">
                        Sair
                    </a>
                </div>
            </div>
        </header>

        <!-- Security Notice -->
        <div id="security-notice" class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="container mx-auto px-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Sistema de Segurança Ativo:</strong> 
                            <?php if ($currentUser['role'] === 'admin'): ?>
                                Você pode gerar tokens de download para os usuários. Cada token é único e expira automaticamente.
                            <?php else: ?>
                                Para fazer download de arquivos, você precisa de um token válido gerado pelo administrador.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-8">
            <!-- Upload Section (Admin Only) -->
            <?php if ($currentUser['role'] === 'admin'): ?>
            <div class="mb-12">
                <div class="rounded-lg shadow-md p-6 mb-8 theme-card-light" id="upload-card">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Upload de Arquivos</h2>
                        <button id="toggleViewBtn" onclick="toggleViewMode()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-th-large mr-1"></i> Visualização em Grid
                        </button>
                    </div>
                    <form action="upload.php" method="POST" enctype="multipart/form-data" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-blue-500 mb-3"></i>
                            <p class="text-gray-600 mb-4">Arraste e solte arquivos aqui ou clique para selecionar</p>
                            <input type="file" name="files[]" id="fileInput" class="hidden" accept=".exe,.zip" multiple>
                            <label for="fileInput" class="gradient-bg hover:opacity-90 text-white font-medium py-2 px-6 rounded-full cursor-pointer transition-opacity">
                                Selecionar Arquivos
                            </label>
                            <p class="text-gray-500 text-sm mt-2">Formatos permitidos: .exe, .zip • Tamanho máximo: 150MB</p>
                        </div>
                        <div id="selectedFiles" class="mt-6 hidden">
                            <h3 class="text-lg font-medium text-gray-700 mb-3">Arquivos selecionados:</h3>
                            <ul id="fileList" class="space-y-2"></ul>
                            <button type="submit" class="gradient-bg hover:opacity-90 text-white font-medium py-2 px-6 rounded-full mt-4 transition-opacity">
                                <i class="fas fa-upload mr-2"></i> Enviar Arquivos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <!-- Files Section -->
            <div class="rounded-lg shadow-md p-6 theme-card-light" id="files-card">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        <?php echo $currentUser['role'] === 'admin' ? 'Gerenciar Arquivos e Tokens' : 'Arquivos Disponíveis'; ?>
                    </h2>
                    <div class="flex space-x-3">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Buscar arquivos..." 
                                   class="border border-gray-300 rounded-full py-2 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <select id="sortSelect" class="border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="newest">Mais recentes</option>
                            <option value="oldest">Mais antigos</option>
                            <option value="name_asc">Nome (A-Z)</option>
                            <option value="name_desc">Nome (Z-A)</option>
                            <option value="size_asc">Tamanho (Menor)</option>
                            <option value="size_desc">Tamanho (Maior)</option>
                        </select>
                    </div>
                </div>
                
                <!-- Table View (default) -->
                <div id="tableView" class="overflow-x-auto">
                    <div class="theme-card-light" id="files-table-wrapper">
                        <?php include 'files_table.php'; ?>
                    </div>
                </div>
                <div id="gridView" class="hidden">
                    <div class="theme-card-light" id="files-grid-wrapper">
                        <?php include 'files_grid.php'; ?>
                    </div>
                </div>
            </div>

            <!-- Token History (Admin Only) -->
            <?php if ($currentUser['role'] === 'admin'): ?>
            <div id="tokens" class="rounded-lg shadow-md p-6 mt-8 theme-card-light">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Histórico de Tokens</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Token</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arquivo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expira em</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usado por</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php 
                            $tokens = getTokens();
                            $files = getFiles();
                            
                            // Sort tokens by creation date (newest first)
                            usort($tokens, function($a, $b) {
                                return strtotime($b['created_at']) - strtotime($a['created_at']);
                            });
                            
                            if (empty($tokens)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-500">
                                        Nenhum token gerado ainda
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tokens as $token): ?>
                                    <?php
                                    $fileName = 'Arquivo não encontrado';
                                    foreach ($files as $file) {
                                        if ($file['id'] == $token['file_id']) {
                                            $fileName = $file['name'];
                                            break;
                                        }
                                    }
                                    
                                    $isExpired = strtotime($token['expires_at']) < time();
                                    $statusClass = $token['used'] ? 'bg-gray-100 text-gray-800' : ($isExpired ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800');
                                    $statusText = $token['used'] ? 'Usado' : ($isExpired ? 'Expirado' : 'Ativo');
                                    ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">
                                                <?php echo substr($token['token'], 0, 8) . '...'; ?>
                                            </code>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($fileName); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('d/m/Y H:i', strtotime($token['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('d/m/Y H:i', strtotime($token['expires_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                                <?php echo $statusText; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $token['used'] ? htmlspecialchars($token['used_by']) . ' em ' . date('d/m/Y H:i', strtotime($token['used_at'])) : '-'; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Notas (Todos veem, só admin posta) -->
            <?php
                $notasFile = __DIR__ . '/notas.json';
                if (!file_exists($notasFile)) file_put_contents($notasFile, '[]');
                $notas = json_decode(file_get_contents($notasFile), true);
            ?>
            <div id="notas" class="rounded-lg shadow-md p-6 mb-8 nota-theme">
                <h2 class="text-xl font-bold mb-4 nota-titulo-dinamico">Notas Administrativas</h2>
                <?php if ($currentUser['role'] === 'admin'): ?>
                <form method="POST" action="" class="mb-6">
                    <div class="mb-3">
                        <label class="block text-gray-700 font-semibold mb-1" for="nota_titulo">Título</label>
                        <input type="text" id="nota_titulo" name="nota_titulo" class="border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" maxlength="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-700 font-semibold mb-1" for="nota_conteudo">Conteúdo</label>
                        <textarea id="nota_conteudo" name="nota_conteudo" rows="3" class="border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" maxlength="1000" required></textarea>
                    </div>
                    <button type="submit" name="postar_nota" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition">Postar Nota</button>
                </form>
                <?php endif; ?>
                <div class="space-y-4">
                    <?php if (!empty($notas)): ?>
                        <?php foreach (array_reverse($notas) as $idx => $nota): ?>
                            <div class="p-4 rounded border nota-card nota-theme mb-2 flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-bold text-lg nota-titulo"><?php echo $nota['titulo']; ?></span>
                                        <span class="text-xs text-gray-500 nota-data"><?php echo $nota['data']; ?></span>
                                    </div>
                                    <div class="text-gray-700 dark:text-gray-200 mb-1 nota-conteudo"><?php echo nl2br($nota['conteudo']); ?></div>
                                    <div class="text-xs text-gray-500">Por: <?php echo htmlspecialchars($nota['autor']); ?></div>
                                </div>
                                <div class="mt-3 md:mt-0 md:ml-4 flex-shrink-0">
                                    <button type="button"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition"
                                        onclick="downloadNota(<?php echo $idx; ?>)">
                                        <i class="fas fa-download mr-1"></i> Baixar Nota
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-gray-500">Nenhuma nota postada ainda.</div>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer id="main-footer" class="bg-gray-800 text-white py-6 theme-footer-light">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; 2023 FileSharePayloadeBypass System com Sistema de Tokens. <p>byCode: RafaelBolinsky</p></p>
            </div>
        </footer>
    </div>

    <script>
        // Token modal functions
        function openTokenModal(fileId, fileName) {
            document.getElementById('tokenFileId').value = fileId;
            document.getElementById('tokenFileName').textContent = fileName;
            document.getElementById('tokenModal').style.display = 'flex';
        }

        function closeTokenDisplay() {
            document.getElementById('tokenDisplayModal').style.display = 'none';
        }

        function copyToken() {
            const tokenText = document.querySelector('#tokenDisplayModal code').textContent;
            navigator.clipboard.writeText(tokenText).then(() => {
                alert('Token copiado para a área de transferência!');
            });
        }

        function copyDownloadLink() {
            const linkText = document.querySelectorAll('#tokenDisplayModal code')[1].textContent;
            navigator.clipboard.writeText(linkText).then(() => {
                alert('Link de download copiado para a área de transferência!');
            });
        }

        // Existing modal functions
        function openRenameModal(fileId, fileName) {
            document.getElementById('renameFileId').value = fileId;
            document.getElementById('newFilename').value = fileName;
            document.getElementById('renameModal').style.display = 'flex';
        }

        function openDeleteModal(fileId, fileName) {
            document.getElementById('deleteFileId').value = fileId;
            document.getElementById('fileNameToDelete').textContent = fileName;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // View toggle function
        let isGridView = false;
        function toggleViewMode() {
            const tableView = document.getElementById('tableView');
            const gridView = document.getElementById('gridView');
            const toggleBtn = document.getElementById('toggleViewBtn');
            
            if (isGridView) {
                tableView.classList.remove('hidden');
                gridView.classList.add('hidden');
                toggleBtn.innerHTML = '<i class="fas fa-th-large mr-1"></i> Visualização em Grid';
                isGridView = false;
            } else {
                tableView.classList.add('hidden');
                gridView.classList.remove('hidden');
                toggleBtn.innerHTML = '<i class="fas fa-table mr-1"></i> Visualização em Tabela';
                isGridView = true;
            }
        }

        // File input handling
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const files = e.target.files;
            const selectedFilesDiv = document.getElementById('selectedFiles');
            const fileList = document.getElementById('fileList');
            
            if (files.length > 0) {
                selectedFilesDiv.classList.remove('hidden');
                fileList.innerHTML = '';
                
                for (let i = 0; i < files.length; i++) {
                    const li = document.createElement('li');
                    li.className = 'flex justify-between items-center bg-gray-50 p-2 rounded';
                    li.innerHTML = `
                        <span class="text-sm text-gray-700">${files[i].name}</span>
                        <span class="text-xs text-gray-500">${(files[i].size / 1024 / 1024).toFixed(2)} MB</span>
                    `;
                    fileList.appendChild(li);
                }
            } else {
                selectedFilesDiv.classList.add('hidden');
            }
        });

        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
            const btn = document.getElementById('theme-toggle-btn');
            const dd = document.getElementById('theme-dropdown');
            if (!btn.contains(e.target) && !dd.contains(e.target)) {
                dd.classList.add('hidden');
            }

            const modals = ['tokenModal', 'renameModal', 'deleteModal', 'tokenDisplayModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Search and sort functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            // Reload page with search parameter
            const searchTerm = this.value;
            const sortBy = document.getElementById('sortSelect').value;
            const url = new URL(window.location);
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.set('sort', sortBy);
            window.location.href = url.toString();
        });

        document.getElementById('sortSelect').addEventListener('change', function() {
            // Reload page with sort parameter
            const sortBy = this.value;
            const searchTerm = document.getElementById('searchInput').value;
            const url = new URL(window.location);
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            }
            url.searchParams.set('sort', sortBy);
            window.location.href = url.toString();
        });

        // Dropdown de tema
        function toggleThemeDropdown() {
            const dd = document.getElementById('theme-dropdown');
            dd.classList.toggle('hidden');
        }

        // Aplica o tema em todos os elementos principais
        function applyTheme(theme) {
            // Body
            document.body.classList.remove('theme-body-light', 'theme-body-dark', 'theme-body-colorful');
            // Header/Footer
            document.getElementById('main-header').classList.remove('theme-header-light', 'theme-header-dark', 'theme-header-colorful');
            document.getElementById('main-footer').classList.remove('theme-footer-light', 'theme-footer-dark', 'theme-footer-colorful');
            // Cards
            const cards = [document.getElementById('upload-card'), document.getElementById('files-card'), document.getElementById('tokens'), document.getElementById('files-table-wrapper'), document.getElementById('files-grid-wrapper')];
            cards.forEach(card => { if(card) card.classList.remove('theme-card-light', 'theme-card-dark', 'theme-card-colorful'); });
            // Modais
            document.querySelectorAll('.modal-content').forEach(modal => {
                modal.classList.remove('theme-modal-light', 'theme-modal-dark', 'theme-modal-colorful');
            });
            // Adiciona a classe correta ao modal de token gerado (caso esteja aberto)
            var tokenModalContent = document.getElementById('token-modal-content');
            if (tokenModalContent) {
                tokenModalContent.classList.remove('theme-modal-light', 'theme-modal-dark', 'theme-modal-colorful');
            }

            if (theme === 'dark') {
                document.body.classList.add('theme-body-dark');
                document.getElementById('main-header').classList.add('theme-header-dark');
                document.getElementById('main-footer').classList.add('theme-footer-dark');
                cards.forEach(card => { if(card) card.classList.add('theme-card-dark'); });
                document.querySelectorAll('.modal-content').forEach(modal => {
                    modal.classList.add('theme-modal-dark');
                });
                if (tokenModalContent) tokenModalContent.classList.add('theme-modal-dark');
                document.getElementById('theme-btn-label').innerHTML = '<i class="fas fa-moon"></i> Escuro';
            } else if (theme === 'colorful') {
                document.body.classList.add('theme-body-colorful');
                document.getElementById('main-header').classList.add('theme-header-colorful');
                document.getElementById('main-footer').classList.add('theme-footer-colorful');
                cards.forEach(card => { if(card) card.classList.add('theme-card-colorful'); });
                document.querySelectorAll('.modal-content').forEach(modal => {
                    modal.classList.add('theme-modal-colorful');
                });
                if (tokenModalContent) tokenModalContent.classList.add('theme-modal-colorful');
                document.getElementById('theme-btn-label').innerHTML = '<i class="fas fa-palette"></i> Colorido';
            } else {
                document.body.classList.add('theme-body-light');
                document.getElementById('main-header').classList.add('theme-header-light');
                document.getElementById('main-footer').classList.add('theme-footer-light');
                cards.forEach(card => { if(card) card.classList.add('theme-card-light'); });
                document.querySelectorAll('.modal-content').forEach(modal => {
                    modal.classList.add('theme-modal-light');
                });
                if (tokenModalContent) tokenModalContent.classList.add('theme-modal-light');
                document.getElementById('theme-btn-label').innerHTML = '<i class="fas fa-sun"></i> Claro';
            }
            // Notas administrativas
            var notasDiv = document.getElementById('notas');
            if (notasDiv) {
                notasDiv.classList.remove('theme-card-light', 'theme-card-dark', 'theme-card-colorful', 'nota-theme');
                // Remove tema das notas individuais
                notasDiv.querySelectorAll('.nota-card').forEach(function(card) {
                    card.classList.remove('theme-card-light', 'theme-card-dark', 'theme-card-colorful', 'nota-theme');
                });
                // Remove cor do título dinâmico
                var tituloDinamico = notasDiv.querySelector('.nota-titulo-dinamico');
                if (tituloDinamico) {
                    tituloDinamico.classList.remove('theme-card-light', 'theme-card-dark', 'theme-card-colorful');
                }
                if (theme === 'dark') {
                    notasDiv.classList.add('theme-card-dark', 'nota-theme');
                    notasDiv.querySelectorAll('.nota-card').forEach(function(card) {
                        card.classList.add('theme-card-dark', 'nota-theme');
                    });
                    if (tituloDinamico) tituloDinamico.classList.add('theme-card-dark');
                } else if (theme === 'colorful') {
                    notasDiv.classList.add('theme-card-colorful', 'nota-theme');
                    notasDiv.querySelectorAll('.nota-card').forEach(function(card) {
                        card.classList.add('theme-card-colorful', 'nota-theme');
                    });
                    if (tituloDinamico) tituloDinamico.classList.add('theme-card-colorful');
                } else {
                    notasDiv.classList.add('theme-card-light', 'nota-theme');
                    notasDiv.querySelectorAll('.nota-card').forEach(function(card) {
                        card.classList.add('theme-card-light', 'nota-theme');
                    });
                    if (tituloDinamico) tituloDinamico.classList.add('theme-card-light');
                }
            }
        }

        // Define o tema e salva
        function setTheme(theme) {
            localStorage.setItem('theme', theme);
            applyTheme(theme);
            document.getElementById('theme-dropdown').classList.add('hidden');
        }

        // Sincroniza o tema entre abas
        window.addEventListener('storage', function(e) {
            if (e.key === 'theme') {
                applyTheme(e.newValue);
            }
        });

        // Inicialização do tema ao carregar
        (function() {
            let theme = localStorage.getItem('theme');
            if (!theme) {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            applyTheme(theme);
        })();

        // Download nota como arquivo .txt
        function downloadNota(idx) {
            // Busca o conteúdo da nota na ordem reversa (igual ao foreach)
            const notas = <?php echo json_encode(array_reverse($notas)); ?>;
            const nota = notas[idx];
            if (!nota) return;
            const conteudo = `Título: ${nota.titulo}\nData: ${nota.data}\nAutor: ${nota.autor}\n\n${nota.conteudo}`;
            const blob = new Blob([conteudo], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = (nota.titulo || 'nota') + '.txt';
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }, 100);
        }
    </script>
</body>
</html>