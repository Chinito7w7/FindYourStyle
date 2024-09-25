<?php
require_once '../includes/auth.php';  // Verificar si el usuario está logueado
require_once '../includes/dbcon.php'; // Conexión a la base de datos

// Verificar si el usuario ya es propietario de un local
$user_id = $_SESSION['user_id']; // Asumimos que el ID del usuario está en la sesión
$query = "SELECT is_local_owner FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['is_local_owner']) {
    // Redirigir al dashboard o mostrar mensaje si ya es propietario de un local
    echo "<p>Ya tienes un local registrado. No puedes agregar otro local.</p>";
    echo "<a href='index.php'>Volver a la pagina principal</a>";
    exit();
}

// Si el usuario no es propietario, mostrar el formulario para agregar un local
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y procesar el formulario
    $local_name = $_POST['name'];
    $local_location = $_POST['location'];
    $opening_time = $_POST['opening_time']; // Obtiene el horario de apertura
    $closing_time = $_POST['closing_time']; // Obtiene el horario de cierre
    $main_photo = $_FILES['main_photo']['name'];

    // Guardar la imagen en la carpeta de uploads
    $target_dir = "../uploads/locals/";
    $target_file = $target_dir . basename($main_photo);
    move_uploaded_file($_FILES['main_photo']['tmp_name'], $target_file);

    // Insertar solicitud de local en la tabla de peticiones
    $query = "INSERT INTO local_requests (owner_id, name, location, opening_time, closing_time, main_photo, status) 
              VALUES (:owner_id, :name, :location, :opening_time, :closing_time, :main_photo, 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':owner_id', $user_id);
    $stmt->bindParam(':name', $local_name);
    $stmt->bindParam(':location', $local_location);
    $stmt->bindParam(':opening_time', $opening_time);
    $stmt->bindParam(':closing_time', $closing_time);
    $stmt->bindParam(':main_photo', $main_photo);
    $stmt->execute();
    
    // Mostrar mensaje de éxito
    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Local</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/add_local.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../assets/img/logo.png" alt="Logo" class="logo">
            <div class="page-name">Agregar Local</div>
        </div>
    </header>
    <main>
        <div class="add-local-container">
            <form action="add_local.php" method="POST" enctype="multipart/form-data">
                <h2>Agregar Local</h2>
                <label for="name">Nombre del Local:</label>
                <input type="text" id="name" name="name" required>

                <label for="location">Ubicación:</label>
                <input type="text" id="location" name="location" required>

                <label for="opening_time">Horario de Apertura:</label>
                <input type="time" id="opening_time" name="opening_time" required>

                <label for="closing_time">Horario de Cierre:</label>        
                <input type="time" id="closing_time" name="closing_time" required>


                <label for="main_photo">Foto Principal:</label>
                <input type="file" id="main_photo" name="main_photo" accept="image/*" required>

                <button onclick="alertaSolicitud()" type="submit">Enviar Solicitud</button>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Find Your Style</p>
        <div class="footer-links">
            <a href="#">Términos de Servicio</a>
            <a href="#">Política de Privacidad</a>
        </div>
    </footer>
</body>
</html>
