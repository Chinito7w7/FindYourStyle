<?php
session_start();
require_once '../includes/dbcon.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Obtener la lista de locales
$query = "SELECT * FROM locals";
$stmt = $conn->prepare($query);
$stmt->execute();
$locals = $stmt->fetchAll();

// Si se suspende o elimina un local
if (isset($_POST['suspend'])) {
    $local_id = $_POST['local_id'];
    $query_suspend = "UPDATE locals SET suspended = 1 WHERE id = :local_id";
    $stmt_suspend = $conn->prepare($query_suspend);
    $stmt_suspend->bindParam(':local_id', $local_id);
    $stmt_suspend->execute();
} elseif (isset($_POST['delete'])) {
    $local_id = $_POST['local_id'];

    // Obtener el ID del dueño (owner) del local antes de eliminarlo
    $query_owner = "SELECT owner_id FROM locals WHERE id = :local_id";
    $stmt_owner = $conn->prepare($query_owner);
    $stmt_owner->bindParam(':local_id', $local_id);
    $stmt_owner->execute();
    $owner = $stmt_owner->fetch(PDO::FETCH_ASSOC);

    if ($owner) {
        $owner_id = $owner['owner_id'];

        // Eliminar el local de la base de datos
        $query_delete_local = "DELETE FROM locals WHERE id = :local_id";
        $stmt_delete_local = $conn->prepare($query_delete_local);
        $stmt_delete_local->bindParam(':local_id', $local_id);
        $stmt_delete_local->execute();

        // Actualizar el campo is_local_owner a 0 para que el dueño pueda agregar otro local
        $query_update_owner = "UPDATE users SET is_local_owner = 0 WHERE id = :owner_id";
        $stmt_update_owner = $conn->prepare($query_update_owner);
        $stmt_update_owner->bindParam(':owner_id', $owner_id);
        $stmt_update_owner->execute();
    } else {
        echo "No se encontró el local.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Locales</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
</head>
<body>
    <header>
    <div class="logo-container">
            <img src="../assets/img/logo.png" alt="Logo" class="logo">
            <span class="page-name">Find Your Style - Administrador</span>
        </div>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Volver</a></li>
                <li><a href="admin_peticiones.php">Peticiones</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Locales</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locals as $local): ?>
                <tr>
                    <td><?php echo $local['id']; ?></td>
                    <td><?php echo $local['name']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="local_id" value="<?php echo $local['id']; ?>">
                            <button type="submit" name="suspend">Suspender</button>
                            <button type="submit" name="delete">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
