<?php
require_once '../admin_middleware.php';
checkAdminAccess();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    try {
        // Start transaction
        $pdo->beginTransaction();

        // Update booking status
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
        if (!$stmt->execute([$_POST['booking_id']])) {
            throw new Exception("Failed to update booking status");
        }

        // Get booking details
        $stmt = $pdo->prepare("
            SELECT b.*, u.name, u.wa, GROUP_CONCAT(s.name) as services
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            LEFT JOIN booking_services bs ON b.id = bs.booking_id
            LEFT JOIN services s ON bs.service_id = s.id
            WHERE b.id = ?
            GROUP BY b.id
        ");
        $stmt->execute([$_POST['booking_id']]);
        $booking = $stmt->fetch();

        if (!$booking) {
            throw new Exception("Booking not found");
        }

        // Commit transaction
        $pdo->commit();
        
        $_SESSION['success_message'] = "Reservasi berhasil dikonfirmasi untuk " . htmlspecialchars($booking['name']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }
}

// Redirect back to admin dashboard
header("Location: index.php");
exit();
