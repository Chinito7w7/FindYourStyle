<?php
require_once '../includes/auth.php';  // Verificar si el usuario está logueado
require_once '../includes/dbcon.php'; // Conexión a la base de datos

// Verificar si el usuario posee un local
$user_id = $_SESSION['user_id'] ?? null;
$query = "SELECT id FROM locals WHERE owner_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$local = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirigir si el usuario es dueño de un local
if ($local) {
    header("Location: index.php"); // Redirige al index si es dueño
    exit();
}

// Obtener las reservas del usuario
$query = "SELECT reservations.id, reservations.reservation_time, locals.name AS local_name, services.name AS service_name
          FROM reservations
          JOIN locals ON reservations.local_id = locals.id
          JOIN services ON reservations.service_id = services.id
          WHERE reservations.user_id = :user_id
          ORDER BY reservations.reservation_time DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="../assets/css/my_reservations.css">
    <link rel="shortcut icon" href="../assets/img/logo.png" />
</head>
<body>
    <div class="container">
        <h2>Mis Reservas</h2>
        <?php if (!empty($reservations)): ?>
            <ul class="reservation-list">
                <?php foreach ($reservations as $reservation): ?>
                    <li class="reservation-item">
                        <strong>Local:</strong> <?= htmlspecialchars($reservation['local_name']) ?><br>
                        <strong>Servicio:</strong> <?= htmlspecialchars($reservation['service_name']) ?><br>
                        <strong>Horario:</strong> <?= htmlspecialchars($reservation['reservation_time']) ?><br>
                        <form action="../process/cancel_reservation.php" method="POST" class="cancel-form">
                            <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($reservation['id']) ?>">
                            <button type="submit" name="cancel" class="cancel-button">Cancelar Reserva</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No tienes reservas pendientes.</p>
        <?php endif; ?>

        <!-- Botón Volver a la página principal -->
        <a href="../public/index.php" class="back-button">Volver</a>
    </div>
</body>
</html>

