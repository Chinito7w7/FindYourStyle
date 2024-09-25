<?php
session_start();
require_once '../includes/dbcon.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Eliminar la reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $reservation_id = $_POST['reservation_id'];
    $local_id = $_POST['local_id'];

    // Eliminar la reserva de la base de datos
    $query_delete_reservation = "DELETE FROM reservations WHERE id = :reservation_id AND local_id = :local_id";
    $stmt_delete_reservation = $conn->prepare($query_delete_reservation);
    $stmt_delete_reservation->bindParam(':reservation_id', $reservation_id);
    $stmt_delete_reservation->bindParam(':local_id', $local_id);
    
    if ($stmt_delete_reservation->execute()) {
        header('Location: ../public/owner_notifications.php'); // Redirigir a la página de notificaciones después de eliminar
        exit;
    } else {
        echo "Error al eliminar la reserva.";
    }
}
?>
