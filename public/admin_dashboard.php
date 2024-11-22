<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo("error");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link rel="shortcut icon" href="../assets/img/logo.png" />
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../assets/img/logo.png" alt="Logo" class="logo">
            <span class="page-name">Find Your Style - Administrador</span>
        </div>
        <nav>
            <ul>
                <li><a href="admin_locales.php">Locales</a></li>
                <li><a href="admin_peticiones.php">Peticiones</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Bienvenido al Panel de Administración</h1>
        <!-- Aquí se puede agregar contenido o estadísticas sobre locales/peticiones -->
    </main>
</body>
</html>
