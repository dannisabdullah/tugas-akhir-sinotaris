<?php
require_once '../admin_middleware.php';
checkAdminAccess();
include '../db.php';

// Handle form submission for adding new service
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' && isset($_POST['name'], $_POST['description'])) {
            try {
                $stmt = $pdo->prepare("INSERT INTO services (name, description) VALUES (?, ?)");
                if ($stmt->execute([$_POST['name'], $_POST['description']])) {
                    $_SESSION['success_message'] = "Layanan berhasil ditambahkan";
                } else {
                    $_SESSION['error_message'] = "Gagal menambahkan layanan";
                }
            } catch (PDOException $e) {
                $_SESSION['error_message'] = "Gagal menambahkan layanan: " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'delete' && isset($_POST['service_id'])) {
            try {
                $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
                if ($stmt->execute([$_POST['service_id']])) {
                    $_SESSION['success_message'] = "Layanan berhasil dihapus";
                } else {
                    $_SESSION['error_message'] = "Gagal menghapus layanan";
                }
            } catch (PDOException $e) {
                $_SESSION['error_message'] = "Gagal menghapus layanan: " . $e->getMessage();
            }
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all services
$stmt = $pdo->query("SELECT * FROM services ORDER BY name");
$services = $stmt->fetchAll();

$title = "Kelola Layanan - Admin";
include '../header.php';
include '../navbar.php';
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-900">Kelola Layanan</h1>
            <p class="mt-2 text-gray-600">Tambah atau hapus layanan notaris</p>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="mb-4 p-4 rounded-md bg-green-50 text-green-800">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="mb-4 p-4 rounded-md bg-red-50 text-red-800">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Add New Service Form -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="p-6">
                <h2 class="text-xl font-medium text-gray-900 mb-4">Tambah Layanan Baru</h2>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="add">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Layanan</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Tambah Layanan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- List of Services -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-medium text-gray-900 mb-4">Daftar Layanan</h2>
                <?php if (empty($services)): ?>
                    <p class="text-gray-500">Belum ada layanan yang tersedia.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Layanan</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($services as $service): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= htmlspecialchars($service['name']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <?= htmlspecialchars($service['description']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <form method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus layanan ini?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
