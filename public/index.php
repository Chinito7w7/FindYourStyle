<?php

require_once '../includes/dbcon.php'; // Conexión a la base de datos
require_once '../includes/auth.php'; // Verificar si el usuario está logueado

// Obtener los locales
$query = "SELECT * FROM locals";
$stmt = $conn->prepare($query);
$stmt->execute();
$locals = $stmt->fetchAll();

// Obtener información del usuario actual
$user_id = $_SESSION['user_id'] ?? null;
$query_user = "SELECT * FROM users WHERE id = :user_id";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bindParam(':user_id', $user_id);
$stmt_user->execute();
$user = $stmt_user->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Your Style</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/index_dash.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
    <!-- Contenedor del logo y el nombre de la página -->
    <div class="logo-container">
        <img src="../assets/img/logo.png" alt="Logo" class="logo">
        <h3>Find Your Style</h3>
    </div>

    <!-- Botones en la parte derecha de la navbar -->
    <div class="navbar-right">
        <!-- Mostrar el botón "Ver mi local" si el usuario tiene un local -->
        <?php if ($user && $user['is_local_owner']): ?>
            <?php
            // Obtener el local del usuario
            $query_local = "SELECT id FROM locals WHERE owner_id = :user_id";
            $stmt_local = $conn->prepare($query_local);
            $stmt_local->bindParam(':user_id', $user_id);
            $stmt_local->execute();
            $local = $stmt_local->fetch();
            ?>
            <?php if ($local): ?>
                <a href="local.php?id=<?php echo htmlspecialchars($local['id']); ?>" class="view-local-button">Ver mi local</a>
                <a href="owner_notifications.php" class="btn-notifications">Notificaciones</a>
            <?php endif; ?>
        <?php else: ?>
            <!-- Mostrar el botón de agregar local si el usuario no es propietario de un local -->
            <a href="add_local.php" class="add-local-btn">Agregar Local</a>
        <?php endif; ?>
        <a href="logout.php" class="logout-button">Cerrar sesión</a>
    </div>
    </nav>
    <div class="search-bar-container">
        <form id="searchForm" onsubmit="return false;">
            <input type="text" id="searchInput" placeholder="Buscar locales..." />
        </form>
    </div>
    <!-- Contenido de la página principal -->
    <div class="content">
        <h1>Locales Disponibles</h1>
        <p>Aquí puedes explorar los locales registrados y reservar tus citas.</p>

        <!-- Grid para mostrar locales -->
        <div class="local-grid">
        <?php foreach ($locals as $local): ?>
            <div class="local-card">
                <img src="../uploads/locals/<?php echo htmlspecialchars($local['main_photo']); ?>" alt="Foto de <?php echo htmlspecialchars($local['name']); ?>">
                <h2><?php echo htmlspecialchars($local['name']); ?></h2>
                <p>Ubicación: <?php echo htmlspecialchars($local['location']); ?></p>
                <p>Horario: <?php echo htmlspecialchars($local['opening_time'] . ' - ' . $local['closing_time']); ?></p>
                <a href="local.php?id=<?php echo htmlspecialchars($local['id']); ?>" class="view-local-button">Ver Local</a>
            </div>
        <?php endforeach; ?>
    </div>

    </div>
    
    <script src="../js/search.js"></script>
    <script src="../js/dropMenu.js"></script>
</body>
</html>
