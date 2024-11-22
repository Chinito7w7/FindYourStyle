<?php
require_once '../includes/dbcon.php'; // Conexión a la base de datos
require_once '../includes/auth.php';  // Verificación de usuario autenticado

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID de la reserva desde el formulario
    $reservation_id = $_POST['reservation_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if ($reservation_id && $user_id) {
        // Eliminar la reserva de la base de datos
        $query = "DELETE FROM reservations WHERE id = :reservation_id AND user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':reservation_id', $reservation_id);
        $stmt->bindParam(':user_id', $user_id);
        
        // Ejecutar la eliminación
        if ($stmt->execute()) {
            // Redirigir de nuevo a la página de "Mis Reservas" después de cancelar
            header('Location: ../public/my_reservations.php');
            exit();
        } else {
            echo "Error al cancelar la reserva. Inténtalo nuevamente.";
        }
    } else {
        echo "ID de reserva inválido o no tienes permiso.";
    }
}
?>
