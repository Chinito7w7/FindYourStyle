<?php
session_start();
require_once '../includes/dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $location = $_POST['location'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashear la contraseña

    $query = "INSERT INTO users (name, surname, location, email, password) VALUES (:name, :surname, :location, :email, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página de inicio de sesión o mostrar un mensaje de éxito
        header("Location: login.php?register=success");
        exit();
    } else {
        // Manejo de errores
        $error = "Error al registrarse. Inténtalo de nuevo.";
        echo "<script>alert('$error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Find Your Style</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="shortcut icon" href="../assets/img/logo.png" />
</head>
<body>
    <main>
        <div class="main_container">
            <div class="back_box">
                <div>
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para acceder a los mejores locales de belleza.</p>
                    <button onclick="location.href='login.php'">Iniciar Sesión</button>
                </div>
            </div>
            <div class="login_register_container">
                <form action="register.php" method="POST" class="form_register">
                    <h2>Registrarse</h2>
                    <input type="text" name="name" placeholder="Nombre" required>
                    <input type="text" name="surname" placeholder="Apellido" required>
                    <input type="text" name="location" placeholder="Ubicación" required>
                    <input type="email" name="email" placeholder="Correo Electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit">Registrarse</button>
                </form>
            </div>
        </div>
    </main>

</body>
</html>
