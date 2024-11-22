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

    // Manejar la actualización del local
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update'])) {
            $name = $_POST['local_name'];
            $image = $_FILES['local_image']['name'];

            // Si hay una nueva imagen, moverla a la carpeta correspondiente
            if ($image) {
                move_uploaded_file($_FILES['local_image']['tmp_name'], "../uploads/locals/" . $image);
            } else {
                $image = null; // Mantener la imagen existente si no se sube una nueva
            }

            $query_update_local = "UPDATE locals SET name = :name, main_photo = COALESCE(:image, main_photo) WHERE id = :local_id";
            $stmt_update_local = $conn->prepare($query_update_local);
            $stmt_update_local->bindParam(':local_id', $local_id);
            $stmt_update_local->bindParam(':name', $name);
            $stmt_update_local->bindParam(':image', $image);
            $stmt_update_local->execute();

            header("Location: local.php?id=" . $local_id);
            exit();
        }

        // Manejar el formulario de servicios
        if (isset($_POST['add_service'])) {
            $service_name = $_POST['name'];
            $price = $_POST['price'];

            $query_add_service = "INSERT INTO services (local_id, name, price) VALUES (:local_id, :name, :price)";
            $stmt_add_service = $conn->prepare($query_add_service);
            $stmt_add_service->bindParam(':local_id', $local_id);
            $stmt_add_service->bindParam(':name', $service_name);
            $stmt_add_service->bindParam(':price', $price);
            $stmt_add_service->execute();

            header("Location: local.php?id=" . $local_id);
            exit();
        }

        // Manejar la eliminación del local
        if (isset($_POST['delete_local'])) {
            // Primero, actualizar el campo is_local_owner a 0
            $query_update_owner_status = "UPDATE users SET is_local_owner = 0 WHERE id = :user_id";
            $stmt_update_owner_status = $conn->prepare($query_update_owner_status);
            $stmt_update_owner_status->bindParam(':user_id', $user_id);
            $stmt_update_owner_status->execute();
        
            // Luego, eliminar el local
            $query_delete_local = "DELETE FROM locals WHERE id = :local_id";
            $stmt_delete_local = $conn->prepare($query_delete_local);
            $stmt_delete_local->bindParam(':local_id', $local_id);
            $stmt_delete_local->execute();
        
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Local - Find Your Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/personalizar.css">
    <link rel="shortcut icon" href="../assets/img/logo.png" />
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../assets/img/logo.png" alt="Logo" class="logo">
            <span class="page-name">Find Your Style</span>
        </div>
        <nav>
            <a href="local.php?id=<?php echo htmlspecialchars($local_id); ?>" class="back-local-btn">Volver al local</a>
            <a href="index.php" class="back-home-btn">Volver a la página principal</a>
        </nav>
    </header>

    <main>
        <div class="customize-container">
            <h1>Personalizar Local</h1>

            <h2>Cambiar Nombre e Imagen del Local</h2>
            <form action="personalizar_local.php?id=<?php echo htmlspecialchars($local_id); ?>" method="POST" enctype="multipart/form-data">
                <label for="local_name">Nombre del Local:</label>
                <input type="text" id="local_name" name="local_name" required>
                
                <label for="local_image">Imagen del Local:</label>
                <input type="file" id="local_image" name="local_image">
                <br>
                <button type="submit" name="update">Actualizar Local</button>
            </form>
            
            <h2>Agregar Servicios</h2>
            <form action="personalizar_local.php?id=<?php echo htmlspecialchars($local_id); ?>" method="POST">
                <label for="name">Nombre del Servicio:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="price">Precio:</label>
                <input type="number" id="price" name="price" step="0.01" required>
                <br>
                <button type="submit" name="add_service">Agregar Servicio</button>
            </form>

            <h2>Eliminar Local</h2>
            <form action="personalizar_local.php?id=<?php echo htmlspecialchars($local_id); ?>" method="POST">
                <button type="submit" name="delete_local" onclick="return confirm('¿Estás seguro de que deseas eliminar este local? Esta acción no se puede deshacer.');">Eliminar Local</button>
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
