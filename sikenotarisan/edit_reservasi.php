<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: log.php');
    exit;
}

$title = "Edit Reservasi - Notaris R. Dewi Agung";
include 'header.php';
include 'navbar.php';
include 'db.php';

$reservation_id = $_GET['id'] ?? null;
$error = null;
$success = null;

if (!$reservation_id) {
    header('Location: reservasi_saya.php');
    exit;
}

// Fetch reservation details
$stmt = $pdo->prepare("
    SELECT b.*, GROUP_CONCAT(s.name) as layanan_names, GROUP_CONCAT(s.id) as service_ids
    FROM bookings b 
    LEFT JOIN booking_services bs ON b.id = bs.booking_id 
    LEFT JOIN services s ON bs.service_id = s.id 
    WHERE b.id = ? AND b.user_id = ?
    GROUP BY b.id
");
$stmt->execute([$reservation_id, $_SESSION['user_id']]);
$reservation = $stmt->fetch();

if (!$reservation) {
    header('Location: reservasi_saya.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = $_POST['day'] ?? '';
    $time_slot = $_POST['time_slot'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    if (empty($day) || empty($time_slot)) {
        $error = "Hari dan waktu harus diisi";
    } else {
        try {
            $pdo->beginTransaction();
            
            // Update the main booking
            $stmt = $pdo->prepare("UPDATE bookings SET day = ?, time_slot = ?, notes = ? WHERE id = ? AND user_id = ?");
            if ($stmt->execute([$day, $time_slot, $notes, $reservation_id, $_SESSION['user_id']])) {
                $success = "Reservasi berhasil diperbarui";
                $pdo->commit();
            } else {
                $error = "Gagal memperbarui reservasi";
                $pdo->rollBack();
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Terjadi kesalahan sistem";
        }
    }
}
?>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden fade-in">
            <div class="p-6 sm:p-8">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl font-notaris text-gray-900">Edit Reservasi</h1>
                    <a href="reservasi_saya.php" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?= htmlspecialchars($success) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Reservation Info Card -->
                <div class="mb-8 bg-indigo-50 rounded-lg p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($reservation['layanan_names'] ?? 'Layanan Notaris') ?></h3>
                            <p class="text-sm text-gray-500">Status: 
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    <?php echo match($reservation['status']) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-blue-100 text-blue-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    }; ?>">
                                    <?php echo match($reservation['status']) {
                                        'pending' => 'Menunggu Konfirmasi',
                                        'confirmed' => 'Terkonfirmasi',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        default => 'Status Tidak Diketahui'
                                    }; ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <form action="" method="POST" class="space-y-6">
                    <!-- Date -->
                    <div>
                        <label for="day" class="block text-sm font-medium text-gray-700">Hari</label>
                        <select name="day" id="day" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            <?php
                            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                            foreach ($days as $d) {
                                $selected = $reservation['day'] === $d ? 'selected' : '';
                                echo "<option value=\"$d\" $selected>$d</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Time -->
                    <div>
                        <label for="time_slot" class="block text-sm font-medium text-gray-700">Waktu</label>
                        <select name="time_slot" id="time_slot" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            <?php
                            $time_slots = [
                                '09:00 - 10:00', 
                                '10:00 - 11:00', 
                                '11:00 - 12:00', 
                                '13:00 - 14:00', 
                                '14:00 - 15:00', 
                                '15:00 - 16:00'
                            ];
                            foreach ($time_slots as $slot) {
                                $selected = $reservation['time_slot'] === $slot ? 'selected' : '';
                                echo "<option value=\"$slot\" $selected>$slot</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Keterangan Tambahan</label>
                        <textarea name="notes" id="notes" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Tambahkan keterangan jika diperlukan"><?= htmlspecialchars($reservation['notes'] ?? '') ?></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="reservasi_saya.php" 
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeIn 0.6s ease-out forwards;
}

@keyframes fadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<?php include 'footer.php'; ?>
