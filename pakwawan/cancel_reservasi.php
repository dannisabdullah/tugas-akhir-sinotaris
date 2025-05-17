<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: log.php');
    exit;
}

include 'db.php';

$reservation_id = $_GET['id'] ?? null;

if (!$reservation_id) {
    header('Location: reservasi_saya.php');
    exit;
}

try {
    // First check if the reservation belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->execute([$reservation_id, $_SESSION['user_id']]);
    $booking = $stmt->fetch();

    if ($booking) {
        // Only allow cancellation of pending or confirmed bookings
        if ($booking['status'] === 'pending' || $booking['status'] === 'confirmed') {
            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
                $stmt->execute([$reservation_id]);
                $pdo->commit();
            } catch (PDOException $e) {
                $pdo->rollBack();
                throw $e;
            }
        }
    }

    $_SESSION['message'] = "Reservasi berhasil dibatalkan";
    $_SESSION['message_type'] = "success";
} catch (PDOException $e) {
    $_SESSION['message'] = "Gagal membatalkan reservasi";
    $_SESSION['message_type'] = "error";
}

header('Location: reservasi_saya.php');
exit;
