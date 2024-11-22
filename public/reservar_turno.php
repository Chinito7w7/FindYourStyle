<?php
require_once '../includes/auth.php';  // Verificar si el usuario está logueado
require_once '../includes/dbcon.php'; // Conexión a la base de datos

// Obtener el ID del local desde el URL
$local_id = $_GET['local_id'];

// Obtener los servicios que ofrece el local
$query = "SELECT id, name, price FROM services WHERE local_id = :local_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':local_id', $local_id);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener los horarios de apertura y cierre del local
$query = "SELECT opening_time, closing_time FROM locals WHERE id = :local_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':local_id', $local_id);
$stmt->execute();
$local = $stmt->fetch(PDO::FETCH_ASSOC);

// Procesar la reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service'];
    $reservation_time = $_POST['time']; // Esto incluye tanto la fecha como la hora
    $user_id = $_SESSION['user_id'];

    // Formatear el valor de 'time' para MySQL (en formato 'Y-m-d H:i:s')
    $formatted_reservation_time = date('Y-m-d H:i:s', strtotime($reservation_time));

    // Verificar que el horario no esté reservado
    $query = "SELECT COUNT(*) FROM reservations WHERE local_id = :local_id AND service_id = :service_id AND reservation_time = :reservation_time";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':local_id', $local_id);
    $stmt->bindParam(':service_id', $service_id);
    $stmt->bindParam(':reservation_time', $formatted_reservation_time);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // Insertar la reserva en la base de datos
        $query = "INSERT INTO reservations (local_id, user_id, service_id, reservation_time) VALUES (:local_id, :user_id, :service_id, :reservation_time)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':local_id', $local_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':service_id', $service_id);
        $stmt->bindParam(':reservation_time', $formatted_reservation_time);
        $stmt->execute();

        header("Location: index.php?success=Reserva realizada con éxito");
        exit();
    } else {
        echo "<p>Este horario ya está reservado. Por favor, selecciona otro horario.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Turno</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/reservar_turno.css">
    <link rel="shortcut icon" href="../assets/img/logo.png" />
</head>
<body>

    <div class="navbar">
        <div class="logo-container">
            <img src="../assets/img/logo.png" alt="Logo">
            <span class="page-name">Find Your Style</span>
        </div>
        <div>
            <a href="index.php" class="view-local-button">Volver</a>
        </div>
    </div>

    <div class="reservation-page">
        <div class="reservation-form">
            <h2>Reservar Turno</h2>
            <form action="reservar_turno.php?local_id=<?= $local_id ?>" method="POST">
                <label for="service">Selecciona un Servicio:</label>
                <select name="service" id="service" required>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['id'] ?>"><?= $service['name'] ?> - $<?= $service['price'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="time">Selecciona un Horario:</label>
                <input type="datetime-local" id="time" name="time" required>
                
                <button type="submit">Reservar Turno</button>
            </form>
        </div>
    </div>

    <footer>
        &copy; 2024 Find Your Style. Todos los derechos reservados.
    </footer>

</body>
</html>

