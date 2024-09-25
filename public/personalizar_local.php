<?php
session_start();
require_once '../includes/dbcon.php'; // Conexión a la base de datos

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener el ID del local desde la URL
if (isset($_GET['id'])) {
    $local_id = $_GET['id'];

    // Verificar si el usuario es el propietario del local
    $query_owner = "SELECT owner_id FROM locals WHERE id = :local_id";
    $stmt_owner = $conn->prepare($query_owner);
    $stmt_owner->bindParam(':local_id', $local_id);
    $stmt_owner->execute();
    $local = $stmt_owner->fetch();

    // Comprobar si el usuario actual es el propietario del local
    if (!$local || $local['owner_id'] != $user_id) {
        echo "No tienes permiso para personalizar este local.";
        exit();
    }

    // Manejar el formulario de servicios
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        $query_add_service = "INSERT INTO services (local_id, name, description, price) VALUES (:local_id, :name, :description, :price)";
        $stmt_add_service = $conn->prepare($query_add_service);
        $stmt_add_service->bindParam(':local_id', $local_id);
        $stmt_add_service->bindParam(':name', $name);
        $stmt_add_service->bindParam(':description', $description);
        $stmt_add_service->bindParam(':price', $price);
        $stmt_add_service->execute();

        header("Location: local.php?id=" . $local_id);
        exit();
    }
} else {
    echo "ID del local no especificado.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalizar Local - Find Your Style</title>
    <link rel="stylesheet" href="../assets/css/personalizar.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../assets/img/logo.png" alt="Logo" class="logo">
            <span class="page-name">Find Your Style</span>
        </div>
        <a href="local.php?id=<?php echo htmlspecialchars($local_id); ?>" class="back-local-btn">Volver al local</a>
        <a href="index.php" class="back-home-btn">Volver a la página principal</a>
    </header>

    <main>
        <div class="customize-container">
            <h1>Agregar Servicios</h1>
            <form action="personalizar_local.php?id=<?php echo htmlspecialchars($local_id); ?>" method="POST">
                <label for="name">Nombre del Servicio:</label>
                <input type="text" id="name" name="name" required>
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
                <label for="price">Precio:</label>
                <input type="number" id="price" name="price" step="0.01" required>
                <button type="submit">Agregar Servicio</button>
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

