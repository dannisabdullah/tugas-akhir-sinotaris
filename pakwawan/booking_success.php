<?php
session_start();
include 'db.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: log.php');
    exit;
}

$userId = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedServices = $_POST['layanan'] ?? [];
    $day = $_POST['day'] ?? '';
    $timeSlot = $_POST['time'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if ($userId && !empty($selectedServices) && $day && $timeSlot) {
        try {
            // Insert booking
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, day, time_slot, notes) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $day, $timeSlot, $notes]);
            $bookingId = $pdo->lastInsertId();

            // Insert booking_services
            $insertServiceStmt = $pdo->prepare("SELECT id FROM services WHERE name = ?");
            $insertBookingServiceStmt = $pdo->prepare("INSERT INTO booking_services (booking_id, service_id) VALUES (?, ?)");

            foreach ($selectedServices as $serviceName) {
                $insertServiceStmt->execute([$serviceName]);
                $service = $insertServiceStmt->fetch();
                if ($service) {
                    $insertBookingServiceStmt->execute([$bookingId, $service['id']]);
                }
            }

            // Redirect to a success page or show success message
            header('Location: index.php?booking=success');
            exit;
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan saat memproses reservasi: " . $e->getMessage();
        }
    } else {
        $error = "Data reservasi tidak lengkap.";
    }
} else {
    $error = "Invalid request method.";
}
?>
<?php include 'header.php'; ?>
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md mx-auto mt-16">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Booking Status</h2>
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php else: ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            Reservasi berhasil dilakukan. Terima kasih!
        </div>
    <?php endif; ?>
    <div class="text-center">
        <a href="index.php" class="inline-block mt-4 bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">Back to Home</a>
    </div>
</div>
<?php include 'footer.php'; ?>
