<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: log.php');
    exit;
}

$title = "Reservasi Anda - Notaris R. Dewi Agung";
include 'header.php';
include 'navbar.php';
include 'db.php';

// Fetch user's reservations with service details
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT b.*, GROUP_CONCAT(s.name) as layanan_names 
    FROM bookings b 
    LEFT JOIN booking_services bs ON b.id = bs.booking_id 
    LEFT JOIN services s ON bs.service_id = s.id 
    WHERE b.user_id = ? 
    GROUP BY b.id 
    ORDER BY b.created_at DESC
");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll();
?>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-notaris text-gray-900 mb-4">Reservasi Anda</h1>
            <p class="text-xl font-elegant text-gray-600">Kelola dan pantau status reservasi layanan notaris Anda</p>
        </div>

        <!-- Reservations Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($reservations)): ?>
            <div class="col-span-full text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Reservasi</h3>
                <p class="text-gray-500 mb-6">Anda belum memiliki reservasi layanan notaris</p>
                <a href="layanan.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Buat Reservasi
                </a>
            </div>
            <?php else: ?>
                <?php foreach ($reservations as $reservation): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 fade-in">
                        <div class="relative">
                            <!-- Status Badge -->
                            <?php
                            $statusClass = match($reservation['status'] ?? 'pending') {
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-blue-100 text-blue-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                            $statusText = match($reservation['status'] ?? 'pending') {
                                'pending' => 'Menunggu Konfirmasi',
                                'confirmed' => 'Terkonfirmasi',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                default => 'Status Tidak Diketahui'
                            };
                            ?>
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </div>

                            <!-- Service Icon -->
                            <div class="p-6 pb-0">
                                <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="p-6">
                                <!-- Reservation Details -->
                                <h3 class="text-xl font-semibold text-gray-900 mb-2"><?= htmlspecialchars($reservation['layanan_names'] ?? 'Layanan Notaris') ?></h3>
                                <div class="space-y-3">
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span><?= htmlspecialchars($reservation['day']) ?></span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span><?= htmlspecialchars($reservation['time_slot']) ?></span>
                                    </div>
                                    <div class="flex items-start text-gray-600">
                                        <svg class="w-5 h-5 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                                        </svg>
                                        <span class="line-clamp-2"><?= htmlspecialchars($reservation['notes'] ?? '') ?></span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Dibuat: <?= date('d F Y H:i', strtotime($reservation['created_at'])) ?></span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-6 flex space-x-3">
                                    <?php if ($reservation['status'] === 'pending' || $reservation['status'] === 'confirmed'): ?>
                                    <a href="edit_reservasi.php?id=<?= $reservation['id'] ?>" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                        Edit Reservasi
                                    </a>
                                    <button onclick="cancelReservation(<?= $reservation['id'] ?>)" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        Batalkan
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationDelay = '0.2s';
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.fade-in').forEach((el) => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
});

function cancelReservation(id) {
    if (confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')) {
        window.location.href = `cancel_reservasi.php?id=${id}`;
    }
}
</script>

<?php include 'footer.php'; ?>
