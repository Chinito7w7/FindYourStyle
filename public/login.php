<?php
session_start();
require_once '../includes/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario de login
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta a la base de datos para obtener el usuario
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    // Verificar si el usuario existe y la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) {
        // Agregar mensajes de depuración
        echo "Usuario autenticado.<br>";
        echo "is_admin: " . $user['is_admin'] . "<br>";

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];

        if ($user['is_admin'] == 1) {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        // Mostrar error si las credenciales son incorrectas
        $error = "Email o contraseña incorrectos.";
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Find Your Style</title>
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
                    <h3>¿Nuevo aquí?</h3>
                    <p>Regístrate y encuentra los mejores locales de belleza.</p>
                    <button onclick="location.href='register.php'">Registrarse</button>
                </div>
            </div>
            <div class="login_register_container">
                <form action="login.php" method="POST" class="form_login">
                    <h2>Iniciar Sesión</h2>
                    <input type="email" name="email" placeholder="Correo Electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit">Entrar</button>
                </form>
            </div>
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
