<?php
require_once 'config.php';

$files = getFiles();
$searchTerm = $_GET['search'] ?? '';
$sortBy = $_GET['sort'] ?? 'newest';

// Filter files based on search
if (!empty($searchTerm)) {
    $files = array_filter($files, function($file) use ($searchTerm) {
        return stripos($file['name'], $searchTerm) !== false ||
               stripos($file['type'], $searchTerm) !== false ||
               stripos($file['uploaded_by'], $searchTerm) !== false;
    });
}

// Sort files
usort($files, function($a, $b) use ($sortBy) {
    switch ($sortBy) {
        case 'newest':
            return strtotime($b['uploaded']) - strtotime($a['uploaded']);
        case 'oldest':
            return strtotime($a['uploaded']) - strtotime($b['uploaded']);
        case 'name_asc':
            return strcasecmp($a['name'], $b['name']);
        case 'name_desc':
            return strcasecmp($b['name'], $a['name']);
        case 'size_asc':
            return $a['size'] - $b['size'];
        case 'size_desc':
            return $b['size'] - $a['size'];
        default:
            return 0;
    }
});
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamanho</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enviado em</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Por</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        <?php if (empty($files)): ?>
            <tr>
                <td colspan="6" class="text-center py-12">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Nenhum arquivo disponível no momento</p>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($files as $file): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="file-icon <?php echo $file['type']; ?> mr-3">
                                <i class="fas <?php echo $file['type'] === 'exe' ? 'fa-cog' : 'fa-file-archive'; ?>"></i>
                            </div>
                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($file['name']); ?></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $file['type'] === 'exe' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                            <?php echo strtoupper($file['type']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo formatFileSize($file['size']); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo date('d/m/Y', strtotime($file['uploaded'])); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo htmlspecialchars($file['uploaded_by']); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <?php if ($currentUser['role'] === 'admin'): ?>
                            <div class="flex items-center space-x-2">
                                <a href="download.php?id=<?php echo $file['id']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">Download</a>
                                <button type="button" onclick="copyDirectLinkAdmin('<?php echo $file['id']; ?>')" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded transition">Copiar Link</button>
                                <button type="button" onclick="showPowershellAdmin('<?php echo $file['id']; ?>', '<?php echo htmlspecialchars(addslashes($file['name'])); ?>')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition">PowerShell</button>
                                <button type="button" onclick="openRenameModal('<?php echo $file['id']; ?>', '<?php echo htmlspecialchars(addslashes($file['name'])); ?>')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded transition">Renomear</button>
                                <button type="button" onclick="openDeleteModal('<?php echo $file['id']; ?>', '<?php echo htmlspecialchars(addslashes($file['name'])); ?>')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition">Excluir</button>
                                <button type="button" onclick="openTokenModal('<?php echo $file['id']; ?>', '<?php echo htmlspecialchars(addslashes($file['name'])); ?>')" class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded transition">Gerar Token</button>
                            </div>
                        <?php else: ?>
                            <form method="GET" action="download.php" class="flex items-center space-x-2" onsubmit="return false;">
                                <input type="hidden" name="id" value="<?php echo $file['id']; ?>">
                                <input type="text" name="token" placeholder="Token" required class="border rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" style="width:120px;" id="token-input-<?php echo $file['id']; ?>">
                                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition"
                                    onclick="downloadWithToken('<?php echo $file['id']; ?>')">Download</button>
                                <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded transition"
                                    onclick="copyDirectLink('<?php echo $file['id']; ?>')">Copiar Link</button>
                                <button type="button" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition"
                                    onclick="showPowershell('<?php echo $file['id']; ?>', '<?php echo htmlspecialchars(addslashes($file['name'])); ?>')">PowerShell</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<script>
function downloadWithToken(fileId) {
    const token = document.getElementById('token-input-' + fileId).value.trim();
    if (!token) {
        alert('Informe o token!');
        return;
    }
    window.location.href = 'download.php?id=' + encodeURIComponent(fileId) + '&token=' + encodeURIComponent(token);
}

function copyDirectLink(fileId) {
    const token = document.getElementById('token-input-' + fileId).value.trim();
    if (!token) {
        alert('Informe o token!');
        return;
    }
    const url = window.location.origin + window.location.pathname.replace('index.php', '') +
        'download.php?id=' + encodeURIComponent(fileId) + '&token=' + encodeURIComponent(token);
    navigator.clipboard.writeText(url).then(() => {
        alert('Link copiado!');
    });
}

function showPowershell(fileId, fileName) {
    const token = document.getElementById('token-input-' + fileId).value.trim();
    if (!token) {
        alert('Informe o token!');
        return;
    }
    const url = window.location.origin + window.location.pathname.replace('index.php', '') +
        'download.php?id=' + encodeURIComponent(fileId) + '&token=' + encodeURIComponent(token);
    const ps = `Invoke-WebRequest -Uri "${url}" -OutFile "${fileName}"`;
    navigator.clipboard.writeText(ps).then(() => {
        alert('Comando PowerShell copiado!');
    });
}

// Funções para admin (não precisa de token)
function copyDirectLinkAdmin(fileId) {
    const url = window.location.origin + window.location.pathname.replace('index.php', '') +
        'download.php?id=' + encodeURIComponent(fileId);
    navigator.clipboard.writeText(url).then(() => {
        alert('Link copiado!');
    });
}

function showPowershellAdmin(fileId, fileName) {
    const url = window.location.origin + window.location.pathname.replace('index.php', '') +
        'download.php?id=' + encodeURIComponent(fileId);
    const ps = `Invoke-WebRequest -Uri "${url}" -OutFile "${fileName}"`;
    navigator.clipboard.writeText(ps).then(() => {
        alert('Comando PowerShell copiado!');
    });
}
</script>