<?php
require_once '../includes/auth.php';  // Verificar si el usuario está logueado
require_once '../includes/dbcon.php'; // Conexión a la base de datos

// Obtener el ID del local del owner
$user_id = $_SESSION['user_id'];
$query = "SELECT id FROM locals WHERE owner_id = :owner_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':owner_id', $user_id);
$stmt->execute();
$local = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener las reservas en orden de llegada
$query = "SELECT reservations.id, reservations.reservation_time, users.name AS customer_name, services.name AS service_name 
          FROM reservations 
          JOIN users ON reservations.user_id = users.id 
          JOIN services ON reservations.service_id = services.id 
          WHERE reservations.local_id = :local_id
          ORDER BY reservations.reservation_time DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':local_id', $local['id']);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones de Reservas</title>
    <link rel="stylesheet" href="../assets/css/notificaciones.css"> <!-- Enlazar el archivo CSS -->
</head>
<body>
    <div class="container">
        <h2>Notificaciones de Reservas</h2>
        <?php if (!empty($reservations)): ?>
            <ul class="reservation-list">
                <?php foreach ($reservations as $reservation): ?>
                    <li class="reservation-item">
                        <strong>Cliente:</strong> <?= htmlspecialchars($reservation['customer_name']) ?><br>
                        <strong>Servicio:</strong> <?= htmlspecialchars($reservation['service_name']) ?><br>
                        <strong>Horario:</strong> <?= htmlspecialchars($reservation['reservation_time']) ?><br>
                        <form action="../process/delete_reservation.php" method="POST" style="display: inline;">
                            <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                            <input type="hidden" name="local_id" value="<?= $local['id'] ?>">
                            <button type="submit" name="delete" class="delete-button">Eliminar Reserva</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No hay reservas pendientes.</p>
        <?php endif; ?>
    </div>
</body>
</html>
