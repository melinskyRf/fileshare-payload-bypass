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

<div id="filesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (empty($files)): ?>
        <div class="col-span-full text-center py-12">
            <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Nenhum arquivo disponível no momento</p>
        </div>
    <?php else: ?>
        <?php foreach ($files as $file): ?>
            <div class="file-card bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="file-icon <?php echo $file['type']; ?>">
                        <i class="fas <?php echo $file['type'] === 'exe' ? 'fa-cog' : 'fa-file-archive'; ?>"></i>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $file['type'] === 'exe' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                        <?php echo strtoupper($file['type']); ?>
                    </span>
                </div>
                <h3 class="font-medium text-gray-900 mb-2 truncate" title="<?php echo htmlspecialchars($file['name']); ?>">
                    <?php echo htmlspecialchars($file['name']); ?>
                </h3>
                <div class="text-sm text-gray-500 mb-4">
                    <p><?php echo formatFileSize($file['size']); ?></p>
                    <p>Por <?php echo htmlspecialchars($file['uploaded_by']); ?></p>
                    <p><?php echo date('d/m/Y', strtotime($file['uploaded'])); ?></p>
                </div>
                <div class="flex justify-between items-center">
                    <?php if ($currentUser['role'] === 'admin'): ?>
                        <button onclick="openTokenModal(<?php echo $file['id']; ?>, '<?php echo htmlspecialchars($file['name'], ENT_QUOTES); ?>')" class="gradient-bg text-white px-4 py-2 rounded-full text-sm font-medium hover:opacity-90">
                            <i class="fas fa-key mr-1"></i> Gerar Token
                        </button>
                        <div class="flex space-x-2">
                            <button onclick="openRenameModal(<?php echo $file['id']; ?>, '<?php echo htmlspecialchars($file['name'], ENT_QUOTES); ?>')" class="text-yellow-600 hover:text-yellow-900 action-btn">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="openDeleteModal(<?php echo $file['id']; ?>, '<?php echo htmlspecialchars($file['name'], ENT_QUOTES); ?>')" class="text-red-600 hover:text-red-900 action-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="text-center w-full">
                            <p class="text-gray-500 text-sm">Solicite token ao admin para download</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>