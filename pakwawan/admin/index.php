<?php
require_once '../admin_middleware.php';
checkAdminAccess();
include '../db.php';

$title = "Admin Dashboard";
include '../header.php';
include '../navbar.php';

// Fetch pending bookings
$stmt = $pdo->prepare("
    SELECT b.*, u.name as user_name, u.wa, GROUP_CONCAT(s.name) as services
    FROM bookings b 
    JOIN users u ON b.user_id = u.id
    LEFT JOIN booking_services bs ON b.id = bs.booking_id
    LEFT JOIN services s ON bs.service_id = s.id
    WHERE b.status = 'pending'
    GROUP BY b.id
    ORDER BY b.created_at DESC
");
$stmt->execute();
$pendingBookings = $stmt->fetchAll();
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-900">Dashboard Admin</h1>
            <p class="mt-2 text-gray-600">Kelola reservasi dan layanan notaris</p>
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

        <div class="mb-6">
            <a href="manage_service.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Kelola Layanan
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-medium text-gray-900 mb-4">Reservasi yang Menunggu Konfirmasi</h2>
                <?php if (empty($pendingBookings)): ?>
                    <p class="text-gray-500">Tidak ada reservasi yang menunggu konfirmasi.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($pendingBookings as $booking): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= htmlspecialchars($booking['user_name']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= htmlspecialchars($booking['wa']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= htmlspecialchars($booking['day']) ?><br>
                                            <?= htmlspecialchars($booking['time_slot']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <?= htmlspecialchars($booking['services']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <form method="POST" action="confirm_booking.php" class="inline">
                                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    Konfirmasi
                                                </button>
                                            </form>
                                            <form method="POST" action="reject_booking.php" class="inline">
                                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                                <button type="submit" 
                                                        onclick="return confirm('Yakin ingin membatalkan reservasi ini?')"
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Tolak
                                                </button>
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
