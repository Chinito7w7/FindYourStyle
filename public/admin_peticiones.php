<?php
session_start();
require_once '../includes/dbcon.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Obtener la lista de peticiones
$query = "SELECT * FROM local_requests WHERE status = 'pending'";
$stmt = $conn->prepare($query);
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Aprobar una solicitud
if (isset($_POST['approve'])) {
    $request_id = $_POST['request_id'];

    // Obtener los detalles de la solicitud
    $query_request = "SELECT * FROM local_requests WHERE id = :request_id";
    $stmt_request = $conn->prepare($query_request);
    $stmt_request->bindParam(':request_id', $request_id);
    $stmt_request->execute();
    $request = $stmt_request->fetch(PDO::FETCH_ASSOC);

    if ($request) {
        // Insertar el local en la tabla de locales
        $query_insert_local = "INSERT INTO locals (name, location, opening_time, closing_time, owner_id, main_photo) 
                               VALUES (:name, :location, :opening_time, :closing_time, :owner_id, :main_photo)";
        $stmt_insert_local = $conn->prepare($query_insert_local);
        $stmt_insert_local->bindParam(':name', $request['name']);
        $stmt_insert_local->bindParam(':location', $request['location']);
        $stmt_insert_local->bindParam(':opening_time', $request['opening_time']); // Cambiado de 'schedule' a 'opening_time'
        $stmt_insert_local->bindParam(':closing_time', $request['closing_time']); // Añadido 'closing_time'
        $stmt_insert_local->bindParam(':owner_id', $request['owner_id']);
        $stmt_insert_local->bindParam(':main_photo', $request['main_photo']);
        $stmt_insert_local->execute();

        // Actualizar el estado de la solicitud a 'approved'
        $query_update_request = "UPDATE local_requests SET status = 'approved' WHERE id = :request_id";
        $stmt_update_request = $conn->prepare($query_update_request);
        $stmt_update_request->bindParam(':request_id', $request_id);
        $stmt_update_request->execute();

        // Actualizar el usuario para que sea propietario del local
        $query_update_user = "UPDATE users SET is_local_owner = 1 WHERE id = :owner_id";
        $stmt_update_user = $conn->prepare($query_update_user);
        $stmt_update_user->bindParam(':owner_id', $request['owner_id']);
        $stmt_update_user->execute();
        header('Location: admin_peticiones.php');
        echo("local aprobado con exito");
        exit();
    } 
}

// Rechazar una solicitud
if (isset($_POST['reject'])) {
    $request_id = $_POST['request_id'];

    // Actualizar el estado de la solicitud a 'rejected'
    $query_update_request = "UPDATE local_requests SET status = 'rejected' WHERE id = :request_id";
    $stmt_update_request = $conn->prepare($query_update_request);
    $stmt_update_request->bindParam(':request_id', $request_id);
    $stmt_update_request->execute();

    echo "Solicitud rechazada.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peticiones de Locales</title>
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
                <li><a href="admin_locales.php">Locales</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Peticiones Pendientes</h1>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>Horario de Apertura</th>
                    <th>Horario de Cierre</th>
                    <th>Foto Principal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['name']); ?></td>
                    <td><?php echo htmlspecialchars($request['location']); ?></td>
                    <td><?php echo htmlspecialchars($request['opening_time']); ?></td>
                    <td><?php echo htmlspecialchars($request['closing_time']); ?></td>
                    <td><img src="../uploads/locals/<?php echo htmlspecialchars($request['main_photo']); ?>" alt="Foto Principal" width="100"></td>
                    <td>
                        <form action="admin_peticiones.php" method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" name="approve">Aprobar</button>
                        </form>
                        <form action="admin_peticiones.php" method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" name="reject">Rechazar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2024 Find Your Style</p>
    </footer>
</body>
</html>
